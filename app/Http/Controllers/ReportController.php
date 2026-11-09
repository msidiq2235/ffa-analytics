<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use App\Models\MatchSession;
use App\Models\Player;
use App\Models\Statistic;
use App\Models\VectorDirection;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    /* ===================== HALAMAN EXPORT LAPORAN ===================== */

    /**
     * Halaman utama Export Laporan (daftar pemain & pertandingan).
     */
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'player') === 'match' ? 'match' : 'player';

        $players = Player::with('club')
            ->when($request->query('name'), fn ($q, $name) => $q->where('name', 'like', '%' . $name . '%'))
            ->when($request->query('position'), fn ($q, $pos) => $q->where('position', $pos))
            ->when($request->query('jersey'), fn ($q, $jersey) => $q->where('jersey_number', $jersey))
            ->when($request->query('club'), fn ($q, $clubName) => $q->whereHas('club', fn ($c) => $c->where('name', $clubName)))
            ->orderBy('name')
            ->paginate(10, ['*'], 'players_page')
            ->withQueryString();

        $matches = MatchSession::orderByDesc('match_date')
            ->when($request->query('search'), fn ($q, $search) => $q->where('title', 'like', '%' . $search . '%')->orWhere('opponent', 'like', '%' . $search . '%'))
            ->paginate(10, ['*'], 'matches_page')
            ->withQueryString();

        $clubs = \App\Models\Club::orderBy('name')->get();

        return view('reports.index', compact('tab', 'players', 'matches', 'clubs'));
    }

    /* ===================== LAPORAN PERTANDINGAN ===================== */

    public function showMatchReport(MatchSession $match)
    {
        $match->load(['players' => function ($q) { $q->withPivot('position'); }]);

        $stats = Statistic::where('match_id', $match->id)
            ->selectRaw('player_id, SUM(goal) as total_goals, SUM(assist) as total_assists, SUM(shooting_on_target) as total_shots_target, SUM(shooting_off_target) as total_shots_off, SUM(successful_passes) as total_passes, SUM(tackles) as total_tackles')
            ->groupBy('player_id')->get()->keyBy('player_id');

        $teamStats = [
            'goals'   => $stats->sum('total_goals'),
            'shots'   => $stats->sum('total_shots_target') + $stats->sum('total_shots_off'),
            'passes'  => $stats->sum('total_passes'),
            'tackles' => $stats->sum('total_tackles'),
        ];

        $vectors = VectorDirection::where('match_id', $match->id)
            ->join('players', 'vector_directions.player_id', '=', 'players.id')
            ->select('vector_directions.*', 'players.name as player_name', 'players.jersey_number')
            ->get();

        return view('reports.show', compact('match', 'stats', 'teamStats', 'vectors'));
    }

    public function exportMatchPdf(MatchSession $match)
    {
        $match->load(['players' => function ($q) { $q->withPivot('position'); }]);

        $stats = Statistic::where('match_id', $match->id)
            ->selectRaw('player_id, SUM(goal) as total_goals, SUM(assist) as total_assists, SUM(shooting_on_target) as total_shots_target, SUM(successful_passes) as total_passes')
            ->groupBy('player_id')->get()->keyBy('player_id');

        $pdf = Pdf::loadView('reports.match-pdf', compact('match', 'stats'));

        return $pdf->stream('Match_Report_' . Str::slug($match->title ?? 'match') . '_' . $match->id . '.pdf');
    }

    /* ===================== LAPORAN PEMAIN ===================== */

    /**
     * Statistik satu pemain, dikelompokkan per pertandingan (key = match_id).
     */
    private function playerStatsPerMatch(int $playerId)
    {
        return Statistic::where('player_id', $playerId)
            ->selectRaw('match_id,
                COALESCE(SUM(goal),0) as total_goals,
                COALESCE(SUM(assist),0) as total_assists,
                COALESCE(SUM(shooting_on_target),0) as total_shots_target,
                COALESCE(SUM(shooting_off_target),0) as total_shots_off,
                COALESCE(SUM(successful_passes),0) as total_passes,
                COALESCE(SUM(tackles),0) as total_tackles')
            ->groupBy('match_id')
            ->get()
            ->keyBy('match_id');
    }

    /**
     * Semua pertandingan yang diikuti pemain (terbaru dulu), lewat relasi players().
     */
    private function playerMatches(int $playerId)
    {
        return MatchSession::whereHas('players', function ($q) use ($playerId) {
                $q->where('players.id', $playerId);
            })
            ->orderByDesc('match_date')
            ->get();
    }

    /** Musim berjalan Juli - Juni. Mengembalikan tahun mulai musim. */
    private function seasonStartYear($date): int
    {
        $d = Carbon::parse($date);
        return $d->month >= 7 ? $d->year : $d->year - 1;
    }

    private function seasonLabel(int $startYear): string
    {
        return sprintf('%02d/%02d', $startYear % 100, ($startYear + 1) % 100);
    }

    /** Nama lawan. Sesuaikan jika kolom Anda punya nama lain. */
    private function opponentOf($match): string
    {
        return $match->opponent
            ?? $match->opponent_name
            ?? $match->away_team
            ?? '-';
    }

    /**
     * Halaman Laporan Performa Pemain (tujuan ikon mata).
     */
    public function showPlayerReport(Player $player)
    {
        $player->load('club');

        $perMatch = $this->playerStatsPerMatch($player->id);
        $matches  = $this->playerMatches($player->id);

        $totals = (object) [
            'goals'   => (int) $perMatch->sum('total_goals'),
            'assists' => (int) $perMatch->sum('total_assists'),
            'matches' => $matches->count(),
        ];

        // Grafik 6 bulan terakhir (gol + assist per bulan)
        $chartLabels = [];
        $chartValues = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $chartLabels[] = $month->translatedFormat('M');
            $chartValues[] = (int) $matches
                ->filter(fn ($m) => Carbon::parse($m->match_date)->isSameMonth($month))
                ->sum(fn ($m) => ($perMatch[$m->id]->total_goals ?? 0) + ($perMatch[$m->id]->total_assists ?? 0));
        }

        // Radar 0-100 dari rata-rata per pertandingan
        $played = max($matches->count(), 1);
        $norm   = fn ($value, $target) => (int) min(100, round((($value / $played) / $target) * 100));

        $radar = [
            'Serangan'  => $norm($perMatch->sum('total_goals') + $perMatch->sum('total_shots_target'), 3),
            'Bertahan'  => $norm($perMatch->sum('total_tackles'), 6),
            'Operan'    => $norm($perMatch->sum('total_passes'), 40),
            'Dribbling' => 0,
            'Fisik'     => 0,
            'Mental'    => 0,
        ];

        // Riwayat musim (diturunkan dari tanggal pertandingan)
        $seasons = $matches
            ->groupBy(fn ($m) => $this->seasonStartYear($m->match_date))
            ->map(fn ($group, $year) => (object) [
                'start'   => (int) $year,
                'label'   => $this->seasonLabel((int) $year),
                'matches' => $group->count(),
            ])
            ->sortByDesc('start')
            ->values();

        $opponentOf = fn ($m) => $this->opponentOf($m);

        return view('reports.player', compact(
            'player', 'totals', 'chartLabels', 'chartValues',
            'radar', 'matches', 'seasons', 'opponentOf'
        ));
    }

    /**
     * PDF satu pemain pada satu pertandingan.
     */
    public function exportPlayerMatchPdf(Player $player, $matchId)
    {
        $match = MatchSession::findOrFail($matchId);

        $joined = $match->players()->where('players.id', $player->id)->exists();
        abort_if(!$joined, 404);

        $player->load('club');
        $stat     = $this->playerStatsPerMatch($player->id)->get($match->id);
        $opponent = $this->opponentOf($match);

        return Pdf::loadView('reports.player_pdf', compact('player', 'match', 'stat', 'opponent'))
            ->stream('Laporan_' . Str::slug($player->name) . '_Match_' . $match->id . '.pdf');
    }

    /**
     * PDF satu pemain untuk satu musim.
     */
    public function exportPlayerSeasonPdf(Player $player, int $season)
    {
        $player->load('club');

        $perMatch = $this->playerStatsPerMatch($player->id);
        $matches  = $this->playerMatches($player->id)
            ->filter(fn ($m) => $this->seasonStartYear($m->match_date) === $season)
            ->values();

        abort_if($matches->isEmpty(), 404);

        $totals = (object) [
            'goals'        => (int) $matches->sum(fn ($m) => $perMatch[$m->id]->total_goals ?? 0),
            'assists'      => (int) $matches->sum(fn ($m) => $perMatch[$m->id]->total_assists ?? 0),
            'shots_target' => (int) $matches->sum(fn ($m) => $perMatch[$m->id]->total_shots_target ?? 0),
            'passes'       => (int) $matches->sum(fn ($m) => $perMatch[$m->id]->total_passes ?? 0),
            'tackles'      => (int) $matches->sum(fn ($m) => $perMatch[$m->id]->total_tackles ?? 0),
        ];

        $seasonLabel = $this->seasonLabel($season);
        $opponentOf  = fn ($m) => $this->opponentOf($m);

        return Pdf::loadView('reports.player_season_pdf', compact(
            'player', 'matches', 'perMatch', 'totals', 'seasonLabel', 'opponentOf'
        ))->stream('Laporan_' . Str::slug($player->name) . '_Musim_' . $season . '.pdf');
    }

    /**
     * PDF Profil Umum Pemain.
     */
    public function exportPlayerProfilePdf($player)
    {
        $player = Player::with(['club'])->findOrFail($player);
        
        $perMatch = $this->playerStatsPerMatch($player->id);
        $matches  = $this->playerMatches($player->id);

        $totals = (object) [
            'goals'        => (int) $perMatch->sum('total_goals'),
            'assists'      => (int) $perMatch->sum('total_assists'),
            'matches'      => $matches->count(),
            'shots_target' => (int) $perMatch->sum('total_shots_target'),
            'passes'       => (int) $perMatch->sum('total_passes'),
            'tackles'      => (int) $perMatch->sum('total_tackles'),
        ];

        $opponentOf = fn ($m) => $this->opponentOf($m);

        return Pdf::loadView('reports.player_pdf', compact('player', 'matches', 'perMatch', 'totals', 'opponentOf'))
            ->stream('Laporan_Lengkap_' . Str::slug($player->name) . '.pdf');
    }
}
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
            ->when($request->query('search'), fn ($q, $search) => $q->where(function ($w) use ($search) {
                $w->where('title', 'like', '%' . $search . '%')
                  ->orWhere('opponent', 'like', '%' . $search . '%');
            }))
            ->paginate(10, ['*'], 'matches_page')
            ->withQueryString();

        $clubs = \App\Models\Club::orderBy('name')->get();

        return view('reports.index', compact('tab', 'players', 'matches', 'clubs'));
    }

    /* ===================== LAPORAN PERTANDINGAN ===================== */

    /**
     * Data bersama untuk halaman match & PDF match.
     */
    private function matchReportData(MatchSession $match): array
    {
        $match->load(['players' => function ($q) { $q->withPivot('position'); }]);

        $stats = Statistic::where('match_id', $match->id)
            ->selectRaw('player_id,
                COALESCE(SUM(goal),0) as total_goals,
                COALESCE(SUM(assist),0) as total_assists,
                COALESCE(SUM(shooting_on_target),0) as total_shots_target,
                COALESCE(SUM(shooting_off_target),0) as total_shots_off,
                COALESCE(SUM(successful_passes),0) as total_passes,
                COALESCE(SUM(tackles),0) as total_tackles')
            ->groupBy('player_id')->get()->keyBy('player_id');

        $teamStats = [
            'goals'   => (int) $stats->sum('total_goals'),
            'shots'   => (int) ($stats->sum('total_shots_target') + $stats->sum('total_shots_off')),
            'passes'  => (int) $stats->sum('total_passes'),
            'tackles' => (int) $stats->sum('total_tackles'),
        ];

        $vectors = VectorDirection::where('match_id', $match->id)
            ->join('players', 'vector_directions.player_id', '=', 'players.id')
            ->select('vector_directions.*', 'players.name as player_name', 'players.jersey_number')
            ->get();

        return compact('match', 'stats', 'teamStats', 'vectors');
    }

    public function showMatchReport(MatchSession $match)
    {
        return view('reports.match-detail', $this->matchReportData($match));
    }

    public function exportMatchPdf(MatchSession $match)
    {
        $data = $this->matchReportData($match);

        return Pdf::loadView('reports.match-pdf', $data)
            ->stream('Match_Report_' . Str::slug($match->title ?? 'match') . '_' . $match->id . '.pdf');
    }

    /* ===================== LAPORAN PEMAIN ===================== */

    /**
     * Statistik satu pemain, dikelompokkan per pertandingan (key = match_id).
     */
    private function playerStatsPerMatch(int $playerId)
    {
        $columns = [
            'goal',
            'shots',
            'shooting_on_target',
            'shooting_off_target',
            'penalty_goal',
            'shot_on_target_alternate',
            'assist',
            'chance_created',
            'successful_passes',
            'successful_crosses',
            'dribble',
            'successful_dribble',
            'unsuccessful_dribble',
            'tackles',
            'fouls_committed',
            'interceptions',
            'dribbled_past',
            'shots_faced',
            'goals_conceded',
            'clean_sheets',
            'saves',
            'penalty_saves',
            'high_claims',
            'punches',
            'sweeper_actions',
            'catches',
            'crosses_stopped',
            'accurate_goal_kicks',
            'unsuccessful_passes',
            'accurate_throws',
        ];
        $selects = array_map(fn ($column) => "COALESCE(SUM({$column}),0) as {$column}", $columns);

        return Statistic::where('player_id', $playerId)
            ->selectRaw(
                'match_id, ' . implode(', ', $selects) . ',
                COALESCE(SUM(goal),0) as total_goals,
                COALESCE(SUM(assist),0) as total_assists,
                COALESCE(SUM(shooting_on_target),0) as total_shots_target,
                COALESCE(SUM(shooting_off_target),0) as total_shots_off,
                COALESCE(SUM(successful_passes),0) as total_passes,
                COALESCE(SUM(tackles),0) as total_tackles'
            )
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
            ->with(['players' => function ($q) use ($playerId) {
                $q->where('players.id', $playerId)->withPivot('position');
            }])
            ->orderByDesc('match_date')
            ->get();
    }

    private function playerStatisticGroups(): array
    {
        return [
            'Menyerang' => [
                'goal' => 'Gol',
                'shots' => 'Total tembakan',
                'shooting_on_target' => 'Tembakan tepat sasaran',
                'shooting_off_target' => 'Tembakan tidak tepat sasaran',
                'penalty_goal' => 'Gol penalti',
                'shot_on_target_alternate' => 'Tembakan tepat sasaran (alternatif)',
                'assist' => 'Assist',
                'chance_created' => 'Peluang tercipta',
            ],
            'Umpan' => [
                'successful_passes' => 'Umpan berhasil',
                'successful_crosses' => 'Umpan silang berhasil',
            ],
            'Penguasaan Bola' => [
                'dribble' => 'Dribel',
                'successful_dribble' => 'Dribel berhasil',
                'unsuccessful_dribble' => 'Dribel gagal',
            ],
            'Bertahan' => [
                'tackles' => 'Tackle',
                'fouls_committed' => 'Pelanggaran',
                'interceptions' => 'Intersepsi',
                'dribbled_past' => 'Dilewati lawan',
            ],
            'Goalkeeping' => [
                'shots_faced' => 'Shots faced',
                'goals_conceded' => 'Goals conceded',
                'clean_sheets' => 'Clean sheets',
                'saves' => 'Saves',
                'penalty_saves' => 'Penalty saves',
                'high_claims' => 'High claims',
            ],
            'Aerial & Defensive' => [
                'punches' => 'Punches',
                'sweeper_actions' => 'Sweeper actions',
                'catches' => 'Catches',
                'crosses_stopped' => 'Crosses stopped',
            ],
            'Distribusi Kiper' => [
                'accurate_goal_kicks' => 'Tendangan gawang akurat',
                'unsuccessful_passes' => 'Umpan tidak berhasil',
                'accurate_throws' => 'Lemparan akurat',
            ],
        ];
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
     * Radar 0-100 dari rata-rata per pertandingan.
     * Dipakai bersama oleh halaman show dan semua PDF.
     */
    private function buildRadar($perMatch, int $matchCount): array
    {
        $played = max($matchCount, 1);
        $norm   = fn ($value, $target) => (int) min(100, round((($value / $played) / $target) * 100));

        return [
            'Serangan'  => $norm($perMatch->sum('total_goals') + $perMatch->sum('total_shots_target'), 3),
            'Bertahan'  => $norm($perMatch->sum('total_tackles'), 6),
            'Operan'    => $norm($perMatch->sum('total_passes'), 40),
            'Dribbling' => 0,
            'Fisik'     => 0,
            'Mental'    => 0,
        ];
    }

    private function playerContributionTrend($matches, $perMatch): array
    {
        $chartLabels = [];
        $chartValues = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $chartLabels[] = $month->translatedFormat('M');
            $chartValues[] = (int) $matches
                ->filter(fn ($match) => Carbon::parse($match->match_date)->isSameMonth($month))
                ->sum(fn ($match) => ($perMatch[$match->id]->total_goals ?? 0) + ($perMatch[$match->id]->total_assists ?? 0));
        }

        return compact('chartLabels', 'chartValues');
    }

    /**
     * Halaman Laporan Performa Pemain (tujuan ikon mata).
     */
    public function showPlayerReport(Player $player)
    {
        $player->load('club');

        $matches = $this->playerMatches($player->id);
        $perMatch = collect($this->playerStatsPerMatch($player->id)->all())
            ->only($matches->pluck('id')->all());
        $statisticGroups = $this->playerStatisticGroups();
        $statisticTotals = [];
        foreach ($statisticGroups as $group) {
            foreach (array_keys($group) as $field) {
                $statisticTotals[$field] = (int) $perMatch->sum($field);
            }
        }

        $totals = (object) [
            'goals'   => (int) $perMatch->sum('total_goals'),
            'assists' => (int) $perMatch->sum('total_assists'),
            'matches' => $matches->count(),
        ];

        $trend = $this->playerContributionTrend($matches, $perMatch);
        $chartLabels = $trend['chartLabels'];
        $chartValues = $trend['chartValues'];

        $radar = $this->buildRadar($perMatch, $matches->count());

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
            'radar', 'matches', 'seasons', 'opponentOf', 'perMatch',
            'statisticGroups', 'statisticTotals'
        ));
    }

    public function showPlayerMatchReport(Player $player, MatchSession $match)
    {
        abort_unless(
            $match->players()->where('players.id', $player->id)->exists(),
            404
        );

        $player->load('club');
        $matchStats = $this->playerStatsPerMatch($player->id)->get($match->id);
        $statisticGroups = $this->playerStatisticGroups();
        $vectors = VectorDirection::where('match_id', $match->id)
            ->where('player_id', $player->id)
            ->orderBy('created_at')
            ->orderBy('id')
            ->get();
        $opponent = $this->opponentOf($match);
        $lineupPlayer = $match->players()
            ->where('players.id', $player->id)
            ->first();
        $position = $lineupPlayer?->pivot?->position;

        return view('reports.player-match', compact(
            'player',
            'match',
            'matchStats',
            'statisticGroups',
            'vectors',
            'opponent',
            'position'
        ));
    }

    /**
     * PDF satu pemain pada satu pertandingan.
     * Memakai template yang sama dengan PDF musim (berisi 1 pertandingan),
     * sehingga tidak bentrok dengan reports.player_pdf (profil lengkap).
     */
    public function exportPlayerMatchPdf(Player $player, $matchId)
    {
        $match = MatchSession::findOrFail($matchId);

        $joined = $match->players()->where('players.id', $player->id)->exists();
        abort_if(!$joined, 404);

        $player->load('club');

        $perMatch = collect($this->playerStatsPerMatch($player->id)->all())
            ->only([$match->id]);
        $matches  = collect([$match]);
        $statisticGroups = $this->playerStatisticGroups();
        $statisticTotals = [];
        foreach ($statisticGroups as $group) {
            foreach (array_keys($group) as $field) {
                $statisticTotals[$field] = (int) $perMatch->sum($field);
            }
        }

        $totals = (object) [
            'goals'        => (int) ($perMatch[$match->id]->total_goals ?? 0),
            'assists'      => (int) ($perMatch[$match->id]->total_assists ?? 0),
            'shots_target' => (int) ($perMatch[$match->id]->total_shots_target ?? 0),
            'passes'       => (int) ($perMatch[$match->id]->total_passes ?? 0),
            'tackles'      => (int) ($perMatch[$match->id]->total_tackles ?? 0),
        ];

        $vectors = VectorDirection::where('match_id', $match->id)
            ->where('player_id', $player->id)
            ->orderBy('created_at')
            ->orderBy('id')
            ->get();
        $lineupPlayer = $match->players()
            ->where('players.id', $player->id)
            ->first();
        $position = $lineupPlayer?->pivot?->position;
        $opponent = $this->opponentOf($match);

        return Pdf::loadView('reports.player_match_pdf', compact(
            'player', 'match', 'perMatch', 'totals', 'statisticGroups',
            'statisticTotals', 'vectors', 'opponent', 'position'
        ))->stream('Laporan_' . Str::slug($player->name) . '_Match_' . $match->id . '.pdf');
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
        $perMatch = collect($perMatch->all())->only($matches->pluck('id')->all());
        $statisticGroups = $this->playerStatisticGroups();
        $statisticTotals = [];
        foreach ($statisticGroups as $group) {
            foreach (array_keys($group) as $field) {
                $statisticTotals[$field] = (int) $perMatch->sum($field);
            }
        }

        $totals = (object) [
            'goals'        => (int) $matches->sum(fn ($m) => $perMatch[$m->id]->total_goals ?? 0),
            'assists'      => (int) $matches->sum(fn ($m) => $perMatch[$m->id]->total_assists ?? 0),
            'shots_target' => (int) $matches->sum(fn ($m) => $perMatch[$m->id]->total_shots_target ?? 0),
            'passes'       => (int) $matches->sum(fn ($m) => $perMatch[$m->id]->total_passes ?? 0),
            'tackles'      => (int) $matches->sum(fn ($m) => $perMatch[$m->id]->total_tackles ?? 0),
        ];

        $seasonLabel = $this->seasonLabel($season);
        $reportTitle = 'LAPORAN MUSIM';

        // Radar hanya dari pertandingan di musim ini
        $radar = array_intersect_key(
            $this->buildRadar($perMatch, $matches->count()),
            array_flip(['Serangan', 'Bertahan', 'Operan'])
        );
        $opponentOf = fn ($match) => $this->opponentOf($match);

        return Pdf::loadView('reports.player_season_pdf', compact(
            'player', 'matches', 'perMatch', 'totals', 'seasonLabel', 'reportTitle',
            'radar', 'statisticGroups', 'statisticTotals', 'opponentOf'
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

        $radar      = $this->buildRadar($perMatch, $matches->count());
        $opponentOf = fn ($m) => $this->opponentOf($m);
        $trend = $this->playerContributionTrend($matches, $perMatch);
        $chartLabels = $trend['chartLabels'];
        $chartValues = $trend['chartValues'];
        $radar = array_intersect_key($radar, array_flip(['Serangan', 'Bertahan', 'Operan']));

        return Pdf::loadView('reports.player_pdf', compact(
            'player', 'matches', 'perMatch', 'totals', 'radar', 'opponentOf',
            'chartLabels', 'chartValues'
        ))
            ->stream('Laporan_Lengkap_' . Str::slug($player->name) . '.pdf');
    }
}
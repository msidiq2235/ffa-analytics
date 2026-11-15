<?php

namespace App\Http\Controllers;

use App\Models\MatchSession;
use App\Models\Club;
use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class MatchController extends Controller
{
    public function index(Request $request)
    {
        $now = now();
        $view = $request->query('view', 'table') === 'card' ? 'card' : 'table';
        $perPage = $view === 'card' ? 9 : 10;

        $matchesQuery = MatchSession::query();
        $activeRole = session('active_role', auth()->user()->role);

        if ($activeRole === 'Pelatih') {
            $clubNames = Club::where('coach_id', auth()->id())->pluck('name');
            $matchesQuery->whereIn('title', $clubNames);
        }

        $matches = $matchesQuery
            ->orderByRaw("CASE WHEN status = 'Live' THEN 0 WHEN match_date >= ? THEN 1 ELSE 2 END", [$now])
            ->orderByRaw("CASE WHEN match_date >= ? THEN match_date END ASC", [$now])
            ->orderByRaw("CASE WHEN match_date < ? THEN match_date END DESC", [$now])
            ->orderBy('id', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        return view('matches.index', compact('matches', 'view'));
    }

    public function create()
    {
        $clubs = Club::orderBy('name', 'asc')->get();

        return view('matches.create', compact('clubs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'      => 'required|string|max:255',
            'match_date' => 'required|date',
            'venue'      => 'nullable|string|max:255',
            'opponent'   => 'nullable|string|max:255',
            'formation'  => 'nullable|string|max:50',
        ]);

        $matchDate = Carbon::parse($request->match_date);
        $formattedDate = $matchDate->toDateTimeString();
        $status = $matchDate->isPast() ? 'Selesai' : 'Upcoming';

        MatchSession::create([
            'title'      => $request->title,
            'match_date' => $formattedDate,
            'venue'      => $request->venue,
            'opponent'   => $request->opponent,
            'formation'  => $request->formation,
            'status'     => $status,
            'created_by' => Auth::id(),
        ]);

        return redirect()
            ->route('matches.index')
            ->with('success', 'Jadwal pertandingan berhasil dibuat!');
    }

    public function lineup(MatchSession $match)
    {
        $club = Club::where('name', $match->title)->first();
        $players = $club
            ? Player::where('club_id', $club->id)->where('status', 'Aktif')->orderBy('name')->get()
            : collect();

        $match->load('players');
        $selectedPlayerIds = $match->players->pluck('id')->toArray();

        return view('matches.lineup', compact('match', 'players', 'selectedPlayerIds'));
    }

    public function updateLineup(Request $request, MatchSession $match)
    {
        $request->validate([
            'formation' => 'nullable|string',
            'players'   => 'array',
        ]);

        $selectedPlayers = collect($request->input('players', []))
            ->filter(fn ($player) => isset($player['selected']) && (string) $player['selected'] === '1')
            ->keys();

        $club = Club::where('name', $match->title)->first();
        $playerRule = $club
            ? Rule::exists('players', 'id')->where('club_id', $club->id)->where('status', 'Aktif')
            : Rule::in([]);

        Validator::make(
            ['player_ids' => $selectedPlayers->all()],
            [
                'player_ids.*' => [
                    'required',
                    $playerRule,
                ],
            ],
            ['player_ids.*.exists' => 'Pemain yang dipilih harus pemain aktif dari tim pertandingan ini.']
        )->validate();

        $match->update([
            'formation' => $request->formation,
        ]);

        $syncData = [];
        if ($request->has('players')) {
            foreach ($request->players as $playerId => $data) {
                if (isset($data['selected']) && $data['selected'] == '1') {
                    $syncData[$playerId] = ['position' => $data['position']];
                }
            }
        }

        $match->players()->sync($syncData);

        return redirect()->route('matches.index')->with('success', 'Line-up dan formasi taktik berhasil disimpan!');
    }

    private function isAdminRole(): bool
    {
        $activeRole = session('active_role', auth()->user()->role);
        return in_array($activeRole, ['Super Admin', 'Admin'], true);
    }

    public function live(MatchSession $match)
    {
        if ($this->isAdminRole()) {
            abort(403, 'Admin tidak memiliki akses untuk melakukan input pada halaman Live Analisis.');
        }

        $match->load(['players' => function ($query) {
            $query->withPivot('position');
        }]);

        $startingEleven = $match->players->where('pivot.position', '!=', 'Cadangan');
        $bench = $match->players->where('pivot.position', 'Cadangan');

        return view('matches.live', compact('match', 'startingEleven', 'bench'));
    }

    public function getPlayerStats(MatchSession $match, $playerId)
    {
        $player = $match->players()->whereKey($playerId)->firstOrFail();
        $isGoalkeeper = $player->pivot->position === 'GK'
            || ($player->pivot->position === 'Cadangan' && $player->position === 'GK');

        $stat = \App\Models\Statistic::where('match_id', $match->id)
            ->where('player_id', $playerId)
            ->where('analyst_id', auth()->id())
            ->first();

        $statsData = [];
        foreach ($this->statisticKeyMap($isGoalkeeper) as $key => $column) {
            $statsData[$key] = (int) ($stat?->{$column} ?? 0);
        }

        return response()->json([
            'success'  => true,
            'position' => $player->pivot->position,
            'stats'    => $statsData,
        ]);
    }

    public function storeStatistic(Request $request, MatchSession $match)
    {
        if ($this->isAdminRole()) {
            return response()->json(['error' => 'Admin tidak memiliki akses untuk menyimpan statistik.'], 403);
        }

        $validated = $request->validate([
            'player_id' => 'required|integer|exists:players,id',
            'stat_key'  => 'required|string',
            'action'    => 'required|in:increment,decrement',
        ]);

        $player = $match->players()->whereKey($validated['player_id'])->firstOrFail();
        $isGoalkeeper = $player->pivot->position === 'GK'
            || ($player->pivot->position === 'Cadangan' && $player->position === 'GK');
        $keyMap = $this->statisticKeyMap($isGoalkeeper);

        if (!array_key_exists($validated['stat_key'], $keyMap)) {
            return response()->json(['error' => 'Invalid statistic for this player position.'], 422);
        }

        $column = $keyMap[$validated['stat_key']];
        $stat = \App\Models\Statistic::firstOrCreate([
            'match_id'   => $match->id,
            'player_id'  => $player->id,
            'analyst_id' => auth()->id(),
        ]);

        if ($validated['action'] === 'increment') {
            $stat->increment($column);
        } elseif ($stat->{$column} > 0) {
            $stat->decrement($column);
        }

        return response()->json(['success' => true, 'current_value' => (int) $stat->{$column}]);
    }

    private function statisticKeyMap(bool $isGoalkeeper): array
    {
        if ($isGoalkeeper) {
            return [
                'gk_shots_faced'         => 'shots_faced',
                'gk_goals_conceded'      => 'goals_conceded',
                'gk_clean_sheets'        => 'clean_sheets',
                'gk_saves'               => 'saves',
                'gk_penalty_saves'       => 'penalty_saves',
                'gk_high_claims'         => 'high_claims',
                'gk_punches'             => 'punches',
                'gk_sweeper_actions'     => 'sweeper_actions',
                'gk_catches'             => 'catches',
                'gk_crosses_stopped'     => 'crosses_stopped',
                'gk_successful_passes'   => 'successful_passes',
                'gk_accurate_goal_kicks' => 'accurate_goal_kicks',
                'gk_unsuccessful_passes' => 'unsuccessful_passes',
                'gk_accurate_throws'     => 'accurate_throws',
            ];
        }

        return [
            '1' => 'shooting_on_target',
            '2' => 'shooting_off_target',
            '3' => 'goal',
            '4' => 'penalty_goal',
            'Q' => 'successful_passes',
            'W' => 'successful_crosses',
            'E' => 'assist',
            'R' => 'chance_created',
            'T' => 'dribble',
            'Y' => 'successful_dribble',
            'U' => 'unsuccessful_dribble',
            'A' => 'tackles',
            'S' => 'fouls_committed',
            'D' => 'interceptions',
            'F' => 'dribbled_past',
        ];
    }

    public function lockMatch(Request $request, MatchSession $match)
    {
        if ($this->isAdminRole()) {
            abort(403, 'Admin tidak memiliki akses untuk mengunci pertandingan.');
        }

        $match->update(['status' => 'Selesai']);
        return redirect()->route('matches.index')->with('success', 'Data pertandingan berhasil dikunci dan final!');
    }

    public function vector(MatchSession $match)
    {
        if ($this->isAdminRole()) {
            abort(403, 'Admin tidak memiliki akses untuk melakukan input pada halaman Vector.');
        }

        $match->load(['players' => function ($query) {
            $query->withPivot('position');
        }]);

        $startingEleven = $match->players->where('pivot.position', '!=', 'Cadangan');
        $vectors = \App\Models\VectorDirection::query()
            ->join('players', 'vector_directions.player_id', '=', 'players.id')
            ->where('vector_directions.match_id', $match->id)
            ->select(
                'vector_directions.id',
                'vector_directions.player_id',
                'players.name as player_name',
                'vector_directions.action_type',
                'vector_directions.subtype',
                'vector_directions.start_x',
                'vector_directions.start_y',
                'vector_directions.end_x',
                'vector_directions.end_y',
                'vector_directions.input_time',
                'vector_directions.match_minute'
            )
            ->orderBy('vector_directions.created_at')
            ->get();

        return view('matches.vector', compact('match', 'startingEleven', 'vectors'));
    }

    public function storeVector(Request $request, MatchSession $match)
    {
        if ($this->isAdminRole()) {
            return response()->json(['message' => 'Admin tidak memiliki akses untuk menyimpan data vektor.'], 403);
        }

        $allowedSubtypes = match ($request->action_type) {
            'SHOT'  => ['ON_TARGET', 'OFF_TARGET', 'BLOCKED', 'GOAL'],
            'PASS'  => ['SUCCESS', 'KEY_PASS', 'UNSUCCESSFUL'],
            'CROSS' => ['SUCCESS', 'ASSIST', 'UNSUCCESSFUL'],
            default => [],
        };

        $request->validate([
            'player_id'      => 'required|exists:players,id',
            'action_type'    => 'required|in:SHOT,PASS,CROSS',
            'subtype'        => ['required', 'string', Rule::in($allowedSubtypes)],
            'start_x'        => 'required|numeric',
            'start_y'        => 'required|numeric',
            'end_x'          => 'required|numeric',
            'end_y'          => 'required|numeric',
            'timestamp_mode' => 'required|in:live,manual',
            'input_time'     => 'required_if:timestamp_mode,live|nullable|date',
            'match_minute'   => 'required_if:timestamp_mode,manual|nullable|integer|min:0',
        ]);

        $vector = \App\Models\VectorDirection::create([
            'match_id'     => $match->id,
            'player_id'    => $request->player_id,
            'analyst_id'   => auth()->id(),
            'action_type'  => $request->action_type,
            'subtype'      => $request->subtype,
            'start_x'      => $request->start_x,
            'start_y'      => $request->start_y,
            'end_x'        => $request->end_x,
            'end_y'        => $request->end_y,
            'input_time'   => $request->timestamp_mode === 'live' ? Carbon::parse($request->input_time) : null,
            'match_minute' => $request->timestamp_mode === 'manual' ? $request->match_minute : null,
        ]);

        return response()->json(['success' => true, 'data' => $vector]);
    }

    public function edit($id)
    {
        $match = MatchSession::findOrFail($id);
        $clubs = Club::orderBy('name', 'asc')->get();

        return view('matches.edit', compact('match', 'clubs'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title'      => 'required|string|max:255',
            'opponent'   => 'nullable|string|max:255',
            'venue'      => 'nullable|string|max:255',
            'match_date' => 'required|date',
            'formation'  => 'nullable|string|max:50',
        ]);

        $matchDate = Carbon::parse($request->match_date);
        $formattedDate = $matchDate->toDateTimeString();
        $match = MatchSession::findOrFail($id);

        $status = $match->status;
        if ($status !== 'Live') {
            $status = $matchDate->isPast() ? 'Selesai' : 'Upcoming';
        }

        $match->update([
            'title'      => $request->title,
            'opponent'   => $request->opponent,
            'venue'      => $request->venue,
            'match_date' => $formattedDate,
            'status'     => $status,
            'formation'  => $request->formation,
        ]);

        return redirect()->route('matches.index')->with('success', 'Jadwal pertandingan berhasil diperbarui!');
    }

    public function destroy(MatchSession $match)
    {
        $user = Auth::user();
        abort_unless(in_array($user->role, ['Super Admin', 'Pelatih'], true), 403);

        if ($user->role === 'Pelatih') {
            abort_unless(
                Club::where('coach_id', $user->id)
                    ->where('name', $match->title)
                    ->exists(),
                404
            );
        }

        $match->delete();

        return redirect()
            ->route('matches.index')
            ->with('success', 'Jadwal pertandingan berhasil dihapus.');
    }
}
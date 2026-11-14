<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\MatchSession;
use App\Models\Player;
use App\Models\Statistic;
use App\Models\User;
use App\Models\VectorDirection;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $canSwitchRole = in_array($user->role, ['Super Admin', 'Admin'], true);
        $activeRole = $canSwitchRole
            ? session('active_role', $user->role)
            : $user->role;
        $data = ['activeRole' => $activeRole];
        if (in_array($activeRole, ['Super Admin', 'Admin'], true)) {
            $data['totalUsers'] = User::count();
            $data['totalClubs'] = Club::count();
            $data['totalPlayers'] = Player::count();
            $data['totalMatches'] = MatchSession::count();
            $data['recentMatches'] = MatchSession::orderBy('match_date', 'desc')->take(5)->get();
            $data['recentPlayers'] = Player::orderBy('created_at', 'desc')->take(5)->get();
        } elseif ($activeRole === 'Pelatih') {
            $isAssignedCoach = $user->role === 'Pelatih';
            $clubsQuery = Club::query();
            if ($isAssignedCoach) {
                $clubsQuery->where('coach_id', $user->id);
            }
            $clubs = $clubsQuery->withCount('players')->orderBy('name')->get();
            $clubIds = $clubs->modelKeys();
            $clubNames = $clubs->pluck('name');
            $playersQuery = Player::with('club')->orderBy('name');
            if ($isAssignedCoach) {
                $playersQuery->whereIn('club_id', $clubIds);
            }
            $players = $playersQuery->get();
            $playerIds = $players->modelKeys();

            $coachMatches = function () use ($isAssignedCoach, $clubNames) {
                $query = MatchSession::query();
                if ($isAssignedCoach) {
                    $query->whereIn('title', $clubNames);
                }

                return $query;
            };

            $data['coachClubs'] = $clubs;
            $data['coachPlayers'] = $players;
            $data['isAssignedCoach'] = $isAssignedCoach;
            $data['upcomingMatches'] = $coachMatches()->where('status', 'Upcoming')->count();
            $data['completedMatches'] = $coachMatches()->where('status', 'Selesai')->count();
            $data['liveMatches'] = $coachMatches()->where('status', 'Live')->orderBy('match_date')->get();
            $data['upcomingMatchesList'] = $coachMatches()->where('status', 'Upcoming')->orderBy('match_date')->take(5)->get();
            $data['recentMatchesList'] = $coachMatches()->where('status', 'Selesai')->orderByDesc('match_date')->take(5)->get();
            $data['totalGoals'] = Statistic::whereIn('player_id', $playerIds)->sum('goal');
            $data['totalAssists'] = Statistic::whereIn('player_id', $playerIds)->sum('assist');
            $playerStats = Statistic::query()
                ->join('players', 'statistics.player_id', '=', 'players.id')
                ->selectRaw('statistics.player_id, SUM(statistics.goal) as goals, SUM(statistics.assist) as assists, COUNT(DISTINCT statistics.match_id) as matches');
            if ($isAssignedCoach) {
                $playerStats->whereIn('players.club_id', $clubIds);
            }
            $data['coachPlayerStats'] = $playerStats
                ->groupBy('statistics.player_id')
                ->get()
                ->keyBy('player_id');
        } elseif ($activeRole === 'Analis') {
            $isOwnAnalyst = $user->role === 'Analis';
            $analystStatistics = fn () => Statistic::query()
                ->when($isOwnAnalyst, fn ($query) => $query->where('analyst_id', $user->id));

            $matches = MatchSession::query()
                ->orderByRaw("CASE WHEN status = 'Live' THEN 0 WHEN status = 'Upcoming' THEN 1 ELSE 2 END")
                ->orderByRaw("CASE WHEN status = 'Upcoming' THEN match_date END ASC")
                ->orderByRaw("CASE WHEN status != 'Upcoming' THEN match_date END DESC")
                ->take(20)
                ->get();
            $matchStats = $analystStatistics()
                ->whereIn('match_id', $matches->modelKeys())
                ->selectRaw('match_id, COUNT(*) as entries, SUM(goal) as goals, SUM(assist) as assists, SUM(shooting_on_target + shooting_off_target) as shots, SUM(successful_passes) as passes')
                ->groupBy('match_id')
                ->get()
                ->keyBy('match_id');

            $topPlayers = Player::query()
                ->join('statistics', 'players.id', '=', 'statistics.player_id')
                ->leftJoin('clubs', 'players.club_id', '=', 'clubs.id')
                ->when($isOwnAnalyst, fn ($query) => $query->where('statistics.analyst_id', $user->id))
                ->selectRaw('players.id, players.name, players.jersey_number, clubs.name as club_name, SUM(statistics.goal) as goals, SUM(statistics.assist) as assists, COUNT(DISTINCT statistics.match_id) as matches')
                ->groupBy('players.id', 'players.name', 'players.jersey_number', 'clubs.name')
                ->orderByDesc('goals')
                ->orderByDesc('assists')
                ->take(5)
                ->get();

            $monthlyGoals = collect(range(5, 0))->map(function ($monthsAgo) use ($analystStatistics) {
                $month = now()->startOfMonth()->subMonths($monthsAgo);
                $goals = $analystStatistics()
                    ->whereBetween('created_at', [$month->copy(), $month->copy()->endOfMonth()])
                    ->sum('goal');

                return [
                    'label' => $month->translatedFormat('M'),
                    'goals' => (int) $goals,
                ];
            });

            $data['isOwnAnalyst'] = $isOwnAnalyst;
            $data['analystMatches'] = $matches;
            $data['analystMatchStats'] = $matchStats;
            $data['analystTopPlayers'] = $topPlayers;
            $data['analystMonthlyGoals'] = $monthlyGoals;
            $data['analystLiveCount'] = MatchSession::where('status', 'Live')->count();
            $data['analystUpcomingCount'] = MatchSession::where('status', 'Upcoming')->count();
            $data['analystCompletedCount'] = MatchSession::where('status', 'Selesai')->count();
            $data['analystGoals'] = $analystStatistics()->sum('goal');
            $data['analystAssists'] = $analystStatistics()->sum('assist');
            $data['analystEntries'] = $analystStatistics()->count();
            $data['analystVectors'] = VectorDirection::query()
                ->when($isOwnAnalyst, fn ($query) => $query->where('analyst_id', $user->id))
                ->count();
        } elseif ($activeRole === 'Pemain') {
            $data['totalGoals'] = Statistic::sum('goal');
            $data['totalAssists'] = Statistic::sum('assist');
            $data['totalMatches'] = MatchSession::where('status', 'Selesai')->count();
            $data['recentMatchesList'] = MatchSession::where('status', 'Selesai')->orderBy('match_date', 'desc')->take(5)->get();
        }

        return view('dashboard', $data);
    }
}

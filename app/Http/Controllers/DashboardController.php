<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\MatchSession;
use App\Models\Player;
use App\Models\Statistic;
use App\Models\User;

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

            $coachMatches = function () use ($isAssignedCoach, $clubNames, $clubIds) {
                $query = MatchSession::query();
                if ($isAssignedCoach) {
                    $query->where(function ($query) use ($clubNames, $clubIds) {
                        $query->whereIn('title', $clubNames);

                        if ($clubIds !== []) {
                            $query->orWhereHas('players', fn ($playerQuery) => $playerQuery->whereIn('players.club_id', $clubIds));
                        }
                    });
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
            $data['liveMatches'] = MatchSession::where('status', 'Live')->orderBy('match_date', 'desc')->get();
            $data['upcomingMatchesData'] = MatchSession::where('status', 'Upcoming')->orderBy('match_date', 'asc')->take(5)->get();
        } elseif ($activeRole === 'Pemain') {
            $data['totalGoals'] = Statistic::sum('goal');
            $data['totalAssists'] = Statistic::sum('assist');
            $data['totalMatches'] = MatchSession::where('status', 'Selesai')->count();
            $data['recentMatchesList'] = MatchSession::where('status', 'Selesai')->orderBy('match_date', 'desc')->take(5)->get();
        }

        return view('dashboard', $data);
    }
}

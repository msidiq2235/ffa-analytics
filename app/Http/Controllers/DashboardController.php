<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Player;
use App\Models\Club;
use App\Models\MatchSession;
use App\Models\Statistic;
class DashboardController extends Controller
{
public function index()
{
$activeRole = session('active_role', auth()->user()->role);
$data = ['activeRole' => $activeRole];
if ($activeRole === 'Super Admin') {
$data['totalUsers'] = User::count();
$data['totalClubs'] = Club::count();
$data['totalPlayers'] = Player::count();
$data['totalMatches'] = MatchSession::count();
$data['recentMatches'] = MatchSession::orderBy('match_date', 'desc')->take(5)->get();
$data['recentPlayers'] = Player::orderBy('created_at', 'desc')->take(5)->get();
} elseif ($activeRole === 'Pelatih') {
$data['upcomingMatches'] = MatchSession::where('status', 'Upcoming')->count();
$data['totalGoals'] = Statistic::sum('goal');
$data['totalAssists'] = Statistic::sum('assist');
$data['completedMatches'] = MatchSession::where('status', 'Selesai')->count();
$data['recentMatchesList'] = MatchSession::where('status', 'Selesai')->orderBy('match_date', 'desc')->take(5)->get();
$data['upcomingMatchesList'] = MatchSession::where('status', 'Upcoming')->orderBy('match_date', 'asc')->take(5)->get();
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
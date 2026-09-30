<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\MatchSession;
use App\Models\Statistic;
use App\Models\VectorDirection;
use Barryvdh\DomPDF\Facade\Pdf;
class ReportController extends Controller
{
public function showMatchReport(MatchSession $match)
{
$match->load(['players'=>function($q){$q->withPivot('position');}]);
$stats=Statistic::where('match_id',$match->id)->selectRaw('player_id, SUM(goal) as total_goals, SUM(assist) as total_assists, SUM(shooting_on_target) as total_shots_target, SUM(shooting_off_target) as total_shots_off, SUM(successful_passes) as total_passes, SUM(tackles) as total_tackles')->groupBy('player_id')->get()->keyBy('player_id');
$teamStats=['goals'=>$stats->sum('total_goals'),'shots'=>$stats->sum('total_shots_target')+$stats->sum('total_shots_off'),'passes'=>$stats->sum('total_passes'),'tackles'=>$stats->sum('total_tackles')];
$vectors=VectorDirection::where('match_id',$match->id)->join('players','vector_directions.player_id','=','players.id')->select('vector_directions.*','players.name as player_name','players.jersey_number')->get();
return view('reports.show',compact('match','stats','teamStats','vectors'));
}
public function exportMatchPdf(MatchSession $match)
{
$match->load(['players'=>function($q){$q->withPivot('position');}]);
$stats=Statistic::where('match_id',$match->id)->selectRaw('player_id, SUM(goal) as total_goals, SUM(assist) as total_assists, SUM(shooting_on_target) as total_shots_target, SUM(successful_passes) as total_passes')->groupBy('player_id')->get()->keyBy('player_id');
$pdf=Pdf::loadView('reports.match-pdf',compact('match','stats'));
return $pdf->download('Match_Report_'.str_replace(' ','_',$match->title).'.pdf');
}
}
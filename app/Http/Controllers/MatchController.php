<?php

namespace App\Http\Controllers;

use App\Models\MatchSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MatchController extends Controller
{
    public function index()
    {
        $matches = MatchSession::orderBy('match_date', 'desc')->get();

        return view('matches.index', compact('matches'));
    }

    public function create()
    {
        return view('matches.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'match_date' => 'required|date',
            'venue' => 'nullable|string|max:255',
            'opponent' => 'nullable|string|max:255',
            'formation' => 'nullable|string|max:50',
        ]);

        MatchSession::create([
            'title' => $request->title,
            'match_date' => $request->match_date,
            'venue' => $request->venue,
            'opponent' => $request->opponent,
            'formation' => $request->formation,
            'status' => 'Upcoming',
            'created_by' => Auth::id(),
        ]);

        return redirect()
            ->route('matches.index')
            ->with('success', 'Jadwal pertandingan berhasil dibuat!');
    }

    public function lineup(MatchSession $match)
    {
        $players = \App\Models\Player::where('status', 'Aktif')->get();

        $match->load('players');

        $selectedPlayerIds = $match->players->pluck('id')->toArray();

        return view(
            'matches.lineup',
            compact('match', 'players', 'selectedPlayerIds')
        );
    }

    public function updateLineup(Request $request, MatchSession $match)
    {
        // 1. Validasi input formasi (opsional tapi direkomendasikan)
        $request->validate([
            'formation' => 'nullable|string',
            'players' => 'array'
        ]);

        // 2. Simpan formasi taktik ke tabel match_sessions
        $match->update([
            'formation' => $request->formation
        ]);

        // 3. Olah data pemain yang di-ceklis (bawa ke pertandingan)
        $syncData = [];
        if ($request->has('players')) {
            foreach ($request->players as $playerId => $data) {
                // Hanya proses pemain yang checkbox "selected"-nya dicentang
                if (isset($data['selected']) && $data['selected'] == '1') {
                    // Simpan ID pemain beserta posisinya ke array sync
                    $syncData[$playerId] = ['position' => $data['position']];
                }
            }
        }

        // 4. Sinkronisasi data ke tabel pivot (match_player)
        $match->players()->sync($syncData);

        return redirect()->route('matches.index')->with('success', 'Line-up dan formasi taktik berhasil disimpan!');
    }

    public function live(MatchSession $match)
    {
        $match->load(['players' => function($query) {
            $query->withPivot('position');
        }]);

        $startingEleven = $match->players->where('pivot.position', '!=', 'Cadangan');
        $bench = $match->players->where('pivot.position', 'Cadangan');

        return view('matches.live', compact('match', 'startingEleven', 'bench'));
    }
    public function getPlayerStats(MatchSession $match,$playerId)
    {
        $stat=\App\Models\Statistic::where('match_id',$match->id)->where('player_id',$playerId)->where('analyst_id',auth()->id())->first();
        $statsData=['1'=>0,'2'=>0,'3'=>0,'4'=>0,'Q'=>0,'W'=>0,'E'=>0,'R'=>0,'T'=>0,'Y'=>0,'U'=>0,'A'=>0,'S'=>0,'D'=>0];
        if($stat){$statsData=['1'=>$stat->shooting_on_target,'2'=>$stat->shooting_off_target,'3'=>$stat->goal,'4'=>$stat->penalty_goal,'Q'=>$stat->successful_passes,'W'=>$stat->successful_crosses,'E'=>$stat->assist,'R'=>$stat->chance_created,'T'=>$stat->dribble,'Y'=>$stat->successful_dribble,'U'=>$stat->unsuccessful_dribble,'A'=>$stat->tackles,'S'=>$stat->fouls_committed,'D'=>$stat->interceptions];}
        return response()->json(['success'=>true,'stats'=>$statsData]);
    }
    public function storeStatistic(Request $request,MatchSession $match)
    {  
        $request->validate(['player_id'=>'required|exists:players,id','stat_key'=>'required|string','action'=>'required|in:increment,decrement']);
        $keyMap=['1'=>'shooting_on_target','2'=>'shooting_off_target','3'=>'goal','4'=>'penalty_goal','Q'=>'successful_passes','W'=>'successful_crosses','E'=>'assist','R'=>'chance_created','T'=>'dribble','Y'=>'successful_dribble','U'=>'unsuccessful_dribble','A'=>'tackles','S'=>'fouls_committed','D'=>'interceptions'];
        if(!array_key_exists($request->stat_key,$keyMap)){return response()->json(['error'=>'Invalid key'],400);}
        $column=$keyMap[$request->stat_key];
        $stat=\App\Models\Statistic::firstOrCreate(['match_id'=>$match->id,'player_id'=>$request->player_id,'analyst_id'=>auth()->id()]);
        if($request->action==='increment'){$stat->increment($column);}else{if($stat->$column>0){$stat->decrement($column);}}
        return response()->json(['success'=>true,'current_value'=>$stat->$column]);
    }
    public function lockMatch(Request $request, MatchSession $match)
    {
        $match->update(['status' => 'Selesai']);
        return redirect()->route('matches.index')->with('success', 'Data pertandingan berhasil dikunci dan final!');
    }
    public function storeVector(Request $request, MatchSession $match)
    {
    $request->validate([
    'player_id' => 'required|exists:players,id',
    'action_type' => 'required|string',
    'start_x' => 'required|numeric',
    'start_y' => 'required|numeric',
    'end_x' => 'required|numeric',
    'end_y' => 'required|numeric',
    ]);
    $vector = \App\Models\VectorDirection::create([
    'match_id' => $match->id,
    'player_id' => $request->player_id,
    'analyst_id' => auth()->id(),
    'action_type' => $request->action_type,
    'start_x' => $request->start_x,
    'start_y' => $request->start_y,
    'end_x' => $request->end_x,
    'end_y' => $request->end_y,
    ]);
    return response()->json(['success' => true, 'data' => $vector]);
    }

    public function vector(MatchSession $match)
    {
        $match->load(['players' => function($query) {
            $query->withPivot('position');
        }]);

        $startingEleven = $match->players->where('pivot.position', '!=', 'Cadangan');

        return view('matches.vector', compact('match', 'startingEleven'));
    }

    public function edit($id)
    {
        // Cari data pertandingan berdasarkan ID
        $match = \App\Models\MatchSession::findOrFail($id);
        
        return view('matches.edit', compact('match'));
    }

    public function update(Request $request, $id)
    {
        // Validasi input dari form
        $request->validate([
            'title'      => 'required|string|max:255',
            'opponent'   => 'nullable|string|max:255',
            'venue'      => 'nullable|string|max:255',
            'match_date' => 'required|date',
            'status'     => 'required|in:Upcoming,Live,Selesai',
        ]);

        // Simpan perubahan ke database
        $match = \App\Models\MatchSession::findOrFail($id);
        $match->update([
            'title'      => $request->title,
            'opponent'   => $request->opponent,
            'venue'      => $request->venue,
            'match_date' => $request->match_date,
            'status'     => $request->status,
        ]);

        return redirect()->route('matches.index')->with('success', 'Jadwal pertandingan berhasil diperbarui!');
    }
}
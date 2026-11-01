<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\Club;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    public function index()
    {
        $players = Player::with('club')->get();
        return view('players.index', compact('players'));
    }

    public function create()
    {
        $clubs = Club::orderBy('name', 'asc')->get();
        return view('players.create', compact('clubs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:50',
            'jersey_number' => 'nullable|integer',
            'club_id' => 'required|exists:clubs,id',
            'status' => 'nullable|string',
        ]);

        Player::create($request->all());

        return redirect()->route('players.index')->with('success', 'Data pemain berhasil ditambahkan!');
    }

    // --- TAMBAHKAN METHOD SHOW DI SINI ---
    public function show($id)
    {
        $player = Player::with('club')->findOrFail($id);
        return view('players.show', compact('player'));
    }

    public function edit(Player $player)
    {
        $clubs = Club::orderBy('name', 'asc')->get();
        return view('players.edit', compact('player', 'clubs'));
    }

    public function update(Request $request, Player $player)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:50',
            'jersey_number' => 'nullable|integer',
            'club_id' => 'required|exists:clubs,id',
            'status' => 'required|string|in:Aktif,Tidak Aktif,Cedera,Nonaktif',
        ]);

        $player->update([
            'name' => $request->name,
            'position' => $request->position,
            'jersey_number' => $request->jersey_number,
            'club_id' => $request->club_id,
            'status' => $request->status,
        ]);

        return redirect()->route('players.index')->with('success', 'Data pemain berhasil diperbarui!');
    }

    public function performance(Request $request)
    {
        $query = Player::with('club');

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('position')) {
            $query->where('position', $request->position);
        }

        if ($request->filled('number')) {
            $query->where(function($q) use ($request) {
                $q->where('number', $request->number)
                  ->orWhere('back_number', $request->number);
            });
        }

        if ($request->filled('club_id')) {
            $query->where('club_id', $request->club_id);
        }

        $players = $query->get();
        $clubs = Club::orderBy('name', 'asc')->get();

        return view('performa.index', compact('players', 'clubs'));
    }

    public function performanceShow($id)
    {
        $player = Player::with('club')->findOrFail($id);
        
        // Mengarahkan ke view khusus detail performa (misal: resources/views/performa/show.blade.php)
        return view('performa.show', compact('player'));
    }
}
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
        $clubs = \App\Models\Club::orderBy('name', 'asc')->get();
        return view('players.create', compact('clubs'));
    }

    public function edit(\App\Models\Player $player)
    {
        $clubs = \App\Models\Club::orderBy('name', 'asc')->get();
        return view('players.edit', compact('player', 'clubs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'jersey_number' => 'nullable|integer',
            'club_id' => 'required|exists:clubs,id',
            'status' => 'nullable|string',
        ]);

        Player::create($request->all());

        return redirect()->route('players.index')->with('success', 'Data pemain berhasil ditambahkan!');
    }

    public function update(Request $request, Player $player)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'jersey_number' => 'nullable|integer',
            'status' => 'required|string|in:Aktif,Tidak Aktif,Cedera',
        ]);

        $player->update([
            'name' => $request->name,
            'jersey_number' => $request->jersey_number,
            'status' => $request->status,
        ]);

        return redirect()->route('players.index')->with('success', 'Data pemain berhasil diperbarui!');
    }
}

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

    public function performance(Request $request)
    {
        // Mengambil query dasar pemain beserta relasi club/team
        $query = \App\Models\Player::with('club');

        // 1. Filter Nama Pemain
        if ($request->filled('name')) {
            $query->where('name', 'like', '\%' .$request->name . '%');
        }

        // 2. Filter Posisi Pemain
        if ($request->filled('position')) {
            $query->where('position',$request->position);
        }

        // 3. Filter Nomor Punggung Pemain
        if ($request->filled('number')) {
            $query->where('number',$request->number);
        }

        // 4. Filter Tim / Klub
        if ($request->filled('club_id')) {
            $query->where('club_id',$request->club_id);
        }

        $players =$query->get();

        // Mengambil daftar klub untuk opsi dropdown filter tim
        $clubs = \App\Models\Club::orderBy('name', 'asc')->get();

        return view('performa.index', compact('players', 'clubs'));
    }
}

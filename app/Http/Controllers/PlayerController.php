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
    // Mengambil data pemain beserta relasi klubnya
    $query = \App\Models\Player::with('club');

    // Filter berdasarkan Nama Pemain
    if ($request->filled('name')) {
        $query->where('name', 'like', '%' . $request->name . '%');
    }

    // Filter berdasarkan Posisi
    if ($request->filled('position')) {
        $query->where('position', $request->position);
    }

    // Filter berdasarkan Nomor Punggung (mendukung kolom 'number' atau 'back_number')
    if ($request->filled('number')) {
        $query->where(function($q) use ($request) {
            $q->where('number', $request->number)
              ->orWhere('back_number', $request->number);
        });
    }

    // Filter berdasarkan Tim / Klub
    if ($request->filled('club_id')) {
        $query->where('club_id', $request->club_id);
    }

    $players = $query->get();
    $clubs = \App\Models\Club::orderBy('name', 'asc')->get();

    return view('performa.index', compact('players', 'clubs'));
}
}

<?php

namespace App\Http\Controllers;

use App\Models\Club;
use Illuminate\Http\Request;

class ClubController extends Controller
{
    public function index()
    {
        // Menampilkan daftar klub beserta jumlah pemainnya
        $clubs = Club::withCount('players')->get();
        return view('clubs.index', compact('clubs'));
    }

    public function create()
    {
        return view('clubs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo_path' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = ['name' => $request->name];

        if ($request->hasFile('logo_path')) {
            $data['logo_path'] = $request->file('logo_path')->store('club_logos', 'public');
        }

        Club::create($data);

        return redirect()->route('clubs.index')->with('success', 'Klub baru berhasil ditambahkan!');
    }

    public function destroy(Club $club)
    {
        $club->delete();
        return redirect()->route('clubs.index')->with('success', 'Klub berhasil dihapus!');
    }
    public function show($id)
    {
        // Muat klub beserta data relasi pemainnya (asumsi nama relasi di model Club adalah 'players')
        $club = \App\Models\Club::with('players')->findOrFail($id);
        return view('clubs.show', compact('club'));
    }

    public function edit($id)
    {
        $club = \App\Models\Club::findOrFail($id);
        return view('clubs.edit', compact('club'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $club = \App\Models\Club::findOrFail($id);
        $club->update([
            'name' => $request->name,
        ]);

        return redirect()->route('clubs.index')->with('success', 'Informasi klub berhasil diperbarui!');
    }
}

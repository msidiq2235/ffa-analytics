<?php

namespace App\Http\Controllers;

use App\Models\Club;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
    public function show(Request $request, $id)
    {
        $club = \App\Models\Club::findOrFail($id);

        // Daftar squad klub ini (mendukung pencarian nama lewat ?search=)
        $players = $club->players()
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            })
            ->orderBy('jersey_number')
            ->get();

        return view('clubs.show', compact('club', 'players'));
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
            'logo_path' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Validasi file logo saat edit
        ]);

        $club = \App\Models\Club::findOrFail($id);
        
        $data = ['name' => $request->name];

        // Cek apakah ada file logo baru yang di-upload saat edit
        if ($request->hasFile('logo_path')) {
            // Hapus logo lama jika ada agar tidak menumpuk di storage
            if ($club->logo_path && Storage::disk('public')->exists($club->logo_path)) {
                Storage::disk('public')->delete($club->logo_path);
            }
            // Simpan logo baru
            $data['logo_path'] = $request->file('logo_path')->store('club_logos', 'public');
        }

        $club->update($data);

        return redirect()->route('clubs.index')->with('success', 'Informasi klub berhasil diperbarui!');
    }
}
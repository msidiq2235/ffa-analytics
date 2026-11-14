<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ClubController extends Controller
{
    public function index()
    {
        $clubs = $this->clubsVisibleToCurrentUser()
            ->with(['coach'])
            ->withCount('players')
            ->get();

        return view('clubs.index', compact('clubs'));
    }

    public function create()
    {
        $coaches = User::where('role', 'Pelatih')->orderBy('name')->get(['id', 'name', 'email']);

        return view('clubs.create', compact('coaches'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo_path' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'coach_id' => ['required', Rule::exists('users', 'id')->where('role', 'Pelatih')],
        ]);

        $data = [
            'name' => $request->name,
            'coach_id' => $request->coach_id,
        ];

        if ($request->hasFile('logo_path')) {
            $data['logo_path'] = $request->file('logo_path')->store('club_logos', 'public');
        }

        Club::create($data);

        return redirect()->route('clubs.index')->with('success', 'Klub baru berhasil ditambahkan!');
    }

    public function destroy(Club $club)
    {
        $this->clubsVisibleToCurrentUser()->findOrFail($club->id)->delete();

        return redirect()->route('clubs.index')->with('success', 'Klub berhasil dihapus!');
    }

    public function show(Request $request, $id)
    {
        $club = $this->clubsVisibleToCurrentUser()
            ->with('coach')
            ->findOrFail($id);

        // Daftar squad klub ini (mendukung pencarian nama lewat ?search=)
        $players = $club->players()
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%');
            })
            ->orderBy('jersey_number')
            ->get();

        return view('clubs.show', compact('club', 'players'));
    }

    public function edit($id)
    {
        $club = $this->clubsVisibleToCurrentUser()->findOrFail($id);
        $coaches = User::where('role', 'Pelatih')->orderBy('name')->get(['id', 'name', 'email']);

        return view('clubs.edit', compact('club', 'coaches'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo_path' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Validasi file logo saat edit
            'coach_id' => ['required', Rule::exists('users', 'id')->where('role', 'Pelatih')],
        ]);

        $club = $this->clubsVisibleToCurrentUser()->findOrFail($id);

        $data = [
            'name' => $request->name,
            'coach_id' => $request->coach_id,
        ];

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

    private function clubsVisibleToCurrentUser()
    {
        $query = Club::query();

        if (auth()->user()->role === 'Pelatih') {
            $query->where('coach_id', auth()->id());
        }

        return $query;
    }
}

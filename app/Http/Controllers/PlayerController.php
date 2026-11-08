<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\Club;
use App\Models\MatchSession;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    public function index(Request $request)
    {
        $query = Player::with('club');

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('position')) {
            $query->where('position', $request->position);
        }

        if ($request->filled('jersey')) {
            $query->where('jersey_number', $request->jersey);
        }

        if ($request->filled('club')) {
            $query->whereHas('club', function($q) use ($request) {
                $q->where('name', $request->club);
            });
        }

        $players = $query->get();
        $clubs = Club::orderBy('name', 'asc')->get();

        $selectedClub = $request->filled('club')
            ? $clubs->firstWhere('name', $request->club)
            : null;

        $lineups = $this->buildLineups($players->pluck('id')->all());

        return view('players.index', compact('players', 'clubs', 'selectedClub', 'lineups'));
    }

    private function buildLineups(array $playerIds): array
    {
        if (empty($playerIds)) {
            return [];
        }

        $lineups = [];

        MatchSession::whereHas('players', function ($q) use ($playerIds) {
                $q->whereIn('players.id', $playerIds);
            })
            ->with(['players' => function ($q) use ($playerIds) {
                $q->withPivot('position')->whereIn('players.id', $playerIds);
            }])
            ->orderBy('match_date', 'desc')
            ->get()
            ->each(function ($match) use (&$lineups) {
                foreach ($match->players as $p) {
                    $pos = $p->pivot->position ?? null;
                    $isStarter = $pos && $pos !== 'Cadangan';
                    $lineups[$p->id][] = [
                        'match'      => ($match->title ?? '-') . ' vs ' . ($match->opponent ?? '-'),
                        'date'       => $match->match_date ? \Carbon\Carbon::parse($match->match_date)->format('d M Y, H:i') : '-',
                        'venue'      => $match->venue ?? null,
                        'status'     => $match->status ?? null,
                        'position'   => $pos,
                        'is_starter' => $isStarter,
                        'role'       => $isStarter ? 'Starter' : 'Cadangan',
                    ];
                }
            });

        return $lineups;
    }

    public function create(Request $request)
    {
        $clubs = Club::orderBy('name', 'asc')->get();
        $selectedClubId = $request->query('club_id');
        return view('players.create', compact('clubs', 'selectedClubId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'popular_name' => 'nullable|string|max:255',
            'position' => 'required|in:ST,GK,CM,CB,LW,RW,RB,LB,DM,AM',
            'jersey_number' => 'nullable|integer',
            'club_id' => 'required|exists:clubs,id',
            'second_club' => 'nullable|string|max:255',
            'birth_place_date' => 'nullable|string|max:255',
            'nik' => 'nullable|string|max:50',
            'age_group' => 'nullable|integer',
            'gender' => 'nullable|string|max:15',
            'country' => 'nullable|string|max:255',
            'dominant_foot' => 'nullable|string|max:50',
            'height_cm' => 'nullable|numeric',
            'weight_kg' => 'nullable|numeric',
            'address' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);

        Player::create([
            'name' => $request->name,
            'popular_name' => $request->popular_name,
            'position' => $request->position,
            'jersey_number' => $request->jersey_number,
            'club_id' => $request->club_id,
            'second_club' => $request->second_club,
            'birth_place_date' => $request->birth_place_date,
            'nik' => $request->nik,
            'age_group' => $request->age_group,
            'gender' => $request->gender,
            'country' => $request->country ?? 'Indonesia',
            'dominant_foot' => $request->dominant_foot,
            'height_cm' => $request->height_cm,
            'weight_kg' => $request->weight_kg,
            'address' => $request->address,
            'province' => $request->province,
            'phone' => $request->phone,
            'email' => $request->email,
            'status' => 'Aktif',
        ]);

        if ($request->boolean('_quick')) {
            return redirect()->back()->with('success', 'Pemain berhasil ditambahkan ke squad!');
        }

        return redirect()->route('players.index')->with('success', 'Data pemain berhasil ditambahkan!');
    }

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
            'popular_name' => 'nullable|string|max:255',
            'position' => 'required|in:ST,GK,CM,CB,LW,RW,RB,LB,DM,AM',
            'jersey_number' => 'nullable|integer',
            'club_id' => 'required|exists:clubs,id',
            'second_club' => 'nullable|string|max:255',
            'birth_place_date' => 'nullable|string|max:255',
            'nik' => 'nullable|string|max:50',
            'age_group' => 'nullable|integer',
            'gender' => 'nullable|string|max:15',
            'country' => 'nullable|string|max:255',
            'dominant_foot' => 'nullable|string|max:50',
            'height_cm' => 'nullable|numeric',
            'weight_kg' => 'nullable|numeric',
            'address' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'status' => 'required|string|in:Aktif,Tidak Aktif,Cedera,Nonaktif',
        ]);

        $player->update([
            'name' => $request->name,
            'popular_name' => $request->popular_name,
            'position' => $request->position,
            'jersey_number' => $request->jersey_number,
            'club_id' => $request->club_id,
            'second_club' => $request->second_club,
            'birth_place_date' => $request->birth_place_date,
            'nik' => $request->nik,
            'age_group' => $request->age_group,
            'gender' => $request->gender,
            'country' => $request->country,
            'dominant_foot' => $request->dominant_foot,
            'height_cm' => $request->height_cm,
            'weight_kg' => $request->weight_kg,
            'address' => $request->address,
            'province' => $request->province,
            'phone' => $request->phone,
            'email' => $request->email,
            'status' => $request->status,
        ]);

        return redirect()->route('players.index')->with('success', 'Data pemain berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $player = Player::findOrFail($id);
        $player->delete();

        return redirect()->route('players.index')->with('success', 'Data pemain berhasil dihapus!');
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
        $clubs = Club::orderBy('name', 'asc',)->get();

        return view('performa.index', compact('players', 'clubs'));
    }

    public function performanceShow($id)
    {
        $player = Player::with(['club'])->findOrFail($id);
        return view('performa.show', compact('player'));
    }

    public function exportPdf($id)
    {
        $player = Player::with(['club'])->findOrFail($id);
        
        // Menggunakan view khusus cetak PDF, atau bisa langsung stream/download
        return view('players.pdf', compact('player'));
    }
}
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

        // Squad yang sedang difilter (dipakai untuk shortcut "Tambah Pemain ke Squad")
        $selectedClub = $request->filled('club')
            ? $clubs->firstWhere('name', $request->club)
            : null;

        // Informasi line-up: pertandingan apa saja yang memuat pemain ini
        $lineups = $this->buildLineups($players->pluck('id')->all());

        return view('players.index', compact('players', 'clubs', 'selectedClub', 'lineups'));
    }

    /**
     * Susun data line-up per pemain: [player_id => [ {match, date, venue, role, position, is_starter}, ... ]]
     */
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
        // Shortcut dari daftar squad: /players/create?club_id=3 -> klub terpilih otomatis
        $selectedClubId = $request->query('club_id');
        return view('players.create', compact('clubs', 'selectedClubId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|in:ST,GK,CM,CB,LW,RW,RB,LB,DM,AM', // Diperbarui
            'jersey_number' => 'nullable|integer',
            'club_id' => 'required|exists:clubs,id',
            'second_club' => 'nullable|string|max:255',
        ]);

        Player::create([
            'name' => $request->name,
            'position' => $request->position,
            'jersey_number' => $request->jersey_number,
            'club_id' => $request->club_id,
            'second_club' => $request->second_club,
            'status' => 'Aktif', // status default otomatis untuk pemain baru
        ]);

        // Tambah cepat dari daftar squad: tetap di halaman yang sama (filter ikut terbawa)
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
            'position' => 'required|in:ST,GK,CM,CB,LW,RW,RB,LB,DM,AM', // Diperbarui
            'jersey_number' => 'nullable|integer',
            'club_id' => 'required|exists:clubs,id',
            'second_club' => 'nullable|string|max:255',
            'status' => 'required|string|in:Aktif,Tidak Aktif,Cedera,Nonaktif',
        ]);

        $player->update([
            'name' => $request->name,
            'position' => $request->position,
            'jersey_number' => $request->jersey_number,
            'club_id' => $request->club_id,
            'second_club' => $request->second_club,
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
        $clubs = Club::orderBy('name', 'asc')->get();

        return view('performa.index', compact('players', 'clubs'));
    }

    public function performanceShow($id)
    {
        $player = Player::with(['club'])->findOrFail($id);
        return view('performa.show', compact('player'));
    }
}
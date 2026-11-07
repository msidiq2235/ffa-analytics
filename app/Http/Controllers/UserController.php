<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Daftar role yang tersedia (dipakai untuk tab filter & validasi)
    private const ROLES = ['Super Admin', 'Admin', 'Pelatih', 'Analis', 'Pemain'];

    // Opsi sorting. Default: 'newest' (terbaru terdaftar)
    private const SORTS = [
        'newest'    => 'Terbaru Terdaftar',
        'oldest'    => 'Terlama Terdaftar',
        'name_asc'  => 'Nama (A-Z)',
        'name_desc' => 'Nama (Z-A)',
        'role'      => 'Role',
        'status'    => 'Status',
    ];

    public function index(Request $request)
    {
        $role = $request->query('role');
        $sort = $request->query('sort', 'newest');

        if (!in_array($role, self::ROLES, true)) {
            $role = null;
        }
        if (!array_key_exists($sort, self::SORTS)) {
            $sort = 'newest';
        }

        $query = User::query();

        // Filter role (tab)
        if ($role) {
            $query->where('role', $role);
        }

        // Sorting
        switch ($sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'role':
                $query->orderBy('role', 'asc')->orderBy('name', 'asc');
                break;
            case 'status':
                $query->orderBy('status', 'asc')->orderBy('name', 'asc');
                break;
            default: // newest
                $query->orderBy('created_at', 'desc');
        }
        $query->orderBy('id', 'desc'); // tie-breaker agar urutan stabil

        $users = $query->get();

        // Jumlah user per role untuk badge pada tab
        $roleCounts = User::select('role', DB::raw('COUNT(*) as total'))
            ->groupBy('role')
            ->pluck('total', 'role');
        $totalAll = $roleCounts->sum();

        $totalAktif = User::where('status', 'Aktif')->count();
        $totalNonaktif = User::where('status', 'Nonaktif')->count();
        $totalOnline = User::where('last_seen', '>=', now()->subMinutes(5))->count();

        $roles = self::ROLES;
        $sorts = self::SORTS;

        return view('users.index', compact(
            'users', 'totalAktif', 'totalNonaktif', 'totalOnline',
            'role', 'sort', 'roles', 'sorts', 'roleCounts', 'totalAll'
        ));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:' . implode(',', self::ROLES),
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'status' => 'Aktif', // status default otomatis untuk akun baru
        ]);

        return redirect()->route('users.index')->with('success', 'Pengguna baru berhasil ditambahkan!');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:' . implode(',', self::ROLES),
            'status' => 'required|in:Aktif,Nonaktif',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'status' => $request->status,
        ];

        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:8']);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'Data pengguna berhasil diperbarui!');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Pengguna berhasil dihapus!');
    }

    // Hapus massal (select multiple)
    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array|min:1',
            'ids.*' => 'integer|exists:users,id',
        ], [
            'ids.required' => 'Pilih minimal satu pengguna yang akan dihapus.',
        ]);

        $ids = collect($request->input('ids'))->map(fn($id) => (int) $id)->unique();
        $selfSelected = $ids->contains(auth()->id());
        $ids = $ids->reject(fn($id) => $id === auth()->id());

        if ($ids->isEmpty()) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $deleted = User::whereIn('id', $ids)->delete();

        $message = $deleted . ' pengguna berhasil dihapus!';
        if ($selfSelected) {
            $message .= ' (Akun Anda sendiri tidak ikut dihapus.)';
        }

        return redirect()->back()->with('success', $message);
    }
}
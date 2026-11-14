@extends('layouts.app')
@section('title', 'Dashboard - FFA Analytics')
@php
$activeRole = $activeRole ?? (auth()->user()->role ?? 'Super Admin');
@endphp
@section('header', 'Dashboard ' . $activeRole)
@section('subheader', 'Selamat datang di FFA Analytics. Di sini Anda dapat mengelola data pemain, tim, dan pertandingan dengan mudah. Gunakan menu navigasi untuk mengakses fitur-fitur yang tersedia.')
@section('content')

<style>
    [data-lucide] { flex-shrink: 0; }
</style>

@if($activeRole === 'Super Admin' || $activeRole === 'Admin')
@php
    // ===== DATA PRIMER: pertandingan =====
    $nextMatches = \App\Models\MatchSession::where('status', 'Upcoming')->orderBy('match_date', 'asc')->take(4)->get();
    $nextMatch   = $nextMatches->first();
    $otherNext   = $nextMatches->slice(1);

    $cntTotal    = $totalMatches ?? \App\Models\MatchSession::count();
    $cntUpcoming = \App\Models\MatchSession::where('status', 'Upcoming')->count();
    $cntLive     = \App\Models\MatchSession::where('status', 'Live')->count();
    $cntDone     = \App\Models\MatchSession::where('status', 'Selesai')->count();
    $pct         = fn($n) => $cntTotal > 0 ? round($n / $cntTotal * 100) : 0;

    // ===== DATA PRIMER: user, klub, pemain =====
    $cntUsers        = $totalUsers ?? \App\Models\User::count();
    $cntUsersActive  = \App\Models\User::where('status', 'Aktif')->count();
    $cntUsersInactive= \App\Models\User::where('status', 'Nonaktif')->count();
    $cntClubs        = $totalClubs ?? \App\Models\Club::count();
    $cntPlayers      = $totalPlayers ?? \App\Models\Player::count();
    $cntPlayersActive= \App\Models\Player::where('status', 'Aktif')->count();

    $roleList   = ['Super Admin' => 'shield-check', 'Pelatih' => 'clipboard-list', 'Analis' => 'chart-column', 'Pemain' => 'user'];
    $roleCounts = \App\Models\User::selectRaw('role, count(*) as total')->groupBy('role')->pluck('total', 'role');

    // ===== PERLU PERHATIAN (kualitas data) =====
    $attention = [
        ['label' => 'Pemain belum punya klub',            'count' => \App\Models\Player::whereNull('club_id')->count(),                                              'route' => route('players.index'), 'icon' => 'user-x'],
        ['label' => 'Jadwal mendatang belum ada lokasi',  'count' => \App\Models\MatchSession::where('status', 'Upcoming')->where(fn($q) => $q->whereNull('venue')->orWhere('venue', ''))->count(), 'route' => route('matches.index'), 'icon' => 'map-pin-off'],
        ['label' => 'Jadwal mendatang belum ada line-up', 'count' => \App\Models\MatchSession::where('status', 'Upcoming')->whereDoesntHave('players')->count(),     'route' => route('matches.index'), 'icon' => 'clipboard-x'],
        ['label' => 'Akun nonaktif',                      'count' => $cntUsersInactive,                                                                              'route' => route('users.index'),   'icon' => 'user-lock'],
    ];
    $attentionTotal = collect($attention)->sum('count');

    // ===== DATA SEKUNDER: ringkasan statistik (dari input Analis) =====
    $statGoals    = $totalGoals ?? 0;
    $statAssists  = $totalAssists ?? 0;
    $statAvgGoals = $cntTotal > 0 ? number_format($statGoals / $cntTotal, 1) : '0.0';
@endphp

<div class="space-y-6">

    <!-- ================= KPI UTAMA (data primer) ================= -->
    <div class="grid grid-cols-2 xl:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)]">
            <div class="flex items-start justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#4062D6] flex items-center justify-center"><i data-lucide="users" class="w-5 h-5"></i></div>
                <a href="{{ route('users.index') }}" class="text-slate-300 hover:text-[#4062D6] transition-colors" aria-label="Kelola user"><i data-lucide="arrow-up-right" class="w-4 h-4"></i></a>
            </div>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total User</p>
            <h3 class="text-2xl font-black text-[#1e293b] mt-1">{{ $cntUsers }}</h3>
            <p class="text-[11px] text-slate-400 mt-1"><span class="font-bold text-emerald-500">{{ $cntUsersActive }}</span> aktif · <span class="font-bold text-rose-500">{{ $cntUsersInactive }}</span> nonaktif</p>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)]">
            <div class="flex items-start justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-orange-50 text-orange-500 flex items-center justify-center"><i data-lucide="shield" class="w-5 h-5"></i></div>
            </div>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Klub</p>
            <h3 class="text-2xl font-black text-[#1e293b] mt-1">{{ $cntClubs }}</h3>
            <p class="text-[11px] text-slate-400 mt-1">Klub terdaftar</p>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)]">
            <div class="flex items-start justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center"><i data-lucide="contact" class="w-5 h-5"></i></div>
                <a href="{{ route('players.index') }}" class="text-slate-300 hover:text-[#4062D6] transition-colors" aria-label="Kelola pemain"><i data-lucide="arrow-up-right" class="w-4 h-4"></i></a>
            </div>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Pemain</p>
            <h3 class="text-2xl font-black text-[#1e293b] mt-1">{{ $cntPlayers }}</h3>
            <p class="text-[11px] text-slate-400 mt-1"><span class="font-bold text-emerald-500">{{ $cntPlayersActive }}</span> berstatus aktif</p>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)]">
            <div class="flex items-start justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-500 flex items-center justify-center"><i data-lucide="calendar-days" class="w-5 h-5"></i></div>
                <a href="{{ route('matches.index') }}" class="text-slate-300 hover:text-[#4062D6] transition-colors" aria-label="Kelola jadwal"><i data-lucide="arrow-up-right" class="w-4 h-4"></i></a>
            </div>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Pertandingan</p>
            <h3 class="text-2xl font-black text-[#1e293b] mt-1">{{ $cntTotal }}</h3>
            <p class="text-[11px] text-slate-400 mt-1"><span class="font-bold text-orange-500">{{ $cntUpcoming }}</span> akan datang</p>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 items-start">

        <!-- ================= KOLOM KIRI (2/3) ================= -->
        <div class="xl:col-span-2 space-y-6">

            <!-- Jadwal Terdekat -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] p-6">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-2">
                        <i data-lucide="calendar-days" class="w-5 h-5 text-[#4062D6]"></i>
                        <h3 class="font-bold text-[#1e293b] text-[14px]">Pertandingan Terdekat</h3>
                    </div>
                    <a href="{{ route('matches.index') }}" class="inline-flex items-center gap-1 text-[11px] font-bold text-[#4062D6] hover:text-[#3251b5]">Lihat Jadwal <i data-lucide="arrow-right" class="w-3 h-3"></i></a>
                </div>

                <!-- Hero: pertandingan terdekat -->
                <div class="rounded-2xl border border-blue-100 bg-gradient-to-br from-blue-50/70 to-white p-6">
                    <div class="flex items-center justify-between mb-5">
                        <span class="text-[11px] font-bold text-slate-500">{{ $nextMatch->title ?? 'Belum ada kompetisi' }}</span>
                        @if($nextMatch)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-50 border border-blue-100 text-[#4062D6] rounded-full text-[10px] font-bold">
                                <i data-lucide="clock" class="w-3 h-3"></i>
                                {{ \Carbon\Carbon::parse($nextMatch->match_date)->diffForHumans() }}
                            </span>
                        @else
                            <span class="px-3 py-1 bg-slate-50 border border-slate-200 text-slate-400 rounded-full text-[10px] font-bold">Belum dijadwalkan</span>
                        @endif
                    </div>

                    <div class="flex items-center justify-center gap-8 md:gap-16 mb-6">
                        <div class="flex flex-col items-center gap-2">
                            <div class="w-16 h-16 rounded-full bg-[#183262] text-white flex items-center justify-center text-lg font-black">{{ $nextMatch ? strtoupper(substr($nextMatch->title, 0, 3)) : '-' }}</div>
                            <span class="text-[13px] font-bold text-[#1e293b] text-center">{{ $nextMatch->title ?? 'Tim Kita' }}</span>
                        </div>
                        <div class="text-2xl font-black text-slate-300">VS</div>
                        <div class="flex flex-col items-center gap-2">
                            <div class="w-16 h-16 rounded-full bg-blue-100 text-[#4062D6] flex items-center justify-center text-xl font-black">{{ $nextMatch ? strtoupper(substr($nextMatch->opponent ?? 'L', 0, 1)) : '-' }}</div>
                            <span class="text-[13px] font-bold text-[#1e293b] text-center">{{ $nextMatch->opponent ?? 'Tim Lawan' }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        <div class="bg-white border border-slate-100 rounded-xl px-3 py-2.5">
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5 flex items-center gap-1"><i data-lucide="calendar" class="w-3 h-3"></i> Tanggal</p>
                            <p class="text-[12px] font-bold text-[#1e293b]">{{ $nextMatch ? \Carbon\Carbon::parse($nextMatch->match_date)->format('d M Y') : '-' }}</p>
                        </div>
                        <div class="bg-white border border-slate-100 rounded-xl px-3 py-2.5">
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5 flex items-center gap-1"><i data-lucide="clock" class="w-3 h-3"></i> Kick-off</p>
                            <p class="text-[12px] font-bold text-[#1e293b]">{{ $nextMatch ? \Carbon\Carbon::parse($nextMatch->match_date)->format('H:i') . ' WIB' : '-' }}</p>
                        </div>
                        <div class="bg-white border border-slate-100 rounded-xl px-3 py-2.5 col-span-2 md:col-span-1">
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5 flex items-center gap-1"><i data-lucide="map-pin" class="w-3 h-3"></i> Lokasi</p>
                            <p class="text-[12px] font-bold text-[#1e293b] truncate">{{ $nextMatch->venue ?? '-' }}</p>
                        </div>
                        <div class="bg-white border border-slate-100 rounded-xl px-3 py-2.5 col-span-2 md:col-span-1">
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5 flex items-center gap-1"><i data-lucide="layout-grid" class="w-3 h-3"></i> Formasi</p>
                            <p class="text-[12px] font-bold text-[#1e293b]">{{ $nextMatch->formation ?? '-' }}</p>
                        </div>
                    </div>

                    @unless($nextMatch)
                        <p class="text-center text-[12px] text-slate-400 mt-5">Belum ada jadwal pertandingan mendatang.</p>
                    @endunless
                </div>

                <!-- Jadwal berikutnya -->
                <div class="mt-5">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3">Jadwal Berikutnya</p>
                    <div class="space-y-2">
                        @forelse($otherNext as $match)
                            <div class="flex items-center justify-between gap-3 px-4 py-3 rounded-xl border border-slate-100 bg-slate-50/50 hover:bg-slate-50 transition-colors">
                                <div class="flex items-center gap-4 min-w-0">
                                    <div class="w-12 shrink-0 text-center bg-white border border-slate-100 rounded-xl py-1.5">
                                        <p class="text-[12px] font-bold text-[#4062D6] leading-none">{{ \Carbon\Carbon::parse($match->match_date)->format('d M') }}</p>
                                        <p class="text-[9px] font-bold text-slate-400 mt-1">{{ \Carbon\Carbon::parse($match->match_date)->format('H:i') }}</p>
                                    </div>
                                    <div class="min-w-0">
                                        <h4 class="text-[13px] font-bold text-[#1e293b] truncate">{{ $match->title }} vs {{ $match->opponent ?? 'Tim Lawan' }}</h4>
                                        <p class="text-[11px] text-slate-400 mt-0.5">Formasi {{ $match->formation ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-1.5 text-slate-500 bg-white border border-slate-100 px-3 py-1.5 rounded-lg shrink-0">
                                    <i data-lucide="map-pin" class="w-3.5 h-3.5"></i>
                                    <span class="text-[10px] font-bold">{{ $match->venue ?? 'Lokasi belum diisi' }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="p-4 text-center border border-dashed border-slate-200 rounded-xl text-[12px] text-slate-400">Belum ada jadwal berikutnya.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Pemain Baru Ditambahkan -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] p-6">
                <div class="flex items-center justify-between mb-5">
                    <div class="flex items-center gap-2">
                        <i data-lucide="user-plus" class="w-5 h-5 text-[#4062D6]"></i>
                        <h3 class="font-bold text-[#1e293b] text-[14px]">Pemain Baru Ditambahkan</h3>
                    </div>
                    <a href="{{ route('players.index') }}" class="inline-flex items-center gap-1 text-[11px] font-bold text-[#4062D6] hover:text-[#3251b5]">Manajemen <i data-lucide="arrow-right" class="w-3 h-3"></i></a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[560px]">
                        <thead>
                            <tr class="border-b border-slate-100">
                                <th class="py-3 px-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Pemain</th>
                                <th class="py-3 px-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">No.</th>
                                <th class="py-3 px-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Posisi</th>
                                <th class="py-3 px-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Klub</th>
                                <th class="py-3 px-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Status</th>
                                <th class="py-3 px-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Ditambahkan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($recentPlayers ?? [] as $player)
                                @php
                                    $pNumber = $player->jersey_number ?? $player->no_punggung ?? $player->number ?? null;
                                    $pClub   = $player->club->name ?? $player->club_name ?? null;
                                    $pStatus = $player->status ?? 'Aktif';
                                @endphp
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="py-3 px-3">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-full bg-[#1e293b] text-white flex items-center justify-center text-[11px] font-bold shrink-0">{{ strtoupper(substr($player->name, 0, 1)) }}</div>
                                            <span class="text-[13px] font-bold text-[#1e293b]">{{ $player->name }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-3 text-[12px] font-bold text-slate-500 text-center">{{ $pNumber ?? '-' }}</td>
                                    <td class="py-3 px-3 text-center">
                                        <span class="px-2 py-0.5 bg-blue-50 text-[#4062D6] border border-blue-100 rounded text-[10px] font-bold">{{ $player->position ?? '-' }}</span>
                                    </td>
                                    <td class="py-3 px-3 text-[12px] font-medium text-slate-500">{{ $pClub ?? '-' }}</td>
                                    <td class="py-3 px-3 text-center">
                                        <span class="inline-flex items-center gap-1.5 text-[11px] font-medium text-slate-500">
                                            <span class="w-1.5 h-1.5 rounded-full {{ strtolower($pStatus) === 'aktif' ? 'bg-emerald-500' : 'bg-slate-300' }}"></span>{{ $pStatus }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-3 text-[11px] font-medium text-slate-400 text-right">{{ $player->created_at ? $player->created_at->format('d M Y') : '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-10 text-center text-[13px] font-medium text-slate-400">
                                        <div class="flex flex-col items-center gap-2">
                                            <i data-lucide="user-x" class="w-8 h-8 text-slate-300"></i>
                                            Belum ada pemain yang ditambahkan.
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ================= KOLOM KANAN (1/3) ================= -->
        <div class="space-y-6">

            <!-- Perlu Perhatian -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        <i data-lucide="triangle-alert" class="w-5 h-5 {{ $attentionTotal > 0 ? 'text-amber-500' : 'text-emerald-500' }}"></i>
                        <h3 class="font-bold text-[#1e293b] text-[14px]">Perlu Perhatian</h3>
                    </div>
                    @if($attentionTotal > 0)
                        <span class="px-2 py-0.5 bg-amber-50 border border-amber-100 text-amber-600 rounded-full text-[10px] font-bold">{{ $attentionTotal }}</span>
                    @endif
                </div>
                @if($attentionTotal === 0)
                    <div class="flex items-center gap-2 px-3 py-3 rounded-xl bg-emerald-50 border border-emerald-100 text-[12px] text-emerald-700 font-medium">
                        <i data-lucide="circle-check" class="w-4 h-4"></i> Semua data sudah lengkap.
                    </div>
                @else
                    <div class="space-y-2">
                        @foreach($attention as $item)
                            @continue($item['count'] === 0)
                            <a href="{{ $item['route'] }}" class="flex items-center justify-between gap-3 px-3 py-2.5 rounded-xl border border-slate-100 bg-slate-50/50 hover:bg-amber-50/50 hover:border-amber-100 transition-colors">
                                <span class="flex items-center gap-2.5 text-[12px] font-medium text-slate-600 min-w-0">
                                    <i data-lucide="{{ $item['icon'] }}" class="w-4 h-4 text-slate-400"></i>
                                    <span class="truncate">{{ $item['label'] }}</span>
                                </span>
                                <span class="text-[12px] font-black text-amber-600">{{ $item['count'] }}</span>
                            </a>
                        @endforeach
                    </div>
                @endif
                @if($cntLive > 0)
                    <a href="{{ route('matches.index') }}" class="mt-3 flex items-center gap-2 px-3 py-2.5 rounded-xl bg-rose-50 border border-rose-100 text-[12px] font-bold text-rose-600">
                        <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span> {{ $cntLive }} pertandingan sedang Live
                    </a>
                @endif
            </div>

            <!-- Informasi Pertandingan -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] p-6">
                <div class="flex items-center justify-between mb-5">
                    <div class="flex items-center gap-2">
                        <i data-lucide="trophy" class="w-5 h-5 text-[#4062D6]"></i>
                        <h3 class="font-bold text-[#1e293b] text-[14px]">Informasi Pertandingan</h3>
                    </div>
                    <a href="{{ route('matches.index') }}" class="text-[11px] font-bold text-[#4062D6] hover:text-[#3251b5]">Lihat semua</a>
                </div>
                <div class="flex h-2 rounded-full overflow-hidden bg-slate-100 mb-5">
                    <div class="bg-orange-400" style="width: {{ $pct($cntUpcoming) }}%"></div>
                    <div class="bg-rose-400" style="width: {{ $pct($cntLive) }}%"></div>
                    <div class="bg-emerald-500" style="width: {{ $pct($cntDone) }}%"></div>
                </div>
                <div class="grid grid-cols-4 text-center">
                    <div><p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Total</p><p class="text-[18px] font-black text-[#1e293b] mt-1">{{ $cntTotal }}</p></div>
                    <div><p class="text-[9px] font-bold text-orange-500 uppercase tracking-widest">Akan</p><p class="text-[18px] font-black text-[#1e293b] mt-1">{{ $cntUpcoming }}</p></div>
                    <div><p class="text-[9px] font-bold text-rose-500 uppercase tracking-widest">Live</p><p class="text-[18px] font-black text-[#1e293b] mt-1">{{ $cntLive }}</p></div>
                    <div><p class="text-[9px] font-bold text-emerald-500 uppercase tracking-widest">Selesai</p><p class="text-[18px] font-black text-[#1e293b] mt-1">{{ $cntDone }}</p></div>
                </div>
            </div>

            <!-- Pengguna per Role -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        <i data-lucide="users" class="w-5 h-5 text-[#4062D6]"></i>
                        <h3 class="font-bold text-[#1e293b] text-[14px]">Pengguna per Role</h3>
                    </div>
                    <a href="{{ route('users.index') }}" class="text-[11px] font-bold text-[#4062D6] hover:text-[#3251b5]">Kelola</a>
                </div>
                <div class="space-y-2.5">
                    @foreach($roleList as $roleName => $roleIcon)
                        @php $rc = $roleCounts[$roleName] ?? 0; @endphp
                        <div>
                            <div class="flex items-center justify-between text-[12px] mb-1">
                                <span class="flex items-center gap-2 font-medium text-slate-600"><i data-lucide="{{ $roleIcon }}" class="w-3.5 h-3.5 text-slate-400"></i>{{ $roleName }}</span>
                                <span class="font-black text-[#1e293b]">{{ $rc }}</span>
                            </div>
                            <div class="h-1.5 rounded-full bg-slate-100 overflow-hidden">
                                <div class="h-full bg-[#4062D6] rounded-full" style="width: {{ $cntUsers > 0 ? round($rc / $cntUsers * 100) : 0 }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Ringkasan Statistik (data sekunder) -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] p-6">
                <div class="flex items-center gap-2 mb-4">
                    <i data-lucide="chart-column" class="w-5 h-5 text-[#4062D6]"></i>
                    <h3 class="font-bold text-[#1e293b] text-[14px]">Ringkasan Statistik</h3>
                </div>
                <div class="grid grid-cols-3 gap-3">
                    <div class="bg-orange-50 rounded-xl py-3 text-center">
                        <p class="text-[18px] font-black text-orange-600">{{ $statGoals }}</p>
                        <p class="text-[9px] font-bold text-orange-500 uppercase tracking-wider mt-0.5">Total Gol</p>
                    </div>
                    <div class="bg-emerald-50 rounded-xl py-3 text-center">
                        <p class="text-[18px] font-black text-emerald-600">{{ $statAssists }}</p>
                        <p class="text-[9px] font-bold text-emerald-500 uppercase tracking-wider mt-0.5">Assist</p>
                    </div>
                    <div class="bg-blue-50 rounded-xl py-3 text-center">
                        <p class="text-[18px] font-black text-blue-600">{{ $statAvgGoals }}</p>
                        <p class="text-[9px] font-bold text-blue-500 uppercase tracking-wider mt-0.5">Gol / Match</p>
                    </div>
                </div>
            </div>

            <!-- Aksi cepat -->
            <div class="rounded-2xl bg-gradient-to-br from-[#183262] to-[#4062D6] p-6 text-white shadow-md">
                <p class="text-[10px] font-bold uppercase tracking-widest opacity-70 mb-1">Aksi Cepat</p>
                <h3 class="text-[18px] font-black leading-snug mb-4">Atur jadwal & data pemain terbaru</h3>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('matches.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-white text-[#183262] rounded-lg text-[11px] font-bold hover:bg-slate-100 transition-colors">
                        <i data-lucide="calendar-plus" class="w-3.5 h-3.5"></i> Tambah Jadwal
                    </a>
                    <a href="{{ route('players.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 border border-white/40 text-white rounded-lg text-[11px] font-bold hover:bg-white/10 transition-colors">
                        <i data-lucide="user-plus" class="w-3.5 h-3.5"></i> Tambah Pemain
                    </a>
                    <a href="{{ route('users.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 border border-white/40 text-white rounded-lg text-[11px] font-bold hover:bg-white/10 transition-colors">
                        <i data-lucide="user-cog" class="w-3.5 h-3.5"></i> Tambah User
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@elseif($activeRole === 'Pelatih')
@include('dashboard.coach')

@elseif($activeRole === 'Analis')
<div class="space-y-6">
    <!-- Panel Pertandingan Berlangsung (LIVE) atau Pesan Kosong -->
    @php
        // Mengambil pertandingan yang statusnya sedang 'Live'
        $liveMatch = \App\Models\MatchSession::where('status', 'Live')->first();
        // Mengambil pertandingan yang masih 'Upcoming' (mendatang)
        $upcomingMatchesList = \App\Models\MatchSession::where('status', 'Upcoming')->orderBy('match_date', 'asc')->take(3)->get();
    @endphp

    @if($liveMatch)
        <!-- Jika ada pertandingan yang LIVE -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] overflow-hidden">
            <div class="bg-[#4062D6] px-6 py-3 flex items-center justify-between text-white">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-rose-400 animate-pulse"></span>
                    <span class="text-[11px] font-black tracking-widest uppercase">LIVE <span class="font-normal opacity-70 ml-1 tracking-normal capitalize">— {{ $liveMatch->title }}</span></span>
                </div>
                <div class="flex items-center gap-1.5 text-[11px] font-bold">
                    <i data-lucide="clock" class="w-3.5 h-3.5"></i> Sedang Berlangsung
                </div>
            </div>
            <div class="p-8">
                <div class="flex items-center justify-center gap-8 md:gap-16 mb-6">
                    <div class="flex flex-col items-center gap-3">
                        <div class="w-16 h-16 md:w-20 md:h-20 bg-slate-100 rounded-full flex items-center justify-center">
                            <span class="font-black text-xl text-[#1e293b]">{{ substr($liveMatch->title, 0, 3) }}</span>
                        </div>
                        <div class="text-center">
                            <h3 class="text-[14px] md:text-[16px] font-bold text-[#1e293b]">{{ $liveMatch->title }}</h3>
                            <p class="text-[11px] text-slate-400">{{ $liveMatch->formation ?? '4-4-2' }}</p>
                        </div>
                    </div>
                    <div class="flex flex-col items-center">
                        <div class="text-3xl md:text-5xl font-black text-[#1e293b] tracking-wider mb-2">VS</div>
                        <span class="px-3 py-1 bg-rose-50 text-rose-500 border border-rose-100 rounded text-[9px] font-black tracking-widest uppercase">BERLANGSUNG</span>
                    </div>
                    <div class="flex flex-col items-center gap-3">
                        <div class="w-16 h-16 md:w-20 md:h-20 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold text-2xl">
                            {{ substr($liveMatch->opponent ?? 'L', 0, 1) }}
                        </div>
                        <div class="text-center">
                            <h3 class="text-[14px] md:text-[16px] font-bold text-[#1e293b]">{{ $liveMatch->opponent ?? 'Tim Lawan' }}</h3>
                            <p class="text-[11px] text-slate-400">Lawan</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Jika TIDAK ADA pertandingan yang LIVE -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] p-6 text-center">
            <div class="w-12 h-12 bg-slate-100 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-3">
                <i data-lucide="clock" class="w-6 h-6"></i>
            </div>
            <h4 class="text-[14px] font-bold text-slate-700">Tidak ada pertandingan yang sedang berlangsung</h4>
            <p class="text-[12px] text-slate-400 mt-1">Panel live analytics akan aktif otomatis saat jadwal pertandingan dimulai.</p>
        </div>
    @endif

    <!-- Panel Pertandingan Mendatang (Upcoming) -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] p-6">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-2">
                <i data-lucide="calendar-days" class="w-5 h-5 text-[#4062D6]"></i>
                <h3 class="font-bold text-[#1e293b]">Pertandingan Mendatang</h3>
            </div>
            <a href="{{ route('matches.index') }}" class="inline-flex items-center gap-1 text-[11px] font-bold text-[#4062D6] hover:text-[#3251b5]">Lihat semua <i data-lucide="chevron-right" class="w-3 h-3"></i></a>
        </div>
        <div class="space-y-3">
            @forelse($upcomingMatchesList as $match)
                <div class="flex items-center justify-between py-3 border-b border-slate-100 last:border-0">
                    <div class="flex gap-4 items-center">
                        <div class="text-center shrink-0 w-12 bg-slate-50 border border-slate-100 rounded-xl p-2">
                            <p class="text-[12px] font-bold text-[#4062D6]">{{ \Carbon\Carbon::parse($match->match_date)->format('d M') }}</p>
                            <p class="text-[9px] font-bold text-slate-400">{{ \Carbon\Carbon::parse($match->match_date)->format('H:i') }}</p>
                        </div>
                        <div>
                            <h4 class="text-[13px] font-bold text-[#1e293b]">vs {{ $match->opponent ?? 'Tim Lawan' }}</h4>
                            <p class="text-[11px] text-slate-500 font-medium mt-0.5">{{ $match->title }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-1.5 text-slate-400 bg-slate-50 px-3 py-1.5 rounded-lg">
                        <i data-lucide="map-pin" class="w-3.5 h-3.5"></i>
                        <span class="text-[10px] font-medium">{{ $match->venue ?? 'Stadion Utama' }}</span>
                    </div>
                </div>
            @empty
                <div class="p-6 text-center text-slate-400 text-[13px] flex flex-col items-center gap-2">
                    <i data-lucide="calendar-x" class="w-8 h-8 text-slate-300"></i>
                    Tidak ada jadwal pertandingan mendatang saat ini.
                </div>
            @endforelse
        </div>
    </div>
</div>

@elseif($activeRole === 'Pemain')
<div class="space-y-6">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] overflow-hidden">
        <div class="p-6 md:p-8 flex flex-col md:flex-row items-center md:items-start gap-6 relative">
            <div class="w-24 h-24 md:w-32 md:h-32 bg-[#183262] rounded-2xl flex flex-col items-center justify-center text-white shrink-0 shadow-md">
                <span class="text-2xl md:text-3xl font-black">YA</span>
            </div>
            <div class="flex-1 text-center md:text-left mt-2 md:mt-4">
                <h2 class="text-2xl font-black text-[#1e293b] tracking-tight">Yazid Ardiyan</h2>
                <div class="flex items-center justify-center md:justify-start gap-3 mt-2">
                    <span class="px-2.5 py-1 bg-slate-100 text-slate-600 rounded text-[11px] font-bold tracking-widest uppercase">#10</span>
                    <span class="px-2.5 py-1 bg-blue-50 text-[#4062D6] border border-blue-100 rounded text-[11px] font-bold tracking-widest uppercase">ST</span>
                </div>
            </div>
            <div class="absolute top-6 right-8 text-right hidden md:block">
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Klub</p>
                <p class="text-[13px] font-bold text-[#1e293b] flex items-center justify-end gap-1.5"><i data-lucide="shield" class="w-3.5 h-3.5 text-slate-400"></i> GhostClub</p>
            </div>
        </div>
        <div class="bg-slate-50 border-t border-slate-100 px-6 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">5 Match Terakhir</span>
                <div class="flex items-center gap-1.5">
                    <div class="w-6 h-6 rounded-full bg-emerald-500 text-white flex items-center justify-center text-[10px] font-bold">W</div>
                    <div class="w-6 h-6 rounded-full bg-rose-500 text-white flex items-center justify-center text-[10px] font-bold">L</div>
                    <div class="w-6 h-6 rounded-full bg-emerald-500 text-white flex items-center justify-center text-[10px] font-bold">W</div>
                    <div class="w-6 h-6 rounded-full bg-yellow-500 text-white flex items-center justify-center text-[10px] font-bold">D</div>
                    <div class="w-6 h-6 rounded-full bg-emerald-500 text-white flex items-center justify-center text-[10px] font-bold">W</div>
                </div>
            </div>
            <div class="flex items-center gap-4 text-[10px] font-bold text-slate-500">
                <div class="flex items-center gap-1.5"><div class="w-2 h-2 rounded-full bg-emerald-500"></div>Menang</div>
                <div class="flex items-center gap-1.5"><div class="w-2 h-2 rounded-full bg-yellow-500"></div>Seri</div>
                <div class="flex items-center gap-1.5"><div class="w-2 h-2 rounded-full bg-rose-500"></div>Kalah</div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] flex flex-col justify-center">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 flex items-center gap-1.5"><i data-lucide="timer" class="w-3.5 h-3.5"></i> Matches Played</p>
            <h3 class="text-4xl font-black text-[#4062D6]">12</h3>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] flex flex-col justify-center">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 flex items-center gap-1.5"><i data-lucide="goal" class="w-3.5 h-3.5"></i> My Goals</p>
            <h3 class="text-4xl font-black text-[#4062D6]">8</h3>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] flex flex-col justify-center">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 flex items-center gap-1.5"><i data-lucide="handshake" class="w-3.5 h-3.5"></i> My Assists</p>
            <h3 class="text-4xl font-black text-[#4062D6]">4</h3>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] grid grid-cols-2 md:grid-cols-6 divide-y md:divide-y-0 md:divide-x divide-slate-100">
        <div class="p-4 flex flex-col items-center justify-center text-center">
            <span class="text-2xl font-black text-orange-500 mb-1">8.4</span>
            <span class="text-[10px] font-bold text-slate-400">Avg Rating</span>
        </div>
        <div class="p-4 flex flex-col items-center justify-center text-center">
            <span class="text-2xl font-black text-[#4062D6] mb-1">980</span>
            <span class="text-[10px] font-bold text-slate-400">Menit Main</span>
        </div>
        <div class="p-4 flex flex-col items-center justify-center text-center">
            <span class="text-2xl font-black text-yellow-500 mb-1">1</span>
            <span class="text-[10px] font-bold text-slate-400">Kartu Kuning</span>
        </div>
        <div class="p-4 flex flex-col items-center justify-center text-center">
            <span class="text-2xl font-black text-rose-500 mb-1">0</span>
            <span class="text-[10px] font-bold text-slate-400">Kartu Merah</span>
        </div>
        <div class="p-4 flex flex-col items-center justify-center text-center">
            <span class="text-2xl font-black text-emerald-500 mb-1">24</span>
            <span class="text-[10px] font-bold text-slate-400">Tembakan</span>
        </div>
        <div class="p-4 flex flex-col items-center justify-center text-center">
            <span class="text-2xl font-black text-purple-500 mb-1">18</span>
            <span class="text-[10px] font-bold text-slate-400">Dribel Sukses</span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)]">
            <div class="flex items-center gap-2 mb-6">
                <i data-lucide="radar" class="w-4 h-4 text-[#4062D6]"></i>
                <h3 class="font-bold text-[#1e293b] text-[13px]">Radar Atribut Pemain</h3>
            </div>
            <div class="relative h-[250px] w-full flex justify-center">
                <canvas id="radarChart"></canvas>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)]">
            <div class="flex items-center gap-2 mb-6">
                <i data-lucide="chart-column" class="w-4 h-4 text-[#4062D6]"></i>
                <h3 class="font-bold text-[#1e293b] text-[13px]">Gol per Bulan — 2026</h3>
            </div>
            <div class="relative h-[250px] w-full">
                <canvas id="barChart"></canvas>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] p-6">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-2">
                <i data-lucide="history" class="w-5 h-5 text-[#4062D6]"></i>
                <h3 class="font-bold text-[#1e293b]">Riwayat Pertandingan Anda</h3>
            </div>
            <a href="#" class="inline-flex items-center gap-1 text-[11px] font-bold text-[#4062D6] hover:text-[#3251b5]">Semua <i data-lucide="chevron-right" class="w-3 h-3"></i></a>
        </div>
        <div class="space-y-3">
            <div class="flex items-center justify-between p-4 rounded-xl border border-slate-100 hover:bg-slate-50 transition-colors">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-500 flex items-center justify-center shrink-0">
                        <i data-lucide="chart-column" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h4 class="text-[13px] font-bold text-[#1e293b]">Liga1 <span class="text-emerald-500 ml-2">Menang 2-1</span></h4>
                        <p class="text-[11px] text-slate-500 mt-1">29 June 2026 - vs SSB Kretek</p>
                    </div>
                </div>
                <div class="flex items-center gap-6">
                    <div class="flex gap-4 text-center">
                        <div><p class="text-[13px] font-black text-[#1e293b]">1</p><p class="text-[9px] font-bold text-slate-400">Gol</p></div>
                        <div><p class="text-[13px] font-black text-[#1e293b]">0</p><p class="text-[9px] font-bold text-slate-400">Assist</p></div>
                        <div><p class="text-[13px] font-black text-[#1e293b]">8.5</p><p class="text-[9px] font-bold text-slate-400">Rating</p></div>
                    </div>
                    <div class="flex gap-2">
                        <a href="#" class="inline-flex items-center gap-1 px-3 py-1.5 border border-blue-200 text-blue-600 rounded-lg text-[10px] font-bold hover:bg-blue-50"><i data-lucide="file-down" class="w-3 h-3"></i> PDF</a>
                        <a href="#" class="inline-flex items-center gap-1 px-3 py-1.5 border border-slate-200 text-slate-600 rounded-lg text-[10px] font-bold hover:bg-slate-50"><i data-lucide="eye" class="w-3 h-3"></i> Lihat Evaluasi</a>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between p-4 rounded-xl border border-slate-100 hover:bg-slate-50 transition-colors">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-lg bg-rose-50 text-rose-500 flex items-center justify-center shrink-0">
                        <i data-lucide="chart-column" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h4 class="text-[13px] font-bold text-[#1e293b]">Liga1 <span class="text-rose-500 ml-2">Kalah 0-2</span></h4>
                        <p class="text-[11px] text-slate-500 mt-1">14 June 2026 - vs SSB Sleman</p>
                    </div>
                </div>
                <div class="flex items-center gap-6">
                    <div class="flex gap-4 text-center">
                        <div><p class="text-[13px] font-black text-[#1e293b]">0</p><p class="text-[9px] font-bold text-slate-400">Gol</p></div>
                        <div><p class="text-[13px] font-black text-[#1e293b]">0</p><p class="text-[9px] font-bold text-slate-400">Assist</p></div>
                        <div><p class="text-[13px] font-black text-[#1e293b]">6.2</p><p class="text-[9px] font-bold text-slate-400">Rating</p></div>
                    </div>
                    <div class="flex gap-2">
                        <a href="#" class="inline-flex items-center gap-1 px-3 py-1.5 border border-blue-200 text-blue-600 rounded-lg text-[10px] font-bold hover:bg-blue-50"><i data-lucide="file-down" class="w-3 h-3"></i> PDF</a>
                        <a href="#" class="inline-flex items-center gap-1 px-3 py-1.5 border border-slate-200 text-slate-600 rounded-lg text-[10px] font-bold hover:bg-slate-50"><i data-lucide="eye" class="w-3 h-3"></i> Lihat Evaluasi</a>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between p-4 rounded-xl border border-slate-100 hover:bg-slate-50 transition-colors">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-500 flex items-center justify-center shrink-0">
                        <i data-lucide="chart-column" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h4 class="text-[13px] font-bold text-[#1e293b]">Liga1 <span class="text-emerald-500 ml-2">Menang 3-1</span></h4>
                        <p class="text-[11px] text-slate-500 mt-1">1 June 2026 - vs SSB Bantul</p>
                    </div>
                </div>
                <div class="flex items-center gap-6">
                    <div class="flex gap-4 text-center">
                        <div><p class="text-[13px] font-black text-[#1e293b]">2</p><p class="text-[9px] font-bold text-slate-400">Gol</p></div>
                        <div><p class="text-[13px] font-black text-[#1e293b]">1</p><p class="text-[9px] font-bold text-slate-400">Assist</p></div>
                        <div><p class="text-[13px] font-black text-[#1e293b]">9.0</p><p class="text-[9px] font-bold text-slate-400">Rating</p></div>
                    </div>
                    <div class="flex gap-2">
                        <a href="#" class="inline-flex items-center gap-1 px-3 py-1.5 border border-blue-200 text-blue-600 rounded-lg text-[10px] font-bold hover:bg-blue-50"><i data-lucide="file-down" class="w-3 h-3"></i> PDF</a>
                        <a href="#" class="inline-flex items-center gap-1 px-3 py-1.5 border border-slate-200 text-slate-600 rounded-lg text-[10px] font-bold hover:bg-slate-50"><i data-lucide="eye" class="w-3 h-3"></i> Lihat Evaluasi</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        if(document.getElementById('radarChart')) {
            const ctxRadar = document.getElementById('radarChart').getContext('2d');
            new Chart(ctxRadar, {
                type: 'radar',
                data: {
                    labels: ['Kecepatan', 'Teknik', 'Penyelesaian', 'Dribel', 'Stamina', 'Kerja Tim'],
                    datasets: [{
                        label: 'Atribut',
                        data: [85, 75, 80, 88, 70, 65],
                        backgroundColor: 'rgba(64, 98, 214, 0.2)',
                        borderColor: '#4062D6',
                        pointBackgroundColor: '#4062D6',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: '#4062D6',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        r: {
                            angleLines: { color: 'rgba(0,0,0,0.05)' },
                            grid: { color: 'rgba(0,0,0,0.05)' },
                            pointLabels: { font: { size: 10, family: 'Inter' }, color: '#94a3b8' },
                            ticks: { display: false, min: 0, max: 100 }
                        }
                    },
                    plugins: { legend: { display: false } }
                }
            });
        }

        if(document.getElementById('barChart')) {
            const ctxBar = document.getElementById('barChart').getContext('2d');
            new Chart(ctxBar, {
                type: 'bar',
                data: {
                    labels: ['Mar', 'Apr', 'Mei', 'Jun', 'Jul'],
                    datasets: [{
                        label: 'Gol',
                        data: [0, 2, 1, 3, 2],
                        backgroundColor: ['#e2e8f0', '#bfdbfe', '#e2e8f0', '#bfdbfe', '#4062D6'],
                        borderRadius: 4,
                        barThickness: 24
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1, font: { size: 10, family: 'Inter' }, color: '#94a3b8' },
                            grid: { color: 'rgba(0,0,0,0.05)', drawBorder: false }
                        },
                        x: {
                            ticks: { font: { size: 10, family: 'Inter' }, color: '#94a3b8' },
                            grid: { display: false, drawBorder: false }
                        }
                    },
                    plugins: { legend: { display: false } }
                }
            });
        }
    });
    </script>
</div>
@endif

<!-- Lucide Icons (hapus baris ini jika layouts.app sudah memuat Lucide) -->
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (window.lucide) lucide.createIcons({ attrs: { 'stroke-width': 2 } });
    });
</script>
@endsection
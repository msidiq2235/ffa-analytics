@extends('layouts.app')
@section('title', 'Dashboard - FFA Analytics')
@php
$activeRole = session('active_role', auth()->user()->role ?? 'Super Admin');
@endphp
@section('header', 'Dashboard ' . $activeRole)
@section('subheader', 'Overview data, jadwal, dan statistik performa platform')
@section('content')

@if($activeRole === 'Super Admin' || $activeRole === 'Admin')
@php
    // ===== Data tampilan Admin (semua punya fallback agar card/tabel tetap tampil walau data kosong) =====
    $nextMatches = \App\Models\MatchSession::where('status', 'Upcoming')->orderBy('match_date', 'asc')->take(4)->get();
    $nextMatch   = $nextMatches->first();
    $otherNext   = $nextMatches->slice(1);

    $cntTotal    = $totalMatches ?? \App\Models\MatchSession::count();
    $cntUpcoming = \App\Models\MatchSession::where('status', 'Upcoming')->count();
    $cntLive     = \App\Models\MatchSession::where('status', 'Live')->count();
    $cntDone     = max($cntTotal - $cntUpcoming - $cntLive, 0);
    $pct         = fn($n) => $cntTotal > 0 ? round($n / $cntTotal * 100) : 0;

    $statGoals    = $totalGoals ?? 0;
    $statAssists  = $totalAssists ?? 0;
    $statAvgGoals = $cntTotal > 0 ? number_format($statGoals / $cntTotal, 1) : '0.0';
    $topScorers   = $topScorers ?? [];
@endphp
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 items-start">

    <!-- ================= KOLOM KIRI (2/3) ================= -->
    <div class="xl:col-span-2 space-y-6">

        <!-- Jadwal Terdekat (Upcoming) -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] p-6">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#4062D6]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <h3 class="font-bold text-[#1e293b] text-[14px]">Pertandingan Terdekat</h3>
                </div>
                <a href="{{ route('matches.index') }}" class="text-[11px] font-bold text-[#4062D6] hover:text-[#3251b5]">Lihat Jadwal</a>
            </div>

            <!-- Hero: pertandingan terdekat -->
            <div class="rounded-2xl border border-blue-100 bg-gradient-to-br from-blue-50/70 to-white p-6">
                <div class="flex items-center justify-between mb-5">
                    <span class="text-[11px] font-bold text-slate-500">{{ $nextMatch->title ?? 'Belum ada kompetisi' }}</span>
                    @if($nextMatch)
                        <span class="px-3 py-1 bg-blue-50 border border-blue-100 text-[#4062D6] rounded-full text-[10px] font-bold">
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
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Tanggal</p>
                        <p class="text-[12px] font-bold text-[#1e293b]">{{ $nextMatch ? \Carbon\Carbon::parse($nextMatch->match_date)->format('d M Y') : '-' }}</p>
                    </div>
                    <div class="bg-white border border-slate-100 rounded-xl px-3 py-2.5">
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Kick-off</p>
                        <p class="text-[12px] font-bold text-[#1e293b]">{{ $nextMatch ? \Carbon\Carbon::parse($nextMatch->match_date)->format('H:i') . ' WIB' : '-' }}</p>
                    </div>
                    <div class="bg-white border border-slate-100 rounded-xl px-3 py-2.5 col-span-2 md:col-span-1">
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Lokasi</p>
                        <p class="text-[12px] font-bold text-[#1e293b] truncate">{{ $nextMatch->venue ?? '-' }}</p>
                    </div>
                    <div class="bg-white border border-slate-100 rounded-xl px-3 py-2.5 col-span-2 md:col-span-1">
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Formasi</p>
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
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
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
                <h3 class="font-bold text-[#1e293b] text-[14px]">Pemain Baru Ditambahkan</h3>
                <a href="{{ route('players.index') }}" class="text-[11px] font-bold text-[#4062D6] hover:text-[#3251b5]">Manajemen</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[640px]">
                    <thead>
                        <tr class="border-b border-slate-100">
                            <th class="py-3 px-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Pemain</th>
                            <th class="py-3 px-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">No.</th>
                            <th class="py-3 px-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Posisi</th>
                            <th class="py-3 px-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Klub</th>
                            <th class="py-3 px-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Usia</th>
                            <th class="py-3 px-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Status</th>
                            <th class="py-3 px-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Ditambahkan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($recentPlayers ?? [] as $player)
                            @php
                                $pNumber = $player->jersey_number ?? $player->no_punggung ?? $player->number ?? null;
                                $pBirth  = $player->birth_date ?? $player->date_of_birth ?? $player->tanggal_lahir ?? null;
                                $pAge    = $pBirth ? \Carbon\Carbon::parse($pBirth)->age : null;
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
                                <td class="py-3 px-3 text-[12px] font-bold text-slate-500 text-center">{{ $pAge !== null ? $pAge . ' th' : '-' }}</td>
                                <td class="py-3 px-3 text-center">
                                    <span class="inline-flex items-center gap-1.5 text-[11px] font-medium text-slate-500">
                                        <span class="w-1.5 h-1.5 rounded-full {{ strtolower($pStatus) === 'aktif' ? 'bg-emerald-500' : 'bg-slate-300' }}"></span>{{ $pStatus }}
                                    </span>
                                </td>
                                <td class="py-3 px-3 text-[11px] font-medium text-slate-400 text-right">{{ $player->created_at ? $player->created_at->format('d M Y') : '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-10 text-center text-[13px] font-medium text-slate-400">Belum ada pemain yang ditambahkan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ================= KOLOM KANAN (1/3) ================= -->
    <div class="space-y-6">

        <!-- Statistik Pertandingan -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] p-6">
            <div class="flex items-center justify-between mb-5">
                <h3 class="font-bold text-[#1e293b] text-[14px]">Informasi Pertandingan</h3>
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

        <!-- Ringkasan: User, Klub, Pemain, Match -->
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)]">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#4062D6] flex items-center justify-center mb-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total User</p>
                <h3 class="text-2xl font-black text-[#1e293b] mt-1">{{ $totalUsers ?? 0 }}</h3>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)]">
                <div class="w-10 h-10 rounded-xl bg-orange-50 text-orange-500 flex items-center justify-center mb-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                </div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Klub</p>
                <h3 class="text-2xl font-black text-[#1e293b] mt-1">{{ $totalClubs ?? 0 }}</h3>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)]">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center mb-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
                </div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Pemain</p>
                <h3 class="text-2xl font-black text-[#1e293b] mt-1">{{ $totalPlayers ?? 0 }}</h3>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)]">
                <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-500 flex items-center justify-center mb-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Match</p>
                <h3 class="text-2xl font-black text-[#1e293b] mt-1">{{ $cntTotal }}</h3>
            </div>
        </div>

        <!-- Statistik Pemain -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] p-6">
            <h3 class="font-bold text-[#1e293b] text-[14px] mb-5">Statistik Pemain</h3>
            <div class="grid grid-cols-3 gap-3 mb-5">
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
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Top Skor</p>
            <div class="divide-y divide-slate-50">
                @forelse($topScorers as $i => $scorer)
                    <div class="flex items-center justify-between py-2.5">
                        <div class="flex items-center gap-3">
                            <span class="w-5 text-[11px] font-bold text-slate-400">{{ $i + 1 }}</span>
                            <span class="text-[12px] font-bold text-[#1e293b]">{{ $scorer->name }}</span>
                        </div>
                        <span class="text-[12px] font-black text-[#4062D6]">{{ $scorer->goals ?? 0 }} gol</span>
                    </div>
                @empty
                    <div class="py-6 text-center border border-dashed border-slate-200 rounded-xl text-[12px] text-slate-400">Belum ada data statistik pemain.</div>
                @endforelse
            </div>
        </div>

        <!-- Aksi cepat -->
        <div class="rounded-2xl bg-gradient-to-br from-[#183262] to-[#4062D6] p-6 text-white shadow-md">
            <p class="text-[10px] font-bold uppercase tracking-widest opacity-70 mb-1">Jangan Lupa</p>
            <h3 class="text-[18px] font-black leading-snug mb-4">Atur jadwal & data pemain terbaru</h3>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('matches.index') }}" class="px-4 py-2 bg-white text-[#183262] rounded-lg text-[11px] font-bold hover:bg-slate-100 transition-colors">Kelola Jadwal</a>
                <a href="{{ route('players.index') }}" class="px-4 py-2 border border-white/40 text-white rounded-lg text-[11px] font-bold hover:bg-white/10 transition-colors">Kelola Pemain</a>
            </div>
        </div>
    </div>
</div>

@elseif($activeRole === 'Pelatih')
<div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)]">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Upcoming Matches</p>
            <h3 class="text-3xl font-black text-[#1e293b]">{{ $upcomingMatches ?? 2 }}</h3>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)]">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Goals Scored</p>
            <h3 class="text-3xl font-black text-[#4062D6]">{{ $goalsScored ?? 1 }}</h3>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)]">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Total Assists</p>
            <h3 class="text-3xl font-black text-emerald-500">{{ $totalAssists ?? 0 }}</h3>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)]">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Matches Completed</p>
            <h3 class="text-3xl font-black text-[#1e293b]">{{ $matchesCompleted ?? 1 }}</h3>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="font-bold text-[#1e293b]">Persiapan: Jadwal Mendatang</h3>
                <a href="{{ route('matches.index') }}" class="text-[11px] font-bold text-[#4062D6] px-3 py-1.5 rounded-lg border border-blue-100 bg-blue-50 hover:bg-blue-100 transition-colors">Buka Jadwal</a>
            </div>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 rounded-xl border border-slate-100 bg-slate-50/50 hover:bg-slate-50 transition-colors">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-white border border-slate-200 flex flex-col items-center justify-center shrink-0 shadow-sm">
                            <span class="text-[10px] font-bold text-slate-400 leading-none">5</span>
                            <span class="text-[10px] font-bold text-slate-600 leading-none mt-1">Jul</span>
                        </div>
                        <div>
                            <h4 class="text-[13px] font-bold text-[#1e293b]">vs SSB Bantul</h4>
                            <p class="text-[11px] text-slate-500 font-medium">Liga1 — P8</p>
                            <div class="flex items-center gap-1 mt-1.5 text-slate-400">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                <span class="text-[10px]">Stadion Mandala Krida</span>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-[13px] font-bold text-[#1e293b]">15.00 WIB</p>
                        <p class="text-[10px] font-bold text-[#4062D6] mt-1">Mendatang</p>
                    </div>
                </div>
                <div class="flex items-center justify-between p-4 rounded-xl border border-slate-100 bg-slate-50/50 hover:bg-slate-50 transition-colors">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-white border border-slate-200 flex flex-col items-center justify-center shrink-0 shadow-sm">
                            <span class="text-[10px] font-bold text-slate-400 leading-none">12</span>
                            <span class="text-[10px] font-bold text-slate-600 leading-none mt-1">Jul</span>
                        </div>
                        <div>
                            <h4 class="text-[13px] font-bold text-[#1e293b]">vs SSB Piyungan</h4>
                            <p class="text-[11px] text-slate-500 font-medium">Liga1 — P9</p>
                            <div class="flex items-center gap-1 mt-1.5 text-slate-400">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                <span class="text-[10px]">Lapangan Piyungan</span>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-[13px] font-bold text-[#1e293b]">09.00 WIB</p>
                        <p class="text-[10px] font-bold text-[#4062D6] mt-1">Mendatang</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="font-bold text-[#1e293b]">Hasil Pertandingan Terakhir</h3>
                <a href="#" class="text-[11px] font-bold text-[#4062D6] hover:text-[#3251b5]">Semua &gt;</a>
            </div>
            <div class="space-y-4 mb-6">
                <div class="flex items-center justify-between p-4 rounded-xl border border-slate-100 bg-slate-50/50 relative overflow-hidden">
                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-emerald-500"></div>
                    <div>
                        <h4 class="text-[13px] font-bold text-[#1e293b] mb-1">Liga1</h4>
                        <p class="text-[10px] text-slate-400 mb-1.5">29 June 2026</p>
                        <p class="text-[12px] font-bold text-emerald-600">Menang 2-1 <span class="text-slate-400 font-medium ml-1">vs SSB Kretek</span></p>
                    </div>
                    <a href="#" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-[11px] font-bold text-slate-600 hover:bg-slate-50 shadow-sm">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg> Evaluasi
                    </a>
                </div>
                <div class="flex items-center justify-between p-4 rounded-xl border border-slate-100 bg-slate-50/50 relative overflow-hidden">
                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-rose-500"></div>
                    <div>
                        <h4 class="text-[13px] font-bold text-[#1e293b] mb-1">Liga1</h4>
                        <p class="text-[10px] text-slate-400 mb-1.5">14 June 2026</p>
                        <p class="text-[12px] font-bold text-rose-600">Kalah 0-2 <span class="text-slate-400 font-medium ml-1">vs SSB Sleman</span></p>
                    </div>
                    <a href="#" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-[11px] font-bold text-slate-600 hover:bg-slate-50 shadow-sm">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg> Evaluasi
                    </a>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-3">
                <div class="bg-emerald-50 rounded-xl p-3 flex flex-col items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-500 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="text-[18px] font-black text-emerald-600">8</span>
                    <span class="text-[10px] font-bold text-emerald-500 uppercase tracking-wider">Menang</span>
                </div>
                <div class="bg-orange-50 rounded-xl p-3 flex flex-col items-center justify-center">
                    <svg class="w-5 h-5 text-orange-500 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="text-[18px] font-black text-orange-600">16</span>
                    <span class="text-[10px] font-bold text-orange-500 uppercase tracking-wider">Gol</span>
                </div>
                <div class="bg-blue-50 rounded-xl p-3 flex flex-col items-center justify-center">
                    <svg class="w-5 h-5 text-blue-500 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                    <span class="text-[18px] font-black text-blue-600">4</span>
                    <span class="text-[10px] font-bold text-blue-500 uppercase tracking-wider">Seri</span>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] p-6">
        <div class="flex items-center gap-2 mb-6">
            <svg class="w-5 h-5 text-[#4062D6]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.898 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
            <h3 class="font-bold text-[#1e293b]">Top Performa Pemain — Musim 2026</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100">
                        <th class="py-3 px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest w-[5%]">#</th>
                        <th class="py-3 px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest w-[30%]">Pemain</th>
                        <th class="py-3 px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">No.</th>
                        <th class="py-3 px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Posisi</th>
                        <th class="py-3 px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Main</th>
                        <th class="py-3 px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Gol</th>
                        <th class="py-3 px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Assist</th>
                        <th class="py-3 px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Rating</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="py-4 px-4 text-[12px] font-bold text-slate-500">1</td>
                        <td class="py-4 px-4 text-[13px] font-bold text-[#1e293b]">Yazid Ardiyan</td>
                        <td class="py-4 px-4 text-[12px] font-bold text-slate-500 text-center">10</td>
                        <td class="py-4 px-4 text-[12px] font-bold text-slate-500 text-center">ST</td>
                        <td class="py-4 px-4 text-[12px] font-bold text-slate-500 text-center">12</td>
                        <td class="py-4 px-4 text-[12px] font-bold text-slate-500 text-center">8</td>
                        <td class="py-4 px-4 text-[12px] font-bold text-slate-500 text-center">4</td>
                        <td class="py-4 px-4 text-[13px] font-black text-emerald-500 text-right">8.4</td>
                    </tr>
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="py-4 px-4 text-[12px] font-bold text-slate-500">2</td>
                        <td class="py-4 px-4 text-[13px] font-bold text-[#1e293b]">Arif Paradigma</td>
                        <td class="py-4 px-4 text-[12px] font-bold text-slate-500 text-center">69</td>
                        <td class="py-4 px-4 text-[12px] font-bold text-slate-500 text-center">GK</td>
                        <td class="py-4 px-4 text-[12px] font-bold text-slate-500 text-center">11</td>
                        <td class="py-4 px-4 text-[12px] font-bold text-slate-500 text-center">0</td>
                        <td class="py-4 px-4 text-[12px] font-bold text-slate-500 text-center">2</td>
                        <td class="py-4 px-4 text-[13px] font-black text-orange-500 text-right">7.9</td>
                    </tr>
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="py-4 px-4 text-[12px] font-bold text-slate-500">3</td>
                        <td class="py-4 px-4 text-[13px] font-bold text-[#1e293b]">Sandy NurWidjan</td>
                        <td class="py-4 px-4 text-[12px] font-bold text-slate-500 text-center">17</td>
                        <td class="py-4 px-4 text-[12px] font-bold text-slate-500 text-center">LW</td>
                        <td class="py-4 px-4 text-[12px] font-bold text-slate-500 text-center">10</td>
                        <td class="py-4 px-4 text-[12px] font-bold text-slate-500 text-center">3</td>
                        <td class="py-4 px-4 text-[12px] font-bold text-slate-500 text-center">5</td>
                        <td class="py-4 px-4 text-[13px] font-black text-orange-500 text-right">7.6</td>
                    </tr>
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="py-4 px-4 text-[12px] font-bold text-slate-500">4</td>
                        <td class="py-4 px-4 text-[13px] font-bold text-[#1e293b]">Anan Zada</td>
                        <td class="py-4 px-4 text-[12px] font-bold text-slate-500 text-center">6</td>
                        <td class="py-4 px-4 text-[12px] font-bold text-slate-500 text-center">RB</td>
                        <td class="py-4 px-4 text-[12px] font-bold text-slate-500 text-center">12</td>
                        <td class="py-4 px-4 text-[12px] font-bold text-slate-500 text-center">1</td>
                        <td class="py-4 px-4 text-[12px] font-bold text-slate-500 text-center">6</td>
                        <td class="py-4 px-4 text-[13px] font-black text-slate-600 text-right">7.3</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

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
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Sedang Berlangsung
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
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <h4 class="text-[14px] font-bold text-slate-700">Tidak ada pertandingan yang sedang berlangsung</h4>
            <p class="text-[12px] text-slate-400 mt-1">Panel live analytics akan aktif otomatis saat jadwal pertandingan dimulai.</p>
        </div>
    @endif

    <!-- Panel Pertandingan Mendatang (Upcoming) -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] p-6">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-[#4062D6]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <h3 class="font-bold text-[#1e293b]">Pertandingan Mendatang</h3>
            </div>
            <a href="{{ route('matches.index') }}" class="text-[11px] font-bold text-[#4062D6] hover:text-[#3251b5]">Lihat semua &gt;</a>
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
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <span class="text-[10px] font-medium">{{ $match->venue ?? 'Stadion Utama' }}</span>
                    </div>
                </div>
            @empty
                <div class="p-6 text-center text-slate-400 text-[13px]">
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
                <p class="text-[13px] font-bold text-[#1e293b]">GhostClub</p>
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
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Matches Played</p>
            <h3 class="text-4xl font-black text-[#4062D6]">12</h3>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] flex flex-col justify-center">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">My Goals</p>
            <h3 class="text-4xl font-black text-[#4062D6]">8</h3>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] flex flex-col justify-center">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">My Assists</p>
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
                <svg class="w-4 h-4 text-[#4062D6]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.898 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                <h3 class="font-bold text-[#1e293b] text-[13px]">Radar Atribut Pemain</h3>
            </div>
            <div class="relative h-[250px] w-full flex justify-center">
                <canvas id="radarChart"></canvas>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)]">
            <div class="flex items-center gap-2 mb-6">
                <div class="w-3 h-3 rounded-full bg-[#4062D6]"></div>
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
                <svg class="w-5 h-5 text-[#4062D6]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 02-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                <h3 class="font-bold text-[#1e293b]">Riwayat Pertandingan Anda</h3>
            </div>
            <a href="#" class="text-[11px] font-bold text-[#4062D6] hover:text-[#3251b5]">Semua &gt;</a>
        </div>
        <div class="space-y-3">
            <div class="flex items-center justify-between p-4 rounded-xl border border-slate-100 hover:bg-slate-50 transition-colors">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-500 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
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
                        <a href="#" class="px-3 py-1.5 border border-blue-200 text-blue-600 rounded-lg text-[10px] font-bold hover:bg-blue-50">PDF</a>
                        <a href="#" class="px-3 py-1.5 border border-slate-200 text-slate-600 rounded-lg text-[10px] font-bold hover:bg-slate-50">Lihat Evaluasi</a>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between p-4 rounded-xl border border-slate-100 hover:bg-slate-50 transition-colors">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-lg bg-rose-50 text-rose-500 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
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
                        <a href="#" class="px-3 py-1.5 border border-blue-200 text-blue-600 rounded-lg text-[10px] font-bold hover:bg-blue-50">PDF</a>
                        <a href="#" class="px-3 py-1.5 border border-slate-200 text-slate-600 rounded-lg text-[10px] font-bold hover:bg-slate-50">Lihat Evaluasi</a>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between p-4 rounded-xl border border-slate-100 hover:bg-slate-50 transition-colors">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-500 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
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
                        <a href="#" class="px-3 py-1.5 border border-blue-200 text-blue-600 rounded-lg text-[10px] font-bold hover:bg-blue-50">PDF</a>
                        <a href="#" class="px-3 py-1.5 border border-slate-200 text-slate-600 rounded-lg text-[10px] font-bold hover:bg-slate-50">Lihat Evaluasi</a>
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
@endsection
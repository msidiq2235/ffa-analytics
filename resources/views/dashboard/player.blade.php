@if($playerProfile)
    @php
        $playerDisplayName = $playerProfile->popular_name ?: $playerProfile->name;
        $playerInitials = collect(preg_split('/\s+/', trim($playerDisplayName)))
            ->filter()
            ->take(2)
            ->map(fn ($part) => mb_substr($part, 0, 1))
            ->implode('');
        $playerMetrics = [
            ['key' => 'matches', 'label' => 'Pertandingan Selesai', 'value' => $playerDashboardTotals->matches, 'icon' => 'calendar-check', 'tone' => 'text-blue-600 bg-blue-50'],
            ['key' => 'goals', 'label' => 'Gol', 'value' => $playerDashboardTotals->goals, 'icon' => 'goal', 'tone' => 'text-orange-600 bg-orange-50'],
            ['key' => 'assists', 'label' => 'Assist', 'value' => $playerDashboardTotals->assists, 'icon' => 'handshake', 'tone' => 'text-violet-600 bg-violet-50'],
            ['key' => 'shots', 'label' => 'Tembakan', 'value' => $playerDashboardTotals->shots, 'icon' => 'target', 'tone' => 'text-rose-600 bg-rose-50'],
            ['key' => 'passes', 'label' => 'Umpan Berhasil', 'value' => $playerDashboardTotals->passes, 'icon' => 'route', 'tone' => 'text-emerald-600 bg-emerald-50'],
            ['key' => 'dribbles', 'label' => 'Dribel Berhasil', 'value' => $playerDashboardTotals->dribbles, 'icon' => 'move-up-right', 'tone' => 'text-cyan-600 bg-cyan-50'],
        ];
        $maxContribution = max(1, (int) $playerMonthlyTrend->max(fn ($month) => $month['goals'] + $month['assists']));
    @endphp

    <div class="space-y-6">
        <section class="relative isolate overflow-hidden rounded-[28px] bg-[#183262] p-6 text-white shadow-xl shadow-slate-900/10 sm:p-8">
            <div class="pointer-events-none absolute -right-14 -top-24 h-72 w-72 rounded-full border-[34px] border-white/[.04]"></div>
            <div class="pointer-events-none absolute -bottom-36 right-1/3 h-72 w-72 rounded-full bg-blue-300/[.07] blur-2xl"></div>
            <div class="relative flex flex-wrap items-start justify-between gap-6">
                <div class="flex min-w-0 items-center gap-4 sm:gap-5">
                    <div class="relative flex h-[76px] w-[76px] shrink-0 items-center justify-center rounded-[24px] border border-white/20 bg-white/10 text-2xl font-black uppercase tracking-wide text-white shadow-inner sm:h-[88px] sm:w-[88px] sm:text-3xl">
                        {{ $playerInitials ?: 'P' }}
                        <span class="absolute -bottom-2 -right-2 rounded-xl border-2 border-[#1c3e7d] bg-white px-2 py-1 text-[10px] font-extrabold text-[#1e3a8a] shadow">#{{ $playerProfile->jersey_number ?? '-' }}</span>
                    </div>
                    <div class="min-w-0">
                        <p class="mb-2 inline-flex items-center gap-1.5 rounded-full border border-white/15 bg-white/10 px-2.5 py-1 text-[9px] font-extrabold uppercase tracking-[.18em] text-blue-100">
                            <i data-lucide="activity" class="h-3.5 w-3.5"></i>
                            Dashboard Pemain
                        </p>
                        <h1 class="truncate text-2xl font-black tracking-tight sm:text-3xl">{{ $playerDisplayName }}</h1>
                        @if($playerProfile->popular_name && $playerProfile->popular_name !== $playerProfile->name)
                            <p class="mt-1 text-[12px] font-medium text-blue-100/80">{{ $playerProfile->name }}</p>
                        @endif
                        <div class="mt-3 flex flex-wrap items-center gap-x-4 gap-y-2 text-[11px] font-semibold text-blue-100">
                            <span class="inline-flex items-center gap-1.5"><i data-lucide="shield" class="h-3.5 w-3.5 text-blue-200"></i>{{ $playerProfile->club->name ?? 'Tanpa Tim' }}</span>
                            <span class="inline-flex items-center gap-1.5"><i data-lucide="map-pin" class="h-3.5 w-3.5 text-blue-200"></i>{{ $playerProfile->position ?: 'Posisi belum diatur' }}</span>
                            @if($playerProfile->age_group)
                                <span class="inline-flex items-center gap-1.5"><i data-lucide="users-round" class="h-3.5 w-3.5 text-blue-200"></i>{{ $playerProfile->age_group }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <a href="{{ route('reports.player.show', $playerProfile) }}" class="inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2.5 text-[11px] font-extrabold text-[#1e3a8a] shadow-sm transition hover:bg-blue-50">
                    <i data-lucide="chart-no-axes-combined" class="h-4 w-4"></i>
                    Laporan Performa
                </a>
            </div>
        </section>

        <section class="grid grid-cols-2 gap-4 md:grid-cols-3 xl:grid-cols-6">
            @foreach($playerMetrics as $metric)
                <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-[0_2px_10px_rgb(0,0,0,0.02)]">
                    <div class="flex items-start justify-between gap-2">
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl {{ $metric['tone'] }}">
                            <i data-lucide="{{ $metric['icon'] }}" class="h-4 w-4"></i>
                        </span>
                        <span data-player-metric="{{ $metric['key'] }}" class="text-2xl font-black text-[#1e293b]">{{ number_format($metric['value']) }}</span>
                    </div>
                    <p class="mt-3 text-[9px] font-bold uppercase tracking-widest text-slate-400">{{ $metric['label'] }}</p>
                </article>
            @endforeach
        </section>

        <section class="grid grid-cols-1 items-start gap-6 xl:grid-cols-5">
            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6 xl:col-span-3">
                <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <h2 class="flex items-center gap-2 text-sm font-extrabold text-[#1e293b]">
                            <i data-lucide="chart-spline" class="h-4 w-4 text-[#4062D6]"></i>
                            Tren Kontribusi
                        </h2>
                        <p class="mt-1 text-[11px] text-slate-400">Gol dan assist per bulan · 6 bulan terakhir</p>
                    </div>
                    <span class="rounded-lg bg-slate-100 px-2.5 py-1.5 text-[9px] font-bold uppercase tracking-wider text-slate-600">6 bulan</span>
                </div>
                <div class="flex h-52 items-end gap-3 border-b border-slate-100 pb-2">
                    @foreach($playerMonthlyTrend as $month)
                        <div class="flex h-full flex-1 flex-col items-center justify-end gap-2" title="{{ $month['goals'] }} gol · {{ $month['assists'] }} assist">
                            <div class="flex h-full w-full items-end justify-center gap-1">
                                <div class="w-3 max-w-[38%] rounded-t bg-[#4062D6] transition-all" style="height: {{ $month['goals'] ? max(8, (int) round($month['goals'] / $maxContribution * 82)) : 3 }}%"></div>
                                <div class="w-3 max-w-[38%] rounded-t bg-violet-400 transition-all" style="height: {{ $month['assists'] ? max(8, (int) round($month['assists'] / $maxContribution * 82)) : 3 }}%"></div>
                            </div>
                            <span class="text-[10px] font-semibold text-slate-500">{{ $month['label'] }}</span>
                        </div>
                    @endforeach
                </div>
                <div class="mt-3 flex flex-wrap gap-4 text-[10px] font-semibold text-slate-500">
                    <span class="inline-flex items-center gap-1.5"><span class="h-2 w-2 rounded-full bg-[#4062D6]"></span>Gol</span>
                    <span class="inline-flex items-center gap-1.5"><span class="h-2 w-2 rounded-full bg-violet-400"></span>Assist</span>
                </div>
            </article>

            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6 xl:col-span-2">
                <div class="mb-4 flex items-center gap-3">
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-50 text-blue-600"><i data-lucide="calendar-clock" class="h-4 w-4"></i></span>
                    <div>
                        <h2 class="text-[13px] font-extrabold text-slate-800">Jadwal Berikutnya</h2>
                        <p class="mt-0.5 text-[10px] text-slate-400">Pertandingan mendatang yang diikuti</p>
                    </div>
                </div>
                @if($playerNextMatch)
                    <div class="rounded-xl border border-blue-100 bg-gradient-to-br from-blue-50/70 to-white p-4">
                        <p class="text-[10px] font-bold text-slate-500">{{ $playerNextMatch->title }}</p>
                        <p class="mt-1 text-sm font-extrabold text-[#1e293b]">vs {{ $playerNextMatch->opponent ?: 'Lawan belum ditentukan' }}</p>
                        <div class="mt-3 space-y-2 text-[10px] font-medium text-slate-500">
                            <p class="flex items-center gap-2"><i data-lucide="calendar" class="h-3.5 w-3.5 text-[#4062D6]"></i>{{ $playerNextMatch->match_date->translatedFormat('d F Y, H:i') }} WIB</p>
                            <p class="flex items-center gap-2"><i data-lucide="map-pin" class="h-3.5 w-3.5 text-[#4062D6]"></i>{{ $playerNextMatch->venue ?: 'Lokasi belum ditentukan' }}</p>
                        </div>
                        <a href="{{ route('matches.index') }}" class="mt-4 inline-flex items-center gap-1.5 text-[10px] font-bold text-[#4062D6] hover:text-[#3251b5]">Lihat semua jadwal <i data-lucide="arrow-up-right" class="h-3 w-3"></i></a>
                    </div>
                @else
                    <div class="rounded-xl border border-dashed border-slate-200 bg-slate-50/70 px-4 py-8 text-center">
                        <i data-lucide="calendar-x" class="mx-auto h-6 w-6 text-slate-300"></i>
                        <p class="mt-2 text-[11px] text-slate-500">Belum ada jadwal mendatang untuk Anda.</p>
                    </div>
                @endif
            </article>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h2 class="flex items-center gap-2 text-sm font-extrabold text-[#1e293b]">
                        <i data-lucide="history" class="h-4 w-4 text-[#4062D6]"></i>
                        Riwayat Pertandingan
                    </h2>
                    <p class="mt-1 text-[11px] text-slate-400">Rekap pertandingan selesai yang diikuti</p>
                </div>
                <a href="{{ route('reports.player.show', $playerProfile) }}" class="inline-flex items-center gap-1 text-[10px] font-bold text-[#4062D6] hover:text-[#3251b5]">Laporan lengkap <i data-lucide="arrow-right" class="h-3 w-3"></i></a>
            </div>
            <div class="space-y-3">
                @forelse($playerRecentMatches as $match)
                    @php $matchStats = $playerMatchStats->get($match->id); @endphp
                    <article class="flex flex-col gap-3 rounded-xl border border-slate-100 p-4 transition-colors hover:border-blue-100 hover:bg-blue-50/20 sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex min-w-0 items-center gap-3">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600"><i data-lucide="circle-check" class="h-5 w-5"></i></span>
                            <div class="min-w-0">
                                <p class="truncate text-[12px] font-bold text-[#1e293b]">{{ $match->title }} <span class="font-medium text-slate-400">vs {{ $match->opponent ?: 'Lawan belum ditentukan' }}</span></p>
                                <p class="mt-1 text-[10px] text-slate-500">{{ $match->match_date->translatedFormat('d F Y') }} · {{ $match->venue ?: 'Lokasi tidak tersedia' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between gap-4 sm:justify-end">
                            <div class="flex gap-4 text-center">
                                <div><p class="text-[12px] font-black text-[#1e293b]">{{ $matchStats->goals ?? 0 }}</p><p class="text-[9px] font-bold text-slate-400">Gol</p></div>
                                <div><p class="text-[12px] font-black text-[#1e293b]">{{ $matchStats->assists ?? 0 }}</p><p class="text-[9px] font-bold text-slate-400">Assist</p></div>
                            </div>
                            <a href="{{ route('reports.player.match.show', [$playerProfile, $match]) }}" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 px-3 py-2 text-[10px] font-bold text-slate-600 hover:bg-slate-50"><i data-lucide="eye" class="h-3.5 w-3.5"></i>Evaluasi</a>
                        </div>
                    </article>
                @empty
                    <div class="rounded-xl border border-dashed border-slate-200 bg-slate-50/70 px-4 py-10 text-center">
                        <i data-lucide="calendar-x" class="mx-auto h-7 w-7 text-slate-300"></i>
                        <p class="mt-2 text-[11px] font-semibold text-slate-500">Belum ada pertandingan yang tercatat.</p>
                    </div>
                @endforelse
            </div>
        </section>
    </div>
@else
    <section class="rounded-2xl border border-amber-200 bg-amber-50 p-6 text-amber-900">
        <div class="flex items-start gap-3">
            <i data-lucide="{{ $playerProfileAmbiguous ? 'triangle-alert' : 'user-round-x' }}" class="mt-0.5 h-5 w-5 shrink-0"></i>
            <div>
                <h2 class="text-sm font-bold">{{ $playerProfileAmbiguous ? 'Profil pemain perlu diperiksa' : 'Profil pemain belum ditemukan' }}</h2>
                <p class="mt-1 text-xs leading-relaxed">
                    @if($playerProfileAmbiguous)
                        Ada lebih dari satu profil pemain dengan nama akun Anda. Hubungi administrator agar data akun dan profil dapat dicocokkan.
                    @else
                        Pastikan nama akun Anda sama dengan nama pada data pemain, lalu hubungi administrator jika profil tetap belum muncul.
                    @endif
                </p>
            </div>
        </div>
    </section>
@endif

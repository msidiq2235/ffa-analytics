@extends('layouts.app')
@section('title', 'Laporan Performa Pemain - FFA Analytics')
@section('header', 'Laporan Performa Pemain')
@section('subheader', 'Pantau perkembangan dan kontribusi pemain')

@section('content')
@php
    $initials = collect(preg_split('/\s+/', trim($player->popular_name ?: $player->name)))
        ->filter()
        ->take(2)
        ->map(fn ($part) => mb_substr($part, 0, 1))
        ->implode('');
    $radarForDisplay = collect($radar)->only(['Serangan', 'Bertahan', 'Operan']);
    $groupIcons = [
        'Menyerang' => 'goal',
        'Umpan' => 'route',
        'Penguasaan Bola' => 'dribbble',
        'Bertahan' => 'shield',
        'Goalkeeping' => 'hand',
        'Aerial & Defensive' => 'shield-check',
        'Distribusi Kiper' => 'send',
    ];
@endphp

<div class="space-y-6">
    <section class="relative isolate overflow-hidden rounded-[28px] bg-[#183262] p-6 text-white shadow-xl shadow-slate-900/10 sm:p-8">
        <div class="pointer-events-none absolute -right-14 -top-24 h-72 w-72 rounded-full border-[34px] border-white/[.04]"></div>
        <div class="pointer-events-none absolute -bottom-36 right-1/3 h-72 w-72 rounded-full bg-blue-300/[.07] blur-2xl"></div>
        <div class="relative flex flex-col gap-7">
            <div class="flex flex-wrap items-start justify-between gap-5">
                <div class="flex min-w-0 items-center gap-4 sm:gap-5">
                    <div class="relative flex h-[76px] w-[76px] shrink-0 items-center justify-center rounded-[24px] border border-white/20 bg-white/10 text-2xl font-black uppercase tracking-wide text-white shadow-inner sm:h-[88px] sm:w-[88px] sm:text-3xl">
                        {{ $initials ?: 'P' }}
                        <span class="absolute -bottom-2 -right-2 rounded-xl border-2 border-[#1c3e7d] bg-white px-2 py-1 text-[10px] font-extrabold text-[#1e3a8a] shadow">#{{ $player->jersey_number ?? $player->number ?? '-' }}</span>
                    </div>
                    <div class="min-w-0">
                        <p class="mb-2 inline-flex items-center gap-1.5 rounded-full border border-white/15 bg-white/10 px-2.5 py-1 text-[9px] font-extrabold uppercase tracking-[.18em] text-blue-100">
                            <i data-lucide="activity" class="h-3.5 w-3.5"></i>
                            Profil Performa
                        </p>
                        <h1 class="truncate text-2xl font-black tracking-tight sm:text-3xl">{{ $player->popular_name ?: $player->name }}</h1>
                        @if($player->popular_name && $player->popular_name !== $player->name)
                            <p class="mt-1 text-[12px] font-medium text-blue-100/80">{{ $player->name }}</p>
                        @endif
                        <div class="mt-3 flex flex-wrap items-center gap-x-4 gap-y-2 text-[11px] font-semibold text-blue-100">
                            <span class="inline-flex items-center gap-1.5"><i data-lucide="shield" class="h-3.5 w-3.5 text-blue-200"></i>{{ $player->club->name ?? 'Tanpa Tim' }}</span>
                            <span class="inline-flex items-center gap-1.5"><i data-lucide="map-pin" class="h-3.5 w-3.5 text-blue-200"></i>{{ $player->position ?? 'Posisi belum diatur' }}</span>
                            @if($player->age_group)
                                <span class="inline-flex items-center gap-1.5"><i data-lucide="users-round" class="h-3.5 w-3.5 text-blue-200"></i>{{ $player->age_group }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('performa.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-white/20 bg-white/10 px-4 py-2.5 text-[11px] font-bold text-white transition hover:bg-white/20">
                        <i data-lucide="arrow-left" class="h-4 w-4"></i>
                        Daftar Pemain
                    </a>
                    <a href="#riwayat-pertandingan" class="inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2.5 text-[11px] font-extrabold text-[#1e3a8a] shadow-sm transition hover:bg-blue-50">
                        <i data-lucide="calendar-days" class="h-4 w-4"></i>
                        Riwayat Match
                    </a>
                </div>
            </div>
            <div class="flex flex-wrap items-center justify-between gap-3 border-t border-white/15 pt-4">
                <p class="text-[11px] font-medium text-blue-100/80">Rekap statistik dihitung dari pertandingan yang telah dijalani pemain.</p>
                <span class="inline-flex items-center gap-1.5 rounded-full border border-white/15 bg-white/10 px-3 py-1.5 text-[10px] font-bold text-blue-100">
                    <span class="h-1.5 w-1.5 rounded-full bg-blue-200"></span>
                    {{ $totals->matches }} pertandingan tercatat
                </span>
            </div>
        </div>
    </section>

    <section class="overflow-hidden rounded-[24px] border border-slate-200/80 bg-white shadow-sm">
        <div class="flex flex-wrap items-center justify-between gap-4 border-b border-slate-100 px-5 py-5 sm:px-6">
            <div>
                <div class="flex items-center gap-2">
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                        <i data-lucide="chart-no-axes-combined" class="h-4 w-4"></i>
                    </span>
                    <h2 class="text-[15px] font-extrabold tracking-tight text-slate-800">Rekapan Statistik Performa</h2>
                </div>
                <p class="ml-11 mt-1 text-[11px] text-slate-400">Akumulasi statistik dari seluruh pertandingan pemain.</p>
            </div>
            <span class="rounded-full bg-slate-100 px-3 py-1.5 text-[10px] font-bold text-slate-500">{{ count($statisticGroups) }} kategori statistik</span>
        </div>

        <div class="grid grid-cols-1 gap-4 p-5 sm:grid-cols-2 xl:grid-cols-3 sm:p-6">
            @php $hasCareerStats = false; @endphp
            @foreach($statisticGroups as $groupName => $group)
                @php
                    $groupTotals = collect($group)
                        ->map(fn ($label, $field) => ['field' => $field, 'label' => $label, 'value' => (int) ($statisticTotals[$field] ?? 0)])
                        ->filter(fn ($metric) => $metric['value'] > 0);
                    $hasCareerStats = $hasCareerStats || $groupTotals->isNotEmpty();
                    $groupMax = max(1, (int) $groupTotals->max('value'));
                    $groupIcon = $groupIcons[$groupName] ?? 'chart-column';
                @endphp
                @if($groupTotals->isNotEmpty())
                    <article class="group rounded-2xl border border-slate-200/80 bg-white p-4 transition duration-200 hover:-translate-y-0.5 hover:border-slate-300 hover:shadow-lg hover:shadow-slate-900/[.04]">
                        <div class="mb-4 flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                                    <i data-lucide="{{ $groupIcon }}" class="h-4 w-4"></i>
                                </span>
                                <div>
                                    <h3 class="text-[11px] font-extrabold uppercase tracking-[.1em] text-slate-700">{{ $groupName }}</h3>
                                    <p class="mt-0.5 text-[9px] text-slate-400">{{ $groupTotals->count() }} metrik tercatat</p>
                                </div>
                            </div>
                            <i data-lucide="arrow-up-right" class="h-4 w-4 text-slate-300 transition group-hover:text-slate-500"></i>
                        </div>
                        <div class="space-y-3">
                            @foreach($groupTotals as $metric)
                                @php $barWidth = min(100, (int) round(($metric['value'] / $groupMax) * 100)); @endphp
                                <div data-stat-total="{{ $metric['field'] }}" data-stat-value="{{ $metric['value'] }}" class="rounded-xl bg-slate-50/80 px-3.5 py-3">
                                    <div class="flex items-start justify-between gap-3">
                                        <p class="text-[10px] font-medium leading-4 text-slate-500">{{ $metric['label'] }}</p>
                                        <p class="shrink-0 text-xl font-black leading-none tracking-tight text-slate-800">{{ number_format($metric['value']) }}</p>
                                    </div>
                                    <div class="mt-2.5 h-1 overflow-hidden rounded-full bg-slate-200/80">
                                        <div class="h-full rounded-full bg-blue-500 transition-all duration-500" style="width: {{ $barWidth }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </article>
                @endif
            @endforeach

            @if(!$hasCareerStats)
                <div class="col-span-full rounded-2xl border border-dashed border-slate-200 bg-slate-50/70 px-4 py-12 text-center">
                    <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-slate-300 shadow-sm">
                        <i data-lucide="chart-no-axes-column-increasing" class="h-6 w-6"></i>
                    </span>
                    <p class="mt-3 text-[12px] font-bold text-slate-600">Belum ada statistik pemain</p>
                    <p class="mt-1 text-[11px] text-slate-400">Statistik akan muncul setelah dicatat pada pertandingan.</p>
                </div>
            @endif
        </div>
    </section>

    <section class="grid grid-cols-1 gap-5 xl:grid-cols-5">
        <article class="rounded-[24px] border border-slate-200/80 bg-white p-5 shadow-sm sm:p-6 xl:col-span-3">
            <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                        <i data-lucide="chart-spline" class="h-4 w-4"></i>
                    </span>
                    <div>
                        <h2 class="text-[13px] font-extrabold text-slate-800">Tren Kontribusi Pemain</h2>
                        <p class="mt-0.5 text-[10px] text-slate-400">Gol dan assist per bulan selama 6 bulan terakhir</p>
                    </div>
                </div>
                <span class="rounded-lg bg-slate-100 px-2.5 py-1.5 text-[9px] font-bold uppercase tracking-wider text-slate-600">6 bulan</span>
            </div>
            @if($totals->matches > 0)
                <div class="relative h-[260px] sm:h-[290px]"><canvas id="lineChart"></canvas></div>
            @else
                <div class="flex h-[260px] flex-col items-center justify-center rounded-2xl bg-slate-50/70 text-center">
                    <i data-lucide="chart-line" class="h-8 w-8 text-slate-300"></i>
                    <p class="mt-2 text-[12px] font-semibold text-slate-500">Belum ada tren performa</p>
                    <p class="mt-1 text-[10px] text-slate-400">Data akan tampil setelah pemain memiliki statistik.</p>
                </div>
            @endif
        </article>

        <article class="rounded-[24px] border border-slate-200/80 bg-white p-5 shadow-sm sm:p-6 xl:col-span-2">
            <div class="mb-5 flex items-center gap-3">
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                    <i data-lucide="radar" class="h-4 w-4"></i>
                </span>
                <div>
                    <h2 class="text-[13px] font-extrabold text-slate-800">Profil Statistik</h2>
                    <p class="mt-0.5 text-[10px] text-slate-400">Indeks serangan, bertahan, dan operan</p>
                </div>
            </div>
            @if($totals->matches > 0)
                <div class="relative h-[260px] sm:h-[290px]"><canvas id="radarChart"></canvas></div>
            @else
                <div class="flex h-[260px] flex-col items-center justify-center rounded-2xl bg-slate-50/70 text-center">
                    <i data-lucide="radar" class="h-8 w-8 text-slate-300"></i>
                    <p class="mt-2 text-[12px] font-semibold text-slate-500">Belum ada profil statistik</p>
                    <p class="mt-1 text-[10px] text-slate-400">Grafik tersedia setelah ada data pertandingan.</p>
                </div>
            @endif
        </article>
    </section>

    <section id="riwayat-pertandingan" class="scroll-mt-6 overflow-hidden rounded-[24px] border border-slate-200/80 bg-white shadow-sm">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 px-5 py-5 sm:px-6">
            <div class="flex items-center gap-3">
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                    <i data-lucide="history" class="h-4 w-4"></i>
                </span>
                <div>
                    <h2 class="text-[13px] font-extrabold text-slate-800">Riwayat Pertandingan</h2>
                    <p class="mt-0.5 text-[10px] text-slate-400">Buka detail statistik dan vektor per match.</p>
                </div>
            </div>
            <span class="rounded-full bg-slate-100 px-3 py-1.5 text-[10px] font-bold text-slate-500">{{ $matches->count() }} pertandingan</span>
        </div>
        <div class="space-y-3 p-4 sm:p-5">
            @forelse($matches as $match)
                <article class="group flex flex-col gap-4 rounded-2xl border border-slate-100 bg-slate-50/60 p-4 transition hover:border-blue-100 hover:bg-blue-50/30 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex min-w-0 items-center gap-3.5">
                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-white bg-white text-slate-500 shadow-sm">
                            <i data-lucide="calendar-check-2" class="h-5 w-5"></i>
                        </span>
                        <div class="min-w-0">
                            <h3 class="truncate text-[12px] font-extrabold text-slate-800">{{ $match->title ?? 'Pertandingan' }} <span class="font-semibold text-slate-400">vs</span> {{ $opponentOf($match) }}</h3>
                            <div class="mt-1.5 flex flex-wrap items-center gap-x-3 gap-y-1 text-[10px] font-medium text-slate-400">
                                <span class="inline-flex items-center gap-1.5"><i data-lucide="calendar-days" class="h-3.5 w-3.5"></i>{{ \Carbon\Carbon::parse($match->match_date)->translatedFormat('d F Y') }}</span>
                                <span class="inline-flex items-center gap-1.5"><i data-lucide="clock-3" class="h-3.5 w-3.5"></i>{{ \Carbon\Carbon::parse($match->match_date)->format('H:i') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2 sm:shrink-0">
                        <a href="{{ route('reports.player.match.show', [$player->id, $match->id]) }}" class="inline-flex items-center justify-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-[10px] font-bold text-slate-600 transition hover:border-blue-200 hover:text-blue-700">
                            <i data-lucide="eye" class="h-3.5 w-3.5"></i>
                            Lihat Match
                        </a>
                        <a href="{{ route('reports.player.match.pdf', [$player->id, $match->id]) }}" target="_blank" rel="noopener" class="inline-flex items-center justify-center gap-1.5 rounded-xl bg-blue-600 px-3.5 py-2 text-[10px] font-bold text-white shadow-sm transition hover:bg-blue-700">
                            <i data-lucide="file-down" class="h-3.5 w-3.5"></i>
                            Unduh PDF
                        </a>
                    </div>
                </article>
            @empty
                <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50/70 px-4 py-12 text-center">
                    <i data-lucide="calendar-x" class="mx-auto h-8 w-8 text-slate-300"></i>
                    <p class="mt-2 text-[12px] font-semibold text-slate-500">Belum ada riwayat pertandingan</p>
                    <p class="mt-1 text-[10px] text-slate-400">Pertandingan pemain akan tercatat di sini.</p>
                </div>
            @endforelse
        </div>
    </section>

    <section class="overflow-hidden rounded-[24px] border border-slate-200/80 bg-white shadow-sm">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 px-5 py-5 sm:px-6">
            <div class="flex items-center gap-3">
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                    <i data-lucide="calendar-range" class="h-4 w-4"></i>
                </span>
                <div>
                    <h2 class="text-[13px] font-extrabold text-slate-800">Laporan Per Musim</h2>
                    <p class="mt-0.5 text-[10px] text-slate-400">Unduh rekap statistik seluruh pertandingan tiap musim.</p>
                </div>
            </div>
            <span class="rounded-full bg-slate-100 px-3 py-1.5 text-[10px] font-bold text-slate-500">{{ $seasons->count() }} musim</span>
        </div>
        <div class="grid grid-cols-1 gap-3 p-4 sm:grid-cols-2 sm:p-5 xl:grid-cols-3">
            @forelse($seasons as $season)
                <article class="flex items-center justify-between gap-3 rounded-2xl border border-slate-100 bg-slate-50/60 p-4 transition hover:border-blue-100 hover:bg-blue-50/30">
                    <div class="flex items-center gap-3">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-white text-blue-600 shadow-sm">
                            <i data-lucide="calendar-check-2" class="h-4 w-4"></i>
                        </span>
                        <div>
                            <h3 class="text-[12px] font-extrabold text-slate-800">Musim {{ $season->label }}</h3>
                            <p class="mt-1 text-[10px] font-medium text-slate-400">{{ $season->matches }} pertandingan</p>
                        </div>
                    </div>
                    <a href="{{ route('reports.player.season.pdf', [$player->id, $season->start]) }}" target="_blank" rel="noopener" aria-label="Unduh PDF musim {{ $season->label }}" class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-blue-600 text-white shadow-sm transition hover:bg-blue-700">
                        <i data-lucide="download" class="h-4 w-4"></i>
                    </a>
                </article>
            @empty
                <div class="col-span-full rounded-2xl border border-dashed border-slate-200 bg-slate-50/70 px-4 py-10 text-center">
                    <i data-lucide="calendar-x" class="mx-auto h-7 w-7 text-slate-300"></i>
                    <p class="mt-2 text-[11px] font-semibold text-slate-500">Belum ada riwayat musim</p>
                </div>
            @endforelse
        </div>
    </section>

    <div class="flex justify-start pb-2">
        <a href="{{ route('performa.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-[11px] font-bold text-slate-600 shadow-sm transition hover:border-blue-200 hover:text-blue-700">
            <i data-lucide="arrow-left" class="h-4 w-4"></i>
            Kembali ke daftar pemain
        </a>
    </div>
</div>

@if($totals->matches > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    new Chart(document.getElementById('lineChart'), {
        type: 'line',
        data: {
            labels: @json($chartLabels),
            datasets: [{
                label: 'Gol + assist',
                data: @json($chartValues),
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37,99,235,0.10)',
                pointBackgroundColor: '#ffffff',
                pointBorderColor: '#2563eb',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
                borderWidth: 3,
                tension: 0.38,
                fill: true
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: { displayColors: false, padding: 10 }
            },
            scales: {
                x: { grid: { display: false }, border: { display: false }, ticks: { color: '#94a3b8', font: { size: 10 } } },
                y: { beginAtZero: true, border: { display: false }, grid: { color: 'rgba(148,163,184,.14)' }, ticks: { precision: 0, color: '#94a3b8', font: { size: 10 } } }
            }
        }
    });

    new Chart(document.getElementById('radarChart'), {
        type: 'radar',
        data: {
            labels: @json($radarForDisplay->keys()->values()),
            datasets: [{
                data: @json($radarForDisplay->values()->values()),
                backgroundColor: 'rgba(37,99,235,0.14)',
                borderColor: '#2563eb',
                borderWidth: 2,
                pointBackgroundColor: '#ffffff',
                pointBorderColor: '#2563eb',
                pointBorderWidth: 2,
                pointRadius: 3
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                r: {
                    min: 0,
                    max: 100,
                    ticks: { display: false, stepSize: 25 },
                    grid: { color: 'rgba(148,163,184,.18)' },
                    angleLines: { color: 'rgba(148,163,184,.18)' },
                    pointLabels: { color: '#64748b', font: { size: 10, weight: '600' } }
                }
            }
        }
    });
</script>
@endif
@endsection

@extends('layouts.app')
@section('title', 'Detail Laporan Pertandingan - FFA Analytics')
@section('header', 'Detail Laporan Pertandingan')
@section('subheader', 'Statistik tim dan aksi pemain pada pertandingan')

@section('content')
@php
    $matchDate = \Carbon\Carbon::parse($match->match_date);
@endphp

<div class="space-y-6">
    <section class="relative isolate overflow-hidden rounded-[26px] bg-[#183262] p-6 text-white shadow-xl shadow-slate-900/10 sm:p-8">
        <div class="pointer-events-none absolute -right-12 -top-20 h-64 w-64 rounded-full border-[32px] border-white/[.04]"></div>
        <div class="relative flex flex-wrap items-start justify-between gap-5">
            <div class="min-w-0">
                <span class="mb-3 inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-3 py-1.5 text-[9px] font-extrabold uppercase tracking-[.16em] text-blue-100">
                    <i data-lucide="clipboard-list" class="h-3.5 w-3.5"></i>
                    Laporan pertandingan
                </span>
                <h1 class="text-2xl font-black tracking-tight sm:text-3xl">{{ $match->title ?? 'Pertandingan' }}</h1>
                <p class="mt-1 text-[14px] font-bold text-blue-100">vs {{ $match->opponent ?? '-' }}</p>
                <div class="mt-4 flex flex-wrap gap-x-4 gap-y-2 text-[11px] font-medium text-blue-100/85">
                    <span class="inline-flex items-center gap-1.5"><i data-lucide="calendar-clock" class="h-4 w-4"></i>{{ $matchDate->translatedFormat('d F Y, H:i') }}</span>
                    @if($match->venue)
                        <span class="inline-flex items-center gap-1.5"><i data-lucide="map-pin" class="h-4 w-4"></i>{{ $match->venue }}</span>
                    @endif
                    @if($match->formation)
                        <span class="inline-flex items-center gap-1.5"><i data-lucide="layout-grid" class="h-4 w-4"></i>Formasi {{ $match->formation }}</span>
                    @endif
                </div>
            </div>
            <div class="relative flex flex-wrap gap-2">
                <a href="{{ route('reports.index', ['tab' => 'match']) }}" class="inline-flex items-center gap-2 rounded-xl border border-white/20 bg-white/10 px-4 py-2.5 text-[11px] font-bold text-white transition hover:bg-white/20">
                    <i data-lucide="arrow-left" class="h-4 w-4"></i>
                    Kembali
                </a>
                <a href="{{ route('reports.match.pdf', $match->id) }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2.5 text-[11px] font-extrabold text-[#183262] shadow-sm transition hover:bg-blue-50">
                    <i data-lucide="file-down" class="h-4 w-4"></i>
                    Unduh PDF
                </a>
            </div>
        </div>
    </section>

    <section class="grid grid-cols-2 gap-3 xl:grid-cols-4">
        @foreach([
            ['label' => 'Total gol', 'value' => $teamStats['goals'], 'icon' => 'goal'],
            ['label' => 'Total tembakan', 'value' => $teamStats['shots'], 'icon' => 'crosshair'],
            ['label' => 'Umpan berhasil', 'value' => $teamStats['passes'], 'icon' => 'route'],
            ['label' => 'Tackle', 'value' => $teamStats['tackles'], 'icon' => 'shield'],
        ] as $metric)
            <article class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-5">
                <div class="flex items-center justify-between gap-3">
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                        <i data-lucide="{{ $metric['icon'] }}" class="h-4 w-4"></i>
                    </span>
                    <span class="text-2xl font-black tracking-tight text-slate-800">{{ number_format($metric['value']) }}</span>
                </div>
                <p class="mt-3 text-[10px] font-bold uppercase tracking-[.08em] text-slate-400">{{ $metric['label'] }}</p>
            </article>
        @endforeach
    </section>

    <section class="overflow-hidden rounded-[24px] border border-slate-200/80 bg-white shadow-sm">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 px-5 py-5 sm:px-6">
            <div>
                <h2 class="flex items-center gap-2 text-[14px] font-extrabold text-slate-800">
                    <i data-lucide="users-round" class="h-4 w-4 text-blue-600"></i>
                    Statistik Pemain
                </h2>
                <p class="mt-1 text-[10px] text-slate-400">Rekap gol, assist, tembakan, umpan berhasil, dan tackle.</p>
            </div>
            <span class="rounded-full bg-slate-100 px-3 py-1.5 text-[10px] font-bold text-slate-500">{{ $match->players->count() }} pemain</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[760px] text-left">
                <thead class="bg-slate-50/80">
                    <tr>
                        <th class="px-5 py-3 text-[9px] font-extrabold uppercase tracking-wider text-slate-400">Pemain</th>
                        <th class="px-3 py-3 text-center text-[9px] font-extrabold uppercase tracking-wider text-slate-400">No.</th>
                        <th class="px-3 py-3 text-center text-[9px] font-extrabold uppercase tracking-wider text-slate-400">Posisi</th>
                        <th class="px-3 py-3 text-center text-[9px] font-extrabold uppercase tracking-wider text-slate-400">Gol</th>
                        <th class="px-3 py-3 text-center text-[9px] font-extrabold uppercase tracking-wider text-slate-400">Assist</th>
                        <th class="px-3 py-3 text-center text-[9px] font-extrabold uppercase tracking-wider text-slate-400">Tembakan tepat</th>
                        <th class="px-3 py-3 text-center text-[9px] font-extrabold uppercase tracking-wider text-slate-400">Tembakan tidak tepat</th>
                        <th class="px-3 py-3 text-center text-[9px] font-extrabold uppercase tracking-wider text-slate-400">Umpan</th>
                        <th class="px-3 py-3 text-center text-[9px] font-extrabold uppercase tracking-wider text-slate-400">Tackle</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($match->players as $player)
                        @php $playerStats = $stats[$player->id] ?? null; @endphp
                        <tr class="transition hover:bg-slate-50/70">
                            <td class="px-5 py-3.5">
                                <span class="font-bold text-slate-700">{{ $player->name }}</span>
                            </td>
                            <td class="px-3 py-3.5 text-center text-[11px] font-semibold text-slate-500">{{ $player->jersey_number ?? '-' }}</td>
                            <td class="px-3 py-3.5 text-center">
                                <span class="rounded-md bg-slate-100 px-2 py-1 text-[9px] font-bold text-slate-600">{{ $player->pivot->position ?? '-' }}</span>
                            </td>
                            <td class="px-3 py-3.5 text-center text-[11px] font-extrabold text-slate-700">{{ $playerStats->total_goals ?? 0 }}</td>
                            <td class="px-3 py-3.5 text-center text-[11px] font-extrabold text-slate-700">{{ $playerStats->total_assists ?? 0 }}</td>
                            <td class="px-3 py-3.5 text-center text-[11px] font-semibold text-slate-600">{{ $playerStats->total_shots_target ?? 0 }}</td>
                            <td class="px-3 py-3.5 text-center text-[11px] font-semibold text-slate-600">{{ $playerStats->total_shots_off ?? 0 }}</td>
                            <td class="px-3 py-3.5 text-center text-[11px] font-semibold text-slate-600">{{ $playerStats->total_passes ?? 0 }}</td>
                            <td class="px-3 py-3.5 text-center text-[11px] font-semibold text-slate-600">{{ $playerStats->total_tackles ?? 0 }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-5 py-12 text-center">
                                <i data-lucide="users-round" class="mx-auto h-8 w-8 text-slate-300"></i>
                                <p class="mt-2 text-[11px] font-semibold text-slate-500">Belum ada pemain pada pertandingan ini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <section class="rounded-[24px] border border-slate-200/80 bg-white p-5 shadow-sm sm:p-6">
        <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="flex items-center gap-2 text-[14px] font-extrabold text-slate-800">
                    <i data-lucide="move-up-right" class="h-4 w-4 text-blue-600"></i>
                    Peta Vektor Pertandingan
                </h2>
                <p class="mt-1 text-[10px] text-slate-400">Arah tembakan, umpan, dan umpan silang yang direkam.</p>
            </div>
            <div class="flex flex-wrap gap-3 text-[9px] font-bold text-slate-500">
                @foreach(\App\Models\VectorDirection::SUBTYPE_COLORS as $action => $subtypes)
                    @foreach($subtypes as $subtype => $color)
                        <span class="inline-flex items-center gap-1.5">
                            <span class="h-2 w-2 rounded-full" style="background-color: {{ $color }}"></span>
                            {{ \App\Models\VectorDirection::ACTION_LABELS[$action] }} · {{ str_replace('_', ' ', $subtype) }}
                        </span>
                    @endforeach
                @endforeach
            </div>
        </div>

        @if($vectors->isNotEmpty())
            <div class="grid grid-cols-1 gap-5 xl:grid-cols-5">
                <div class="rounded-2xl bg-[#edf5ef] p-3 xl:col-span-3">
                    <svg viewBox="0 0 100 100" class="mx-auto max-h-[500px] w-full" role="img" aria-label="Peta vektor pertandingan">
                        <rect x="1" y="1" width="98" height="98" fill="none" stroke="#9aafa0" stroke-width=".6"></rect>
                        <line x1="1" y1="50" x2="99" y2="50" stroke="#9aafa0" stroke-width=".6"></line>
                        <circle cx="50" cy="50" r="12" fill="none" stroke="#9aafa0" stroke-width=".6"></circle>
                        <circle cx="50" cy="50" r=".8" fill="#7b9381"></circle>
                        <rect x="25" y="1" width="50" height="16" fill="none" stroke="#9aafa0" stroke-width=".6"></rect>
                        <rect x="25" y="83" width="50" height="16" fill="none" stroke="#9aafa0" stroke-width=".6"></rect>
                        @foreach($vectors as $vector)
                            @php
                                $color = $vector->display_color;
                                $startX = max(0, min(100, (float) $vector->start_x));
                                $startY = max(0, min(100, (float) $vector->start_y));
                                $endX = max(0, min(100, (float) $vector->end_x));
                                $endY = max(0, min(100, (float) $vector->end_y));
                                $angle = atan2($endY - $startY, $endX - $startX);
                                $baseX = $endX - 2.5 * cos($angle);
                                $baseY = $endY - 2.5 * sin($angle);
                                $leftX = $baseX + 1.1 * sin($angle);
                                $leftY = $baseY - 1.1 * cos($angle);
                                $rightX = $baseX - 1.1 * sin($angle);
                                $rightY = $baseY + 1.1 * cos($angle);
                            @endphp
                            <line x1="{{ $startX }}" y1="{{ $startY }}" x2="{{ $baseX }}" y2="{{ $baseY }}" stroke="{{ $color }}" stroke-width=".9" opacity=".8"></line>
                            <polygon points="{{ $endX }},{{ $endY }} {{ $leftX }},{{ $leftY }} {{ $rightX }},{{ $rightY }}" fill="{{ $color }}"></polygon>
                            <circle cx="{{ $startX }}" cy="{{ $startY }}" r=".8" fill="{{ $color }}"></circle>
                        @endforeach
                    </svg>
                </div>
                <div class="xl:col-span-2">
                    <div class="mb-2 flex items-center justify-between">
                        <h3 class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Rincian Aksi</h3>
                        <span class="rounded-full bg-slate-100 px-2.5 py-1 text-[9px] font-bold text-slate-500">{{ $vectors->count() }} aksi</span>
                    </div>
                    <div class="max-h-[500px] divide-y divide-slate-100 overflow-y-auto">
                        @foreach($vectors as $vector)
                            @php
                                $color = $vector->display_color;
                            @endphp
                            <div class="flex items-start gap-3 py-3">
                                <span class="mt-1 h-2.5 w-2.5 shrink-0 rounded-full" style="background-color: {{ $color }}"></span>
                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-wrap items-center justify-between gap-2">
                                        <p class="text-[11px] font-extrabold text-slate-700">{{ $vector->display_label }} <span class="font-medium text-slate-400">·</span> {{ $vector->player_name }}</p>
                                        <span class="text-[9px] font-semibold text-slate-400">{{ $vector->jersey_number ? '#' . $vector->jersey_number : '' }}</span>
                                    </div>
                                    <p class="mt-1 text-[9px] text-slate-500">Dari ({{ number_format($vector->start_x, 1) }}, {{ number_format($vector->start_y, 1) }}) ke ({{ number_format($vector->end_x, 1) }}, {{ number_format($vector->end_y, 1) }})</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @else
            <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50/70 px-4 py-12 text-center">
                <i data-lucide="waypoints" class="mx-auto h-8 w-8 text-slate-300"></i>
                <p class="mt-2 text-[11px] font-semibold text-slate-500">Belum ada data vektor untuk pertandingan ini.</p>
            </div>
        @endif
    </section>
</div>
@endsection

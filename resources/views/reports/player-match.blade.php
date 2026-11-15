@extends('layouts.app')
@section('title', 'Detail Performa Pertandingan - FFA Analytics')
@section('header', 'Detail Performa Pertandingan')
@section('subheader', 'Statistik dan arah aksi pemain pada pertandingan ini')
@section('content')

@php
    $vectorColors = [
        'SHOT' => '#ef4444',
        'PASS' => '#22c55e',
        'CROSS' => '#a855f7',
    ];
    $matchDate = \Carbon\Carbon::parse($match->match_date);
@endphp

<div class="space-y-6">
    <div class="relative overflow-hidden rounded-[24px] bg-gradient-to-br from-[#142c56] via-[#1e3a8a] to-[#2563eb] p-6 text-white shadow-lg">
        <div class="absolute -right-10 -top-16 h-56 w-56 rounded-full bg-white/5"></div>
        <div class="absolute -bottom-24 right-24 h-52 w-52 rounded-full bg-blue-300/10"></div>
        <div class="relative flex flex-wrap items-start justify-between gap-5">
            <div class="space-y-4">
                <div class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3 py-1 text-[10px] font-bold uppercase tracking-[.16em] text-blue-100">
                    <i data-lucide="activity" class="h-3.5 w-3.5"></i>
                    Match Performance
                </div>
                <div>
                    <h2 class="text-2xl font-extrabold tracking-tight sm:text-3xl">{{ $match->title }} <span class="text-blue-200">vs</span> {{ $opponent }}</h2>
                    <p class="mt-2 flex flex-wrap items-center gap-x-4 gap-y-2 text-[12px] font-medium text-blue-100">
                        <span class="inline-flex items-center gap-1.5"><i data-lucide="calendar-clock" class="h-4 w-4"></i>{{ $matchDate->translatedFormat('d F Y, H:i') }}</span>
                        @if($match->venue)
                            <span class="inline-flex items-center gap-1.5"><i data-lucide="map-pin" class="h-4 w-4"></i>{{ $match->venue }}</span>
                        @endif
                    </p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <span class="rounded-lg border border-white/15 bg-white/10 px-3 py-1.5 text-[11px] font-bold">{{ $player->club->name ?? 'Tanpa Tim' }}</span>
                    @if($position)
                        <span class="rounded-lg border border-white/15 bg-white/10 px-3 py-1.5 text-[11px] font-bold">Posisi: {{ $position }}</span>
                    @endif
                    @if($match->formation)
                        <span class="rounded-lg border border-white/15 bg-white/10 px-3 py-1.5 text-[11px] font-bold">Formasi {{ $match->formation }}</span>
                    @endif
                </div>
            </div>
            <div class="relative flex flex-wrap gap-2">
                <a href="{{ route('reports.player.show', $player->id) }}" class="inline-flex items-center gap-2 rounded-xl border border-white/20 bg-white/10 px-4 py-2.5 text-[12px] font-bold text-white transition hover:bg-white/20">
                    <i data-lucide="arrow-left" class="h-4 w-4"></i>
                    Profil Performa
                </a>
                <a href="{{ route('reports.match.show', $match->id) }}" class="inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2.5 text-[12px] font-bold text-[#1e3a8a] shadow-sm transition hover:bg-blue-50">
                    <i data-lucide="users-round" class="h-4 w-4"></i>
                    Laporan Tim
                </a>
            </div>
        </div>
    </div>

    <section class="rounded-[20px] border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
        <div class="mb-5 flex items-center justify-between gap-3">
            <div>
                <h3 class="flex items-center gap-2 text-[14px] font-extrabold text-[#1e293b]">
                    <i data-lucide="chart-no-axes-combined" class="h-4 w-4 text-[#4062D6]"></i>
                    Statistik Live Analytics
                </h3>
                <p class="mt-1 text-[11px] text-slate-400">Rekapan input statistik pemain untuk pertandingan ini.</p>
            </div>
            <span class="rounded-lg bg-blue-50 px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider text-blue-600">Per pertandingan</span>
        </div>

        @php $hasMatchStats = false; @endphp
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
            @foreach($statisticGroups as $groupName => $group)
                @php
                    $groupValues = collect($group)
                        ->map(fn ($label, $field) => ['field' => $field, 'label' => $label, 'value' => (int) ($matchStats->{$field} ?? 0)])
                        ->filter(fn ($metric) => $metric['value'] > 0);
                    $hasMatchStats = $hasMatchStats || $groupValues->isNotEmpty();
                @endphp
                @if($groupValues->isNotEmpty())
                    <div class="rounded-xl border border-slate-100 bg-slate-50/70 p-4">
                        <h4 class="mb-3 text-[10px] font-extrabold uppercase tracking-[.12em] text-slate-400">{{ $groupName }}</h4>
                        <div class="grid grid-cols-2 gap-2">
                            @foreach($groupValues as $metric)
                                <div class="rounded-lg border border-slate-100 bg-white px-3 py-2.5">
                                    <p class="text-[10px] leading-tight text-slate-500">{{ $metric['label'] }}</p>
                                    <p class="mt-1 text-xl font-extrabold text-[#1e293b]">{{ $metric['value'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
        @if(!$hasMatchStats)
            <div class="rounded-xl border border-dashed border-slate-200 bg-slate-50/50 px-4 py-10 text-center">
                <i data-lucide="chart-no-axes-column-increasing" class="mx-auto h-8 w-8 text-slate-300"></i>
                <p class="mt-2 text-[12px] font-medium text-slate-400">Belum ada statistik Live Analytics untuk pemain ini di pertandingan tersebut.</p>
            </div>
        @endif
    </section>

    <section class="rounded-[20px] border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
        <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h3 class="flex items-center gap-2 text-[14px] font-extrabold text-[#1e293b]">
                    <i data-lucide="move-up-right" class="h-4 w-4 text-[#4062D6]"></i>
                    Peta Vektor Pemain
                </h3>
                <p class="mt-1 text-[11px] text-slate-400">Arah tembakan, umpan, dan umpan silang yang dicatat pada pertandingan ini.</p>
            </div>
            <div class="flex flex-wrap gap-3 text-[10px] font-bold text-slate-500">
                @foreach(['SHOT' => 'Tembakan', 'PASS' => 'Umpan', 'CROSS' => 'Umpan silang'] as $action => $label)
                    <span class="inline-flex items-center gap-1.5">
                        <span class="h-2.5 w-2.5 rounded-full" style="background-color: {{ $vectorColors[$action] }}"></span>
                        {{ $label }}
                    </span>
                @endforeach
            </div>
        </div>

        @if($vectors->isNotEmpty())
            <div class="grid grid-cols-1 gap-5 xl:grid-cols-5">
                <div class="rounded-2xl bg-[#0e5a2c] p-4 xl:col-span-3">
                    <svg viewBox="0 0 100 100" class="mx-auto max-h-[520px] w-full" role="img" aria-label="Diagram arah aksi pemain">
                        <defs>
                            @foreach($vectorColors as $action => $color)
                                <marker id="arrow-{{ strtolower($action) }}" markerWidth="5" markerHeight="5" refX="4" refY="2.5" orient="auto" markerUnits="strokeWidth">
                                    <path d="M0,0 L5,2.5 L0,5 z" fill="{{ $color }}"></path>
                                </marker>
                            @endforeach
                        </defs>
                        <rect x="1" y="1" width="98" height="98" fill="none" stroke="rgba(255,255,255,.55)" stroke-width=".5"></rect>
                        <line x1="1" y1="50" x2="99" y2="50" stroke="rgba(255,255,255,.45)" stroke-width=".5"></line>
                        <circle cx="50" cy="50" r="12" fill="none" stroke="rgba(255,255,255,.4)" stroke-width=".5"></circle>
                        <circle cx="50" cy="50" r=".8" fill="rgba(255,255,255,.6)"></circle>
                        <rect x="25" y="1" width="50" height="16" fill="none" stroke="rgba(255,255,255,.4)" stroke-width=".5"></rect>
                        <rect x="25" y="83" width="50" height="16" fill="none" stroke="rgba(255,255,255,.4)" stroke-width=".5"></rect>
                        @foreach($vectors as $vector)
                            @php $vectorColor = $vectorColors[$vector->action_type] ?? '#ffffff'; @endphp
                            <line
                                data-vector-id="{{ $vector->id }}"
                                x1="{{ $vector->start_x }}"
                                y1="{{ $vector->start_y }}"
                                x2="{{ $vector->end_x }}"
                                y2="{{ $vector->end_y }}"
                                stroke="{{ $vectorColor }}"
                                stroke-width="1"
                                marker-end="url(#arrow-{{ strtolower($vector->action_type) }})"
                            ></line>
                        @endforeach
                    </svg>
                </div>
                <div class="xl:col-span-2">
                    <div class="mb-3 flex items-center justify-between">
                        <h4 class="text-[11px] font-extrabold uppercase tracking-[.12em] text-slate-400">Rincian Aksi</h4>
                        <span class="rounded-full bg-slate-100 px-2.5 py-1 text-[10px] font-bold text-slate-500">{{ $vectors->count() }} aksi</span>
                    </div>
                    <div class="max-h-[520px] divide-y divide-slate-100 overflow-y-auto">
                        @foreach($vectors as $vector)
                            @php $vectorColor = $vectorColors[$vector->action_type] ?? '#64748b'; @endphp
                            <div class="flex items-start gap-3 py-3">
                                <span class="mt-1 h-2.5 w-2.5 shrink-0 rounded-full" style="background-color: {{ $vectorColor }}"></span>
                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-wrap items-center justify-between gap-2">
                                        <p class="text-[12px] font-extrabold text-slate-700">
                                            {{ $vector->action_type === 'SHOT' && $vector->subtype ? 'Tembakan · ' . str_replace('_', ' ', $vector->subtype) : match ($vector->action_type) {
                                                'PASS' => 'Umpan',
                                                'CROSS' => 'Umpan silang',
                                                default => $vector->action_type,
                                            } }}
                                        </p>
                                        <span class="text-[10px] font-semibold text-slate-400">
                                            {{ $vector->match_minute !== null ? $vector->match_minute . ' menit' : ($vector->input_time ? \Carbon\Carbon::parse($vector->input_time)->format('H:i:s') : 'Waktu tidak dicatat') }}
                                        </span>
                                    </div>
                                    <p class="mt-1 text-[10px] text-slate-500">
                                        Dari ({{ number_format($vector->start_x, 1) }}, {{ number_format($vector->start_y, 1) }})
                                        ke ({{ number_format($vector->end_x, 1) }}, {{ number_format($vector->end_y, 1) }})
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @else
            <div class="rounded-xl border border-dashed border-slate-200 bg-slate-50/50 px-4 py-10 text-center">
                <i data-lucide="waypoints" class="mx-auto h-8 w-8 text-slate-300"></i>
                <p class="mt-2 text-[12px] font-medium text-slate-400">Belum ada data vektor untuk pemain ini di pertandingan tersebut.</p>
            </div>
        @endif
    </section>

    <div class="flex flex-wrap justify-between gap-3">
        <a href="{{ route('reports.player.show', $player->id) }}" class="inline-flex items-center gap-2 rounded-xl bg-[#4062D6] px-5 py-2.5 text-[12px] font-bold text-white shadow-sm transition hover:bg-[#3251b5]">
            <i data-lucide="arrow-left" class="h-4 w-4"></i>
            Kembali ke Performa Pemain
        </a>
        <a href="{{ route('reports.match.show', $match->id) }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-[12px] font-bold text-slate-600 transition hover:bg-slate-50">
            <i data-lucide="users-round" class="h-4 w-4"></i>
            Laporan Seluruh Tim
        </a>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (window.lucide) window.lucide.createIcons();
    });
</script>
@endsection

@extends('layouts.app')
@section('title', 'Laporan Performa Pemain - FFA Analytics')
@section('header', 'Laporan Performa Pemain')
@section('subheader', 'Informasi performa pemain')

@section('content')

<style>
    [data-lucide] { flex-shrink: 0; }
</style>

<div class="space-y-6">

    <!-- Header Profil -->
    <div class="bg-white rounded-[20px] border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] p-6 flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <span class="px-3.5 py-1.5 bg-[#1e293b] text-white rounded-xl text-sm font-bold shadow-sm">
                #{{ $player->jersey_number ?? $player->number ?? '-' }}
            </span>
            <div>
                <h2 class="text-lg font-bold text-[#1e293b]">{{ $player->name }}</h2>
                <p class="text-xs font-semibold text-slate-500 mt-0.5 flex items-center gap-1.5">
                    <i data-lucide="shield" class="w-3.5 h-3.5 text-slate-400"></i>
                    Tim: <span class="text-[#4062D6]">{{ $player->club->name ?? 'Tanpa Tim' }}</span>
                </p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-sm font-bold text-slate-500 uppercase tracking-wider">
                <i data-lucide="map-pin" class="w-4 h-4 text-slate-400"></i>
                {{ $player->position ?? '-' }}
            </div>

            <!-- Tombol Download PDF Laporan Lengkap -->
            <a href="{{ route('reports.player.pdf', $player->id) }}" target="_blank" rel="noopener"
               class="inline-flex items-center gap-2 px-4 py-2 bg-[#4062D6] hover:bg-[#3251b5] active:scale-95 text-white text-[13px] font-bold rounded-xl transition shadow-sm">
                <i data-lucide="file-down" class="w-4 h-4"></i>
                Download PDF
            </a>
        </div>
    </div>

    <!-- 3 Kartu Metrik -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="bg-white rounded-[20px] border border-slate-200 p-6 shadow-[0_2px_10px_rgb(0,0,0,0.02)] flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-orange-50 border border-orange-100 flex items-center justify-center text-orange-500">
                <i data-lucide="trophy" class="w-6 h-6"></i>
            </div>
            <div>
                <h3 class="text-2xl font-extrabold text-[#1e293b]">{{ $totals->goals }}</h3>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Gol</span>
            </div>
        </div>
        <div class="bg-white rounded-[20px] border border-slate-200 p-6 shadow-[0_2px_10px_rgb(0,0,0,0.02)] flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-500">
                <i data-lucide="target" class="w-6 h-6"></i>
            </div>
            <div>
                <h3 class="text-2xl font-extrabold text-[#1e293b]">{{ $totals->assists }}</h3>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Assist</span>
            </div>
        </div>
        <div class="bg-white rounded-[20px] border border-slate-200 p-6 shadow-[0_2px_10px_rgb(0,0,0,0.02)] flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-500">
                <i data-lucide="timer" class="w-6 h-6"></i>
            </div>
            <div>
                <h3 class="text-2xl font-extrabold text-[#1e293b]">{{ $totals->matches }}</h3>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Bermain</span>
            </div>
        </div>
    </div>

    <!-- Grafik & Radar -->
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
        <div class="lg:col-span-3 bg-white rounded-[20px] border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] p-6">
            <h3 class="text-[13px] font-bold text-[#1e293b] mb-4 flex items-center gap-2">
                <i data-lucide="trending-up" class="w-4 h-4 text-slate-400"></i>
                Statistik Performa Pemain (6 Bulan)
            </h3>
            @if($totals->matches > 0)
                <div class="relative h-[280px]"><canvas id="lineChart"></canvas></div>
            @else
                <div class="py-16 text-center text-slate-400 text-[13px] flex flex-col items-center gap-2">
                    <i data-lucide="chart-line" class="w-8 h-8 text-slate-300"></i>
                    Belum ada data statistik.
                </div>
            @endif
        </div>
        <div class="lg:col-span-2 bg-white rounded-[20px] border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] p-6">
            <h3 class="text-[13px] font-bold text-[#1e293b] mb-4 flex items-center gap-2">
                <i data-lucide="radar" class="w-4 h-4 text-slate-400"></i>
                Radar Atribut Pemain
            </h3>
            @if($totals->matches > 0)
                <div class="relative h-[280px]"><canvas id="radarChart"></canvas></div>
            @else
                <div class="py-16 text-center text-slate-400 text-[13px] flex flex-col items-center gap-2">
                    <i data-lucide="radar" class="w-8 h-8 text-slate-300"></i>
                    Belum ada data atribut.
                </div>
            @endif
        </div>
    </div>

    <!-- Riwayat Pertandingan -->
    <div class="bg-white rounded-[20px] border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] p-6">
        <h3 class="text-[13px] font-bold text-[#1e293b] mb-4 flex items-center gap-2">
            <i data-lucide="history" class="w-4 h-4 text-slate-400"></i>
            Riwayat Pertandingan
        </h3>
        <div class="divide-y divide-slate-100">
            @forelse($matches as $match)
                <div class="flex items-center justify-between py-3">
                    <div>
                        <h4 class="text-[13px] font-bold text-[#1e293b]">{{ $match->title ?? 'Pertandingan' }}</h4>
                        <p class="text-[11px] font-medium text-slate-400 flex items-center gap-1.5 mt-0.5">
                            <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                            {{ \Carbon\Carbon::parse($match->match_date)->translatedFormat('d F Y') }} - vs {{ $opponentOf($match) }}
                        </p>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('reports.match.show', $match->id) }}"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-600 text-[11px] font-bold rounded-lg transition-colors">
                            <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                            Lihat Match
                        </a>
                        <a href="{{ route('reports.player.match.pdf', [$player->id, $match->id]) }}"
                           target="_blank" rel="noopener"
                           class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-white border border-[#4062D6] hover:bg-blue-50 text-[#4062D6] text-[11px] font-bold rounded-full transition-colors shadow-sm">
                            <i data-lucide="file-down" class="w-3.5 h-3.5"></i>
                            PDF
                        </a>
                    </div>
                </div>
            @empty
                <div class="py-8 text-center text-slate-400 text-[13px] bg-slate-50/50 rounded-xl border border-dashed border-slate-200 flex flex-col items-center gap-2">
                    <i data-lucide="calendar-x" class="w-8 h-8 text-slate-300"></i>
                    Belum ada riwayat pertandingan untuk pemain ini.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Riwayat Musim -->
    <div class="bg-white rounded-[20px] border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] p-6">
        <h3 class="text-[13px] font-bold text-[#1e293b] mb-4 flex items-center gap-2">
            <i data-lucide="calendar-range" class="w-4 h-4 text-slate-400"></i>
            Riwayat Musim
        </h3>
        <div class="divide-y divide-slate-100">
            @forelse($seasons as $season)
                <div class="flex items-center justify-between py-3">
                    <div>
                        <h4 class="text-[13px] font-bold text-[#1e293b]">Musim {{ $season->label }}</h4>
                        <p class="text-[11px] font-medium text-slate-400">{{ $season->matches }} pertandingan</p>
                    </div>
                    <a href="{{ route('reports.player.season.pdf', [$player->id, $season->start]) }}"
                       target="_blank" rel="noopener"
                       class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-white border border-[#4062D6] hover:bg-blue-50 text-[#4062D6] text-[11px] font-bold rounded-full transition-colors shadow-sm">
                        <i data-lucide="file-down" class="w-3.5 h-3.5"></i>
                        PDF
                    </a>
                </div>
            @empty
                <div class="py-8 text-center text-slate-400 text-[13px] bg-slate-50/50 rounded-xl border border-dashed border-slate-200 flex flex-col items-center gap-2">
                    <i data-lucide="calendar-x" class="w-8 h-8 text-slate-300"></i>
                    Belum ada riwayat musim.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Kembali -->
    <div class="pt-2">
        <a href="{{ route('performa.index') }}"
           class="inline-flex items-center gap-2 px-6 py-2.5 bg-[#4062D6] hover:bg-[#3251b5] active:scale-95 text-white text-[13px] font-bold rounded-xl transition shadow-sm">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Kembali
        </a>
    </div>
</div>

<!-- Lucide Icons (hapus baris ini jika layouts.app sudah memuat Lucide) -->
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (window.lucide) lucide.createIcons({ attrs: { 'stroke-width': 2 } });
    });
</script>

@if($totals->matches > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    new Chart(document.getElementById('lineChart'), {
        type: 'line',
        data: {
            labels: @json($chartLabels),
            datasets: [{
                data: @json($chartValues),
                borderColor: '#34c38f',
                backgroundColor: 'rgba(52,195,143,0.1)',
                pointBackgroundColor: '#34c38f',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
        }
    });

    new Chart(document.getElementById('radarChart'), {
        type: 'radar',
        data: {
            labels: @json(array_keys($radar)),
            datasets: [{
                data: @json(array_values($radar)),
                backgroundColor: 'rgba(249,115,22,0.3)',
                borderColor: '#f97316',
                pointBackgroundColor: '#f97316'
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { r: { min: 0, max: 100, ticks: { display: false } } }
        }
    });
</script>
@endif
@endsection
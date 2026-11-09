@extends('layouts.app')
@section('title', 'Detail Performa Pemain - FFA Analytics')
@section('header', 'Laporan Performa Pemain')
@section('subheader', 'Informasi performa pemain')

@section('content')

<style>
    [data-lucide] { flex-shrink: 0; }
</style>

<div class="space-y-6">
    <!-- Header Profil Singkat Pemain -->
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

            <!-- Tombol Download PDF Laporan Lengkap Pemain -->
            <a href="{{ route('reports.player.pdf', $player->id) }}" target="_blank" rel="noopener"
               class="inline-flex items-center gap-2 px-4 py-2 bg-[#4062D6] hover:bg-[#3251b5] active:scale-95 text-white text-[13px] font-bold rounded-xl transition shadow-sm">
                <i data-lucide="file-down" class="w-4 h-4"></i>
                Download PDF
            </a>
        </div>
    </div>

    <!-- 3 Kartu Metrik Utama -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="bg-white rounded-[20px] border border-slate-200 p-6 shadow-[0_2px_10px_rgb(0,0,0,0.02)] flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-orange-50 border border-orange-100 flex items-center justify-center text-orange-500">
                <i data-lucide="trophy" class="w-6 h-6"></i>
            </div>
            <div>
                <h3 class="text-2xl font-extrabold text-[#1e293b]">{{ $player->total_goals ?? 0 }}</h3>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Gol</span>
            </div>
        </div>

        <div class="bg-white rounded-[20px] border border-slate-200 p-6 shadow-[0_2px_10px_rgb(0,0,0,0.02)] flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-500">
                <i data-lucide="target" class="w-6 h-6"></i>
            </div>
            <div>
                <h3 class="text-2xl font-extrabold text-[#1e293b]">{{ $player->total_assists ?? 0 }}</h3>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Assist</span>
            </div>
        </div>

        <div class="bg-white rounded-[20px] border border-slate-200 p-6 shadow-[0_2px_10px_rgb(0,0,0,0.02)] flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-500">
                <i data-lucide="timer" class="w-6 h-6"></i>
            </div>
            <div>
                <h3 class="text-2xl font-extrabold text-[#1e293b]">{{ $player->matches_played ?? 0 }}</h3>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Pertandingan</span>
            </div>
        </div>
    </div>

    <!-- Riwayat Pertandingan (Real-time dari Database) -->
    <div class="bg-white rounded-[20px] border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] p-6 space-y-4">
        <h3 class="text-[13px] font-bold text-[#1e293b] flex items-center gap-2">
            <i data-lucide="history" class="w-4 h-4 text-slate-400"></i>
            Riwayat Pertandingan
        </h3>
        <div class="space-y-3">
            @isset($player->matches)
                @forelse($player->matches as $match)
                    <div class="flex flex-wrap items-center justify-between gap-3 p-4 rounded-xl bg-slate-50/70 border border-slate-100">
                        <div>
                            <h4 class="text-[13px] font-bold text-[#1e293b]">{{ $match->title ?? 'Pertandingan' }}</h4>
                            <p class="text-[11px] font-medium text-slate-400 flex items-center gap-1.5 mt-0.5">
                                <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                                {{ \Carbon\Carbon::parse($match->match_date)->format('d F Y') }}
                            </p>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('reports.match.show', $match->id) }}" class="px-3.5 py-2 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-bold rounded-lg shadow-sm flex items-center gap-1.5 transition-colors">
                                <i data-lucide="file-text" class="w-3.5 h-3.5"></i>
                                Lihat Laporan Match
                            </a>

                            <!-- Tombol PDF per pertandingan -->
                            <a href="{{ route('reports.player.match.pdf', [$player->id, $match->id]) }}" target="_blank" rel="noopener"
                               class="px-3.5 py-2 bg-white border border-[#4062D6] hover:bg-blue-50 text-[#4062D6] text-xs font-bold rounded-lg shadow-sm flex items-center gap-1.5 transition-colors">
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
            @else
                <div class="py-8 text-center text-slate-400 text-[13px] bg-slate-50/50 rounded-xl border border-dashed border-slate-200 flex flex-col items-center gap-2">
                    <i data-lucide="calendar-x" class="w-8 h-8 text-slate-300"></i>
                    Belum ada riwayat pertandingan untuk pemain ini.
                </div>
            @endisset
        </div>
    </div>

    <!-- Tombol Kembali -->
    <div class="pt-2 flex items-center justify-start">
        <a href="{{ route('performa.index') }}" class="px-6 py-2.5 bg-[#4062D6] hover:bg-[#3251b5] active:scale-95 text-white text-[13px] font-bold rounded-xl transition shadow-sm inline-flex items-center justify-center gap-2">
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
@endsection
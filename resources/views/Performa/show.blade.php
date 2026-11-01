@extends('layouts.app')
@section('title', 'Detail Performa Pemain - FFA Analytics')
@section('header', 'Laporan Performa Pemain')
@section('subheader', 'Informasi performa pemain')

@section('content')
<div class="space-y-6">
    <!-- Header Profil Singkat Pemain -->
    <div class="bg-white rounded-[20px] border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] p-6 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <span class="px-3.5 py-1.5 bg-[#1e293b] text-white rounded-xl text-sm font-bold shadow-sm">
                #{{ $player->jersey_number ?? $player->number ?? '-' }}
            </span>
            <div>
                <h2 class="text-lg font-bold text-[#1e293b]">{{ $player->name }}</h2>
                <p class="text-xs font-semibold text-slate-500 mt-0.5">
                    Tim: <span class="text-[#4062D6]">{{ $player->club->name ?? 'Tanpa Tim' }}</span>
                </p>
            </div>
        </div>
        <div class="text-sm font-bold text-slate-500 uppercase tracking-wider">
            {{ $player->position ?? '-' }}
        </div>
    </div>

    <!-- 3 Kartu Metrik Utama -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="bg-white rounded-[20px] border border-slate-200 p-6 shadow-[0_2px_10px_rgb(0,0,0,0.02)] flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-orange-50 border border-orange-100 flex items-center justify-center text-orange-500 font-bold text-lg">⚽</div>
            <div>
                <h3 class="text-2xl font-extrabold text-[#1e293b]">{{ $player->total_goals ?? 0 }}</h3>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Gol</span>
            </div>
        </div>

        <div class="bg-white rounded-[20px] border border-slate-200 p-6 shadow-[0_2px_10px_rgb(0,0,0,0.02)] flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-500 font-bold text-lg">🎯</div>
            <div>
                <h3 class="text-2xl font-extrabold text-[#1e293b]">{{ $player->total_assists ?? 0 }}</h3>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Assist</span>
            </div>
        </div>

        <div class="bg-white rounded-[20px] border border-slate-200 p-6 shadow-[0_2px_10px_rgb(0,0,0,0.02)] flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-500 font-bold text-lg">⏱️</div>
            <div>
                <h3 class="text-2xl font-extrabold text-[#1e293b]">{{ $player->matches_played ?? 0 }}</h3>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Pertandingan</span>
            </div>
        </div>
    </div>

    <!-- Riwayat Pertandingan (Real-time dari Database) -->
    <div class="bg-white rounded-[20px] border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] p-6 space-y-4">
        <h3 class="text-[13px] font-bold text-[#1e293b]">Riwayat Pertandingan</h3>
        <div class="space-y-3">
            @isset($player->matches)
                @forelse($player->matches as $match)
                    <div class="flex items-center justify-between p-4 rounded-xl bg-slate-50/70 border border-slate-100">
                        <div>
                            <h4 class="text-[13px] font-bold text-[#1e293b]">{{ $match->title ?? 'Pertandingan' }}</h4>
                            <p class="text-[11px] font-medium text-slate-400">{{ \Carbon\Carbon::parse($match->match_date)->format('d F Y') }}</p>
                        </div>
                        <a href="{{ route('reports.match.show', $match->id) }}" class="px-3.5 py-2 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-bold rounded-lg shadow-sm flex items-center gap-1.5 transition-colors">
                            Lihat Laporan
                        </a>
                    </div>
                @empty
                    <div class="py-8 text-center text-slate-400 text-[13px] bg-slate-50/50 rounded-xl border border-dashed border-slate-200">
                        Belum ada riwayat pertandingan untuk pemain ini.
                    </div>
                @endforelse
            @else
                <div class="py-8 text-center text-slate-400 text-[13px] bg-slate-50/50 rounded-xl border border-dashed border-slate-200">
                    Belum ada riwayat pertandingan untuk pemain ini.
                </div>
            @endisset
        </div>
    </div>

    <!-- Tombol Kembali -->
    <div class="pt-2 flex items-center justify-start">
        <a href="{{ route('performa.index') }}" class="px-6 py-2.5 bg-[#4062D6] hover:bg-[#3251b5] text-white text-[13px] font-bold rounded-xl transition-colors shadow-sm text-center">
            Kembali
        </a>
    </div>
</div>
@endsection
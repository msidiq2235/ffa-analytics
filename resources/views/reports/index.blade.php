@extends('layouts.app')
@section('title', 'Export Laporan - FFA Analytics')
@section('header', 'Export Laporan')
@section('subheader', 'Pilih pemain atau pertandingan untuk melihat dan mengunduh laporan')

@section('content')

<style>
    [data-lucide] { flex-shrink: 0; }
</style>

<div class="space-y-6">

    <!-- Tab -->
    <div class="bg-white rounded-[20px] border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] overflow-hidden">
        <div class="flex border-b border-slate-100 bg-slate-50">
            <a href="{{ route('reports.index', ['tab' => 'player']) }}"
               class="inline-flex items-center gap-2 px-6 py-4 font-bold text-sm border-b-2 transition-colors {{ $tab === 'player' ? 'text-[#4062D6] border-[#4062D6] bg-white' : 'text-slate-500 border-transparent hover:text-slate-700' }}">
                <i data-lucide="user" class="w-4 h-4"></i>
                Per Pemain
            </a>
            <a href="{{ route('reports.index', ['tab' => 'match']) }}"
               class="inline-flex items-center gap-2 px-6 py-4 font-bold text-sm border-b-2 transition-colors {{ $tab === 'match' ? 'text-[#4062D6] border-[#4062D6] bg-white' : 'text-slate-500 border-transparent hover:text-slate-700' }}">
                <i data-lucide="trophy" class="w-4 h-4"></i>
                Per Pertandingan
            </a>
        </div>

        @if($tab === 'player')
            <!-- Cari pemain -->
            <form method="GET" action="{{ route('reports.index') }}" class="p-6 flex flex-wrap items-center gap-3 border-b border-slate-100">
                <input type="hidden" name="tab" value="player">
                <div class="flex-1 min-w-[240px] relative">
                    <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                    <input type="text" name="name" value="{{ request('name') }}" placeholder="Cari nama pemain..."
                           class="w-full pl-10 pr-4 py-2.5 bg-white border border-[#e2e8f0] rounded-xl text-[13px] text-[#334155] focus:outline-none focus:border-[#4062D6]">
                </div>
                <button type="submit" class="px-5 py-2.5 bg-[#4062D6] hover:bg-[#3251b5] active:scale-95 text-white text-[13px] font-bold rounded-xl transition shadow-sm inline-flex items-center gap-2">
                    <i data-lucide="search" class="w-4 h-4"></i>
                    Cari
                </button>
                @if(request('name'))
                    <a href="{{ route('reports.index', ['tab' => 'player']) }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 text-[13px] font-bold rounded-xl transition-colors inline-flex items-center gap-2">
                        <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                        Reset
                    </a>
                @endif
            </form>

            <!-- Tabel pemain -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100">
                            <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest w-[12%]">NO. PUNG.</th>
                            <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest w-[35%]">NAMA PEMAIN</th>
                            <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest w-[15%]">POSISI</th>
                            <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest w-[28%]">TIM</th>
                            <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right w-[10%]">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($players as $player)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-4 px-6">
                                    <span class="px-2.5 py-1 bg-[#1e293b] text-white rounded-full text-[11px] font-bold">
                                        #{{ $player->jersey_number ?? $player->number ?? '-' }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-[13px] font-bold text-[#1e293b]">{{ $player->name }}</td>
                                <td class="py-4 px-6 text-[13px] font-medium text-slate-600">{{ $player->position ?? '-' }}</td>
                                <td class="py-4 px-6 text-[13px] font-medium text-slate-600">{{ $player->club->name ?? 'Tanpa Tim' }}</td>
                                <td class="py-4 px-6 text-right">
                                    <a href="{{ route('reports.player.show', $player->id) }}"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-slate-50 border border-slate-200 text-slate-600 hover:bg-[#4062D6] hover:border-[#4062D6] hover:text-white transition-colors"
                                       title="Lihat Laporan Pemain" aria-label="Lihat laporan {{ $player->name }}">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-10 text-center text-slate-400 text-[13px]">
                                    <div class="flex flex-col items-center gap-2">
                                        <i data-lucide="user-x" class="w-8 h-8 text-slate-300"></i>
                                        Pemain tidak ditemukan.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4">{{ $players->links() }}</div>

        @else
            <!-- Tabel pertandingan -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100">
                            <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest w-[50%]">PERTANDINGAN</th>
                            <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest w-[25%]">TANGGAL</th>
                            <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right w-[25%]">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($matches as $match)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-4 px-6 text-[13px] font-bold text-[#1e293b]">{{ $match->title ?? 'Pertandingan' }}</td>
                                <td class="py-4 px-6 text-[13px] font-medium text-slate-600">
                                    <span class="inline-flex items-center gap-2">
                                        <i data-lucide="calendar" class="w-4 h-4 text-slate-400"></i>
                                        {{ \Carbon\Carbon::parse($match->match_date)->translatedFormat('d F Y') }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('reports.match.show', $match->id) }}"
                                           class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-600 text-[11px] font-bold rounded-lg transition-colors">
                                            <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                            Lihat
                                        </a>
                                        <a href="{{ route('reports.match.pdf', $match->id) }}" target="_blank" rel="noopener"
                                           class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-[#4062D6] hover:bg-blue-50 text-[#4062D6] text-[11px] font-bold rounded-lg transition-colors">
                                            <i data-lucide="file-down" class="w-3.5 h-3.5"></i>
                                            PDF
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-10 text-center text-slate-400 text-[13px]">
                                    <div class="flex flex-col items-center gap-2">
                                        <i data-lucide="calendar-x" class="w-8 h-8 text-slate-300"></i>
                                        Belum ada data pertandingan.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4">{{ $matches->links() }}</div>
        @endif
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
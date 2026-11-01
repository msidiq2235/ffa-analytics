@extends('layouts.app')
@section('title', 'Export Laporan - FFA Analytics')
@section('header', 'Export Laporan')
@section('subheader', 'Pilih pemain atau pertandingan untuk melihat dan mengunduh laporan')

@section('content')
<div class="space-y-6">

    <!-- Tab -->
    <div class="bg-white rounded-[20px] border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] overflow-hidden">
        <div class="flex border-b border-slate-100 bg-slate-50">
            <a href="{{ route('reports.index', ['tab' => 'player']) }}"
               class="px-6 py-4 font-bold text-sm border-b-2 transition-colors {{ $tab === 'player' ? 'text-[#4062D6] border-[#4062D6] bg-white' : 'text-slate-500 border-transparent hover:text-slate-700' }}">
                Per Pemain
            </a>
            <a href="{{ route('reports.index', ['tab' => 'match']) }}"
               class="px-6 py-4 font-bold text-sm border-b-2 transition-colors {{ $tab === 'match' ? 'text-[#4062D6] border-[#4062D6] bg-white' : 'text-slate-500 border-transparent hover:text-slate-700' }}">
                Per Pertandingan
            </a>
        </div>

        @if($tab === 'player')
            <!-- Cari pemain -->
            <form method="GET" action="{{ route('reports.index') }}" class="p-6 flex flex-wrap items-center gap-3 border-b border-slate-100">
                <input type="hidden" name="tab" value="player">
                <input type="text" name="name" value="{{ request('name') }}" placeholder="Cari nama pemain..."
                       class="flex-1 min-w-[240px] px-4 py-2.5 bg-white border border-[#e2e8f0] rounded-xl text-[13px] text-[#334155] focus:outline-none focus:border-[#4062D6]">
                <button type="submit" class="px-5 py-2.5 bg-[#4062D6] hover:bg-[#3251b5] text-white text-[13px] font-bold rounded-xl transition-colors shadow-sm">
                    Cari
                </button>
                @if(request('name'))
                    <a href="{{ route('reports.index', ['tab' => 'player']) }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 text-[13px] font-bold rounded-xl transition-colors">Reset</a>
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
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-slate-50 border border-slate-200 text-slate-600 hover:bg-slate-100 transition-colors"
                                       title="Lihat Laporan Pemain">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-slate-400 text-[13px]">Pemain tidak ditemukan.</td>
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
                                    {{ \Carbon\Carbon::parse($match->match_date)->translatedFormat('d F Y') }}
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('reports.match.show', $match->id) }}"
                                           class="px-3 py-1.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-600 text-[11px] font-bold rounded-lg transition-colors">
                                            Lihat
                                        </a>
                                        <a href="{{ route('reports.match.pdf', $match->id) }}" target="_blank" rel="noopener"
                                            class="px-3 py-1.5 bg-white border border-[#4062D6] hover:bg-blue-50 text-[#4062D6] text-[11px] font-bold rounded-lg transition-colors">
                                            PDF
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-8 text-center text-slate-400 text-[13px]">Belum ada data pertandingan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4">{{ $matches->links() }}</div>
        @endif
    </div>
</div>
@endsection
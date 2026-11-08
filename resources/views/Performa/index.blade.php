@extends('layouts.app')
@section('title', 'Laporan Performa Pemain - FFA Analytics')
@section('header', 'Laporan Performa Pemain')
@section('subheader', 'Informasi performa pemain')

@section('content')

<style>
    [data-lucide] { flex-shrink: 0; }
</style>

<div class="space-y-6">
    <!-- Form Filter Section -->
    <div class="bg-white rounded-[20px] border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] p-6">
        <h3 class="text-[13px] font-bold text-[#334155] mb-4 flex items-center gap-2">
            <i data-lucide="users" class="w-4 h-4 text-slate-400"></i>
            Pilih Pemain
        </h3>
        <form method="GET" action="{{ route('performa.index') }}" class="space-y-4">
            <div class="flex flex-wrap items-center gap-3">
                <div class="flex-1 min-w-[240px] relative">
                    <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                    <input type="text" name="name" value="{{ request('name') }}" placeholder="Cari nama pemain..." class="w-full pl-10 pr-4 py-2.5 bg-white border border-[#e2e8f0] rounded-xl text-[13px] text-[#334155] focus:outline-none focus:border-[#4062D6]">
                </div>
                <button type="button" class="px-5 py-2.5 bg-[#4062D6] hover:bg-[#3251b5] active:scale-95 text-white text-[13px] font-bold rounded-xl transition shadow-sm flex items-center gap-2">
                    <i data-lucide="sliders-horizontal" class="w-4 h-4"></i>
                    Filter
                </button>
                <button type="submit" class="px-5 py-2.5 bg-[#4062D6] hover:bg-[#3251b5] active:scale-95 text-white text-[13px] font-bold rounded-xl transition shadow-sm flex items-center gap-2">
                    <i data-lucide="search" class="w-4 h-4"></i>
                    Cari
                </button>
                @if(request()->hasAny(['name', 'position', 'number', 'club_id']))
                    <a href="{{ route('performa.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 text-[13px] font-bold rounded-xl transition-colors flex items-center gap-2">
                        <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                        Reset
                    </a>
                @endif
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 pt-2">
                <div>
                    <label class="flex items-center gap-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">
                        <i data-lucide="user" class="w-3.5 h-3.5"></i> Nama Pemain
                    </label>
                    <input type="text" name="name" value="{{ request('name') }}" placeholder="Nama pemain" class="w-full px-3 py-2 bg-slate-50 border border-[#e2e8f0] rounded-lg text-[12px] text-[#334155] focus:outline-none focus:border-[#4062D6]">
                </div>

                <div>
                    <label class="flex items-center gap-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">
                        <i data-lucide="map-pin" class="w-3.5 h-3.5"></i> Posisi
                    </label>
                    <select name="position" class="w-full px-3 py-2 bg-slate-50 border border-[#e2e8f0] rounded-lg text-[12px] text-[#334155] focus:outline-none focus:border-[#4062D6]">
                        <option value="">Semua Posisi</option>
                        <option value="GK" {{ request('position') == 'GK' ? 'selected' : '' }}>GK (Kiper)</option>
                        <option value="CB" {{ request('position') == 'CB' ? 'selected' : '' }}>CB (Bek Tengah)</option>
                        <option value="LB" {{ request('position') == 'LB' ? 'selected' : '' }}>LB (Bek Kiri)</option>
                        <option value="RB" {{ request('position') == 'RB' ? 'selected' : '' }}>RB (Bek Kanan)</option>
                        <option value="DMF" {{ request('position') == 'DMF' ? 'selected' : '' }}>DMF (Gelandang Bertahan)</option>
                        <option value="CMF" {{ request('position') == 'CMF' ? 'selected' : '' }}>CMF (Gelandang Tengah)</option>
                        <option value="AMF" {{ request('position') == 'AMF' ? 'selected' : '' }}>AMF (Gelandang Serang)</option>
                        <option value="LW" {{ request('position') == 'LW' ? 'selected' : '' }}>LW (Sayap Kiri)</option>
                        <option value="RW" {{ request('position') == 'RW' ? 'selected' : '' }}>RW (Sayap Kanan)</option>
                        <option value="ST" {{ request('position') == 'ST' ? 'selected' : '' }}>ST (Penyerang)</option>
                    </select>
                </div>

                <div>
                    <label class="flex items-center gap-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">
                        <i data-lucide="hash" class="w-3.5 h-3.5"></i> Nomor Punggung
                    </label>
                    <input type="number" name="number" value="{{ request('number') }}" placeholder="Contoh: 10" class="w-full px-3 py-2 bg-slate-50 border border-[#e2e8f0] rounded-lg text-[12px] text-[#334155] focus:outline-none focus:border-[#4062D6]">
                </div>

                <div>
                    <label class="flex items-center gap-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">
                        <i data-lucide="shield" class="w-3.5 h-3.5"></i> Tim / Klub
                    </label>
                    <select name="club_id" class="w-full px-3 py-2 bg-slate-50 border border-[#e2e8f0] rounded-lg text-[12px] text-[#334155] focus:outline-none focus:border-[#4062D6]">
                        <option value="">Semua Tim</option>
                        @foreach($clubs as $club)
                            <option value="{{ $club->id }}" {{ request('club_id') == $club->id ? 'selected' : '' }}>
                                {{ $club->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>
    </div>

    <!-- Table Result Section -->
    <div class="bg-white rounded-[20px] border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest w-[12%]">NO. PUNG.</th>
                        <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest w-[40%]">NAMA PEMAIN</th>
                        <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest w-[38%]">POSISI</th>
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
                            <td class="py-4 px-6 text-[13px] font-bold text-[#1e293b] truncate max-w-[200px]">{{ $player->name }}</td>
                            <td class="py-4 px-6 text-[13px] font-medium text-slate-600">{{ $player->position ?? '-' }}</td>
                            <td class="py-4 px-6 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <!-- Tombol Lihat Detail Performa -->
                                    <a href="{{ route('performa.show', $player->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-slate-50 border border-slate-200 text-slate-600 hover:bg-[#4062D6] hover:border-[#4062D6] hover:text-white transition-colors" title="Lihat Detail Performa">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </a>

                                    <!-- Tombol Hapus -->
                                    <form action="{{ route('players.destroy', $player->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pemain ini?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-slate-50 border border-slate-200 text-slate-600 hover:bg-rose-500 hover:border-rose-500 hover:text-white transition-colors" title="Hapus Pemain">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-10 text-center text-slate-400 text-[13px]">
                                <div class="flex flex-col items-center gap-2">
                                    <i data-lucide="user-x" class="w-8 h-8 text-slate-300"></i>
                                    Pemain tidak ditemukan atau belum ada data di database.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
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
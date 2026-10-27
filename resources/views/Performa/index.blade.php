@extends('layouts.app')
@section('title', 'Laporan Performa Pemain - FFA Analytics')
@section('header', 'Laporan Performa Pemain')
@section('subheader', 'Informasi performa pemain')

@section('content')
<div class="space-y-6">
    <!-- Form Filter Section -->
    <div class="bg-white rounded-[20px] border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] p-6">
        <h3 class="text-[13px] font-bold text-[#334155] mb-4">Pilih Pemain</h3>
        <form method="GET" action="{{ route('performa.index') }}" class="space-y-4">
            <!-- Baris Utama (Search Input + Tombol) -->
            <div class="flex flex-wrap items-center gap-3">
                <div class="flex-1 min-w-[240px]">
                    <input type="text" name="name" value="{{ request('name') }}" placeholder="Cari nama pemain..." class="w-full px-4 py-2.5 bg-white border border-[#e2e8f0] rounded-xl text-[13px] text-[#334155] focus:outline-none focus:border-[#4062D6]">
                </div>
                <button type="submit" class="px-5 py-2.5 bg-[#4062D6] hover:bg-[#3251b5] text-white text-[13px] font-bold rounded-xl transition-colors shadow-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    Cari
                </button>
                @if(request()->hasAny(['name', 'position', 'number', 'club_id']))
                    <a href="{{ route('performa.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 text-[13px] font-bold rounded-xl transition-colors">
                        Reset
                    </a>
                @endif
            </div>

            <!-- Baris Detail Filter (Nama, Posisi, Nomor Punggung, Tim) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 pt-2">
                <div>
                    <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Nama Pemain</label>
                    <input type="text" name="name" value="{{ request('name') }}" placeholder="Nama pemain" class="w-full px-3 py-2 bg-slate-50 border border-[#e2e8f0] rounded-lg text-[12px] text-[#334155] focus:outline-none focus:border-[#4062D6]">
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Posisi</label>
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
                    <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Nomor Punggung</label>
                    <input type="number" name="number" value="{{ request('number') }}" placeholder="Contoh: 10" class="w-full px-3 py-2 bg-slate-50 border border-[#e2e8f0] rounded-lg text-[12px] text-[#334155] focus:outline-none focus:border-[#4062D6]">
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Tim / Klub</label>
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
                        <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest w-[20%]">POSISI</th>
                        <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest w-[18%]">TIM</th>
                        <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right w-[10%]">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($players as $player)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 bg-[#1e293b] text-white rounded-full text-[11px] font-bold">
                                    #{{ $player->number ?? '-' }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-[13px] font-bold text-[#1e293b]">{{ $player->name }}</td>
                            <td class="py-4 px-6 text-[13px] font-medium text-slate-600">{{ $player->position ?? '-' }}</td>
                            <td class="py-4 px-6 text-[13px] font-medium text-slate-600">{{ $player->club->name ?? 'Tanpa Tim' }}</td>
                            <td class="py-4 px-6 text-right space-x-1">
                                <a href="{{ route('players.show', $player->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-slate-50 border border-slate-200 text-slate-600 hover:bg-slate-100 transition-colors" title="Lihat Detail">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-400 text-[13px]">Pemain tidak ditemukan atau belum ada data di database.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
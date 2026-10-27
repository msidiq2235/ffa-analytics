@extends('layouts.app')
@section('title', 'Database Pemain - FFA Analytics')
@section('header', 'Database Pemain')
@section('subheader', 'Kelola data dan profil pemain akademi')
@section('content')

<div class="space-y-6">
    <!-- Filter & Action Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] p-6">
        <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-4">Filter Pemain</h3>
        
        <form method="GET" action="{{ route('players.index') }}" class="flex flex-col lg:flex-row items-end lg:items-center justify-between gap-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 flex-1 w-full">
                <!-- Nama Pemain -->
                <div>
                    <label for="name" class="block text-[11px] font-bold text-slate-400 mb-1">Nama Pemain</label>
                    <input type="text" id="name" name="name" value="{{ request('name') }}" placeholder="Cari nama..." 
                           class="w-full px-3.5 py-2 bg-white border border-slate-200 rounded-xl text-[12px] text-slate-700 placeholder-slate-400 focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] transition-all shadow-sm">
                </div>

                <!-- Posisi -->
                <div>
                    <label for="position" class="block text-[11px] font-bold text-slate-400 mb-1">Posisi</label>
                    <div class="relative">
                        <select id="position" name="position" 
                                class="w-full px-3.5 py-2 bg-white border border-slate-200 rounded-xl text-[12px] text-slate-700 appearance-none focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] transition-all shadow-sm cursor-pointer">
                            <option value="">Semua Posisi</option>
                            <option value="ST" {{ request('position') == 'ST' ? 'selected' : '' }}>ST</option>
                            <option value="GK" {{ request('position') == 'GK' ? 'selected' : '' }}>GK</option>
                            <option value="CM" {{ request('position') == 'CM' ? 'selected' : '' }}>CM</option>
                            <option value="CB" {{ request('position') == 'CB' ? 'selected' : '' }}>CB</option>
                        </select>
                        <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Nomor Punggung -->
                <div>
                    <label for="jersey" class="block text-[11px] font-bold text-slate-400 mb-1">Nomor Punggung</label>
                    <input type="text" id="jersey" name="jersey" value="{{ request('jersey') }}" placeholder="Contoh: 10" 
                           class="w-full px-3.5 py-2 bg-white border border-slate-200 rounded-xl text-[12px] text-slate-700 placeholder-slate-400 focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] transition-all shadow-sm">
                </div>

                <!-- Tim Asal -->
                <div>
                    <label for="club" class="block text-[11px] font-bold text-slate-400 mb-1">Tim Asal</label>
                    <div class="relative">
                        <select id="club" name="club" 
                                class="w-full px-3.5 py-2 bg-white border border-slate-200 rounded-xl text-[12px] text-slate-700 appearance-none focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] transition-all shadow-sm cursor-pointer">
                            <option value="">Semua Tim</option>
                            <option value="GhostTim" {{ request('club') == 'GhostTim' ? 'selected' : '' }}>GhostTim</option>
                            <option value="BunnyTim" {{ request('club') == 'BunnyTim' ? 'selected' : '' }}>BunnyTim</option>
                        </select>
                        <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tombol Cari & Action -->
            <div class="flex items-center gap-2 w-full lg:w-auto shrink-0 pt-2 lg:pt-0">
                <button type="submit" class="px-4 py-2 bg-[#4062D6] hover:bg-[#3251b5] text-white rounded-xl text-[12px] font-bold flex items-center gap-1.5 transition-colors shadow-sm active:scale-95">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    Cari
                </button>
                <a href="{{ route('players.create') }}" class="px-4 py-2 bg-[#4062D6] hover:bg-[#3251b5] text-white rounded-xl text-[12px] font-bold flex items-center gap-1.5 transition-colors shadow-sm active:scale-95">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Pemain
                </a>
                <a href="#" class="px-4 py-2 bg-[#4062D6] hover:bg-[#3251b5] text-white rounded-xl text-[12px] font-bold flex items-center gap-1.5 transition-colors shadow-sm active:scale-95">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    Impor Pemain
                </a>
            </div>
        </form>
    </div>

    <!-- Tabel Database Pemain -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] overflow-x-auto">
        <table class="w-full text-left border-collapse min-w-[900px]">
            <thead>
                <tr class="border-b border-slate-100">
                    <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest w-[10%]">No. Pung.</th>
                    <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest w-[25%]">Nama Pemain</th>
                    <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest w-[12%]">Posisi</th>
                    <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest w-[18%]">Klub Asal</th>
                    <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest w-[15%]">Klub Kedua</th>
                    <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest w-[10%]">Status</th>
                    <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest w-[10%] text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($players ?? [] as $player)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="py-4 px-6">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-[#1e293b] text-white text-[11px] font-bold">
                            #{{ $player->jersey_number ?? '10' }}
                        </span>
                    </td>
                    <td class="py-4 px-6 text-[13px] font-bold text-[#1e293b]">
                        {{ $player->name }}
                    </td>
                    <td class="py-4 px-6 text-[12px] font-bold text-slate-600">
                        {{ $player->position ?? 'ST' }}
                    </td>
                    <td class="py-4 px-6 text-[12px] font-medium text-slate-600">
                        {{ $player->club->name ?? $player->club_name ?? 'GhostTim' }}
                    </td>
                    <td class="py-4 px-6 text-[12px] font-medium text-slate-600">
                        {{ $player->second_club ?? '-' }}
                    </td>
                    <td class="py-4 px-6">
                        @if(($player->status ?? 'Aktif') == 'Aktif')
                            <span class="inline-block px-3 py-1 bg-emerald-50 border border-emerald-100 text-emerald-600 rounded-full text-[11px] font-bold">
                                Aktif
                            </span>
                        @else
                            <span class="inline-block px-3 py-1 bg-slate-100 border border-slate-200 text-slate-500 rounded-full text-[11px] font-bold">
                                Nonaktif
                            </span>
                        @endif
                    </td>
                    <td class="py-4 px-6 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('players.edit', $player->id) }}" class="w-8 h-8 rounded-lg bg-slate-50 hover:bg-slate-100 border border-slate-100 flex items-center justify-center text-slate-400 hover:text-[#4062D6] transition-colors shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            </a>
                            <form action="{{ route('players.destroy', $player->id) }}" method="POST" onsubmit="return confirm('Hapus data pemain ini?');" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-8 h-8 rounded-lg bg-slate-50 hover:bg-rose-50 border border-slate-100 flex items-center justify-center text-slate-400 hover:text-rose-500 transition-colors shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <!-- Dummy Row sesuai gambar jika database kosong -->
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="py-4 px-6">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-[#1e293b] text-white text-[11px] font-bold">#10</span>
                    </td>
                    <td class="py-4 px-6 text-[13px] font-bold text-[#1e293b]">Yazid Ardiyan Wicaksono</td>
                    <td class="py-4 px-6 text-[12px] font-bold text-slate-600">ST</td>
                    <td class="py-4 px-6 text-[12px] font-medium text-slate-600">GhostTim</td>
                    <td class="py-4 px-6 text-[12px] font-medium text-slate-600">BunnyTim</td>
                    <td class="py-4 px-6">
                        <span class="inline-block px-3 py-1 bg-emerald-50 border border-emerald-100 text-emerald-600 rounded-full text-[11px] font-bold">Aktif</span>
                    </td>
                    <td class="py-4 px-6 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="#" class="w-8 h-8 rounded-lg bg-slate-50 hover:bg-slate-100 border border-slate-100 flex items-center justify-center text-slate-400 hover:text-[#4062D6] transition-colors shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            </a>
                            <a href="#" class="w-8 h-8 rounded-lg bg-slate-50 hover:bg-rose-50 border border-slate-100 flex items-center justify-center text-slate-400 hover:text-rose-500 transition-colors shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
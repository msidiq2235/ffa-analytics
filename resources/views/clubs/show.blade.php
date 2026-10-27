@extends('layouts.app')
@section('title', 'Daftar Anggota Tim - FFA Analytics')
@section('header', 'Daftar Anggota Tim')
@section('subheader', 'Daftarkan Pemain ke dalam skuad klub atau akademi')
@section('content')

<div class="space-y-6">
    <!-- Card Container -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] p-6 md:p-8">
        
        <div class="mb-6">
            <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3">Daftar Anggota Tim</h3>
            
            <!-- Form Pencarian -->
            <form method="GET" action="{{ route('clubs.show', $club->id) }}" class="flex items-center gap-3">
                <div class="flex-1">
                    <label for="search" class="block text-[12px] font-bold text-slate-700 mb-1.5">Cari Nama Pemain</label>
                    <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Cari berdasarkan nama..." 
                           class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 placeholder-slate-400 focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] transition-all shadow-sm">
                </div>
                <div class="pt-5">
                    <button type="submit" class="w-10 h-10 bg-[#4062D6] hover:bg-[#3251b5] text-white rounded-xl flex items-center justify-center transition-colors shadow-sm active:scale-95 shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabel Anggota Tim -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100">
                        <th class="py-3 px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest w-[15%]">No. Punggung</th>
                        <th class="py-3 px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest w-[50%]">Nama Lengkap Pemain</th>
                        <th class="py-3 px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest w-[20%]">Status Pemain</th>
                        <th class="py-3 px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest w-[15%] text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($club->players ?? [] as $player)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="py-4 px-4 text-[13px] font-bold text-[#1e293b]">
                            #{{ $player->jersey_number ?? '-' }}
                        </td>
                        <td class="py-4 px-4 text-[13px] font-bold text-[#1e293b]">
                            {{ $player->name }}
                        </td>
                        <td class="py-4 px-4">
                            <span class="inline-block px-3 py-1 bg-emerald-50 border border-emerald-100 text-emerald-600 rounded-full text-[11px] font-bold">
                                {{ $player->status ?? 'Aktif' }}
                            </span>
                        </td>
                        <td class="py-4 px-4 text-right">
                            <!-- Contoh Form Hapus/Keluarkan Pemain dari Klub -->
                            <form action="#" method="POST" onsubmit="return confirm('Keluarkan pemain ini dari klub?');" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-8 h-8 rounded-lg bg-slate-50 hover:bg-rose-50 border border-slate-100 inline-flex items-center justify-center text-slate-400 hover:text-rose-500 transition-colors shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-8 text-center text-slate-400 text-[13px] font-medium">
                            Belum ada pemain yang terdaftar di dalam tim ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

    <!-- Tombol Kembali -->
    <div>
        <a href="{{ route('clubs.index') }}" class="inline-flex items-center justify-center px-6 py-2.5 bg-[#4062D6] hover:bg-[#3251b5] text-white text-[13px] font-bold rounded-xl transition-colors shadow-sm active:scale-95">
            Kembali
        </a>
    </div>
</div>

@endsection
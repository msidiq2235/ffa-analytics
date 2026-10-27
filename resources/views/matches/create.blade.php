@extends('layouts.app')
@section('title', 'Tambah Pertandingan - FFA Analytics')
@section('header', 'Tambah Pertandingan')
@section('subheader', 'Kelola jadwal pertandingan akademi')
@section('content')

<div class="bg-white rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] overflow-hidden">
    <div class="px-8 py-5 border-b border-slate-100 bg-white">
        <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Informasi Pertandingan</h3>
    </div>
    
    <form action="{{ route('matches.store') }}" method="POST" class="p-8">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6">
            <!-- Kolom Kiri -->
            <div class="space-y-6">
                <div>
                    <label for="team_id" class="block text-[12px] font-bold text-slate-700 mb-2">Nama Tim</label>
                    <div class="relative">
                        <select id="team_id" name="team_id" required 
                                class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 appearance-none focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] transition-all shadow-sm cursor-pointer">
                            <option value="" disabled selected class="text-slate-300">Nama Tim</option>
                            <!-- Looping data klub di sini, contoh: -->
                            <!-- <option value="1">GhostClub</option> -->
                        </select>
                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-300">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                
                <div>
                    <label for="opponent" class="block text-[12px] font-bold text-slate-700 mb-2">Tim Lawan</label>
                    <input type="text" id="opponent" name="opponent" placeholder="Nama Tim Lawan" required 
                           class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 placeholder-slate-300 focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] transition-all shadow-sm">
                </div>

                <div>
                    <label for="formation" class="block text-[12px] font-bold text-slate-700 mb-2">Formasi</label>
                    <input type="text" id="formation" name="formation" required 
                           class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 placeholder-slate-300 focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] transition-all shadow-sm">
                </div>
            </div>

            <!-- Kolom Kanan -->
            <div class="space-y-6">
                <div>
                    <label for="match_date" class="block text-[12px] font-bold text-slate-700 mb-2">Tanggal Pertandingan</label>
                    <input type="datetime-local" id="match_date" name="match_date" required 
                           class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] transition-all shadow-sm">
                </div>

                <div>
                    <label for="location" class="block text-[12px] font-bold text-slate-700 mb-2">Tempat Pertandingan</label>
                    <input type="text" id="location" name="location" required 
                           class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 placeholder-slate-300 focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] transition-all shadow-sm">
                </div>
            </div>
        </div>

        <!-- Tombol Aksi -->
        <div class="mt-12 pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
            <a href="{{ route('matches.index') }}" 
               class="px-6 py-2.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-[13px] font-bold rounded-xl transition-colors shadow-sm text-center">
                Batal
            </a>
            <button type="submit" 
                    class="px-6 py-2.5 bg-[#4062D6] hover:bg-[#3251b5] text-white text-[13px] font-bold rounded-xl transition-colors shadow-sm active:scale-95">
                Simpan Pertandingan
            </button>
        </div>
    </form>
</div>

@endsection
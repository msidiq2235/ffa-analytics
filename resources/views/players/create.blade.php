@extends('layouts.app')
@section('title', 'Tambah Pemain Baru - FFA Analytics')
@section('header', 'Tambah Pemain Baru')
@section('subheader', 'Daftarkan pemain baru ke dalam sistem dan tentukan klubnya')
@section('content')

<div class="bg-white rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] p-8">
    <form action="{{ route('players.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6">
            <!-- Kolom Kiri -->
            <div class="space-y-6">
                <div>
                    <label for="name" class="block text-[13px] font-bold text-slate-700 mb-2">Nama Pemain</label>
                    <input type="text" id="name" name="name" placeholder="Nama Lengkap Pemain" required 
                           class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 placeholder-slate-400 focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] transition-all shadow-sm">
                </div>
                
                <div>
                    <label for="position" class="block text-[13px] font-bold text-slate-700 mb-2">Posisi</label>
                    <input type="text" id="position" name="position" placeholder="Contoh: ST, GK, CM, CB" required 
                           class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 placeholder-slate-400 focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] transition-all shadow-sm">
                </div>

                <div>
                    <label for="jersey_number" class="block text-[13px] font-bold text-slate-700 mb-2">Nomor Punggung</label>
                    <input type="number" id="jersey_number" name="jersey_number" placeholder="Contoh: 10" required 
                           class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 placeholder-slate-400 focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] transition-all shadow-sm">
                </div>
            </div>

            <!-- Kolom Kanan -->
            <div class="space-y-6">
                <div>
                    <label class="block text-[13px] font-bold text-slate-700 mb-2">Tim</label>
                    <!-- Kotak Checklist Tim -->
                    <div class="w-full bg-white border border-slate-200 rounded-xl p-4 space-y-3 shadow-sm">
                        <div class="text-[12px] font-medium text-slate-400 border-b border-slate-100 pb-2">
                            Checklist Tim yang Terdaftar oleh Pemain
                        </div>
                        
                        <!-- Looping klub dari database jika ada, atau gunakan contoh statis berikut -->
                        @forelse($clubs ?? [] as $club)
                        <label class="flex items-center gap-3 cursor-pointer select-none">
                            <input type="checkbox" name="clubs[]" value="{{ $club->id }}" 
                                   class="w-4 h-4 text-[#4062D6] border-slate-300 rounded focus:ring-[#4062D6]">
                            <span class="text-[13px] font-medium text-slate-700">{{ $club->name }}</span>
                        </label>
                        @empty
                        <!-- Fallback jika variabel clubs belum dikirim dari controller -->
                        <label class="flex items-center gap-3 cursor-pointer select-none">
                            <input type="checkbox" name="clubs[]" value="BunnyTim" class="w-4 h-4 text-[#4062D6] border-slate-300 rounded focus:ring-[#4062D6]">
                            <span class="text-[13px] font-medium text-slate-700">BunnyTim</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer select-none">
                            <input type="checkbox" name="clubs[]" value="GhostTim" class="w-4 h-4 text-[#4062D6] border-slate-300 rounded focus:ring-[#4062D6]">
                            <span class="text-[13px] font-medium text-slate-700">GhostTim</span>
                        </label>
                        @endforelse
                    </div>
                </div>

                <div>
                    <label for="status" class="block text-[13px] font-bold text-slate-700 mb-2">Status</label>
                    <div class="relative">
                        <select id="status" name="status" required 
                                class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 appearance-none focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] transition-all shadow-sm cursor-pointer">
                            <option value="" disabled selected class="text-slate-400">Pilih Status</option>
                            <option value="Aktif">Aktif</option>
                            <option value="Nonaktif">Nonaktif</option>
                        </select>
                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tombol Aksi -->
        <div class="mt-12 pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
            <a href="{{ route('players.index') }}" 
               class="px-6 py-2.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-[13px] font-bold rounded-xl transition-colors shadow-sm text-center">
                Batal
            </a>
            <button type="submit" 
                    class="px-6 py-2.5 bg-[#4062D6] hover:bg-[#3251b5] text-white text-[13px] font-bold rounded-xl transition-colors shadow-sm active:scale-95">
                Tambah Pemain
            </button>
        </div>
    </form>
</div>

@endsection
@extends('layouts.app')
@section('title', 'Edit Data Pemain - FFA Analytics')
@section('header', 'Edit Data Pemain')
@section('subheader', 'Perbarui Informasi profil, transfer klub, dan status pemain')
@section('content')

<div class="bg-white rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] p-8">
    <form action="{{ route('players.update', $player->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6">
            <!-- Kolom Kiri -->
            <div class="space-y-6">
                <div>
                    <label for="name" class="block text-[13px] font-bold text-slate-700 mb-2">Nama Pemain</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $player->name) }}" required 
                           class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 placeholder-slate-400 focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] transition-all shadow-sm">
                </div>
                
                <div>
                    <label for="position" class="block text-[13px] font-bold text-slate-700 mb-2">Posisi</label>
                    <div class="relative">
                        <select id="position" name="position" required 
                                class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 appearance-none focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] transition-all shadow-sm cursor-pointer">
                            <option value="" disabled>Pilih Posisi</option>
                            <option value="ST" {{ (old('position', $player->position) == 'ST') ? 'selected' : '' }}>ST</option>
                            <option value="GK" {{ (old('position', $player->position) == 'GK') ? 'selected' : '' }}>GK</option>
                            <option value="CM" {{ (old('position', $player->position) == 'CM') ? 'selected' : '' }}>CM</option>
                            <option value="CB" {{ (old('position', $player->position) == 'CB') ? 'selected' : '' }}>CB</option>
                            <option value="LW" {{ (old('position', $player->position) == 'LW') ? 'selected' : '' }}>LW</option>
                            <option value="RW" {{ (old('position', $player->position) == 'RW') ? 'selected' : '' }}>RW</option>
                        </select>
                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="jersey_number" class="block text-[13px] font-bold text-slate-700 mb-2">Nomor Punggung</label>
                    <input type="number" id="jersey_number" name="jersey_number" value="{{ old('jersey_number', $player->jersey_number) }}" required 
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
                        
                        @php
                            // Mengambil ID klub yang sudah diikuti pemain ini (jika menggunakan relasi many-to-many / pivot)
                            $playerClubs = isset($player->clubs) ? $player->clubs->pluck('id')->toArray() : [];
                        @endphp

                        @forelse($clubs ?? [] as $club)
                        <label class="flex items-center gap-3 cursor-pointer select-none">
                            <input type="checkbox" name="clubs[]" value="{{ $club->id }}" 
                                   {{ in_array($club->id, $playerClubs) ? 'checked' : '' }}
                                   class="w-4 h-4 text-[#4062D6] border-slate-300 rounded focus:ring-[#4062D6]">
                            <span class="text-[13px] font-medium text-slate-700">{{ $club->name }}</span>
                        </label>
                        @empty
                        <!-- Fallback jika variabel clubs belum dikirim dari controller -->
                        <label class="flex items-center gap-3 cursor-pointer select-none">
                            <input type="checkbox" name="clubs[]" value="BunnyTim" checked class="w-4 h-4 text-[#4062D6] border-slate-300 rounded focus:ring-[#4062D6]">
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
                            <option value="" disabled>Pilih Status</option>
                            <option value="Aktif" {{ (old('status', $player->status ?? 'Aktif') == 'Aktif') ? 'selected' : '' }}>Aktif</option>
                            <option value="Nonaktif" {{ (old('status', $player->status ?? '') == 'Nonaktif') ? 'selected' : '' }}>Nonaktif</option>
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
                Update Data
            </button>
        </div>
    </form>
</div>

@endsection
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
                    <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Nama Lengkap Pemain" required 
                           class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 placeholder-slate-400 focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] transition-all shadow-sm">
                </div>
                
                <div>
                    <label for="position" class="block text-[13px] font-bold text-slate-700 mb-2">Posisi</label>
                    <div class="relative">
                        <select id="position" name="position" required 
                                class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 appearance-none focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] transition-all shadow-sm cursor-pointer">
                            <option value="" disabled selected class="text-slate-400">Pilih Posisi</option>
                            <option value="ST" {{ old('position') == 'ST' ? 'selected' : '' }}>ST (Striker)</option>
                            <option value="AM" {{ old('position') == 'AM' ? 'selected' : '' }}>AM (Attacking Midfielder)</option>
                            <option value="RW" {{ old('position') == 'RW' ? 'selected' : '' }}>RW (Right Winger)</option>
                            <option value="LW" {{ old('position') == 'LW' ? 'selected' : '' }}>LW (Left Winger)</option>
                            <option value="CM" {{ old('position') == 'CM' ? 'selected' : '' }}>CM (Central Midfielder)</option>
                            <option value="DM" {{ old('position') == 'DM' ? 'selected' : '' }}>DM (Defensive Midfielder)</option>
                            <option value="RB" {{ old('position') == 'RB' ? 'selected' : '' }}>RB (Right Back)</option>
                            <option value="CB" {{ old('position') == 'CB' ? 'selected' : '' }}>CB (Centre-Back)</option>
                            <option value="LB" {{ old('position') == 'LB' ? 'selected' : '' }}>LB (Left Back)</option>
                            <option value="GK" {{ old('position') == 'GK' ? 'selected' : '' }}>GK (Goalkeeper)</option>
                        </select>
                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="jersey_number" class="block text-[13px] font-bold text-slate-700 mb-2">Nomor Punggung</label>
                    <input type="number" id="jersey_number" name="jersey_number" value="{{ old('jersey_number') }}" placeholder="Contoh: 10" required 
                           class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 placeholder-slate-400 focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] transition-all shadow-sm">
                </div>
            </div>

            <!-- Kolom Kanan -->
            <div class="space-y-6">
                <div>
                    <label for="club_id" class="block text-[13px] font-bold text-slate-700 mb-2">Tim / Klub Utama</label>
                    <div class="relative">
                        <select id="club_id" name="club_id" required 
                                class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 appearance-none focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] transition-all shadow-sm cursor-pointer">
                            <option value="" disabled selected class="text-slate-400">Pilih Klub Utama</option>
                            @foreach($clubs as $club)
                                <option value="{{ $club->id }}" {{ old('club_id') == $club->id ? 'selected' : '' }}>{{ $club->name }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="second_club" class="block text-[13px] font-bold text-slate-700 mb-2">Klub Kedua (Opsional)</label>
                    <div class="relative">
                        <select id="second_club" name="second_club" 
                                class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 appearance-none focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] transition-all shadow-sm cursor-pointer">
                            <option value="">Pilih Klub Kedua (Opsional)</option>
                            @foreach($clubs as $club)
                                <option value="{{ $club->name }}" {{ old('second_club') == $club->name ? 'selected' : '' }}>{{ $club->name }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="status" class="block text-[13px] font-bold text-slate-700 mb-2">Status</label>
                    <div class="relative">
                        <select id="status" name="status" required 
                                class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 appearance-none focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] transition-all shadow-sm cursor-pointer">
                            <option value="" disabled selected class="text-slate-400">Pilih Status</option>
                            <option value="Aktif" {{ old('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="Nonaktif" {{ old('status') == 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
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
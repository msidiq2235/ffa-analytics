@extends('layouts.app')
@section('title', 'Tambah Pemain Baru - FFA Analytics')
@section('header', 'Tambah Pemain Baru')
@section('subheader', 'Daftarkan pemain baru dan tentukan klubnya')
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
                <!-- Dropdown Tim Utama -->
                <div>
                    <label class="block text-[13px] font-bold text-slate-700 mb-2">Tim Utama</label>
                    <div class="relative" id="club-dropdown-container">
                        <input type="hidden" id="club_id" name="club_id" value="{{ old('club_id') }}" required>
                        <button type="button" onclick="toggleDropdown('club-options-box', event)" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 flex items-center justify-between focus:outline-none focus:border-[#4062D6] shadow-sm">
                            <span id="club-selected-text" class="text-slate-400">Pilih Tim Utama</span>
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        
                        <div id="club-options-box" class="hidden absolute top-full left-0 right-0 mt-2 bg-white border border-slate-200 rounded-xl shadow-lg py-2 z-20 max-h-48 overflow-y-auto">
                            @foreach($clubs as $club)
                                <div onclick="selectClub('{{ $club->id }}', '{{ $club->name }}')" class="px-4 py-2.5 hover:bg-blue-50 hover:text-[#4062D6] cursor-pointer text-[13px] text-slate-700 transition-colors">
                                    {{ $club->name }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Dropdown Tim Kedua -->
                <div>
                    <label class="block text-[13px] font-bold text-slate-700 mb-2">Tim Kedua</label>
                    <div class="relative" id="second-club-dropdown-container">
                        <input type="hidden" id="second_club" name="second_club" value="{{ old('second_club') }}">
                        <button type="button" onclick="toggleDropdown('second-club-options-box', event)" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 flex items-center justify-between focus:outline-none focus:border-[#4062D6] shadow-sm">
                            <span id="second-club-selected-text" class="text-slate-400">Pilih Tim Kedua (Opsional)</span>
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        
                        <div id="second-club-options-box" class="hidden absolute top-full left-0 right-0 mt-2 bg-white border border-slate-200 rounded-xl shadow-lg py-2 z-20 max-h-48 overflow-y-auto">
                            <div onclick="selectSecondClub('', 'Pilih Tim Kedua (Opsional)', true)" class="px-4 py-2.5 hover:bg-slate-50 cursor-pointer text-[13px] text-slate-400 transition-colors italic">
                                -- Tidak ada --
                            </div>
                            @foreach($clubs as $club)
                                <div onclick="selectSecondClub('{{ $club->name }}', '{{ $club->name }}', false)" class="px-4 py-2.5 hover:bg-blue-50 hover:text-[#4062D6] cursor-pointer text-[13px] text-slate-700 transition-colors">
                                    {{ $club->name }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Status Default Aktif -->
                <div>
                    <label class="block text-[13px] font-bold text-slate-700 mb-2">Status</label>
                    <input type="hidden" name="status" value="Aktif">
                    <div class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-[13px] text-slate-600 flex items-center gap-2 shadow-sm">
                        <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                        <span class="font-semibold">Aktif (Default)</span>
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

<script>
    function toggleDropdown(boxId, e) {
        e.stopPropagation();
        // Tutup dropdown lain terlebih dahulu
        document.querySelectorAll('[id$="-options-box"]').forEach(box => {
            if (box.id !== boxId) box.classList.add('hidden');
        });
        document.getElementById(boxId).classList.toggle('hidden');
    }

    function selectClub(id, name) {
        document.getElementById('club_id').value = id;
        const textSpan = document.getElementById('club-selected-text');
        textSpan.textContent = name;
        textSpan.classList.remove('text-slate-400');
        textSpan.classList.add('text-slate-700');
        document.getElementById('club-options-box').classList.add('hidden');
    }

    function selectSecondClub(val, name, isPlaceholder) {
        document.getElementById('second_club').value = val;
        const textSpan = document.getElementById('second-club-selected-text');
        textSpan.textContent = name;
        if (isPlaceholder) {
            textSpan.classList.add('text-slate-400');
            textSpan.classList.remove('text-slate-700');
        } else {
            textSpan.classList.remove('text-slate-400');
            textSpan.classList.add('text-slate-700');
        }
        document.getElementById('second-club-options-box').classList.add('hidden');
    }

    // Tutup dropdown jika area luar diklik
    document.addEventListener('click', function() {
        document.querySelectorAll('[id$="-options-box"]').forEach(box => {
            box.classList.add('hidden');
        });
    });
</script>

@endsection
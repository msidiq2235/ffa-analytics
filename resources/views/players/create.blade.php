@extends('layouts.app')
@section('title', 'Tambah Pemain Baru - FFA Analytics')
@section('header', 'Tambah Pemain Baru')
@section('subheader', 'Daftarkan pemain baru secara lengkap dan tentukan klubnya')
@section('content')

<style>
    [data-lucide] { flex-shrink: 0; }
</style>

<div class="bg-white rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] p-8">
    <form action="{{ route('players.store') }}" method="POST">
        @csrf
        
        <!-- BAGIAN 1: INFORMASI UTAMA & KLUB -->
        <div class="mb-8">
            <h3 class="text-[14px] font-bold text-slate-800 uppercase tracking-wider mb-4 flex items-center gap-2 border-b border-slate-100 pb-2">
                <i data-lucide="shield" class="w-4 h-4 text-[#4062D6]"></i> Informasi Tim & Posisi Utama
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="name" class="block text-[13px] font-bold text-slate-700 mb-2">Nama Lengkap Pemain *</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Contoh: Bintang Pamungkas" required
                           class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] shadow-sm">
                </div>

                <div>
                    <label for="popular_name" class="block text-[13px] font-bold text-slate-700 mb-2">Nama Panggilan / Punggung</label>
                    <input type="text" id="popular_name" name="popular_name" value="{{ old('popular_name') }}" placeholder="Contoh: Bintang"
                           class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] shadow-sm">
                </div>

                <!-- Dropdown Tim Utama -->
                <div>
                    <label class="block text-[13px] font-bold text-slate-700 mb-2">Tim Utama *</label>
                    <div class="relative" id="club-dropdown-container">
                        <input type="hidden" id="club_id" name="club_id" value="{{ old('club_id', $selectedClubId ?? '') }}" required>
                        <button type="button" onclick="toggleDropdown('club-options-box', event)" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 flex items-center justify-between focus:outline-none focus:border-[#4062D6] shadow-sm">
                            <span id="club-selected-text" class="{{ isset($selectedClubId) ? 'text-slate-700' : 'text-slate-400' }}">
                                @php
                                    $selectedName = 'Pilih Tim Utama';
                                    if(isset($selectedClubId)) {
                                        $foundClub = $clubs->firstWhere('id', $selectedClubId);
                                        if($foundClub) $selectedName = $foundClub->name;
                                    }
                                @endphp
                                {{ old('club_id') ? optional($clubs->firstWhere('id', old('club_id')))->name : $selectedName }}
                            </span>
                            <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400"></i>
                        </button>

                        <div id="club-options-box" class="hidden absolute top-full left-0 right-0 mt-2 bg-white border border-slate-200 rounded-xl shadow-lg py-2 z-20 max-h-48 overflow-y-auto">
                            @foreach($clubs as $club)
                                <div onclick="selectClub('{{ $club->id }}', @js($club->name))" class="px-4 py-2.5 hover:bg-blue-50 hover:text-[#4062D6] cursor-pointer text-[13px] text-slate-700 transition-colors">
                                    {{ $club->name }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div>
                    <label for="position" class="block text-[13px] font-bold text-slate-700 mb-2">Posisi Utama *</label>
                    <select id="position" name="position" required class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] shadow-sm cursor-pointer">
                        <option value="" disabled selected>Pilih Posisi</option>
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
                </div>

                <div>
                    <label for="jersey_number" class="block text-[13px] font-bold text-slate-700 mb-2">Nomor Punggung</label>
                    <input type="number" id="jersey_number" name="jersey_number" value="{{ old('jersey_number') }}" placeholder="Contoh: 10"
                           class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] shadow-sm">
                </div>

                <!-- Dropdown Tim Kedua -->
                <div>
                    <label class="block text-[13px] font-bold text-slate-700 mb-2">Tim Kedua (Opsional)</label>
                    <input type="text" name="second_club" value="{{ old('second_club') }}" placeholder="Contoh: Tim Cadangan / Sekolah"
                           class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] shadow-sm">
                </div>
            </div>
        </div>

        <!-- BAGIAN 2: BIODATA & IDENTITAS PRIBADI -->
        <div class="mb-8">
            <h3 class="text-[14px] font-bold text-slate-800 uppercase tracking-wider mb-4 flex items-center gap-2 border-b border-slate-100 pb-2">
                <i data-lucide="user" class="w-4 h-4 text-[#4062D6]"></i> Biodata & Identitas Pribadi
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="nik" class="block text-[13px] font-bold text-slate-700 mb-2">NIK</label>
                    <input type="text" id="nik" name="nik" value="{{ old('nik') }}" placeholder="Nomor Induk Kependudukan"
                           class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] shadow-sm">
                </div>

                <div>
                    <label for="birth_place_date" class="block text-[13px] font-bold text-slate-700 mb-2">Tempat, Tanggal Lahir</label>
                    <input type="text" id="birth_place_date" name="birth_place_date" value="{{ old('birth_place_date') }}" placeholder="Contoh: Yogyakarta, 12 Mei 2010"
                           class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] shadow-sm">
                </div>

                <div>
                    <label for="age_group" class="block text-[13px] font-bold text-slate-700 mb-2">Kelompok Umur (Tahun)</label>
                    <input type="number" id="age_group" name="age_group" value="{{ old('age_group') }}" placeholder="Contoh: 16"
                           class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] shadow-sm">
                </div>

                <div>
                    <label for="gender" class="block text-[13px] font-bold text-slate-700 mb-2">Jenis Kelamin</label>
                    <select id="gender" name="gender" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 focus:outline-none focus:border-[#4062D6] shadow-sm cursor-pointer">
                        <option value="Laki-laki" {{ old('gender') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ old('gender') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>

                <div>
                    <label for="country" class="block text-[13px] font-bold text-slate-700 mb-2">Kewarganegaraan</label>
                    <input type="text" id="country" name="country" value="{{ old('country', 'Indonesia') }}" placeholder="Indonesia"
                           class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 focus:outline-none focus:border-[#4062D6] shadow-sm">
                </div>

                <div>
                    <label for="dominant_foot" class="block text-[13px] font-bold text-slate-700 mb-2">Kaki Dominan</label>
                    <select id="dominant_foot" name="dominant_foot" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 focus:outline-none focus:border-[#4062D6] shadow-sm cursor-pointer">
                        <option value="Kanan" {{ old('dominant_foot') == 'Kanan' ? 'selected' : '' }}>Kanan</option>
                        <option value="Kiri" {{ old('dominant_foot') == 'Kiri' ? 'selected' : '' }}>Kiri</option>
                        <option value="Keduanya" {{ old('dominant_foot') == 'Keduanya' ? 'selected' : '' }}>Keduanya</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- BAGIAN 3: FISIK, KONTAK & DOMISILI -->
        <div class="mb-6">
            <h3 class="text-[14px] font-bold text-slate-800 uppercase tracking-wider mb-4 flex items-center gap-2 border-b border-slate-100 pb-2">
                <i data-lucide="activity" class="w-4 h-4 text-[#4062D6]"></i> Fisik, Kontak & Domisili
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div>
                    <label for="height_cm" class="block text-[13px] font-bold text-slate-700 mb-2">Tinggi Badan (cm)</label>
                    <input type="number" step="0.01" id="height_cm" name="height_cm" value="{{ old('height_cm') }}" placeholder="170"
                           class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 focus:outline-none focus:border-[#4062D6] shadow-sm">
                </div>

                <div>
                    <label for="weight_kg" class="block text-[13px] font-bold text-slate-700 mb-2">Berat Badan (kg)</label>
                    <input type="number" step="0.01" id="weight_kg" name="weight_kg" value="{{ old('weight_kg') }}" placeholder="60"
                           class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 focus:outline-none focus:border-[#4062D6] shadow-sm">
                </div>

                <div>
                    <label for="phone" class="block text-[13px] font-bold text-slate-700 mb-2">No. Telepon / WhatsApp</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone') }}" placeholder="0812xxxx"
                           class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 focus:outline-none focus:border-[#4062D6] shadow-sm">
                </div>

                <div>
                    <label for="email" class="block text-[13px] font-bold text-slate-700 mb-2">Alamat Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="pemain@email.com"
                           class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 focus:outline-none focus:border-[#4062D6] shadow-sm">
                </div>

                <div class="md:col-span-2">
                    <label for="address" class="block text-[13px] font-bold text-slate-700 mb-2">Alamat Domisili</label>
                    <input type="text" id="address" name="address" value="{{ old('address') }}" placeholder="Nama Jalan, RT/RW, Kalurahan, Kapanewon"
                           class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 focus:outline-none focus:border-[#4062D6] shadow-sm">
                </div>

                <div class="md:col-span-2">
                    <label for="province" class="block text-[13px] font-bold text-slate-700 mb-2">Provinsi / Kabupaten</label>
                    <input type="text" id="province" name="province" value="{{ old('province', 'DI Yogyakarta') }}" placeholder="DI Yogyakarta"
                           class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 focus:outline-none focus:border-[#4062D6] shadow-sm">
                </div>
            </div>
        </div>

        <!-- Tombol Aksi -->
        <div class="mt-10 pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
            <a href="{{ route('players.index') }}"
               class="px-6 py-2.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-[13px] font-bold rounded-xl transition-colors shadow-sm text-center inline-flex items-center justify-center gap-2">
                <i data-lucide="x" class="w-4 h-4"></i>
                Batal
            </a>
            <button type="submit"
                    class="px-6 py-2.5 bg-[#4062D6] hover:bg-[#3251b5] text-white text-[13px] font-bold rounded-xl transition shadow-sm active:scale-95 inline-flex items-center justify-center gap-2">
                <i data-lucide="user-plus" class="w-4 h-4"></i>
                Simpan Data Lengkap Pemain
            </button>
        </div>
    </form>
</div>

<script src="https://unpkg.com/lucide@latest"></script>
<script>
    function renderIcons() {
        if (window.lucide) lucide.createIcons({ attrs: { 'stroke-width': 2 } });
    }
    document.addEventListener('DOMContentLoaded', renderIcons);

    function toggleDropdown(boxId, e) {
        e.stopPropagation();
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

    document.addEventListener('click', function() {
        document.querySelectorAll('[id$="-options-box"]').forEach(box => {
            box.classList.add('hidden');
        });
    });
</script>

@endsection
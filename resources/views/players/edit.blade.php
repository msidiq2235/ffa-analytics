@extends('layouts.app')
@section('title', 'Edit Data Pemain - FFA Analytics')
@section('header', 'Edit Data Pemain')
@section('subheader', 'Perbarui informasi profil, posisi, dan biodata lengkap pemain di sini.')
@section('content')

<style>
    [data-lucide] { flex-shrink: 0; }
</style>

<div class="bg-white rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] p-8">
    <form action="{{ route('players.update', $player->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- BAGIAN 1: INFORMASI UTAMA & KLUB -->
        <div class="mb-8">
            <h3 class="text-[14px] font-bold text-slate-800 uppercase tracking-wider mb-4 flex items-center gap-2 border-b border-slate-100 pb-2">
                <i data-lucide="shield" class="w-4 h-4 text-[#4062D6]"></i> Informasi Tim & Posisi Utama
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="name" class="block text-[13px] font-bold text-slate-700 mb-2">Nama Lengkap Pemain *</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $player->name) }}" required
                           class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] shadow-sm">
                </div>

                <div>
                    <label for="popular_name" class="block text-[13px] font-bold text-slate-700 mb-2">Nama Panggilan / Punggung</label>
                    <input type="text" id="popular_name" name="popular_name" value="{{ old('popular_name', $player->popular_name) }}" placeholder="Contoh: Bintang"
                           class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] shadow-sm">
                </div>

                <!-- Dropdown Tim Utama -->
                <div>
                    <label for="club_id" class="block text-[13px] font-bold text-slate-700 mb-2">Tim Utama *</label>
                    <select id="club_id" name="club_id" required class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 focus:outline-none focus:border-[#4062D6] shadow-sm cursor-pointer">
                        <option value="" disabled>Pilih Klub Utama</option>
                        @foreach($clubs ?? [] as $club)
                            <option value="{{ $club->id }}" {{ (old('club_id', $player->club_id) == $club->id) ? 'selected' : '' }}>
                                {{ $club->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="position" class="block text-[13px] font-bold text-slate-700 mb-2">Posisi Utama *</label>
                    <select id="position" name="position" required class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 focus:outline-none focus:border-[#4062D6] shadow-sm cursor-pointer">
                        <option value="" disabled>Pilih Posisi</option>
                        <option value="ST" {{ (old('position', $player->position) == 'ST') ? 'selected' : '' }}>ST (Striker)</option>
                        <option value="AM" {{ (old('position', $player->position) == 'AM') ? 'selected' : '' }}>AM (Attacking Midfielder)</option>
                        <option value="RW" {{ (old('position', $player->position) == 'RW') ? 'selected' : '' }}>RW (Right Winger)</option>
                        <option value="LW" {{ (old('position', $player->position) == 'LW') ? 'selected' : '' }}>LW (Left Winger)</option>
                        <option value="CM" {{ (old('position', $player->position) == 'CM') ? 'selected' : '' }}>CM (Central Midfielder)</option>
                        <option value="DM" {{ (old('position', $player->position) == 'DM') ? 'selected' : '' }}>DM (Defensive Midfielder)</option>
                        <option value="RB" {{ (old('position', $player->position) == 'RB') ? 'selected' : '' }}>RB (Right Back)</option>
                        <option value="CB" {{ (old('position', $player->position) == 'CB') ? 'selected' : '' }}>CB (Centre-Back)</option>
                        <option value="LB" {{ (old('position', $player->position) == 'LB') ? 'selected' : '' }}>LB (Left Back)</option>
                        <option value="GK" {{ (old('position', $player->position) == 'GK') ? 'selected' : '' }}>GK (Goalkeeper)</option>
                    </select>
                </div>

                <div>
                    <label for="jersey_number" class="block text-[13px] font-bold text-slate-700 mb-2">Nomor Punggung</label>
                    <input type="number" id="jersey_number" name="jersey_number" value="{{ old('jersey_number', $player->jersey_number) }}" placeholder="Contoh: 10"
                           class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 focus:outline-none focus:border-[#4062D6] shadow-sm">
                </div>

                <div>
                    <label for="second_club" class="block text-[13px] font-bold text-slate-700 mb-2">Tim Kedua (Opsional)</label>
                    <input type="text" id="second_club" name="second_club" value="{{ old('second_club', $player->second_club) }}" placeholder="Contoh: Tim Cadangan / Sekolah"
                           class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 focus:outline-none focus:border-[#4062D6] shadow-sm">
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
                    <input type="text" id="nik" name="nik" value="{{ old('nik', $player->nik) }}" placeholder="Nomor Induk Kependudukan"
                           class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 focus:outline-none focus:border-[#4062D6] shadow-sm">
                </div>

                <div>
                    <label for="birth_place_date" class="block text-[13px] font-bold text-slate-700 mb-2">Tempat, Tanggal Lahir</label>
                    <input type="text" id="birth_place_date" name="birth_place_date" value="{{ old('birth_place_date', $player->birth_place_date) }}" placeholder="Contoh: Yogyakarta, 12 Mei 2010"
                           class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 focus:outline-none focus:border-[#4062D6] shadow-sm">
                </div>

                <div>
                    <label for="age_group" class="block text-[13px] font-bold text-slate-700 mb-2">Kelompok Umur (Tahun)</label>
                    <input type="number" id="age_group" name="age_group" value="{{ old('age_group', $player->age_group) }}" placeholder="Contoh: 16"
                           class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 focus:outline-none focus:border-[#4062D6] shadow-sm">
                </div>

                <div>
                    <label for="gender" class="block text-[13px] font-bold text-slate-700 mb-2">Jenis Kelamin</label>
                    <select id="gender" name="gender" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 focus:outline-none focus:border-[#4062D6] shadow-sm cursor-pointer">
                        <option value="Laki-laki" {{ (old('gender', $player->gender) == 'Laki-laki') ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ (old('gender', $player->gender) == 'Perempuan') ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>

                <div>
                    <label for="country" class="block text-[13px] font-bold text-slate-700 mb-2">Kewarganegaraan</label>
                    <input type="text" id="country" name="country" value="{{ old('country', $player->country ?? 'Indonesia') }}" placeholder="Indonesia"
                           class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 focus:outline-none focus:border-[#4062D6] shadow-sm">
                </div>

                <div>
                    <label for="dominant_foot" class="block text-[13px] font-bold text-slate-700 mb-2">Kaki Dominan</label>
                    <select id="dominant_foot" name="dominant_foot" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 focus:outline-none focus:border-[#4062D6] shadow-sm cursor-pointer">
                        <option value="Kanan" {{ (old('dominant_foot', $player->dominant_foot) == 'Kanan') ? 'selected' : '' }}>Kanan</option>
                        <option value="Kiri" {{ (old('dominant_foot', $player->dominant_foot) == 'Kiri') ? 'selected' : '' }}>Kiri</option>
                        <option value="Keduanya" {{ (old('dominant_foot', $player->dominant_foot) == 'Keduanya') ? 'selected' : '' }}>Keduanya</option>
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
                    <input type="number" step="0.01" id="height_cm" name="height_cm" value="{{ old('height_cm', $player->height_cm) }}" placeholder="170"
                           class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 focus:outline-none focus:border-[#4062D6] shadow-sm">
                </div>

                <div>
                    <label for="weight_kg" class="block text-[13px] font-bold text-slate-700 mb-2">Berat Badan (kg)</label>
                    <input type="number" step="0.01" id="weight_kg" name="weight_kg" value="{{ old('weight_kg', $player->weight_kg) }}" placeholder="60"
                           class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 focus:outline-none focus:border-[#4062D6] shadow-sm">
                </div>

                <div>
                    <label for="phone" class="block text-[13px] font-bold text-slate-700 mb-2">No. Telepon / WhatsApp</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone', $player->phone) }}" placeholder="0812xxxx"
                           class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 focus:outline-none focus:border-[#4062D6] shadow-sm">
                </div>

                <div>
                    <label for="email" class="block text-[13px] font-bold text-slate-700 mb-2">Alamat Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $player->email) }}" placeholder="pemain@email.com"
                           class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 focus:outline-none focus:border-[#4062D6] shadow-sm">
                </div>

                <div class="md:col-span-2">
                    <label for="address" class="block text-[13px] font-bold text-slate-700 mb-2">Alamat Domisili</label>
                    <input type="text" id="address" name="address" value="{{ old('address', $player->address) }}" placeholder="Nama Jalan, RT/RW, Kalurahan, Kapanewon"
                           class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 focus:outline-none focus:border-[#4062D6] shadow-sm">
                </div>

                <div class="md:col-span-2">
                    <label for="province" class="block text-[13px] font-bold text-slate-700 mb-2">Provinsi / Kabupaten</label>
                    <input type="text" id="province" name="province" value="{{ old('province', $player->province ?? 'DI Yogyakarta') }}" placeholder="DI Yogyakarta"
                           class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 focus:outline-none focus:border-[#4062D6] shadow-sm">
                </div>

                <div class="md:col-span-4">
                    <label for="status" class="block text-[13px] font-bold text-slate-700 mb-2">Status Pemain *</label>
                    <select id="status" name="status" required class="w-full md:w-1/3 px-4 py-3 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 focus:outline-none focus:border-[#4062D6] shadow-sm cursor-pointer">
                        <option value="Aktif" {{ (old('status', $player->status) == 'Aktif') ? 'selected' : '' }}>Aktif</option>
                        <option value="Nonaktif" {{ (old('status', $player->status) == 'Nonaktif') ? 'selected' : '' }}>Nonaktif</option>
                        <option value="Cedera" {{ (old('status', $player->status) == 'Cedera') ? 'selected' : '' }}>Cedera</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Tombol Aksi -->
        <div class="mt-12 pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
            <a href="{{ route('players.index') }}"
               class="px-6 py-2.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-[13px] font-bold rounded-xl transition-colors shadow-sm text-center inline-flex items-center justify-center gap-2">
                <i data-lucide="x" class="w-4 h-4"></i>
                Batal
            </a>
            <button type="submit"
                    class="px-6 py-2.5 bg-[#4062D6] hover:bg-[#3251b5] text-white text-[13px] font-bold rounded-xl transition shadow-sm active:scale-95 inline-flex items-center justify-center gap-2">
                <i data-lucide="save" class="w-4 h-4"></i>
                Update Data Lengkap
            </button>
        </div>
    </form>
</div>

<!-- Lucide Icons -->
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (window.lucide) lucide.createIcons({ attrs: { 'stroke-width': 2 } });
    });
</script>

@endsection
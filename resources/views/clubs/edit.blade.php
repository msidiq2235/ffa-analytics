@extends('layouts.app')
@section('title', 'Edit Tim - FFA Analytics')
@section('header', 'Edit Tim')
@section('subheader', 'Perbarui informasi profil tim di sini. Pastikan data yang dimasukkan akurat dan sesuai dengan profil tim.')
@section('content')

<!-- Tambahkan CDN Lucide Icons jika belum ada di layouts/app.blade.php -->
<script src="https://unpkg.com/lucide@latest"></script>

<div class="bg-white rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] p-8">
    <form action="{{ route('clubs.update', $club->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @if ($errors->any())
            <div class="mb-6 p-4 bg-rose-50 border border-rose-100 rounded-xl text-[12px] text-rose-600">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <div class="space-y-6">
            <!-- Input Nama Klub -->
            <div>
                <label for="name" class="block text-[13px] font-bold text-slate-700 mb-2 flex items-center gap-2">
                    <i data-lucide="shield" class="w-4 h-4 text-[#4062D6]"></i>
                    <span>Nama Klub / Akademi</span>
                </label>
                <input type="text" id="name" name="name" value="{{ old('name', $club->name) }}" required 
                       class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 placeholder-slate-400 focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] transition-all shadow-sm">
            </div>

            <div>
                <label for="coach_id" class="block text-[13px] font-bold text-slate-700 mb-2 flex items-center gap-2">
                    <i data-lucide="clipboard-list" class="w-4 h-4 text-[#4062D6]"></i>
                    <span>Penanggung Jawab (Pelatih)</span>
                </label>
                <select id="coach_id" name="coach_id" required
                        class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] transition-all shadow-sm">
                    <option value="" disabled {{ old('coach_id', $club->coach_id) ? '' : 'selected' }}>Pilih pelatih</option>
                    @foreach($coaches as $coach)
                        <option value="{{ $coach->id }}" {{ old('coach_id', $club->coach_id) == $coach->id ? 'selected' : '' }}>
                            {{ $coach->name }} ({{ $coach->email }})
                        </option>
                    @endforeach
                </select>
                @if($coaches->isEmpty())
                    <p class="text-[11px] text-amber-600 mt-2">Belum ada user dengan role Pelatih. Tambahkan akun Pelatih terlebih dahulu.</p>
                @endif
            </div>
            
            <!-- Input Logo Klub -->
            <div>
                <label for="logo_path" class="block text-[13px] font-bold text-slate-700 mb-2 flex items-center gap-2">
                    <i data-lucide="image" class="w-4 h-4 text-[#4062D6]"></i>
                    <span>Logo Klub (Opsional)</span>
                </label>
                <input type="file" id="logo_path" name="logo_path" accept="image/*"
                       class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-[11px] file:font-bold file:bg-blue-50 file:text-[#4062D6] hover:file:bg-blue-100 transition-all shadow-sm cursor-pointer">
                
                <!-- Helper text untuk menampilkan nama file yang sedang tersimpan -->
                @if($club->logo_path)
                    <p class="text-[11px] text-slate-500 mt-2 font-medium flex items-center gap-1.5">
                        <i data-lucide="info" class="w-3.5 h-3.5 text-[#4062D6] shrink-0"></i>
                        <span>Logo saat ini: <span class="text-[#4062D6]">{{ basename($club->logo_path) }}</span> (Biarkan kosong jika tidak ingin mengubah logo)</span>
                    </p>
                @endif
            </div>
        </div>

        <!-- Tombol Aksi -->
        <div class="mt-12 pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
            <a href="{{ route('clubs.index') }}" 
               class="px-6 py-2.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-[13px] font-bold rounded-xl transition-colors shadow-sm text-center flex items-center gap-2">
                <i data-lucide="x" class="w-4 h-4"></i>
                <span>Batal</span>
            </a>
            <button type="submit" 
                    class="px-6 py-2.5 bg-[#4062D6] hover:bg-[#3251b5] text-white text-[13px] font-bold rounded-xl transition-colors shadow-sm active:scale-95 flex items-center gap-2">
                <i data-lucide="check" class="w-4 h-4"></i>
                <span>Simpan Tim</span>
            </button>
        </div>
    </form>
</div>

<!-- Script inisialisasi Lucide Icons -->
<script>
    lucide.createIcons();
</script>

@endsection
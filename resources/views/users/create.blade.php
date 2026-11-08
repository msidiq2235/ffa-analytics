@extends('layouts.app')
@section('title', 'Tambah User - FFA Analytics')
@section('header', 'Tambah User')
@section('subheader', 'Daftarkan akun pengguna baru ke dalam sistem')
@section('content')

<style>
    [data-lucide] { flex-shrink: 0; }
</style>

<div class="bg-white rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] p-8">
    @if ($errors->any())
        <div class="mb-6 p-4 bg-rose-50 border border-rose-100 rounded-xl text-[12px] text-rose-600 flex gap-2">
            <i data-lucide="triangle-alert" class="w-4 h-4 mt-0.5"></i>
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('users.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6">
            <!-- Kolom Kiri -->
            <div class="space-y-6">
                <div>
                    <label for="name" class="flex items-center gap-2 text-[13px] font-bold text-slate-700 mb-2">
                        <i data-lucide="user" class="w-4 h-4 text-slate-400"></i>
                        Nama Lengkap
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Nama Lengkap" required
                           class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 placeholder-slate-400 focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] transition-all shadow-sm">
                </div>

                <div>
                    <label for="email" class="flex items-center gap-2 text-[13px] font-bold text-slate-700 mb-2">
                        <i data-lucide="mail" class="w-4 h-4 text-slate-400"></i>
                        Alamat Email
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" required
                           class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 placeholder-slate-400 focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] transition-all shadow-sm">
                </div>

                <div>
                    <label for="password" class="flex items-center gap-2 text-[13px] font-bold text-slate-700 mb-2">
                        <i data-lucide="lock" class="w-4 h-4 text-slate-400"></i>
                        Password
                    </label>
                    <div class="relative">
                        <input type="password" id="password" name="password" placeholder="Minimal 8 karakter" minlength="8" required
                               class="w-full pl-4 pr-12 py-3 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 placeholder-slate-400 focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] transition-all shadow-sm">
                        <button type="button" onclick="togglePassword()" aria-label="Tampilkan atau sembunyikan password"
                                class="absolute inset-y-0 right-3 flex items-center text-slate-400 hover:text-slate-600 transition-colors">
                            <i data-lucide="eye" class="w-4 h-4" id="password-eye"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan -->
            <div class="space-y-6">
                <div>
                    <label for="role" class="flex items-center gap-2 text-[13px] font-bold text-slate-700 mb-2">
                        <i data-lucide="shield-check" class="w-4 h-4 text-slate-400"></i>
                        Role / Hak Akses
                    </label>
                    <div class="relative">
                        <select id="role" name="role" required
                                class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 appearance-none focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] transition-all shadow-sm cursor-pointer">
                            <option value="" disabled {{ old('role') ? '' : 'selected' }}>Pilih Role</option>
                            @foreach(['Super Admin', 'Pelatih', 'Analis', 'Pemain'] as $r)
                                <option value="{{ $r }}" {{ old('role') == $r ? 'selected' : '' }}>{{ $r }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="chevron-down" class="w-4 h-4"></i>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="flex items-center gap-2 text-[13px] font-bold text-slate-700 mb-2">
                        <i data-lucide="activity" class="w-4 h-4 text-slate-400"></i>
                        Status Akun
                    </label>
                    <div class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-[13px] text-slate-700 shadow-sm flex items-center justify-between">
                        <span class="flex items-center gap-2 font-bold text-emerald-600">
                            <i data-lucide="circle-check" class="w-4 h-4"></i> Aktif
                        </span>
                        <span class="text-[11px] text-slate-400">Diatur otomatis</span>
                    </div>
                    <p class="text-[11px] text-slate-400 mt-1.5">Akun baru otomatis berstatus Aktif. Status dapat diubah nanti lewat menu Edit.</p>
                </div>
            </div>
        </div>

        <!-- Tombol Aksi -->
        <div class="mt-12 pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
            <a href="{{ route('users.index') }}"
               class="px-6 py-2.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-[13px] font-bold rounded-xl transition-colors shadow-sm text-center inline-flex items-center justify-center gap-2">
                <i data-lucide="x" class="w-4 h-4"></i>
                Batal
            </a>
            <button type="submit"
                    class="px-6 py-2.5 bg-[#4062D6] hover:bg-[#3251b5] text-white text-[13px] font-bold rounded-xl transition shadow-sm active:scale-95 inline-flex items-center justify-center gap-2">
                <i data-lucide="user-plus" class="w-4 h-4"></i>
                Tambah User
            </button>
        </div>
    </form>
</div>

<!-- Lucide Icons (hapus baris ini jika layouts.app sudah memuat Lucide) -->
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    function renderIcons() {
        if (window.lucide) lucide.createIcons({ attrs: { 'stroke-width': 2 } });
    }
    document.addEventListener('DOMContentLoaded', renderIcons);

    // Tampilkan / sembunyikan password
    function togglePassword() {
        const input = document.getElementById('password');
        const btn = document.getElementById('password-eye').parentElement;
        const show = input.type === 'password';
        input.type = show ? 'text' : 'password';
        btn.innerHTML = '<i data-lucide="' + (show ? 'eye-off' : 'eye') + '" class="w-4 h-4" id="password-eye"></i>';
        renderIcons();
    }
</script>

@endsection
@extends('layouts.app')
@section('title', 'Tambah User - FFA Analytics')
@section('header', 'Tambah User')
@section('subheader', 'Daftarkan akun pengguna baru ke dalam sistem')
@section('content')

<div class="bg-white rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] p-8">
    @if ($errors->any())
        <div class="mb-6 p-4 bg-rose-50 border border-rose-100 rounded-xl text-[12px] text-rose-600">
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
                    <label for="name" class="block text-[13px] font-bold text-slate-700 mb-2">Nama Lengkap</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Nama Lengkap" required
                           class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 placeholder-slate-400 focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] transition-all shadow-sm">
                </div>

                <div>
                    <label for="email" class="block text-[13px] font-bold text-slate-700 mb-2">Alamat Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" required
                           class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 placeholder-slate-400 focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] transition-all shadow-sm">
                </div>

                <div>
                    <label for="password" class="block text-[13px] font-bold text-slate-700 mb-2">Password</label>
                    <input type="password" id="password" name="password" placeholder="Minimal 8 karakter" minlength="8" required
                           class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 placeholder-slate-400 focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] transition-all shadow-sm">
                </div>
            </div>

            <!-- Kolom Kanan -->
            <div class="space-y-6">
                <div>
                    <label for="role" class="block text-[13px] font-bold text-slate-700 mb-2">Role / Hak Akses</label>
                    <div class="relative">
                        <select id="role" name="role" required
                                class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 appearance-none focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] transition-all shadow-sm cursor-pointer">
                            <option value="" disabled {{ old('role') ? '' : 'selected' }}>Pilih Role</option>
                            @foreach(['Super Admin', 'Pelatih', 'Analis', 'Pemain'] as $r)
                                <option value="{{ $r }}" {{ old('role') == $r ? 'selected' : '' }}>{{ $r }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-[13px] font-bold text-slate-700 mb-2">Status Akun</label>
                    <div class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-[13px] text-slate-700 shadow-sm flex items-center justify-between">
                        <span class="flex items-center gap-2 font-bold text-emerald-600">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aktif
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
               class="px-6 py-2.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-[13px] font-bold rounded-xl transition-colors shadow-sm text-center">
                Batal
            </a>
            <button type="submit"
                    class="px-6 py-2.5 bg-[#4062D6] hover:bg-[#3251b5] text-white text-[13px] font-bold rounded-xl transition-colors shadow-sm active:scale-95">
                Tambah User
            </button>
        </div>
    </form>
</div>

@endsection
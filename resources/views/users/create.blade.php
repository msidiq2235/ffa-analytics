@extends('layouts.app')
@section('title', 'Tambah Pengguna - FFA Analytics')
@section('header', 'Tambah Pengguna Baru')
@section('subheader', 'Daftarkan pengguna baru dan atur hak aksesnya ke dalam sistem')
@section('content')

<div class="bg-white rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] p-8">
    <form action="{{ route('users.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6">
            <!-- Kolom Kiri -->
            <div class="space-y-6">
                <div>
                    <label for="name" class="block text-[13px] font-bold text-slate-700 mb-2">Nama Lengkap</label>
                    <input type="text" id="name" name="name" placeholder="Contoh: Budi Santoso" required 
                           class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 placeholder-slate-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all shadow-sm">
                </div>
                
                <div>
                    <label for="email" class="block text-[13px] font-bold text-slate-700 mb-2">Alamat Email</label>
                    <input type="email" id="email" name="email" placeholder="nama@email.com" required 
                           class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 placeholder-slate-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all shadow-sm">
                </div>

                <div>
                    <label for="password" class="block text-[13px] font-bold text-slate-700 mb-2">Password Awal</label>
                    <input type="password" id="password" name="password" placeholder="Password minimal 8 karakter" required minlength="8" 
                           class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 placeholder-slate-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all shadow-sm">
                </div>
            </div>

            <!-- Kolom Kanan -->
            <div class="space-y-6">
                <div>
                    <label for="role" class="block text-[13px] font-bold text-slate-700 mb-2">Role / Hak Akses</label>
                    <div class="relative">
                        <select id="role" name="role" required 
                                class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 appearance-none focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all shadow-sm cursor-pointer">
                            <option value="" disabled selected class="text-slate-400">Role</option>
                            <option value="Admin">Admin</option>
                            <option value="Pelatih">Pelatih</option>
                            <option value="Analis">Analis</option>
                            <option value="Pemain">Pemain</option>
                        </select>
                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="status" class="block text-[13px] font-bold text-slate-700 mb-2">Status Akun</label>
                    <div class="relative">
                        <select id="status" name="status" required 
                                class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 appearance-none focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all shadow-sm cursor-pointer">
                            <option value="" disabled selected class="text-slate-400">Status Akun</option>
                            <option value="Aktif">Aktif</option>
                            <option value="Nonaktif">Nonaktif</option>
                        </select>
                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
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
                Tambah Pengguna
            </button>
        </div>
    </form>
</div>

@endsection
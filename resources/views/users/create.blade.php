@extends('layouts.app')

@section('title', 'Tambah Pengguna - FFA Analytics')
@section('header', 'Tambah Pengguna Baru')
@section('subheader', 'Daftarkan pengguna baru dan atur hak aksesnya ke dalam sistem')

@section('header_action')
<a href="{{ route('users.index') }}" class="bg-[#263578] hover:bg-[#183262] text-white px-6 py-2.5 rounded-lg text-[13px] font-semibold transition-colors shadow-sm">
    Kembali
</a>
@endsection

@section('content')
<div class="bg-white rounded-[20px] border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] p-8 md:p-10">
    <form action="{{ route('users.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-10 gap-y-8">
            
            <div>
                <label for="name" class="block text-[13px] font-bold text-[#334155] mb-2">Nama Lengkap</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required 
                    class="w-full px-4 py-3 bg-white border border-[#e2e8f0] rounded-xl text-[13px] text-[#334155] placeholder-slate-400 focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] transition-shadow shadow-sm"
                    placeholder="Contoh: Budi Santoso">
                @error('name')<p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="role" class="block text-[13px] font-bold text-[#334155] mb-2">Role / Hak Akses</label>
                <div class="relative">
                    <select id="role" name="role" required class="w-full px-4 py-3 bg-white border border-[#e2e8f0] rounded-xl text-[13px] text-[#334155] appearance-none focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] transition-shadow shadow-sm cursor-pointer">
                        <option value="" disabled {{ old('role') ? '' : 'selected' }}>Pilih Role</option>
                        <option value="Super Admin" {{ old('role') == 'Super Admin' ? 'selected' : '' }}>Super Admin</option>
                        <option value="Pelatih" {{ old('role') == 'Pelatih' ? 'selected' : '' }}>Pelatih</option>
                        <option value="Analis" {{ old('role') == 'Analis' ? 'selected' : '' }}>Analis</option>
                        <option value="Pemain" {{ old('role') == 'Pemain' ? 'selected' : '' }}>Pemain</option>
                    </select>
                    <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>
                @error('role')<p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="email" class="block text-[13px] font-bold text-[#334155] mb-2">Alamat Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required 
                    class="w-full px-4 py-3 bg-white border border-[#e2e8f0] rounded-xl text-[13px] text-[#334155] placeholder-slate-400 focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] transition-shadow shadow-sm"
                    placeholder="nama@email.com">
                @error('email')<p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="status" class="block text-[13px] font-bold text-[#334155] mb-2">Status Akun</label>
                <div class="relative">
                    <select id="status" name="status" required class="w-full px-4 py-3 bg-white border border-[#e2e8f0] rounded-xl text-[13px] text-[#334155] appearance-none focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] transition-shadow shadow-sm cursor-pointer">
                        <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                    <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>
                @error('status')<p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="password" class="block text-[13px] font-bold text-[#334155] mb-2">Password Awal</label>
                <input type="password" id="password" name="password" required 
                    class="w-full px-4 py-3 bg-white border border-[#e2e8f0] rounded-xl text-[13px] text-[#334155] placeholder-slate-400 focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] transition-shadow shadow-sm"
                    placeholder="Password minimal 8 karakter">
                @error('password')<p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>@enderror
            </div>

        </div>

        <div class="mt-12 pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
            <a href="{{ route('users.index') }}" class="px-6 py-3 bg-white border border-slate-200 text-slate-600 text-[13px] font-bold rounded-xl hover:bg-slate-50 transition-colors">
                Batal
            </a>
            <button type="submit" class="px-6 py-3 bg-[#4062D6] hover:bg-[#3251b5] text-white text-[13px] font-bold rounded-xl transition-colors shadow-sm active:scale-95">
                Tambah Pengguna
            </button>
        </div>

    </form>
</div>
@endsection
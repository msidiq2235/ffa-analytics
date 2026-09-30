@extends('layouts.app')
@section('title', 'Kelola Akun - FFA Analytics')
@section('header', 'Kelola Akun Saya')
@section('subheader', 'Informasi profil dan pengaturan keamanan akun Anda')
@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
<div class="lg:col-span-1 bg-white rounded-[20px] border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] p-10 flex flex-col items-center justify-center min-h-[450px]">
<div class="w-28 h-28 rounded-full bg-[#e2e8f0] flex items-center justify-center text-[#334155] font-bold text-4xl mb-6 uppercase">
{{ substr(auth()->user()->name, 0, 1) }}
</div>
<h3 class="font-bold text-[#1e293b] text-xl mb-1">{{ auth()->user()->name }}</h3>
<p class="text-slate-500 text-[13px] mb-8">{{ auth()->user()->email }}</p>
<span class="px-6 py-1.5 text-[11px] font-bold rounded-full bg-blue-50 text-[#4062D6] border border-blue-100">{{ auth()->user()->role }}</span>
</div>
<div class="lg:col-span-2 bg-white rounded-[20px] border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] p-8 md:p-10 min-h-[450px]">
<h3 class="font-bold text-[#1e293b] text-[15px] mb-8">Pengaturan Password</h3>
@if(session('success'))
<div class="bg-emerald-50 border border-emerald-200 text-emerald-700 p-4 rounded-xl mb-6 text-[13px] font-medium">
{{ session('success') }}
</div>
@endif
<form action="{{ route('password.update') }}" method="POST">
@csrf
<div class="space-y-6">
<div>
<label for="current_password" class="block text-[13px] font-bold text-[#334155] mb-2">Password Saat Ini</label>
<div class="relative">
<input type="password" id="current_password" name="current_password" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-[13px] text-[#334155] focus:outline-none focus:border-[#4062D6] focus:bg-white transition-colors pr-10">
<div class="absolute inset-y-0 right-4 flex items-center cursor-pointer text-slate-400 hover:text-slate-600" onclick="togglePassword('current_password')">
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
</div>
</div>
@error('current_password')
<p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
@enderror
</div>
<div>
<label for="new_password" class="block text-[13px] font-bold text-[#334155] mb-2">Password Baru</label>
<div class="relative">
<input type="password" id="new_password" name="new_password" required minlength="8" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-[13px] text-[#334155] focus:outline-none focus:border-[#4062D6] focus:bg-white transition-colors pr-10">
<div class="absolute inset-y-0 right-4 flex items-center cursor-pointer text-slate-400 hover:text-slate-600" onclick="togglePassword('new_password')">
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
</div>
</div>
@error('new_password')
<p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
@enderror
</div>
<div>
<label for="new_password_confirmation" class="block text-[13px] font-bold text-[#334155] mb-2">Konfirmasi Password Baru</label>
<div class="relative">
<input type="password" id="new_password_confirmation" name="new_password_confirmation" required minlength="8" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-[13px] text-[#334155] focus:outline-none focus:border-[#4062D6] focus:bg-white transition-colors pr-10">
<div class="absolute inset-y-0 right-4 flex items-center cursor-pointer text-slate-400 hover:text-slate-600" onclick="togglePassword('new_password_confirmation')">
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
</div>
</div>
</div>
</div>
<div class="mt-8 flex items-center justify-end">
<button type="submit" class="px-6 py-3 bg-[#4062D6] hover:bg-[#3251b5] text-white text-[13px] font-bold rounded-xl transition-colors shadow-sm active:scale-95">
Perbarui Password
</button>
</div>
</form>
</div>
</div>
<script>
function togglePassword(inputId) {
const input = document.getElementById(inputId);
if (input.type === 'password') {
input.type = 'text';
} else {
input.type = 'password';
}
}
</script>
@endsection
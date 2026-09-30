@extends('layouts.app')
@section('title', 'Kelola User - FFA Analytics')
@section('header', 'Kelola User')
@section('subheader', 'Manajemen akun Football')
@section('header_action')
<a href="{{ route('users.create') }}" class="bg-[#2d5096] hover:bg-[#183262] text-white px-5 py-2.5 rounded-xl text-[13px] font-semibold transition-colors shadow-sm flex items-center gap-2">
<span>+</span> Tambah User
</a>
@endsection
@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
<div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] flex items-center justify-between transition-transform hover:-translate-y-1 duration-300">
<div class="flex flex-col">
<h3 class="text-[28px] font-bold text-[#1e293b] leading-none mb-2">{{ $totalAktif ?? 0 }}</h3>
<p class="text-[10px] font-bold text-[#94a3b8] uppercase tracking-widest">Akun Aktif</p>
</div>
<div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-500 border border-emerald-100 flex items-center justify-center shrink-0">
<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
</div>
</div>
<div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] flex items-center justify-between transition-transform hover:-translate-y-1 duration-300">
<div class="flex flex-col">
<h3 class="text-[28px] font-bold text-[#1e293b] leading-none mb-2">{{ $totalNonaktif ?? 0 }}</h3>
<p class="text-[10px] font-bold text-[#94a3b8] uppercase tracking-widest">Akun Nonaktif</p>
</div>
<div class="w-12 h-12 rounded-full bg-rose-50 text-rose-500 border border-rose-100 flex items-center justify-center shrink-0">
<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
</div>
</div>
<div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] flex items-center justify-between transition-transform hover:-translate-y-1 duration-300">
<div class="flex flex-col">
<h3 class="text-[28px] font-bold text-[#1e293b] leading-none mb-2">{{ $totalOnline ?? 0 }}</h3>
<p class="text-[10px] font-bold text-[#94a3b8] uppercase tracking-widest">Online Sekarang</p>
</div>
<div class="w-12 h-12 rounded-full bg-blue-50 text-blue-500 border border-blue-100 flex items-center justify-center shrink-0">
<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
</div>
</div>
</div>
<div class="bg-white rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] overflow-hidden">
<div class="overflow-x-auto">
<table class="w-full text-left border-collapse">
<thead>
<tr class="border-b border-slate-100 bg-white">
<th class="py-4 px-6 text-[10px] font-bold text-[#94a3b8] uppercase tracking-widest w-[30%]">Nama Pengguna</th>
<th class="py-4 px-4 text-[10px] font-bold text-[#94a3b8] uppercase tracking-widest">Email</th>
<th class="py-4 px-4 text-[10px] font-bold text-[#94a3b8] uppercase tracking-widest text-center">Role</th>
<th class="py-4 px-4 text-[10px] font-bold text-[#94a3b8] uppercase tracking-widest">Status</th>
<th class="py-4 px-6 text-[10px] font-bold text-[#94a3b8] uppercase tracking-widest text-right">Aksi</th>
</tr>
</thead>
<tbody class="divide-y divide-slate-50">
@forelse($users as $user)
<tr class="hover:bg-[#F8FAFC] transition-colors group">
<td class="py-4 px-6">
<div class="flex items-center gap-3">
@if($user->role === 'Super Admin')
<div class="w-10 h-10 rounded-full bg-[#183262] text-white flex items-center justify-center text-[13px] font-bold shrink-0 relative">
{{ strtoupper(substr($user->name, 0, 1)) }}
<div class="absolute bottom-0 right-0 w-3 h-3 bg-emerald-400 border-2 border-white rounded-full"></div>
</div>
@else
<div class="w-10 h-10 rounded-full bg-[#334155] text-white flex items-center justify-center text-[13px] font-bold shrink-0">
{{ strtoupper(substr($user->name, 0, 1)) }}
</div>
@endif
<div class="flex flex-col">
<div class="flex items-center gap-2">
<h4 class="text-[13px] font-bold text-[#1e293b]">{{ $user->name }}</h4>
@if($user->role === 'Super Admin')
<span class="px-1.5 py-0.5 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded text-[9px] font-black tracking-wider uppercase">Online</span>
@endif
</div>
<p class="text-[11px] font-medium text-[#94a3b8] mt-0.5">Terdaftar {{ $user->created_at ? $user->created_at->diffForHumans() : '-' }}</p>
</div>
</div>
</td>
<td class="py-4 px-4 text-[13px] font-medium text-[#64748b]">{{ $user->email }}</td>
<td class="py-4 px-4 text-center">
@if($user->role === 'Super Admin')
<span class="inline-block px-3 py-1 bg-rose-50 text-rose-600 border border-rose-100 rounded-full text-[10px] font-bold tracking-wide">Super Admin</span>
@elseif($user->role === 'Pelatih')
<span class="inline-block px-3 py-1 bg-blue-50 text-blue-600 border border-blue-100 rounded-full text-[10px] font-bold tracking-wide">Pelatih</span>
@elseif($user->role === 'Analis')
<span class="inline-block px-3 py-1 bg-amber-50 text-amber-600 border border-amber-100 rounded-full text-[10px] font-bold tracking-wide">Analis</span>
@else
<span class="inline-block px-3 py-1 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-full text-[10px] font-bold tracking-wide">Pemain</span>
@endif
</td>
<td class="py-4 px-4">
@if($user->status === 'Aktif')
<div class="flex items-center gap-1.5">
<div class="w-1.5 h-1.5 rounded-full bg-emerald-500"></div>
<p class="text-[12px] font-bold text-emerald-600">Aktif</p>
</div>
@else
<div class="flex items-center gap-1.5">
<div class="w-1.5 h-1.5 rounded-full bg-rose-500"></div>
<p class="text-[12px] font-bold text-rose-600">Nonaktif</p>
</div>
@endif
</td>
<td class="py-4 px-6 text-right">
<div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
<a href="{{ route('users.edit', $user->id) }}" class="w-8 h-8 rounded-lg bg-slate-50 border border-slate-200 text-slate-400 hover:text-[#2d5096] hover:border-[#2d5096] flex items-center justify-center transition-colors" title="Edit">
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
</a>
<form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline-block m-0" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?');">
@csrf
@method('DELETE')
<button type="submit" class="w-8 h-8 rounded-lg bg-slate-50 border border-slate-200 text-slate-400 hover:text-rose-500 hover:border-rose-500 flex items-center justify-center transition-colors" title="Hapus">
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
</button>
</form>
</div>
</td>
</tr>
@empty
<tr>
<td colspan="5" class="py-10 text-center text-slate-500 font-medium">Belum ada pengguna terdaftar.</td>
</tr>
@endforelse
</tbody>
</table>
</div>
</div>
@endsection
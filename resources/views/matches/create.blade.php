@extends('layouts.app')
@section('title', 'Tambah Match - FFA Analytics')
@section('header', 'Tambah Match')
@section('subheader', 'Kelola jadwal pertandingan akademi')
@section('header_action')
<a href="{{ route('matches.index') }}" class="bg-[#2d5096] hover:bg-[#183262] text-white px-6 py-2.5 rounded-lg text-[13px] font-semibold transition-colors shadow-sm">Kembali</a>
@endsection
@section('content')
<div class="bg-white rounded-[20px] border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] p-8 mb-8">
<h3 class="text-[11px] font-black text-slate-400 tracking-widest uppercase mb-6">Informasi Pertandingan</h3>
<form action="{{ route('matches.store') }}" method="POST">
@csrf
<div class="grid grid-cols-1 md:grid-cols-2 gap-x-10 gap-y-6">
<div>
<label for="title" class="block text-[13px] font-bold text-[#334155] mb-2">Nama Tim</label>
<div class="relative">
<select id="title" name="title" required class="w-full px-4 py-3 bg-white border border-[#e2e8f0] rounded-xl text-[13px] text-[#334155] appearance-none focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] transition-shadow shadow-sm cursor-pointer">
<option value="" disabled selected>Nama Tim</option>
<option value="GhostClub">GhostClub</option>
</select>
<div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400">
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
</div>
</div>
</div>
<div>
<label for="match_date" class="block text-[13px] font-bold text-[#334155] mb-2">Tanggal Pertandingan</label>
<input type="datetime-local" id="match_date" name="match_date" required class="w-full px-4 py-3 bg-white border border-[#e2e8f0] rounded-xl text-[13px] text-[#334155] focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] transition-shadow shadow-sm">
</div>
<div>
<label for="opponent" class="block text-[13px] font-bold text-[#334155] mb-2">Tim Lawan</label>
<input type="text" id="opponent" name="opponent" placeholder="Nama Tim Lawan" required class="w-full px-4 py-3 bg-white border border-[#e2e8f0] rounded-xl text-[13px] text-[#334155] placeholder-slate-400 focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] transition-shadow shadow-sm">
</div>
<div>
<label for="venue" class="block text-[13px] font-bold text-[#334155] mb-2">Tempat Pertandingan</label>
<input type="text" id="venue" name="venue" required class="w-full px-4 py-3 bg-white border border-[#e2e8f0] rounded-xl text-[13px] text-[#334155] focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] transition-shadow shadow-sm">
</div>
<div>
<label for="formation" class="block text-[13px] font-bold text-[#334155] mb-2">Formasi</label>
<div class="relative">
<select id="formation" name="formation" required class="w-full px-4 py-3 bg-white border border-[#e2e8f0] rounded-xl text-[13px] text-[#334155] appearance-none focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] transition-shadow shadow-sm cursor-pointer">
<option value="" disabled selected>Pilih Formasi</option>
<option value="4-3-3">4-3-3</option>
<option value="4-4-2">4-4-2</option>
<option value="4-2-3-1">4-2-3-1</option>
<option value="3-5-2">3-5-2</option>
<option value="3-4-3">3-4-3</option>
<option value="5-3-2">5-3-2</option>
</select>
<div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400">
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
</div>
</div>
</div>
</div>
<div class="mt-8 pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
<a href="{{ route('matches.index') }}" class="px-6 py-2.5 bg-white border border-slate-200 text-slate-600 text-[13px] font-bold rounded-lg hover:bg-slate-50 transition-colors">Batal</a>
<button type="submit" class="px-6 py-2.5 bg-[#2d5096] hover:bg-[#183262] text-white text-[13px] font-bold rounded-lg transition-colors shadow-sm">Simpan Pertandingan</button>
</div>
</form>
</div>
<div class="bg-white rounded-[20px] border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] p-8">
<div class="flex items-center justify-between mb-6">
<h3 class="text-[11px] font-black text-slate-400 tracking-widest uppercase">Set Line-Up Tim</h3>
</div>
<div class="flex items-center gap-4 mb-6">
<label class="text-[13px] font-bold text-[#334155]">Nama Tim</label>
<div class="relative w-64">
<select class="w-full px-4 py-2 bg-slate-50 border border-[#e2e8f0] rounded-lg text-[12px] text-[#334155] appearance-none focus:outline-none focus:border-[#4062D6] cursor-pointer">
<option value="GhostClub">GhostClub</option>
</select>
<div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-400">
<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
</div>
</div>
</div>
<div class="flex justify-between items-end mb-4">
<h4 class="text-[14px] font-bold text-[#1e293b]">Daftar Pemain Aktif</h4>
<p class="text-[11px] text-slate-400 italic">Pilih pemain dan tentukan posisi bermainnya.</p>
</div>
<div class="overflow-x-auto border border-slate-200 rounded-xl mb-6">
<table class="w-full text-left border-collapse">
<thead>
<tr class="bg-slate-50 border-b border-slate-200">
<th class="py-3 px-4 text-[10px] font-bold text-[#94a3b8] uppercase tracking-widest w-[5%] text-center">Bawa</th>
<th class="py-3 px-4 text-[10px] font-bold text-[#94a3b8] uppercase tracking-widest w-[35%]">Nama Pemain</th>
<th class="py-3 px-4 text-[10px] font-bold text-[#94a3b8] uppercase tracking-widest w-[15%] text-center">No. Punggung</th>
<th class="py-3 px-4 text-[10px] font-bold text-[#94a3b8] uppercase tracking-widest w-[25%] text-center">Posisi Di Pertandingan</th>
<th class="py-3 px-4 text-[10px] font-bold text-[#94a3b8] uppercase tracking-widest w-[20%] text-center">Status</th>
</tr>
</thead>
<tbody class="divide-y divide-slate-100">
<tr class="hover:bg-slate-50 transition-colors">
<td class="py-3 px-4 text-center">
<input type="checkbox" checked class="w-4 h-4 text-[#2d5096] border-slate-300 rounded focus:ring-[#2d5096]">
</td>
<td class="py-3 px-4 text-[13px] font-bold text-[#1e293b]">Yazid Ardiyan Wicaksono</td>
<td class="py-3 px-4 text-[13px] font-bold text-slate-500 text-center">#10</td>
<td class="py-3 px-4">
<select class="w-full px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-[12px] text-slate-600 focus:outline-none focus:border-[#4062D6]">
<option value="ST" selected>ST</option>
<option value="LW">LW</option>
<option value="RW">RW</option>
</select>
</td>
<td class="py-3 px-4">
<select class="w-full px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-[12px] text-slate-600 focus:outline-none focus:border-[#4062D6]">
<option value="Starting XI" selected>Starting XI</option>
<option value="Bench">Bench</option>
</select>
</td>
</tr>
<tr class="hover:bg-slate-50 transition-colors">
<td class="py-3 px-4 text-center">
<input type="checkbox" class="w-4 h-4 text-[#2d5096] border-slate-300 rounded focus:ring-[#2d5096]">
</td>
<td class="py-3 px-4 text-[13px] font-bold text-[#1e293b]">Arif Paradigma</td>
<td class="py-3 px-4 text-[13px] font-bold text-slate-500 text-center">#69</td>
<td class="py-3 px-4">
<select class="w-full px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-[12px] text-slate-600 focus:outline-none focus:border-[#4062D6]">
<option value="">Pilih Posisi</option>
<option value="CB">CB</option>
</select>
</td>
<td class="py-3 px-4">
<select class="w-full px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-[12px] text-slate-600 focus:outline-none focus:border-[#4062D6]">
<option value="">Pilih Status</option>
<option value="Starting XI">Starting XI</option>
<option value="Bench">Bench</option>
</select>
</td>
</tr>
</tbody>
</table>
</div>
<div class="flex items-center justify-end gap-3 mt-4">
<button type="button" class="px-6 py-2.5 bg-white border border-slate-200 text-slate-600 text-[13px] font-bold rounded-lg hover:bg-slate-50 transition-colors">Batal</button>
<button type="button" class="px-6 py-2.5 bg-[#2d5096] hover:bg-[#183262] text-white text-[13px] font-bold rounded-lg transition-colors shadow-sm">Simpan Line-Up</button>
</div>
</div>
@endsection
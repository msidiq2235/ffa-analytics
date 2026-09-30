@extends('layouts.app')
@section('title', 'Edit Match - FFA Analytics')
@section('header', 'Edit Pertandingan')
@section('subheader', 'Perbarui informasi pertandingan')
@section('header_action')
<a href="{{ route('matches.index') }}" class="bg-[#2d5096] hover:bg-[#183262] text-white px-6 py-2.5 rounded-lg text-[13px] font-semibold transition-colors shadow-sm">Kembali</a>
@endsection
@section('content')
<div class="bg-white rounded-[20px] border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] p-8 md:p-10">
<form action="{{ route('matches.update', $match->id) }}" method="POST">
@csrf
@method('PUT')
<div class="grid grid-cols-1 md:grid-cols-2 gap-x-10 gap-y-6">
<div>
<label for="title" class="block text-[13px] font-bold text-[#334155] mb-2">Nama Tim</label>
<div class="relative">
<select id="title" name="title" required class="w-full px-4 py-3 bg-white border border-[#e2e8f0] rounded-xl text-[13px] text-[#334155] appearance-none focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] transition-shadow shadow-sm cursor-pointer">
<option value="GhostClub" {{ old('title', $match->title) == 'GhostClub' ? 'selected' : '' }}>GhostClub</option>
</select>
<div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400">
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
</div>
</div>
@error('title')<p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>@enderror
</div>
<div>
<label for="match_date" class="block text-[13px] font-bold text-[#334155] mb-2">Tanggal Pertandingan</label>
<input type="datetime-local" id="match_date" name="match_date" value="{{ old('match_date', \Carbon\Carbon::parse($match->match_date)->format('Y-m-d\TH:i')) }}" required class="w-full px-4 py-3 bg-white border border-[#e2e8f0] rounded-xl text-[13px] text-[#334155] focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] transition-shadow shadow-sm">
@error('match_date')<p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>@enderror
</div>
<div>
<label for="opponent" class="block text-[13px] font-bold text-[#334155] mb-2">Tim Lawan</label>
<input type="text" id="opponent" name="opponent" value="{{ old('opponent', $match->opponent) }}" placeholder="Nama Tim Lawan" required class="w-full px-4 py-3 bg-white border border-[#e2e8f0] rounded-xl text-[13px] text-[#334155] placeholder-slate-400 focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] transition-shadow shadow-sm">
@error('opponent')<p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>@enderror
</div>
<div>
<label for="venue" class="block text-[13px] font-bold text-[#334155] mb-2">Tempat Pertandingan</label>
<input type="text" id="venue" name="venue" value="{{ old('venue', $match->venue) }}" required class="w-full px-4 py-3 bg-white border border-[#e2e8f0] rounded-xl text-[13px] text-[#334155] focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] transition-shadow shadow-sm">
@error('venue')<p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>@enderror
</div>
<div>
<label for="status" class="block text-[13px] font-bold text-[#334155] mb-2">Status Pertandingan Akademi</label>
<div class="relative">
<select id="status" name="status" required class="w-full px-4 py-3 bg-white border border-[#e2e8f0] rounded-xl text-[13px] text-[#334155] appearance-none focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] transition-shadow shadow-sm cursor-pointer">
<option value="Upcoming" {{ old('status', $match->status) == 'Upcoming' ? 'selected' : '' }}>Upcoming</option>
<option value="Live" {{ old('status', $match->status) == 'Live' ? 'selected' : '' }}>Live</option>
<option value="Selesai" {{ old('status', $match->status) == 'Selesai' ? 'selected' : '' }}>Selesai</option>
</select>
<div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400">
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
</div>
</div>
@error('status')<p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>@enderror
</div>
</div>
<div class="mt-12 pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
<a href="{{ route('matches.index') }}" class="px-6 py-3 bg-white border border-slate-200 text-slate-600 text-[13px] font-bold rounded-xl hover:bg-slate-50 transition-colors">Batal</a>
<button type="submit" class="px-6 py-3 bg-[#2d5096] hover:bg-[#183262] text-white text-[13px] font-bold rounded-xl transition-colors shadow-sm active:scale-95">Update Data</button>
</div>
</form>
</div>
@endsection
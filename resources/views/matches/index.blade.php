@extends('layouts.app')
@section('title', 'Jadwal & Match - FFA Analytics')
@section('header', 'Jadwal & Match')
@section('subheader', 'kelola jadwal pertandingan akademi')
@section('header_action')
<a href="{{ route('matches.create') }}" class="bg-[#4062D6] hover:bg-[#3251b5] text-white px-5 py-2.5 rounded-xl text-[13px] font-semibold transition-colors shadow-sm flex items-center gap-2">
<span>+</span> Tambah Jadwal
</a>
@endsection
@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
@forelse($matches as $match)
<div class="bg-white rounded-[20px] border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] p-6">
<div class="flex justify-between items-center mb-5">
<span class="text-[11px] font-bold text-slate-400 tracking-widest uppercase">{{ \Carbon\Carbon::parse($match->match_date)->format('d M Y') }}</span>
@if(strtolower($match->status) === 'selesai')
<span class="px-3 py-1 bg-emerald-50 text-emerald-500 border border-emerald-100 rounded text-[10px] font-black tracking-widest uppercase">Selesai</span>
@else
<span class="px-3 py-1 bg-orange-50 text-orange-400 border border-orange-100 rounded text-[10px] font-black tracking-widest uppercase">Upcoming</span>
@endif
</div>
<div class="mb-5">
<h3 class="text-[22px] font-bold text-[#1e293b] leading-tight">{{ $match->title ?? 'GhostClub' }}</h3>
<p class="text-[13px] font-medium text-slate-500 mt-1">vs {{ $match->opponent ?? 'Tim Lawan' }}</p>
</div>
<div class="space-y-2.5 mb-6">
<div class="flex items-center gap-2.5 text-slate-400">
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
<span class="text-[12px] font-medium text-slate-500">{{ $match->venue ?? 'Belum ditentukan' }}</span>
</div>
<div class="flex items-center gap-2.5 text-slate-400">
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
<span class="text-[12px] font-medium text-slate-500">Formasi: <span class="font-bold text-slate-700">{{ $match->formation ?? '4-3-3' }}</span></span>
</div>
</div>
<div class="grid grid-cols-2 gap-3 mb-3">
<a href="{{ route('matches.lineup', $match->id) }}" class="flex items-center justify-center py-2.5 border border-slate-200 rounded-xl text-[12px] font-bold text-slate-600 hover:bg-slate-50 transition-colors">Line-Up</a>
<a href="{{ route('matches.live', $match->id) }}" class="flex items-center justify-center gap-1.5 py-2.5 border border-orange-200 rounded-xl text-[12px] font-bold text-orange-500 hover:bg-orange-50 transition-colors">
<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
Live Analytics
</a>
<a href="{{ route('matches.vector', $match->id) }}" class="flex items-center justify-center py-2.5 border border-purple-200 rounded-xl text-[12px] font-bold text-purple-600 hover:bg-purple-50 transition-colors">Vektor</a>
<a href="{{ route('matches.edit', $match->id) }}" class="flex items-center justify-center gap-1.5 py-2.5 border border-slate-200 rounded-xl text-[12px] font-bold text-slate-500 hover:bg-slate-50 transition-colors">
<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
Edit Pertandingan
</a>
</div>
<a href="{{ route('reports.match.show', $match->id) }}" class="w-full flex items-center justify-center gap-2 py-3 bg-[#4062D6] hover:bg-[#3251b5] text-white rounded-xl text-[13px] font-bold transition-colors shadow-sm active:scale-95">
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
Lihat Report Pertandingan
</a>
</div>
@empty
<div class="col-span-full py-12 bg-white rounded-2xl border border-slate-200 flex flex-col items-center justify-center text-center">
<svg class="w-12 h-12 text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
<h3 class="text-lg font-bold text-slate-700 mb-1">Belum Ada Jadwal</h3>
<p class="text-[13px] text-slate-500">Jadwal pertandingan yang ditambahkan akan muncul di sini.</p>
</div>
@endforelse
</div>
@endsection
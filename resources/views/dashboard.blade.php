@extends('layouts.app')
@section('title', 'Dashboard - FFA Analytics')
@php
$activeRole = session('active_role', auth()->user()->role ?? 'Super Admin');
@endphp
@section('header', 'Dashboard ' . $activeRole)
@section('subheader', 'Overview data, jadwal, dan statistik performa platform')
@section('content')

@if($activeRole === 'Pelatih' || $activeRole === 'Super Admin')
<div class="space-y-6">
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
<div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)]">
<p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Upcoming Matches</p>
<h3 class="text-3xl font-black text-[#1e293b]">{{ $upcomingMatches ?? 2 }}</h3>
</div>
<div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)]">
<p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Goals Scored</p>
<h3 class="text-3xl font-black text-[#4062D6]">{{ $goalsScored ?? 1 }}</h3>
</div>
<div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)]">
<p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Total Assists</p>
<h3 class="text-3xl font-black text-emerald-500">{{ $totalAssists ?? 0 }}</h3>
</div>
<div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)]">
<p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Matches Completed</p>
<h3 class="text-3xl font-black text-[#1e293b]">{{ $matchesCompleted ?? 1 }}</h3>
</div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
<div class="bg-white rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] p-6">
<div class="flex items-center justify-between mb-6">
<h3 class="font-bold text-[#1e293b]">Persiapan: Jadwal Mendatang</h3>
<a href="{{ route('matches.index') }}" class="text-[11px] font-bold text-[#4062D6] px-3 py-1.5 rounded-lg border border-blue-100 bg-blue-50 hover:bg-blue-100 transition-colors">Buka Jadwal</a>
</div>
<div class="space-y-4">
<div class="flex items-center justify-between p-4 rounded-xl border border-slate-100 bg-slate-50/50 hover:bg-slate-50 transition-colors">
<div class="flex items-center gap-4">
<div class="w-12 h-12 rounded-xl bg-white border border-slate-200 flex flex-col items-center justify-center shrink-0 shadow-sm">
<span class="text-[10px] font-bold text-slate-400 leading-none">5</span>
<span class="text-[10px] font-bold text-slate-600 leading-none mt-1">Jul</span>
</div>
<div>
<h4 class="text-[13px] font-bold text-[#1e293b]">vs SSB Bantul</h4>
<p class="text-[11px] text-slate-500 font-medium">Liga1 — P8</p>
<div class="flex items-center gap-1 mt-1.5 text-slate-400">
<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
<span class="text-[10px]">Stadion Mandala Krida</span>
</div>
</div>
</div>
<div class="text-right">
<p class="text-[13px] font-bold text-[#1e293b]">15.00 WIB</p>
<p class="text-[10px] font-bold text-[#4062D6] mt-1">Mendatang</p>
</div>
</div>
<div class="flex items-center justify-between p-4 rounded-xl border border-slate-100 bg-slate-50/50 hover:bg-slate-50 transition-colors">
<div class="flex items-center gap-4">
<div class="w-12 h-12 rounded-xl bg-white border border-slate-200 flex flex-col items-center justify-center shrink-0 shadow-sm">
<span class="text-[10px] font-bold text-slate-400 leading-none">12</span>
<span class="text-[10px] font-bold text-slate-600 leading-none mt-1">Jul</span>
</div>
<div>
<h4 class="text-[13px] font-bold text-[#1e293b]">vs SSB Piyungan</h4>
<p class="text-[11px] text-slate-500 font-medium">Liga1 — P9</p>
<div class="flex items-center gap-1 mt-1.5 text-slate-400">
<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
<span class="text-[10px]">Lapangan Piyungan</span>
</div>
</div>
</div>
<div class="text-right">
<p class="text-[13px] font-bold text-[#1e293b]">09.00 WIB</p>
<p class="text-[10px] font-bold text-[#4062D6] mt-1">Mendatang</p>
</div>
</div>
</div>
</div>
<div class="bg-white rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] p-6">
<div class="flex items-center justify-between mb-6">
<h3 class="font-bold text-[#1e293b]">Hasil Pertandingan Terakhir</h3>
<a href="#" class="text-[11px] font-bold text-[#4062D6] hover:text-[#3251b5]">Semua &gt;</a>
</div>
<div class="space-y-4 mb-6">
<div class="flex items-center justify-between p-4 rounded-xl border border-slate-100 bg-slate-50/50 relative overflow-hidden">
<div class="absolute left-0 top-0 bottom-0 w-1 bg-emerald-500"></div>
<div>
<h4 class="text-[13px] font-bold text-[#1e293b] mb-1">Liga1</h4>
<p class="text-[10px] text-slate-400 mb-1.5">29 June 2026</p>
<p class="text-[12px] font-bold text-emerald-600">Menang 2-1 <span class="text-slate-400 font-medium ml-1">vs SSB Kretek</span></p>
</div>
<a href="#" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-[11px] font-bold text-slate-600 hover:bg-slate-50 shadow-sm">
<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg> Evaluasi
</a>
</div>
<div class="flex items-center justify-between p-4 rounded-xl border border-slate-100 bg-slate-50/50 relative overflow-hidden">
<div class="absolute left-0 top-0 bottom-0 w-1 bg-rose-500"></div>
<div>
<h4 class="text-[13px] font-bold text-[#1e293b] mb-1">Liga1</h4>
<p class="text-[10px] text-slate-400 mb-1.5">14 June 2026</p>
<p class="text-[12px] font-bold text-rose-600">Kalah 0-2 <span class="text-slate-400 font-medium ml-1">vs SSB Sleman</span></p>
</div>
<a href="#" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-[11px] font-bold text-slate-600 hover:bg-slate-50 shadow-sm">
<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg> Evaluasi
</a>
</div>
</div>
<div class="grid grid-cols-3 gap-3">
<div class="bg-emerald-50 rounded-xl p-3 flex flex-col items-center justify-center">
<svg class="w-5 h-5 text-emerald-500 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
<span class="text-[18px] font-black text-emerald-600">8</span>
<span class="text-[10px] font-bold text-emerald-500 uppercase tracking-wider">Menang</span>
</div>
<div class="bg-orange-50 rounded-xl p-3 flex flex-col items-center justify-center">
<svg class="w-5 h-5 text-orange-500 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
<span class="text-[18px] font-black text-orange-600">16</span>
<span class="text-[10px] font-bold text-orange-500 uppercase tracking-wider">Gol</span>
</div>
<div class="bg-blue-50 rounded-xl p-3 flex flex-col items-center justify-center">
<svg class="w-5 h-5 text-blue-500 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
<span class="text-[18px] font-black text-blue-600">4</span>
<span class="text-[10px] font-bold text-blue-500 uppercase tracking-wider">Seri</span>
</div>
</div>
</div>
</div>

<div class="bg-white rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] p-6">
<div class="flex items-center gap-2 mb-6">
<svg class="w-5 h-5 text-[#4062D6]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.898 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
<h3 class="font-bold text-[#1e293b]">Top Performa Pemain — Musim 2026</h3>
</div>
<div class="overflow-x-auto">
<table class="w-full text-left border-collapse">
<thead>
<tr class="border-b border-slate-100">
<th class="py-3 px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest w-[5%]">#</th>
<th class="py-3 px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest w-[30%]">Pemain</th>
<th class="py-3 px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">No.</th>
<th class="py-3 px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Posisi</th>
<th class="py-3 px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Main</th>
<th class="py-3 px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Gol</th>
<th class="py-3 px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Assist</th>
<th class="py-3 px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Rating</th>
</tr>
</thead>
<tbody class="divide-y divide-slate-50">
<tr class="hover:bg-slate-50 transition-colors">
<td class="py-4 px-4 text-[12px] font-bold text-slate-500">1</td>
<td class="py-4 px-4 text-[13px] font-bold text-[#1e293b]">Yazid Ardiyan</td>
<td class="py-4 px-4 text-[12px] font-bold text-slate-500 text-center">10</td>
<td class="py-4 px-4 text-[12px] font-bold text-slate-500 text-center">ST</td>
<td class="py-4 px-4 text-[12px] font-bold text-slate-500 text-center">12</td>
<td class="py-4 px-4 text-[12px] font-bold text-slate-500 text-center">8</td>
<td class="py-4 px-4 text-[12px] font-bold text-slate-500 text-center">4</td>
<td class="py-4 px-4 text-[13px] font-black text-emerald-500 text-right">8.4</td>
</tr>
<tr class="hover:bg-slate-50 transition-colors">
<td class="py-4 px-4 text-[12px] font-bold text-slate-500">2</td>
<td class="py-4 px-4 text-[13px] font-bold text-[#1e293b]">Arif Paradigma</td>
<td class="py-4 px-4 text-[12px] font-bold text-slate-500 text-center">69</td>
<td class="py-4 px-4 text-[12px] font-bold text-slate-500 text-center">GK</td>
<td class="py-4 px-4 text-[12px] font-bold text-slate-500 text-center">11</td>
<td class="py-4 px-4 text-[12px] font-bold text-slate-500 text-center">0</td>
<td class="py-4 px-4 text-[12px] font-bold text-slate-500 text-center">2</td>
<td class="py-4 px-4 text-[13px] font-black text-orange-500 text-right">7.9</td>
</tr>
<tr class="hover:bg-slate-50 transition-colors">
<td class="py-4 px-4 text-[12px] font-bold text-slate-500">3</td>
<td class="py-4 px-4 text-[13px] font-bold text-[#1e293b]">Sandy NurWidjan</td>
<td class="py-4 px-4 text-[12px] font-bold text-slate-500 text-center">17</td>
<td class="py-4 px-4 text-[12px] font-bold text-slate-500 text-center">LW</td>
<td class="py-4 px-4 text-[12px] font-bold text-slate-500 text-center">10</td>
<td class="py-4 px-4 text-[12px] font-bold text-slate-500 text-center">3</td>
<td class="py-4 px-4 text-[12px] font-bold text-slate-500 text-center">5</td>
<td class="py-4 px-4 text-[13px] font-black text-orange-500 text-right">7.6</td>
</tr>
<tr class="hover:bg-slate-50 transition-colors">
<td class="py-4 px-4 text-[12px] font-bold text-slate-500">4</td>
<td class="py-4 px-4 text-[13px] font-bold text-[#1e293b]">Anan Zada</td>
<td class="py-4 px-4 text-[12px] font-bold text-slate-500 text-center">6</td>
<td class="py-4 px-4 text-[12px] font-bold text-slate-500 text-center">RB</td>
<td class="py-4 px-4 text-[12px] font-bold text-slate-500 text-center">12</td>
<td class="py-4 px-4 text-[12px] font-bold text-slate-500 text-center">1</td>
<td class="py-4 px-4 text-[12px] font-bold text-slate-500 text-center">6</td>
<td class="py-4 px-4 text-[13px] font-black text-slate-600 text-right">7.3</td>
</tr>
</tbody>
</table>
</div>
</div>
</div>
@elseif($activeRole === 'Analis')
<div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
<div class="lg:col-span-8 space-y-6">
<div class="bg-white rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] overflow-hidden">
<div class="bg-[#4062D6] px-6 py-3 flex items-center justify-between text-white">
<div class="flex items-center gap-2">
<div class="w-2 h-2 rounded-full bg-rose-400 animate-pulse"></div>
<span class="text-[11px] font-black tracking-widest uppercase">Live <span class="font-normal opacity-70 ml-1 tracking-normal capitalize">Liga1 — Pekan 7</span></span>
</div>
<div class="flex items-center gap-1.5 text-[11px] font-bold">
<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Menit 64'
</div>
</div>
<div class="p-8">
<div class="flex items-center justify-center gap-8 md:gap-16 mb-10">
<div class="flex flex-col items-center gap-3">
<div class="w-16 h-16 md:w-20 md:h-20 bg-slate-100 rounded-full flex items-center justify-center">
<img src="{{ asset('images/logo-ffa.png') }}" alt="Home" class="w-10 h-10 md:w-14 md:h-14 object-contain">
</div>
<div class="text-center">
<h3 class="text-[14px] md:text-[16px] font-bold text-[#1e293b]">GhostClub</h3>
<p class="text-[11px] text-slate-400">4-4-2</p>
</div>
</div>
<div class="flex flex-col items-center">
<div class="text-3xl md:text-5xl font-black text-[#1e293b] tracking-wider mb-2">2 - 1</div>
<span class="px-3 py-1 bg-rose-50 text-rose-500 border border-rose-100 rounded text-[9px] font-black tracking-widest uppercase">Berlangsung</span>
</div>
<div class="flex flex-col items-center gap-3">
<div class="w-16 h-16 md:w-20 md:h-20 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold text-2xl">
K
</div>
<div class="text-center">
<h3 class="text-[14px] md:text-[16px] font-bold text-[#1e293b]">SSB Kretek</h3>
<p class="text-[11px] text-slate-400">4-3-3</p>
</div>
</div>
</div>
<div class="border-t border-slate-100 pt-6">
<p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-4">Timeline Kejadian</p>
<div class="space-y-3 pl-2">
<div class="flex items-center gap-4">
<span class="text-[12px] font-bold text-slate-500 w-6 text-right">12'</span>
<svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
<p class="text-[12px] font-medium text-slate-700">Yazid A. <span class="text-slate-400 text-[11px] ml-1">(GhostClub)</span></p>
</div>
<div class="flex items-center gap-4">
<span class="text-[12px] font-bold text-slate-500 w-6 text-right">38'</span>
<svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
<p class="text-[12px] font-medium text-slate-700">Bagas K. <span class="text-slate-400 text-[11px] ml-1">(SSB Kretek)</span></p>
</div>
<div class="flex items-center gap-4">
<span class="text-[12px] font-bold text-slate-500 w-6 text-right">57'</span>
<svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
<p class="text-[12px] font-medium text-slate-700">Arif P. <span class="text-slate-400 text-[11px] ml-1">(GhostClub)</span></p>
</div>
<div class="flex items-center gap-4">
<span class="text-[12px] font-bold text-slate-500 w-6 text-right">59'</span>
<div class="w-3 h-4 bg-yellow-400 rounded-sm ml-0.5"></div>
<p class="text-[12px] font-medium text-slate-700 ml-0.5">Hendra S. <span class="text-slate-400 text-[11px] ml-1">(SSB Kretek)</span></p>
</div>
</div>
</div>
</div>
</div>
<div class="bg-white rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] p-6">
<div class="flex items-center justify-between mb-6">
<div class="flex items-center gap-2">
<svg class="w-5 h-5 text-[#4062D6]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
<h3 class="font-bold text-[#1e293b]">Pertandingan Mendatang</h3>
</div>
<a href="{{ route('matches.index') }}" class="text-[11px] font-bold text-[#4062D6] hover:text-[#3251b5]">Lihat semua &gt;</a>
</div>
<div class="space-y-3">
<div class="flex items-center justify-between py-3 border-b border-slate-100">
<div class="flex gap-4">
<div class="text-center shrink-0">
<p class="text-[13px] font-bold text-[#4062D6]">5 Jul</p>
<p class="text-[10px] font-bold text-slate-400">15.00 WIB</p>
</div>
<div>
<h4 class="text-[13px] font-bold text-[#1e293b]">vs SSB Bantul</h4>
<p class="text-[11px] text-slate-500 font-medium mt-0.5">Liga1 — P8</p>
</div>
</div>
<div class="flex items-center gap-1.5 text-slate-400 bg-slate-50 px-3 py-1.5 rounded-lg">
<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
<span class="text-[10px] font-medium">Stadion Mandala Krida</span>
</div>
</div>
<div class="flex items-center justify-between py-3">
<div class="flex gap-4">
<div class="text-center shrink-0">
<p class="text-[13px] font-bold text-[#4062D6]">12 Jul</p>
<p class="text-[10px] font-bold text-slate-400">09.00 WIB</p>
</div>
<div>
<h4 class="text-[13px] font-bold text-[#1e293b]">vs SSB Piyungan</h4>
<p class="text-[11px] text-slate-500 font-medium mt-0.5">Liga1 — P9</p>
</div>
</div>
<div class="flex items-center gap-1.5 text-slate-400 bg-slate-50 px-3 py-1.5 rounded-lg">
<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
<span class="text-[10px] font-medium">Lapangan Piyungan</span>
</div>
</div>
</div>
</div>
</div>
<div class="lg:col-span-4 space-y-6">
<div class="bg-white rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] p-6">
<div class="flex items-center justify-between mb-6">
<div class="flex items-center gap-2">
<svg class="w-5 h-5 text-[#4062D6]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
<h3 class="font-bold text-[#1e293b]">Tugas Mendatang</h3>
</div>
<span class="text-[10px] font-bold text-orange-500">4 Aktif</span>
</div>
<div class="space-y-4">
<label class="flex items-start gap-3 cursor-pointer group">
<input type="checkbox" class="mt-0.5 w-4 h-4 text-[#4062D6] border-slate-300 rounded focus:ring-[#4062D6]">
<div>
<p class="text-[12px] font-bold text-slate-700 group-hover:text-[#4062D6] transition-colors">Analisis Pertandingan vs SSB Bantul</p>
<p class="text-[10px] text-slate-400 mt-0.5">Besok, 15:00</p>
</div>
</label>
<label class="flex items-start gap-3 cursor-pointer group">
<input type="checkbox" class="mt-0.5 w-4 h-4 text-[#4062D6] border-slate-300 rounded focus:ring-[#4062D6]">
<div>
<p class="text-[12px] font-bold text-slate-700 group-hover:text-[#4062D6] transition-colors">Evaluasi video pertandingan vs SSB Gamping</p>
<p class="text-[10px] text-slate-400 mt-0.5">2 Jul 2026</p>
</div>
</label>
<label class="flex items-start gap-3 cursor-pointer group">
<input type="checkbox" checked class="mt-0.5 w-4 h-4 text-[#4062D6] border-slate-300 rounded focus:ring-[#4062D6]">
<div>
<p class="text-[12px] font-bold text-slate-400 line-through">Input data statistik vs SSB Imogiri</p>
<p class="text-[10px] text-slate-400 mt-0.5">3 Jul 2026</p>
</div>
</label>
<label class="flex items-start gap-3 cursor-pointer group">
<input type="checkbox" class="mt-0.5 w-4 h-4 text-[#4062D6] border-slate-300 rounded focus:ring-[#4062D6]">
<div>
<p class="text-[12px] font-bold text-slate-700 group-hover:text-[#4062D6] transition-colors">Live Mapping pertandingan vs SSB Imogiri</p>
<p class="text-[10px] text-slate-400 mt-0.5">3 Jul 2026</p>
</div>
</label>
<label class="flex items-start gap-3 cursor-pointer group">
<input type="checkbox" class="mt-0.5 w-4 h-4 text-[#4062D6] border-slate-300 rounded focus:ring-[#4062D6]">
<div>
<p class="text-[12px] font-bold text-slate-700 group-hover:text-[#4062D6] transition-colors">Kirim laporan ke manajemen klub</p>
<p class="text-[10px] text-slate-400 mt-0.5">7 Jul 2026</p>
</div>
</label>
</div>
</div>
<div class="bg-white rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] p-6">
<div class="flex items-center gap-2 mb-6">
<svg class="w-5 h-5 text-[#4062D6]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
<h3 class="font-bold text-[#1e293b]">Aktivitas Terkini</h3>
</div>
<div class="relative border-l border-slate-200 ml-2 pl-4 space-y-5">
<div class="relative">
<div class="absolute -left-5 mt-1 w-2 h-2 rounded-full bg-emerald-500 ring-4 ring-white"></div>
<p class="text-[12px] font-bold text-slate-700">Match vs SSB Kretek selesai — menang 2-1</p>
<p class="text-[10px] text-slate-400 mt-0.5">1 jam lalu</p>
</div>
<div class="relative">
<div class="absolute -left-5 mt-1 w-2 h-2 rounded-full bg-orange-500 ring-4 ring-white"></div>
<p class="text-[12px] font-bold text-slate-700">Laporan performa Pekan 6 diunggah</p>
<p class="text-[10px] text-slate-400 mt-0.5">Kemarin, 16:25</p>
</div>
</div>
</div>
</div>
</div>

@elseif($activeRole === 'Pemain')
<div class="space-y-6">
<div class="bg-white rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] overflow-hidden">
<div class="p-6 md:p-8 flex flex-col md:flex-row items-center md:items-start gap-6 relative">
<div class="w-24 h-24 md:w-32 md:h-32 bg-[#183262] rounded-2xl flex flex-col items-center justify-center text-white shrink-0 shadow-md">
<span class="text-2xl md:text-3xl font-black">YA</span>
</div>
<div class="flex-1 text-center md:text-left mt-2 md:mt-4">
<h2 class="text-2xl font-black text-[#1e293b] tracking-tight">Yazid Ardiyan</h2>
<div class="flex items-center justify-center md:justify-start gap-3 mt-2">
<span class="px-2.5 py-1 bg-slate-100 text-slate-600 rounded text-[11px] font-bold tracking-widest uppercase">#10</span>
<span class="px-2.5 py-1 bg-blue-50 text-[#4062D6] border border-blue-100 rounded text-[11px] font-bold tracking-widest uppercase">ST</span>
</div>
</div>
<div class="absolute top-6 right-8 text-right hidden md:block">
<p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Klub</p>
<p class="text-[13px] font-bold text-[#1e293b]">GhostClub</p>
</div>
</div>
<div class="bg-slate-50 border-t border-slate-100 px-6 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
<div class="flex items-center gap-3">
<span class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">5 Match Terakhir</span>
<div class="flex items-center gap-1.5">
<div class="w-6 h-6 rounded-full bg-emerald-500 text-white flex items-center justify-center text-[10px] font-bold">W</div>
<div class="w-6 h-6 rounded-full bg-rose-500 text-white flex items-center justify-center text-[10px] font-bold">L</div>
<div class="w-6 h-6 rounded-full bg-emerald-500 text-white flex items-center justify-center text-[10px] font-bold">W</div>
<div class="w-6 h-6 rounded-full bg-yellow-500 text-white flex items-center justify-center text-[10px] font-bold">D</div>
<div class="w-6 h-6 rounded-full bg-emerald-500 text-white flex items-center justify-center text-[10px] font-bold">W</div>
</div>
</div>
<div class="flex items-center gap-4 text-[10px] font-bold text-slate-500">
<div class="flex items-center gap-1.5"><div class="w-2 h-2 rounded-full bg-emerald-500"></div>Menang</div>
<div class="flex items-center gap-1.5"><div class="w-2 h-2 rounded-full bg-yellow-500"></div>Seri</div>
<div class="flex items-center gap-1.5"><div class="w-2 h-2 rounded-full bg-rose-500"></div>Kalah</div>
</div>
</div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
<div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] flex flex-col justify-center">
<p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Matches Played</p>
<h3 class="text-4xl font-black text-[#4062D6]">12</h3>
</div>
<div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] flex flex-col justify-center">
<p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">My Goals</p>
<h3 class="text-4xl font-black text-[#4062D6]">8</h3>
</div>
<div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] flex flex-col justify-center">
<p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">My Assists</p>
<h3 class="text-4xl font-black text-[#4062D6]">4</h3>
</div>
</div>

<div class="bg-white rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] grid grid-cols-2 md:grid-cols-6 divide-y md:divide-y-0 md:divide-x divide-slate-100">
<div class="p-4 flex flex-col items-center justify-center text-center">
<span class="text-2xl font-black text-orange-500 mb-1">8.4</span>
<span class="text-[10px] font-bold text-slate-400">Avg Rating</span>
</div>
<div class="p-4 flex flex-col items-center justify-center text-center">
<span class="text-2xl font-black text-[#4062D6] mb-1">980</span>
<span class="text-[10px] font-bold text-slate-400">Menit Main</span>
</div>
<div class="p-4 flex flex-col items-center justify-center text-center">
<span class="text-2xl font-black text-yellow-500 mb-1">1</span>
<span class="text-[10px] font-bold text-slate-400">Kartu Kuning</span>
</div>
<div class="p-4 flex flex-col items-center justify-center text-center">
<span class="text-2xl font-black text-rose-500 mb-1">0</span>
<span class="text-[10px] font-bold text-slate-400">Kartu Merah</span>
</div>
<div class="p-4 flex flex-col items-center justify-center text-center">
<span class="text-2xl font-black text-emerald-500 mb-1">24</span>
<span class="text-[10px] font-bold text-slate-400">Tembakan</span>
</div>
<div class="p-4 flex flex-col items-center justify-center text-center">
<span class="text-2xl font-black text-purple-500 mb-1">18</span>
<span class="text-[10px] font-bold text-slate-400">Dribel Sukses</span>
</div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
<div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)]">
<div class="flex items-center gap-2 mb-6">
<svg class="w-4 h-4 text-[#4062D6]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.898 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
<h3 class="font-bold text-[#1e293b] text-[13px]">Radar Atribut Pemain</h3>
</div>
<div class="relative h-[250px] w-full flex justify-center">
<canvas id="radarChart"></canvas>
</div>
</div>
<div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)]">
<div class="flex items-center gap-2 mb-6">
<div class="w-3 h-3 rounded-full bg-[#4062D6]"></div>
<h3 class="font-bold text-[#1e293b] text-[13px]">Gol per Bulan — 2026</h3>
</div>
<div class="relative h-[250px] w-full">
<canvas id="barChart"></canvas>
</div>
</div>
</div>

<div class="bg-white rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] p-6">
<div class="flex items-center justify-between mb-6">
<div class="flex items-center gap-2">
<svg class="w-5 h-5 text-[#4062D6]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
<h3 class="font-bold text-[#1e293b]">Riwayat Pertandingan Anda</h3>
</div>
<a href="#" class="text-[11px] font-bold text-[#4062D6] hover:text-[#3251b5]">Semua &gt;</a>
</div>
<div class="space-y-3">
<div class="flex items-center justify-between p-4 rounded-xl border border-slate-100 hover:bg-slate-50 transition-colors">
<div class="flex items-center gap-4">
<div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-500 flex items-center justify-center shrink-0">
<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
</div>
<div>
<h4 class="text-[13px] font-bold text-[#1e293b]">Liga1 <span class="text-emerald-500 ml-2">Menang 2-1</span></h4>
<p class="text-[11px] text-slate-500 mt-1">29 June 2026 - vs SSB Kretek</p>
</div>
</div>
<div class="flex items-center gap-6">
<div class="flex gap-4 text-center">
<div><p class="text-[13px] font-black text-[#1e293b]">1</p><p class="text-[9px] font-bold text-slate-400">Gol</p></div>
<div><p class="text-[13px] font-black text-[#1e293b]">0</p><p class="text-[9px] font-bold text-slate-400">Assist</p></div>
<div><p class="text-[13px] font-black text-[#1e293b]">8.5</p><p class="text-[9px] font-bold text-slate-400">Rating</p></div>
</div>
<div class="flex gap-2">
<a href="#" class="px-3 py-1.5 border border-blue-200 text-blue-600 rounded-lg text-[10px] font-bold hover:bg-blue-50">PDF</a>
<a href="#" class="px-3 py-1.5 border border-slate-200 text-slate-600 rounded-lg text-[10px] font-bold hover:bg-slate-50">Lihat Evaluasi</a>
</div>
</div>
</div>

<div class="flex items-center justify-between p-4 rounded-xl border border-slate-100 hover:bg-slate-50 transition-colors">
<div class="flex items-center gap-4">
<div class="w-10 h-10 rounded-lg bg-rose-50 text-rose-500 flex items-center justify-center shrink-0">
<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
</div>
<div>
<h4 class="text-[13px] font-bold text-[#1e293b]">Liga1 <span class="text-rose-500 ml-2">Kalah 0-2</span></h4>
<p class="text-[11px] text-slate-500 mt-1">14 June 2026 - vs SSB Sleman</p>
</div>
</div>
<div class="flex items-center gap-6">
<div class="flex gap-4 text-center">
<div><p class="text-[13px] font-black text-[#1e293b]">0</p><p class="text-[9px] font-bold text-slate-400">Gol</p></div>
<div><p class="text-[13px] font-black text-[#1e293b]">0</p><p class="text-[9px] font-bold text-slate-400">Assist</p></div>
<div><p class="text-[13px] font-black text-[#1e293b]">6.2</p><p class="text-[9px] font-bold text-slate-400">Rating</p></div>
</div>
<div class="flex gap-2">
<a href="#" class="px-3 py-1.5 border border-blue-200 text-blue-600 rounded-lg text-[10px] font-bold hover:bg-blue-50">PDF</a>
<a href="#" class="px-3 py-1.5 border border-slate-200 text-slate-600 rounded-lg text-[10px] font-bold hover:bg-slate-50">Lihat Evaluasi</a>
</div>
</div>
</div>

<div class="flex items-center justify-between p-4 rounded-xl border border-slate-100 hover:bg-slate-50 transition-colors">
<div class="flex items-center gap-4">
<div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-500 flex items-center justify-center shrink-0">
<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
</div>
<div>
<h4 class="text-[13px] font-bold text-[#1e293b]">Liga1 <span class="text-emerald-500 ml-2">Menang 3-1</span></h4>
<p class="text-[11px] text-slate-500 mt-1">1 June 2026 - vs SSB Bantul</p>
</div>
</div>
<div class="flex items-center gap-6">
<div class="flex gap-4 text-center">
<div><p class="text-[13px] font-black text-[#1e293b]">2</p><p class="text-[9px] font-bold text-slate-400">Gol</p></div>
<div><p class="text-[13px] font-black text-[#1e293b]">1</p><p class="text-[9px] font-bold text-slate-400">Assist</p></div>
<div><p class="text-[13px] font-black text-[#1e293b]">9.0</p><p class="text-[9px] font-bold text-slate-400">Rating</p></div>
</div>
<div class="flex gap-2">
<a href="#" class="px-3 py-1.5 border border-blue-200 text-blue-600 rounded-lg text-[10px] font-bold hover:bg-blue-50">PDF</a>
<a href="#" class="px-3 py-1.5 border border-slate-200 text-slate-600 rounded-lg text-[10px] font-bold hover:bg-slate-50">Lihat Evaluasi</a>
</div>
</div>
</div>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
const ctxRadar = document.getElementById('radarChart').getContext('2d');
new Chart(ctxRadar, {
type: 'radar',
data: {
labels: ['Kecepatan', 'Teknik', 'Penyelesaian', 'Dribel', 'Stamina', 'Kerja Tim'],
datasets: [{
label: 'Atribut',
data: [85, 75, 80, 88, 70, 65],
backgroundColor: 'rgba(64, 98, 214, 0.2)',
borderColor: '#4062D6',
pointBackgroundColor: '#4062D6',
pointBorderColor: '#fff',
pointHoverBackgroundColor: '#fff',
pointHoverBorderColor: '#4062D6',
borderWidth: 2
}]
},
options: {
responsive: true,
maintainAspectRatio: false,
scales: {
r: {
angleLines: { color: 'rgba(0,0,0,0.05)' },
grid: { color: 'rgba(0,0,0,0.05)' },
pointLabels: { font: { size: 10, family: 'Inter' }, color: '#94a3b8' },
ticks: { display: false, min: 0, max: 100 }
}
},
plugins: { legend: { display: false } }
}
});

const ctxBar = document.getElementById('barChart').getContext('2d');
new Chart(ctxBar, {
type: 'bar',
data: {
labels: ['Mar', 'Apr', 'Mei', 'Jun', 'Jul'],
datasets: [{
label: 'Gol',
data: [0, 2, 1, 3, 2],
backgroundColor: ['#e2e8f0', '#bfdbfe', '#e2e8f0', '#bfdbfe', '#4062D6'],
borderRadius: 4,
barThickness: 24
}]
},
options: {
responsive: true,
maintainAspectRatio: false,
scales: {
y: {
beginAtZero: true,
ticks: { stepSize: 1, font: { size: 10, family: 'Inter' }, color: '#94a3b8' },
grid: { color: 'rgba(0,0,0,0.05)', drawBorder: false }
},
x: {
ticks: { font: { size: 10, family: 'Inter' }, color: '#94a3b8' },
grid: { display: false, drawBorder: false }
}
},
plugins: { legend: { display: false } }
}
});
});
</script>
</div>
@endif
@endsection
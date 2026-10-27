@extends('layouts.app')
@section('title', 'Jadwal Pertandingan - FFA Analytics')
@section('header', 'Jadwal Pertandingan')
@section('subheader', 'Kelola jadwal pertandingan akademi')
@section('content')

<div class="space-y-6">
    <!-- Action Top Bar -->
    <div class="flex justify-end">
        <a href="{{ route('matches.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#4062D6] hover:bg-[#3251b5] text-white text-[12px] font-bold rounded-xl transition-colors shadow-sm active:scale-95">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Jadwal
        </a>
    </div>

    <!-- Match Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        
        <!-- Card 1: UPCOMING -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] p-6 flex flex-col h-full">
            <div class="flex items-center justify-between mb-4">
                <span class="text-[11px] font-bold text-slate-400 tracking-widest uppercase">29 JUN 2026</span>
                <!-- Badge Upcoming -->
                <span class="px-3 py-1 bg-amber-50 border border-amber-200 text-amber-600 rounded text-[10px] font-black tracking-widest uppercase">
                    Upcoming
                </span>
            </div>
            
            <div class="mb-5 flex-1">
                <h3 class="text-xl font-bold text-[#1e293b] mb-1">GhostTim</h3>
                <p class="text-[13px] text-slate-500 font-medium">vs SSB Gamping</p>
            </div>
            
            <div class="space-y-3 mb-6">
                <div class="flex items-center gap-2 text-slate-400">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    <span class="text-[13px] font-medium">Belum ditentukan</span>
                </div>
                <div class="flex items-center gap-2 text-slate-400">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    <span class="text-[13px] font-medium">Formasi: <span class="font-bold text-[#1e293b]">4-3-3</span></span>
                </div>
            </div>
            
            <div class="space-y-3 mt-auto shrink-0">
                <div class="grid grid-cols-2 gap-3">
                    <a href="#" class="flex items-center justify-center px-4 py-2.5 bg-slate-50 hover:bg-slate-100 border border-slate-200 text-[#1e293b] text-[12px] font-bold rounded-xl transition-colors shadow-sm">
                        Line-Up
                    </a>
                    <a href="#" class="flex items-center justify-center gap-2 px-4 py-2.5 bg-slate-50 hover:bg-slate-100 border border-slate-200 text-[#1e293b] text-[12px] font-bold rounded-xl transition-colors shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        Edit Pertandingan
                    </a>
                </div>
                <a href="#" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-[#4062D6] hover:bg-[#3251b5] text-white text-[12px] font-bold rounded-xl transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Lihat Report Pertandingan
                </a>
            </div>
        </div>

        <!-- Card 2: SELESAI -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] p-6 flex flex-col h-full">
            <div class="flex items-center justify-between mb-4">
                <span class="text-[11px] font-bold text-slate-400 tracking-widest uppercase">29 JUN 2026</span>
                <!-- Badge Selesai -->
                <span class="px-3 py-1 bg-emerald-50 border border-emerald-200 text-emerald-500 rounded text-[10px] font-black tracking-widest uppercase">
                    Selesai
                </span>
            </div>
            
            <div class="mb-5 flex-1">
                <h3 class="text-xl font-bold text-[#1e293b] mb-1">GhostTim</h3>
                <p class="text-[13px] text-slate-500 font-medium">vs SSB Kretek</p>
            </div>
            
            <div class="space-y-3 mb-6">
                <div class="flex items-center gap-2 text-slate-400">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    <span class="text-[13px] font-medium">Stadion Kretek</span>
                </div>
                <div class="flex items-center gap-2 text-slate-400">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    <span class="text-[13px] font-medium">Formasi: <span class="font-bold text-[#1e293b]">4-4-2</span></span>
                </div>
            </div>
            
            <div class="space-y-3 mt-auto shrink-0">
                <div class="grid grid-cols-2 gap-3">
                    <a href="#" class="flex items-center justify-center px-4 py-2.5 bg-slate-50 hover:bg-slate-100 border border-slate-200 text-[#1e293b] text-[12px] font-bold rounded-xl transition-colors shadow-sm">
                        Line-Up
                    </a>
                    <a href="#" class="flex items-center justify-center gap-2 px-4 py-2.5 bg-slate-50 hover:bg-slate-100 border border-slate-200 text-[#1e293b] text-[12px] font-bold rounded-xl transition-colors shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        Edit Pertandingan
                    </a>
                </div>
                <a href="#" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-[#4062D6] hover:bg-[#3251b5] text-white text-[12px] font-bold rounded-xl transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Lihat Report Pertandingan
                </a>
            </div>
        </div>

    </div>
</div>

@endsection
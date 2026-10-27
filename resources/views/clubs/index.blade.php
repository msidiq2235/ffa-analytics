@extends('layouts.app')
@section('title', 'Profil Tim - FFA Analytics')
@section('header', 'Profil Tim')
@section('subheader', 'Kelola data klub dan akademi')
@section('content')

@if(session('success'))
<div class="bg-emerald-50 border border-emerald-200 text-emerald-700 p-4 rounded-xl mb-6 font-medium text-[13px]">
    {{ session('success') }}
</div>
@endif

<div class="space-y-6">
    <!-- Action Top Bar -->
    <div class="flex justify-end">
        <a href="{{ route('clubs.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#4062D6] hover:bg-[#3251b5] text-white text-[13px] font-bold rounded-xl transition-colors shadow-sm active:scale-95">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Klub
        </a>
    </div>

    <!-- Clubs Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        
        @forelse($clubs as $club)
        <div class="bg-white rounded-[24px] border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] p-6 flex flex-col items-center text-center h-full">
            <!-- Logo Tim (Sudah diperbaiki ke logo_path) -->
            <div class="w-24 h-24 mb-4 flex items-center justify-center">
                @if($club->logo_path)
                    <img src="{{ asset('storage/' . $club->logo_path) }}" alt="Logo {{ $club->name }}" class="w-full h-full object-contain">
                @else
                    <!-- Placeholder jika di database belum ada logo -->
                    <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center text-slate-400 font-bold text-2xl uppercase">
                        {{ substr($club->name, 0, 2) }}
                    </div>
                @endif
            </div>

            <!-- Nama Tim -->
            <h3 class="text-xl font-bold text-[#1e293b] mb-6">{{ $club->name }}</h3>

            <!-- Tombol Aksi -->
            <div class="w-full flex flex-col gap-3 mt-auto">
                <div class="grid grid-cols-2 gap-3">
                    <!-- Tombol Edit Tim -->
                    <a href="{{ route('clubs.edit', $club->id) }}" class="flex items-center justify-center gap-1.5 py-2.5 bg-[#4062D6] hover:bg-[#3251b5] text-white text-[12px] font-bold rounded-xl transition-colors shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        Edit Tim
                    </a>
                    <!-- Tombol Daftar Squad -->
                    <a href="{{ route('clubs.show', $club->id) }}" class="flex items-center justify-center gap-1.5 py-2.5 bg-[#4062D6] hover:bg-[#3251b5] text-white text-[12px] font-bold rounded-xl transition-colors shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        Daftar Squad
                    </a>
                </div>
                <!-- Tombol Hapus Tim -->
                <form action="{{ route('clubs.destroy', $club->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tim ini? Data pemain di dalamnya mungkin terpengaruh.');" class="w-full">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full flex items-center justify-center gap-1.5 py-2.5 bg-white border border-rose-200 hover:bg-rose-50 text-rose-500 text-[12px] font-bold rounded-xl transition-colors shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        Hapus Tim
                    </button>
                </form>
            </div>
        </div>
        @empty
        <!-- State jika belum ada klub -->
        <div class="col-span-full bg-white p-8 rounded-2xl border border-slate-200 text-center text-slate-500 font-medium">
            Belum ada profil tim/klub yang ditambahkan. Silakan klik tombol "Tambah Klub" untuk memulai.
        </div>
        @endforelse

    </div>
</div>

@endsection
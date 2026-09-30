@extends('layouts.app')

@section('title', 'Team Overview - FFA Analytics')
@section('header', 'Team Overview')
@section('subheader', 'Manajemen daftar klub dan akademi yang terdaftar di sistem')

@section('header_action')
<a href="{{ route('clubs.create') }}" class="bg-orange-500 hover:bg-orange-600 text-white px-5 py-2.5 rounded-lg text-sm font-semibold transition-colors shadow-sm flex items-center gap-2">
    <span>+</span> Tambah Klub
</a>
@endsection

@section('content')
@if(session('success'))
<div class="bg-green-50 border border-green-200 text-green-700 p-4 rounded-xl mb-6">
    {{ session('success') }}
</div>
@endif

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    @forelse($clubs as $club)
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 flex flex-col items-center text-center hover:shadow-md transition-shadow">
        <div class="w-20 h-20 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center mb-4 overflow-hidden shadow-inner">
            @if($club->logo_path)
                <img src="{{ asset('storage/' . $club->logo_path) }}" alt="{{ $club->name }}" class="w-full h-full object-cover">
            @else
                <span class="text-2xl font-bold text-slate-300">{{ substr($club->name, 0, 1) }}</span>
            @endif
        </div>
        <h3 class="text-lg font-bold text-slate-800">{{ $club->name }}</h3>
        <p class="text-sm text-slate-500 mt-1 mb-6">{{ $club->players_count ?? $club->players->count() ?? 0 }} Pemain Terdaftar</p>
        
        <div class="mt-auto w-full flex gap-2">
            <a href="{{ route('clubs.show', $club->id) }}" class="flex-1 flex items-center justify-center gap-2 bg-slate-50 hover:bg-slate-100 text-slate-600 text-sm font-semibold py-2 rounded-lg border border-slate-200 transition-colors">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                Lihat Skuad
            </a>
            
            <a href="{{ route('clubs.edit', $club->id) }}" class="w-10 h-10 flex items-center justify-center bg-orange-50 hover:bg-orange-100 text-orange-500 rounded-lg border border-orange-100 transition-colors shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            </a>
            
            <form action="{{ route('clubs.destroy', $club->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus klub ini? Semua pemain di dalamnya juga akan terhapus!');" class="shrink-0">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-10 h-10 flex items-center justify-center bg-red-50 hover:bg-red-100 text-red-500 rounded-lg border border-red-100 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </button>
            </form>
        </div>
    </div>
    @empty
    <div class="col-span-full bg-white rounded-2xl border border-slate-100 shadow-sm p-12 text-center">
        <p class="text-slate-500 font-medium">Belum ada klub yang terdaftar.</p>
    </div>
    @endforelse
</div>
@endsection
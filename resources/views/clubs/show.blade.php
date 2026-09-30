@extends('layouts.app')
@section('title', 'Skuad Tim - FFA Analytics')
@section('header', 'Skuad Resmi Tim')
@section('subheader', 'Daftar pemain yang terdaftar di dalam klub ' . $club->name)
@section('content')
<div class="max-w-4xl space-y-6">
    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex justify-between items-center relative overflow-hidden">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-orange-50 rounded-full opacity-50"></div>
        <div class="flex items-center gap-4 relative z-10">
            <div class="w-14 h-14 rounded-xl bg-linear-to-br from-orange-500 to-red-500 flex items-center justify-center text-white shadow-lg shadow-orange-500/20 shrink-0">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
            </div>
            <div>
                <h2 class="text-xl font-black text-slate-800 tracking-tight">{{ $club->name }}</h2>
                <p class="text-xs font-bold text-slate-400 tracking-wider uppercase mt-0.5">Total Pemain Terikat: {{ $club->players->count() }}</p>
            </div>
        </div>
        <a href="{{ route('clubs.index') }}" class="px-4 py-2 bg-slate-100 text-slate-600 font-bold text-xs rounded-lg border border-slate-200 hover:bg-slate-200 transition-all shadow-sm">
            Kembali ke Daftar
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="bg-slate-50 border-b border-slate-100 px-6 py-4">
            <h3 class="text-sm font-black text-slate-700 uppercase tracking-widest">Daftar Anggota Skuad</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-white border-b border-slate-100 text-slate-400 font-bold text-xs tracking-wider uppercase">
                    <tr>
                        <th class="px-6 py-4 w-28">No. Punggung</th>
                        <th class="px-6 py-4">Nama Lengkap Pemain</th>
                        <th class="px-6 py-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($club->players as $player)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 font-mono font-bold text-slate-500">
                            #{{ $player->jersey_number ?? '-' }}
                        </td>
                        <td class="px-6 py-4 font-bold text-slate-800">
                            {{ $player->name }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-3 py-1 text-[10px] font-black uppercase tracking-wider rounded-full {{ $player->status === 'Aktif' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-slate-50 text-slate-600 border border-slate-200' }}">
                                {{ $player->status ?? 'Aktif' }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-10 text-center text-slate-400 font-medium">Belum ada pemain yang terdaftar di dalam klub ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
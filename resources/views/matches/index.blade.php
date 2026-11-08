@extends('layouts.app')
@section('title', 'Jadwal Pertandingan - FFA Analytics')
@section('header', 'Jadwal Pertandingan')
@section('subheader', 'Kelola jadwal pertandingan akademi. Tambahkan, edit, atau hapus jadwal pertandingan sesuai kebutuhan.')
@section('content')

<!-- Tambahkan CDN Lucide Icons jika belum ada di layouts/app.blade.php -->
<script src="https://unpkg.com/lucide@latest"></script>

@php
    $realRole = auth()->user()->role ?? 'Super Admin';
    $activeRole = session('active_role', $realRole);
@endphp

<div class="space-y-6">
    <!-- Action Top Bar: pilihan tampilan (Tabel / Card) + tombol tambah -->
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div class="inline-flex p-1 bg-white border border-slate-200 rounded-xl shadow-sm">
            <a href="{{ route('matches.index', ['view' => 'table']) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-[12px] font-bold transition-colors {{ $view === 'table' ? 'bg-[#4062D6] text-white shadow-sm' : 'text-slate-500 hover:bg-slate-50' }}">
                <i data-lucide="list" class="w-4 h-4"></i>
                <span>Tabel</span>
            </a>
            <a href="{{ route('matches.index', ['view' => 'card']) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-[12px] font-bold transition-colors {{ $view === 'card' ? 'bg-[#4062D6] text-white shadow-sm' : 'text-slate-500 hover:bg-slate-50' }}">
                <i data-lucide="layout-grid" class="w-4 h-4"></i>
                <span>Card</span>
            </a>
        </div>

        <!-- Tombol tambah (tidak untuk Analis) -->
        @if($activeRole !== 'Analis')
        <a href="{{ route('matches.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#4062D6] hover:bg-[#3251b5] text-white text-[12px] font-bold rounded-xl transition-colors shadow-sm active:scale-95">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>Tambah Jadwal</span>
        </a>
        @endif
    </div>

    @if($view === 'table')
    <!-- Tampilan Tabel -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[860px]">
                <thead>
                    <tr class="border-b border-slate-100 bg-white">
                        <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest w-[16%]">Tanggal &amp; Waktu</th>
                        <th class="py-4 px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest w-[24%]">Pertandingan</th>
                        <th class="py-4 px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Lokasi</th>
                        <th class="py-4 px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Formasi</th>
                        <th class="py-4 px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Status</th>
                        <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($matches as $match)
                    @php $matchDate = \Carbon\Carbon::parse($match->match_date); @endphp
                    <tr class="hover:bg-[#F8FAFC] transition-colors {{ $match->status == 'Live' ? 'bg-rose-50/30' : '' }}">
                        <td class="py-4 px-6">
                            <p class="text-[13px] font-bold text-[#1e293b]">{{ $matchDate->format('d M Y') }}</p>
                            <p class="text-[11px] font-medium text-slate-400 mt-0.5">
                                {{ $matchDate->format('H:i') }} WIB
                                @if($match->status !== 'Selesai' && $matchDate->isFuture())
                                    · <span class="text-[#4062D6] font-bold">{{ $matchDate->diffForHumans() }}</span>
                                @endif
                            </p>
                        </td>
                        <td class="py-4 px-4">
                            <p class="text-[13px] font-bold text-[#1e293b]">{{ $match->title ?? 'Nama Tim' }}</p>
                            <p class="text-[11px] font-medium text-slate-500 mt-0.5">vs {{ $match->opponent ?? '-' }}</p>
                        </td>
                        <td class="py-4 px-4">
                            <div class="flex items-center gap-1.5 text-slate-500">
                                <i data-lucide="map-pin" class="w-4 h-4 shrink-0 text-slate-400"></i>
                                <span class="text-[12px] font-medium">{{ $match->venue ?? 'Belum ditentukan' }}</span>
                            </div>
                        </td>
                        <td class="py-4 px-4 text-center">
                            <span class="inline-block px-2.5 py-1 bg-slate-50 border border-slate-200 rounded-lg text-[11px] font-bold text-[#1e293b]">{{ $match->formation ?? '4-3-3' }}</span>
                        </td>
                        <td class="py-4 px-4 text-center">
                            @if($match->status == 'Live')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-rose-50 border border-rose-200 text-rose-500 rounded text-[10px] font-black tracking-widest uppercase">
                                    <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span> Live
                                </span>
                            @elseif($match->status == 'Selesai')
                                <span class="inline-block px-3 py-1 bg-emerald-50 border border-emerald-200 text-emerald-500 rounded text-[10px] font-black tracking-widest uppercase">Selesai</span>
                            @else
                                <span class="inline-block px-3 py-1 bg-amber-50 border border-amber-200 text-amber-600 rounded text-[10px] font-black tracking-widest uppercase">Upcoming</span>
                            @endif
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex flex-wrap items-center justify-end gap-2">
                                @if($activeRole === 'Analis')
                                    <a href="{{ route('matches.vector', $match->id) }}" class="px-3 py-1.5 bg-white hover:bg-slate-50 border border-indigo-200 text-indigo-600 text-[11px] font-bold rounded-lg transition-colors">Vektor</a>
                                    <a href="{{ route('matches.live', $match->id) }}" class="px-3 py-1.5 bg-white hover:bg-slate-50 border border-amber-200 text-amber-600 text-[11px] font-bold rounded-lg transition-colors">Live Analytics</a>
                                    <a href="{{ route('matches.lineup', $match->id) }}" class="px-3 py-1.5 bg-white hover:bg-slate-50 border border-slate-200 text-[#1e293b] text-[11px] font-bold rounded-lg transition-colors">Line-Up</a>
                                    <a href="{{ route('reports.match.show', $match->id) }}" class="px-3 py-1.5 bg-[#4062D6] hover:bg-[#3251b5] text-white text-[11px] font-bold rounded-lg transition-colors">Report</a>
                                @else
                                    <a href="{{ route('matches.lineup', $match->id) }}" class="px-3 py-1.5 bg-slate-50 hover:bg-slate-100 border border-slate-200 text-[#1e293b] text-[11px] font-bold rounded-lg transition-colors">Line-Up</a>
                                    <a href="{{ route('matches.edit', $match->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-50 hover:bg-slate-100 border border-slate-200 text-[#1e293b] text-[11px] font-bold rounded-lg transition-colors">
                                        <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                                        <span>Edit</span>
                                    </a>
                                    <a href="{{ route('reports.match.show', $match->id) }}" class="px-3 py-1.5 bg-[#4062D6] hover:bg-[#3251b5] text-white text-[11px] font-bold rounded-lg transition-colors">Report</a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-10 text-center text-slate-500 font-medium">Belum ada jadwal pertandingan yang ditambahkan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @else
    <!-- Match Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        
        @forelse($matches as $match)
        <div class="bg-white rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] p-6 flex flex-col h-full">
            <div class="flex items-center justify-between mb-4">
                <span class="text-[11px] font-bold text-slate-400 tracking-widest uppercase">
                    {{ \Carbon\Carbon::parse($match->match_date)->format('d M Y') }}
                </span>
                
                <!-- BADGE STATUS DINAMIS -->
                @if($match->status == 'Live')
                    <span class="px-3 py-1 bg-rose-50 border border-rose-200 text-rose-500 rounded text-[10px] font-black tracking-widest uppercase flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span> Live
                    </span>
                @elseif($match->status == 'Selesai')
                    <span class="px-3 py-1 bg-emerald-50 border border-emerald-200 text-emerald-500 rounded text-[10px] font-black tracking-widest uppercase">
                        Selesai
                    </span>
                @else
                    <span class="px-3 py-1 bg-amber-50 border border-amber-200 text-amber-600 rounded text-[10px] font-black tracking-widest uppercase">
                        Upcoming
                    </span>
                @endif
            </div>
            
            <div class="mb-5 flex-1">
                <h3 class="text-xl font-bold text-[#1e293b] mb-1">{{ $match->title ?? 'Nama Tim' }}</h3>
                <p class="text-[13px] text-slate-500 font-medium">vs {{ $match->opponent }}</p>
            </div>
            
            <div class="space-y-3 mb-6">
                <div class="flex items-center gap-2 text-slate-400">
                    <i data-lucide="map-pin" class="w-4 h-4 shrink-0"></i>
                    <span class="text-[13px] font-medium">{{ $match->venue ?? 'Belum ditentukan' }}</span>
                </div>
                <div class="flex items-center gap-2 text-slate-400">
                    <i data-lucide="grid" class="w-4 h-4 shrink-0"></i>
                    <span class="text-[13px] font-medium">Formasi: <span class="font-bold text-[#1e293b]">{{ $match->formation ?? '4-3-3' }}</span></span>
                </div>
            </div>
            
            <!-- TOMBOL AKSI BERDASARKAN ROLE -->
            <div class="space-y-3 mt-auto shrink-0">
                @if($activeRole === 'Analis')
                    <div class="grid grid-cols-2 gap-3">
                        <a href="{{ route('matches.vector', $match->id) }}" class="flex items-center justify-center gap-1.5 px-4 py-2.5 bg-white hover:bg-slate-50 border border-indigo-200 text-indigo-600 text-[12px] font-bold rounded-xl transition-colors shadow-sm">
                            Vektor
                        </a>
                        <a href="{{ route('matches.live', $match->id) }}" class="flex items-center justify-center gap-1.5 px-4 py-2.5 bg-white hover:bg-slate-50 border border-amber-200 text-amber-600 text-[12px] font-bold rounded-xl transition-colors shadow-sm">
                            Live Analytics
                        </a>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <a href="{{ route('reports.match.show', $match->id) }}" class="flex items-center justify-center gap-1.5 px-3 py-2.5 bg-[#4062D6] hover:bg-[#3251b5] text-white text-[11px] font-bold rounded-xl transition-colors shadow-sm text-center">
                            Lihat Report Pertandingan
                        </a>
                        <a href="{{ route('matches.lineup', $match->id) }}" class="flex items-center justify-center px-4 py-2.5 bg-white hover:bg-slate-50 border border-slate-200 text-[#1e293b] text-[12px] font-bold rounded-xl transition-colors shadow-sm">
                            Line-Up
                        </a>
                    </div>
                @else
                    <div class="grid grid-cols-2 gap-3">
                        <a href="{{ route('matches.lineup', $match->id) }}" class="flex items-center justify-center px-4 py-2.5 bg-slate-50 hover:bg-slate-100 border border-slate-200 text-[#1e293b] text-[12px] font-bold rounded-xl transition-colors shadow-sm">
                            Line-Up
                        </a>
                        <a href="{{ route('matches.edit', $match->id) }}" class="flex items-center justify-center gap-2 px-4 py-2.5 bg-slate-50 hover:bg-slate-100 border border-slate-200 text-[#1e293b] text-[12px] font-bold rounded-xl transition-colors shadow-sm">
                            <i data-lucide="pencil" class="w-4 h-4"></i>
                            <span>Edit Pertandingan</span>
                        </a>
                    </div>
                    <a href="{{ route('reports.match.show', $match->id) }}" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-[#4062D6] hover:bg-[#3251b5] text-white text-[12px] font-bold rounded-xl transition-colors shadow-sm">
                        <i data-lucide="file-text" class="w-4 h-4"></i>
                        <span>Lihat Report Pertandingan</span>
                    </a>
                @endif
            </div>
        </div>
        @empty
        <div class="col-span-full bg-white p-8 rounded-2xl border border-slate-200 text-center text-slate-500 font-medium">
            Belum ada jadwal pertandingan yang ditambahkan.
        </div>
        @endforelse

    </div>
    @endif

    <!-- Pagination -->
    @if($matches->total() > 0)
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
        <p class="text-[12px] font-medium text-slate-500">
            Menampilkan <span class="font-bold text-[#1e293b]">{{ $matches->firstItem() }}</span>–<span class="font-bold text-[#1e293b]">{{ $matches->lastItem() }}</span>
            dari <span class="font-bold text-[#1e293b]">{{ $matches->total() }}</span> jadwal
        </p>

        @if($matches->hasPages())
        @php
            $current = $matches->currentPage();
            $last = $matches->lastPage();
            $start = max(1, $current - 2);
            $end = min($last, $current + 2);
            $btn = 'min-w-[36px] h-9 px-3 inline-flex items-center justify-center rounded-lg border text-[12px] font-bold transition-colors';
        @endphp
        <nav class="flex items-center gap-1.5" aria-label="Pagination">
            @if($matches->onFirstPage())
                <span class="{{ $btn }} bg-slate-50 border-slate-200 text-slate-300 cursor-not-allowed">&lsaquo;</span>
            @else
                <a href="{{ $matches->previousPageUrl() }}" class="{{ $btn }} bg-white border-slate-200 text-slate-600 hover:bg-slate-50">&lsaquo;</a>
            @endif

            @if($start > 1)
                <a href="{{ $matches->url(1) }}" class="{{ $btn }} bg-white border-slate-200 text-slate-600 hover:bg-slate-50">1</a>
                @if($start > 2)<span class="px-1 text-slate-400">…</span>@endif
            @endif

            @for($page = $start; $page <= $end; $page++)
                @if($page === $current)
                    <span class="{{ $btn }} bg-[#4062D6] border-[#4062D6] text-white shadow-sm">{{ $page }}</span>
                @else
                    <a href="{{ $matches->url($page) }}" class="{{ $btn }} bg-white border-slate-200 text-slate-600 hover:bg-slate-50">{{ $page }}</a>
                @endif
            @endfor

            @if($end < $last)
                @if($end < $last - 1)<span class="px-1 text-slate-400">…</span>@endif
                <a href="{{ $matches->url($last) }}" class="{{ $btn }} bg-white border-slate-200 text-slate-600 hover:bg-slate-50">{{ $last }}</a>
            @endif

            @if($matches->hasMorePages())
                <a href="{{ $matches->nextPageUrl() }}" class="{{ $btn }} bg-white border-slate-200 text-slate-600 hover:bg-slate-50">&rsaquo;</a>
            @else
                <span class="{{ $btn }} bg-slate-50 border-slate-200 text-slate-300 cursor-not-allowed">&rsaquo;</span>
            @endif
        </nav>
        @endif
    </div>
    @endif
</div>

<!-- Inisialisasi Lucide Icons -->
<script>
    lucide.createIcons();
</script>

@endsection
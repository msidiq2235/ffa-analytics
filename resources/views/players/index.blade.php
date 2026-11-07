@extends('layouts.app')
@section('title', 'Database Pemain - FFA Analytics')
@section('header', 'Database Pemain')
@section('subheader', 'Kelola data dan profil pemain di sini. Tambahkan, edit, atau hapus data pemain sesuai kebutuhan.')
@section('content')

@php
    // ===== Ikon Lucide (inline SVG, tidak butuh CDN) =====
    $icons = [
        'search'         => '<circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>',
        'plus'           => '<path d="M5 12h14"/><path d="M12 5v14"/>',
        'user-plus'      => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/>',
        'upload'         => '<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" x2="12" y1="3" y2="15"/>',
        'square-pen'     => '<path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.375 2.625a1 1 0 0 1 3 3l-9.013 9.014a2 2 0 0 1-.853.505l-2.873.84a.5.5 0 0 1-.62-.62l.84-2.873a2 2 0 0 1 .506-.852z"/>',
        'trash-2'        => '<path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/>',
        'x'              => '<path d="M18 6 6 18"/><path d="m6 6 12 12"/>',
        'clipboard-list' => '<rect width="8" height="4" x="8" y="2" rx="1" ry="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="M12 11h4"/><path d="M12 16h4"/><path d="M8 11h.01"/><path d="M8 16h.01"/>',
        'calendar'       => '<path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/>',
        'map-pin'        => '<path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/>',
        'chevron-down'   => '<path d="m6 9 6 6 6-6"/>',
        'triangle-alert' => '<path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"/><path d="M12 9v4"/><path d="M12 17h.01"/>',
        'shield-check'   => '<path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/>',
        'users'          => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
        'rotate-ccw'     => '<path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/>',
    ];
    $icon = fn($name, $class = 'w-4 h-4') => '<svg xmlns="http://www.w3.org/2000/svg" class="' . $class . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">' . $icons[$name] . '</svg>';

    $hasFilter = request()->hasAny(['name', 'position', 'jersey', 'club']) && collect(request()->only(['name', 'position', 'jersey', 'club']))->filter()->isNotEmpty();
    $positions = ['ST', 'AM', 'RW', 'LW', 'CM', 'DM', 'RB', 'CB', 'LB', 'GK'];
    $quickErrors = old('_quick') && $errors->any();
@endphp

<div class="space-y-6">
    <!-- Filter & Action Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] p-6">
        <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-4">Filter Pemain</h3>

        <form method="GET" action="{{ route('players.index') }}" class="flex flex-col lg:flex-row items-end lg:items-center justify-between gap-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 flex-1 w-full">
                <!-- Nama Pemain -->
                <div>
                    <label for="name" class="block text-[11px] font-bold text-slate-400 mb-1">Nama Pemain</label>
                    <input type="text" id="name" name="name" value="{{ request('name') }}" placeholder="Cari nama..."
                           class="w-full px-3.5 py-2 bg-white border border-slate-200 rounded-xl text-[12px] text-slate-700 placeholder-slate-400 focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] transition-all shadow-sm">
                </div>

                <!-- Posisi -->
                <div>
                    <label for="position" class="block text-[11px] font-bold text-slate-400 mb-1">Posisi</label>
                    <div class="relative">
                        <select id="position" name="position"
                                class="w-full px-3.5 py-2 bg-white border border-slate-200 rounded-xl text-[12px] text-slate-700 appearance-none focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] transition-all shadow-sm cursor-pointer">
                            <option value="">Semua Posisi</option>
                            @foreach($positions as $pos)
                                <option value="{{ $pos }}" {{ request('position') == $pos ? 'selected' : '' }}>{{ $pos }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-400">{!! $icon('chevron-down', 'w-3.5 h-3.5') !!}</div>
                    </div>
                </div>

                <!-- Nomor Punggung -->
                <div>
                    <label for="jersey" class="block text-[11px] font-bold text-slate-400 mb-1">Nomor Punggung</label>
                    <input type="text" id="jersey" name="jersey" value="{{ request('jersey') }}" placeholder="Contoh: 10"
                           class="w-full px-3.5 py-2 bg-white border border-slate-200 rounded-xl text-[12px] text-slate-700 placeholder-slate-400 focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] transition-all shadow-sm">
                </div>

                <!-- Tim Asal -->
                <div>
                    <label for="club" class="block text-[11px] font-bold text-slate-400 mb-1">Tim Asal</label>
                    <div class="relative">
                        <select id="club" name="club"
                                class="w-full px-3.5 py-2 bg-white border border-slate-200 rounded-xl text-[12px] text-slate-700 appearance-none focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] transition-all shadow-sm cursor-pointer">
                            <option value="">Semua Tim</option>
                            @foreach($clubs ?? [] as $c)
                                <option value="{{ $c->name }}" {{ request('club') == $c->name ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-400">{!! $icon('chevron-down', 'w-3.5 h-3.5') !!}</div>
                    </div>
                </div>
            </div>

            <!-- Tombol Cari & Action -->
            <div class="flex flex-wrap items-center gap-2 w-full lg:w-auto shrink-0 pt-2 lg:pt-0">
                <button type="submit" class="px-4 py-2 bg-[#4062D6] hover:bg-[#3251b5] text-white rounded-xl text-[12px] font-bold flex items-center gap-1.5 transition-colors shadow-sm active:scale-95">
                    {!! $icon('search', 'w-3.5 h-3.5') !!}
                    Cari
                </button>
                @if($hasFilter)
                <a href="{{ route('players.index') }}" class="px-4 py-2 bg-white hover:bg-slate-50 border border-slate-200 text-slate-600 rounded-xl text-[12px] font-bold flex items-center gap-1.5 transition-colors shadow-sm">
                    {!! $icon('rotate-ccw', 'w-3.5 h-3.5') !!}
                    Reset
                </a>
                @endif
                <a href="{{ route('players.create', array_filter(['club_id' => $selectedClub->id ?? null])) }}" class="px-4 py-2 bg-[#4062D6] hover:bg-[#3251b5] text-white rounded-xl text-[12px] font-bold flex items-center gap-1.5 transition-colors shadow-sm active:scale-95">
                    {!! $icon('plus', 'w-3.5 h-3.5') !!}
                    Tambah Pemain
                </a>
                <a href="#" class="px-4 py-2 bg-[#4062D6] hover:bg-[#3251b5] text-white rounded-xl text-[12px] font-bold flex items-center gap-1.5 transition-colors shadow-sm active:scale-95">
                    {!! $icon('upload', 'w-3.5 h-3.5') !!}
                    Impor Pemain
                </a>
            </div>
        </form>
    </div>

    <!-- Daftar Squad + Shortcut Tambah Pemain -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-blue-50/60 border border-blue-100 rounded-2xl px-5 py-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-white border border-blue-100 text-[#4062D6] flex items-center justify-center shrink-0">{!! $icon('users', 'w-5 h-5') !!}</div>
            <div>
                <h3 class="text-[13px] font-bold text-[#1e293b]">
                    {{ $selectedClub ? 'Daftar Squad ' . $selectedClub->name : 'Daftar Squad Semua Tim' }}
                </h3>
                <p class="text-[11px] text-slate-500 mt-0.5">{{ count($players ?? []) }} pemain ditampilkan{{ $selectedClub ? '' : ' · pilih Tim Asal untuk melihat satu squad' }}</p>
            </div>
        </div>
        <button type="button" onclick="openModal('quick-add-modal')" class="px-4 py-2.5 bg-white hover:bg-blue-50 border border-blue-200 text-[#4062D6] rounded-xl text-[12px] font-bold flex items-center justify-center gap-2 transition-colors shadow-sm active:scale-95">
            {!! $icon('user-plus', 'w-4 h-4') !!}
            {{ $selectedClub ? 'Tambah Pemain ke Squad' : 'Tambah Cepat' }}
            <kbd class="hidden sm:inline-block px-1.5 py-0.5 bg-slate-100 border border-slate-200 rounded text-[10px] font-bold text-slate-500">N</kbd>
        </button>
    </div>

    <!-- Tabel Database Pemain -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] overflow-x-auto">
        <table class="w-full text-left border-collapse min-w-[1000px]">
            <thead>
                <tr class="border-b border-slate-100">
                    <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest w-[8%]">No. Pung.</th>
                    <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest w-[20%]">Nama Pemain</th>
                    <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest w-[8%]">Posisi</th>
                    <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest w-[14%]">Klub Asal</th>
                    <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest w-[12%]">Klub Kedua</th>
                    <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest w-[18%]">Line-up</th>
                    <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest w-[10%]">Status</th>
                    <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest w-[10%] text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($players ?? [] as $player)
                @php
                    $pl = collect($lineups[$player->id] ?? []);
                    $starterCount = $pl->where('is_starter', true)->count();
                    $benchCount = $pl->count() - $starterCount;
                    $status = $player->status ?? 'Aktif';
                @endphp
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="py-4 px-6">
                        <span class="inline-flex items-center justify-center min-w-[2rem] h-8 px-1.5 rounded-lg bg-[#1e293b] text-white text-[11px] font-bold">
                            #{{ $player->jersey_number ?? '-' }}
                        </span>
                    </td>
                    <td class="py-4 px-6 text-[13px] font-bold text-[#1e293b]">{{ $player->name }}</td>
                    <td class="py-4 px-6 text-[12px] font-bold text-slate-600">{{ $player->position ?? '-' }}</td>
                    <td class="py-4 px-6 text-[12px] font-medium text-slate-600">{{ $player->club->name ?? '-' }}</td>
                    <td class="py-4 px-6 text-[12px] font-medium text-slate-600">{{ $player->second_club ?? '-' }}</td>

                    <!-- Informasi Line-up -->
                    <td class="py-4 px-6">
                        @if($pl->isEmpty())
                            <span class="text-[11px] font-medium text-slate-400">Belum masuk line-up</span>
                        @else
                            <button type="button"
                                    data-player-id="{{ $player->id }}" data-name="{{ $player->name }}"
                                    onclick="openLineupModal(this)"
                                    aria-label="Lihat riwayat line-up {{ $player->name }}"
                                    class="group relative inline-flex items-center gap-1.5 flex-wrap text-left focus:outline-none">
                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-blue-50 border border-blue-100 text-[#4062D6] rounded-lg text-[10px] font-bold group-hover:bg-blue-100 transition-colors">
                                    {!! $icon('clipboard-list', 'w-3 h-3') !!} {{ $pl->count() }} line-up
                                </span>
                                @if($starterCount)
                                    <span class="px-2 py-1 bg-emerald-50 border border-emerald-100 text-emerald-600 rounded-lg text-[10px] font-bold">Starter {{ $starterCount }}</span>
                                @endif
                                @if($benchCount)
                                    <span class="px-2 py-1 bg-slate-100 border border-slate-200 text-slate-500 rounded-lg text-[10px] font-bold">Cadangan {{ $benchCount }}</span>
                                @endif
                                <span class="pointer-events-none absolute -top-8 left-0 whitespace-nowrap rounded-md bg-slate-800 px-2 py-1 text-[11px] font-medium text-white opacity-0 group-hover:opacity-100 group-focus-visible:opacity-100 transition-opacity z-20" role="tooltip">Klik untuk detail line-up</span>
                            </button>
                        @endif
                    </td>

                    <td class="py-4 px-6">
                        @if($status == 'Aktif')
                            <span class="inline-block px-3 py-1 bg-emerald-50 border border-emerald-100 text-emerald-600 rounded-full text-[11px] font-bold">Aktif</span>
                        @elseif($status == 'Cedera')
                            <span class="inline-block px-3 py-1 bg-amber-50 border border-amber-100 text-amber-600 rounded-full text-[11px] font-bold">Cedera</span>
                        @else
                            <span class="inline-block px-3 py-1 bg-slate-100 border border-slate-200 text-slate-500 rounded-full text-[11px] font-bold">{{ $status }}</span>
                        @endif
                    </td>

                    <td class="py-4 px-6 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('players.edit', $player->id) }}" aria-label="Edit data {{ $player->name }}"
                               class="group relative w-8 h-8 rounded-lg bg-slate-50 hover:bg-slate-100 border border-slate-100 flex items-center justify-center text-slate-400 hover:text-[#4062D6] transition-colors shadow-sm">
                                {!! $icon('square-pen', 'w-4 h-4') !!}
                                <span class="pointer-events-none absolute -top-8 left-1/2 -translate-x-1/2 whitespace-nowrap rounded-md bg-slate-800 px-2 py-1 text-[11px] font-medium text-white opacity-0 group-hover:opacity-100 group-focus-visible:opacity-100 transition-opacity z-20" role="tooltip">Edit</span>
                            </a>
                            <button type="button"
                                    data-action="{{ route('players.destroy', $player->id) }}" data-name="{{ $player->name }}"
                                    onclick="openDeleteModal(this)" aria-label="Hapus data {{ $player->name }}"
                                    class="group relative w-8 h-8 rounded-lg bg-slate-50 hover:bg-rose-50 border border-slate-100 flex items-center justify-center text-slate-400 hover:text-rose-500 transition-colors shadow-sm">
                                {!! $icon('trash-2', 'w-4 h-4') !!}
                                <span class="pointer-events-none absolute -top-8 left-1/2 -translate-x-1/2 whitespace-nowrap rounded-md bg-slate-800 px-2 py-1 text-[11px] font-medium text-white opacity-0 group-hover:opacity-100 group-focus-visible:opacity-100 transition-opacity z-20" role="tooltip">Hapus</span>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="py-10 text-center text-slate-400 text-[13px] font-medium">
                        Belum ada data pemain di database. Silakan tambahkan pemain baru.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- ================= POP-UP ================= -->

<!-- Pop-up: Tambah Cepat Pemain (shortcut di daftar squad) -->
<div id="quick-add-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4" role="dialog" aria-modal="true" aria-labelledby="quick-add-title">
    <div class="absolute inset-0 bg-slate-900/50" data-close-modal></div>
    <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <div class="flex items-center gap-2">
                <span class="text-[#4062D6]">{!! $icon('user-plus', 'w-5 h-5') !!}</span>
                <h3 id="quick-add-title" class="text-[14px] font-bold text-[#1e293b]">Tambah Pemain ke Squad</h3>
            </div>
            <button type="button" data-close-modal aria-label="Tutup" class="w-8 h-8 rounded-lg hover:bg-slate-100 text-slate-400 flex items-center justify-center">{!! $icon('x', 'w-4 h-4') !!}</button>
        </div>

        <form action="{{ route('players.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <input type="hidden" name="_quick" value="1">

            @if($quickErrors)
                <div class="flex gap-2 p-3 rounded-xl bg-rose-50 border border-rose-100 text-rose-600 text-[12px]">
                    {!! $icon('triangle-alert', 'w-4 h-4 shrink-0 mt-0.5') !!}
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <div>
                <label for="qa_name" class="block text-[12px] font-bold text-slate-700 mb-1.5">Nama Pemain</label>
                <input type="text" id="qa_name" name="name" value="{{ old('_quick') ? old('name') : '' }}" required placeholder="Nama Lengkap Pemain"
                       class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 placeholder-slate-400 focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] shadow-sm">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="qa_position" class="block text-[12px] font-bold text-slate-700 mb-1.5">Posisi</label>
                    <div class="relative">
                        <select id="qa_position" name="position" required class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 appearance-none focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] shadow-sm cursor-pointer">
                            <option value="" disabled {{ old('_quick') && old('position') ? '' : 'selected' }}>Pilih Posisi</option>
                            @foreach($positions as $pos)
                                <option value="{{ $pos }}" {{ old('_quick') && old('position') == $pos ? 'selected' : '' }}>{{ $pos }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-400">{!! $icon('chevron-down', 'w-4 h-4') !!}</div>
                    </div>
                </div>
                <div>
                    <label for="qa_jersey" class="block text-[12px] font-bold text-slate-700 mb-1.5">Nomor Punggung</label>
                    <input type="number" id="qa_jersey" name="jersey_number" value="{{ old('_quick') ? old('jersey_number') : '' }}" placeholder="Contoh: 10"
                           class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 placeholder-slate-400 focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] shadow-sm">
                </div>
            </div>

            <div>
                <label for="qa_club" class="block text-[12px] font-bold text-slate-700 mb-1.5">Tim / Klub Utama</label>
                <div class="relative">
                    <select id="qa_club" name="club_id" required class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 appearance-none focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] shadow-sm cursor-pointer">
                        <option value="" disabled {{ ($selectedClub || old('club_id')) ? '' : 'selected' }}>Pilih Klub Utama</option>
                        @foreach($clubs ?? [] as $c)
                            <option value="{{ $c->id }}" {{ (old('_quick') ? old('club_id') : ($selectedClub->id ?? null)) == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-400">{!! $icon('chevron-down', 'w-4 h-4') !!}</div>
                </div>
            </div>

            <div class="flex items-center gap-2 px-3.5 py-2.5 bg-emerald-50 border border-emerald-100 rounded-xl text-[12px] text-emerald-700">
                {!! $icon('shield-check', 'w-4 h-4 shrink-0') !!}
                Status pemain baru otomatis <span class="font-bold">Aktif</span>.
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <button type="button" data-close-modal class="px-5 py-2.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-[13px] font-bold rounded-xl transition-colors shadow-sm">Batal</button>
                <button type="submit" class="px-5 py-2.5 bg-[#4062D6] hover:bg-[#3251b5] text-white text-[13px] font-bold rounded-xl transition-colors shadow-sm active:scale-95 flex items-center gap-2">
                    {!! $icon('plus', 'w-4 h-4') !!} Tambah Pemain
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Pop-up: Konfirmasi Hapus -->
<div id="delete-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4" role="alertdialog" aria-modal="true" aria-labelledby="delete-title" aria-describedby="delete-desc">
    <div class="absolute inset-0 bg-slate-900/50" data-close-modal></div>
    <div class="relative w-full max-w-md bg-white rounded-2xl shadow-xl border border-slate-200 p-6">
        <div class="flex items-start gap-4">
            <div class="w-11 h-11 rounded-full bg-rose-50 border border-rose-100 text-rose-500 flex items-center justify-center shrink-0">{!! $icon('triangle-alert', 'w-5 h-5') !!}</div>
            <div>
                <h3 id="delete-title" class="text-[14px] font-bold text-[#1e293b]">Hapus data pemain?</h3>
                <p id="delete-desc" class="text-[12px] text-slate-500 mt-1 leading-relaxed">Data pemain <span id="delete-name" class="font-bold text-[#1e293b]"></span> akan dihapus permanen dan tidak dapat dikembalikan.</p>
            </div>
        </div>
        <form id="delete-form" method="POST" class="flex items-center justify-end gap-3 mt-6">
            @csrf
            @method('DELETE')
            <button type="button" data-close-modal class="px-5 py-2.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-[13px] font-bold rounded-xl transition-colors shadow-sm">Batal</button>
            <button type="submit" class="px-5 py-2.5 bg-rose-500 hover:bg-rose-600 text-white text-[13px] font-bold rounded-xl transition-colors shadow-sm active:scale-95 flex items-center gap-2">
                {!! $icon('trash-2', 'w-4 h-4') !!} Ya, Hapus
            </button>
        </form>
    </div>
</div>

<!-- Pop-up: Detail Line-up Pemain -->
<div id="lineup-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4" role="dialog" aria-modal="true" aria-labelledby="lineup-title">
    <div class="absolute inset-0 bg-slate-900/50" data-close-modal></div>
    <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <div class="flex items-center gap-2">
                <span class="text-[#4062D6]">{!! $icon('clipboard-list', 'w-5 h-5') !!}</span>
                <h3 id="lineup-title" class="text-[14px] font-bold text-[#1e293b]">Riwayat Line-up</h3>
            </div>
            <button type="button" data-close-modal aria-label="Tutup" class="w-8 h-8 rounded-lg hover:bg-slate-100 text-slate-400 flex items-center justify-center">{!! $icon('x', 'w-4 h-4') !!}</button>
        </div>
        <div class="px-6 pt-4 pb-2 text-[12px] text-slate-500">Pemain: <span id="lineup-player" class="font-bold text-[#1e293b]"></span></div>
        <ul id="lineup-list" class="px-6 pb-6 space-y-3 max-h-[60vh] overflow-y-auto"></ul>
    </div>
</div>

<script>
(function () {
    const lineupData = @json($lineups ?? []);
    const iconCalendar = {!! json_encode($icon('calendar', 'w-3.5 h-3.5')) !!};
    const iconPin = {!! json_encode($icon('map-pin', 'w-3.5 h-3.5')) !!};
    let lastFocused = null;

    window.openModal = function (id) {
        const el = document.getElementById(id);
        if (!el) return;
        lastFocused = document.activeElement;
        el.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
        const focusTarget = el.querySelector('input:not([type=hidden]), select, button[type=submit], button');
        if (focusTarget) setTimeout(() => focusTarget.focus(), 30);
    };

    function closeAll() {
        document.querySelectorAll('#quick-add-modal, #delete-modal, #lineup-modal').forEach(m => m.classList.add('hidden'));
        document.body.classList.remove('overflow-hidden');
        if (lastFocused && lastFocused.focus) lastFocused.focus();
    }

    document.addEventListener('click', e => {
        if (e.target.closest('[data-close-modal]')) closeAll();
    });

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') { closeAll(); return; }
        // Shortcut: tekan N untuk membuka Tambah Cepat (jika tidak sedang mengetik)
        const tag = (e.target.tagName || '').toLowerCase();
        const typing = ['input', 'textarea', 'select'].includes(tag) || e.target.isContentEditable;
        if (!typing && !e.ctrlKey && !e.metaKey && !e.altKey && e.key.toLowerCase() === 'n') {
            e.preventDefault();
            openModal('quick-add-modal');
        }
    });

    window.openDeleteModal = function (btn) {
        document.getElementById('delete-name').textContent = btn.dataset.name;
        document.getElementById('delete-form').setAttribute('action', btn.dataset.action);
        openModal('delete-modal');
    };

    window.openLineupModal = function (btn) {
        const items = lineupData[btn.dataset.playerId] || [];
        document.getElementById('lineup-player').textContent = btn.dataset.name;
        const list = document.getElementById('lineup-list');
        list.innerHTML = '';

        if (!items.length) {
            const li = document.createElement('li');
            li.className = 'text-[12px] text-slate-400 py-4 text-center';
            li.textContent = 'Pemain ini belum masuk line-up pertandingan manapun.';
            list.appendChild(li);
        }

        items.forEach(it => {
            const li = document.createElement('li');
            li.className = 'p-3 rounded-xl border border-slate-100 bg-slate-50/60';

            const top = document.createElement('div');
            top.className = 'flex items-center justify-between gap-3';

            const title = document.createElement('span');
            title.className = 'text-[13px] font-bold text-[#1e293b]';
            title.textContent = it.match;

            const role = document.createElement('span');
            role.className = 'shrink-0 px-2 py-0.5 rounded-md border text-[10px] font-bold ' +
                (it.is_starter ? 'bg-emerald-50 border-emerald-100 text-emerald-600' : 'bg-slate-100 border-slate-200 text-slate-500');
            role.textContent = it.is_starter ? ('Starter' + (it.position ? ' · ' + it.position : '')) : 'Cadangan';

            top.appendChild(title);
            top.appendChild(role);

            const meta = document.createElement('div');
            meta.className = 'flex flex-wrap items-center gap-x-4 gap-y-1 mt-2 text-[11px] text-slate-500';

            const date = document.createElement('span');
            date.className = 'inline-flex items-center gap-1.5';
            date.innerHTML = iconCalendar;
            date.appendChild(document.createTextNode(it.date));
            meta.appendChild(date);

            if (it.venue) {
                const venue = document.createElement('span');
                venue.className = 'inline-flex items-center gap-1.5';
                venue.innerHTML = iconPin;
                venue.appendChild(document.createTextNode(it.venue));
                meta.appendChild(venue);
            }

            li.appendChild(top);
            li.appendChild(meta);
            list.appendChild(li);
        });

        openModal('lineup-modal');
    };

    // Buka kembali pop-up Tambah Cepat jika validasi gagal
    @if($quickErrors)
    openModal('quick-add-modal');
    @endif
})();
</script>
@endsection
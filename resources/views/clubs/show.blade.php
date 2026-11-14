@extends('layouts.app')
@section('title', 'Daftar Anggota Tim - FFA Analytics')
@section('header', 'Daftar Anggota Tim')
@section('subheader', 'Daftarkan Pemain ke dalam tim klub. Tambahkan pemain baru, edit data pemain, atau keluarkan pemain dari tim sesuai kebutuhan.')
@section('content')

@php
    // ===== Ikon Lucide (inline SVG) =====
    $icons = [
        'search'         => '<circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>',
        'user-plus'      => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/>',
        'plus'           => '<path d="M5 12h14"/><path d="M12 5v14"/>',
        'x'              => '<path d="M18 6 6 18"/><path d="m6 6 12 12"/>',
        'trash-2'        => '<path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/>',
        'chevron-down'   => '<path d="m6 9 6 6 6-6"/>',
        'shield-check'   => '<path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/>',
        'triangle-alert' => '<path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"/><path d="M12 9v4"/><path d="M12 17h.01"/>',
        'database'       => '<ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M3 5V19A9 3 0 0 0 21 19V5"/><path d="M3 12A9 3 0 0 0 21 12"/>',
        'arrow-left'     => '<path d="m12 19-7-7 7-7"/><path d="M19 12H5"/>',
    ];
    $icon = fn($name, $class = 'w-4 h-4') => '<svg xmlns="http://www.w3.org/2000/svg" class="' . $class . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">' . $icons[$name] . '</svg>';

    $squad = $players ?? $club->players ?? collect();
    $positions = ['ST', 'AM', 'RW', 'LW', 'CM', 'DM', 'RB', 'CB', 'LB', 'GK'];
    $quickErrors = old('_quick') && $errors->any();
@endphp

<div class="space-y-6">
    <!-- Card Container -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] p-6 md:p-8">

        <div class="mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
                <div>
                    <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Daftar Anggota Tim</h3>
                    <p class="text-[15px] font-bold text-[#1e293b] mt-1">{{ $club->name }} <span class="text-[12px] font-medium text-slate-400 ml-1">· {{ count($squad) }} pemain</span></p>
                    <p class="text-[12px] text-slate-500 mt-1">Penanggung jawab: <span class="font-semibold text-slate-700">{{ $club->coach?->name ?? 'Belum ditentukan' }}</span></p>
                </div>

                <!-- Shortcut: tambah pemain khusus tim ini -->
                <div class="flex items-center gap-2">
                    <a href="{{ route('players.index', ['club' => $club->name]) }}" class="px-4 py-2.5 bg-white hover:bg-slate-50 border border-slate-200 text-slate-600 rounded-xl text-[12px] font-bold flex items-center gap-2 transition-colors shadow-sm">
                        {!! $icon('database', 'w-4 h-4') !!}
                        Lihat di Database Pemain
                    </a>
                    <button type="button" onclick="openModal('quick-add-modal')" class="px-4 py-2.5 bg-[#4062D6] hover:bg-[#3251b5] text-white rounded-xl text-[12px] font-bold flex items-center gap-2 transition-colors shadow-sm active:scale-95">
                        {!! $icon('user-plus', 'w-4 h-4') !!}
                        Tambah Pemain
                        <kbd class="hidden sm:inline-block px-1.5 py-0.5 bg-white/20 rounded text-[10px] font-bold">N</kbd>
                    </button>
                </div>
            </div>

            <!-- Form Pencarian -->
            <form method="GET" action="{{ route('clubs.show', $club->id) }}" class="flex items-center gap-3">
                <div class="flex-1">
                    <label for="search" class="block text-[12px] font-bold text-slate-700 mb-1.5">Cari Nama Pemain</label>
                    <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Cari berdasarkan nama..."
                           class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 placeholder-slate-400 focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] transition-all shadow-sm">
                </div>
                <div class="pt-5">
                    <button type="submit" aria-label="Cari pemain" class="w-10 h-10 bg-[#4062D6] hover:bg-[#3251b5] text-white rounded-xl flex items-center justify-center transition-colors shadow-sm active:scale-95 shrink-0">
                        {!! $icon('search', 'w-4 h-4') !!}
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabel Anggota Tim -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100">
                        <th class="py-3 px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest w-[15%]">No. Punggung</th>
                        <th class="py-3 px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest w-[40%]">Nama Lengkap Pemain</th>
                        <th class="py-3 px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest w-[12%]">Posisi</th>
                        <th class="py-3 px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest w-[18%]">Status Pemain</th>
                        <th class="py-3 px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest w-[15%] text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($squad as $player)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="py-4 px-4 text-[13px] font-bold text-[#1e293b]">
                            #{{ $player->jersey_number ?? '-' }}
                        </td>
                        <td class="py-4 px-4 text-[13px] font-bold text-[#1e293b]">
                            {{ $player->name }}
                        </td>
                        <td class="py-4 px-4 text-[12px] font-bold text-slate-600">
                            {{ $player->position ?? '-' }}
                        </td>
                        <td class="py-4 px-4">
                            @php $pStatus = $player->status ?? 'Aktif'; @endphp
                            <span class="inline-block px-3 py-1 rounded-full text-[11px] font-bold border
                                {{ $pStatus == 'Aktif' ? 'bg-emerald-50 border-emerald-100 text-emerald-600' : ($pStatus == 'Cedera' ? 'bg-amber-50 border-amber-100 text-amber-600' : 'bg-slate-100 border-slate-200 text-slate-500') }}">
                                {{ $pStatus }}
                            </span>
                        </td>
                        <td class="py-4 px-4 text-right">
                            <!-- Contoh Form Hapus/Keluarkan Pemain dari Klub -->
                            <form action="#" method="POST" onsubmit="return confirm('Keluarkan {{ addslashes($player->name) }} dari klub?');" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" aria-label="Keluarkan {{ $player->name }} dari klub" title="Keluarkan dari klub" class="w-8 h-8 rounded-lg bg-slate-50 hover:bg-rose-50 border border-slate-100 inline-flex items-center justify-center text-slate-400 hover:text-rose-500 transition-colors shadow-sm">
                                    {!! $icon('trash-2', 'w-4 h-4') !!}
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-slate-400 text-[13px] font-medium">
                            {{ request('search') ? 'Tidak ada pemain yang cocok dengan pencarian.' : 'Belum ada pemain yang terdaftar di dalam tim ini.' }}
                            @unless(request('search'))
                                <button type="button" onclick="openModal('quick-add-modal')" class="ml-1 text-[#4062D6] font-bold hover:underline">Tambah pemain pertama</button>
                            @endunless
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

    <!-- Tombol Kembali -->
    <div>
        <a href="{{ route('clubs.index') }}" class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-[#4062D6] hover:bg-[#3251b5] text-white text-[13px] font-bold rounded-xl transition-colors shadow-sm active:scale-95">
            {!! $icon('arrow-left', 'w-4 h-4') !!}
            Kembali
        </a>
    </div>
</div>

<!-- Pop-up: Tambah Pemain ke tim ini -->
<div id="quick-add-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4" role="dialog" aria-modal="true" aria-labelledby="quick-add-title">
    <div class="absolute inset-0 bg-slate-900/50" data-close-modal></div>
    <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <div class="flex items-center gap-2">
                <span class="text-[#4062D6]">{!! $icon('user-plus', 'w-5 h-5') !!}</span>
                <h3 id="quick-add-title" class="text-[14px] font-bold text-[#1e293b]">Tambah Pemain ke {{ $club->name }}</h3>
            </div>
            <button type="button" data-close-modal aria-label="Tutup" class="w-8 h-8 rounded-lg hover:bg-slate-100 text-slate-400 flex items-center justify-center">{!! $icon('x', 'w-4 h-4') !!}</button>
        </div>

        <form action="{{ route('players.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <input type="hidden" name="_quick" value="1">
            <!-- Klub dikunci ke tim ini; pemain otomatis tampil di Database Pemain dengan tim ini -->
            <input type="hidden" name="club_id" value="{{ $club->id }}">

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
                <label class="block text-[12px] font-bold text-slate-700 mb-1.5">Tim / Klub</label>
                <div class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-[13px] font-bold text-slate-700">{{ $club->name }}</div>
            </div>

            <div class="flex items-center gap-2 px-3.5 py-2.5 bg-emerald-50 border border-emerald-100 rounded-xl text-[12px] text-emerald-700">
                {!! $icon('shield-check', 'w-4 h-4 shrink-0') !!}
                Status pemain baru otomatis <span class="font-bold">Aktif</span>, dan langsung tampil di Database Pemain.
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

<script>
(function () {
    let lastFocused = null;

    window.openModal = function (id) {
        const el = document.getElementById(id);
        if (!el) return;
        lastFocused = document.activeElement;
        el.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
        setTimeout(() => { const f = el.querySelector('#qa_name'); if (f) f.focus(); }, 30);
    };

    function closeAll() {
        document.getElementById('quick-add-modal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        if (lastFocused && lastFocused.focus) lastFocused.focus();
    }

    document.addEventListener('click', e => { if (e.target.closest('[data-close-modal]')) closeAll(); });

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') { closeAll(); return; }
        // Shortcut: tekan N untuk menambah pemain (jika tidak sedang mengetik)
        const tag = (e.target.tagName || '').toLowerCase();
        const typing = ['input', 'textarea', 'select'].includes(tag) || e.target.isContentEditable;
        if (!typing && !e.ctrlKey && !e.metaKey && !e.altKey && e.key.toLowerCase() === 'n') {
            e.preventDefault();
            openModal('quick-add-modal');
        }
    });

    @if($quickErrors)
    openModal('quick-add-modal');
    @endif
})();
</script>
@endsection
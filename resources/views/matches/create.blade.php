@extends('layouts.app')
@section('title', 'Tambah Pertandingan - FFA Analytics')
@section('header', 'Tambah Pertandingan')
@section('subheader', 'Kelola jadwal pertandingan akademi')
@section('content')

<!-- Tambahkan CDN Lucide Icons jika belum ada di layouts/app.blade.php -->
<script src="https://unpkg.com/lucide@latest"></script>

<div class="bg-white rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] overflow-hidden">
    <div class="px-8 py-5 border-b border-slate-100 bg-white flex items-center gap-2">
        <i data-lucide="calendar-plus" class="w-4 h-4 text-[#4062D6]"></i>
        <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Informasi Pertandingan</h3>
    </div>
    
    <form action="{{ route('matches.store') }}" method="POST" class="p-8">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6">
            <!-- Kolom Kiri -->
            <div class="space-y-6">
                <div>
                    <label for="title_search" class="block text-[12px] font-bold text-slate-700 mb-2 flex items-center gap-1.5">
                        <i data-lucide="shield" class="w-3.5 h-3.5 text-[#4062D6]"></i>
                        <span>Nama Tim</span>
                    </label>
                    <!-- Combobox dengan fitur pencarian: ketik untuk mencari, lalu pilih dari daftar -->
                    <div class="relative" id="team-combobox">
                        <input type="hidden" id="title" name="title" value="{{ old('title') }}">
                        <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="search" class="w-4 h-4"></i>
                        </div>
                        <input type="text" id="title_search" autocomplete="off" placeholder="Cari / ketik nama tim..." value="{{ old('title') }}" required
                               role="combobox" aria-expanded="false" aria-controls="team-options"
                               class="w-full pl-11 pr-10 py-3 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 placeholder-slate-300 focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] transition-all shadow-sm">
                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="chevron-down" class="w-4 h-4"></i>
                        </div>
                        <ul id="team-options" role="listbox" class="hidden absolute z-30 left-0 right-0 mt-2 max-h-60 overflow-y-auto bg-white border border-slate-200 rounded-xl shadow-lg py-1"></ul>
                    </div>
                    <p class="text-[11px] text-slate-400 mt-1.5">Ketik untuk mencari, lalu pilih tim dari daftar.</p>
                    @error('title')<p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>@enderror
                </div>
                
                <div>
                    <label for="opponent" class="block text-[12px] font-bold text-slate-700 mb-2 flex items-center gap-1.5">
                        <i data-lucide="shield-alert" class="w-3.5 h-3.5 text-rose-500"></i>
                        <span>Tim Lawan</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="user-x" class="w-4 h-4"></i>
                        </div>
                        <input type="text" id="opponent" name="opponent" placeholder="Nama Tim Lawan" required 
                               class="w-full pl-11 pr-4 py-3 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 placeholder-slate-300 focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] transition-all shadow-sm">
                    </div>
                </div>

                <div>
                    <label for="formation" class="block text-[12px] font-bold text-slate-700 mb-2 flex items-center gap-1.5">
                        <i data-lucide="layout-grid" class="w-3.5 h-3.5 text-[#4062D6]"></i>
                        <span>Formasi</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="grid" class="w-4 h-4"></i>
                        </div>
                        <select id="formation" name="formation" required 
                                class="w-full pl-11 pr-10 py-3 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 appearance-none focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] transition-all shadow-sm cursor-pointer">
                            <option value="" disabled selected class="text-slate-300">Pilih Formasi</option>
                            <option value="4-3-3" {{ old('formation') == '4-3-3' ? 'selected' : '' }}>4-3-3</option>
                            <option value="4-4-2" {{ old('formation') == '4-4-2' ? 'selected' : '' }}>4-4-2</option>
                            <option value="3-5-2" {{ old('formation') == '3-5-2' ? 'selected' : '' }}>3-5-2</option>
                            <option value="4-2-3-1" {{ old('formation') == '4-2-3-1' ? 'selected' : '' }}>4-2-3-1</option>
                            <option value="3-4-3" {{ old('formation') == '3-4-3' ? 'selected' : '' }}>3-4-3</option>
                        </select>
                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="chevron-down" class="w-4 h-4"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan -->
            <div class="space-y-6">
                <div>
                    <label for="match_date" class="block text-[12px] font-bold text-slate-700 mb-2 flex items-center gap-1.5">
                        <i data-lucide="calendar" class="w-3.5 h-3.5 text-[#4062D6]"></i>
                        <span>Tanggal Pertandingan</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="clock" class="w-4 h-4"></i>
                        </div>
                        <input type="datetime-local" id="match_date" name="match_date" required 
                               class="w-full pl-11 pr-4 py-3 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] transition-all shadow-sm">
                    </div>
                </div>

                <div>
                    <label for="venue" class="block text-[12px] font-bold text-slate-700 mb-2 flex items-center gap-1.5">
                        <i data-lucide="map-pin" class="w-3.5 h-3.5 text-[#4062D6]"></i>
                        <span>Tempat Pertandingan</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="navigation" class="w-4 h-4"></i>
                        </div>
                        <input type="text" id="venue" name="venue" required placeholder="Contoh: Stadion Mandala Krida"
                               class="w-full pl-11 pr-4 py-3 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 placeholder-slate-300 focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] transition-all shadow-sm">
                    </div>
                </div>
            </div>
        </div>

        <!-- Tombol Aksi -->
        <div class="mt-12 pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
            <a href="{{ route('matches.index') }}" 
               class="px-6 py-2.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-[13px] font-bold rounded-xl transition-colors shadow-sm text-center flex items-center gap-2">
                <i data-lucide="x" class="w-4 h-4"></i>
                <span>Batal</span>
            </a>
            <button type="submit" 
                    class="px-6 py-2.5 bg-[#4062D6] hover:bg-[#3251b5] text-white text-[13px] font-bold rounded-xl transition-colors shadow-sm active:scale-95 flex items-center gap-2">
                <i data-lucide="check" class="w-4 h-4"></i>
                <span>Simpan Pertandingan</span>
            </button>
        </div>
    </form>
</div>

<script>
(function () {
    const clubs = @json($clubs->pluck('name')->values());
    const input = document.getElementById('title_search');
    const hidden = document.getElementById('title');
    const list = document.getElementById('team-options');
    let active = -1;
    let visible = [];

    function setValidity() {
        input.setCustomValidity(hidden.value ? '' : 'Pilih nama tim dari daftar.');
    }

    function render() {
        const q = input.value.trim().toLowerCase();
        visible = clubs.filter(n => n.toLowerCase().includes(q));
        active = -1;
        list.innerHTML = '';
        if (visible.length === 0) {
            const li = document.createElement('li');
            li.className = 'px-4 py-3 text-[12px] text-slate-400';
            li.textContent = 'Tim tidak ditemukan';
            list.appendChild(li);
            return;
        }
        visible.forEach((name, i) => {
            const li = document.createElement('li');
            li.setAttribute('role', 'option');
            li.dataset.index = i;
            li.className = 'px-4 py-2.5 text-[13px] text-slate-700 cursor-pointer hover:bg-blue-50 hover:text-[#4062D6]';
            li.textContent = name;
            li.addEventListener('mousedown', e => { e.preventDefault(); choose(name); });
            list.appendChild(li);
        });
    }

    function highlight() {
        Array.from(list.children).forEach((li, i) => {
            li.classList.toggle('bg-blue-50', i === active);
            li.classList.toggle('text-[#4062D6]', i === active);
            if (i === active) li.scrollIntoView({ block: 'nearest' });
        });
    }

    function open() { render(); list.classList.remove('hidden'); input.setAttribute('aria-expanded', 'true'); }
    function close() { list.classList.add('hidden'); input.setAttribute('aria-expanded', 'false'); }

    function choose(name) {
        input.value = name;
        hidden.value = name;
        setValidity();
        close();
    }

    input.addEventListener('focus', open);
    input.addEventListener('input', () => {
        const exact = clubs.find(n => n.toLowerCase() === input.value.trim().toLowerCase());
        hidden.value = exact || '';
        setValidity();
        open();
    });
    input.addEventListener('keydown', e => {
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            if (list.classList.contains('hidden')) open();
            active = Math.min(active + 1, visible.length - 1);
            highlight();
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            active = Math.max(active - 1, 0);
            highlight();
        } else if (e.key === 'Enter') {
            if (!list.classList.contains('hidden') && active >= 0 && visible[active]) {
                e.preventDefault();
                choose(visible[active]);
            }
        } else if (e.key === 'Escape') {
            close();
        }
    });
    input.addEventListener('blur', () => {
        setTimeout(() => {
            close();
            if (hidden.value) input.value = hidden.value;
        }, 100);
    });
    document.addEventListener('click', e => { if (!document.getElementById('team-combobox').contains(e.target)) close(); });

    if (hidden.value && !clubs.includes(hidden.value)) { hidden.value = ''; input.value = ''; }
    setValidity();

    // Inisialisasi Lucide Icons
    lucide.createIcons();
})();
</script>
@endsection
@extends('layouts.app')
@section('title', 'Kelola User - FFA Analytics')
@section('header', 'Kelola User')
@section('subheader', 'Manajemen Akun User — atur akun, hak akses (role), dan status pengguna sistem')

@section('content')
<!-- Penjelasan Menu: Manajemen Akun User -->
<div class="bg-blue-50/65 border border-blue-100 rounded-2xl p-5 mb-6 flex gap-4">
<div class="w-10 h-10 rounded-xl bg-white border border-blue-100 text-[#4062D6] flex items-center justify-center shrink-0">
<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
</div>
<div>
<h3 class="text-[13px] font-bold text-[#1e293b] mb-1">Apa itu Manajemen Akun User?</h3>
<p class="text-[12px] text-slate-600 leading-relaxed">Menu ini dipakai untuk mengelola seluruh akun yang dapat masuk ke sistem: menambah akun baru (otomatis berstatus Aktif), mengubah data, role, dan status akun, serta menghapus akun, baik satu per satu maupun sekaligus. <span class="font-semibold">Role</span> menentukan hak akses: <span class="font-semibold">Super Admin</span> mengelola seluruh data, <span class="font-semibold">Pelatih</span> memantau performa dan jadwal tim, <span class="font-semibold">Analis</span> menginput statistik pertandingan, dan <span class="font-semibold">Pemain</span> melihat data performa pribadinya.</p>
</div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
<div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] flex items-center justify-between transition-transform hover:-translate-y-1 duration-300">
<div class="flex flex-col">
<h3 class="text-[28px] font-bold text-[#1e293b] leading-none mb-2">{{ $totalAktif ?? 0 }}</h3>
<p class="text-[10px] font-bold text-[#94a3b8] uppercase tracking-widest">Akun Aktif</p>
</div>
<div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-500 border border-emerald-100 flex items-center justify-center shrink-0">
<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
</div>
</div>
<div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] flex items-center justify-between transition-transform hover:-translate-y-1 duration-300">
<div class="flex flex-col">
<h3 class="text-[28px] font-bold text-[#1e293b] leading-none mb-2">{{ $totalNonaktif ?? 0 }}</h3>
<p class="text-[10px] font-bold text-[#94a3b8] uppercase tracking-widest">Akun Nonaktif</p>
</div>
<div class="w-12 h-12 rounded-full bg-rose-50 text-rose-500 border border-rose-100 flex items-center justify-center shrink-0">
<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
</div>
</div>
<div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] flex items-center justify-between transition-transform hover:-translate-y-1 duration-300">
<div class="flex flex-col">
<h3 class="text-[28px] font-bold text-[#1e293b] leading-none mb-2">{{ $totalOnline ?? 0 }}</h3>
<p class="text-[10px] font-bold text-[#94a3b8] uppercase tracking-widest">Online Sekarang</p>
</div>
<div class="w-12 h-12 rounded-full bg-blue-50 text-blue-500 border border-blue-100 flex items-center justify-center shrink-0">
<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
</div>
</div>
</div>

<!-- Form hapus massal -->
<form id="bulk-form" action="{{ route('users.bulk-destroy') }}" method="POST" class="hidden">
@csrf
</form>

<!-- Filter Role (Tabs) -->
<div class="flex flex-wrap items-center gap-2 mb-4">
@php
$tabClass = fn($active) => $active
? 'bg-[#4062D6] text-white border-[#4062D6] shadow-sm'
: 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50';
$tabBadge = fn($active) => $active ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-500';
@endphp
<a href="{{ route('users.index', array_filter(['sort' => $sort !== 'newest' ? $sort : null])) }}" class="px-4 py-2 rounded-xl border text-[12px] font-bold transition-colors flex items-center gap-2 {{ $tabClass(!$role) }}">
Semua <span class="px-1.5 py-0.5 rounded-md text-[10px] {{ $tabBadge(!$role) }}">{{ $totalAll }}</span>
</a>
@foreach($roles as $r)
@continue($r === 'Admin')
<a href="{{ route('users.index', array_filter(['role' => $r, 'sort' => $sort !== 'newest' ? $sort : null])) }}" class="px-4 py-2 rounded-xl border text-[12px] font-bold transition-colors flex items-center gap-2 {{ $tabClass($role === $r) }}">
{{ $r }} <span class="px-1.5 py-0.5 rounded-md text-[10px] {{ $tabBadge($role === $r) }}">{{ $roleCounts[$r] ?? 0 }}</span>
</a>
@endforeach
</div>

<!-- Toolbar: Sorting, Hapus Massal, Tambah User -->
<div class="flex flex-wrap items-center justify-between gap-3 mb-6">
<form method="GET" action="{{ route('users.index') }}" class="flex items-center gap-2 m-0">
@if($role)<input type="hidden" name="role" value="{{ $role }}">@endif
<label for="sort" class="text-[12px] font-bold text-slate-500">Urutkan:</label>
<select id="sort" name="sort" onchange="this.form.submit()" class="px-3 py-2 bg-white border border-slate-200 rounded-xl text-[12px] font-semibold text-slate-700 focus:outline-none focus:border-blue-500 shadow-sm cursor-pointer">
@foreach($sorts as $key => $label)
<option value="{{ $key }}" {{ $sort === $key ? 'selected' : '' }}>{{ $label }}{{ $key === 'newest' ? ' (Default)' : '' }}</option>
@endforeach
</select>
</form>

<div class="flex items-center gap-3">
<button type="button" onclick="openDeleteModal('bulk')" id="bulk-delete-btn" disabled class="px-4 py-2.5 rounded-xl text-[13px] font-semibold flex items-center gap-2 border border-rose-200 bg-rose-50 text-rose-600 hover:bg-rose-100 transition-colors disabled:opacity-40 disabled:cursor-not-allowed">
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
Hapus Terpilih (<span id="bulk-count">0</span>)
</button>
<a href="{{ route('users.create') }}" class="bg-gradient-to-r from-[#1e40af] via-[#2563eb] to-[#3b82f6] hover:opacity-90 text-white px-5 py-2.5 rounded-xl text-[13px] font-semibold transition-all shadow-[0_4px_12px_rgba(37,99,235,0.2)] flex items-center gap-2">
<span>+</span> Tambah User
</a>
</div>
</div>

<div class="bg-white rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] overflow-hidden">
<div class="overflow-x-auto">
<table class="w-full text-left border-collapse">
<thead>
<tr class="border-b border-slate-100 bg-white">
<th class="py-4 pl-6 pr-2 w-14">
  <!-- Checkbox + Dropdown Pilihan Opsi Seleksi -->
  <div class="relative inline-flex items-center gap-1">
    <input type="checkbox" id="select-all" class="w-4 h-4 rounded border-slate-300 text-[#4062D6] focus:ring-[#4062D6] cursor-pointer" title="Pilih semua">
    <button type="button" onclick="toggleSelectDropdown(event)" class="text-slate-400 hover:text-slate-600 focus:outline-none">
      <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
    </button>
    
    <!-- Popover Menu Pilihan -->
    <div id="select-options-dropdown" class="hidden absolute top-full left-0 mt-2 w-44 bg-white border border-slate-100 shadow-lg rounded-xl py-1.5 z-20 text-xs">
      <button type="button" onclick="selectOption('all')" class="w-full text-left px-3 py-1.5 text-slate-700 hover:bg-slate-50 font-medium">Pilih Semua</button>
      <button type="button" onclick="selectOption('none')" class="w-full text-left px-3 py-1.5 text-slate-700 hover:bg-slate-50 font-medium">Batal Pilih</button>
      <div class="border-t border-slate-100 my-1"></div>
      <button type="button" onclick="selectOption('active')" class="w-full text-left px-3 py-1.5 text-slate-700 hover:bg-slate-50 font-medium">Hanya User Aktif</button>
      <button type="button" onclick="selectOption('inactive')" class="w-full text-left px-3 py-1.5 text-slate-700 hover:bg-slate-50 font-medium">Hanya User Nonaktif</button>
    </div>
  </div>
</th>
<th class="py-4 px-4 text-[10px] font-bold text-[#94a3b8] uppercase tracking-widest w-[30%]">Nama Pengguna</th>
<th class="py-4 px-4 text-[10px] font-bold text-[#94a3b8] uppercase tracking-widest">Email</th>
<th class="py-4 px-4 text-[10px] font-bold text-[#94a3b8] uppercase tracking-widest text-center">Role</th>
<th class="py-4 px-4 text-[10px] font-bold text-[#94a3b8] uppercase tracking-widest">Status</th>
<th class="py-4 px-6 text-[10px] font-bold text-[#94a3b8] uppercase tracking-widest text-right">Aksi</th>
</tr>
</thead>
<tbody class="divide-y divide-slate-50">
@forelse($users as $user)
@continue($user->role === 'Admin')
<tr class="hover:bg-[#F8FAFC] transition-colors group" data-status="{{ strtolower($user->status) }}">
<td class="py-4 pl-6 pr-2">
<input type="checkbox" form="bulk-form" name="ids[]" value="{{ $user->id }}" class="row-check w-4 h-4 rounded border-slate-300 text-[#4062D6] focus:ring-[#4062D6] {{ auth()->id() === $user->id ? 'opacity-30 cursor-not-allowed' : 'cursor-pointer' }}" @if(auth()->id() === $user->id) disabled title="Akun Anda sendiri" @endif>
</td>
<td class="py-4 px-4">
<div class="flex items-center gap-3">
@if($user->role === 'Super Admin')
<div class="w-10 h-10 rounded-full bg-[#183262] text-white flex items-center justify-center text-[13px] font-bold shrink-0 relative">
{{ strtoupper(substr($user->name, 0, 1)) }}
<div class="absolute bottom-0 right-0 w-3 h-3 bg-emerald-400 border-2 border-white rounded-full"></div>
</div>
@else
<div class="w-10 h-10 rounded-full bg-[#334155] text-white flex items-center justify-center text-[13px] font-bold shrink-0">
{{ strtoupper(substr($user->name, 0, 1)) }}
</div>
@endif
<div class="flex flex-col">
<div class="flex items-center gap-2">
<h4 class="text-[13px] font-bold text-[#1e293b]">{{ $user->name }}</h4>
@if($user->role === 'Super Admin')
<span class="px-1.5 py-0.5 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded text-[9px] font-black tracking-wider uppercase">Online</span>
@endif
</div>
<p class="text-[11px] font-medium text-[#94a3b8] mt-0.5">Terdaftar {{ $user->created_at ? $user->created_at->diffForHumans() : '-' }}</p>
</div>
</div>
</td>
<td class="py-4 px-4 text-[13px] font-medium text-[#64748b]">{{ $user->email }}</td>
<td class="py-4 px-4 text-center">
@if($user->role === 'Super Admin')
<span class="inline-block px-3 py-1 bg-rose-50 text-rose-600 border border-rose-100 rounded-full text-[10px] font-bold tracking-wide">{{ $user->role }}</span>
@elseif($user->role === 'Pelatih')
<span class="inline-block px-3 py-1 bg-blue-50 text-blue-600 border border-blue-100 rounded-full text-[10px] font-bold tracking-wide">Pelatih</span>
@elseif($user->role === 'Analis')
<span class="inline-block px-3 py-1 bg-amber-50 text-amber-600 border border-amber-100 rounded-full text-[10px] font-bold tracking-wide">Analis</span>
@else
<span class="inline-block px-3 py-1 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-full text-[10px] font-bold tracking-wide">Pemain</span>
@endif
</td>
<td class="py-4 px-4">
@if($user->status === 'Aktif')
<div class="flex items-center gap-1.5">
<div class="w-1.5 h-1.5 rounded-full bg-emerald-500"></div>
<p class="text-[12px] font-bold text-emerald-600">Aktif</p>
</div>
@else
<div class="flex items-center gap-1.5">
<div class="w-1.5 h-1.5 rounded-full bg-rose-500"></div>
<p class="text-[12px] font-bold text-rose-600">Nonaktif</p>
</div>
@endif
</td>
<td class="py-4 px-6 text-right">
<div class="flex items-center justify-end gap-2">
<a href="{{ route('users.edit', $user->id) }}" class="w-8 h-8 rounded-lg bg-slate-50 border border-slate-200 text-slate-400 hover:text-[#2d5096] hover:border-[#2d5096] flex items-center justify-center transition-colors" title="Edit">
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
</a>
<form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline-block m-0">
@csrf
@method('DELETE')
<button type="button" onclick="openDeleteModal('single', this.form)" class="w-8 h-8 rounded-lg bg-slate-50 border border-slate-200 text-slate-400 hover:text-rose-500 hover:border-rose-500 flex items-center justify-center transition-colors" title="Hapus">
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
</button>
</form>
</div>
</td>
</tr>
@empty
<tr>
<td colspan="6" class="py-10 text-center text-slate-500 font-medium">{{ $role ? 'Belum ada pengguna dengan role ' . $role . '.' : 'Belum ada pengguna terdaftar.' }}</td>
</tr>
@endforelse
</tbody>
</table>
</div>
</div>

<!-- Modal Konfirmasi Hapus Custom -->
<div id="delete-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-xs hidden">
<div class="bg-white rounded-2xl border border-slate-100 shadow-xl max-w-md w-full mx-4 p-6 transform transition-all">
<div class="w-12 h-12 rounded-full bg-rose-50 text-rose-500 border border-rose-100 flex items-center justify-center mb-4 mx-auto">
<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
</div>
<h3 class="text-base font-bold text-slate-800 text-center mb-1">Konfirmasi Penghapusan</h3>
<p id="delete-modal-text" class="text-xs text-slate-500 text-center mb-6 leading-relaxed">Apakah Anda yakin ingin menghapus data user yang dipilih? Tindakan ini tidak dapat dibatalkan.</p>
<div class="flex items-center gap-3">
<button type="button" onclick="closeDeleteModal()" class="flex-1 px-4 py-2.5 rounded-xl text-xs font-semibold border border-slate-200 text-slate-600 hover:bg-slate-50 transition-colors">Batal</button>
<button type="button" id="confirm-delete-btn" class="flex-1 px-4 py-2.5 rounded-xl text-xs font-semibold bg-rose-600 text-white hover:bg-rose-700 transition-colors shadow-sm">Ya, Hapus</button>
</div>
</div>
</div>

<script>
(function () {
    const selectAll = document.getElementById('select-all');
    const btn = document.getElementById('bulk-delete-btn');
    const countEl = document.getElementById('bulk-count');
    const dropdown = document.getElementById('select-options-dropdown');
    
    const getCheckboxes = () => Array.from(document.querySelectorAll('.row-check:not(:disabled)'));

    function refresh() {
        const items = getCheckboxes();
        const selected = items.filter(c => c.checked).length;
        countEl.textContent = selected;
        btn.disabled = selected === 0;
        if(selectAll) {
            selectAll.checked = items.length > 0 && selected === items.length;
            selectAll.indeterminate = selected > 0 && selected < items.length;
        }
    }

    if (selectAll) {
        selectAll.addEventListener('change', () => {
            getCheckboxes().forEach(c => c.checked = selectAll.checked);
            refresh();
        });
    }

    document.addEventListener('change', e => {
        if (e.target.classList && e.target.classList.contains('row-check')) refresh();
    });

    // Toggle Dropdown Menu Pilihan
    window.toggleSelectDropdown = function (e) {
        e.stopPropagation();
        dropdown.classList.toggle('hidden');
    };

    // Close Dropdown saat klik di luar
    document.addEventListener('click', () => {
        if (dropdown && !dropdown.classList.contains('hidden')) {
            dropdown.classList.add('hidden');
        }
    });

    // Opsi Seleksi Pintar
    window.selectOption = function (type) {
        getCheckboxes().forEach(c => {
            const tr = c.closest('tr');
            const status = tr ? tr.dataset.status : '';

            if (type === 'all') c.checked = true;
            else if (type === 'none') c.checked = false;
            else if (type === 'active') c.checked = (status === 'aktif');
            else if (type === 'inactive') c.checked = (status === 'nonaktif');
        });
        refresh();
    };

    let targetFormToSubmit = null;

    window.openDeleteModal = function (type, form = null) {
        if (type === 'bulk') {
            const n = getCheckboxes().filter(c => c.checked).length;
            if (n === 0) return;
            document.getElementById('delete-modal-text').textContent = 'Apakah Anda yakin ingin menghapus ' + n + ' user terpilih? Tindakan ini tidak dapat dibatalkan.';
            targetFormToSubmit = document.getElementById('bulk-form');
        } else if (type === 'single') {
            document.getElementById('delete-modal-text').textContent = 'Apakah Anda yakin ingin menghapus user ini? Tindakan ini tidak dapat dibatalkan.';
            targetFormToSubmit = form;
        }
        document.getElementById('delete-modal').classList.remove('hidden');
    };

    window.closeDeleteModal = function () {
        document.getElementById('delete-modal').classList.add('hidden');
        targetFormToSubmit = null;
    };

    document.getElementById('confirm-delete-btn').addEventListener('click', function () {
        if (targetFormToSubmit) {
            targetFormToSubmit.submit();
        }
    });

    refresh();
})();
</script>
@endsection
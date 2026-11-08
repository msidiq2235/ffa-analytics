@extends('layouts.app')
@section('title', 'Kelola User - FFA Analytics')
@section('header', 'Kelola User')
@section('subheader', 'Manajemen akun Football')
@section('content')

<style>
    [data-lucide] { flex-shrink: 0; }
</style>

<div class="space-y-6">
    <!-- Top Metric Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Akun Aktif -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] flex items-center justify-between">
            <div>
                <h3 class="text-3xl font-black text-[#1e293b] leading-none mb-2">4</h3>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Akun Aktif</p>
            </div>
            <div class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-500 flex items-center justify-center shrink-0">
                <i data-lucide="user-check" class="w-5 h-5"></i>
            </div>
        </div>

        <!-- Akun Nonaktif -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] flex items-center justify-between">
            <div>
                <h3 class="text-3xl font-black text-[#1e293b] leading-none mb-2">0</h3>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Akun Nonaktif</p>
            </div>
            <div class="w-10 h-10 rounded-full bg-rose-50 text-rose-500 flex items-center justify-center shrink-0">
                <i data-lucide="user-x" class="w-5 h-5"></i>
            </div>
        </div>

        <!-- Online Sekarang -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] flex items-center justify-between">
            <div>
                <h3 class="text-3xl font-black text-[#1e293b] leading-none mb-2">1</h3>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Online Sekarang</p>
            </div>
            <div class="w-10 h-10 rounded-full bg-blue-50 text-[#4062D6] flex items-center justify-center shrink-0">
                <i data-lucide="wifi" class="w-5 h-5"></i>
            </div>
        </div>
    </div>

    <!-- Action Button & Table -->
    <div class="flex flex-col space-y-4">
        <div class="flex justify-end">
            <a href="#" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#4062D6] hover:bg-[#3251b5] text-white text-[12px] font-bold rounded-xl transition-colors shadow-sm active:scale-95">
                <i data-lucide="user-plus" class="w-4 h-4"></i>
                Tambah User
            </a>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[800px]">
                <thead>
                    <tr class="border-b border-slate-100">
                        <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Nama Pengguna</th>
                        <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Email</th>
                        <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Role</th>
                        <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Status</th>
                        <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <!-- Row 1: Pemain -->
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-[#2f3f58] text-white flex items-center justify-center text-[13px] font-bold shrink-0">
                                    P
                                </div>
                                <div>
                                    <h4 class="text-[13px] font-bold text-[#1e293b]">pemain1</h4>
                                    <p class="text-[11px] text-slate-400 mt-0.5 flex items-center gap-1">
                                        <i data-lucide="clock" class="w-3 h-3"></i> Belum pernah aktif
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6 text-[13px] text-slate-500 font-medium">
                            <span class="inline-flex items-center gap-2"><i data-lucide="mail" class="w-3.5 h-3.5 text-slate-400"></i>pemain1@email.com</span>
                        </td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-100/50 text-emerald-600 rounded-full text-[11px] font-bold">
                                <i data-lucide="user" class="w-3 h-3"></i> Pemain
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-1.5">
                                <i data-lucide="circle-check" class="w-3.5 h-3.5 text-emerald-500"></i>
                                <span class="text-[12px] font-bold text-emerald-500">Aktif</span>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-2">
                                <button type="button" title="Edit" aria-label="Edit pemain1" class="w-8 h-8 rounded-lg bg-slate-50 hover:bg-slate-100 border border-slate-100 flex items-center justify-center text-slate-400 hover:text-[#4062D6] transition-colors">
                                    <i data-lucide="square-pen" class="w-4 h-4"></i>
                                </button>
                                <button type="button" title="Hapus" aria-label="Hapus pemain1" class="w-8 h-8 rounded-lg bg-slate-50 hover:bg-rose-50 border border-slate-100 flex items-center justify-center text-slate-400 hover:text-rose-500 transition-colors">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Row 2: Analis -->
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-[#2f3f58] text-white flex items-center justify-center text-[13px] font-bold shrink-0">
                                    A
                                </div>
                                <div>
                                    <h4 class="text-[13px] font-bold text-[#1e293b]">Analis1</h4>
                                    <p class="text-[11px] text-slate-400 mt-0.5 flex items-center gap-1">
                                        <i data-lucide="clock" class="w-3 h-3"></i> Belum pernah aktif
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6 text-[13px] text-slate-500 font-medium">
                            <span class="inline-flex items-center gap-2"><i data-lucide="mail" class="w-3.5 h-3.5 text-slate-400"></i>analis1@email.com</span>
                        </td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-orange-100/50 text-orange-600 rounded-full text-[11px] font-bold">
                                <i data-lucide="chart-column" class="w-3 h-3"></i> Analis
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-1.5">
                                <i data-lucide="circle-check" class="w-3.5 h-3.5 text-emerald-500"></i>
                                <span class="text-[12px] font-bold text-emerald-500">Aktif</span>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-2">
                                <button type="button" title="Edit" aria-label="Edit Analis1" class="w-8 h-8 rounded-lg bg-slate-50 hover:bg-slate-100 border border-slate-100 flex items-center justify-center text-slate-400 hover:text-[#4062D6] transition-colors">
                                    <i data-lucide="square-pen" class="w-4 h-4"></i>
                                </button>
                                <button type="button" title="Hapus" aria-label="Hapus Analis1" class="w-8 h-8 rounded-lg bg-slate-50 hover:bg-rose-50 border border-slate-100 flex items-center justify-center text-slate-400 hover:text-rose-500 transition-colors">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Row 3: Pelatih -->
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-[#2f3f58] text-white flex items-center justify-center text-[13px] font-bold shrink-0">
                                    P
                                </div>
                                <div>
                                    <h4 class="text-[13px] font-bold text-[#1e293b]">Pelatih1</h4>
                                    <p class="text-[11px] text-slate-400 mt-0.5 flex items-center gap-1">
                                        <i data-lucide="clock" class="w-3 h-3"></i> Aktif 1 hour ago
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6 text-[13px] text-slate-500 font-medium">
                            <span class="inline-flex items-center gap-2"><i data-lucide="mail" class="w-3.5 h-3.5 text-slate-400"></i>pelatih1@email.com</span>
                        </td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-100/50 text-[#4062D6] rounded-full text-[11px] font-bold">
                                <i data-lucide="clipboard-list" class="w-3 h-3"></i> Pelatih
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-1.5">
                                <i data-lucide="circle-check" class="w-3.5 h-3.5 text-emerald-500"></i>
                                <span class="text-[12px] font-bold text-emerald-500">Aktif</span>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-2">
                                <button type="button" title="Edit" aria-label="Edit Pelatih1" class="w-8 h-8 rounded-lg bg-slate-50 hover:bg-slate-100 border border-slate-100 flex items-center justify-center text-slate-400 hover:text-[#4062D6] transition-colors">
                                    <i data-lucide="square-pen" class="w-4 h-4"></i>
                                </button>
                                <button type="button" title="Hapus" aria-label="Hapus Pelatih1" class="w-8 h-8 rounded-lg bg-slate-50 hover:bg-rose-50 border border-slate-100 flex items-center justify-center text-slate-400 hover:text-rose-500 transition-colors">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Row 4: Admin (Online) -->
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-4">
                                <div class="relative w-10 h-10 rounded-full bg-[#2f3f58] text-white flex items-center justify-center text-[13px] font-bold shrink-0">
                                    S
                                    <div class="absolute bottom-0 right-0 w-3 h-3 bg-emerald-500 border-2 border-white rounded-full"></div>
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h4 class="text-[13px] font-bold text-[#1e293b]">Admin FFA</h4>
                                        <span class="inline-flex items-center gap-1 px-1.5 py-0.5 bg-emerald-50 border border-emerald-100 text-emerald-600 rounded text-[9px] font-black uppercase tracking-wider">
                                            <i data-lucide="wifi" class="w-2.5 h-2.5"></i> Online
                                        </span>
                                    </div>
                                    <p class="text-[11px] text-slate-400 mt-0.5 flex items-center gap-1">
                                        <i data-lucide="clock" class="w-3 h-3"></i> Aktif 21 seconds ago
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6 text-[13px] text-slate-500 font-medium">
                            <span class="inline-flex items-center gap-2"><i data-lucide="mail" class="w-3.5 h-3.5 text-slate-400"></i>admin@ffa.com</span>
                        </td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-rose-100/50 text-rose-600 rounded-full text-[11px] font-bold">
                                <i data-lucide="shield-check" class="w-3 h-3"></i> Admin
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-1.5">
                                <i data-lucide="circle-check" class="w-3.5 h-3.5 text-emerald-500"></i>
                                <span class="text-[12px] font-bold text-emerald-500">Aktif</span>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-2">
                                <button type="button" title="Edit" aria-label="Edit Admin FFA" class="w-8 h-8 rounded-lg bg-slate-50 hover:bg-slate-100 border border-slate-100 flex items-center justify-center text-slate-400 hover:text-[#4062D6] transition-colors">
                                    <i data-lucide="square-pen" class="w-4 h-4"></i>
                                </button>
                                <button type="button" title="Hapus" aria-label="Hapus Admin FFA" class="w-8 h-8 rounded-lg bg-slate-50 hover:bg-rose-50 border border-slate-100 flex items-center justify-center text-slate-400 hover:text-rose-500 transition-colors">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Lucide Icons (hapus baris ini jika layouts.app sudah memuat Lucide) -->
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (window.lucide) lucide.createIcons({ attrs: { 'stroke-width': 2 } });
    });
</script>

@endsection
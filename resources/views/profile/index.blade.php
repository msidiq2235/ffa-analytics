@extends('layouts.app')
@section('title', 'Kelola User - FFA Analytics')
@section('header', 'Kelola User')
@section('subheader', 'Manajemen akun Football')
@section('content')

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
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
        </div>

        <!-- Akun Nonaktif -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] flex items-center justify-between">
            <div>
                <h3 class="text-3xl font-black text-[#1e293b] leading-none mb-2">0</h3>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Akun Nonaktif</p>
            </div>
            <div class="w-10 h-10 rounded-full bg-rose-50 text-rose-500 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </div>
        </div>

        <!-- Online Sekarang -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] flex items-center justify-between">
            <div>
                <h3 class="text-3xl font-black text-[#1e293b] leading-none mb-2">1</h3>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Online Sekarang</p>
            </div>
            <div class="w-10 h-10 rounded-full bg-blue-50 text-[#4062D6] flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            </div>
        </div>
    </div>

    <!-- Action Button & Table -->
    <div class="flex flex-col space-y-4">
        <div class="flex justify-end">
            <a href="#" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#4062D6] hover:bg-[#3251b5] text-white text-[12px] font-bold rounded-xl transition-colors shadow-sm active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
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
                                    <p class="text-[11px] text-slate-400 mt-0.5">Belum pernah aktif</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6 text-[13px] text-slate-500 font-medium">pemain1@email.com</td>
                        <td class="py-4 px-6">
                            <span class="inline-block px-3 py-1 bg-emerald-100/50 text-emerald-600 rounded-full text-[11px] font-bold">Pemain</span>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-1.5">
                                <div class="w-1.5 h-1.5 rounded-full bg-emerald-500"></div>
                                <span class="text-[12px] font-bold text-emerald-500">Aktif</span>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-2">
                                <button class="w-8 h-8 rounded-lg bg-slate-50 hover:bg-slate-100 border border-slate-100 flex items-center justify-center text-slate-400 hover:text-[#4062D6] transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </button>
                                <button class="w-8 h-8 rounded-lg bg-slate-50 hover:bg-rose-50 border border-slate-100 flex items-center justify-center text-slate-400 hover:text-rose-500 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
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
                                    <p class="text-[11px] text-slate-400 mt-0.5">Belum pernah aktif</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6 text-[13px] text-slate-500 font-medium">analis1@email.com</td>
                        <td class="py-4 px-6">
                            <span class="inline-block px-3 py-1 bg-orange-100/50 text-orange-600 rounded-full text-[11px] font-bold">Analis</span>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-1.5">
                                <div class="w-1.5 h-1.5 rounded-full bg-emerald-500"></div>
                                <span class="text-[12px] font-bold text-emerald-500">Aktif</span>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-2">
                                <button class="w-8 h-8 rounded-lg bg-slate-50 hover:bg-slate-100 border border-slate-100 flex items-center justify-center text-slate-400 hover:text-[#4062D6] transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </button>
                                <button class="w-8 h-8 rounded-lg bg-slate-50 hover:bg-rose-50 border border-slate-100 flex items-center justify-center text-slate-400 hover:text-rose-500 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
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
                                    <p class="text-[11px] text-slate-400 mt-0.5">Aktif 1 hour ago</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6 text-[13px] text-slate-500 font-medium">pelatih1@email.com</td>
                        <td class="py-4 px-6">
                            <span class="inline-block px-3 py-1 bg-blue-100/50 text-[#4062D6] rounded-full text-[11px] font-bold">Pelatih</span>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-1.5">
                                <div class="w-1.5 h-1.5 rounded-full bg-emerald-500"></div>
                                <span class="text-[12px] font-bold text-emerald-500">Aktif</span>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-2">
                                <button class="w-8 h-8 rounded-lg bg-slate-50 hover:bg-slate-100 border border-slate-100 flex items-center justify-center text-slate-400 hover:text-[#4062D6] transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </button>
                                <button class="w-8 h-8 rounded-lg bg-slate-50 hover:bg-rose-50 border border-slate-100 flex items-center justify-center text-slate-400 hover:text-rose-500 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
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
                                        <span class="px-1.5 py-0.5 bg-emerald-50 border border-emerald-100 text-emerald-600 rounded text-[9px] font-black uppercase tracking-wider">Online</span>
                                    </div>
                                    <p class="text-[11px] text-slate-400 mt-0.5">Aktif 21 seconds ago</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6 text-[13px] text-slate-500 font-medium">admin@ffa.com</td>
                        <td class="py-4 px-6">
                            <span class="inline-block px-3 py-1 bg-rose-100/50 text-rose-600 rounded-full text-[11px] font-bold">Admin</span>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-1.5">
                                <div class="w-1.5 h-1.5 rounded-full bg-emerald-500"></div>
                                <span class="text-[12px] font-bold text-emerald-500">Aktif</span>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-2">
                                <button class="w-8 h-8 rounded-lg bg-slate-50 hover:bg-slate-100 border border-slate-100 flex items-center justify-center text-slate-400 hover:text-[#4062D6] transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </button>
                                <button class="w-8 h-8 rounded-lg bg-slate-50 hover:bg-rose-50 border border-slate-100 flex items-center justify-center text-slate-400 hover:text-rose-500 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
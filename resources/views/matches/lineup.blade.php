@extends('layouts.app')
@section('title', 'Edit Line-Up - FFA Analytics')
@section('header', 'Edit Line-Up')
@section('subheader', 'Kelola jadwal pertandingan akademi')
@section('content')

<div class="bg-white rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] overflow-hidden">
    <div class="px-8 py-5 border-b border-slate-100 bg-white">
        <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">SET LINE-UP TIM</h3>
    </div>
    
    <form action="{{ route('matches.updateLineup', $match->id) }}" method="POST" class="p-8">
    @csrf
        @method('PUT')
        
        <div class="flex items-center justify-between mb-6">
            <h4 class="text-[15px] font-bold text-[#1e293b]">Daftar Pemain Aktif</h4>
            <p class="text-[11px] text-slate-400 font-medium">Pilih pemain dan tentukan posisi bermainnya.</p>
        </div>

        <div class="overflow-x-auto mb-10">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100">
                        <th class="py-3 px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest w-[10%]">BAWA</th>
                        <th class="py-3 px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest w-[40%]">NAMA PEMAIN</th>
                        <th class="py-3 px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest w-[20%]">NO. PUNGGUNG</th>
                        <th class="py-3 px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest w-[30%]">POSISI DI PERTANDINGAN</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <!-- Loop Pemain 1 -->
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="py-4 px-4">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="players[]" value="1" checked 
                                       class="w-5 h-5 text-[#4062D6] border-slate-200 rounded focus:ring-[#4062D6]">
                            </label>
                        </td>
                        <td class="py-4 px-4 text-[13px] font-bold text-[#1e293b]">
                            Yazid Ardiyan Wicaksono
                        </td>
                        <td class="py-4 px-4 text-[12px] font-bold text-[#1e293b]">
                            #10
                        </td>
                        <td class="py-4 px-4">
                            <div class="relative w-full max-w-[200px]">
                                <select name="positions[1]" 
                                        class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-[12px] text-slate-700 appearance-none focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] transition-all shadow-sm cursor-pointer">
                                    <option value="" disabled>Pilih Posisi</option>
                                    <option value="ST" selected>ST</option>
                                    <option value="LW">LW</option>
                                    <option value="RW">RW</option>
                                    <option value="CM">CM</option>
                                    <option value="CB">CB</option>
                                    <option value="GK">GK</option>
                                </select>
                                <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Loop Pemain 2 -->
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="py-4 px-4">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="players[]" value="2" 
                                       class="w-5 h-5 text-[#4062D6] border-slate-200 rounded focus:ring-[#4062D6]">
                            </label>
                        </td>
                        <td class="py-4 px-4 text-[13px] font-bold text-[#1e293b]">
                            Arif Paradigma
                        </td>
                        <td class="py-4 px-4 text-[12px] font-bold text-[#1e293b]">
                            #69
                        </td>
                        <td class="py-4 px-4">
                            <div class="relative w-full max-w-[200px]">
                                <select name="positions[2]" 
                                        class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-[12px] text-slate-700 appearance-none focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] transition-all shadow-sm cursor-pointer">
                                    <option value="" disabled selected>Pilih Posisi</option>
                                    <option value="ST">ST</option>
                                    <option value="LW">LW</option>
                                    <option value="RW">RW</option>
                                    <option value="CM">CM</option>
                                    <option value="CB">CB</option>
                                    <option value="GK">GK</option>
                                </select>
                                <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Tombol Aksi -->
        <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
            <a href="{{ route('matches.index') }}" 
               class="px-6 py-2.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-[13px] font-bold rounded-xl transition-colors shadow-sm text-center">
                Batal
            </a>
            <button type="submit" 
                    class="px-6 py-2.5 bg-[#4062D6] hover:bg-[#3251b5] text-white text-[13px] font-bold rounded-xl transition-colors shadow-sm active:scale-95">
                Simpan Line-Up
            </button>
        </div>
    </form>
</div>

@endsection
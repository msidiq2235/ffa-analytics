@extends('layouts.app')
@section('title', 'Tambah Tim - FFA Analytics')
@section('header', 'Tambah Tim')
@section('subheader', 'Daftarkan klub atau akademi baru ke dalam database')
@section('content')

<div class="bg-white rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] p-8">
    <form action="{{ route('clubs.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="space-y-6">
            <!-- Input Nama Klub -->
            <div>
                <label for="name" class="block text-[13px] font-bold text-slate-700 mb-2">Nama Klub / Akademi</label>
                <input type="text" id="name" name="name" placeholder="Contoh: Future Football Academy" required 
                       class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 placeholder-slate-400 focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] transition-all shadow-sm">
            </div>
            
            <!-- Input Logo Klub -->
            <div>
                <label for="logo_path" class="block text-[13px] font-bold text-slate-700 mb-2">Logo Klub (Opsional)</label>
                <input type="file" id="logo_path" name="logo_path" accept="image/*"
                       class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-[13px] text-slate-700 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-[11px] file:font-bold file:bg-blue-50 file:text-[#4062D6] hover:file:bg-blue-100 transition-all shadow-sm cursor-pointer">
            </div>
        </div>

        <!-- Tombol Aksi -->
        <div class="mt-12 pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
            <a href="{{ route('clubs.index') }}" 
               class="px-6 py-2.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-[13px] font-bold rounded-xl transition-colors shadow-sm text-center">
                Batal
            </a>
            <button type="submit" 
                    class="px-6 py-2.5 bg-[#4062D6] hover:bg-[#3251b5] text-white text-[13px] font-bold rounded-xl transition-colors shadow-sm active:scale-95">
                Simpan Tim
            </button>
        </div>
    </form>
</div>

@endsection
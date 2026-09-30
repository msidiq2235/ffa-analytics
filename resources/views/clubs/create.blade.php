@extends('layouts.app')

@section('title', 'Tambah Klub - FFA Analytics')
@section('header', 'Tambah Klub')
@section('subheader', 'Daftarkan klub atau akademi baru ke dalam database')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-6 md:p-8">
            <form action="{{ route('clubs.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-semibold text-slate-700 mb-2">Nama Klub / Akademi</label>
                        <input type="text" id="name" name="name" required placeholder="Contoh: Future Football Academy" 
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-slate-600 focus:outline-none focus:ring-2 focus:ring-orange-500 transition-shadow">
                    </div>

                    <div>
                        <label for="logo_path" class="block text-sm font-semibold text-slate-700 mb-2">Logo Klub (Opsional)</label>
                        <input type="file" id="logo_path" name="logo_path" accept="image/*"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-slate-600 focus:outline-none focus:ring-2 focus:ring-orange-500 transition-shadow file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-600 hover:file:bg-orange-100">
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                    <a href="{{ route('clubs.index') }}" class="px-5 py-2.5 text-sm font-semibold text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-orange-500 rounded-lg hover:bg-orange-600 shadow-sm transition-colors">
                        Simpan Klub
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@php
    if (!file_exists(public_path('storage'))) {
        Artisan::call('storage:link');
    }
@endphp
@endsection
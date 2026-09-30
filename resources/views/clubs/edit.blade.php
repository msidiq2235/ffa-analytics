@extends('layouts.app')
@section('title', 'Edit Klub - FFA Analytics')
@section('header', 'Edit Data Klub')
@section('subheader', 'Perbarui informasi profil dan identitas klub')
@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden p-6 md:p-8">
        <form action="{{ route('clubs.update', $club->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <div>
                    <label for="name" class="block text-sm font-semibold text-slate-700 mb-2">Nama Klub</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $club->name) }}" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-slate-600 focus:outline-none focus:ring-2 focus:ring-orange-500 transition-shadow">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('clubs.index') }}" class="px-5 py-2.5 text-sm font-semibold text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors">Batal</a>
                <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-orange-500 rounded-lg hover:bg-orange-600 shadow-sm transition-colors">Simpan Perubahan</button>
            </div>
            
        </form>
    </div>
</div>
@endsection
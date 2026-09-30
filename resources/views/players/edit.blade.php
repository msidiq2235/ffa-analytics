@extends('layouts.app')
@section('title', 'Edit Pemain - FFA Analytics')
@section('header', 'Edit Data Pemain')
@section('subheader', 'Perbarui informasi profil, transfer klub, dan status pemain')
@section('content')
<div class="max-w-2xl">
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden p-6 md:p-8">
<form action="{{ route('players.update', $player->id) }}" method="POST">
@csrf
@method('PUT')
<div class="space-y-6">
<div>
<label for="name" class="block text-sm font-semibold text-slate-700 mb-2">Nama Pemain</label>
<input type="text" id="name" name="name" value="{{ old('name', $player->name) }}" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-slate-600 focus:outline-none focus:ring-2 focus:ring-orange-500 transition-shadow">
@error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
</div>
<div>
<label for="jersey_number" class="block text-sm font-semibold text-slate-700 mb-2">Nomor Punggung</label>
<input type="number" id="jersey_number" name="jersey_number" value="{{ old('jersey_number', $player->jersey_number) }}" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-slate-600 focus:outline-none focus:ring-2 focus:ring-orange-500 transition-shadow">
@error('jersey_number')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
</div>
<div>
<label for="club_id" class="block text-sm font-semibold text-slate-700 mb-2">Klub Asal</label>
<div class="relative">
<select id="club_id" name="club_id" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-slate-700 appearance-none focus:outline-none focus:ring-2 focus:ring-orange-500 transition-shadow">
<option value="">-- Pilih Klub --</option>
@foreach($clubs as $club)
<option value="{{ $club->id }}" {{ old('club_id', $player->club_id ?? '') == $club->id ? 'selected' : '' }}>{{ $club->name }}</option>
@endforeach
</select>
<div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400">
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
</div>
</div>
@error('club_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
</div>
<div>
<label for="status" class="block text-sm font-semibold text-slate-700 mb-2">Status Pemain</label>
<div class="relative">
<select id="status" name="status" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-slate-600 appearance-none focus:outline-none focus:ring-2 focus:ring-orange-500 transition-shadow">
<option value="Aktif" {{ old('status', $player->status ?? '') === 'Aktif' ? 'selected' : '' }}>Aktif</option>
<option value="Cedera" {{ old('status', $player->status ?? '') === 'Cedera' ? 'selected' : '' }}>Cedera</option>
<option value="Nonaktif" {{ old('status', $player->status ?? '') === 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
</select>
<div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400">
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
</div>
</div>
@error('status')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
</div>
</div>
<div class="mt-8 pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
<a href="{{ route('players.index') }}" class="px-5 py-2.5 text-sm font-semibold text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors">Batal</a>
<button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-orange-500 rounded-lg hover:bg-orange-600 shadow-sm transition-colors">Simpan Perubahan</button>
</div>
</form>
</div>
</div>
@endsection
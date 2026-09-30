@extends('layouts.app')
@section('title', 'Database Pemain - FFA Analytics')
@section('header', 'Database Pemain')
@section('subheader', 'Kelola data skuad, nomor punggung, dan profil pemain')
@section('header_action')
<a href="{{ route('players.create') }}" class="px-4 py-2 bg-orange-500 text-white font-semibold rounded-lg hover:bg-orange-600 shadow-sm transition-colors flex items-center gap-2">
<svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
Tambah Pemain
</a>
@endsection
@section('content')
@if(session('success'))
<div class="bg-green-50 border border-green-200 text-green-700 p-4 rounded-xl mb-6 font-medium">
{{ session('success') }}
</div>
@endif
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
<div class="overflow-x-auto">
<table class="w-full text-left text-sm text-slate-600">
<thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-100">
<tr>
<th class="px-6 py-4">Pemain</th>
<th class="px-6 py-4 text-center">Status</th>
<th class="px-6 py-4 text-right">Aksi</th>
</tr>
</thead>
<tbody class="divide-y divide-slate-100">
@forelse($players as $player)
<tr class="hover:bg-slate-50 transition-colors">
<td class="px-6 py-4 font-bold text-slate-800">
<div class="flex items-center gap-3">
<div class="w-8 h-8 rounded-full bg-slate-900 flex items-center justify-center text-white font-bold text-xs">
{{ $player->jersey_number ?? '-' }}
</div>
{{ $player->name }}
</div>
</td>
<td class="px-6 py-4 text-center">
<span class="px-3 py-1 text-xs font-bold rounded-full bg-slate-100 text-slate-600 border border-slate-200">
{{ $player->status ?? 'Aktif' }}
</span>
</td>
<td class="px-6 py-4 text-right">
<div class="flex items-center justify-end gap-2">
<a href="{{ route('players.edit', $player->id) }}" class="p-2 text-slate-400 hover:text-blue-500 hover:bg-blue-50 rounded-lg transition-colors">
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
</a>
<form action="{{ route('players.destroy', $player->id) }}" method="POST" onsubmit="return confirm('Hapus pemain ini?');" class="inline-block">
@csrf
@method('DELETE')
<button type="submit" class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors">
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
</button>
</form>
</div>
</td>
</tr>
@empty
<tr>
<td colspan="3" class="px-6 py-8 text-center text-slate-400 font-medium">Belum ada data pemain terdaftar.</td>
</tr>
@endforelse
</tbody>
</table>
</div>
</div>
@endsection
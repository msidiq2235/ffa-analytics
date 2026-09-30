@extends('layouts.app')
@section('title', 'Set Line-Up Tim - FFA Analytics')
@section('header', 'Set Line-Up Tim')
@section('subheader', 'Tentukan formasi taktik dan daftar pemain utama')
@section('header_action')
<a href="{{ route('matches.index') }}" class="bg-[#2d5096] hover:bg-[#183262] text-white px-6 py-2.5 rounded-lg text-[13px] font-semibold transition-colors shadow-sm">Kembali</a>
@endsection
@section('content')
<div class="bg-white rounded-[20px] border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] p-8">
<form action="{{ route('matches.updateLineup', $match->id) }}" method="POST">
@csrf
<div class="flex flex-col md:flex-row md:items-center gap-4 mb-8">
<label for="formation" class="text-[13px] font-bold text-[#334155] shrink-0">Formasi Taktik</label>
<div class="relative w-full md:w-64">
<select id="formation" name="formation" required class="w-full px-4 py-2 bg-white border border-[#e2e8f0] rounded-xl text-[12px] text-[#334155] appearance-none focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] transition-shadow shadow-sm cursor-pointer">
<option value="4-3-3" {{ old('formation', $match->formation) == '4-3-3' ? 'selected' : '' }}>4-3-3</option>
<option value="4-4-2" {{ old('formation', $match->formation) == '4-4-2' ? 'selected' : '' }}>4-4-2</option>
<option value="4-2-3-1" {{ old('formation', $match->formation) == '4-2-3-1' ? 'selected' : '' }}>4-2-3-1</option>
<option value="3-5-2" {{ old('formation', $match->formation) == '3-5-2' ? 'selected' : '' }}>3-5-2</option>
<option value="3-4-3" {{ old('formation', $match->formation) == '3-4-3' ? 'selected' : '' }}>3-4-3</option>
</select>
<div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-400">
<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
</div>
</div>
</div>
<div class="flex justify-between items-end mb-4">
<h4 class="text-[14px] font-bold text-[#1e293b]">Daftar Pemain Aktif</h4>
<p class="text-[11px] text-slate-400 italic">Pilih pemain dan tentukan posisi bermainnya.</p>
</div>
<div class="overflow-x-auto border border-slate-200 rounded-xl mb-6">
<table class="w-full text-left border-collapse">
<thead>
<tr class="bg-slate-50 border-b border-slate-200">
<th class="py-3 px-4 text-[10px] font-bold text-[#94a3b8] uppercase tracking-widest w-[8%] text-center">Bawa</th>
<th class="py-3 px-4 text-[10px] font-bold text-[#94a3b8] uppercase tracking-widest w-[42%]">Nama Pemain</th>
<th class="py-3 px-4 text-[10px] font-bold text-[#94a3b8] uppercase tracking-widest w-[15%] text-center">No. Punggung</th>
<th class="py-3 px-4 text-[10px] font-bold text-[#94a3b8] uppercase tracking-widest w-[35%] text-center">Posisi Di Pertandingan</th>
</tr>
</thead>
<tbody class="divide-y divide-slate-100">
@foreach($players as $player)
@php
$isExist = in_array($player->id, $selectedPlayerIds);
$currentPosition = $isExist ? ($match->players->find($player->id)->pivot->position ?? '') : '';
@endphp
<tr class="hover:bg-slate-50 transition-colors">
<td class="py-3 px-4 text-center">
<input type="hidden" name="players[{{ $player->id }}][selected]" value="0">
<input type="checkbox" name="players[{{ $player->id }}][selected]" value="1" {{ $isExist ? 'checked' : '' }} class="w-4 h-4 text-[#2d5096] border-slate-300 rounded focus:ring-[#2d5096]">
</td>
<td class="py-3 px-4 text-[13px] font-bold text-[#1e293b]">{{ $player->name }}</td>
<td class="py-3 px-4 text-[13px] font-bold text-slate-500 text-center">#{{ $player->number ?? $player->jersey_number ?? '0' }}</td>
<td class="py-3 px-4">
<select name="players[{{ $player->id }}][position]" class="w-full px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-[12px] text-slate-600 focus:outline-none focus:border-[#4062D6]">
<option value="ST" {{ $currentPosition == 'ST' ? 'selected' : '' }}>ST (Striker)</option>
<option value="LW" {{ $currentPosition == 'LW' ? 'selected' : '' }}>LW (Left Wing)</option>
<option value="RW" {{ $currentPosition == 'RW' ? 'selected' : '' }}>RW (Right Wing)</option>
<option value="CM" {{ $currentPosition == 'CM' ? 'selected' : '' }}>CM (Central Midfield)</option>
<option value="DM" {{ $currentPosition == 'DM' ? 'selected' : '' }}>DM (Defensive Midfield)</option>
<option value="CB" {{ $currentPosition == 'CB' ? 'selected' : '' }}>CB (Center Back)</option>
<option value="LB" {{ $currentPosition == 'LB' ? 'selected' : '' }}>LB (Left Back)</option>
<option value="RB" {{ $currentPosition == 'RB' ? 'selected' : '' }}>RB (Right Back)</option>
<option value="GK" {{ $currentPosition == 'GK' ? 'selected' : '' }}>GK (Goalkeeper)</option>
<option value="Cadangan" {{ $currentPosition == 'Cadangan' ? 'selected' : '' }}>Cadangan (Bench)</option>
</select>
</td>
</tr>
@endforeach
</tbody>
</table>
</div>
<div class="flex items-center justify-end gap-3 mt-4">
<a href="{{ route('matches.index') }}" class="px-6 py-2.5 bg-white border border-slate-200 text-slate-600 text-[13px] font-bold rounded-lg hover:bg-slate-50 transition-colors">Batal</a>
<button type="submit" class="px-6 py-2.5 bg-[#2d5096] hover:bg-[#183262] text-white text-[13px] font-bold rounded-lg transition-colors shadow-sm active:scale-95">Simpan Line-Up</button>
</div>
</form>
</div>
@endsection
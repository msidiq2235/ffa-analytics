@extends('layouts.app')
@section('title', 'Live Analytics - FFA Analytics')
@section('header', 'Match Statistics Input')
@section('subheader', $match->title . ' vs ' . $match->opponent . ' - ' . \Carbon\Carbon::parse($match->match_date)->format('M d, Y'))
@section('header_action')
<a href="{{ route('matches.index') }}" class="bg-[#2d5096] hover:bg-[#183262] text-white px-6 py-2.5 rounded-lg text-[13px] font-semibold transition-colors shadow-sm">Kembali</a>
@endsection
@section('content')
<div class="flex flex-col lg:flex-row gap-4 h-[calc(100vh-120px)] w-full">
<div class="w-full lg:w-[220px] xl:w-[240px] flex flex-col gap-4 shrink-0 h-full">
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm flex-1 flex flex-col overflow-hidden">
<div class="bg-[#4062D6] text-white text-center py-3 font-bold text-[14px]">Cadangan</div>
<div class="flex-1 overflow-y-auto p-3 space-y-2" id="bench-container" ondragover="allowDrop(event)" ondrop="drop(event)">
@foreach($bench as $player)
<div class="bg-white border border-slate-200 rounded-xl p-2.5 flex items-center gap-3 cursor-grab hover:bg-slate-50 transition-colors shadow-sm bench-player" draggable="true" ondragstart="drag(event)" id="player-{{ $player->id }}" data-id="{{ $player->id }}" data-name="{{ $player->name }}" data-number="{{ $player->jersey_number ?? $player->no_punggung ?? $player->number ?? '00' }}">
<div class="w-8 h-8 rounded-full bg-[#1e293b] text-white flex items-center justify-center text-[11px] font-bold shrink-0">{{ $player->jersey_number ?? $player->no_punggung ?? $player->number ?? '00' }}</div>
<span class="text-[12px] font-bold text-slate-700 truncate">{{ $player->name }}</span>
</div>
@endforeach
</div>
<div class="p-3 bg-blue-50 border-t border-blue-100 m-3 rounded-xl shrink-0">
<div class="flex items-center gap-2 mb-1">
<div class="w-2 h-2 rounded-full bg-emerald-500"></div>
<span class="text-[10px] font-bold text-blue-800">Roster Loaded</span>
</div>
<div class="text-[11px] font-bold text-blue-900">Formation: {{ $match->formation ?? '4-3-3' }}</div>
</div>
</div>
<div class="flex flex-col gap-2 shrink-0">
<button class="w-full py-3 bg-[#4caf50] hover:bg-[#43a047] text-white rounded-xl font-bold text-[13px] flex items-center justify-center gap-2 shadow-sm transition-colors">
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
SAVE
</button>
@if(auth()->user()->role === 'Analis')
<form action="{{ route('matches.lock', $match->id) }}" method="POST" class="m-0" onsubmit="return confirm('Yakin ingin mengunci data? Data yang dikunci tidak bisa diubah lagi.');">
@csrf
<button type="submit" class="w-full py-3 bg-[#d32f2f] hover:bg-[#c62828] text-white rounded-xl font-bold text-[13px] flex items-center justify-center gap-2 shadow-sm transition-colors">
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
LOCK
</button>
</form>
@endif
<a href="#" class="w-full py-3 bg-[#4062D6] hover:bg-[#3251b5] text-white rounded-xl font-bold text-[13px] flex items-center justify-center gap-2 shadow-sm transition-colors text-center">
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
EXPORT
</a>
</div>
</div>
<div class="flex-1 flex flex-col gap-4 min-w-0 h-full max-w-[750px]">
<div class="bg-[#2e7d32] flex-1 rounded-2xl border-4 border-[#1b5e20] relative overflow-hidden flex flex-col items-center justify-center p-2 shadow-inner min-h-[300px]" id="pitch-container" ondragover="allowDrop(event)" ondrop="dropToPitch(event)">
<div class="absolute inset-0 border-[2px] border-white/40 m-4 pointer-events-none"></div>
<div class="absolute top-4 left-1/2 -translate-x-1/2 w-[40%] h-[15%] border-[2px] border-white/40 pointer-events-none"></div>
<div class="absolute bottom-4 left-1/2 -translate-x-1/2 w-[40%] h-[15%] border-[2px] border-white/40 pointer-events-none"></div>
<div class="absolute top-1/2 left-0 w-full h-[2px] bg-white/40 -translate-y-1/2 pointer-events-none"></div>
<div class="absolute top-1/2 left-1/2 w-[20%] aspect-square border-[2px] border-white/40 rounded-full -translate-x-1/2 -translate-y-1/2 pointer-events-none"></div>
<div class="relative w-full h-full flex flex-col justify-between py-6 z-10" id="pitch-players">
<div class="flex justify-around px-2 sm:px-10">
@foreach($startingEleven->whereIn('pivot.position', ['LW', 'ST', 'RW']) as $fwd)
<div class="flex flex-col items-center cursor-pointer pitch-player group" data-id="{{ $fwd->id }}" onclick="selectPlayer({{ $fwd->id }}, '{{ $fwd->name }}')">
<span class="text-[10px] font-bold text-white mb-1 drop-shadow-md truncate max-w-[60px] text-center">{{ explode(' ', $fwd->name)[0] }}</span>
<div class="w-9 h-9 rounded-full bg-[#1e293b] border-2 border-white text-white flex items-center justify-center text-[12px] font-bold group-hover:bg-[#4062D6] transition-colors">{{ $fwd->jersey_number ?? $fwd->no_punggung ?? $fwd->number ?? '00' }}</div>
</div>
@endforeach
</div>
<div class="flex justify-around px-2 sm:px-4">
@foreach($startingEleven->whereIn('pivot.position', ['CM', 'DM']) as $mid)
<div class="flex flex-col items-center cursor-pointer pitch-player group" data-id="{{ $mid->id }}" onclick="selectPlayer({{ $mid->id }}, '{{ $mid->name }}')">
<span class="text-[10px] font-bold text-white mb-1 drop-shadow-md truncate max-w-[60px] text-center">{{ explode(' ', $mid->name)[0] }}</span>
<div class="w-9 h-9 rounded-full bg-[#1e293b] border-2 border-white text-white flex items-center justify-center text-[12px] font-bold group-hover:bg-[#4062D6] transition-colors">{{ $mid->jersey_number ?? $mid->no_punggung ?? $mid->number ?? '00' }}</div>
</div>
@endforeach
</div>
<div class="flex justify-around px-1 sm:px-2">
@foreach($startingEleven->whereIn('pivot.position', ['LB', 'CB', 'RB']) as $def)
<div class="flex flex-col items-center cursor-pointer pitch-player group" data-id="{{ $def->id }}" onclick="selectPlayer({{ $def->id }}, '{{ $def->name }}')">
<span class="text-[10px] font-bold text-white mb-1 drop-shadow-md truncate max-w-[60px] text-center">{{ explode(' ', $def->name)[0] }}</span>
<div class="w-9 h-9 rounded-full bg-[#1e293b] border-2 border-white text-white flex items-center justify-center text-[12px] font-bold group-hover:bg-[#4062D6] transition-colors">{{ $def->jersey_number ?? $def->no_punggung ?? $def->number ?? '00' }}</div>
</div>
@endforeach
</div>
<div class="flex justify-center">
@foreach($startingEleven->where('pivot.position', 'GK') as $gk)
<div class="flex flex-col items-center cursor-pointer pitch-player group" data-id="{{ $gk->id }}" onclick="selectPlayer({{ $gk->id }}, '{{ $gk->name }}')">
<span class="text-[10px] font-bold text-white mb-1 drop-shadow-md truncate max-w-[60px] text-center">{{ explode(' ', $gk->name)[0] }}</span>
<div class="w-9 h-9 rounded-full bg-amber-500 border-2 border-white text-white flex items-center justify-center text-[12px] font-bold group-hover:bg-amber-400 transition-colors">{{ $gk->jersey_number ?? $gk->no_punggung ?? $gk->number ?? '01' }}</div>
</div>
@endforeach
</div>
</div>
<div class="absolute bottom-4 right-4 bg-[#111827]/80 backdrop-blur px-3 py-2 rounded-lg border border-white/10 text-right pointer-events-none hidden sm:block">
<div class="flex items-center gap-1.5 mb-1 justify-end">
<div class="w-1.5 h-1.5 rounded-full bg-emerald-400"></div>
<span class="text-[9px] font-bold text-white">Format Aktif</span>
</div>
<div class="text-[10px] text-slate-300">Formation: {{ $match->formation ?? '4-3-3' }}</div>
</div>
</div>
<div class="h-[160px] bg-white rounded-2xl border border-slate-200 flex flex-col shadow-sm shrink-0">
<div class="px-4 py-2 flex items-center justify-between border-b border-slate-100">
<span class="text-[11px] text-slate-500">Tekan [Spasi] Untuk Masuk Ke Mode Input data</span>
<div class="w-9 h-5 bg-slate-200 rounded-full relative cursor-pointer shrink-0" id="input-toggle" onclick="toggleInputMode()">
<div class="w-4 h-4 bg-white rounded-full absolute left-0.5 top-0.5 shadow-sm transition-transform" id="input-toggle-dot"></div>
</div>
</div>
<div class="bg-slate-200 text-center py-1 text-[10px] font-bold text-slate-600 uppercase tracking-widest border-b border-slate-300">Histori Input</div>
<div class="flex-1 overflow-y-auto p-2 bg-slate-100 text-[11px] text-slate-600 font-mono" id="history-log"></div>
<div class="bg-slate-200 text-center py-1.5 text-[9px] font-bold text-red-500 shrink-0">!! Tekan Backspace untuk Undo Input !!</div>
</div>
</div>
<div class="w-full lg:w-[360px] xl:w-[400px] bg-white rounded-2xl border border-slate-200 shadow-sm flex flex-col shrink-0 h-full overflow-hidden relative">
<div class="bg-[#4062D6] text-white p-4 font-bold text-[16px] h-[56px] flex items-center shrink-0" id="active-player-name">Pilih Player Untuk Memunculkan Data</div>
<div class="flex-1 overflow-y-auto p-3 hidden" id="stats-panel">
<div class="mb-3">
<div class="bg-[#4062D6] text-white px-3 py-1.5 rounded-t-xl font-bold text-[12px]">Shooting</div>
<div class="bg-slate-50 border-x border-b border-slate-200 rounded-b-xl p-2 grid grid-cols-2 gap-2">
<div class="bg-white border border-slate-200 rounded-lg p-1.5 flex items-center justify-between shadow-sm">
<div class="flex items-center gap-1.5"><span class="w-5 h-5 bg-slate-100 rounded flex items-center justify-center font-bold text-[11px]">1</span><span class="text-[9px] font-bold text-slate-600 leading-tight">Shots on<br>target</span></div>
<div class="flex items-center gap-0.5"><button class="w-5 h-5 bg-[#4062D6] text-white rounded font-bold text-[11px] flex items-center justify-center" onclick="executeStatUpdate(activePlayerId, '1', 'decrement', false)">-</button><span class="w-5 text-center font-bold text-[12px]" id="val-1">0</span><button class="w-5 h-5 bg-[#4062D6] text-white rounded font-bold text-[11px] flex items-center justify-center" onclick="executeStatUpdate(activePlayerId, '1', 'increment', false)">+</button></div>
</div>
<div class="bg-white border border-slate-200 rounded-lg p-1.5 flex items-center justify-between shadow-sm">
<div class="flex items-center gap-1.5"><span class="w-5 h-5 bg-slate-100 rounded flex items-center justify-center font-bold text-[11px]">3</span><span class="text-[9px] font-bold text-slate-600 leading-tight">Goal</span></div>
<div class="flex items-center gap-0.5"><button class="w-5 h-5 bg-[#4062D6] text-white rounded font-bold text-[11px] flex items-center justify-center" onclick="executeStatUpdate(activePlayerId, '3', 'decrement', false)">-</button><span class="w-5 text-center font-bold text-[12px]" id="val-3">0</span><button class="w-5 h-5 bg-[#4062D6] text-white rounded font-bold text-[11px] flex items-center justify-center" onclick="executeStatUpdate(activePlayerId, '3', 'increment', false)">+</button></div>
</div>
<div class="bg-white border border-slate-200 rounded-lg p-1.5 flex items-center justify-between shadow-sm">
<div class="flex items-center gap-1.5"><span class="w-5 h-5 bg-slate-100 rounded flex items-center justify-center font-bold text-[11px]">2</span><span class="text-[9px] font-bold text-slate-600 leading-tight">Shots off<br>target</span></div>
<div class="flex items-center gap-0.5"><button class="w-5 h-5 bg-[#4062D6] text-white rounded font-bold text-[11px] flex items-center justify-center" onclick="executeStatUpdate(activePlayerId, '2', 'decrement', false)">-</button><span class="w-5 text-center font-bold text-[12px]" id="val-2">0</span><button class="w-5 h-5 bg-[#4062D6] text-white rounded font-bold text-[11px] flex items-center justify-center" onclick="executeStatUpdate(activePlayerId, '2', 'increment', false)">+</button></div>
</div>
<div class="bg-white border border-slate-200 rounded-lg p-1.5 flex items-center justify-between shadow-sm">
<div class="flex items-center gap-1.5"><span class="w-5 h-5 bg-slate-100 rounded flex items-center justify-center font-bold text-[11px]">4</span><span class="text-[9px] font-bold text-slate-600 leading-tight">Penalty<br>Goal</span></div>
<div class="flex items-center gap-0.5"><button class="w-5 h-5 bg-[#4062D6] text-white rounded font-bold text-[11px] flex items-center justify-center" onclick="executeStatUpdate(activePlayerId, '4', 'decrement', false)">-</button><span class="w-5 text-center font-bold text-[12px]" id="val-4">0</span><button class="w-5 h-5 bg-[#4062D6] text-white rounded font-bold text-[11px] flex items-center justify-center" onclick="executeStatUpdate(activePlayerId, '4', 'increment', false)">+</button></div>
</div>
</div>
</div>
<div class="mb-3">
<div class="bg-[#4062D6] text-white px-3 py-1.5 rounded-t-xl font-bold text-[12px]">Passing</div>
<div class="bg-slate-50 border-x border-b border-slate-200 rounded-b-xl p-2 grid grid-cols-2 gap-2">
<div class="bg-white border border-slate-200 rounded-lg p-1.5 flex items-center justify-between shadow-sm">
<div class="flex items-center gap-1.5"><span class="w-5 h-5 bg-slate-100 rounded flex items-center justify-center font-bold text-[11px]">5</span><span class="text-[9px] font-bold text-slate-600 leading-tight">Successful<br>Passes</span></div>
<div class="flex items-center gap-0.5"><button class="w-5 h-5 bg-[#4062D6] text-white rounded font-bold text-[11px] flex items-center justify-center" onclick="executeStatUpdate(activePlayerId, '5', 'decrement', false)">-</button><span class="w-5 text-center font-bold text-[12px]" id="val-5">0</span><button class="w-5 h-5 bg-[#4062D6] text-white rounded font-bold text-[11px] flex items-center justify-center" onclick="executeStatUpdate(activePlayerId, '5', 'increment', false)">+</button></div>
</div>
<div class="bg-white border border-slate-200 rounded-lg p-1.5 flex items-center justify-between shadow-sm">
<div class="flex items-center gap-1.5"><span class="w-5 h-5 bg-slate-100 rounded flex items-center justify-center font-bold text-[11px]">7</span><span class="text-[9px] font-bold text-slate-600 leading-tight">Assist</span></div>
<div class="flex items-center gap-0.5"><button class="w-5 h-5 bg-[#4062D6] text-white rounded font-bold text-[11px] flex items-center justify-center" onclick="executeStatUpdate(activePlayerId, '7', 'decrement', false)">-</button><span class="w-5 text-center font-bold text-[12px]" id="val-7">0</span><button class="w-5 h-5 bg-[#4062D6] text-white rounded font-bold text-[11px] flex items-center justify-center" onclick="executeStatUpdate(activePlayerId, '7', 'increment', false)">+</button></div>
</div>
<div class="bg-white border border-slate-200 rounded-lg p-1.5 flex items-center justify-between shadow-sm">
<div class="flex items-center gap-1.5"><span class="w-5 h-5 bg-slate-100 rounded flex items-center justify-center font-bold text-[11px]">6</span><span class="text-[9px] font-bold text-slate-600 leading-tight">Successful<br>Crosses</span></div>
<div class="flex items-center gap-0.5"><button class="w-5 h-5 bg-[#4062D6] text-white rounded font-bold text-[11px] flex items-center justify-center" onclick="executeStatUpdate(activePlayerId, '6', 'decrement', false)">-</button><span class="w-5 text-center font-bold text-[12px]" id="val-6">0</span><button class="w-5 h-5 bg-[#4062D6] text-white rounded font-bold text-[11px] flex items-center justify-center" onclick="executeStatUpdate(activePlayerId, '6', 'increment', false)">+</button></div>
</div>
<div class="bg-white border border-slate-200 rounded-lg p-1.5 flex items-center justify-between shadow-sm">
<div class="flex items-center gap-1.5"><span class="w-5 h-5 bg-slate-100 rounded flex items-center justify-center font-bold text-[11px]">8</span><span class="text-[9px] font-bold text-slate-600 leading-tight">Chance<br>Created</span></div>
<div class="flex items-center gap-0.5"><button class="w-5 h-5 bg-[#4062D6] text-white rounded font-bold text-[11px] flex items-center justify-center" onclick="executeStatUpdate(activePlayerId, '8', 'decrement', false)">-</button><span class="w-5 text-center font-bold text-[12px]" id="val-8">0</span><button class="w-5 h-5 bg-[#4062D6] text-white rounded font-bold text-[11px] flex items-center justify-center" onclick="executeStatUpdate(activePlayerId, '8', 'increment', false)">+</button></div>
</div>
</div>
</div>
<div class="grid grid-cols-2 gap-3 pb-3">
<div>
<div class="bg-[#4062D6] text-white px-3 py-1.5 rounded-t-xl font-bold text-[12px]">Possession</div>
<div class="bg-slate-50 border-x border-b border-slate-200 rounded-b-xl p-2 space-y-2 flex-1">
<div class="bg-white border border-slate-200 rounded-lg p-1.5 flex items-center justify-between shadow-sm">
<div class="flex items-center gap-1.5"><span class="w-5 h-5 bg-slate-100 rounded flex items-center justify-center font-bold text-[11px]">Q</span><span class="text-[9px] font-bold text-slate-600 leading-tight">Dribble</span></div>
<div class="flex items-center gap-0.5"><button class="w-5 h-5 bg-[#4062D6] text-white rounded font-bold text-[11px] flex items-center justify-center" onclick="executeStatUpdate(activePlayerId, 'Q', 'decrement', false)">-</button><span class="w-5 text-center font-bold text-[12px]" id="val-Q">0</span><button class="w-5 h-5 bg-[#4062D6] text-white rounded font-bold text-[11px] flex items-center justify-center" onclick="executeStatUpdate(activePlayerId, 'Q', 'increment', false)">+</button></div>
</div>
<div class="bg-white border border-slate-200 rounded-lg p-1.5 flex items-center justify-between shadow-sm">
<div class="flex items-center gap-1.5"><span class="w-5 h-5 bg-slate-100 rounded flex items-center justify-center font-bold text-[11px]">W</span><span class="text-[9px] font-bold text-slate-600 leading-tight">Successfull<br>Dribble</span></div>
<div class="flex items-center gap-0.5"><button class="w-5 h-5 bg-[#4062D6] text-white rounded font-bold text-[11px] flex items-center justify-center" onclick="executeStatUpdate(activePlayerId, 'W', 'decrement', false)">-</button><span class="w-5 text-center font-bold text-[12px]" id="val-W">0</span><button class="w-5 h-5 bg-[#4062D6] text-white rounded font-bold text-[11px] flex items-center justify-center" onclick="executeStatUpdate(activePlayerId, 'W', 'increment', false)">+</button></div>
</div>
<div class="bg-white border border-slate-200 rounded-lg p-1.5 flex items-center justify-between shadow-sm">
<div class="flex items-center gap-1.5"><span class="w-5 h-5 bg-slate-100 rounded flex items-center justify-center font-bold text-[11px]">E</span><span class="text-[9px] font-bold text-slate-600 leading-tight">Unsuccessfull<br>Dribble</span></div>
<div class="flex items-center gap-0.5"><button class="w-5 h-5 bg-[#4062D6] text-white rounded font-bold text-[11px] flex items-center justify-center" onclick="executeStatUpdate(activePlayerId, 'E', 'decrement', false)">-</button><span class="w-5 text-center font-bold text-[12px]" id="val-E">0</span><button class="w-5 h-5 bg-[#4062D6] text-white rounded font-bold text-[11px] flex items-center justify-center" onclick="executeStatUpdate(activePlayerId, 'E', 'increment', false)">+</button></div>
</div>
</div>
</div>
<div>
<div class="bg-[#4062D6] text-white px-3 py-1.5 rounded-t-xl font-bold text-[12px]">Defending</div>
<div class="bg-slate-50 border-x border-b border-slate-200 rounded-b-xl p-2 space-y-2 flex-1">
<div class="bg-white border border-slate-200 rounded-lg p-1.5 flex items-center justify-between shadow-sm">
<div class="flex items-center gap-1.5"><span class="w-5 h-5 bg-slate-100 rounded flex items-center justify-center font-bold text-[11px]">Z</span><span class="text-[9px] font-bold text-slate-600 leading-tight">Tackles</span></div>
<div class="flex items-center gap-0.5"><button class="w-5 h-5 bg-[#4062D6] text-white rounded font-bold text-[11px] flex items-center justify-center" onclick="executeStatUpdate(activePlayerId, 'Z', 'decrement', false)">-</button><span class="w-5 text-center font-bold text-[12px]" id="val-Z">0</span><button class="w-5 h-5 bg-[#4062D6] text-white rounded font-bold text-[11px] flex items-center justify-center" onclick="executeStatUpdate(activePlayerId, 'Z', 'increment', false)">+</button></div>
</div>
<div class="bg-white border border-slate-200 rounded-lg p-1.5 flex items-center justify-between shadow-sm">
<div class="flex items-center gap-1.5"><span class="w-5 h-5 bg-slate-100 rounded flex items-center justify-center font-bold text-[11px]">X</span><span class="text-[9px] font-bold text-slate-600 leading-tight">Fouls<br>Committed</span></div>
<div class="flex items-center gap-0.5"><button class="w-5 h-5 bg-[#4062D6] text-white rounded font-bold text-[11px] flex items-center justify-center" onclick="executeStatUpdate(activePlayerId, 'X', 'decrement', false)">-</button><span class="w-5 text-center font-bold text-[12px]" id="val-X">0</span><button class="w-5 h-5 bg-[#4062D6] text-white rounded font-bold text-[11px] flex items-center justify-center" onclick="executeStatUpdate(activePlayerId, 'X', 'increment', false)">+</button></div>
</div>
<div class="bg-white border border-slate-200 rounded-lg p-1.5 flex items-center justify-between shadow-sm">
<div class="flex items-center gap-1.5"><span class="w-5 h-5 bg-slate-100 rounded flex items-center justify-center font-bold text-[11px]">C</span><span class="text-[9px] font-bold text-slate-600 leading-tight">Dribbled<br>Past</span></div>
<div class="flex items-center gap-0.5"><button class="w-5 h-5 bg-[#4062D6] text-white rounded font-bold text-[11px] flex items-center justify-center" onclick="executeStatUpdate(activePlayerId, 'C', 'decrement', false)">-</button><span class="w-5 text-center font-bold text-[12px]" id="val-C">0</span><button class="w-5 h-5 bg-[#4062D6] text-white rounded font-bold text-[11px] flex items-center justify-center" onclick="executeStatUpdate(activePlayerId, 'C', 'increment', false)">+</button></div>
</div>
<div class="bg-white border border-slate-200 rounded-lg p-1.5 flex items-center justify-between shadow-sm">
<div class="flex items-center gap-1.5"><span class="w-5 h-5 bg-slate-100 rounded flex items-center justify-center font-bold text-[11px]">V</span><span class="text-[9px] font-bold text-slate-600 leading-tight">Interceptions</span></div>
<div class="flex items-center gap-0.5"><button class="w-5 h-5 bg-[#4062D6] text-white rounded font-bold text-[11px] flex items-center justify-center" onclick="executeStatUpdate(activePlayerId, 'V', 'decrement', false)">-</button><span class="w-5 text-center font-bold text-[12px]" id="val-V">0</span><button class="w-5 h-5 bg-[#4062D6] text-white rounded font-bold text-[11px] flex items-center justify-center" onclick="executeStatUpdate(activePlayerId, 'V', 'increment', false)">+</button></div>
</div>
</div>
</div>
</div>
</div>
<div id="stats-placeholder" class="absolute inset-0 top-[56px] flex items-center justify-center text-[12px] font-bold text-slate-400 bg-slate-50">
Pilih Player Untuk Memunculkan Data
</div>
</div>
</div>
<script>
let activePlayerId = null;
let inputMode = false;
let matchId = {{ $match->id }};
let csrfToken = '{{ csrf_token() }}';
let inputHistory = [];

function toggleInputMode() {
inputMode = !inputMode;
const dot = document.getElementById('input-toggle-dot');
const toggle = document.getElementById('input-toggle');
if(inputMode) {
toggle.classList.remove('bg-slate-200');
toggle.classList.add('bg-[#4062D6]');
dot.classList.add('translate-x-4');
} else {
toggle.classList.remove('bg-[#4062D6]');
toggle.classList.add('bg-slate-200');
dot.classList.remove('translate-x-4');
}
}

function selectPlayer(playerId, playerName) {
activePlayerId = playerId;
document.getElementById('active-player-name').innerText = playerName;
document.getElementById('stats-placeholder').classList.add('hidden');
document.getElementById('stats-panel').classList.remove('hidden');

fetch(`/matches/${matchId}/player-stats/${playerId}`)
.then(res => res.json())
.then(data => {
if(data.success) {
for (const [key, value] of Object.entries(data.stats)) {
const el = document.getElementById(`val-${key}`);
if (el) el.innerText = value;
}
}
});
}

function executeStatUpdate(playerId, statKey, action, isUndo) {
if(!playerId) return;
fetch(`/matches/${matchId}/statistics`, {
method: 'POST',
headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
body: JSON.stringify({ player_id: playerId, stat_key: statKey, action: action })
})
.then(res => res.json())
.then(data => {
if(data.success) {
if(playerId === activePlayerId) {
document.getElementById(`val-${statKey}`).innerText = data.current_value;
}
if(!isUndo) {
inputHistory.push({ playerId: playerId, statKey: statKey, action: action });
logHistory(playerId, statKey, action);
} else {
logUndoHistory(playerId, statKey);
}
}
});
}

function undoLastInput() {
if(inputHistory.length === 0) return;
const lastAction = inputHistory.pop();
const reverseAction = lastAction.action === 'increment' ? 'decrement' : 'increment';
executeStatUpdate(lastAction.playerId, lastAction.statKey, reverseAction, true);
}

function logHistory(playerId, key, action) {
const historyLog = document.getElementById('history-log');
const time = new Date().toLocaleTimeString();
const actionText = action === 'increment' ? '+1' : '-1';
const entry = document.createElement('div');
entry.className = 'border-b border-slate-200 py-1';
entry.innerText = `[${time}] Player ${playerId} | Key: ${key} | ${actionText}`;
historyLog.prepend(entry);
}

function logUndoHistory(playerId, key) {
const historyLog = document.getElementById('history-log');
const time = new Date().toLocaleTimeString();
const entry = document.createElement('div');
entry.className = 'border-b border-red-200 py-1 text-red-500 font-bold';
entry.innerText = `[${time}] UNDO Player ${playerId} | Key: ${key}`;
historyLog.prepend(entry);
}

document.addEventListener('keydown', (e) => {
if(e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return;

if(e.code === 'Space') {
e.preventDefault();
toggleInputMode();
return;
}

if(inputMode) {
if(e.code === 'Backspace') {
e.preventDefault();
undoLastInput();
return;
}

if(activePlayerId) {
const keyMap = ['1','2','3','4','5','6','7','8','q','w','e','z','x','c','v'];
const pressed = e.key.toLowerCase();
if(keyMap.includes(pressed)) {
executeStatUpdate(activePlayerId, pressed.toUpperCase(), 'increment', false);
}
}
}
});

let draggedItem = null;
function drag(e) { draggedItem = e.target; }
function allowDrop(e) { e.preventDefault(); }
function drop(e) {
e.preventDefault();
if(e.target.id === 'bench-container' || e.target.closest('#bench-container')) {
document.getElementById('bench-container').appendChild(draggedItem);
}
}
function dropToPitch(e) {
e.preventDefault();
const targetPlayer = e.target.closest('.pitch-player');
if (targetPlayer && draggedItem) {
const cloneDragged = draggedItem.cloneNode(true);
cloneDragged.classList.remove('flex-row', 'bg-white', 'border', 'w-full', 'justify-start', 'p-2.5');
cloneDragged.classList.add('flex-col', 'items-center', 'cursor-pointer', 'pitch-player', 'group');
cloneDragged.innerHTML = `
<span class="text-[10px] font-bold text-white mb-1 drop-shadow-md truncate max-w-[60px] text-center">${draggedItem.dataset.name.split(' ')[0]}</span>
<div class="w-9 h-9 rounded-full bg-[#1e293b] border-2 border-white text-white flex items-center justify-center text-[12px] font-bold group-hover:bg-[#4062D6] transition-colors">${draggedItem.dataset.number}</div>
`;
cloneDragged.setAttribute('onclick', `selectPlayer(${draggedItem.dataset.id}, '${draggedItem.dataset.name}')`);

const originalBenchHTML = `
<div class="bg-white border border-slate-200 rounded-xl p-2.5 flex items-center gap-3 cursor-grab hover:bg-slate-50 transition-colors shadow-sm bench-player" draggable="true" ondragstart="drag(event)" data-id="${targetPlayer.dataset.id}" data-name="${targetPlayer.querySelector('span').innerText}" data-number="${targetPlayer.querySelector('div').innerText}">
<div class="w-8 h-8 rounded-full bg-[#1e293b] text-white flex items-center justify-center text-[11px] font-bold shrink-0">${targetPlayer.querySelector('div').innerText}</div>
<span class="text-[12px] font-bold text-slate-700 truncate">${targetPlayer.querySelector('span').innerText}</span>
</div>
`;
document.getElementById('bench-container').insertAdjacentHTML('beforeend', originalBenchHTML);

targetPlayer.replaceWith(cloneDragged);
draggedItem.remove();
draggedItem = null;
}
}
</script>
@endsection
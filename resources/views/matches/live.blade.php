@extends('layouts.app')
@section('title', 'Live Analytics - FFA Analytics')
@section('header', 'Match Statistics Input')
@section('subheader', $match->title . ' vs ' . $match->opponent . ' - ' . \Carbon\Carbon::parse($match->match_date)->format('M d, Y'))
@section('content')

@php
    // ===== Helper tampilan (tidak mengubah logika / ID / fungsi JS) =====
    $jersey = fn($p, $d = '00') => $p->jersey_number ?? $p->no_punggung ?? $p->number ?? $d;

    $offsets = ['LW' => 'mt-12', 'RW' => 'mt-12', 'DM' => 'mt-14', 'CB' => 'mt-8'];

    $pitchPlayer = function ($p, $default = '00') use ($jersey, $offsets) {
        $pos = $p->pivot->position ?? '';
        $off = $offsets[$pos] ?? '';
        $name = e($p->name);
        $num = e($jersey($p, $default));
        return '<div class="flex flex-col items-center cursor-pointer pitch-player group ' . $off . '" data-id="' . $p->id . '" onclick="selectPlayer(' . $p->id . ', \'' . $name . '\')">'
            . '<span class="text-[13px] font-medium text-white mb-1.5 drop-shadow-md truncate max-w-[110px] text-center">' . $name . '</span>'
            . '<div class="pitch-circle w-11 h-11 rounded-full bg-[#111827] border-[3px] border-white text-white flex items-center justify-center text-[13px] font-bold transition-colors shadow-lg">' . $num . '</div>'
            . '</div>';
    };

    $byPos = fn(array $positions) => $startingEleven->filter(fn($p) => in_array($p->pivot->position ?? '', $positions))->values();

    $forwards = $byPos(['LW'])->concat($byPos(['ST']))->concat($byPos(['RW']));
    $cms = $byPos(['CM']);
    $mids = $cms->slice(0, 1)->concat($byPos(['DM']))->concat($cms->slice(1))->values();
    $defs = $byPos(['LB'])->concat($byPos(['CB']))->concat($byPos(['RB']));
    $keepers = $byPos(['GK']);

    $statRow = function ($key, $label) {
        return '<div class="flex items-stretch gap-3 h-full min-h-[56px]">'
            . '<div class="aspect-square h-full min-w-[52px] shrink-0 bg-white border border-slate-200 rounded-xl shadow-md flex items-center justify-center text-[28px] font-medium text-slate-800">' . $key . '</div>'
            . '<div class="flex-1 min-w-0 bg-white border border-slate-200 rounded-xl shadow-md px-3 flex items-center justify-between gap-2">'
            . '<span class="text-[14px] xl:text-[15px] font-medium text-slate-800 leading-tight">' . $label . '</span>'
            . '<div class="flex items-center gap-1.5 shrink-0">'
            . '<button class="w-8 h-8 bg-[#2563eb] hover:bg-[#1d4ed8] text-white rounded-md font-bold text-[16px] flex items-center justify-center transition-colors" onclick="executeStatUpdate(activePlayerId, \'' . $key . '\', \'decrement\', false)">-</button>'
            . '<span class="w-10 h-8 bg-white border border-slate-300 rounded-md text-center font-medium text-[15px] flex items-center justify-center" id="val-' . $key . '">0</span>'
            . '<button class="w-8 h-8 bg-[#2563eb] hover:bg-[#1d4ed8] text-white rounded-md font-bold text-[16px] flex items-center justify-center transition-colors" onclick="executeStatUpdate(activePlayerId, \'' . $key . '\', \'increment\', false)">+</button>'
            . '</div></div></div>';
    };
@endphp

<style>
    .pitch-player.is-selected .pitch-circle { background-color: #00f58a; color: #062b1a; }
    .pitch-player:hover .pitch-circle { background-color: #2563eb; }
    .pitch-player.is-selected:hover .pitch-circle { background-color: #00f58a; }
</style>

<div class="flex flex-col lg:flex-row gap-6 w-full items-stretch lg:h-[calc(100vh-150px)] lg:min-h-[900px]">

    <!-- KOLOM KIRI: Cadangan + Roster + Kembali -->
    <div class="w-full lg:w-[280px] xl:w-[300px] flex flex-col gap-5 shrink-0 min-h-0">
        <div class="bg-white rounded-2xl shadow-lg flex flex-col overflow-hidden min-h-[300px] flex-1">
            <div class="bg-gradient-to-br from-[#1e3a8a] to-[#3b82f6] text-white text-center py-5 font-medium text-[22px] shrink-0">Cadangan</div>
            <div class="flex-1 overflow-y-auto bg-[#eef0f4]" id="bench-container" ondragover="allowDrop(event)" ondrop="drop(event)">
                @foreach($bench as $player)
                <div class="bg-white border-b-[3px] border-[#eef0f4] px-5 py-3 flex items-center gap-4 cursor-grab hover:bg-slate-50 transition-colors bench-player" draggable="true" ondragstart="drag(event)" id="player-{{ $player->id }}" data-id="{{ $player->id }}" data-name="{{ $player->name }}" data-number="{{ $jersey($player) }}">
                    <div class="w-11 h-11 rounded-full bg-[#111c34] text-white flex items-center justify-center text-[14px] font-bold shrink-0">{{ $jersey($player) }}</div>
                    <span class="text-[16px] font-medium text-slate-800 truncate">{{ $player->name }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <div class="bg-gradient-to-br from-[#2563eb] to-[#3b82f6] rounded-2xl shadow-lg px-5 py-4 shrink-0">
            <div class="flex items-center gap-2 mb-1.5">
                <div class="w-2 h-2 rounded-full bg-emerald-400"></div>
                <span class="text-[12px] font-medium text-emerald-300">Roster Loaded</span>
            </div>
            <div class="text-[14px] font-medium text-white">Formation: {{ $match->formation ?? '4-3-3' }}</div>
        </div>

        <a href="{{ route('matches.index') }}" class="mt-auto shrink-0 bg-gradient-to-br from-[#1e3a8a] to-[#3b82f6] hover:brightness-110 text-white text-center py-4 rounded-2xl text-[26px] font-medium shadow-lg transition">Kembali</a>
    </div>

    <!-- KOLOM TENGAH: Lapangan, toggle & Histori Input -->
    <div class="flex-1 flex flex-col gap-4 min-w-0 w-full min-h-0">
        <!-- Lapangan -->
        <div class="bg-[#0e5a2c] rounded-2xl relative overflow-hidden flex flex-col items-center justify-center p-4 shadow-lg min-h-[520px] flex-[2.6]" id="pitch-container" ondragover="allowDrop(event)" ondrop="dropToPitch(event)">
            <div class="absolute inset-0 border-[2px] border-white/25 m-9 pointer-events-none"></div>
            <div class="absolute top-9 left-1/2 -translate-x-1/2 w-[60%] h-[15%] border-[2px] border-white/25 pointer-events-none"></div>
            <div class="absolute bottom-9 left-1/2 -translate-x-1/2 w-[60%] h-[15%] border-[2px] border-white/25 pointer-events-none"></div>
            <div class="absolute top-1/2 left-9 right-9 h-[2px] bg-white/25 -translate-y-1/2 pointer-events-none"></div>
            <div class="absolute top-1/2 left-1/2 w-[22%] aspect-square border-[2px] border-white/25 rounded-full -translate-x-1/2 -translate-y-1/2 pointer-events-none"></div>
            <div class="absolute top-1/2 left-1/2 w-1.5 h-1.5 bg-white/30 rounded-full -translate-x-1/2 -translate-y-1/2 pointer-events-none"></div>

            <div class="relative w-full h-full flex flex-col justify-between py-6 z-10" id="pitch-players">
                <div class="flex justify-around items-start px-8 sm:px-14">
                    @foreach($forwards as $fwd) {!! $pitchPlayer($fwd) !!} @endforeach
                </div>
                <div class="flex justify-around items-start px-8 sm:px-14">
                    @foreach($mids as $mid) {!! $pitchPlayer($mid) !!} @endforeach
                </div>
                <div class="flex justify-around items-start px-4 sm:px-10">
                    @foreach($defs as $def) {!! $pitchPlayer($def) !!} @endforeach
                </div>
                <div class="flex justify-center">
                    @foreach($keepers as $gk) {!! $pitchPlayer($gk, '01') !!} @endforeach
                </div>
            </div>

            <div class="absolute bottom-12 right-12 bg-[#111827]/90 px-4 py-2.5 rounded-lg border border-white/10 pointer-events-none hidden sm:block z-20">
                <div class="flex items-center gap-1.5 mb-1">
                    <div class="w-1.5 h-1.5 rounded-full bg-emerald-400"></div>
                    <span class="text-[10px] font-medium text-emerald-400">Formasi Aktif</span>
                </div>
                <div class="text-[12px] text-white">Formation: {{ $match->formation ?? '4-3-3' }}</div>
            </div>
        </div>

        <!-- Toggle mode input -->
        <div class="flex items-center justify-between px-1 shrink-0">
            <span class="text-[17px] text-slate-500">Tekan [Spasi] Untuk Masuk Ke Mode Input data</span>
            <div class="w-14 h-8 bg-slate-400 rounded-full relative cursor-pointer shrink-0 transition-colors" id="input-toggle" onclick="toggleInputMode()">
                <div class="w-6 h-6 bg-white rounded-full absolute left-1 top-1 shadow-sm transition-transform" id="input-toggle-dot"></div>
            </div>
        </div>

        <!-- Histori Input -->
        <div class="flex-1 min-h-[150px] bg-[#ededed] rounded-2xl border border-slate-300 flex flex-col shadow-lg overflow-hidden">
            <div class="bg-[#dcdcdc] text-center py-1.5 text-[16px] font-medium text-black shrink-0">HISTORI INPUT</div>
            <div class="flex-1 overflow-y-auto p-3 bg-[#ededed] text-[12px] text-slate-600 font-mono" id="history-log"></div>
            <div class="bg-[#dcdcdc] text-center py-1.5 text-[12px] font-medium text-red-600 shrink-0">!!! Tekan Backspace untuk Undo Input !!!</div>
        </div>
    </div>

    <!-- KOLOM KANAN: Panel Statistik -->
    <div class="w-full lg:w-[42%] flex flex-col gap-5 shrink-0 min-h-0">
        <div class="bg-gradient-to-r from-[#2563eb] to-[#1e40af] text-white px-8 font-medium text-[32px] h-[92px] rounded-2xl shadow-lg flex items-center shrink-0" id="active-player-name"></div>

        <div class="relative flex-1 min-h-0">
            <!-- Panel Isi Statistik -->
            <div class="h-full overflow-y-auto flex flex-col gap-4 hidden" id="stats-panel">
                <!-- Shooting -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden flex flex-col flex-[2] min-h-[190px]">
                    <div class="bg-gradient-to-r from-[#1e3a8a] to-[#3b82f6] text-white px-6 py-3 font-medium text-[22px] shrink-0">Shooting</div>
                    <div class="flex-1 grid grid-cols-2 grid-rows-2 gap-x-6 gap-y-3 p-4">
                        {!! $statRow('1', 'Shots on target') !!}
                        {!! $statRow('3', 'Goal') !!}
                        {!! $statRow('2', 'Shots off target') !!}
                        {!! $statRow('4', 'Penalty Goal') !!}
                    </div>
                </div>

                <!-- Passing -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden flex flex-col flex-[2] min-h-[190px]">
                    <div class="bg-gradient-to-r from-[#1e3a8a] to-[#3b82f6] text-white px-6 py-3 font-medium text-[22px] shrink-0">Passing</div>
                    <div class="flex-1 grid grid-cols-2 grid-rows-2 gap-x-6 gap-y-3 p-4">
                        {!! $statRow('5', 'Successful Passes') !!}
                        {!! $statRow('7', 'Assist') !!}
                        {!! $statRow('6', 'Successful Crosses') !!}
                        {!! $statRow('8', 'Chance Created') !!}
                    </div>
                </div>

                <!-- Possession & Defending -->
                <div class="grid grid-cols-1 xl:grid-cols-2 gap-4 flex-[4] min-h-[380px]">
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden flex flex-col">
                        <div class="bg-gradient-to-r from-[#1e3a8a] to-[#3b82f6] text-white px-6 py-3 font-medium text-[22px] shrink-0">Possession</div>
                        <div class="flex-1 grid grid-rows-4 gap-3 p-4">
                            {!! $statRow('Q', 'Dribble') !!}
                            {!! $statRow('W', 'Successfull Dribble') !!}
                            {!! $statRow('E', 'Unsuccessfull Dribble') !!}
                        </div>
                    </div>
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden flex flex-col">
                        <div class="bg-gradient-to-r from-[#1e3a8a] to-[#3b82f6] text-white px-6 py-3 font-medium text-[22px] shrink-0">Defending</div>
                        <div class="flex-1 grid grid-rows-4 gap-3 p-4">
                            {!! $statRow('Z', 'Tackles') !!}
                            {!! $statRow('X', 'Fouls Committed') !!}
                            {!! $statRow('C', 'Dribbled Past') !!}
                            {!! $statRow('V', 'Interceptions') !!}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Placeholder sebelum player dipilih -->
            <div id="stats-placeholder" class="absolute inset-0 flex items-center justify-center text-[20px] text-slate-400 bg-white rounded-2xl shadow-lg text-center px-4">
                Pilih Player Untuk Memunculkan Data
            </div>
        </div>

        <!-- Tombol Aksi Bawah -->
        <div class="flex gap-4 shrink-0">
            <button class="flex-1 h-[68px] bg-gradient-to-br from-[#00c060] to-[#008a45] hover:brightness-110 text-white rounded-2xl border-2 border-emerald-400/60 font-medium text-[26px] flex items-center justify-center gap-3 shadow-lg transition">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 21h14a2 2 0 002-2V8.5L15.5 3H5a2 2 0 00-2 2v14a2 2 0 002 2zM7 3v5h8V3M7 21v-7h10v7"></path></svg>
                SAVE
            </button>
            @if(auth()->user()->role === 'Analis')
            <form action="{{ route('matches.lock', $match->id) }}" method="POST" class="m-0 flex-1" onsubmit="return confirm('Yakin ingin mengunci data? Data yang dikunci tidak bisa diubah lagi.');">
                @csrf
                <button type="submit" class="w-full h-[68px] bg-gradient-to-br from-[#c00000] to-[#7a0000] hover:brightness-110 text-white rounded-2xl font-medium text-[26px] flex items-center justify-center gap-3 shadow-lg transition">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    LOCK
                </button>
            </form>
            @endif
            <a href="#" class="flex-1 h-[68px] bg-gradient-to-br from-[#3b82f6] to-[#1e3a8a] hover:brightness-110 text-white rounded-2xl border-2 border-blue-300/60 font-medium text-[26px] flex items-center justify-center gap-3 shadow-lg transition text-center">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                EXPORT
            </a>
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
        toggle.classList.remove('bg-slate-400');
        toggle.classList.add('bg-[#2563eb]');
        dot.classList.add('translate-x-6');
    } else {
        toggle.classList.remove('bg-[#2563eb]');
        toggle.classList.add('bg-slate-400');
        dot.classList.remove('translate-x-6');
    }
}

function selectPlayer(playerId, playerName) {
    activePlayerId = playerId;
    document.getElementById('active-player-name').innerText = playerName;
    document.getElementById('stats-placeholder').classList.add('hidden');
    document.getElementById('stats-panel').classList.remove('hidden');

    document.querySelectorAll('.pitch-player').forEach(el => {
        el.classList.toggle('is-selected', String(el.dataset.id) === String(playerId));
    });

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
        cloneDragged.className = targetPlayer.className.replace('is-selected', '').trim();
        cloneDragged.removeAttribute('draggable');
        cloneDragged.removeAttribute('ondragstart');
        cloneDragged.innerHTML = `
            <span class="text-[13px] font-medium text-white mb-1.5 drop-shadow-md truncate max-w-[110px] text-center">${draggedItem.dataset.name}</span>
            <div class="pitch-circle w-11 h-11 rounded-full bg-[#111827] border-[3px] border-white text-white flex items-center justify-center text-[13px] font-bold transition-colors shadow-lg">${draggedItem.dataset.number}</div>
        `;
        cloneDragged.setAttribute('onclick', `selectPlayer(${draggedItem.dataset.id}, '${draggedItem.dataset.name}')`);

        const originalBenchHTML = `
            <div class="bg-white border-b-[3px] border-[#eef0f4] px-5 py-3 flex items-center gap-4 cursor-grab hover:bg-slate-50 transition-colors bench-player" draggable="true" ondragstart="drag(event)" data-id="${targetPlayer.dataset.id}" data-name="${targetPlayer.querySelector('span').innerText}" data-number="${targetPlayer.querySelector('.pitch-circle').innerText}">
                <div class="w-11 h-11 rounded-full bg-[#111c34] text-white flex items-center justify-center text-[14px] font-bold shrink-0">${targetPlayer.querySelector('.pitch-circle').innerText}</div>
                <span class="text-[16px] font-medium text-slate-800 truncate">${targetPlayer.querySelector('span').innerText}</span>
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
@extends('layouts.app')
@section('title', 'Live Analytics - FFA Analytics')
@section('header', 'Match Statistics Input')
@section('subheader', $match->title . ' vs ' . $match->opponent . ' - ' . \Carbon\Carbon::parse($match->match_date)->format('M d, Y'))
@section('content')

<!-- Tambahkan CDN Lucide Icons jika belum ada di layouts/app.blade.php -->
<script src="https://unpkg.com/lucide@latest"></script>

@php
    // ===== Helper tampilan (tidak mengubah logika / ID / fungsi JS) =====
    $jersey = fn($p, $d = '00') => $p->jersey_number ?? $p->no_punggung ?? $p->number ?? $d;

    $offsets = ['LW' => 'mt-12', 'RW' => 'mt-12', 'DM' => 'mt-14', 'CB' => 'mt-8'];

    $pitchPlayer = function ($p, $default = '00') use ($jersey, $offsets) {
        $pos = $p->pivot->position === 'Cadangan' ? ($p->position ?? 'Cadangan') : ($p->pivot->position ?? '');
        $displayName = $p->popular_name ?: $p->name;
        $off = $offsets[$pos] ?? '';
        $name = e($displayName);
        $num = e($jersey($p, $default));
        return '<div class="flex flex-col items-center cursor-pointer pitch-player group ' . $off . '" role="button" tabindex="0" aria-label="Pilih ' . e($displayName) . '" data-id="' . $p->id . '" data-name="' . e($displayName) . '" data-position="' . e($pos) . '">'
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

    $statRow = function ($key, $label, $shortcut = null) {
        $shortcut = $shortcut ?? $key;
        return '<div class="flex items-stretch gap-3 h-full min-h-[56px]">'
            . '<div class="aspect-square h-full min-w-[52px] shrink-0 bg-white border border-slate-200 rounded-xl shadow-md flex items-center justify-center text-[22px] font-medium text-slate-800">' . $shortcut . '</div>'
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

<div class="flex flex-col lg:flex-row gap-5 w-full items-stretch lg:h-[calc(100vh-150px)] lg:min-h-[720px]">

    <!-- KOLOM KIRI: Cadangan + Roster + Kembali -->
    <div class="w-full lg:w-[280px] xl:w-[300px] flex flex-col gap-5 shrink-0 min-h-0">
        <div class="bg-white rounded-2xl shadow-lg flex flex-col overflow-hidden min-h-[300px] flex-1">
            <div class="bg-gradient-to-br from-[#1e3a8a] to-[#3b82f6] text-white text-center py-5 font-medium text-[22px] shrink-0 flex items-center justify-center gap-2">
                <i data-lucide="users" class="w-5 h-5"></i>
                <span>Cadangan</span>
            </div>
            <div class="flex-1 overflow-y-auto bg-[#eef0f4]" id="bench-container" ondragover="allowDrop(event)" ondrop="drop(event)">
                @foreach($bench as $player)
                <div class="bg-white border-b-[3px] border-[#eef0f4] px-5 py-3 flex items-center gap-4 cursor-grab hover:bg-slate-50 transition-colors bench-player" draggable="true" ondragstart="drag(event)" id="player-{{ $player->id }}" data-id="{{ $player->id }}" data-name="{{ $player->popular_name ?: $player->name }}" data-number="{{ $jersey($player) }}" data-position="{{ $player->position ?? $player->pivot->position }}">
                    <div class="w-11 h-11 rounded-full bg-[#111c34] text-white flex items-center justify-center text-[14px] font-bold shrink-0">{{ $jersey($player) }}</div>
                    <span class="text-[16px] font-medium text-slate-800 truncate">{{ $player->popular_name ?: $player->name }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <div class="bg-gradient-to-br from-[#2563eb] to-[#3b82f6] rounded-2xl shadow-lg px-5 py-4 shrink-0">
            <div class="flex items-center gap-2 mb-1.5">
                <div class="w-2 h-2 rounded-full bg-emerald-400"></div>
                <span class="text-[12px] font-medium text-emerald-300">Roster Loaded</span>
            </div>
            <div class="text-[14px] font-medium text-white flex items-center gap-2">
                <i data-lucide="layout-grid" class="w-4 h-4"></i>
                <span>Formation: {{ $match->formation ?? '4-3-3' }}</span>
            </div>
        </div>

        <a href="{{ route('matches.index') }}" class="mt-auto shrink-0 bg-gradient-to-br from-[#1e3a8a] to-[#3b82f6] hover:brightness-110 text-white text-center py-4 rounded-2xl text-[22px] font-medium shadow-lg transition flex items-center justify-center gap-2">
            <i data-lucide="arrow-left" class="w-6 h-6"></i>
            <span>Kembali</span>
        </a>
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
                <div class="text-[12px] text-white flex items-center gap-1.5">
                    <i data-lucide="grid" class="w-3.5 h-3.5 text-blue-400"></i>
                    <span>Formation: {{ $match->formation ?? '4-3-3' }}</span>
                </div>
            </div>
        </div>

        <!-- Toggle mode input -->
        <div class="flex items-center justify-between px-1 shrink-0">
            <span class="text-[17px] text-slate-500 font-medium">Tekan [Spasi] Untuk Masuk Ke Mode Input data</span>
            <div class="w-14 h-8 bg-slate-400 rounded-full relative cursor-pointer shrink-0 transition-colors" id="input-toggle" onclick="toggleInputMode()">
                <div class="w-6 h-6 bg-white rounded-full absolute left-1 top-1 shadow-sm transition-transform" id="input-toggle-dot"></div>
            </div>
        </div>

        <!-- Histori Input -->
        <div class="flex-1 min-h-[150px] bg-[#ededed] rounded-2xl border border-slate-300 flex flex-col shadow-lg overflow-hidden">
            <div class="bg-[#dcdcdc] text-center py-1.5 text-[16px] font-bold text-black shrink-0 flex items-center justify-center gap-2">
                <i data-lucide="history" class="w-4 h-4"></i>
                <span>HISTORI INPUT</span>
            </div>
            <div class="flex-1 overflow-y-auto p-3 bg-[#ededed] text-[12px] text-slate-600 font-mono" id="history-log"></div>
            <div class="bg-[#dcdcdc] text-center py-1.5 text-[12px] font-bold text-red-600 shrink-0 flex items-center justify-center gap-1">
                <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i>
                <span>!!! Tekan Backspace untuk Undo Input !!!</span>
            </div>
        </div>
    </div>

    <!-- KOLOM KANAN: Panel Statistik -->
    <div class="w-full lg:w-[42%] flex flex-col gap-5 shrink-0 min-h-0">
        <div class="bg-gradient-to-r from-[#2563eb] to-[#1e40af] text-white px-8 font-bold text-[28px] h-[92px] rounded-2xl shadow-lg flex items-center gap-3 shrink-0" id="active-player-name">
            <i data-lucide="user-check" class="w-7 h-7 text-blue-200"></i>
            <span class="truncate">Pilih Pemain di Lapangan</span>
        </div>

        <div class="relative flex-1 min-h-0">
            <!-- Panel Isi Statistik -->
            <div class="h-full overflow-y-auto flex flex-col gap-4 hidden" id="stats-panel">
                <div id="field-stats" class="flex flex-col gap-4">
                <!-- Shooting -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden flex flex-col flex-[2] min-h-[190px]">
                    <div class="bg-gradient-to-r from-[#1e3a8a] to-[#3b82f6] text-white px-6 py-3 font-semibold text-[18px] shrink-0 flex items-center gap-2">
                        <i data-lucide="target" class="w-5 h-5"></i>
                        <span>Shooting</span>
                    </div>
                    <div class="flex-1 grid grid-cols-2 grid-rows-2 gap-x-6 gap-y-3 p-4">
                        {!! $statRow('1', 'Shots on target') !!}
                        {!! $statRow('3', 'Goal') !!}
                        {!! $statRow('2', 'Shots off target') !!}
                        {!! $statRow('4', 'Penalty Goal') !!}
                    </div>
                </div>

                <!-- Passing -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden flex flex-col flex-[2] min-h-[190px]">
                    <div class="bg-gradient-to-r from-[#1e3a8a] to-[#3b82f6] text-white px-6 py-3 font-semibold text-[18px] shrink-0 flex items-center gap-2">
                        <i data-lucide="git-commit" class="w-5 h-5"></i>
                        <span>Passing</span>
                    </div>
                    <div class="flex-1 grid grid-cols-2 grid-rows-2 gap-x-6 gap-y-3 p-4">
                        {!! $statRow('Q', 'Successful Passes') !!}
                        {!! $statRow('E', 'Assist') !!}
                        {!! $statRow('W', 'Successful Crosses') !!}
                        {!! $statRow('R', 'Chance Created') !!}
                    </div>
                </div>

                <!-- Possession & Defending -->
                <div class="grid grid-cols-1 xl:grid-cols-2 gap-4 flex-[4] min-h-[380px]">
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden flex flex-col">
                        <div class="bg-gradient-to-r from-[#1e3a8a] to-[#3b82f6] text-white px-6 py-3 font-semibold text-[18px] shrink-0 flex items-center gap-2">
                            <i data-lucide="activity" class="w-5 h-5"></i>
                            <span>Possession</span>
                        </div>
                        <div class="flex-1 grid grid-rows-4 gap-3 p-4">
                            {!! $statRow('T', 'Dribble') !!}
                            {!! $statRow('Y', 'Successful Dribble') !!}
                            {!! $statRow('U', 'Unsuccessful Dribble') !!}
                        </div>
                    </div>
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden flex flex-col">
                        <div class="bg-gradient-to-r from-[#1e3a8a] to-[#3b82f6] text-white px-6 py-3 font-semibold text-[18px] shrink-0 flex items-center gap-2">
                            <i data-lucide="shield" class="w-5 h-5"></i>
                            <span>Defending</span>
                        </div>
                        <div class="flex-1 grid grid-rows-4 gap-3 p-4">
                            {!! $statRow('A', 'Tackles') !!}
                            {!! $statRow('S', 'Fouls Committed') !!}
                            {!! $statRow('F', 'Dribbled Past') !!}
                            {!! $statRow('D', 'Interceptions') !!}
                        </div>
                        </div>
                    </div>
                </div>

                        <div id="goalkeeper-stats" class="hidden flex flex-col gap-4">
                            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                                <div class="bg-gradient-to-r from-[#1e3a8a] to-[#3b82f6] text-white px-5 py-3 font-semibold text-[18px] flex items-center gap-2">
                                    <i data-lucide="shield-check" class="w-5 h-5"></i>
                                    <span>Goalkeeping</span>
                                </div>
                                <div class="grid grid-cols-1 xl:grid-cols-2 gap-3 p-4">
                                    {!! $statRow('gk_shots_faced', 'Shots Faced', '1') !!}
                                    {!! $statRow('gk_goals_conceded', 'Goals Conceded', '2') !!}
                                    {!! $statRow('gk_clean_sheets', 'Clean Sheets', '3') !!}
                                    {!! $statRow('gk_saves', 'Saves', '4') !!}
                                    {!! $statRow('gk_penalty_saves', 'Penalty Saves', '5') !!}
                                    {!! $statRow('gk_high_claims', 'High Claims', '6') !!}
                                </div>
                            </div>
                            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                                <div class="bg-gradient-to-r from-[#1e3a8a] to-[#3b82f6] text-white px-5 py-3 font-semibold text-[18px] flex items-center gap-2">
                                    <i data-lucide="hand" class="w-5 h-5"></i>
                                    <span>Aerial &amp; Defensive</span>
                                </div>
                                <div class="grid grid-cols-1 xl:grid-cols-2 gap-3 p-4">
                                    {!! $statRow('gk_punches', 'Punches', '7') !!}
                                    {!! $statRow('gk_sweeper_actions', 'Sweeper Actions', '8') !!}
                                    {!! $statRow('gk_catches', 'Catches', '9') !!}
                                    {!! $statRow('gk_crosses_stopped', 'Crosses Stopped', 'Q') !!}
                                </div>
                            </div>
                            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                                <div class="bg-gradient-to-r from-[#1e3a8a] to-[#3b82f6] text-white px-5 py-3 font-semibold text-[18px] flex items-center gap-2">
                                    <i data-lucide="send" class="w-5 h-5"></i>
                                    <span>Distribution</span>
                                </div>
                                <div class="grid grid-cols-1 xl:grid-cols-2 gap-3 p-4">
                                    {!! $statRow('gk_successful_passes', 'Successful Passes', 'W') !!}
                                    {!! $statRow('gk_accurate_goal_kicks', 'Accurate Goal Kicks', 'E') !!}
                                    {!! $statRow('gk_unsuccessful_passes', 'Unsuccessful Passes', 'R') !!}
                                    {!! $statRow('gk_accurate_throws', 'Accurate Throws', 'T') !!}
                                </div>
                            </div>
                        </div>
            </div>

            <!-- Placeholder sebelum player dipilih -->
            <div id="stats-placeholder" class="absolute inset-0 flex flex-col items-center justify-center gap-3 text-[18px] text-slate-400 bg-white rounded-2xl shadow-lg text-center px-4 font-medium">
                <i data-lucide="mouse-pointer-click" class="w-10 h-10 text-slate-300 animate-bounce"></i>
                <span>Pilih Player Untuk Memunculkan Data</span>
            </div>
        </div>

        <!-- Tombol Aksi Bawah -->
        <div class="flex gap-4 shrink-0">
            <button class="flex-1 h-[68px] bg-gradient-to-br from-[#00c060] to-[#008a45] hover:brightness-110 text-white rounded-2xl border-2 border-emerald-400/60 font-bold text-[20px] flex items-center justify-center gap-2 shadow-lg transition active:scale-95">
                <i data-lucide="save" class="w-6 h-6"></i>
                <span>SAVE</span>
            </button>
            @if(auth()->user()->role === 'Analis')
            <form action="{{ route('matches.lock', $match->id) }}" method="POST" class="m-0 flex-1" onsubmit="return confirm('Yakin ingin mengunci data? Data yang dikunci tidak bisa diubah lagi.');">
                @csrf
                <button type="submit" class="w-full h-[68px] bg-gradient-to-br from-[#c00000] to-[#7a0000] hover:brightness-110 text-white rounded-2xl font-bold text-[20px] flex items-center justify-center gap-2 shadow-lg transition active:scale-95">
                    <i data-lucide="lock" class="w-6 h-6"></i>
                    <span>LOCK</span>
                </button>
            </form>
            @endif
            <a href="#" class="flex-1 h-[68px] bg-gradient-to-br from-[#3b82f6] to-[#1e3a8a] hover:brightness-110 text-white rounded-2xl border-2 border-blue-300/60 font-bold text-[20px] flex items-center justify-center gap-2 shadow-lg transition text-center active:scale-95">
                <i data-lucide="download" class="w-6 h-6"></i>
                <span>EXPORT</span>
            </a>
        </div>
    </div>

</div>

<script>
let activePlayerId = null;
let activePlayerPosition = null;
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

function selectPlayer(playerId, playerName, position = '') {
    activePlayerId = playerId;
    activePlayerPosition = position;
    const activeName = document.getElementById('active-player-name');
    const icon = document.createElement('i');
    icon.dataset.lucide = 'user-check';
    icon.className = 'w-7 h-7 text-blue-200';
    const name = document.createElement('span');
    name.className = 'truncate';
    name.textContent = playerName;
    activeName.replaceChildren(icon, name);
    document.getElementById('stats-placeholder').classList.add('hidden');
    document.getElementById('stats-panel').classList.remove('hidden');
    const isGoalkeeper = position === 'GK';
    document.getElementById('field-stats').classList.toggle('hidden', isGoalkeeper);
    document.getElementById('goalkeeper-stats').classList.toggle('hidden', !isGoalkeeper);
    if (window.lucide) window.lucide.createIcons();

    document.querySelectorAll('.pitch-player').forEach(el => {
        el.classList.toggle('is-selected', String(el.dataset.id) === String(playerId));
    });

    fetch(`/matches/${matchId}/player-stats/${playerId}`)
    .then(res => {
        if (!res.ok) throw new Error(`Failed to load player statistics (${res.status})`);
        return res.json();
    })
    .then(data => {
        if (String(activePlayerId) !== String(playerId)) return;
        if(data.success) {
            for (const [key, value] of Object.entries(data.stats)) {
                const el = document.getElementById(`val-${key}`);
                if (el) el.innerText = value;
            }
        }
    })
    .catch(error => console.error(error));
}

function executeStatUpdate(playerId, statKey, action, isUndo) {
    if(!playerId) return;
    fetch(`/matches/${matchId}/statistics`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
        body: JSON.stringify({ player_id: playerId, stat_key: statKey, action: action })
    })
    .then(res => {
        if (!res.ok) throw new Error(`Failed to update statistic (${res.status})`);
        return res.json();
    })
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
    })
    .catch(error => console.error(error));
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
            const pressed = e.key.toLowerCase();
            const fieldKeys = {
                '1': '1', '2': '2', '3': '3', '4': '4',
                q: 'Q', w: 'W', e: 'E', r: 'R', t: 'T',
                y: 'Y', u: 'U', a: 'A', s: 'S', d: 'D', f: 'F'
            };
            const goalkeeperKeys = {
                '1': 'gk_shots_faced', '2': 'gk_goals_conceded',
                '3': 'gk_clean_sheets', '4': 'gk_saves',
                '5': 'gk_penalty_saves', '6': 'gk_high_claims',
                '7': 'gk_punches', '8': 'gk_sweeper_actions',
                '9': 'gk_catches', q: 'gk_crosses_stopped',
                w: 'gk_successful_passes', e: 'gk_accurate_goal_kicks',
                r: 'gk_unsuccessful_passes', t: 'gk_accurate_throws'
            };
            const statKey = activePlayerPosition === 'GK' ? goalkeeperKeys[pressed] : fieldKeys[pressed];
            if (statKey) executeStatUpdate(activePlayerId, statKey, 'increment', false);
        }
    }
});

let draggedItem = null;
function drag(e) { draggedItem = e.currentTarget; }
function allowDrop(e) { e.preventDefault(); }
document.getElementById('pitch-players').addEventListener('click', (e) => {
    const player = e.target.closest('.pitch-player');
    if (player) {
        selectPlayer(player.dataset.id, player.dataset.name, player.dataset.position);
    }
});
document.getElementById('pitch-players').addEventListener('keydown', (e) => {
    const player = e.target.closest('.pitch-player');
    if (player && (e.key === 'Enter' || e.key === ' ')) {
        e.preventDefault();
        selectPlayer(player.dataset.id, player.dataset.name, player.dataset.position);
    }
});
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
        const pitchName = document.createElement('span');
        pitchName.className = 'text-[13px] font-medium text-white mb-1.5 drop-shadow-md truncate max-w-[110px] text-center';
        pitchName.textContent = draggedItem.dataset.name;
        const pitchNumber = document.createElement('div');
        pitchNumber.className = 'pitch-circle w-11 h-11 rounded-full bg-[#111827] border-[3px] border-white text-white flex items-center justify-center text-[13px] font-bold transition-colors shadow-lg';
        pitchNumber.textContent = draggedItem.dataset.number;
        cloneDragged.replaceChildren(pitchName, pitchNumber);
        cloneDragged.dataset.position = draggedItem.dataset.position;
        cloneDragged.setAttribute('role', 'button');
        cloneDragged.setAttribute('tabindex', '0');
        cloneDragged.setAttribute('aria-label', `Pilih ${draggedItem.dataset.name}`);

        const originalBench = document.createElement('div');
        originalBench.className = 'bg-white border-b-[3px] border-[#eef0f4] px-5 py-3 flex items-center gap-4 cursor-grab hover:bg-slate-50 transition-colors bench-player';
        originalBench.draggable = true;
        originalBench.ondragstart = drag;
        originalBench.dataset.id = targetPlayer.dataset.id;
        originalBench.dataset.name = targetPlayer.querySelector('span').textContent;
        originalBench.dataset.number = targetPlayer.querySelector('.pitch-circle').textContent;
        originalBench.dataset.position = targetPlayer.dataset.position;
        const benchNumber = document.createElement('div');
        benchNumber.className = 'w-11 h-11 rounded-full bg-[#111c34] text-white flex items-center justify-center text-[14px] font-bold shrink-0';
        benchNumber.textContent = originalBench.dataset.number;
        const benchName = document.createElement('span');
        benchName.className = 'text-[16px] font-medium text-slate-800 truncate';
        benchName.textContent = originalBench.dataset.name;
        originalBench.replaceChildren(benchNumber, benchName);
        document.getElementById('bench-container').appendChild(originalBench);

        targetPlayer.replaceWith(cloneDragged);
        draggedItem.remove();
        draggedItem = null;
    }
}

// Inisialisasi Lucide Icons
document.addEventListener("DOMContentLoaded", function() {
    if (window.lucide) window.lucide.createIcons();
});
</script>
@endsection
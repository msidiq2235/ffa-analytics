@extends('layouts.app')

@section('title', 'Match Mapping Event - FFA Analytics')
@section('header', 'Match Mapping Event')
@section('subheader', $match->title . ' - ' . \Carbon\Carbon::parse($match->match_date)->format('M d, Y'))

@section('header_action')
<div class="flex items-center gap-3">
    <a href="{{ route('matches.index') }}" class="px-4 py-2 bg-slate-100 text-slate-600 font-semibold rounded-lg border border-slate-200 hover:bg-slate-200 flex items-center gap-2 transition-colors">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali
    </a>
    @if($match->status === 'Selesai')
    <span class="px-4 py-2 bg-green-50 text-green-600 border border-green-200 font-bold rounded-lg flex items-center gap-2">
        <i data-lucide="lock" class="w-4 h-4"></i>
        Data Final (Terkunci)
    </span>
    @endif
</div>
@endsection

@section('content')

<style>
    [data-lucide] { flex-shrink: 0; }
</style>

<div class="grid min-h-[calc(100vh-12rem)] grid-cols-1 gap-4 md:grid-cols-12">
    <section class="flex min-w-0 flex-col gap-4 md:col-span-7">
        <div class="rounded-xl border border-slate-200 bg-white p-3 shadow-sm">
            <div class="flex items-center gap-3 overflow-x-auto">
                <span class="shrink-0 text-xs font-bold tracking-wider text-slate-400">PLAYER</span>
                <span class="shrink-0 rounded-lg bg-slate-100 px-3 py-2 text-xs font-bold text-slate-500">XI</span>
                @foreach($startingEleven as $player)
                <button id="btn-player-{{ $player->id }}" onclick="setPlayer({{ $player->id }}, @js($player->jersey_number), @js($player->name))" class="player-btn h-9 min-w-9 shrink-0 rounded-lg border border-slate-200 bg-slate-50 px-2 text-sm font-bold text-slate-600 transition-colors hover:border-blue-200 hover:bg-blue-50">
                    {{ $player->jersey_number ?? '-' }}
                </button>
                @endforeach
            </div>
        </div>

        <div class="relative flex min-h-[400px] flex-1 flex-col">
            <div class="relative flex-1 overflow-hidden rounded-xl border-4 border-white bg-[#164a27] shadow-lg" id="pitch-container">
                <div class="pointer-events-none absolute inset-[5%] rounded border-2 border-white/40"></div>
                <div class="pointer-events-none absolute bottom-[5%] left-1/2 top-[5%] border-l-2 border-white/40"></div>
                <div class="pointer-events-none absolute left-1/2 top-1/2 aspect-square w-[18%] -translate-x-1/2 -translate-y-1/2 rounded-full border-2 border-white/40"></div>
                <div class="pointer-events-none absolute left-1/2 top-1/2 h-2 w-2 -translate-x-1/2 -translate-y-1/2 rounded-full bg-white/40"></div>
                <div class="pointer-events-none absolute bottom-[25%] left-[5%] top-[25%] w-[21%] border-2 border-l-0 border-white/40"></div>
                <div class="pointer-events-none absolute bottom-[37%] left-[5%] top-[37%] w-[8%] border-2 border-l-0 border-white/40"></div>
                <div class="pointer-events-none absolute bottom-[25%] right-[5%] top-[25%] w-[21%] border-2 border-r-0 border-white/40"></div>
                <div class="pointer-events-none absolute bottom-[37%] right-[5%] top-[37%] w-[8%] border-2 border-r-0 border-white/40"></div>
                <canvas id="vectorCanvas" class="absolute inset-0 z-10 h-full w-full touch-none cursor-crosshair"></canvas>
            </div>
            <div class="mt-3 grid grid-cols-2 gap-2 rounded-lg bg-slate-900 px-4 py-3 text-[10px] font-semibold text-slate-300 sm:grid-cols-4">
                <span class="inline-flex items-center gap-2"><i class="h-1 w-6 rounded bg-emerald-400"></i>Shot On Target</span>
                <span class="inline-flex items-center gap-2"><i class="h-1 w-6 rounded bg-red-400"></i>Shot Off Target</span>
                <span class="inline-flex items-center gap-2"><i class="h-1 w-6 rounded bg-amber-400"></i>Blocked</span>
                <span class="inline-flex items-center gap-2"><i class="h-1 w-6 rounded bg-blue-400"></i>Goal</span>
                <span class="col-span-2 text-emerald-300 sm:col-span-4">48-POINT GRID · Drag dari awal ke akhir · Double klik awal, lalu klik akhir</span>
            </div>
        </div>
        <a href="{{ route('matches.index') }}" class="w-fit rounded-lg bg-blue-600 px-6 py-2 text-sm font-bold text-white shadow-sm transition hover:bg-blue-700">
            <i data-lucide="arrow-left" class="mr-1 inline h-4 w-4"></i>Kembali
        </a>
    </section>

    <section class="flex min-w-0 flex-col gap-3 md:col-span-5">
        <div class="rounded-xl border border-slate-200 bg-white p-3 shadow-sm">
            <div class="flex flex-wrap items-center gap-2">
                <button type="button" id="live-mode-toggle" onclick="toggleLiveMode()" aria-pressed="false" title="LIVE ON mencatat waktu dari jam perangkat" class="inline-flex shrink-0 items-center gap-2 rounded-full border border-slate-200 bg-slate-100 px-3 py-2 text-xs font-bold text-slate-600 transition-colors">
                    <span class="h-2 w-2 rounded-full bg-slate-400" id="live-mode-indicator"></span>
                    <span id="live-mode-label">LIVE OFF</span>
                </button>
                <span class="ml-auto text-xs font-bold tracking-wider text-slate-400">EVENT</span>
                <button id="btn-action-SHOT" onclick="setAction('SHOT')" class="action-btn rounded-full border border-slate-200 bg-slate-50 px-4 py-2 text-xs font-bold text-slate-600 transition-colors hover:bg-red-50 hover:text-red-600">SHOT</button>
                <button id="btn-action-PASS" onclick="setAction('PASS')" class="action-btn rounded-full border border-slate-200 bg-slate-50 px-4 py-2 text-xs font-bold text-slate-600 transition-colors hover:bg-green-50 hover:text-green-600">PASS</button>
                <button id="btn-action-CROSS" onclick="setAction('CROSS')" class="action-btn rounded-full border border-slate-200 bg-slate-50 px-4 py-2 text-xs font-bold text-slate-600 transition-colors hover:bg-purple-50 hover:text-purple-600">CROSS</button>
            </div>
            <div id="shot-subtypes" class="mt-3 border-t border-slate-100 pt-3">
                <div class="mb-2 text-[10px] font-bold tracking-wider text-slate-400">SHOT SUBTYPE</div>
                <div class="flex flex-wrap gap-2">
                    <button type="button" data-subtype="ON_TARGET" onclick="setSubtype('ON_TARGET')" class="subtype-btn rounded-md bg-emerald-100 px-2.5 py-1.5 text-[9px] font-bold text-emerald-700 ring-1 ring-emerald-300">ON TARGET</button>
                    <button type="button" data-subtype="OFF_TARGET" onclick="setSubtype('OFF_TARGET')" class="subtype-btn rounded-md bg-slate-100 px-2.5 py-1.5 text-[9px] font-bold text-slate-500">OFF TARGET</button>
                    <button type="button" data-subtype="BLOCKED" onclick="setSubtype('BLOCKED')" class="subtype-btn rounded-md bg-slate-100 px-2.5 py-1.5 text-[9px] font-bold text-slate-500">BLOCKED</button>
                    <button type="button" data-subtype="GOAL" onclick="setSubtype('GOAL')" class="subtype-btn rounded-md bg-slate-100 px-2.5 py-1.5 text-[9px] font-bold text-slate-500">GOAL</button>
                </div>
            </div>
        </div>

        <div class="flex min-h-[500px] flex-1 flex-col overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="flex h-14 shrink-0 items-center gap-2 bg-gradient-to-r from-[#263a8b] to-[#4a7bf0] px-4">
                <span class="truncate text-sm font-bold text-white" id="active-indicator">Pilih pemain untuk melihat ringkasan</span>
            </div>
            <div class="flex shrink-0 border-b border-slate-100 bg-slate-50 p-2">
                <button type="button" id="tab-live-mapping" onclick="setPanel('mapping')" class="panel-tab flex-1 rounded-lg bg-white py-2 text-xs font-bold text-slate-700 shadow-sm">
                    <i data-lucide="map-pin" class="mr-1 inline h-3.5 w-3.5"></i>Live Mapping
                </button>
                <button type="button" id="tab-vector-summary" onclick="setPanel('summary')" class="panel-tab flex-1 rounded-lg py-2 text-xs font-bold text-slate-400 transition-colors hover:text-blue-600">
                    <i data-lucide="chart-column" class="mr-1 inline h-3.5 w-3.5"></i>Vector Summary
                </button>
            </div>

            <div id="mapping-panel" class="flex-1 overflow-y-auto p-3">
                <div id="event-list" class="flex flex-col gap-2"></div>
                <div id="empty-event-list" class="flex h-40 flex-col items-center justify-center gap-2 text-center text-xs text-slate-400">
                    <i data-lucide="mouse-pointer-click" class="h-7 w-7 text-slate-300"></i>
                    Pilih pemain dan event, lalu gambar vektor di lapangan.
                </div>
            </div>

            <div id="summary-panel" class="hidden flex-1 overflow-y-auto p-3">
                <div class="mb-2 text-[9px] font-bold tracking-[0.18em] text-slate-500" id="summary-player-label">VECTOR MAP</div>
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-[minmax(0,1fr)_minmax(0,1.15fr)]">
                    <div class="overflow-hidden rounded-lg border border-slate-200 bg-[#164a27]">
                        <canvas id="summary-canvas" class="block h-[210px] w-full"></canvas>
                    </div>
                    <div class="flex flex-col gap-2">
                        <div class="flex flex-1 items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 p-3 shadow-sm">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full border-[5px] border-red-400 text-[10px] font-extrabold text-red-500" id="shot-percentage">0%</div>
                            <div><div class="text-xs font-semibold text-slate-500">Shot Accuracy</div><div class="text-[11px] text-slate-400" id="shot-ratio">0/0 on target</div></div>
                        </div>
                        <div class="flex flex-1 items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 p-3 shadow-sm">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full border-[5px] border-blue-500 text-[10px] font-extrabold text-blue-600" id="pass-count">0</div>
                            <div><div class="text-xs font-semibold text-slate-500">Passes</div><div class="text-[11px] text-slate-400">total recorded</div></div>
                        </div>
                        <div class="flex flex-1 items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 p-3 shadow-sm">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full border-[5px] border-purple-500 text-[10px] font-extrabold text-purple-600" id="cross-count">0</div>
                            <div><div class="text-xs font-semibold text-slate-500">Crosses</div><div class="text-[11px] text-slate-400">total recorded</div></div>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="mb-2 text-[10px] font-bold tracking-[0.18em] text-slate-500">EVENT BREAKDOWN</div>
                    <div class="space-y-2.5 text-[10px]">
                        <div><div class="mb-1 flex justify-between text-slate-400"><span>Shots</span><span class="font-bold text-red-500" id="shot-total">0</span></div><div class="h-1.5 rounded-full bg-slate-100"><div id="shot-bar" class="h-1.5 rounded-full bg-red-400" style="width:0%"></div></div></div>
                        <div><div class="mb-1 flex justify-between text-slate-400"><span>Passes</span><span class="font-bold text-blue-500" id="pass-total">0</span></div><div class="h-1.5 rounded-full bg-slate-100"><div id="pass-bar" class="h-1.5 rounded-full bg-blue-500" style="width:0%"></div></div></div>
                        <div><div class="mb-1 flex justify-between text-slate-400"><span>Crosses</span><span class="font-bold text-purple-500" id="cross-total">0</span></div><div class="h-1.5 rounded-full bg-slate-100"><div id="cross-bar" class="h-1.5 rounded-full bg-purple-500" style="width:0%"></div></div></div>
                    </div>
                </div>
            </div>

            <div class="flex shrink-0 gap-2 border-t border-slate-100 bg-slate-50 p-3">
                <button type="button" class="flex flex-1 items-center justify-center gap-2 rounded-lg bg-gradient-to-r from-blue-500 to-blue-700 py-2.5 text-sm font-bold text-white shadow-sm transition hover:brightness-110">
                    <i data-lucide="download" class="h-4 w-4"></i>EXPORT
                </button>
                <button type="button" onclick="submitVectors()" class="flex flex-1 items-center justify-center gap-2 rounded-lg bg-gradient-to-r from-emerald-500 to-green-700 py-2.5 text-sm font-bold text-white shadow-sm transition hover:brightness-110">
                    <i data-lucide="save" class="h-4 w-4"></i>SAVE
                </button>
            </div>
        </div>
    </section>
</div>

<div id="manual-minute-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 p-4" role="dialog" aria-modal="true" aria-labelledby="manual-minute-title">
    <form class="w-full max-w-sm rounded-2xl bg-white p-6 shadow-xl" onsubmit="confirmManualMinute(event)">
        <h2 id="manual-minute-title" class="text-lg font-bold text-slate-800">Masukkan Menit Pertandingan</h2>
        <p class="mt-1 text-sm text-slate-500">Masukkan menit saat aksi ini terjadi.</p>
        <label for="manual-minute-input" class="mt-4 block text-sm font-semibold text-slate-700">Menit</label>
        <input type="number" id="manual-minute-input" min="0" step="1" required autofocus class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-400">
        <div class="mt-5 flex justify-end gap-2">
            <button type="button" onclick="cancelManualMinute()" class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50">Batal</button>
            <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">Simpan Waktu</button>
        </div>
    </form>
</div>

<div id="notification-container" class="fixed right-4 top-4 z-[60] flex w-[min(24rem,calc(100vw-2rem))] flex-col gap-3" aria-live="polite" aria-atomic="false"></div>

<!-- Lucide Icons (hapus baris ini jika layouts.app sudah memuat Lucide) -->
<script src="https://unpkg.com/lucide@latest"></script>

<script>
let selectedPlayerId = null;
let selectedPlayerName = '';
let selectedAction = null;
let selectedSubtype = 'ON_TARGET';
let liveMode = false;
let isDrawing = false;
let dragStartPoint = null;
let pointerDownPosition = null;
let suppressNextClick = false;
let clickTimer = null;
let gridPoints = [];
let pendingVectors = [];
let currentStartPoint = null;
let pendingManualVector = null;
const savedVectors = @js($vectors);

const canvas = document.getElementById('vectorCanvas');
const ctx = canvas.getContext('2d');
const summaryCanvas = document.getElementById('summary-canvas');
const summaryCtx = summaryCanvas.getContext('2d');
const matchStatus = '{{ $match->status }}';
const eventList = document.getElementById('event-list');
const indicator = document.getElementById('active-indicator');
const manualMinuteModal = document.getElementById('manual-minute-modal');
const manualMinuteInput = document.getElementById('manual-minute-input');

// Render semua ikon Lucide
function renderIcons() {
    if (window.lucide) lucide.createIcons({ attrs: { 'stroke-width': 2 } });
}
document.addEventListener('DOMContentLoaded', renderIcons);

function calculateGrid() {
    gridPoints = [];
    const cols = 8;
    const rows = 6;
    const spacingX = canvas.width / (cols + 1);
    const spacingY = canvas.height / (rows + 1);

    for (let i = 1; i <= cols; i++) {
        for (let j = 1; j <= rows; j++) {
            gridPoints.push({ x: i * spacingX, y: j * spacingY });
        }
    }
}

function drawGrid() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    ctx.fillStyle = 'rgba(255, 255, 255, 0.15)';
    ctx.strokeStyle = 'rgba(255, 255, 255, 0.3)';
    ctx.lineWidth = 2;

    gridPoints.forEach(p => {
        ctx.beginPath();
        ctx.arc(p.x, p.y, 6, 0, Math.PI * 2);
        ctx.fill();
        ctx.stroke();
    });
}

function redrawAll() {
    drawGrid();
    getSelectedPlayerVectors().forEach(v => {
        const start = getPointFromPct(v.start_x, v.start_y);
        const end = getPointFromPct(v.end_x, v.end_y);
        drawArrow(start.x, start.y, end.x, end.y, getActionColor(v.action_type, v.subtype));
    });

    if (currentStartPoint) {
        ctx.beginPath();
        ctx.arc(currentStartPoint.x, currentStartPoint.y, 10, 0, Math.PI * 2);
        ctx.fillStyle = '#facc15';
        ctx.fill();
        ctx.strokeStyle = '#ffffff';
        ctx.lineWidth = 2;
        ctx.stroke();
    }
}

function resizeCanvas() {
    canvas.width = canvas.offsetWidth;
    canvas.height = canvas.offsetHeight;
    calculateGrid();
    redrawAll();
    drawSummary();
}

window.addEventListener('resize', resizeCanvas);
setTimeout(resizeCanvas, 100);

function setPlayer(id, number, name) {
    selectedPlayerId = id;
    selectedPlayerName = name;
    document.querySelectorAll('.player-btn').forEach(btn => {
        btn.classList.remove('bg-blue-500', 'text-white', 'border-blue-600');
        btn.classList.add('bg-slate-50', 'text-slate-600');
    });
    const activeBtn = document.getElementById('btn-player-' + id);
    activeBtn.classList.remove('bg-slate-50', 'text-slate-600');
    activeBtn.classList.add('bg-blue-500', 'text-white', 'border-blue-600');
    updateIndicator();
    updateSummary();
    updateEventList();
    redrawAll();
}

function setAction(action) {
    selectedAction = action;
    document.querySelectorAll('.action-btn').forEach(btn => {
        btn.classList.remove('bg-red-500', 'bg-green-500', 'bg-purple-500', 'text-white');
        btn.classList.add('bg-slate-50', 'text-slate-600');
    });
    const activeBtn = document.getElementById('btn-action-' + action);
    activeBtn.classList.remove('bg-slate-50', 'text-slate-600');
    if(action === 'SHOT') activeBtn.classList.add('bg-red-500', 'text-white');
    if(action === 'PASS') activeBtn.classList.add('bg-green-500', 'text-white');
    if(action === 'CROSS') activeBtn.classList.add('bg-purple-500', 'text-white');
    document.getElementById('shot-subtypes').classList.toggle('hidden', action !== 'SHOT');
    if (action === 'SHOT' && !selectedSubtype) setSubtype('ON_TARGET');
    updateIndicator();
    redrawAll();
}

function setSubtype(subtype) {
    selectedSubtype = subtype;
    document.querySelectorAll('.subtype-btn').forEach(button => {
        const selected = button.dataset.subtype === subtype;
        button.classList.toggle('ring-1', selected);
        button.classList.toggle('ring-emerald-300', selected && subtype === 'ON_TARGET');
        button.classList.toggle('ring-red-300', selected && subtype === 'OFF_TARGET');
        button.classList.toggle('ring-amber-300', selected && subtype === 'BLOCKED');
        button.classList.toggle('ring-blue-300', selected && subtype === 'GOAL');
        button.classList.toggle('bg-emerald-100', selected && subtype === 'ON_TARGET');
        button.classList.toggle('text-emerald-700', selected && subtype === 'ON_TARGET');
        button.classList.toggle('bg-red-100', selected && subtype === 'OFF_TARGET');
        button.classList.toggle('text-red-700', selected && subtype === 'OFF_TARGET');
        button.classList.toggle('bg-amber-100', selected && subtype === 'BLOCKED');
        button.classList.toggle('text-amber-700', selected && subtype === 'BLOCKED');
        button.classList.toggle('bg-blue-100', selected && subtype === 'GOAL');
        button.classList.toggle('text-blue-700', selected && subtype === 'GOAL');
        button.classList.toggle('bg-slate-100', !selected);
        button.classList.toggle('text-slate-500', !selected);
    });
}

function setPanel(panel) {
    const showSummary = panel === 'summary';
    document.getElementById('mapping-panel').classList.toggle('hidden', showSummary);
    document.getElementById('summary-panel').classList.toggle('hidden', !showSummary);
    document.getElementById('tab-live-mapping').className = `panel-tab flex-1 rounded-lg py-2 text-xs font-bold ${showSummary ? 'text-slate-400 hover:text-blue-600' : 'bg-white text-slate-700 shadow-sm'}`;
    document.getElementById('tab-vector-summary').className = `panel-tab flex-1 rounded-lg py-2 text-xs font-bold ${showSummary ? 'bg-blue-100 text-blue-600 shadow-sm' : 'text-slate-400 hover:text-blue-600'}`;
    if (showSummary) {
        updateSummary();
        requestAnimationFrame(drawSummary);
    }
}

function toggleLiveMode() {
    liveMode = !liveMode;
    const toggle = document.getElementById('live-mode-toggle');
    const indicator = document.getElementById('live-mode-indicator');
    const label = document.getElementById('live-mode-label');

    toggle.setAttribute('aria-pressed', String(liveMode));
    toggle.classList.toggle('bg-emerald-100', liveMode);
    toggle.classList.toggle('text-emerald-700', liveMode);
    toggle.classList.toggle('border-emerald-300', liveMode);
    toggle.classList.toggle('bg-slate-100', !liveMode);
    toggle.classList.toggle('text-slate-600', !liveMode);
    toggle.classList.toggle('border-slate-200', !liveMode);
    indicator.classList.toggle('bg-emerald-500', liveMode);
    indicator.classList.toggle('animate-pulse', liveMode);
    indicator.classList.toggle('bg-slate-400', !liveMode);
    label.textContent = liveMode ? 'LIVE ON' : 'LIVE OFF';
}

function updateIndicator() {
    indicator.textContent = selectedPlayerName || 'Pilih pemain untuk melihat ringkasan';
}

function getActionColor(action, subtype = null) {
    if (action === 'SHOT') {
        const subtypeColors = {
            ON_TARGET: '#4ade80',
            OFF_TARGET: '#ef4444',
            BLOCKED: '#fbbf24',
            GOAL: '#3b82f6'
        };
        return subtypeColors[subtype] || '#ef4444';
    }
    if (action === 'PASS') return '#22c55e';
    if (action === 'CROSS') return '#a855f7';
    return '#ffffff';
}

function getAllVectors() {
    return [...savedVectors, ...pendingVectors];
}

function getSelectedPlayerVectors() {
    const vectors = getAllVectors();
    return selectedPlayerId
        ? vectors.filter(vector => String(vector.player_id) === String(selectedPlayerId))
        : vectors;
}

function updateSummary() {
    const vectors = getSelectedPlayerVectors();
    const shots = vectors.filter(vector => vector.action_type === 'SHOT');
    const classifiedShots = shots.filter(vector => vector.subtype);
    const onTarget = classifiedShots.filter(vector => ['ON_TARGET', 'GOAL'].includes(vector.subtype)).length;
    const passes = vectors.filter(vector => vector.action_type === 'PASS').length;
    const crosses = vectors.filter(vector => vector.action_type === 'CROSS').length;
    const shotPercentage = classifiedShots.length ? Math.round((onTarget / classifiedShots.length) * 100) : 0;
    const maxTotal = Math.max(shots.length, passes, crosses, 1);

    document.getElementById('summary-player-label').textContent = selectedPlayerName
        ? `VECTOR MAP — ${selectedPlayerName.toUpperCase()}`
        : 'VECTOR MAP — ALL PLAYERS';
    document.getElementById('shot-percentage').textContent = `${shotPercentage}%`;
    document.getElementById('shot-ratio').textContent = `${onTarget}/${classifiedShots.length} on target`;
    document.getElementById('pass-count').textContent = passes;
    document.getElementById('cross-count').textContent = crosses;
    document.getElementById('shot-total').textContent = shots.length;
    document.getElementById('pass-total').textContent = passes;
    document.getElementById('cross-total').textContent = crosses;
    document.getElementById('shot-bar').style.width = `${(shots.length / maxTotal) * 100}%`;
    document.getElementById('pass-bar').style.width = `${(passes / maxTotal) * 100}%`;
    document.getElementById('cross-bar').style.width = `${(crosses / maxTotal) * 100}%`;
    drawSummary();
}

function drawSummary() {
    if (!summaryCanvas || !summaryCanvas.offsetWidth) return;
    summaryCanvas.width = summaryCanvas.offsetWidth;
    summaryCanvas.height = summaryCanvas.offsetHeight;
    const width = summaryCanvas.width;
    const height = summaryCanvas.height;
    summaryCtx.clearRect(0, 0, width, height);
    summaryCtx.fillStyle = '#164a27';
    summaryCtx.fillRect(0, 0, width, height);
    summaryCtx.strokeStyle = 'rgba(255,255,255,0.35)';
    summaryCtx.lineWidth = 1;
    summaryCtx.strokeRect(width * 0.06, height * 0.06, width * 0.88, height * 0.88);
    summaryCtx.beginPath();
    summaryCtx.moveTo(width / 2, height * 0.06);
    summaryCtx.lineTo(width / 2, height * 0.94);
    summaryCtx.stroke();
    summaryCtx.beginPath();
    summaryCtx.arc(width / 2, height / 2, Math.min(width, height) * 0.12, 0, Math.PI * 2);
    summaryCtx.stroke();
    summaryCtx.strokeRect(width * 0.06, height * 0.32, width * 0.2, height * 0.36);
    summaryCtx.strokeRect(width * 0.74, height * 0.32, width * 0.2, height * 0.36);
    summaryCtx.fillStyle = 'rgba(255,255,255,0.3)';
    for (let column = 1; column <= 8; column++) {
        for (let row = 1; row <= 6; row++) {
            summaryCtx.beginPath();
            summaryCtx.arc((column * width) / 9, (row * height) / 7, 2, 0, Math.PI * 2);
            summaryCtx.fill();
        }
    }

    const vectors = getSelectedPlayerVectors();
    vectors.forEach(vector => {
        const fromX = (vector.start_x / 100) * width;
        const fromY = (vector.start_y / 100) * height;
        const toX = (vector.end_x / 100) * width;
        const toY = (vector.end_y / 100) * height;
        const angle = Math.atan2(toY - fromY, toX - fromX);
        const head = 7;
        summaryCtx.strokeStyle = getActionColor(vector.action_type, vector.subtype);
        summaryCtx.lineWidth = 2;
        summaryCtx.beginPath();
        summaryCtx.moveTo(fromX, fromY);
        summaryCtx.lineTo(toX, toY);
        summaryCtx.lineTo(toX - head * Math.cos(angle - Math.PI / 6), toY - head * Math.sin(angle - Math.PI / 6));
        summaryCtx.moveTo(toX, toY);
        summaryCtx.lineTo(toX - head * Math.cos(angle + Math.PI / 6), toY - head * Math.sin(angle + Math.PI / 6));
        summaryCtx.stroke();
    });
}

function getActionIcon(action) {
    if (action === 'SHOT') return 'crosshair';
    if (action === 'PASS') return 'move-up-right';
    return 'corner-up-right';
}

function getNearestPoint(x, y) {
    let nearest = null;
    let minDistance = 50;
    gridPoints.forEach(p => {
        const dist = Math.hypot(p.x - x, p.y - y);
        if (dist < minDistance) {
            minDistance = dist;
            nearest = p;
        }
    });
    return nearest;
}

function getPointFromPct(pctX, pctY) {
    return {
        x: (pctX / 100) * canvas.width,
        y: (pctY / 100) * canvas.height
    };
}

function drawArrow(fromX, fromY, toX, toY, color) {
    const headlen = 12;
    const dx = toX - fromX;
    const dy = toY - fromY;
    const angle = Math.atan2(dy, dx);
    ctx.beginPath();
    ctx.moveTo(fromX, fromY);
    ctx.lineTo(toX, toY);
    ctx.lineTo(toX - headlen * Math.cos(angle - Math.PI / 6), toY - headlen * Math.sin(angle - Math.PI / 6));
    ctx.moveTo(toX, toY);
    ctx.lineTo(toX - headlen * Math.cos(angle + Math.PI / 6), toY - headlen * Math.sin(angle + Math.PI / 6));
    ctx.strokeStyle = color;
    ctx.lineWidth = 4;
    ctx.stroke();
}

function getCanvasPosition(e) {
    const rect = canvas.getBoundingClientRect();
    return { x: e.clientX - rect.left, y: e.clientY - rect.top };
}

function addVector(startPoint, endPoint, playerId, playerName, action, subtype = selectedSubtype) {
    if (!startPoint || !endPoint || (startPoint.x === endPoint.x && startPoint.y === endPoint.y)) return false;

    const vector = {
        player_id: playerId,
        playerName: playerName,
        action_type: action,
        subtype: action === 'SHOT' ? subtype : null,
        start_x: (startPoint.x / canvas.width) * 100,
        start_y: (startPoint.y / canvas.height) * 100,
        end_x: (endPoint.x / canvas.width) * 100,
        end_y: (endPoint.y / canvas.height) * 100
    };

    if (liveMode) {
        vector.timestamp_mode = 'live';
        vector.input_time = new Date().toISOString();
        vector.match_minute = null;
        pendingVectors.push(vector);
        updateEventList();
        updateSummary();
        redrawAll();
        return true;
    }

    pendingManualVector = vector;
    manualMinuteInput.value = '';
    manualMinuteModal.classList.remove('hidden');
    manualMinuteModal.classList.add('flex');
    requestAnimationFrame(() => manualMinuteInput.focus());
    return true;
}

function confirmManualMinute(event) {
    event.preventDefault();
    if (!pendingManualVector || manualMinuteInput.value === '') return;

    pendingManualVector.timestamp_mode = 'manual';
    pendingManualVector.input_time = null;
    pendingManualVector.match_minute = Number(manualMinuteInput.value);
    pendingVectors.push(pendingManualVector);
    pendingManualVector = null;
    closeManualMinuteModal();
    updateEventList();
    updateSummary();
    redrawAll();
}

function cancelManualMinute() {
    pendingManualVector = null;
    closeManualMinuteModal();
}

function closeManualMinuteModal() {
    manualMinuteModal.classList.add('hidden');
    manualMinuteModal.classList.remove('flex');
}

function showNotification(message, type = 'info') {
    const styles = {
        info: { icon: 'info', accent: 'border-blue-500', iconColor: 'text-blue-600', title: 'Perlu diperhatikan' },
        warning: { icon: 'alert-triangle', accent: 'border-amber-500', iconColor: 'text-amber-600', title: 'Periksa kembali' },
        success: { icon: 'circle-check', accent: 'border-emerald-500', iconColor: 'text-emerald-600', title: 'Berhasil' },
        error: { icon: 'circle-x', accent: 'border-red-500', iconColor: 'text-red-600', title: 'Gagal' }
    };
    const style = styles[type] || styles.info;
    const toast = document.createElement('div');
    toast.className = `flex items-start gap-3 rounded-xl border-l-4 ${style.accent} bg-white p-4 shadow-lg ring-1 ring-black/5 transition duration-300`;

    const icon = document.createElement('i');
    icon.setAttribute('data-lucide', style.icon);
    icon.className = `mt-0.5 h-5 w-5 shrink-0 ${style.iconColor}`;

    const content = document.createElement('div');
    content.className = 'min-w-0 flex-1';
    const title = document.createElement('p');
    title.className = 'text-sm font-bold text-slate-800';
    title.textContent = style.title;
    const description = document.createElement('p');
    description.className = 'mt-0.5 text-sm text-slate-600';
    description.textContent = message;
    content.append(title, description);

    const dismiss = document.createElement('button');
    dismiss.type = 'button';
    dismiss.className = 'rounded p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-600';
    dismiss.setAttribute('aria-label', 'Tutup notifikasi');
    dismiss.textContent = '×';
    dismiss.addEventListener('click', () => toast.remove());

    toast.append(icon, content, dismiss);
    document.getElementById('notification-container').appendChild(toast);
    renderIcons();
    window.setTimeout(() => {
        toast.classList.add('translate-x-2', 'opacity-0');
        window.setTimeout(() => toast.remove(), 300);
    }, 5000);
}

canvas.addEventListener('pointerdown', (e) => {
    if(matchStatus === 'Selesai') return showNotification('Pertandingan sudah selesai, sehingga data tidak dapat diubah.', 'warning');
    if(!selectedPlayerId && !selectedAction) return showNotification('Pilih pemain dan event terlebih dahulu sebelum menggambar vektor.', 'info');
    if(!selectedPlayerId) return showNotification('Pilih pemain terlebih dahulu sebelum menggambar vektor.', 'info');
    if(!selectedAction) return showNotification('Pilih event SHOT, PASS, atau CROSS terlebih dahulu.', 'info');

    const position = getCanvasPosition(e);
    dragStartPoint = getNearestPoint(position.x, position.y);
    pointerDownPosition = position;
    isDrawing = false;
    if (dragStartPoint) canvas.setPointerCapture(e.pointerId);
});

canvas.addEventListener('pointermove', (e) => {
    if (!pointerDownPosition || !dragStartPoint) return;
    const position = getCanvasPosition(e);
    if (!isDrawing && Math.hypot(position.x - pointerDownPosition.x, position.y - pointerDownPosition.y) > 4) {
        isDrawing = true;
    }
    if (!isDrawing) return;

    redrawAll();
    const snapPoint = getNearestPoint(position.x, position.y);
    drawArrow(dragStartPoint.x, dragStartPoint.y, snapPoint ? snapPoint.x : position.x, snapPoint ? snapPoint.y : position.y, getActionColor(selectedAction, selectedSubtype));
});

canvas.addEventListener('pointerup', (e) => {
    if (!pointerDownPosition) return;
    const wasDragging = isDrawing;
    const startPoint = dragStartPoint;
    const position = getCanvasPosition(e);
    const endPoint = getNearestPoint(position.x, position.y);

    if (wasDragging && startPoint && endPoint) {
        addVector(startPoint, endPoint, selectedPlayerId, selectedPlayerName, selectedAction, selectedSubtype);
        suppressNextClick = true;
        setTimeout(() => { suppressNextClick = false; }, 100);
        currentStartPoint = null;
    }

    isDrawing = false;
    dragStartPoint = null;
    pointerDownPosition = null;
    redrawAll();
});

canvas.addEventListener('click', (e) => {
    if (suppressNextClick) {
        suppressNextClick = false;
        return;
    }
    if (e.detail !== 1) return;

    clearTimeout(clickTimer);
    clickTimer = setTimeout(() => {
        if (!currentStartPoint) return;
        const position = getCanvasPosition(e);
        const endPoint = getNearestPoint(position.x, position.y);
        if (!endPoint) return showNotification('Klik salah satu titik pada grid untuk menentukan titik akhir.', 'info');
        if (endPoint.x === currentStartPoint.x && endPoint.y === currentStartPoint.y) {
            return showNotification('Titik akhir harus berbeda dari titik awal.', 'warning');
        }

        const added = addVector(
            currentStartPoint,
            endPoint,
            currentStartPoint.player_id,
            currentStartPoint.playerName,
            currentStartPoint.action_type,
            currentStartPoint.subtype
        );
        if (added) {
            currentStartPoint = null;
            redrawAll();
        }
    }, 250);
});

canvas.addEventListener('dblclick', (e) => {
    e.preventDefault();
    clearTimeout(clickTimer);
    if(matchStatus === 'Selesai') return showNotification('Pertandingan sudah selesai, sehingga data tidak dapat diubah.', 'warning');
    if(!selectedPlayerId && !selectedAction) return showNotification('Pilih pemain dan event terlebih dahulu sebelum menggambar vektor.', 'info');
    if(!selectedPlayerId) return showNotification('Pilih pemain terlebih dahulu sebelum menggambar vektor.', 'info');
    if(!selectedAction) return showNotification('Pilih event SHOT, PASS, atau CROSS terlebih dahulu.', 'info');

    const position = getCanvasPosition(e);
    const startPoint = getNearestPoint(position.x, position.y);
    if (!startPoint) return showNotification('Double klik salah satu titik pada grid untuk menentukan titik awal.', 'info');

    currentStartPoint = {
        ...startPoint,
        player_id: selectedPlayerId,
        playerName: selectedPlayerName,
        action_type: selectedAction,
        subtype: selectedAction === 'SHOT' ? selectedSubtype : null
    };
    redrawAll();
});

function updateEventList() {
    eventList.innerHTML = '';
    const vectors = getSelectedPlayerVectors();
    document.getElementById('empty-event-list').classList.toggle('hidden', vectors.length > 0);
    [...vectors].reverse().forEach(v => {
        const el = document.createElement('div');
        el.className = 'bg-white border border-slate-100 rounded-lg p-2.5 text-xs flex justify-between items-center shadow-sm gap-2';
        const mode = v.timestamp_mode || (v.input_time ? 'live' : 'manual');
        const player = document.createElement('span');
        player.className = 'font-bold text-slate-700';
        player.textContent = v.playerName || v.player_name || 'Pemain';
        const time = document.createElement('span');
        time.className = 'block text-[10px] text-slate-400';
        time.textContent = mode === 'live' ? new Date(v.input_time).toLocaleString() : `Menit ${v.match_minute}`;
        const details = document.createElement('div');
        details.className = 'min-w-0';
        details.append(player, time);

        const action = document.createElement('span');
        action.className = `inline-flex items-center gap-1.5 font-bold ${v.action_type === 'SHOT' ? 'text-red-500' : (v.action_type === 'PASS' ? 'text-green-500' : 'text-purple-500')}`;
        if (v.action_type === 'SHOT') action.style.color = getActionColor(v.action_type, v.subtype);
        const icon = document.createElement('i');
        icon.setAttribute('data-lucide', getActionIcon(v.action_type));
        icon.className = 'h-3.5 w-3.5';
        action.append(icon, document.createTextNode(v.action_type === 'SHOT' && v.subtype ? `SHOT · ${v.subtype.replace('_', ' ')}` : v.action_type));

        const modeLabel = document.createElement('span');
        modeLabel.className = `rounded-full px-2 py-0.5 text-[9px] font-extrabold tracking-wide ${mode === 'live' ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600'}`;
        modeLabel.textContent = mode.toUpperCase();
        const right = document.createElement('div');
        right.className = 'flex shrink-0 flex-col items-end gap-1';
        right.append(modeLabel, action);
        el.append(details, right);
        eventList.appendChild(el);
    });
    // Render ulang ikon untuk item event yang baru dibuat
    renderIcons();
}

function submitVectors() {
    if (pendingVectors.length === 0) return showNotification('Belum ada vektor baru untuk disimpan.', 'info');

    const saveNextVector = () => {
        if (pendingVectors.length === 0) {
            redrawAll();
            updateEventList();
            updateSummary();
            showNotification('Semua vektor berhasil disimpan.', 'success');
            return;
        }

        const vector = pendingVectors[0];
        fetch(`{{ route('matches.storeVector', $match->id) }}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(vector)
        }).then(async response => {
            const data = await response.json();
            if (!response.ok || !data.success) {
                throw new Error(data.message || 'Gagal menyimpan vektor.');
            }
            savedVectors.push({
                ...vector,
                id: data.data.id,
                player_name: vector.playerName
            });
            pendingVectors.shift();
            saveNextVector();
        }).catch(error => {
            showNotification(`Vektor gagal disimpan: ${error.message}`, 'error');
        });
    };

    saveNextVector();
}

setAction('SHOT');
setSubtype('ON_TARGET');
updateEventList();
updateSummary();
</script>
@endsection
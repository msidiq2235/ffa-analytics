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

<div class="flex flex-col gap-4 h-[calc(100vh-140px)] min-h-0">

    <!-- Bar pilihan Player & Event -->
    <div class="flex flex-wrap items-center justify-between bg-white rounded-xl shadow-sm p-3 border border-slate-200 shrink-0">
        <div class="flex items-center gap-2 overflow-x-auto pr-4">
            <span class="text-xs font-bold text-slate-400 tracking-wider mr-2 shrink-0 flex items-center gap-1.5">
                <i data-lucide="users" class="w-4 h-4"></i> PLAYER
            </span>
            @foreach($startingEleven as $player)
            <button id="btn-player-{{ $player->id }}" onclick="setPlayer({{ $player->id }}, '{{ $player->jersey_number }}', '{{ $player->name }}')" class="player-btn shrink-0 w-10 h-10 rounded-lg border border-slate-200 bg-slate-50 text-slate-600 font-bold text-sm hover:bg-blue-50 hover:border-blue-200 transition-colors">
                {{ $player->jersey_number ?? '-' }}
            </button>
            @endforeach
        </div>

        <div class="flex flex-wrap items-center gap-2 border-l border-slate-200 pl-4">
            <button type="button" id="live-mode-toggle" onclick="toggleLiveMode()" aria-pressed="false" title="LIVE ON mencatat waktu dari jam perangkat" class="px-4 py-2 rounded-full border border-slate-200 bg-slate-100 text-slate-600 font-bold text-sm transition-colors inline-flex items-center gap-2 shrink-0">
                <span class="w-2 h-2 rounded-full bg-slate-400" id="live-mode-indicator"></span>
                <span id="live-mode-label">LIVE OFF</span>
            </button>
            <span class="text-xs font-bold text-slate-400 tracking-wider mr-2 flex items-center gap-1.5">
                <i data-lucide="mouse-pointer-click" class="w-4 h-4"></i> EVENT
            </span>
            <button id="btn-action-SHOT" onclick="setAction('SHOT')" class="action-btn px-5 py-2 rounded-full border border-slate-200 bg-slate-50 text-slate-600 font-bold text-sm hover:bg-red-50 hover:text-red-600 transition-colors inline-flex items-center gap-2">
                <i data-lucide="crosshair" class="w-4 h-4"></i> SHOT
            </button>
            <button id="btn-action-PASS" onclick="setAction('PASS')" class="action-btn px-5 py-2 rounded-full border border-slate-200 bg-slate-50 text-slate-600 font-bold text-sm hover:bg-green-50 hover:text-green-600 transition-colors inline-flex items-center gap-2">
                <i data-lucide="move-up-right" class="w-4 h-4"></i> PASS
            </button>
            <button id="btn-action-CROSS" onclick="setAction('CROSS')" class="action-btn px-5 py-2 rounded-full border border-slate-200 bg-slate-50 text-slate-600 font-bold text-sm hover:bg-purple-50 hover:text-purple-600 transition-colors inline-flex items-center gap-2">
                <i data-lucide="corner-up-right" class="w-4 h-4"></i> CROSS
            </button>
        </div>
    </div>

    <div class="flex gap-6 flex-1 min-h-0">
        <!-- Lapangan + canvas -->
        <div class="flex-1 relative flex flex-col">
            <div class="flex-1 relative bg-[#2C6E36] rounded-xl border-4 border-white shadow-lg overflow-hidden min-h-[400px]" id="pitch-container">
                <div class="absolute inset-0 border-2 border-white/40 m-6 pointer-events-none"></div>
                <div class="absolute left-1/2 top-6 bottom-6 w-0 border-l-2 border-white/40 pointer-events-none"></div>
                <div class="absolute top-1/2 left-1/2 w-32 h-32 -mt-16 -ml-16 border-2 border-white/40 rounded-full pointer-events-none"></div>
                <div class="absolute top-1/2 left-1/2 w-2 h-2 -mt-1 -ml-1 bg-white/40 rounded-full pointer-events-none"></div>
                <div class="absolute top-1/2 left-6 w-32 h-64 -mt-32 border-2 border-white/40 border-l-0 pointer-events-none"></div>
                <div class="absolute top-1/2 left-6 w-12 h-24 -mt-12 border-2 border-white/40 border-l-0 pointer-events-none"></div>
                <div class="absolute top-1/2 right-6 w-32 h-64 -mt-32 border-2 border-white/40 border-r-0 pointer-events-none"></div>
                <div class="absolute top-1/2 right-6 w-12 h-24 -mt-12 border-2 border-white/40 border-r-0 pointer-events-none"></div>
                <canvas id="vectorCanvas" class="absolute inset-0 w-full h-full cursor-crosshair z-10"></canvas>
            </div>
            <div class="absolute -bottom-4 -left-4 bg-slate-900 text-green-400 text-xs font-mono font-bold px-4 py-3 rounded-lg shadow-lg border border-slate-700 z-20 flex items-center gap-2">
                <i data-lucide="grid-3x3" class="w-4 h-4"></i>
                <span>48-POINT GRID<br><span class="text-white/70">Drag dari awal ke akhir · Double klik awal, klik akhir</span></span>
            </div>
        </div>

        <!-- Panel kanan -->
        <div class="w-80 flex flex-col bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden shrink-0">
            <div class="h-14 bg-linear-to-r from-slate-800 to-red-700 bg-gradient-to-r flex items-center gap-2 px-4">
                <i data-lucide="activity" class="w-4 h-4 text-white/80"></i>
                <span class="text-white font-bold text-sm" id="active-indicator">-</span>
            </div>
            <div class="flex border-b border-slate-100 p-2 gap-2 bg-slate-50">
                <button class="flex-1 py-2 text-xs font-bold bg-white shadow-sm rounded text-slate-700 flex justify-center items-center gap-1.5">
                    <i data-lucide="map-pin" class="w-3.5 h-3.5"></i>
                    Live Mapping
                </button>
                <button class="flex-1 py-2 text-xs font-bold text-slate-400 hover:text-slate-600 flex justify-center items-center gap-1.5 transition-colors">
                    <i data-lucide="chart-column" class="w-3.5 h-3.5"></i>
                    Vector Summary
                </button>
            </div>
            <div class="flex-1 p-4 overflow-y-auto flex flex-col gap-2" id="event-list">
            </div>
            <div class="p-4 border-t border-slate-100 flex gap-2 bg-slate-50">
                <button type="button" class="flex-1 bg-blue-500 hover:bg-blue-600 active:scale-95 text-white text-sm font-bold py-3 rounded-lg flex justify-center items-center gap-2 transition">
                    <i data-lucide="download" class="w-4 h-4"></i>
                    EXPORT
                </button>
                <button type="button" onclick="submitVectors()" class="flex-1 bg-green-500 hover:bg-green-600 active:scale-95 text-white text-sm font-bold py-3 rounded-lg flex justify-center items-center gap-2 transition">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    SAVE
                </button>
            </div>
        </div>
    </div>
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

const canvas = document.getElementById('vectorCanvas');
const ctx = canvas.getContext('2d');
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
    pendingVectors.forEach(v => {
        const start = getPointFromPct(v.start_x, v.start_y);
        const end = getPointFromPct(v.end_x, v.end_y);
        drawArrow(start.x, start.y, end.x, end.y, getActionColor(v.action_type));
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
    updateIndicator();
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
    if(selectedPlayerId && selectedAction) {
        indicator.innerHTML = `${selectedPlayerName} <span class="mx-2 text-slate-400">|</span> ${selectedAction}`;
    } else {
        indicator.innerText = '-';
    }
}

function getActionColor(action) {
    if (action === 'SHOT') return '#ef4444';
    if (action === 'PASS') return '#22c55e';
    if (action === 'CROSS') return '#a855f7';
    return '#ffffff';
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

function addVector(startPoint, endPoint, playerId, playerName, action) {
    if (!startPoint || !endPoint || (startPoint.x === endPoint.x && startPoint.y === endPoint.y)) return false;

    const vector = {
        player_id: playerId,
        playerName: playerName,
        action_type: action,
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
    drawArrow(dragStartPoint.x, dragStartPoint.y, snapPoint ? snapPoint.x : position.x, snapPoint ? snapPoint.y : position.y, getActionColor(selectedAction));
});

canvas.addEventListener('pointerup', (e) => {
    if (!pointerDownPosition) return;
    const wasDragging = isDrawing;
    const startPoint = dragStartPoint;
    const position = getCanvasPosition(e);
    const endPoint = getNearestPoint(position.x, position.y);

    if (wasDragging && startPoint && endPoint) {
        addVector(startPoint, endPoint, selectedPlayerId, selectedPlayerName, selectedAction);
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
            currentStartPoint.action_type
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
        action_type: selectedAction
    };
    redrawAll();
});

function updateEventList() {
    eventList.innerHTML = '';
    [...pendingVectors].reverse().forEach((v, index) => {
        const el = document.createElement('div');
        el.className = 'bg-white border border-slate-100 rounded-lg p-2.5 text-xs flex justify-between items-center shadow-sm gap-2';
        let colorClass = v.action_type === 'SHOT' ? 'text-red-500' : (v.action_type === 'PASS' ? 'text-green-500' : 'text-purple-500');
        const modeLabel = v.timestamp_mode === 'live' ? 'LIVE' : 'MANUAL';
        const modeClass = v.timestamp_mode === 'live' ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600';
        el.innerHTML = `
            <div class="min-w-0">
                <span class="font-bold text-slate-700">${v.playerName}</span>
                <span class="block text-[10px] text-slate-400">${v.timestamp_mode === 'live' ? new Date(v.input_time).toLocaleString() : `Menit ${v.match_minute}`}</span>
            </div>
            <div class="flex shrink-0 flex-col items-end gap-1">
                <span class="rounded-full px-2 py-0.5 text-[9px] font-extrabold tracking-wide ${modeClass}">${modeLabel}</span>
                <span class="font-bold ${colorClass} inline-flex items-center gap-1.5">
                    <i data-lucide="${getActionIcon(v.action_type)}" class="w-3.5 h-3.5"></i>${v.action_type}
                </span>
            </div>`;
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
            pendingVectors.shift();
            saveNextVector();
        }).catch(error => {
            showNotification(`Vektor gagal disimpan: ${error.message}`, 'error');
        });
    };

    saveNextVector();
}
</script>
@endsection
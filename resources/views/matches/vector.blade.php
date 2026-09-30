@extends('layouts.app')

@section('title', 'Match Mapping Event - FFA Analytics')
@section('header', 'Match Mapping Event')
@section('subheader', $match->title . ' - ' . \Carbon\Carbon::parse($match->match_date)->format('M d, Y'))

@section('header_action')
<div class="flex items-center gap-3">
<a href="{{ route('matches.index') }}" class="px-4 py-2 bg-slate-100 text-slate-600 font-semibold rounded-lg border border-slate-200 hover:bg-slate-200 flex items-center gap-2 transition-colors">
<span><</span> Kembali
</a>
@if($match->status === 'Selesai')
<span class="px-4 py-2 bg-green-50 text-green-600 border border-green-200 font-bold rounded-lg flex items-center gap-2">
Data Final (Terkunci)
</span>
@endif
</div>
@endsection

@section('content')
<div class="flex flex-col gap-4 h-[calc(100vh-140px)] min-h-0">

<div class="flex flex-wrap items-center justify-between bg-white rounded-xl shadow-sm p-3 border border-slate-200 shrink-0">
<div class="flex items-center gap-2 overflow-x-auto pr-4">
<span class="text-xs font-bold text-slate-400 tracking-wider mr-2 shrink-0">PLAYER</span>
@foreach($startingEleven as $player)
<button id="btn-player-{{ $player->id }}" onclick="setPlayer({{ $player->id }}, '{{ $player->jersey_number }}', '{{ $player->name }}')" class="player-btn shrink-0 w-10 h-10 rounded-lg border border-slate-200 bg-slate-50 text-slate-600 font-bold text-sm hover:bg-blue-50 hover:border-blue-200 transition-colors">
{{ $player->jersey_number ?? '-' }}
</button>
@endforeach
</div>

<div class="flex items-center gap-2 border-l border-slate-200 pl-4 shrink-0">
<span class="text-xs font-bold text-slate-400 tracking-wider mr-2">EVENT</span>
<button id="btn-action-SHOT" onclick="setAction('SHOT')" class="action-btn px-6 py-2 rounded-full border border-slate-200 bg-slate-50 text-slate-600 font-bold text-sm hover:bg-red-50 hover:text-red-600 transition-colors">SHOT</button>
<button id="btn-action-PASS" onclick="setAction('PASS')" class="action-btn px-6 py-2 rounded-full border border-slate-200 bg-slate-50 text-slate-600 font-bold text-sm hover:bg-green-50 hover:text-green-600 transition-colors">PASS</button>
<button id="btn-action-CROSS" onclick="setAction('CROSS')" class="action-btn px-6 py-2 rounded-full border border-slate-200 bg-slate-50 text-slate-600 font-bold text-sm hover:bg-purple-50 hover:text-purple-600 transition-colors">CROSS</button>
</div>
</div>

<div class="flex gap-6 flex-1 min-h-0">
<div class="flex-1 relative flex flex-col">
<div class="flex-1 relative bg-[#2C6E36] rounded-xl border-4 border-white shadow-lg overflow-hidden min-h-400px" id="pitch-container">
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
<div class="absolute -bottom-4 -left-4 bg-slate-900 text-green-400 text-xs font-mono font-bold px-4 py-3 rounded-lg shadow-lg border border-slate-700 z-20">
48-POINT GRID
</div>
</div>

<div class="w-80 flex flex-col bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden shrink-0">
<div class="h-14 bg-linear-to-r from-slate-800 to-red-700 flex items-center px-4">
<span class="text-white font-bold text-sm" id="active-indicator">-</span>
</div>
<div class="flex border-b border-slate-100 p-2 gap-2 bg-slate-50">
<button class="flex-1 py-2 text-xs font-bold bg-white shadow-sm rounded text-slate-700 flex justify-center items-center gap-1">
<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
Live Mapping
</button>
<button class="flex-1 py-2 text-xs font-bold text-slate-400 hover:text-slate-600 flex justify-center items-center gap-1 transition-colors">
<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
Vector Summary
</button>
</div>
<div class="flex-1 p-4 overflow-y-auto flex flex-col gap-2" id="event-list">
</div>
<div class="p-4 border-t border-slate-100 flex gap-2 bg-slate-50">
<button class="flex-1 bg-blue-500 hover:bg-blue-600 text-white text-sm font-bold py-3 rounded-lg flex justify-center items-center gap-2 transition-colors">
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
EXPORT
</button>
<button onclick="submitVectors()" class="flex-1 bg-green-500 hover:bg-green-600 text-white text-sm font-bold py-3 rounded-lg flex justify-center items-center gap-2 transition-colors">
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
SAVE
</button>
</div>
</div>
</div>
</div>

<script>
let selectedPlayerId = null;
let selectedPlayerName = '';
let selectedAction = null;
let isDrawing = false;
let gridPoints = [];
let pendingVectors = [];
let currentStartPoint = null;

const canvas = document.getElementById('vectorCanvas');
const ctx = canvas.getContext('2d');
const matchStatus = '{{ $match->status }}';
const eventList = document.getElementById('event-list');
const indicator = document.getElementById('active-indicator');

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

canvas.addEventListener('mousedown', (e) => {
if(matchStatus === 'Selesai') return alert('Pertandingan selesai, data dikunci.');
if(!selectedPlayerId || !selectedAction) return alert('Pilih PLAYER dan EVENT di atas terlebih dahulu.');
const rect = canvas.getBoundingClientRect();
const clickX = e.clientX - rect.left;
const clickY = e.clientY - rect.top;
currentStartPoint = getNearestPoint(clickX, clickY);
if (currentStartPoint) {
isDrawing = true;
}
});

canvas.addEventListener('mousemove', (e) => {
if (!isDrawing || !currentStartPoint) return;
const rect = canvas.getBoundingClientRect();
const currentX = e.clientX - rect.left;
const currentY = e.clientY - rect.top;
redrawAll();
const snapPoint = getNearestPoint(currentX, currentY);
const endX = snapPoint ? snapPoint.x : currentX;
const endY = snapPoint ? snapPoint.y : currentY;
drawArrow(currentStartPoint.x, currentStartPoint.y, endX, endY, getActionColor(selectedAction));
});

canvas.addEventListener('mouseup', (e) => {
if (!isDrawing || !currentStartPoint) return;
isDrawing = false;
const rect = canvas.getBoundingClientRect();
const clickX = e.clientX - rect.left;
const clickY = e.clientY - rect.top;
const snapPoint = getNearestPoint(clickX, clickY);

if (snapPoint && (snapPoint.x !== currentStartPoint.x || snapPoint.y !== currentStartPoint.y)) {
const pctStartX = (currentStartPoint.x / canvas.width) * 100;
const pctStartY = (currentStartPoint.y / canvas.height) * 100;
const pctEndX = (snapPoint.x / canvas.width) * 100;
const pctEndY = (snapPoint.y / canvas.height) * 100;

pendingVectors.push({
player_id: selectedPlayerId,
playerName: selectedPlayerName,
action_type: selectedAction,
start_x: pctStartX,
start_y: pctStartY,
end_x: pctEndX,
end_y: pctEndY
});

updateEventList();
}
redrawAll();
currentStartPoint = null;
});

function updateEventList() {
eventList.innerHTML = '';
[...pendingVectors].reverse().forEach((v, index) => {
const el = document.createElement('div');
el.className = 'bg-white border border-slate-100 rounded p-2 text-xs flex justify-between shadow-sm';
let colorClass = v.action_type === 'SHOT' ? 'text-red-500' : (v.action_type === 'PASS' ? 'text-green-500' : 'text-purple-500');
el.innerHTML = `<span class="font-bold text-slate-700">${v.playerName}</span><span class="font-bold ${colorClass}">${v.action_type}</span>`;
eventList.appendChild(el);
});
}

function submitVectors() {
if (pendingVectors.length === 0) return alert('Tidak ada data vektor baru untuk disimpan.');
const lastVector = pendingVectors[pendingVectors.length - 1];
fetch(`{{ route('matches.storeVector', $match->id) }}`, {
method: 'POST',
headers: {
'Content-Type': 'application/json',
'X-CSRF-TOKEN': '{{ csrf_token() }}'
},
body: JSON.stringify(lastVector) 
}).then(res => res.json()).then(data => {
alert('Vektor terakhir berhasil disimpan!');
}).catch(err => console.error(err));
}
</script>
@endsection
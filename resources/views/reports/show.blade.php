@extends('layouts.app')
@section('title', 'Match Report - FFA Analytics')
@section('header', 'Match Report Dashboard')
@section('subheader', $match->title . ' - ' . \Carbon\Carbon::parse($match->match_date)->format('d M Y'))
@section('header_action')
<div class="flex gap-2">
<a href="{{ route('matches.index') }}" class="px-4 py-2 bg-slate-100 text-slate-600 font-semibold rounded-lg border border-slate-200 hover:bg-slate-200 transition-colors">
Kembali
</a>
<a href="{{ route('reports.match.pdf', $match->id) }}" class="px-4 py-2 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-600 flex items-center gap-2 shadow-sm transition-colors">
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
Export PDF
</a>
</div>
@endsection
@section('content')
<div class="space-y-6">
<div class="grid grid-cols-1 md:grid-cols-4 gap-6">
<div class="bg-white rounded-xl border border-slate-100 shadow-sm p-6 flex flex-col items-center justify-center">
<span class="text-slate-400 text-xs font-bold tracking-wider mb-2">TOTAL GOALS</span>
<span class="text-4xl font-black text-slate-800">{{ $teamStats['goals'] }}</span>
</div>
<div class="bg-white rounded-xl border border-slate-100 shadow-sm p-6 flex flex-col items-center justify-center">
<span class="text-slate-400 text-xs font-bold tracking-wider mb-2">TOTAL SHOTS</span>
<span class="text-4xl font-black text-slate-800">{{ $teamStats['shots'] }}</span>
</div>
<div class="bg-white rounded-xl border border-slate-100 shadow-sm p-6 flex flex-col items-center justify-center">
<span class="text-slate-400 text-xs font-bold tracking-wider mb-2">SUCC. PASSES</span>
<span class="text-4xl font-black text-slate-800">{{ $teamStats['passes'] }}</span>
</div>
<div class="bg-white rounded-xl border border-slate-100 shadow-sm p-6 flex flex-col items-center justify-center">
<span class="text-slate-400 text-xs font-bold tracking-wider mb-2">TACKLES</span>
<span class="text-4xl font-black text-slate-800">{{ $teamStats['tackles'] }}</span>
</div>
</div>
<div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
<div class="flex border-b border-slate-100 bg-slate-50">
<button onclick="switchTab('stats')" id="tab-stats" class="px-6 py-4 font-bold text-sm text-blue-600 border-b-2 border-blue-600 bg-white transition-colors">Data Statistik</button>
<button onclick="switchTab('vector')" id="tab-vector" class="px-6 py-4 font-bold text-sm text-slate-500 border-b-2 border-transparent hover:text-slate-700 transition-colors">Vector Summary</button>
</div>
<div id="content-stats" class="block overflow-x-auto">
<table class="w-full text-left text-sm text-slate-600">
<thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-100">
<tr>
<th class="px-6 py-4">Player</th>
<th class="px-6 py-4 text-center">Pos</th>
<th class="px-6 py-4 text-center">Goals</th>
<th class="px-6 py-4 text-center">Assists</th>
<th class="px-6 py-4 text-center">Shots (On/Off)</th>
<th class="px-6 py-4 text-center">Passes</th>
<th class="px-6 py-4 text-center">Tackles</th>
</tr>
</thead>
<tbody class="divide-y divide-slate-100">
@foreach($match->players as $player)
<tr class="hover:bg-slate-50 transition-colors">
<td class="px-6 py-4 font-bold text-slate-800">{{ $player->jersey_number ? $player->jersey_number . ' - ' : '' }}{{ $player->name }}</td>
<td class="px-6 py-4 text-center"><span class="px-2 py-1 text-xs rounded bg-slate-100 text-slate-600 font-medium">{{ $player->pivot->position }}</span></td>
<td class="px-6 py-4 text-center font-bold text-blue-600">{{ $stats[$player->id]->total_goals ?? 0 }}</td>
<td class="px-6 py-4 text-center">{{ $stats[$player->id]->total_assists ?? 0 }}</td>
<td class="px-6 py-4 text-center">{{ $stats[$player->id]->total_shots_target ?? 0 }} / {{ $stats[$player->id]->total_shots_off ?? 0 }}</td>
<td class="px-6 py-4 text-center">{{ $stats[$player->id]->total_passes ?? 0 }}</td>
<td class="px-6 py-4 text-center">{{ $stats[$player->id]->total_tackles ?? 0 }}</td>
</tr>
@endforeach
</tbody>
</table>
</div>
<div id="content-vector" class="hidden p-6 bg-slate-50">
<div class="flex gap-4">
<div class="w-1/4 bg-white rounded-lg border border-slate-200 p-4 shadow-sm h-500px flex flex-col">
<h3 class="font-bold text-slate-800 mb-4 text-sm">Filter Tampilan</h3>
<div class="space-y-3 flex-1 overflow-y-auto">
<label class="flex items-center gap-2 text-sm font-semibold text-slate-700 cursor-pointer p-2 hover:bg-slate-50 rounded">
<input type="checkbox" checked onchange="toggleAction('SHOT')" class="rounded border-slate-300 text-red-500 focus:ring-red-500"> SHOT <span class="w-3 h-3 rounded-full bg-red-500 ml-auto"></span>
</label>
<label class="flex items-center gap-2 text-sm font-semibold text-slate-700 cursor-pointer p-2 hover:bg-slate-50 rounded">
<input type="checkbox" checked onchange="toggleAction('PASS')" class="rounded border-slate-300 text-green-500 focus:ring-green-500"> PASS <span class="w-3 h-3 rounded-full bg-green-500 ml-auto"></span>
</label>
<label class="flex items-center gap-2 text-sm font-semibold text-slate-700 cursor-pointer p-2 hover:bg-slate-50 rounded">
<input type="checkbox" checked onchange="toggleAction('CROSS')" class="rounded border-slate-300 text-purple-500 focus:ring-purple-500"> CROSS <span class="w-3 h-3 rounded-full bg-purple-500 ml-auto"></span>
</label>
</div>
</div>
<div class="w-3/4 relative bg-[#2C6E36] rounded-lg border-4 border-white shadow-lg overflow-hidden h-500px" id="pitch-container">
<div class="absolute inset-0 border-2 border-white/40 m-6 pointer-events-none"></div>
<div class="absolute left-1/2 top-6 bottom-6 w-0 border-l-2 border-white/40 pointer-events-none"></div>
<div class="absolute top-1/2 left-1/2 w-32 h-32 -mt-16 -ml-16 border-2 border-white/40 rounded-full pointer-events-none"></div>
<div class="absolute top-1/2 left-1/2 w-2 h-2 -mt-1 -ml-1 bg-white/40 rounded-full pointer-events-none"></div>
<div class="absolute top-1/2 left-6 w-32 h-64 -mt-32 border-2 border-white/40 border-l-0 pointer-events-none"></div>
<div class="absolute top-1/2 left-6 w-12 h-24 -mt-12 border-2 border-white/40 border-l-0 pointer-events-none"></div>
<div class="absolute top-1/2 right-6 w-32 h-64 -mt-32 border-2 border-white/40 border-r-0 pointer-events-none"></div>
<div class="absolute top-1/2 right-6 w-12 h-24 -mt-12 border-2 border-white/40 border-r-0 pointer-events-none"></div>
<canvas id="summaryCanvas" class="absolute inset-0 w-full h-full z-10"></canvas>
</div>
</div>
</div>
</div>
</div>
<script>
const rawVectors = @json($vectors);
let activeFilters = ['SHOT', 'PASS', 'CROSS'];
const canvas = document.getElementById('summaryCanvas');
const ctx = canvas.getContext('2d');
function switchTab(tab) {
document.getElementById('content-stats').classList.add('hidden');
document.getElementById('content-vector').classList.add('hidden');
document.getElementById('tab-stats').classList.remove('text-blue-600', 'border-blue-600', 'bg-white');
document.getElementById('tab-stats').classList.add('text-slate-500', 'border-transparent');
document.getElementById('tab-vector').classList.remove('text-blue-600', 'border-blue-600', 'bg-white');
document.getElementById('tab-vector').classList.add('text-slate-500', 'border-transparent');
document.getElementById('content-' + tab).classList.remove('hidden');
document.getElementById('tab-' + tab).classList.remove('text-slate-500', 'border-transparent');
document.getElementById('tab-' + tab).classList.add('text-blue-600', 'border-blue-600', 'bg-white');
if (tab === 'vector') { setTimeout(resizeCanvas, 50); }
}
function toggleAction(action) {
const index = activeFilters.indexOf(action);
if (index > -1) { activeFilters.splice(index, 1); } else { activeFilters.push(action); }
renderVectors();
}
function getActionColor(action) {
if (action === 'SHOT') return 'rgba(239, 68, 68, 0.8)';
if (action === 'PASS') return 'rgba(34, 197, 94, 0.8)';
if (action === 'CROSS') return 'rgba(168, 85, 247, 0.8)';
return 'rgba(255, 255, 255, 0.8)';
}
function drawArrow(fromX, fromY, toX, toY, color, playerName, number) {
const headlen = 10;
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
ctx.lineWidth = 3;
ctx.stroke();
ctx.fillStyle = color;
ctx.beginPath();
ctx.arc(fromX, fromY, 8, 0, 2 * Math.PI);
ctx.fill();
ctx.fillStyle = '#ffffff';
ctx.font = 'bold 9px sans-serif';
ctx.textAlign = 'center';
ctx.textBaseline = 'middle';
ctx.fillText(number || '-', fromX, fromY);
}
function renderVectors() {
ctx.clearRect(0, 0, canvas.width, canvas.height);
rawVectors.forEach(v => {
if (activeFilters.includes(v.action_type)) {
const startX = (v.start_x / 100) * canvas.width;
const startY = (v.start_y / 100) * canvas.height;
const endX = (v.end_x / 100) * canvas.width;
const endY = (v.end_y / 100) * canvas.height;
drawArrow(startX, startY, endX, endY, getActionColor(v.action_type), v.player_name, v.jersey_number);
}
});
}
function resizeCanvas() {
canvas.width = canvas.offsetWidth;
canvas.height = canvas.offsetHeight;
renderVectors();
}
window.addEventListener('resize', resizeCanvas);
</script>
@endsection
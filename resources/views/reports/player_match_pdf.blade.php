<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan Pertandingan - {{ $player->name }}</title>
<style>
@page{margin:24px 28px 30px}
*{box-sizing:border-box}
body{font-family:Helvetica,Arial,sans-serif;font-size:10px;color:#24312b;margin:0;line-height:1.35}
.top-band{height:8px;background:#159447;margin:-24px -28px 16px}
.brand{width:100%;border-collapse:collapse;margin-bottom:12px}
.brand td{vertical-align:middle}
.brand-mark{width:38px;height:38px;border-radius:19px;background:#159447;color:#fff;text-align:center;font-size:15px;font-weight:bold;padding-top:10px}
.brand-name{padding-left:9px;font-size:10px;font-weight:bold;color:#16783d;letter-spacing:1px}
.brand-name span{display:block;color:#69766f;font-size:8px;font-weight:normal;letter-spacing:.4px;margin-top:3px}
.report-tag{text-align:right;color:#16783d;font-size:9px;font-weight:bold;letter-spacing:.8px}
.report-tag span{display:block;margin-top:4px;color:#69766f;font-size:8px;font-weight:normal;letter-spacing:0}
.player-card{width:100%;border-collapse:collapse;background:#f1f8f3;border:1px solid #d5e9db}
.player-card td{padding:10px;vertical-align:middle}
.player-number{width:74px;text-align:center;background:#159447;color:#fff;font-size:24px;font-weight:bold}
.player-name{font-size:19px;font-weight:bold;color:#173b28}
.player-meta{font-size:9px;color:#637169;margin-top:4px}
.match-details{width:100%;border-collapse:collapse;margin-top:12px}
.match-details td{width:25%;background:#f7faf8;border:1px solid #e1ebe4;padding:8px}
.match-details strong{display:block;font-size:7px;color:#748078;text-transform:uppercase;margin-bottom:3px}
.match-details span{font-size:9px;color:#263c2d;font-weight:bold}
.title{margin:14px 0 7px;background:#159447;color:#fff;padding:6px 9px;font-size:9px;font-weight:bold;letter-spacing:.5px;text-transform:uppercase}
.cards{width:100%;border-collapse:separate;border-spacing:5px 0}
.cards td{background:#f1f8f3;border:1px solid #dcebe0;text-align:center;padding:8px 4px;width:20%}
.cards .num{display:block;font-size:18px;font-weight:bold;color:#16783d}
.cards .lbl{display:block;font-size:7px;color:#64736a;text-transform:uppercase;font-weight:bold;letter-spacing:.3px;margin-top:2px}
.panel{border:1px solid #dce7df;padding:8px;background:#fff}
.panel-title{font-size:9px;font-weight:bold;color:#254631;margin-bottom:5px}
.panel-subtitle{font-size:8px;color:#748078;margin-bottom:6px}
.pitch{width:100%;height:330px;display:block;background:#eff8f0}
.legend{font-size:7px;color:#59675e;padding-top:5px}
.legend span{display:inline-block;margin-right:10px}
.dot{display:inline-block;width:6px;height:6px;border-radius:3px;margin-right:3px}
.empty{height:330px;text-align:center;color:#8a978f;padding-top:150px;background:#f7faf8}
.report-page{page-break-before:always}
.page-heading{border-bottom:2px solid #159447;padding-bottom:7px;margin-bottom:10px}
.page-heading h2{margin:0;color:#173b28;font-size:16px}
.page-heading p{margin:3px 0 0;color:#748078;font-size:8px}
.stat-columns{width:100%;border-collapse:separate;border-spacing:6px 0}
.stat-columns td{width:50%;vertical-align:top;padding:0}
.stat-group{width:100%;border-collapse:collapse;margin:0 0 9px;page-break-inside:avoid}
.stat-group th{background:#e8f4eb;color:#176b39;text-align:left;padding:5px 7px;font-size:8px;text-transform:uppercase}
.stat-group td{border-bottom:1px solid #e8eee9;padding:5px 7px;font-size:9px}
.stat-group td:last-child{text-align:right;font-weight:bold;color:#263c2d;width:55px}
.footer{position:fixed;bottom:-17px;left:0;right:0;border-top:1px solid #dce7df;padding-top:5px;color:#849087;font-size:7px}
.footer .right{float:right}
tr{page-break-inside:avoid}
</style>
</head>
<body>
<div class="top-band"></div>
<table class="brand">
    <tr>
        <td style="width:38px"><div class="brand-mark">FFA</div></td>
        <td class="brand-name">FFA ANALYTICS<span>PLAYER PERFORMANCE REPORT</span></td>
        <td class="report-tag">LAPORAN PERTANDINGAN<span>Data dari pertandingan ini saja</span></td>
    </tr>
</table>

<table class="player-card">
    <tr>
        <td class="player-number">#{{ $player->jersey_number ?? $player->number ?? '-' }}</td>
        <td>
            <div class="player-name">{{ $player->popular_name ?: $player->name }}</div>
            <div class="player-meta">{{ $player->name }} &nbsp;|&nbsp; {{ $position ?? $player->position ?? 'Posisi tidak tersedia' }} &nbsp;|&nbsp; {{ $player->club->name ?? 'Tim tidak tersedia' }}</div>
        </td>
    </tr>
</table>

<table class="match-details">
    <tr>
        <td><strong>Pertandingan</strong><span>{{ $match->title ?? 'Pertandingan' }}</span></td>
        <td><strong>Lawan</strong><span>{{ $opponent }}</span></td>
        <td><strong>Tanggal</strong><span>{{ \Carbon\Carbon::parse($match->match_date)->format('d/m/Y') }}</span></td>
        <td><strong>Tempat</strong><span>{{ $match->venue ?? '-' }}</span></td>
    </tr>
</table>

<div class="title">Statistik Pertandingan</div>
<table class="cards">
    <tr>
        <td><span class="num">{{ $totals->goals }}</span><span class="lbl">Gol</span></td>
        <td><span class="num">{{ $totals->assists }}</span><span class="lbl">Assist</span></td>
        <td><span class="num">{{ $totals->shots_target }}</span><span class="lbl">Tembakan Tepat</span></td>
        <td><span class="num">{{ $totals->passes }}</span><span class="lbl">Umpan Berhasil</span></td>
        <td><span class="num">{{ $totals->tackles }}</span><span class="lbl">Tackle</span></td>
    </tr>
</table>

<div class="title">Peta Vektor Pertandingan</div>
<div class="panel">
    <div class="panel-title">Aksi pemain pada {{ $match->title ?? 'pertandingan ini' }}</div>
    @if($vectors->isNotEmpty())
        <svg class="pitch" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 60">
            <rect x="3" y="3" width="94" height="54" rx="1" fill="#eff8f0" stroke="#94b99d" stroke-width=".7"/>
            <line x1="50" y1="3" x2="50" y2="57" stroke="#94b99d" stroke-width=".6"/>
            <circle cx="50" cy="30" r="8" fill="none" stroke="#94b99d" stroke-width=".6"/>
            <circle cx="50" cy="30" r=".7" fill="#94b99d"/>
            <rect x="3" y="17" width="13" height="26" fill="none" stroke="#94b99d" stroke-width=".6"/>
            <rect x="84" y="17" width="13" height="26" fill="none" stroke="#94b99d" stroke-width=".6"/>
            <rect x="3" y="23" width="5" height="14" fill="none" stroke="#94b99d" stroke-width=".6"/>
            <rect x="92" y="23" width="5" height="14" fill="none" stroke="#94b99d" stroke-width=".6"/>
            @foreach($vectors as $vector)
                @php
                    $startX = 4 + max(0, min(100, (float) $vector->start_x)) * .92;
                    $startY = 4 + max(0, min(100, (float) $vector->start_y)) * .52;
                    $endX = 4 + max(0, min(100, (float) $vector->end_x)) * .92;
                    $endY = 4 + max(0, min(100, (float) $vector->end_y)) * .52;
                    $action = strtolower($vector->action_type ?? '');
                    $color = strpos($action, 'shot') !== false || strpos($action, 'shoot') !== false
                        ? '#ed7d31'
                        : (strpos($action, 'pass') !== false ? '#2878c8' : '#9a4bbb');
                    $angle = atan2($endY - $startY, $endX - $startX);
                    $baseX = $endX - 2.1 * cos($angle);
                    $baseY = $endY - 2.1 * sin($angle);
                    $leftX = $baseX + .9 * sin($angle);
                    $leftY = $baseY - .9 * cos($angle);
                    $rightX = $baseX - .9 * sin($angle);
                    $rightY = $baseY + .9 * cos($angle);
                @endphp
                <line x1="{{ $startX }}" y1="{{ $startY }}" x2="{{ $baseX }}" y2="{{ $baseY }}" stroke="{{ $color }}" stroke-width=".65" opacity=".8"/>
                <polygon points="{{ $endX }},{{ $endY }} {{ $leftX }},{{ $leftY }} {{ $rightX }},{{ $rightY }}" fill="{{ $color }}"/>
                <circle cx="{{ $startX }}" cy="{{ $startY }}" r=".75" fill="{{ $color }}"/>
            @endforeach
        </svg>
        <div class="legend">
            <span><i class="dot" style="background:#ed7d31"></i>Tembakan</span>
            <span><i class="dot" style="background:#2878c8"></i>Umpan</span>
            <span><i class="dot" style="background:#9a4bbb"></i>Aksi lainnya</span>
        </div>
    @else
        <div class="empty">Belum ada data vektor untuk pemain pada pertandingan ini.</div>
    @endif
</div>

<div class="report-page">
    <div class="page-heading">
        <h2>Rincian Statistik Pertandingan</h2>
        <p>{{ $player->popular_name ?: $player->name }} &nbsp;|&nbsp; {{ $match->title ?? 'Pertandingan' }} — {{ \Carbon\Carbon::parse($match->match_date)->format('d/m/Y') }}</p>
    </div>
    <table class="stat-columns">
        @foreach(array_chunk($statisticGroups, 2, true) as $row)
            <tr>
                @foreach($row as $groupName => $metrics)
                    <td>
                        <table class="stat-group">
                            <thead><tr><th colspan="2">{{ $groupName }}</th></tr></thead>
                            <tbody>
                                @foreach($metrics as $field => $label)
                                    <tr>
                                        <td>{{ $label }}</td>
                                        <td>{{ number_format($statisticTotals[$field] ?? 0) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </td>
                @endforeach
                @if(count($row) < 2)<td></td>@endif
            </tr>
        @endforeach
    </table>
</div>

<div class="footer">FFA Analytics — dibuat {{ now()->format('d/m/Y H:i') }}<span class="right">Laporan satu pertandingan</span></div>
</body>
</html>

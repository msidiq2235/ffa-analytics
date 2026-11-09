<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Season Report - {{ $player->name }}</title>
<style>
body{font-family:Helvetica,Arial,sans-serif;font-size:11px;color:#333;margin:0;padding:20px;line-height:1.4;}
.header{text-align:center;margin-bottom:20px;padding-bottom:10px;border-bottom:2px solid #ea580c;}
.header h2{margin:0 0 5px 0;color:#1e293b;font-size:24px;text-transform:uppercase;}
.header p{margin:0;color:#64748b;font-size:13px;}
.section-title{background:#ea580c;color:#fff;padding:5px 8px;font-size:11px;font-weight:bold;margin-top:18px;margin-bottom:8px;border-radius:3px;text-transform:uppercase;}
table{width:100%;border-collapse:collapse;}
.cards td{border:1px solid #e2e8f0;background:#f8fafc;text-align:center;padding:10px;width:16.66%;}
.cards .num{font-size:20px;font-weight:bold;color:#1e293b;display:block;}
.cards .lbl{font-size:8px;color:#64748b;text-transform:uppercase;font-weight:bold;letter-spacing:.5px;}
.charts td{vertical-align:top;border:1px solid #e2e8f0;padding:6px;text-align:center;}
.charts img{width:100%;}
.charts .ct{font-size:10px;font-weight:bold;color:#475569;text-transform:uppercase;margin-bottom:4px;text-align:left;}
.data th,.data td{border:1px solid #e2e8f0;padding:8px;text-align:center;}
.data th{background:#f8fafc;color:#475569;font-weight:bold;text-transform:uppercase;font-size:10px;}
.data td.left-align,.data th.left-align{text-align:left;}
.data tr{page-break-inside:avoid;}
.data tfoot td{font-weight:bold;background:#f8fafc;}
.empty{text-align:center;color:#94a3b8;padding:25px 0;}
.footer{margin-top:30px;text-align:right;font-size:10px;color:#94a3b8;border-top:1px solid #e2e8f0;padding-top:6px;}
</style>
</head>
<body>{{-- tambahkan onload="window.print()" jika memakai cetak browser, bukan DomPDF --}}

<div class="header">
    <h2>SEASON REPORT {{ $seasonLabel }}</h2>
    <p>{{ $player->name }} (#{{ $player->jersey_number ?? $player->number ?? '-' }}) | {{ $player->position ?? '-' }} | {{ $player->club->name ?? '-' }}</p>
</div>

<!-- RINGKASAN -->
<div class="section-title">Ringkasan Statistik</div>
<table class="cards">
    <tr>
        <td><span class="num">{{ $totals->goals }}</span><span class="lbl">Total Gol</span></td>
        <td><span class="num">{{ $totals->assists }}</span><span class="lbl">Total Assist</span></td>
        <td><span class="num">{{ $matches->count() }}</span><span class="lbl">Pertandingan</span></td>
        <td><span class="num">{{ $totals->shots_target }}</span><span class="lbl">Shots On</span></td>
        <td><span class="num">{{ $totals->passes }}</span><span class="lbl">Passes</span></td>
        <td><span class="num">{{ $totals->tackles }}</span><span class="lbl">Tackles</span></td>
    </tr>
</table>

<!-- DIAGRAM -->
@php
    $f = fn($n) => number_format($n, 1, '.', '');

    /* ---------- LINE CHART: gol & assist per pertandingan ---------- */
    $labels = []; $gs = []; $as = [];
    foreach ($matches as $m) {
        $labels[] = \Carbon\Carbon::parse($m->match_date)->format('d/m');
        $gs[]     = (int)($perMatch[$m->id]->total_goals ?? 0);
        $as[]     = (int)($perMatch[$m->id]->total_assists ?? 0);
    }
    $cnt = count($labels);
    $LW = 420; $LH = 240; $lpl = 30; $lpr = 14; $lpt = 32; $lpb = 34;
    $max  = max(1, ...$gs, ...$as);
    $step = max(1, (int)ceil($max / 4));
    $top  = $step * (int)ceil($max / $step);
    $px = fn($i) => $cnt > 1 ? $lpl + $i * ($LW - $lpl - $lpr) / ($cnt - 1) : $lpl + ($LW - $lpl - $lpr) / 2;
    $py = fn($v) => $LH - $lpb - ($v / $top) * ($LH - $lpt - $lpb);

    $line  = '<svg xmlns="http://www.w3.org/2000/svg" width="'.$LW.'" height="'.$LH.'" viewBox="0 0 '.$LW.' '.$LH.'">';
    $line .= '<rect width="'.$LW.'" height="'.$LH.'" fill="#ffffff"/>';
    $line .= '<rect x="'.$lpl.'" y="10" width="10" height="10" fill="#ea580c"/><text x="'.($lpl + 15).'" y="19" font-size="10" fill="#475569" font-family="Helvetica">Gol</text>';
    $line .= '<rect x="'.($lpl + 60).'" y="10" width="10" height="10" fill="#4062D6"/><text x="'.($lpl + 75).'" y="19" font-size="10" fill="#475569" font-family="Helvetica">Assist</text>';
    for ($v = 0; $v <= $top; $v += $step) {
        $line .= '<line x1="'.$lpl.'" y1="'.$f($py($v)).'" x2="'.($LW - $lpr).'" y2="'.$f($py($v)).'" stroke="#e2e8f0"/>';
        $line .= '<text x="'.($lpl - 6).'" y="'.$f($py($v) + 3).'" font-size="9" fill="#94a3b8" text-anchor="end" font-family="Helvetica">'.$v.'</text>';
    }
    foreach ([[$gs, '#ea580c'], [$as, '#4062D6']] as [$data, $color]) {
        $pts = [];
        foreach ($data as $i => $v) { $pts[] = $f($px($i)).','.$f($py($v)); }
        if ($cnt > 1) {
            $line .= '<polyline points="'.implode(' ', $pts).'" fill="none" stroke="'.$color.'" stroke-width="2.5"/>';
        }
        foreach ($data as $i => $v) {
            $line .= '<circle cx="'.$f($px($i)).'" cy="'.$f($py($v)).'" r="3.5" fill="'.$color.'"/>';
        }
    }
    $every = $cnt > 8 ? 2 : 1;
    foreach ($labels as $i => $lb) {
        if ($i % $every === 0) {
            $line .= '<text x="'.$f($px($i)).'" y="'.($LH - $lpb + 15).'" font-size="8" fill="#475569" text-anchor="middle" font-family="Helvetica">'.$lb.'</text>';
        }
    }
    $line .= '<line x1="'.$lpl.'" y1="'.($LH - $lpb).'" x2="'.($LW - $lpr).'" y2="'.($LH - $lpb).'" stroke="#94a3b8"/>';
    $line .= '</svg>';
    $lineImg = 'data:image/svg+xml;base64,' . base64_encode($line);

    /* ---------- RADAR CHART (butuh $radar dari controller, nilai 0-100) ---------- */
    $radarImg = null;
    if (!empty($radar)) {
        $RW = 300; $RH = 240; $cx = 150; $cy = 125; $R = 78;
        $keys = array_keys($radar); $vals = array_values($radar); $N = count($keys);
        $ang  = fn($i) => -M_PI / 2 + 2 * M_PI * $i / $N;
        $rad  = '<svg xmlns="http://www.w3.org/2000/svg" width="'.$RW.'" height="'.$RH.'" viewBox="0 0 '.$RW.' '.$RH.'">';
        $rad .= '<rect width="'.$RW.'" height="'.$RH.'" fill="#ffffff"/>';
        foreach ([0.25, 0.5, 0.75, 1] as $ring) {
            $p = [];
            for ($i = 0; $i < $N; $i++) { $p[] = $f($cx + $R*$ring*cos($ang($i))).','.$f($cy + $R*$ring*sin($ang($i))); }
            $rad .= '<polygon points="'.implode(' ', $p).'" fill="none" stroke="#e2e8f0"/>';
        }
        $p = [];
        for ($i = 0; $i < $N; $i++) {
            $rad .= '<line x1="'.$cx.'" y1="'.$cy.'" x2="'.$f($cx + $R*cos($ang($i))).'" y2="'.$f($cy + $R*sin($ang($i))).'" stroke="#e2e8f0"/>';
            $v = max(0, min(100, (float)$vals[$i])) / 100;
            $p[] = $f($cx + $R*$v*cos($ang($i))).','.$f($cy + $R*$v*sin($ang($i)));
            $anchor = abs(cos($ang($i))) < 0.2 ? 'middle' : (cos($ang($i)) > 0 ? 'start' : 'end');
            $rad .= '<text x="'.$f($cx + ($R + 12)*cos($ang($i))).'" y="'.$f($cy + ($R + 12)*sin($ang($i)) + 3).'" font-size="9" fill="#475569" text-anchor="'.$anchor.'" font-family="Helvetica">'.htmlspecialchars($keys[$i]).'</text>';
        }
        $rad .= '<polygon points="'.implode(' ', $p).'" fill="#f97316" fill-opacity="0.3" stroke="#f97316" stroke-width="2"/>';
        foreach ($p as $pt) { [$ox, $oy] = explode(',', $pt); $rad .= '<circle cx="'.$ox.'" cy="'.$oy.'" r="3" fill="#f97316"/>'; }
        $rad .= '</svg>';
        $radarImg = 'data:image/svg+xml;base64,' . base64_encode($rad);
    }
@endphp

<div class="section-title">Diagram Performa</div>
@if($matches->count() > 0)
<table class="charts">
    <tr>
        <td style="width:58%;">
            <div class="ct">Gol &amp; Assist Per Pertandingan</div>
            <img src="{{ $lineImg }}" alt="Grafik performa">
        </td>
        <td style="width:42%;">
            <div class="ct">Radar Atribut Pemain</div>
            @if($radarImg)
                <img src="{{ $radarImg }}" alt="Radar atribut">
            @else
                <div class="empty">Data atribut belum tersedia.</div>
            @endif
        </td>
    </tr>
</table>
@else
    <div class="empty">Belum ada data statistik.</div>
@endif

<!-- TABEL -->
<div class="section-title">Riwayat Statistik Per Pertandingan</div>
<table class="data">
    <thead>
        <tr>
            <th class="left-align">Pertandingan</th>
            <th>Tanggal</th>
            <th>Goals</th>
            <th>Assists</th>
            <th>Shots On Target</th>
            <th>Succ. Passes</th>
            <th>Tackles</th>
        </tr>
    </thead>
    <tbody>
        @forelse($matches as $m)
        <tr>
            <td class="left-align">{{ $m->title ?? 'Pertandingan' }} (vs {{ $opponentOf($m) }})</td>
            <td>{{ \Carbon\Carbon::parse($m->match_date)->format('d/m/Y') }}</td>
            <td>{{ $perMatch[$m->id]->total_goals ?? 0 }}</td>
            <td>{{ $perMatch[$m->id]->total_assists ?? 0 }}</td>
            <td>{{ $perMatch[$m->id]->total_shots_target ?? 0 }}</td>
            <td>{{ $perMatch[$m->id]->total_passes ?? 0 }}</td>
            <td>{{ $perMatch[$m->id]->total_tackles ?? 0 }}</td>
        </tr>
        @empty
        <tr><td colspan="7" class="empty">Belum ada riwayat pertandingan.</td></tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr>
            <td class="left-align" colspan="2">TOTAL ({{ $matches->count() }} match)</td>
            <td>{{ $totals->goals }}</td>
            <td>{{ $totals->assists }}</td>
            <td>{{ $totals->shots_target }}</td>
            <td>{{ $totals->passes }}</td>
            <td>{{ $totals->tackles }}</td>
        </tr>
    </tfoot>
</table>

<div class="footer">Generated by FFA Analytics Pro Suite - {{ now()->format('Y-m-d H:i:s') }}</div>
</body>
</html>
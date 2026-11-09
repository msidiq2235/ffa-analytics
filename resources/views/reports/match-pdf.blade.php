<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Match Report - {{ $match->title }}</title>
<style>
body{font-family:Helvetica,Arial,sans-serif;font-size:11px;color:#333;margin:0;padding:20px;line-height:1.4;}
.header{text-align:center;margin-bottom:20px;padding-bottom:10px;border-bottom:2px solid #ea580c;}
.header h2{margin:0 0 5px 0;color:#1e293b;font-size:24px;text-transform:uppercase;}
.header p{margin:0;color:#64748b;font-size:13px;}
.section-title{background:#ea580c;color:#fff;padding:5px 8px;font-size:11px;font-weight:bold;margin-top:18px;margin-bottom:8px;border-radius:3px;text-transform:uppercase;}
table{width:100%;border-collapse:collapse;}
.cards td{border:1px solid #e2e8f0;background:#f8fafc;text-align:center;padding:10px;width:25%;}
.cards .num{font-size:22px;font-weight:bold;color:#1e293b;display:block;}
.cards .lbl{font-size:9px;color:#64748b;text-transform:uppercase;font-weight:bold;letter-spacing:.5px;}
.data th,.data td{border:1px solid #e2e8f0;padding:7px 8px;text-align:center;}
.data th{background:#f8fafc;color:#475569;font-weight:bold;text-transform:uppercase;font-size:10px;}
.data td.left-align,.data th.left-align{text-align:left;}
.data tr{page-break-inside:avoid;}
.player-name{font-weight:bold;color:#0f172a;}
.chart{text-align:center;margin:4px 0;}
.chart img{width:100%;}
.footer{margin-top:30px;text-align:right;font-size:10px;color:#94a3b8;border-top:1px solid #e2e8f0;padding-top:6px;}
</style>
</head>
<body>{{-- tambahkan onload="window.print()" jika memakai cetak browser, bukan DomPDF --}}

<div class="header">
    <h2>MATCH REPORT</h2>
    <p>{{ $match->title }} | {{ \Carbon\Carbon::parse($match->match_date)->format('d F Y') }}</p>
</div>

<!-- RINGKASAN TIM -->
<div class="section-title">Ringkasan Tim</div>
<table class="cards">
    <tr>
        <td><span class="num">{{ $teamStats['goals'] ?? 0 }}</span><span class="lbl">Total Goals</span></td>
        <td><span class="num">{{ $teamStats['shots'] ?? 0 }}</span><span class="lbl">Total Shots</span></td>
        <td><span class="num">{{ $teamStats['passes'] ?? 0 }}</span><span class="lbl">Succ. Passes</span></td>
        <td><span class="num">{{ $teamStats['tackles'] ?? 0 }}</span><span class="lbl">Tackles</span></td>
    </tr>
</table>

<!-- DIAGRAM: STATISTIK PER PEMAIN -->
@php
    $f = fn($n) => number_format($n, 1, '.', '');
    $series = [
        ['key' => 'total_goals',        'label' => 'Gol',            'color' => '#ea580c'],
        ['key' => 'total_assists',      'label' => 'Assist',         'color' => '#4062D6'],
        ['key' => 'total_shots_target', 'label' => 'Shot On Target', 'color' => '#34c38f'],
    ];
    $plist = $match->players;
    $W = 700; $H = 270; $pl = 34; $pr = 10; $pt = 34; $pb = 44;
    $n = max($plist->count(), 1);
    $max = 1;
    foreach ($plist as $p) {
        $st = $stats[$p->id] ?? null;
        foreach ($series as $s) { $max = max($max, (int)($st->{$s['key']} ?? 0)); }
    }
    $step = max(1, (int)ceil($max / 4));
    $top  = $step * (int)ceil($max / $step);
    $plotW = $W - $pl - $pr; $plotH = $H - $pt - $pb;
    $groupW = $plotW / $n;
    $barW = min(14, ($groupW - 8) / count($series));

    $svg  = '<svg xmlns="http://www.w3.org/2000/svg" width="'.$W.'" height="'.$H.'" viewBox="0 0 '.$W.' '.$H.'">';
    $svg .= '<rect width="'.$W.'" height="'.$H.'" fill="#ffffff"/>';
    // legend
    $lx = $pl;
    foreach ($series as $s) {
        $svg .= '<rect x="'.$lx.'" y="10" width="10" height="10" fill="'.$s['color'].'"/>';
        $svg .= '<text x="'.($lx + 15).'" y="19" font-size="10" fill="#475569" font-family="Helvetica">'.$s['label'].'</text>';
        $lx += 110;
    }
    // grid + y labels
    for ($v = 0; $v <= $top; $v += $step) {
        $y = $H - $pb - ($v / $top) * $plotH;
        $svg .= '<line x1="'.$pl.'" y1="'.$f($y).'" x2="'.($W - $pr).'" y2="'.$f($y).'" stroke="#e2e8f0" stroke-width="1"/>';
        $svg .= '<text x="'.($pl - 6).'" y="'.$f($y + 3).'" font-size="9" fill="#94a3b8" text-anchor="end" font-family="Helvetica">'.$v.'</text>';
    }
    // bars
    $i = 0;
    foreach ($plist as $p) {
        $st = $stats[$p->id] ?? null;
        $gx = $pl + $i * $groupW + ($groupW - $barW * count($series)) / 2;
        foreach ($series as $k => $s) {
            $val = (int)($st->{$s['key']} ?? 0);
            $bh  = ($val / $top) * $plotH;
            $bx  = $gx + $k * $barW;
            $by  = $H - $pb - $bh;
            if ($val > 0) {
                $svg .= '<rect x="'.$f($bx).'" y="'.$f($by).'" width="'.($barW - 1).'" height="'.$f($bh).'" fill="'.$s['color'].'"/>';
            }
        }
        $label = ($p->jersey_number ? '#'.$p->jersey_number.' ' : '') . mb_substr($p->name, 0, 6);
        $svg .= '<text x="'.$f($pl + $i * $groupW + $groupW / 2).'" y="'.($H - $pb + 14).'" font-size="8" fill="#475569" text-anchor="middle" font-family="Helvetica">'.htmlspecialchars($label).'</text>';
        $i++;
    }
    $svg .= '<line x1="'.$pl.'" y1="'.($H - $pb).'" x2="'.($W - $pr).'" y2="'.($H - $pb).'" stroke="#94a3b8" stroke-width="1"/>';
    $svg .= '</svg>';
    $barChart = 'data:image/svg+xml;base64,' . base64_encode($svg);
@endphp

<div class="section-title">Diagram Statistik Per Pemain</div>
<div class="chart"><img src="{{ $barChart }}" alt="Diagram statistik pemain"></div>

<!-- TABEL STATISTIK -->
<div class="section-title">Data Statistik Pemain</div>
<table class="data">
    <thead>
        <tr>
            <th class="left-align">Player</th>
            <th>No.</th>
            <th>Pos</th>
            <th>Goals</th>
            <th>Assists</th>
            <th>Shots (On/Off)</th>
            <th>Succ. Passes</th>
            <th>Tackles</th>
        </tr>
    </thead>
    <tbody>
        @foreach($match->players as $player)
        <tr>
            <td class="left-align player-name">{{ $player->name }}</td>
            <td>{{ $player->jersey_number ?? '-' }}</td>
            <td>{{ $player->pivot->position }}</td>
            <td>{{ $stats[$player->id]->total_goals ?? 0 }}</td>
            <td>{{ $stats[$player->id]->total_assists ?? 0 }}</td>
            <td>{{ $stats[$player->id]->total_shots_target ?? 0 }} / {{ $stats[$player->id]->total_shots_off ?? 0 }}</td>
            <td>{{ $stats[$player->id]->total_passes ?? 0 }}</td>
            <td>{{ $stats[$player->id]->total_tackles ?? 0 }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<!-- VECTOR SUMMARY (muncul jika controller mengirim $vectors) -->
@if(!empty($vectors) && count($vectors) > 0)
    @php
        $PW = 700; $PH = 440; $m = 20;
        $pitch  = '<svg xmlns="http://www.w3.org/2000/svg" width="'.$PW.'" height="'.$PH.'" viewBox="0 0 '.$PW.' '.$PH.'">';
        $pitch .= '<rect width="'.$PW.'" height="'.$PH.'" fill="#2C6E36"/>';
        $ln = 'fill="none" stroke="#ffffff" stroke-opacity="0.6" stroke-width="2"';
        $pitch .= '<rect x="'.$m.'" y="'.$m.'" width="'.($PW - 2*$m).'" height="'.($PH - 2*$m).'" '.$ln.'/>';
        $pitch .= '<line x1="'.($PW/2).'" y1="'.$m.'" x2="'.($PW/2).'" y2="'.($PH - $m).'" '.$ln.'/>';
        $pitch .= '<circle cx="'.($PW/2).'" cy="'.($PH/2).'" r="50" '.$ln.'/>';
        $pitch .= '<rect x="'.$m.'" y="'.($PH/2 - 100).'" width="110" height="200" '.$ln.'/>';
        $pitch .= '<rect x="'.$m.'" y="'.($PH/2 - 45).'" width="40" height="90" '.$ln.'/>';
        $pitch .= '<rect x="'.($PW - $m - 110).'" y="'.($PH/2 - 100).'" width="110" height="200" '.$ln.'/>';
        $pitch .= '<rect x="'.($PW - $m - 40).'" y="'.($PH/2 - 45).'" width="40" height="90" '.$ln.'/>';
        $colors = ['SHOT' => '#ef4444', 'PASS' => '#22c55e', 'CROSS' => '#a855f7'];
        foreach ($vectors as $v) {
            $v  = (object)$v;
            $c  = $colors[$v->action_type] ?? '#ffffff';
            $x1 = $v->start_x / 100 * $PW; $y1 = $v->start_y / 100 * $PH;
            $x2 = $v->end_x   / 100 * $PW; $y2 = $v->end_y   / 100 * $PH;
            $a  = atan2($y2 - $y1, $x2 - $x1); $h = 9;
            $pitch .= '<line x1="'.$f($x1).'" y1="'.$f($y1).'" x2="'.$f($x2).'" y2="'.$f($y2).'" stroke="'.$c.'" stroke-width="2.5"/>';
            $pitch .= '<line x1="'.$f($x2).'" y1="'.$f($y2).'" x2="'.$f($x2 - $h*cos($a - M_PI/6)).'" y2="'.$f($y2 - $h*sin($a - M_PI/6)).'" stroke="'.$c.'" stroke-width="2.5"/>';
            $pitch .= '<line x1="'.$f($x2).'" y1="'.$f($y2).'" x2="'.$f($x2 - $h*cos($a + M_PI/6)).'" y2="'.$f($y2 - $h*sin($a + M_PI/6)).'" stroke="'.$c.'" stroke-width="2.5"/>';
            $pitch .= '<circle cx="'.$f($x1).'" cy="'.$f($y1).'" r="4" fill="'.$c.'"/>';
        }
        $pitch .= '</svg>';
        $pitchImg = 'data:image/svg+xml;base64,' . base64_encode($pitch);
    @endphp
    <div class="section-title">Vector Summary</div>
    <div class="chart"><img src="{{ $pitchImg }}" alt="Vector summary"></div>
    <p style="font-size:10px;color:#64748b;margin:4px 0 0;">
        <span style="color:#ef4444;">&#9632;</span> SHOT &nbsp;
        <span style="color:#22c55e;">&#9632;</span> PASS &nbsp;
        <span style="color:#a855f7;">&#9632;</span> CROSS
    </p>
@endif

<div class="footer">Generated by FFA Analytics Pro Suite - {{ now()->format('Y-m-d H:i:s') }}</div>
</body>
</html>
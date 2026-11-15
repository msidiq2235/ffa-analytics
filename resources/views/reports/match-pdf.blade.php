<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan Pertandingan - {{ $match->title }}</title>
<style>
@page{margin:24px 28px 30px}
*{box-sizing:border-box}
body{font-family:Helvetica,Arial,sans-serif;font-size:9px;color:#24312b;margin:0;line-height:1.35}
.top-band{height:8px;background:#15803d;margin:-24px -28px 16px}
.brand{width:100%;border-collapse:collapse;margin-bottom:12px}
.brand td{vertical-align:middle}
.brand-mark{width:38px;height:38px;border-radius:19px;background:#15803d;color:#fff;text-align:center;font-size:13px;font-weight:bold;padding-top:11px}
.brand-name{padding-left:9px;font-size:10px;font-weight:bold;color:#176b39;letter-spacing:1px}
.brand-name span{display:block;color:#748078;font-size:8px;font-weight:normal;letter-spacing:.4px;margin-top:3px}
.report-tag{text-align:right;color:#176b39;font-size:9px;font-weight:bold;letter-spacing:.8px}
.report-tag span{display:block;margin-top:4px;color:#748078;font-size:8px;font-weight:normal;letter-spacing:0}
.match-card{width:100%;border-collapse:collapse;background:#f1f8f3;border:1px solid #d5e9db}
.match-card td{padding:10px;vertical-align:middle}
.match-title{font-size:18px;font-weight:bold;color:#173b28}
.match-subtitle{font-size:9px;color:#65736a;margin-top:4px}
.match-meta{width:100%;border-collapse:collapse;margin-top:9px}
.match-meta td{width:25%;padding:5px 8px 0 0;font-size:8px;color:#34443a}
.match-meta strong{display:block;margin-bottom:2px;color:#7a877f;font-size:7px;font-weight:normal;text-transform:uppercase;letter-spacing:.4px}
.section-title{margin:14px 0 7px;background:#15803d;color:#fff;padding:6px 9px;font-size:9px;font-weight:bold;letter-spacing:.5px;text-transform:uppercase}
table{width:100%;border-collapse:collapse}
.cards{border-collapse:separate;border-spacing:5px 0}
.cards td{background:#f1f8f3;border:1px solid #dcebe0;text-align:center;padding:8px 4px;width:25%}
.cards .num{display:block;font-size:18px;font-weight:bold;color:#176b39}
.cards .lbl{display:block;margin-top:2px;font-size:7px;color:#64736a;text-transform:uppercase;font-weight:bold;letter-spacing:.4px}
.chart-panel{border:1px solid #dce7df;padding:8px 8px 3px;background:#fff}
.chart-heading{font-size:9px;font-weight:bold;color:#254631;margin:0 0 4px}
.chart-caption{font-size:7px;color:#7b8780;margin:0}
.chart{text-align:center;margin:0}
.chart img{width:100%;height:auto}
.player-table{margin-top:5px}
.player-table th,.player-table td{border-bottom:1px solid #e4ebe6;padding:6px 5px;text-align:center;font-size:8px}
.player-table th{background:#e8f4eb;color:#176b39;font-size:7px;font-weight:bold;text-transform:uppercase;letter-spacing:.3px}
.player-table tbody tr:nth-child(even){background:#f8faf8}
.player-table td.left,.player-table th.left{text-align:left}
.player-table td.name{font-weight:bold;color:#263c2d}
.player-table td.value{font-weight:bold;color:#35443a}
.empty{text-align:center;padding:24px;color:#7b8780;background:#f8faf8;border:1px dashed #dce7df}
.vector-section{page-break-before:always}
.vector-panel{border:1px solid #dce7df;padding:9px;background:#fff}
.vector-heading{font-size:9px;font-weight:bold;color:#254631;margin:0 0 7px}
.pitch-img{width:100%;height:auto;display:block}
.legend{font-size:7px;color:#5d6b62;margin-top:7px}
.legend span{display:inline-block;margin-right:13px}
.legend i{display:inline-block;width:7px;height:7px;margin-right:4px}
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
        <td class="brand-name">FFA ANALYTICS<span>TEAM MATCH REPORT</span></td>
        <td class="report-tag">MATCH REPORT<span>FFA Analytics Performance Report</span></td>
    </tr>
</table>

<table class="match-card">
    <tr>
        <td>
            <div class="match-title">{{ $match->title ?? 'Laporan Pertandingan' }}</div>
            <div class="match-subtitle">Laporan statistik pemain dan aksi pertandingan</div>
            <table class="match-meta">
                <tr>
                    <td><strong>Tanggal</strong>{{ \Carbon\Carbon::parse($match->match_date)->translatedFormat('d F Y, H:i') }}</td>
                    <td><strong>Lawan</strong>{{ $match->opponent ?? '-' }}</td>
                    <td><strong>Tempat</strong>{{ $match->venue ?? '-' }}</td>
                    <td><strong>Formasi</strong>{{ $match->formation ?? '-' }}</td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<div class="section-title">Ringkasan Tim</div>
<table class="cards">
    <tr>
        <td><span class="num">{{ $teamStats['goals'] ?? 0 }}</span><span class="lbl">Total gol</span></td>
        <td><span class="num">{{ $teamStats['shots'] ?? 0 }}</span><span class="lbl">Total tembakan</span></td>
        <td><span class="num">{{ $teamStats['passes'] ?? 0 }}</span><span class="lbl">Umpan berhasil</span></td>
        <td><span class="num">{{ $teamStats['tackles'] ?? 0 }}</span><span class="lbl">Tackle</span></td>
    </tr>
</table>

@php
    $f = fn ($number) => number_format((float) $number, 1, '.', '');
    $series = [
        ['key' => 'total_goals', 'label' => 'Gol', 'color' => '#15803d'],
        ['key' => 'total_assists', 'label' => 'Assist', 'color' => '#52715e'],
        ['key' => 'total_shots_target', 'label' => 'Tembakan tepat sasaran', 'color' => '#a8b7ac'],
    ];
    $players = $match->players;
    $width = 700; $height = 250; $left = 34; $right = 10; $topPad = 34; $bottom = 44;
    $playerCount = max($players->count(), 1);
    $maxValue = 1;
    foreach ($players as $player) {
        $playerStats = $stats[$player->id] ?? null;
        foreach ($series as $item) {
            $maxValue = max($maxValue, (int) ($playerStats->{$item['key']} ?? 0));
        }
    }
    $step = max(1, (int) ceil($maxValue / 4));
    $scaleMax = $step * (int) ceil($maxValue / $step);
    $plotWidth = $width - $left - $right;
    $plotHeight = $height - $topPad - $bottom;
    $groupWidth = $plotWidth / $playerCount;
    $barWidth = min(14, ($groupWidth - 8) / count($series));

    $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="'.$width.'" height="'.$height.'" viewBox="0 0 '.$width.' '.$height.'">';
    $svg .= '<rect width="'.$width.'" height="'.$height.'" fill="#ffffff"/>';
    $legendX = $left;
    foreach ($series as $item) {
        $svg .= '<rect x="'.$legendX.'" y="10" width="9" height="9" fill="'.$item['color'].'"/>';
        $svg .= '<text x="'.($legendX + 13).'" y="18" font-size="9" fill="#526158" font-family="Helvetica">'.$item['label'].'</text>';
        $legendX += 155;
    }
    for ($value = 0; $value <= $scaleMax; $value += $step) {
        $y = $height - $bottom - ($value / $scaleMax) * $plotHeight;
        $svg .= '<line x1="'.$left.'" y1="'.$f($y).'" x2="'.($width - $right).'" y2="'.$f($y).'" stroke="#e5ece7" stroke-width="1"/>';
        $svg .= '<text x="'.($left - 6).'" y="'.$f($y + 3).'" font-size="8" fill="#8a968e" text-anchor="end" font-family="Helvetica">'.$value.'</text>';
    }
    foreach ($players as $index => $player) {
        $playerStats = $stats[$player->id] ?? null;
        $groupX = $left + $index * $groupWidth + ($groupWidth - $barWidth * count($series)) / 2;
        foreach ($series as $seriesIndex => $item) {
            $value = (int) ($playerStats->{$item['key']} ?? 0);
            $barHeight = ($value / $scaleMax) * $plotHeight;
            if ($value > 0) {
                $barX = $groupX + $seriesIndex * $barWidth;
                $barY = $height - $bottom - $barHeight;
                $svg .= '<rect x="'.$f($barX).'" y="'.$f($barY).'" width="'.max(1, $barWidth - 1).'" height="'.$f($barHeight).'" fill="'.$item['color'].'"/>';
            }
        }
        $label = ($player->jersey_number ? '#'.$player->jersey_number.' ' : '').mb_substr($player->name, 0, 7);
        $svg .= '<text x="'.$f($left + $index * $groupWidth + $groupWidth / 2).'" y="'.($height - $bottom + 14).'" font-size="7" fill="#526158" text-anchor="middle" font-family="Helvetica">'.htmlspecialchars($label, ENT_QUOTES, 'UTF-8').'</text>';
    }
    $svg .= '<line x1="'.$left.'" y1="'.($height - $bottom).'" x2="'.($width - $right).'" y2="'.($height - $bottom).'" stroke="#aab7ad" stroke-width="1"/>';
    $svg .= '</svg>';
    $barChart = 'data:image/svg+xml;base64,'.base64_encode($svg);
@endphp

<div class="section-title">Perbandingan Statistik Pemain</div>
<div class="chart-panel">
    <p class="chart-heading">Gol, assist, dan tembakan tepat sasaran</p>
    <p class="chart-caption">Perbandingan statistik setiap pemain yang terdaftar pada pertandingan ini.</p>
    @if($players->isNotEmpty())
        <div class="chart"><img src="{{ $barChart }}" alt="Diagram statistik pemain"></div>
    @else
        <div class="empty">Belum ada pemain yang terdaftar pada pertandingan ini.</div>
    @endif
</div>

<div class="section-title">Data Statistik Pemain</div>
<table class="player-table">
    <thead>
        <tr>
            <th class="left">Pemain</th>
            <th>No.</th>
            <th>Posisi</th>
            <th>Gol</th>
            <th>Assist</th>
            <th>Tembakan<br>(tepat / tidak)</th>
            <th>Umpan<br>berhasil</th>
            <th>Tackle</th>
        </tr>
    </thead>
    <tbody>
        @forelse($players as $player)
            <tr>
                <td class="left name">{{ $player->name }}</td>
                <td>{{ $player->jersey_number ?? '-' }}</td>
                <td>{{ $player->pivot->position ?? '-' }}</td>
                <td class="value">{{ $stats[$player->id]->total_goals ?? 0 }}</td>
                <td class="value">{{ $stats[$player->id]->total_assists ?? 0 }}</td>
                <td>{{ $stats[$player->id]->total_shots_target ?? 0 }} / {{ $stats[$player->id]->total_shots_off ?? 0 }}</td>
                <td>{{ $stats[$player->id]->total_passes ?? 0 }}</td>
                <td>{{ $stats[$player->id]->total_tackles ?? 0 }}</td>
            </tr>
        @empty
            <tr><td colspan="8" class="empty">Belum ada pemain yang terdaftar pada pertandingan ini.</td></tr>
        @endforelse
    </tbody>
</table>

@if(!empty($vectors) && count($vectors) > 0)
    @php
        $pitchWidth = 700; $pitchHeight = 440; $margin = 20;
        $pitch = '<svg xmlns="http://www.w3.org/2000/svg" width="'.$pitchWidth.'" height="'.$pitchHeight.'" viewBox="0 0 '.$pitchWidth.' '.$pitchHeight.'">';
        $pitch .= '<rect width="'.$pitchWidth.'" height="'.$pitchHeight.'" fill="#edf5ef"/>';
        $lineStyle = 'fill="none" stroke="#8eaa96" stroke-width="2"';
        $pitch .= '<rect x="'.$margin.'" y="'.$margin.'" width="'.($pitchWidth - 2*$margin).'" height="'.($pitchHeight - 2*$margin).'" '.$lineStyle.'/>';
        $pitch .= '<line x1="'.($pitchWidth/2).'" y1="'.$margin.'" x2="'.($pitchWidth/2).'" y2="'.($pitchHeight - $margin).'" '.$lineStyle.'/>';
        $pitch .= '<circle cx="'.($pitchWidth/2).'" cy="'.($pitchHeight/2).'" r="50" '.$lineStyle.'/>';
        $pitch .= '<rect x="'.$margin.'" y="'.($pitchHeight/2 - 100).'" width="110" height="200" '.$lineStyle.'/>';
        $pitch .= '<rect x="'.$margin.'" y="'.($pitchHeight/2 - 45).'" width="40" height="90" '.$lineStyle.'/>';
        $pitch .= '<rect x="'.($pitchWidth - $margin - 110).'" y="'.($pitchHeight/2 - 100).'" width="110" height="200" '.$lineStyle.'/>';
        $pitch .= '<rect x="'.($pitchWidth - $margin - 40).'" y="'.($pitchHeight/2 - 45).'" width="40" height="90" '.$lineStyle.'/>';
        $vectorLegend = collect($vectors)
            ->groupBy(fn ($vector) => $vector->display_label)
            ->map(fn ($group) => [
                'color' => $group->first()->display_color,
                'count' => $group->count(),
            ]);
        foreach ($vectors as $vector) {
            $vector = (object) $vector;
            $color = $vector->display_color;
            $startX = max(0, min(100, (float) $vector->start_x)) / 100 * $pitchWidth;
            $startY = max(0, min(100, (float) $vector->start_y)) / 100 * $pitchHeight;
            $endX = max(0, min(100, (float) $vector->end_x)) / 100 * $pitchWidth;
            $endY = max(0, min(100, (float) $vector->end_y)) / 100 * $pitchHeight;
            $angle = atan2($endY - $startY, $endX - $startX);
            $arrowLength = 9;
            $pitch .= '<line x1="'.$f($startX).'" y1="'.$f($startY).'" x2="'.$f($endX).'" y2="'.$f($endY).'" stroke="'.$color.'" stroke-width="2.5"/>';
            $pitch .= '<line x1="'.$f($endX).'" y1="'.$f($endY).'" x2="'.$f($endX - $arrowLength*cos($angle - M_PI/6)).'" y2="'.$f($endY - $arrowLength*sin($angle - M_PI/6)).'" stroke="'.$color.'" stroke-width="2.5"/>';
            $pitch .= '<line x1="'.$f($endX).'" y1="'.$f($endY).'" x2="'.$f($endX - $arrowLength*cos($angle + M_PI/6)).'" y2="'.$f($endY - $arrowLength*sin($angle + M_PI/6)).'" stroke="'.$color.'" stroke-width="2.5"/>';
            $pitch .= '<circle cx="'.$f($startX).'" cy="'.$f($startY).'" r="4" fill="'.$color.'"/>';
        }
        $pitch .= '</svg>';
        $pitchImg = 'data:image/svg+xml;base64,'.base64_encode($pitch);
    @endphp
    <div class="vector-section">
        <div class="section-title">Peta Vektor Pertandingan</div>
        <div class="vector-panel">
            <p class="vector-heading">Arah aksi yang dicatat selama pertandingan</p>
            <img class="pitch-img" src="{{ $pitchImg }}" alt="Peta vektor pertandingan">
            <div class="legend">
                @foreach($vectorLegend as $label => $item)
                    <span><i style="background:{{ $item['color'] }}"></i>{{ $label }} ({{ $item['count'] }})</span>
                @endforeach
            </div>
        </div>
    </div>
@endif

<div class="footer">FFA Analytics — dibuat {{ now()->format('d/m/Y H:i') }}<span class="right">Laporan pertandingan</span></div>
</body>
</html>

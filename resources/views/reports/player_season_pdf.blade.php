<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>{{ $reportTitle }} - {{ $player->name }}</title>
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
.profile-details{width:100%;border-collapse:collapse;margin-top:7px}
.profile-details td{padding:2px 6px 2px 0;font-size:8px;color:#34443a}
.profile-details strong{color:#738078;font-weight:normal}
.title{margin:14px 0 7px;background:#159447;color:#fff;padding:6px 9px;font-size:9px;font-weight:bold;letter-spacing:.5px;text-transform:uppercase}
.cards{width:100%;border-collapse:separate;border-spacing:5px 0}
.cards td{background:#f1f8f3;border:1px solid #dcebe0;text-align:center;padding:8px 4px;width:16.66%}
.cards .num{display:block;font-size:18px;font-weight:bold;color:#16783d}
.cards .lbl{display:block;font-size:7px;color:#64736a;text-transform:uppercase;font-weight:bold;letter-spacing:.3px;margin-top:2px}
.two-col{width:100%;border-collapse:separate;border-spacing:7px 0}
.two-col>tbody>tr>td{width:50%;vertical-align:top;padding:0}
.panel{border:1px solid #dce7df;padding:8px;background:#fff}
.panel-title{font-size:9px;font-weight:bold;color:#254631;margin-bottom:5px}
.panel-subtitle{font-size:8px;color:#748078;margin-bottom:6px}
.chart-img{width:100%;height:220px}
.pitch{width:100%;height:220px;display:block;background:#eff8f0}
.pitch-note{font-size:7px;color:#748078;margin-top:4px}
.legend{font-size:7px;color:#59675e;padding-top:5px}
.legend span{display:inline-block;margin-right:10px}
.dot{display:inline-block;width:6px;height:6px;border-radius:3px;margin-right:3px}
.empty{height:218px;text-align:center;color:#8a978f;padding-top:95px;background:#f7faf8}
.report-page{page-break-before:always}
.page-heading{border-bottom:2px solid #159447;padding-bottom:7px;margin-bottom:10px}
.page-heading h2{margin:0;color:#173b28;font-size:16px}
.page-heading p{margin:3px 0 0;color:#748078;font-size:8px}
.stat-columns{width:100%;border-collapse:separate;border-spacing:6px 0}
.stat-columns td{width:50%;vertical-align:top;padding:0}
.stat-group{width:100%;border-collapse:collapse;margin:0 0 9px;page-break-inside:avoid}
.stat-group th{background:#e8f4eb;color:#176b39;text-align:left;padding:5px 7px;font-size:8px;text-transform:uppercase}
.stat-group td{border-bottom:1px solid #e8eee9;padding:4px 7px;font-size:8px}
.stat-group td:last-child{text-align:right;font-weight:bold;color:#263c2d;width:55px}
.history{width:100%;border-collapse:collapse;margin-top:5px}
.history th,.history td{border:1px solid #dfe8e1;padding:5px;text-align:center;font-size:8px}
.history th{background:#e8f4eb;color:#176b39;text-transform:uppercase;font-size:7px}
.history td:first-child,.history th:first-child{text-align:left}
.footer{position:fixed;bottom:-17px;left:0;right:0;border-top:1px solid #dce7df;padding-top:5px;color:#849087;font-size:7px}
.footer .right{float:right}
.muted{color:#758279}
table{page-break-inside:auto}
tr{page-break-inside:avoid}
</style>
</head>
<body>
<div class="top-band"></div>
<table class="brand">
    <tr>
        <td style="width:38px"><div class="brand-mark">FFA</div></td>
        <td class="brand-name">FFA ANALYTICS<span>PLAYER PERFORMANCE REPORT</span></td>
        <td class="report-tag">{{ $reportTitle }}<span>Musim {{ $seasonLabel }}</span></td>
    </tr>
</table>

<table class="player-card">
    <tr>
        <td class="player-number">#{{ $player->jersey_number ?? $player->number ?? '-' }}</td>
        <td>
            <div class="player-name">{{ $player->popular_name ?: $player->name }}</div>
            <div class="player-meta">{{ $player->name }} &nbsp;|&nbsp; {{ $player->position ?? 'Posisi tidak tersedia' }} &nbsp;|&nbsp; {{ $player->club->name ?? 'Tim tidak tersedia' }}</div>
            <table class="profile-details">
                <tr>
                    <td><strong>Jenis kelamin</strong> {{ $player->gender ?? '-' }}</td>
                    <td><strong>Kaki dominan</strong> {{ $player->dominant_foot ?? '-' }}</td>
                    <td><strong>Tinggi</strong> {{ $player->height_cm ? $player->height_cm . ' cm' : '-' }}</td>
                    <td><strong>Berat</strong> {{ $player->weight_kg ? $player->weight_kg . ' kg' : '-' }}</td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<div class="title">Ringkasan Performa</div>
<table class="cards">
    <tr>
        <td><span class="num">{{ $matches->count() }}</span><span class="lbl">Pertandingan</span></td>
        <td><span class="num">{{ $totals->goals }}</span><span class="lbl">Gol</span></td>
        <td><span class="num">{{ $totals->assists }}</span><span class="lbl">Assist</span></td>
        <td><span class="num">{{ $totals->shots_target }}</span><span class="lbl">Tembakan Tepat</span></td>
        <td><span class="num">{{ $totals->passes }}</span><span class="lbl">Umpan Berhasil</span></td>
        <td><span class="num">{{ $totals->tackles }}</span><span class="lbl">Tackle</span></td>
    </tr>
</table>

@php
    $f = fn ($number) => number_format((float) $number, 2, '.', '');
    $radarImg = null;
    if (!empty($radar)) {
        $width = 300; $height = 220; $cx = 150; $cy = 112; $radius = 72;
        $keys = array_keys($radar); $values = array_values($radar); $count = count($keys);
        $angle = fn ($index) => -M_PI / 2 + 2 * M_PI * $index / max($count, 1);
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="'.$width.'" height="'.$height.'" viewBox="0 0 '.$width.' '.$height.'">';
        $svg .= '<rect width="'.$width.'" height="'.$height.'" fill="#ffffff"/>';
        foreach ([0.25, 0.5, 0.75, 1] as $ring) {
            $points = [];
            for ($index = 0; $index < $count; $index++) {
                $points[] = $f($cx + $radius * $ring * cos($angle($index))).','.$f($cy + $radius * $ring * sin($angle($index)));
            }
            $svg .= '<polygon points="'.implode(' ', $points).'" fill="none" stroke="#dce8df"/>';
        }
        $points = [];
        for ($index = 0; $index < $count; $index++) {
            $x = $cx + $radius * cos($angle($index));
            $y = $cy + $radius * sin($angle($index));
            $svg .= '<line x1="'.$cx.'" y1="'.$cy.'" x2="'.$f($x).'" y2="'.$f($y).'" stroke="#dce8df"/>';
            $value = max(0, min(100, (float) $values[$index])) / 100;
            $points[] = $f($cx + $radius * $value * cos($angle($index))).','.$f($cy + $radius * $value * sin($angle($index)));
            $anchor = abs(cos($angle($index))) < 0.2 ? 'middle' : (cos($angle($index)) > 0 ? 'start' : 'end');
            $svg .= '<text x="'.$f($cx + ($radius + 12) * cos($angle($index))).'" y="'.$f($cy + ($radius + 12) * sin($angle($index)) + 3).'" font-size="8" fill="#526158" text-anchor="'.$anchor.'" font-family="Helvetica">'.htmlspecialchars($keys[$index], ENT_QUOTES, 'UTF-8').'</text>';
        }
        $svg .= '<polygon points="'.implode(' ', $points).'" fill="#159447" fill-opacity="0.22" stroke="#159447" stroke-width="2"/>';
        foreach ($points as $point) {
            [$x, $y] = explode(',', $point);
            $svg .= '<circle cx="'.$x.'" cy="'.$y.'" r="3" fill="#159447"/>';
        }
        $svg .= '</svg>';
        $radarImg = 'data:image/svg+xml;base64,'.base64_encode($svg);
    }
@endphp

<div class="title">Profil Performa Musim</div>
<div class="panel">
    <div class="panel-title">Ringkasan statistik seluruh pertandingan</div>
    <div class="panel-subtitle">Grafik merangkum performa pemain selama musim {{ $seasonLabel }}.</div>
    @if($radarImg)
        <img class="chart-img" src="{{ $radarImg }}" alt="Profil performa musim">
    @else
        <div class="empty">Belum ada data statistik.</div>
    @endif
</div>

<div class="report-page">
    <div class="page-heading">
        <h2>Rincian Statistik Pemain</h2>
        <p>{{ $player->popular_name ?: $player->name }} &nbsp;|&nbsp; {{ $reportTitle }} — Musim {{ $seasonLabel }}</p>
    </div>
    <table class="cards">
        <tr>
            <td><span class="num">{{ $matches->count() }}</span><span class="lbl">Pertandingan</span></td>
            <td><span class="num">{{ $totals->goals }}</span><span class="lbl">Gol</span></td>
            <td><span class="num">{{ $totals->assists }}</span><span class="lbl">Assist</span></td>
            <td><span class="num">{{ $totals->shots_target }}</span><span class="lbl">Tembakan Tepat</span></td>
            <td><span class="num">{{ $totals->passes }}</span><span class="lbl">Umpan Berhasil</span></td>
            <td><span class="num">{{ $totals->tackles }}</span><span class="lbl">Tackle</span></td>
        </tr>
    </table>

    @php($groupRows = array_chunk($statisticGroups, 2, true))
    <table class="stat-columns">
        @foreach($groupRows as $row)
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

    @if($matches->count() > 1)
        <div class="title">Ringkasan Per Pertandingan</div>
        <table class="history">
            <thead>
                <tr><th>Pertandingan</th><th>Tanggal</th><th>Gol</th><th>Assist</th><th>Tembakan tepat</th><th>Umpan</th><th>Tackle</th></tr>
            </thead>
            <tbody>
                @foreach($matches as $match)
                    <tr>
                        <td>{{ $match->title ?? 'Pertandingan' }} (vs {{ $opponentOf($match) }})</td>
                        <td>{{ \Carbon\Carbon::parse($match->match_date)->format('d/m/Y') }}</td>
                        <td>{{ $perMatch[$match->id]->goal ?? 0 }}</td>
                        <td>{{ $perMatch[$match->id]->assist ?? 0 }}</td>
                        <td>{{ $perMatch[$match->id]->shooting_on_target ?? 0 }}</td>
                        <td>{{ $perMatch[$match->id]->successful_passes ?? 0 }}</td>
                        <td>{{ $perMatch[$match->id]->tackles ?? 0 }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

<div class="footer">FFA Analytics — dibuat {{ now()->format('d/m/Y H:i') }}<span class="right">Laporan performa pemain</span></div>
</body>
</html>

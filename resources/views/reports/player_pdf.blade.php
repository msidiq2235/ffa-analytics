<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Lengkap Pemain - {{ $player->name }}</title>
    <style>
        body { font-family: 'Helvetica', Arial, sans-serif; font-size: 11px; color: #333; margin: 0; padding: 15px; line-height: 1.4; }
        .header { text-align: center; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #4062D6; }
        .header h2 { margin: 0 0 3px 0; color: #1e293b; font-size: 20px; text-transform: uppercase; }
        .header p { margin: 0; color: #64748b; font-size: 12px; }
        .section-title { background: #4062D6; color: #fff; padding: 5px 8px; font-size: 11px; font-weight: bold; margin-top: 15px; margin-bottom: 8px; border-radius: 3px; text-transform: uppercase; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
        th, td { border: 1px solid #cbd5e1; padding: 6px 8px; text-align: left; font-size: 11px; }
        th { background-color: #f1f5f9; color: #334155; font-weight: bold; }
        .text-center { text-align: center; }
        .stats-grid { width: 100%; margin-bottom: 10px; border-collapse: collapse; }
        .stats-grid td { border: 1px solid #cbd5e1; background: #f8fafc; text-align: center; padding: 8px; width: 33%; }
        .stats-num { font-size: 16px; font-weight: bold; color: #1e293b; display: block; }
        .stats-label { font-size: 10px; color: #64748b; text-transform: uppercase; font-weight: bold; }
        .chart-grid { width: 100%; border-collapse: separate; border-spacing: 8px 0; }
        .chart-grid td { width: 50%; vertical-align: top; border: 1px solid #cbd5e1; padding: 8px; }
        .chart-title { margin: 0 0 5px; color: #334155; font-size: 10px; font-weight: bold; }
        .chart-img { display: block; width: 100%; height: auto; }
        .footer { margin-top: 30px; text-align: center; font-size: 9px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 8px; }
    </style>
</head>
<body onload="window.print()">

    <div class="header">
        <h2>FFA ANALYTICS - PRO SUITE</h2>
        <p>Laporan Profil & Statistik Performa Pemain</p>
    </div>

    <!-- INFORMASI UTAMA & KLUB -->
    <div class="section-title">Informasi Utama & Klub</div>
    <table>
        <tr>
            <th style="width: 25%;">Nama Lengkap</th>
            <td>{{ $player->name }}</td>
            <th style="width: 20%;">Posisi</th>
            <td>{{ $player->position ?? '-' }}</td>
        </tr>
        <tr>
            <th>Nama Panggilan</th>
            <td>{{ $player->popular_name ?? '-' }}</td>
            <th>No. Punggung</th>
            <td>#{{ $player->jersey_number ?? '-' }}</td>
        </tr>
        <tr>
            <th>Tim / Klub Utama</th>
            <td>{{ optional($player->club)->name ?? '-' }}</td>
            <th>Status</th>
            <td>{{ $player->status ?? 'Aktif' }}</td>
        </tr>
    </table>

    <!-- BIODATA & FISIK -->
    <div class="section-title">Biodata & Fisik Pemain</div>
    <table>
        <tr>
            <th style="width: 25%;">Tempat, Tgl Lahir</th>
            <td>{{ $player->birth_place_date ?? '-' }}</td>
            <th style="width: 20%;">Tinggi / Berat</th>
            <td>{{ $player->height_cm ? $player->height_cm . ' cm' : '-' }} / {{ $player->weight_kg ? $player->weight_kg . ' kg' : '-' }}</td>
        </tr>
        <tr>
            <th>NIK</th>
            <td>{{ $player->nik ?? '-' }}</td>
            <th>Kaki Dominan</th>
            <td>{{ $player->dominant_foot ?? '-' }}</td>
        </tr>
        <tr>
            <th>Kontak (Telp/Email)</th>
            <td>{{ $player->phone ?? '-' }} / {{ $player->email ?? '-' }}</td>
            <th>Alamat</th>
            <td>{{ $player->address ?? '-' }} {{ $player->province ? ', ' . $player->province : '' }}</td>
        </tr>
    </table>

    <!-- RINGKASAN STATISTIK KESELURUHAN -->
    <div class="section-title">Ringkasan Statistik Performa</div>
    <table class="stats-grid">
        <tr>
            <td>
                <span class="stats-num">{{ $totals->goals }}</span>
                <span class="stats-label">Total Gol</span>
            </td>
            <td>
                <span class="stats-num">{{ $totals->assists }}</span>
                <span class="stats-label">Total Assist</span>
            </td>
            <td>
                <span class="stats-num">{{ $totals->matches }}</span>
                <span class="stats-label">Total Pertandingan</span>
            </td>
        </tr>
    </table>

    @php
        $chartImage = null;
        $radarImage = null;
        if ($matches->isNotEmpty()) {
            $format = fn ($number) => number_format((float) $number, 2, '.', '');
            $width = 650; $height = 260; $left = 36; $right = 20; $top = 20; $bottom = 42;
            $maxValue = max(1, (int) max($chartValues));
            $step = max(1, (int) ceil($maxValue / 4));
            $scaleMax = $step * (int) ceil($maxValue / $step);
            $plotHeight = $height - $top - $bottom;
            $plotWidth = $width - $left - $right;
            $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="'.$width.'" height="'.$height.'" viewBox="0 0 '.$width.' '.$height.'">';
            $svg .= '<rect width="'.$width.'" height="'.$height.'" fill="#ffffff"/>';
            for ($value = 0; $value <= $scaleMax; $value += $step) {
                $y = $height - $bottom - ($value / $scaleMax) * $plotHeight;
                $svg .= '<line x1="'.$left.'" y1="'.$format($y).'" x2="'.($width - $right).'" y2="'.$format($y).'" stroke="#e2e8f0"/>';
                $svg .= '<text x="'.($left - 7).'" y="'.$format($y + 3).'" font-size="9" fill="#94a3b8" text-anchor="end" font-family="Helvetica">'.$value.'</text>';
            }
            $points = [];
            foreach ($chartValues as $index => $value) {
                $x = $left + ($plotWidth * $index / max(count($chartValues) - 1, 1));
                $y = $height - $bottom - ($value / $scaleMax) * $plotHeight;
                $points[] = $format($x).','.$format($y);
                $label = htmlspecialchars($chartLabels[$index], ENT_QUOTES, 'UTF-8');
                $svg .= '<circle cx="'.$format($x).'" cy="'.$format($y).'" r="4" fill="#2563eb"/>';
                $svg .= '<text x="'.$format($x).'" y="'.$format($y - 8).'" font-size="8" fill="#334155" text-anchor="middle" font-family="Helvetica">'.$value.'</text>';
                $svg .= '<text x="'.$format($x).'" y="'.($height - 16).'" font-size="8" fill="#64748b" text-anchor="middle" font-family="Helvetica">'.$label.'</text>';
            }
            $svg .= '<polyline points="'.implode(' ', $points).'" fill="none" stroke="#2563eb" stroke-width="3"/>';
            $svg .= '</svg>';
            $chartImage = 'data:image/svg+xml;base64,'.base64_encode($svg);

            $radarWidth = 340; $radarHeight = 260; $cx = 170; $cy = 125; $radius = 78;
            $keys = array_keys($radar); $values = array_values($radar); $count = count($keys);
            $angle = fn ($index) => -M_PI / 2 + 2 * M_PI * $index / max($count, 1);
            $radarSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="'.$radarWidth.'" height="'.$radarHeight.'" viewBox="0 0 '.$radarWidth.' '.$radarHeight.'">';
            $radarSvg .= '<rect width="'.$radarWidth.'" height="'.$radarHeight.'" fill="#ffffff"/>';
            foreach ([0.25, 0.5, 0.75, 1] as $ring) {
                $ringPoints = [];
                for ($index = 0; $index < $count; $index++) {
                    $ringPoints[] = $format($cx + $radius * $ring * cos($angle($index))).','.$format($cy + $radius * $ring * sin($angle($index)));
                }
                $radarSvg .= '<polygon points="'.implode(' ', $ringPoints).'" fill="none" stroke="#e2e8f0"/>';
            }
            $radarPoints = [];
            for ($index = 0; $index < $count; $index++) {
                $x = $cx + $radius * cos($angle($index));
                $y = $cy + $radius * sin($angle($index));
                $radarSvg .= '<line x1="'.$cx.'" y1="'.$cy.'" x2="'.$format($x).'" y2="'.$format($y).'" stroke="#e2e8f0"/>';
                $value = max(0, min(100, (float) $values[$index])) / 100;
                $radarPoints[] = $format($cx + $radius * $value * cos($angle($index))).','.$format($cy + $radius * $value * sin($angle($index)));
                $anchor = abs(cos($angle($index))) < 0.2 ? 'middle' : (cos($angle($index)) > 0 ? 'start' : 'end');
                $label = htmlspecialchars($keys[$index], ENT_QUOTES, 'UTF-8');
                $radarSvg .= '<text x="'.$format($cx + ($radius + 12) * cos($angle($index))).'" y="'.$format($cy + ($radius + 12) * sin($angle($index)) + 3).'" font-size="8" fill="#64748b" text-anchor="'.$anchor.'" font-family="Helvetica">'.$label.'</text>';
            }
            $radarSvg .= '<polygon points="'.implode(' ', $radarPoints).'" fill="#2563eb" fill-opacity="0.16" stroke="#2563eb" stroke-width="2"/>';
            $radarSvg .= '</svg>';
            $radarImage = 'data:image/svg+xml;base64,'.base64_encode($radarSvg);
        }
    @endphp

    <div class="section-title">Grafik Performa Pemain</div>
    @if($chartImage && $radarImage)
        <table class="chart-grid">
            <tr>
                <td>
                    <p class="chart-title">Kontribusi gol + assist (6 bulan)</p>
                    <img class="chart-img" src="{{ $chartImage }}" alt="Grafik kontribusi enam bulan">
                </td>
                <td>
                    <p class="chart-title">Profil statistik</p>
                    <img class="chart-img" src="{{ $radarImage }}" alt="Radar profil statistik">
                </td>
            </tr>
        </table>
    @else
        <p>Belum ada data statistik untuk menampilkan grafik.</p>
    @endif

    <!-- RIWAYAT PERTANDINGAN -->
    <div class="section-title">Riwayat Statistik Per Pertandingan</div>
    <table>
        <thead>
            <tr>
                <th>Pertandingan / Lawan</th>
                <th>Tanggal</th>
                <th class="text-center">Gol</th>
                <th class="text-center">Assist</th>
                <th class="text-center">Shots On</th>
                <th class="text-center">Passes</th>
                <th class="text-center">Tackles</th>
            </tr>
        </thead>
        <tbody>
            @forelse($matches as $match)
                @php $st = $perMatch[$match->id] ?? null; @endphp
                <tr>
                    <td><b>{{ $match->title ?? 'Pertandingan' }}</b> vs {{ $opponentOf($match) }}</td>
                    <td>{{ \Carbon\Carbon::parse($match->match_date)->format('d/m/Y') }}</td>
                    <td class="text-center">{{ $st->total_goals ?? 0 }}</td>
                    <td class="text-center">{{ $st->total_assists ?? 0 }}</td>
                    <td class="text-center">{{ $st->total_shots_target ?? 0 }}</td>
                    <td class="text-center">{{ $st->total_passes ?? 0 }}</td>
                    <td class="text-center">{{ $st->total_tackles ?? 0 }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center" style="color: #64748b; padding: 15px;">Belum ada riwayat pertandingan untuk pemain ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Dokumen ini digenerate secara otomatis oleh sistem FFA Analytics pada {{ now()->format('d-m-Y H:i:s') }}</p>
    </div>

</body>
</html>
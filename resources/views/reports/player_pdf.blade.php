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
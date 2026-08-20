<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Menu Terlaris Restoran</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #1f2937;
            margin: 0;
            padding: 0;
            .meta-list,
            .data-table,
            .signature {
            width: 100%;
            padding: 20px 24px 28px;
            box-sizing: border-box;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #1f2937;
            padding-bottom: 12px;
            margin-bottom: 16px;
        }

            .meta-list {
                width: 100%;
                margin: 0 0 18px;
                border: 1px solid #cfd6df;
                padding: 10px 12px;
                box-sizing: border-box;
            }

            .meta-item {
                display: flex;
                gap: 10px;
                padding: 6px 0;
                font-size: 12px;
                border-bottom: 1px solid #e5e7eb;
            }

            .meta-item:last-child {
                border-bottom: none;
            }

            .meta-label {
                width: 130px;
                font-weight: bold;
                flex: 0 0 130px;

        .meta-box td,
        .data-table th,
        .data-table td {
            border: 1px solid #cfd6df;
            padding: 8px 10px;
            font-size: 11px;
        }

        .meta-box td {
            width: 25%;
        }

        .meta-label {
            background: #f8fafc;
            font-weight: bold;
        }

        .summary-table td {
            width: 33.333%;
            padding-right: 8px;
            vertical-align: top;
        }

        .summary-card {
            border: 1px solid #d6dde6;
            border-radius: 8px;
            padding: 12px 14px;
            background: #f9fafb;
            min-height: 70px;
        }

        .summary-card .label {
            font-size: 10px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            margin-bottom: 8px;
        }

        .summary-card .value {
            font-size: 16px;
            font-weight: bold;
        }

        .section-title {
            margin: 16px 0 8px;
            font-size: 13px;
            font-weight: bold;
            border-left: 4px solid #374151;
            padding-left: 8px;
        }

        .data-table th {
            background: #f3f4f6;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .ranking-badge {
            display: inline-block;
            width: 26px;
            height: 26px;
            line-height: 26px;
            text-align: center;
            border-radius: 50%;
            background: #374151;
            color: #fff;
            font-weight: bold;
            font-size: 11px;
        }

        .note {
            margin-top: 8px;
            font-size: 10px;
            color: #4b5563;
        }

        .signature td {
            width: 50%;
            padding-top: 30px;
            vertical-align: top;
            font-size: 11px;
        }

        .signature .space {
            height: 58px;
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="header">
            <h1>LAPORAN RESTORAN</h1>
            <div class="subtitle">Rekap Menu Terlaris Operasional</div>
        </div>

        <div class="report-title">
            <h2>Laporan Menu Terlaris</h2>
            <p>Periode {{ \Carbon\Carbon::parse($start)->format('d M Y') }} s.d. {{ \Carbon\Carbon::parse($end)->format('d M Y') }}</p>
        </div>

        <div class="meta-list">
            <div class="meta-item"><span class="meta-label">Tanggal cetak</span><span>{{ \Carbon\Carbon::now()->format('d M Y H:i') }}</span></div>
            <div class="meta-item"><span class="meta-label">Periode data</span><span>{{ \Carbon\Carbon::parse($start)->locale('id')->translatedFormat('F Y') }} - {{ \Carbon\Carbon::parse($end)->locale('id')->translatedFormat('F Y') }}</span></div>
            <div class="meta-item"><span class="meta-label">Nama pencetak</span><span>{{ $namaPencetak ?? '-' }}</span></div>
            <div class="meta-item"><span class="meta-label">Jenis data</span><span>Top menu berdasarkan jumlah terjual</span></div>
        </div>

        <div class="section-title">Top Menu Terlaris</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 8%;" class="text-center">No</th>
                    <th style="width: 34%;">Nama Menu</th>
                    <th style="width: 22%;">Kategori</th>
                    <th style="width: 18%;" class="text-center">Jumlah Terjual</th>
                    <th style="width: 18%;" class="text-right">Total Pendapatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($topMenus as $index => $menu)
                    <tr>
                        <td class="text-center"><span class="ranking-badge">{{ $index + 1 }}</span></td>
                        <td>{{ $menu->menu->nama ?? 'N/A' }}</td>
                        <td>{{ $menu->menu->jenis->nama_jenis ?? '-' }}</td>
                        <td class="text-center">{{ number_format($menu->total_jumlah) }}</td>
                        <td class="text-right">Rp {{ number_format($menu->total_pendapatan, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada data menu dalam periode ini</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="note">Keterangan: laporan ini hanya memuat menu-menu penting berdasarkan total penjualan tertinggi agar mudah dipakai dalam evaluasi restoran.</div>

        <table class="signature">
            <tr>
                <td></td>
                <td class="text-center">
                    <div>TTD</div>
                    <div style="margin-top: 14px;">{{ \Carbon\Carbon::now()->format('d M Y') }}</div>
                    <div style="margin-top: 6px;">Mengetahui,</div>
                    <div class="space"></div>
                    <div><strong>Kepala Restoran</strong></div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>

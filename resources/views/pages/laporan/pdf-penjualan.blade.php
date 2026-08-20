<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan Restoran</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #1f2937;
            margin: 0;
            padding: 0;
            background: #fff;
        }

        .page {
            width: 100%;
            padding: 24px 28px 32px;
            box-sizing: border-box;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #1f2937;
            padding-bottom: 14px;
            margin-bottom: 18px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            letter-spacing: 0.5px;
        }

        .header .subtitle {
            margin: 4px 0 0;
            font-size: 13px;
            color: #4b5563;
        }

        .report-title {
            text-align: center;
            margin: 6px 0 18px;
        }

        .report-title h2 {
            margin: 0;
            font-size: 18px;
        }

        .report-title p {
            margin: 6px 0 0;
            font-size: 12px;
            color: #6b7280;
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
        }

        .section-title {
            margin: 18px 0 10px;
            font-size: 14px;
            font-weight: bold;
            color: #111827;
            border-left: 4px solid #374151;
            padding-left: 10px;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th,
        .data-table td {
            border: 1px solid #cfd6df;
            padding: 9px 10px;
            font-size: 12px;
        }

        .data-table th {
            background: #f3f4f6;
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .note {
            margin-top: 10px;
            font-size: 11px;
            color: #4b5563;
        }

        .signature {
            width: 100%;
            margin-top: 34px;
        }

        .signature td {
            width: 50%;
            vertical-align: top;
            font-size: 12px;
        }

        .signature .right {
            text-align: center;
        }

        .signature .space {
            height: 68px;
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="header">
            <h1>LAPORAN RESTORAN</h1>
            <div class="subtitle">Rekap Penjualan Operasional</div>
        </div>

        <div class="report-title">
            <h2>Laporan Penjualan</h2>
            <p>Periode {{ \Carbon\Carbon::parse($start)->format('d M Y') }} s.d. {{ \Carbon\Carbon::parse($end)->format('d M Y') }}</p>
        </div>

        <div class="meta-list">
            <div class="meta-item"><span class="meta-label">Tanggal cetak</span><span>{{ \Carbon\Carbon::now()->format('d M Y H:i') }}</span></div>
            <div class="meta-item"><span class="meta-label">Periode data</span><span>{{ \Carbon\Carbon::parse($start)->locale('id')->translatedFormat('F Y') }} - {{ \Carbon\Carbon::parse($end)->locale('id')->translatedFormat('F Y') }}</span></div>
            <div class="meta-item"><span class="meta-label">Nama pencetak</span><span>{{ $namaPencetak }}</span></div>
            <div class="meta-item"><span class="meta-label">Jenis data</span><span>Akumulasi transaksi penjualan</span></div>
        </div>

        <div class="section-title">Akumulasi Penjualan Bulanan</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 8%;">No</th>
                    <th style="width: 34%;">Bulan</th>
                    <th style="width: 18%;" class="text-center">Jumlah Transaksi</th>
                    <th style="width: 20%;" class="text-right">Total Penjualan</th>
                    <th style="width: 20%;" class="text-right">Rata-rata / Transaksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($monthlySummary as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $item['periode'] }}</td>
                        <td class="text-center">{{ number_format($item['jumlah_transaksi']) }}</td>
                        <td class="text-right">Rp {{ number_format($item['total_penjualan'], 0, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format($item['rata_rata'], 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada data penjualan pada periode ini</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="note">Keterangan: laporan ini menampilkan data penting berupa jumlah transaksi, total omzet, dan akumulasi per bulan. Contoh periode September dan Oktober akan tampil sebagai dua baris akumulasi bulanan jika data tersedia pada rentang tanggal tersebut.</div>

        <table class="signature">
            <tr>
                <td></td>
                <td class="right">
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

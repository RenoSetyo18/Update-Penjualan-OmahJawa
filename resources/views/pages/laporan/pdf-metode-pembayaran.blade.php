<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Metode Pembayaran</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .page {
            width: 100%;
            padding: 20px;
        }
        .header {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            margin-bottom: 20px;
        }
        .header img {
            max-height: 80px;
            margin-bottom: 12px;
        }
        .header-text {
            flex: none;
            width: 100%;
        }
        .header-text h1 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }
        .header-text p {
            margin: 4px 0 0;
            color: #555;
            font-size: 12px;
        }
        .summary-cards {
    width: 100%;
    margin-bottom: 24px;
}

.summary-table {
    width: 100%;
    border: none;
}

.summary-table td {
    border: none;
    padding: 0 8px 16px 8px;
    vertical-align: top;
}

.summary-table td {
    width: 50%;
    vertical-align: top;
}
        .card {
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 16px;
            background: #fff;
        }
        .card h2 {
            margin: 0 0 8px;
            font-size: 13px;
            color: #555;
            font-weight: bold;
        }
        .card p {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }
        .card.info-box {
            background: #f9f9f9;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }
        table th,
        table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
            font-size: 11px;
        }
        table th {
            background: #f7f7f7;
            font-weight: bold;
            color: #333;
        }
        table td {
            background: #fff;
        }
        table tr:nth-child(even) td {
            background: #f9f9f9;
        }
        .method-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            color: white;
        }
        .method-cash {
            background-color: #27ae60;
        }
        .method-card {
            background-color: #3498db;
        }
        .method-transfer {
            background-color: #9b59b6;
        }
        .method-check {
            background-color: #f39c12;
        }
        .method-qris {
            background-color: #1abc9c;
        }
        .meta {
            margin-top: 8px;
            color: #555;
            font-size: 11px;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        @media print {
            .page {
                padding: 0;
            }
            .header {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="header">
            <img src="{{ $logoBase64 }}" alt="Logo Omah Jawa">
            <div class="header-text">
                <h1>Laporan Metode Pembayaran</h1>
                <p>Periode: {{ \Carbon\Carbon::parse($start)->format('d-m-Y') }} sampai {{ \Carbon\Carbon::parse($end)->format('d-m-Y') }}</p>
                <p class="meta">Tanggal Cetak: {{ \Carbon\Carbon::now()->format('d-m-Y H:i') }}</p>
            </div>
        </div>

        <div class="summary-cards">
            <table class="summary-table">
                <tr>
                    <td>
                        <div class="card info-box">
                            <h2>Total Transaksi</h2>
                            <p>{{ number_format($totalTransaksi) }}</p>
                        </div>
                    </td>
                    <td>
                        <div class="card info-box">
                            <h2>Total Pendapatan</h2>
                            <p>Rp {{ number_format($totalPendapatan) }}</p>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="card info-box">
                            <h2>Metode Paling Umum</h2>
                            <p>{{ $metodeStats->first()?->metode_pembayaran ?? 'N/A' }}</p>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 25%">Metode Pembayaran</th>
                    <th class="text-center" style="width: 20%">Jumlah Transaksi</th>
                    <th class="text-center" style="width: 15%">% Transaksi</th>
                    <th class="text-right" style="width: 22%">Total Pendapatan</th>
                    <th class="text-center" style="width: 18%">% Pendapatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($metodeStats as $stat)
                <tr>
                    <td>
                        @php
                            $method = strtolower(str_replace(' ', '_', $stat->metode_pembayaran));
                            $badgeClass = "method-" . $method;
                            if (!in_array($badgeClass, ['method-cash', 'method-card', 'method-transfer', 'method-check', 'method-qris'])) {
                                $badgeClass = 'method-cash';
                            }
                        @endphp
                        <span class="method-badge {{ $badgeClass }}">{{ $stat->metode_pembayaran }}</span>
                    </td>
                    <td class="text-center">{{ number_format($stat->jumlah_transaksi) }}</td>
                    <td class="text-center">{{ $totalTransaksi > 0 ? number_format(($stat->jumlah_transaksi / $totalTransaksi) * 100, 2) : 0 }}%</td>
                    <td class="text-right">Rp {{ number_format($stat->total_pendapatan) }}</td>
                    <td class="text-center">{{ $totalPendapatan > 0 ? number_format(($stat->total_pendapatan / $totalPendapatan) * 100, 2) : 0 }}%</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">Tidak ada data transaksi dalam periode ini</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>

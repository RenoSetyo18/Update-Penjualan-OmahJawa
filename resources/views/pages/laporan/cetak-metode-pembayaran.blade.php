<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Metode Pembayaran</title>
    <style>
        body { font-family: Arial, sans-serif; color: #333; }
        .page { width: 100%; padding: 20px; }
        .header { display:flex; align-items:center; justify-content:space-between; gap:20px; margin-bottom:20px; }
        .header img { max-height:80px; }
        .header-text { flex:1; }
        .header-text h1 { margin:0; font-size:22px; }
        .header-text p { margin:4px 0 0; color:#555; }
        .summary-cards { display:flex; gap:16px; margin-bottom:16px; }
        .card { border:1px solid #ccc; border-radius:8px; flex:1; min-width:160px; padding:12px; background:#fff; }
        .card h2 { margin:0 0 8px; font-size:16px; }
        .card p { margin:0; font-size:18px; font-weight:bold; }
        table { width:100%; border-collapse:collapse; margin-top:12px; }
        table th, table td { border:1px solid #ddd; padding:8px; text-align:left; }
        table th { background:#f7f7f7; }
        .text-right { text-align:right; }
        .btn {
            display: inline-block;
            font-family: Arial, sans-serif;
            padding: 8px 14px;
            border-radius: 4px;
            text-decoration: none;
            cursor: pointer;
            border: 1px solid transparent;
            font-size: 14px;
        }
        .btn-secondary {
            background: #6c757d;
            color: #fff;
            border-color: #6c757d;
        }
        .btn-info {
            background: #17a2b8;
            color: #fff;
            border-color: #17a2b8;
        }
        .btn-success {
            background: #28a745;
            color: #fff;
            border-color: #28a745;
        }
        .btn:hover { opacity: 0.92; }
        @media print { .page { padding:0; } .header { page-break-inside:avoid; } }
    </style>
</head>
<body>
    <div class="page">
        <div class="header">
            <img src="{{ asset('dataset/dist/img/LogoOmahJawa.png') }}" alt="Logo Omah Jawa">
            <div class="header-text">
                <h1>Laporan Metode Pembayaran</h1>
                <p>Periode: {{ \Carbon\Carbon::parse($start)->format('d-m-Y') }} sampai {{ \Carbon\Carbon::parse($end)->format('d-m-Y') }}</p>
                <p class="meta">Tanggal Cetak: {{ \Carbon\Carbon::now()->format('d-m-Y H:i') }}</p>
            </div>
        </div>

        <div class="summary-cards">
            <div class="card">
                <h2>Total Transaksi</h2>
                <p>{{ number_format($totalTransaksi) }}</p>
            </div>
            <div class="card">
                <h2>Total Pendapatan</h2>
                <p>Rp{{ number_format($totalPendapatan, 0, ',', '.') }}</p>
            </div>
            <div class="card">
                <h2>Metode Terbanyak</h2>
                <p>{{ $metodeStats->first()->metode_pembayaran ?? 'N/A' }}</p>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Metode Pembayaran</th>
                    <th>Jumlah Transaksi</th>
                    <th>Persentase Transaksi</th>
                    <th>Total Pendapatan</th>
                    <th>Persentase Pendapatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($metodeStats as $stat)
                <tr>
                    <td>{{ $stat->metode_pembayaran }}</td>
                    <td>{{ number_format($stat->jumlah_transaksi) }}</td>
                    <td>{{ $totalTransaksi > 0 ? number_format(($stat->jumlah_transaksi / $totalTransaksi) * 100, 1) : 0 }}%</td>
                    <td class="text-right">Rp{{ number_format($stat->total_pendapatan, 0, ',', '.') }}</td>
                    <td>{{ $totalPendapatan > 0 ? number_format(($stat->total_pendapatan / $totalPendapatan) * 100, 1) : 0 }}%</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">Tidak ada data transaksi dalam periode ini</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <script>
        window.addEventListener('load', function () {
            window.print();
        });
    </script>
</body>
</html>

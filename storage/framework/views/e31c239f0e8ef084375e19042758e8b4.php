<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Transaksi</title>
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

        .summary-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .summary-table td {
            width: 33.3333%;
            padding: 0 8px 16px;
            vertical-align: top;
        }

        .card {
            border: 1px solid #cfd6df;
            border-radius: 8px;
            padding: 16px;
            background: #f9fafb;
            min-height: 92px;
            box-sizing: border-box;
        }

        .card h3 {
            margin: 0 0 8px;
            font-size: 13px;
            color: #4b5563;
            font-weight: 700;
        }

        .card p {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
            color: #111827;
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

        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
            color: #fff;
        }

        .badge-secondary {
            background: #6b7280;
        }

        .badge-info {
            background: #0ea5e9;
        }

        .badge-success {
            background: #16a34a;
        }

        .note {
            margin-top: 10px;
            font-size: 11px;
            color: #4b5563;
        }

        @media print {
            .page {
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <?php
        $dari = $request->dari ?? null;
        $sampai = $request->sampai ?? null;
        $namaPencetak = auth()->user()->name ?? auth()->user()->nama ?? 'Administrator';
        $totalTransaksi = $transaksis->count();
        $totalPendapatan = $transaksis->sum('totalharga');
        $countBelumSiap = $transaksis->whereIn('status', ['belum_diproses', 'sedang_diproses', 'Pesanan Online'])->count();
        $countMakananSiap = $transaksis->where('status', 'makanan_siap')->count();
        $countSelesai = $transaksis->whereIn('status', ['selesai', 'lunas'])->count();
        $periodeLabel = $dari && $sampai
            ? \Carbon\Carbon::parse($dari)->format('d M Y') . ' s.d. ' . \Carbon\Carbon::parse($sampai)->format('d M Y')
            : 'Semua periode';
    ?>

    <div class="page">
        <div class="header">
            <h1>Warung Omah Jawa</h1>
            <div class="subtitle">Kandeg, Waung, Kec. Baron, Kabupaten Nganjuk, Jawa Timur 64482</div>
        </div>

        <div class="report-title">
            <h2>Rekap Transaksi</h2>
            <p>Periode <?php echo e($periodeLabel); ?></p>
        </div>

        <div class="meta-list">
            <div class="meta-item"><span class="meta-label">Tanggal cetak</span><span><?php echo e(\Carbon\Carbon::now()->format('d M Y H:i')); ?></span></div>
            <div class="meta-item"><span class="meta-label">Periode data</span><span><?php echo e($periodeLabel); ?></span></div>
            <div class="meta-item"><span class="meta-label">Nama pencetak</span><span><?php echo e($namaPencetak); ?></span></div>
            <div class="meta-item"><span class="meta-label">Jenis data</span><span>Akumulasi transaksi pesanan</span></div>
        </div>

        <div class="section-title">Ringkasan</div>
        <table class="summary-table">
            <tr>
                <td>
                    <div class="card">
                        <h3>Total Transaksi</h3>
                        <p><?php echo e($totalTransaksi); ?></p>
                    </div>
                </td>
                <td>
                    <div class="card">
                        <h3>Total Pendapatan</h3>
                        <p>Rp <?php echo e(number_format($totalPendapatan, 0, ',', '.')); ?></p>
                    </div>
                </td>
                <td>
                    <div class="card">
                        <h3>Status (Belum Siap / Makanan Siap / Selesai)</h3>
                        <p><?php echo e($countBelumSiap); ?> / <?php echo e($countMakananSiap); ?> / <?php echo e($countSelesai); ?></p>
                    </div>
                </td>
            </tr>
        </table>

        <div class="section-title">Daftar Transaksi</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 6%;" class="text-center">No</th>
                    <th style="width: 14%;">Kode</th>
                    <th style="width: 12%;">Meja</th>
                    <th style="width: 20%;">Atas Nama</th>
                    <th style="width: 16%;" class="text-right">Total Harga</th>
                    <th style="width: 14%;">Status</th>
                    <th style="width: 18%;">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $transaksis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $transaksi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $statusLabel = \App\Models\Transaksi::transaksiStatusLabel($transaksi->status);
                        $statusClass = match ($statusLabel) {
                            'Belum Siap' => 'badge-secondary',
                            'Makanan Siap' => 'badge-info',
                            'Selesai' => 'badge-success',
                            default => 'badge-secondary',
                        };
                    ?>
                    <tr>
                        <td class="text-center"><?php echo e($index + 1); ?></td>
                        <td><?php echo e($transaksi->kode); ?></td>
                        <td><?php echo e($transaksi->nomeja); ?></td>
                        <td><?php echo e($transaksi->atasnama); ?></td>
                        <td class="text-right">Rp <?php echo e(number_format($transaksi->totalharga, 0, ',', '.')); ?></td>
                        <td><span class="badge <?php echo e($statusClass); ?>"><?php echo e($statusLabel); ?></span></td>
                        <td><?php echo e(\Carbon\Carbon::parse($transaksi->created_at)->format('d M Y H:i')); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="text-center">Tidak ada data transaksi pada periode ini</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="note">Keterangan: rekap ini menampilkan daftar transaksi lengkap beserta ringkasan status agar tampilannya lebih konsisten dengan cetak laporan.</div>
    </div>

    <script>
        window.addEventListener('load', function () {
            window.print();
        });
    </script>
</body>
</html>
<?php /**PATH C:\laragon\www\omahjawa1-Copy\resources\views/pages/transaksi/cetak_rekap.blade.php ENDPATH**/ ?>
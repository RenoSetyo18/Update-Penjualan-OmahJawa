<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Menu Terlaris Restoran</title>
    <style>
        @media print {
        @page {
            margin: 10mm;
        }
    }
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

        .meta-list,
        .data-table,
        .signature {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th,
        .data-table td {
            border: 1px solid #cfd6df;
            padding: 8px 10px;
            font-size: 12px;
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
            border-left: 4px solid #374151;
            padding-left: 10px;
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
            <h1>Warung Omah Jawa</h1>
            <div class="subtitle">Kandeg, Waung, Kec. Baron, Kabupaten Nganjuk, Jawa Timur 64482</div>
        </div>

        <div class="report-title">
            <h2>Laporan Menu Terlaris</h2>
            <p>Periode <?php echo e(\Carbon\Carbon::parse($start)->format('d M Y')); ?> s.d. <?php echo e(\Carbon\Carbon::parse($end)->format('d M Y')); ?></p>
        </div>

        <div class="meta-list">
            <div class="meta-item"><span class="meta-label">Tanggal cetak</span><span><?php echo e(\Carbon\Carbon::now()->format('d M Y H:i')); ?></span></div>
            <div class="meta-item"><span class="meta-label">Periode data</span><span><?php echo e(\Carbon\Carbon::parse($start)->locale('id')->translatedFormat('F Y')); ?> - <?php echo e(\Carbon\Carbon::parse($end)->locale('id')->translatedFormat('F Y')); ?></span></div>
            <div class="meta-item"><span class="meta-label">Nama pencetak</span><span><?php echo e($namaPencetak ?? '-'); ?></span></div>
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
                <?php $__empty_1 = true; $__currentLoopData = $topMenus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="text-center"><span class="ranking-badge"><?php echo e($index + 1); ?></span></td>
                        <td><?php echo e($menu->menu->nama ?? 'N/A'); ?></td>
                        <td><?php echo e($menu->menu->jenis->nama_jenis ?? '-'); ?></td>
                        <td class="text-center"><?php echo e(number_format($menu->total_jumlah)); ?></td>
                        <td class="text-right">Rp <?php echo e(number_format($menu->total_pendapatan, 0, ',', '.')); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada data menu dalam periode ini</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="note">Keterangan: laporan ini hanya memuat menu-menu penting berdasarkan total penjualan tertinggi agar mudah dipakai dalam evaluasi restoran.</div>

        <table class="signature">
            <tr>
                <td></td>
                <td class="text-center">
                    <div style="margin-top: 14px;"><?php echo e(\Carbon\Carbon::now()->format('d M Y')); ?></div>
                    <div style="margin-top: 6px;">Mengetahui,</div>
                    <div class="space"></div>
                    <div><strong>Kepala Restoran</strong></div>
                </td>
            </tr>
        </table>
    </div>

    <script>
        window.addEventListener('load', function () {
            window.print();
        });
    </script>
</body>
</html>
<?php /**PATH C:\laragon\www\omahjawa1-Copy\resources\views/pages/laporan/cetak-menu-terlaris.blade.php ENDPATH**/ ?>
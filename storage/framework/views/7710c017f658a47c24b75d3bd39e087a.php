<!DOCTYPE html>
<html>
<head>
    <title>Struk Pembayaran</title>
    <style>
        body {
            font-family: monospace;
            max-width: 300px;
            margin: 20px auto;
            padding: 10px;
            border: 1px solid #000;
        }
        h2, h3 {
            text-align: center;
            margin-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 14px;
        }
        th, td {
            border-bottom: 1px dotted #000;
            padding: 5px 0;
        }
        tfoot td {
            border: none;
            font-weight: bold;
        }
        .right {
            text-align: right;
        }
        .center {
            text-align: center;
        }
        .small {
            font-size: 12px;
            color: #555;
        }
        .note {
            margin-top: 20px;
            font-size: 12px;
            text-align: center;
            font-style: italic;
        }
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
        .btn:hover {
            opacity: 0.92;
        }
    </style>
</head>
<body>
    <div class="page">
    <h3>Struk Pembayaran</h3>

    <p><strong>Nomor Meja:</strong> <?php echo e($transaksi->nomeja); ?></p>
    <p><strong>Atas Nama:</strong> <?php echo e($transaksi->atasnama); ?></p>
    <p><strong>Tanggal:</strong> <?php echo e(date('d-m-Y H:i', strtotime($transaksi->created_at))); ?></p>
    <p><strong>Metode Pembayaran:</strong> <?php echo e($transaksi->metode_pembayaran ?? 'Cash'); ?></p>

    <?php
        $jumlahBayar = isset($jumlah_bayar) ? floatval($jumlah_bayar) : $transaksi->totalharga;
        $kembalian = isset($kembalian) ? floatval($kembalian) : ($jumlahBayar - $transaksi->totalharga);
    ?>

    <table>
        <thead>
            <tr>
                <th>Menu</th>
                <th class="center">Qty</th>
                <th class="right">Harga</th>
                <th class="right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $transaksi->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($detail->menu->nama); ?></td>
                    <td class="center"><?php echo e($detail->jumlah); ?></td>
                    <td class="right">Rp<?php echo e(number_format($detail->harga, 2)); ?></td>
                    <td class="right">Rp<?php echo e(number_format($detail->harga * $detail->jumlah, 2)); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="right">Total Harga</td>
                <td class="right">Rp<?php echo e(number_format($transaksi->totalharga, 2)); ?></td>
            </tr>
            <tr>
                <td colspan="3" class="right">Jumlah Bayar</td>
                <td class="right">Rp<?php echo e(number_format($jumlahBayar, 2)); ?></td>
            </tr>
            <tr>
                <td colspan="3" class="right">Kembalian</td>
                <td class="right">Rp<?php echo e(number_format($kembalian, 2)); ?></td>
            </tr>
        </tfoot>
    </table>

    <p class="note">Terima kasih sudah berbelanja di Cafe Omah Jawa!</p>

    <script>
        window.addEventListener('load', function () {
            window.print();
        });
    </script>
</body>
</html>
<?php /**PATH C:\laragon\www\omahjawa1-Copy\resources\views/pages/kasir/cetak_struk.blade.php ENDPATH**/ ?>
<?php $__env->startSection('content'); ?>
<div class="container">
    <h1>Laporan Transaksi</h1>

    <!-- Tombol Cetak dengan parameter filter -->
    <div class="d-flex justify-content-end mb-3">
        <a href="<?php echo e(route('laporan.cetak', ['start_date' => request('start_date'), 'end_date' => request('end_date')])); ?>"
           class="btn btn-primary" target="_blank">
            <i class="fas fa-print"></i> Cetak
        </a>
    </div>

    <!-- Form Filter Laporan -->
    <form method="GET" action="<?php echo e(route('laporan.index')); ?>" class="mb-3">
        <div class="row">
            <div class="col-md-5">
                <input type="date" name="start_date" class="form-control"
                       value="<?php echo e(request('start_date')); ?>">
            </div>
            <div class="col-md-5">
                <input type="date" name="end_date" class="form-control"
                       value="<?php echo e(request('end_date')); ?>">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </div>
    </form>

    <!-- Tabel Laporan -->
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tanggal</th>
                <th>Total Harga</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $transaksis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaksi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($transaksi->id); ?></td>
                <td><?php echo e($transaksi->created_at->format('Y-m-d H:i')); ?></td>
                <td>Rp <?php echo e(number_format($transaksi->totalharga, 0, ',', '.')); ?></td>
                <td><?php echo e($transaksi->status); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

    <?php if($transaksis->isEmpty()): ?>
        <div class="alert alert-warning text-center">Tidak ada transaksi pada periode ini.</div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\omahjawa1\resources\views/pages/laporan/index.blade.php ENDPATH**/ ?>
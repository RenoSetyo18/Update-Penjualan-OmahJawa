<?php $__env->startSection('header'); ?>
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Dashboard</h1>
        </div>
        <div class="col-sm-6 text-right">
            <form action="<?php echo e(route('dashboard.index')); ?>" method="GET" class="d-flex align-items-center justify-content-end">
                <label for="startDate" class="mb-0 mr-2">Dari:</label>
                <input type="date" name="startDate" id="startDate" class="form-control d-inline-block w-auto mr-2"
                    value="<?php echo e($startDate); ?>">
                <label for="endDate" class="mb-0 mr-2">Sampai:</label>
                <input type="date" name="endDate" id="endDate" class="form-control d-inline-block w-auto mr-2"
                    value="<?php echo e($endDate); ?>">
                <button type="submit" class="btn btn-primary">Tampilkan</button>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php
        $startDate = $startDate ?? '';
        $endDate = $endDate ?? '';
    ?>

    <div class="row justify-content-center">
    <div class="col-lg-3 col-md-6">
        <div class="card bg-warning text-white h-100">
            <div class="card-body text-center d-flex flex-column justify-content-center align-items-center" style="min-height: 150px;">
                <h5>Total Transaksi</h5>
                <h3><?php echo e($totalTransaksiLunas ?? 0); ?></h3>
                <div class="small mt-2">Transaksi yang sudah <b>Lunas</b></div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card bg-info text-white h-100">
            <div class="card-body text-center d-flex flex-column justify-content-center align-items-center" style="min-height: 150px;">
                <h5>Jumlah Pesanan</h5>
                <h3><?php echo e($totalTransaksi ?? 0); ?></h3>
                <div class="small mt-2">Total transaksi masuk</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mt-3 mt-lg-0">
        <div class="card bg-success text-white h-100">
            <div class="card-body text-center d-flex flex-column justify-content-center align-items-center" style="min-height: 150px;">
                <h5 class="text-wrap mb-1">Total Pendapatan</h5>
                <h3>Rp<?php echo e(number_format($totalPendapatanLunas, 2)); ?></h3>
            </div>
        </div>
    </div>
</div>


    
    

    <div class="row mt-4">
        <div class="col-md-6">
            <h3>Grafik Pendapatan Harian</h3>
            <canvas id="incomeChart" height="200"></canvas>
        </div>
        <div class="col-md-6">
            <h3>Grafik Total Penjualan Harian</h3>
            <canvas id="penjualanChart" height="200"></canvas>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        var ctx2 = document.getElementById('incomeChart').getContext('2d');
        var incomeChart = new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode(array_keys((array) $pendapatanHarian)); ?>,
                datasets: [{
                    label: 'Pendapatan Harian (Rp)',
                    data: <?php echo json_encode(array_values((array) $pendapatanHarian)); ?>,
                    backgroundColor: '#007bff'
                }]
            }
        });

        var ctx3 = document.getElementById('penjualanChart').getContext('2d');
        var penjualanChart = new Chart(ctx3, {
            type: 'line',
            data: {
                labels: <?php echo json_encode(array_keys((array) $penjualanHarian)); ?>,
                datasets: [{
                    label: 'Total Penjualan Harian',
                    data: <?php echo json_encode(array_values((array) $penjualanHarian)); ?>,
                    borderColor: '#fd7e14',
                    backgroundColor: 'rgba(253, 126, 20, 0.2)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\omahjawa1\resources\views/pages/dashboard/index.blade.php ENDPATH**/ ?>
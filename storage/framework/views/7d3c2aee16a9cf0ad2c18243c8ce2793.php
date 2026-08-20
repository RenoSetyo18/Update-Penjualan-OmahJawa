<?php $__env->startSection('header'); ?>
    <div class="row mb-4">
        <div class="col-sm-6">
            <h1 class="h3 mb-0">Dashboard</h1>
            <small class="text-muted" id="currentDateTime"></small>
        </div>
        <div class="col-sm-6 text-right">
            <form action="<?php echo e(route('dashboard.index')); ?>" method="GET" class="d-flex align-items-center justify-content-end">
                <label for="startDate" class="mb-0 mr-2 font-weight-bold">Dari:</label>
                <input type="date" name="startDate" id="startDate" class="form-control d-inline-block w-auto mr-2"
                    value="<?php echo e($startDate); ?>">
                <label for="endDate" class="mb-0 mr-2 font-weight-bold">Sampai:</label>
                <input type="date" name="endDate" id="endDate" class="form-control d-inline-block w-auto mr-2"
                    value="<?php echo e($endDate); ?>">
                <button type="submit" class="btn btn-primary">Tampilkan</button>
            </form>
        </div>
    </div>
    <style>
        /* Soften the dashboard visuals without changing structure */
        .card {
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(20,20,50,0.06);
            transition: transform .18s ease, box-shadow .18s ease;
            overflow: hidden;
        }
        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 30px rgba(20,20,50,0.08);
        }
        .card-header {
            background: transparent;
            border-bottom: none;
            padding: .9rem 1.25rem;
        }
        .card-body {
            padding: 1.25rem;
        }
        .table thead th {
            background: transparent;
            border-bottom: 1px solid #e9ecef;
        }
        .table tbody tr:hover {
            background: rgba(0,0,0,0.02);
        }
        .chart-container { padding: 0.75rem; }
        /* Make welcome header feel less cramped */
        .dashboard-welcome {
    position: relative;
    overflow: hidden;
    background: linear-gradient(135deg, #4D55CC 0%, #5863ED 100%);
    padding: 1.3rem;
    color: white;
    border-radius: 18px;
    min-height: 160px;
}

/* Bubble besar kanan */
.dashboard-welcome::before {
    content: '';
    position: absolute;
    width: 420px;
    height: 420px;
    background: rgba(255,255,255,0.08);
    border-radius: 50%;
    right: -120px;
    top: 20px;
}

/* Bubble atas */
.dashboard-welcome::after {
    content: '';
    position: absolute;
    width: 240px;
    height: 240px;
    background: rgba(255,255,255,0.06);
    border-radius: 50%;
    top: -140px;
    right: 220px;
}

/* Gelombang kanan bawah */
.dashboard-wave {
    position: absolute;
    right: 120px;
    bottom: -90px;
    width: 420px;
    height: 220px;
    background: rgba(255,255,255,0.05);
    border-radius: 50%;
    transform: rotate(-8deg);
}

/* Lingkaran kecil */
.dashboard-circle {
    position: absolute;
    width: 55px;
    height: 55px;
    background: rgba(255,255,255,0.08);
    border-radius: 50%;
    right: 260px;
    bottom: 25px;
}

/* Titik-titik */
.dashboard-dots {
    position: absolute;
    top: 22px;
    right: 460px;
    display: grid;
    grid-template-columns: repeat(4, 8px);
    gap: 10px;
}

.dashboard-dots span {
    width: 4px;
    height: 4px;
    background: rgba(255,255,255,0.5);
    border-radius: 50%;
}

/* Konten utama */
.dashboard-content {
    position: relative;
    z-index: 2;
}
        }
        @media (max-width: 992px) {
            .card { border-radius: 8px; }
            .card-body { padding: 1rem; }
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php
        $startDate = $startDate ?? '';
        $endDate = $endDate ?? '';
        $role = Auth::user()->role ?? '';
        $roleLabel = \App\Models\User::roleLabel($role);
    ?>

    <div class="row mb-2">
        <div class="col-12 mb-0">
            <div class="card shadow-lg border-0 overflow-hidden">
                <div class="card-body p-0">
                    <div class="dashboard-welcome d-flex align-items-center">

    <!-- dekorasi -->
    <div class="dashboard-wave"></div>
    <div class="dashboard-circle"></div>

    <div class="dashboard-dots">
        <span></span><span></span><span></span><span></span>
        <span></span><span></span><span></span><span></span>
        <span></span><span></span><span></span><span></span>
    </div>

    <!-- content -->
    <div class="dashboard-content d-flex align-items-center">
                        <div class="mr-4 d-flex align-items-center justify-content-center rounded-circle" style="width: 70px; height: 70px; background: rgba(255,255,255,0.2); font-size: 1.75rem; font-weight: 700;">
                            <?php echo e(strtoupper(substr(Auth::user()->name, 0, 1))); ?>

                        </div>
                        <div>
                            <p class="text-white-50 mb-1" style="font-size: 0.9rem;">Selamat datang</p>
                            <h4 class="font-weight-bold mb-1"><?php echo e(Auth::user()->name); ?></h4>
                            <p class="mb-2" style="opacity: 0.85;"><?php echo e(Auth::user()->email); ?></p>
                            <span class="badge badge-light" style="letter-spacing: 0.05em;"><?php echo e($roleLabel); ?></span>
                                </div>
    </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <?php if($role === 'koki'): ?>
        <div class="row mb-4">
            <div class="col-lg-4 col-md-6 mb-3">
                <div class="card bg-danger text-white h-100 shadow">
                    <div class="card-body text-center d-flex flex-column justify-content-center align-items-center" style="min-height: 150px;">
                        <i class="fas fa-hourglass-start fa-2x mb-2"></i>
                        <h5 class="card-title">Belum Diproses</h5>
                        <h3 class="mb-1"><?php echo e($kokiStatusBelum); ?></h3>
                        <small>Pesanan yang menunggu dimulai</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-3">
                <div class="card bg-warning text-white h-100 shadow">
                    <div class="card-body text-center d-flex flex-column justify-content-center align-items-center" style="min-height: 150px;">
                        <i class="fas fa-fire fa-2x mb-2"></i>
                        <h5 class="card-title">Sedang Diproses</h5>
                        <h3 class="mb-1"><?php echo e($kokiStatusSedang); ?></h3>
                        <small>Pesanan yang sedang dikerjakan</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-3">
                <div class="card bg-success text-white h-100 shadow">
                    <div class="card-body text-center d-flex flex-column justify-content-center align-items-center" style="min-height: 150px;">
                        <i class="fas fa-check-circle fa-2x mb-2"></i>
                        <h5 class="card-title">Selesai</h5>
                        <h3 class="mb-1"><?php echo e($kokiStatusSelesai); ?></h3>
                        <small>Pesanan yang sudah siap</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0"><i class="fas fa-list mr-2"></i>Pesanan Belum Selesai</h5>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-striped table-sm mb-0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Atas Nama</th>
                            <th>Status</th>
                            <th>Meja</th>
                            <th class="text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $kokiUnfinishedOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($index + 1); ?></td>
                                <td><?php echo e($order->kode); ?></td>
                                <td><?php echo e($order->atasnama); ?></td>
                                <td><?php echo e(\App\Models\Transaksi::kokiStatusLabel($order->status)); ?></td>
                                <td><?php echo e($order->nomeja); ?></td>
                                <td class="text-right">Rp <?php echo e(number_format($order->totalharga, 0, ',', '.')); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada pesanan belum selesai.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php elseif($role === 'kasir'): ?>
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-3">
                <a href="<?php echo e(route('kasir.index')); ?>" class="text-decoration-none text-white">
                    <div class="card bg-primary h-100 shadow" style="cursor: pointer;">
                        <div class="card-body text-center d-flex flex-column justify-content-center align-items-center" style="min-height: 150px;">
                            <i class="fas fa-money-bill-wave fa-2x mb-2"></i>
                            <h5 class="card-title">Pendapatan Hari Ini</h5>
                            <h3 class="mb-1">Rp <?php echo e(number_format($kasirTodayRevenue, 0, ',', '.')); ?></h3>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <a href="<?php echo e(route('kasir.index')); ?>" class="text-decoration-none text-white">
                    <div class="card bg-info h-100 shadow" style="cursor: pointer;">
                        <div class="card-body text-center d-flex flex-column justify-content-center align-items-center" style="min-height: 150px;">
                            <i class="fas fa-receipt fa-2x mb-2"></i>
                            <h5 class="card-title">Transaksi Hari Ini</h5>
                            <h3 class="mb-1"><?php echo e($kasirTodayTransactions); ?></h3>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <a href="<?php echo e(route('kasir.index')); ?>" class="text-decoration-none text-white">
                    <div class="card bg-success h-100 shadow" style="cursor: pointer;">
                        <div class="card-body text-center d-flex flex-column justify-content-center align-items-center" style="min-height: 150px;">
                            <i class="fas fa-check fa-2x mb-2"></i>
                            <h5 class="card-title">Lunas Hari Ini</h5>
                            <h3 class="mb-1"><?php echo e($kasirTodayLunas); ?></h3>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <a href="<?php echo e(route('kasir.index')); ?>" class="text-decoration-none text-white">
                    <div class="card bg-danger h-100 shadow" style="cursor: pointer;">
                        <div class="card-body text-center d-flex flex-column justify-content-center align-items-center" style="min-height: 150px;">
                            <i class="fas fa-times-circle fa-2x mb-2"></i>
                            <h5 class="card-title">Belum Lunas</h5>
                            <h3 class="mb-1"><?php echo e($kasirTodayBelumLunas); ?></h3>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="card shadow">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="fas fa-list-ul mr-2"></i>Data Belum Lunas (Hanya Lihat)
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-sm mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Meja</th>
                                <th>Atas Nama</th>
                                <th>Status</th>
                                <th>Total</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $kasirUnpaidTransactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($index + 1); ?></td>
                                    <td><?php echo e($item->kode ?? '-'); ?></td>
                                    <td><?php echo e($item->nomeja ?? '-'); ?></td>
                                    <td><?php echo e($item->atasnama ?? '-'); ?></td>
                                    <td>
                                        <span class="badge badge-warning text-dark">
                                            <?php echo e($item->isOnlineOrder() ? 'Pesanan Online' : \App\Models\Transaksi::kasirStatusLabel($item->status)); ?>

                                        </span>
                                    </td>
                                    <td>Rp <?php echo e(number_format($item->totalharga ?? 0, 0, ',', '.')); ?></td>
                                    <td><?php echo e($item->created_at ? $item->created_at->translatedFormat('d M Y H:i') : '-'); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">Tidak ada data tagihan belum lunas.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php else: ?>
        <?php if(($transaksiBelumDiproses ?? 0) > 0): ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <strong>Perhatian!</strong> Ada <strong><?php echo e($transaksiBelumDiproses); ?></strong> transaksi yang belum diproses.
                <a href="<?php echo e(route('transaksi.index')); ?>" class="alert-link">Kelola sekarang</a>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>

        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-3">
                <a href="<?php echo e(route('laporan.penjualan')); ?>" class="text-decoration-none">
                    <div class="card bg-warning text-white h-100 shadow" style="cursor: pointer; transition: transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 16px rgba(0,0,0,0.2)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='';">
                        <div class="card-body text-center d-flex flex-column justify-content-center align-items-center" style="min-height: 150px;">
                            <i class="fas fa-shopping-cart fa-2x mb-2"></i>
                            <h5 class="card-title">Total Transaksi</h5>
                            <h3 class="mb-1"><?php echo e($totalTransaksi); ?></h3>
                            <small>Total transaksi periode ini</small>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <a href="<?php echo e(route('laporan.penjualan')); ?>" class="text-decoration-none">
                    <div class="card bg-success text-white h-100 shadow" style="cursor: pointer; transition: transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 16px rgba(0,0,0,0.2)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='';">
                        <div class="card-body text-center d-flex flex-column justify-content-center align-items-center" style="min-height: 150px;">
                            <i class="fas fa-dollar-sign fa-2x mb-2"></i>
                            <h5 class="card-title">Pendapatan</h5>
                            <h3 class="mb-1">Rp<?php echo e(number_format($totalPendapatanLunas, 0, ',', '.')); ?></h3>
                            <small>Dari transaksi lunas</small>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <a href="javascript:void(0)" onclick="scrollToDashboardCharts()" class="text-decoration-none">
                    <div class="card bg-info text-white h-100 shadow" style="cursor: pointer; transition: transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 16px rgba(0,0,0,0.2)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='';">
                        <div class="card-body text-center d-flex flex-column justify-content-center align-items-center" style="min-height: 150px;">
                            <i class="fas fa-star fa-2x mb-2"></i>
                            <h5 class="card-title">Menu Paling Laris</h5>
                            <h3 class="mb-1">Top 3</h3>
                            <small>Periode ini</small>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <a href="javascript:void(0)" onclick="scrollToDashboardCharts()" class="text-decoration-none">
                    <div class="card bg-secondary text-white h-100 shadow" style="cursor: pointer; transition: transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 16px rgba(0,0,0,0.2)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='';">
                        <div class="card-body text-center d-flex flex-column justify-content-center align-items-center" style="min-height: 150px;">
                            <i class="fas fa-chart-bar fa-2x mb-2"></i>
                            <h5 class="card-title">Grafik Harian</h5>
                            <h3 class="mb-1">Pendapatan</h3>
                            <small>Perbandingan harian</small>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-lg-6 col-md-12 mb-3">
                <div class="card shadow h-100">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-chart-line mr-2"></i>Tren Penjualan Mingguan
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-2 text-muted">Bandingkan pendapatan per hari untuk minggu ini dan minggu lalu.</p>
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <h6 class="mb-1">Minggu ini</h6>
                                <h3 class="mb-0">Rp <?php echo e(number_format($currentWeekSales, 0, ',', '.')); ?></h3>
                            </div>
                            <div class="text-right">
                                <h6 class="mb-1">Minggu lalu</h6>
                                <h5 class="mb-0">Rp <?php echo e(number_format($previousWeekSales, 0, ',', '.')); ?></h5>
                            </div>
                        </div>
                        <div class="chart-container" style="position: relative; min-height: 320px;">
                            <canvas id="weeklyTrendChart" style="height: 100%; width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-md-12 mb-3">
                <div class="card shadow h-100">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-exchange-alt mr-2"></i>Perbandingan Periode Bulanan
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-2 text-muted">Bandingkan total pendapatan bulan ini dengan bulan lalu.</p>
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <h6 class="mb-1">Bulan ini</h6>
                                <h3 class="mb-0">Rp <?php echo e(number_format($currentMonthSales, 0, ',', '.')); ?></h3>
                            </div>
                            <div class="text-right">
                                <h6 class="mb-1">Bulan lalu</h6>
                                <h5 class="mb-0">Rp <?php echo e(number_format($previousMonthSales, 0, ',', '.')); ?></h5>
                            </div>
                        </div>
                        <div class="chart-container" style="position: relative; min-height: 320px;">
                            <canvas id="monthlyComparisonChart" style="height: 100%; width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4" id="dashboard-bottom-row">
            <div class="col-lg-4 col-md-12 mb-4">
                <div class="card shadow h-100">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-chart-bar mr-2"></i>Grafik Pendapatan Harian
                        </h5>
                    </div>
                    <div class="card-body" style="min-height: 360px; padding-bottom: 1.5rem;">
                        <?php if(empty($pendapatanHarian)): ?>
                            <div class="text-center py-4">
                                <i class="fas fa-chart-bar fa-3x text-muted mb-3"></i>
                                <p class="text-muted mb-0">Belum ada data pendapatan untuk periode ini.</p>
                            </div>
                        <?php else: ?>
                            <div class="chart-container" style="position: relative; min-height: 320px;">
                                <canvas id="incomeChart" style="height: 100%; width: 100%;"></canvas>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-12 mb-4">
                <div class="card shadow h-100">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-credit-card mr-2"></i>Distribusi Penggunaan Metode Pembayaran
                        </h5>
                    </div>
                    <div class="card-body" style="min-height: 360px;">
                        <?php if($paymentMethodDistribution->isEmpty()): ?>
                            <div class="text-center py-4">
                                <i class="fas fa-credit-card fa-3x text-muted mb-3"></i>
                                <p class="text-muted mb-0">Belum ada transaksi lunas dengan metode pembayaran pada periode ini.</p>
                            </div>
                        <?php else: ?>
                            <div class="chart-container" style="position: relative; min-height: 320px;">
                                <canvas id="paymentMethodChart" style="height: 100%; width: 100%;"></canvas>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-12 mb-4">
                <div class="d-flex flex-column h-100">
                    <div class="card shadow mb-3 flex-fill">
                        <div class="card-header bg-light">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-trophy mr-2"></i>Top 3 Menu Paling Laris
                            </h5>
                        </div>
                        <div class="card-body">
                            <?php if($bestSellingOverall->isEmpty()): ?>
                                <div class="text-center py-4">
                                    <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                                    <p class="text-muted mb-0">Belum ada data penjualan untuk periode ini.</p>
                                </div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-hover table-sm mb-0">
                                        <thead class="thead-light">
                                            <tr>
                                                <th class="border-0">Posisi</th>
                                                <th class="border-0">Menu</th>
                                                <th class="border-0 text-center">Jumlah</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__currentLoopData = $bestSellingOverall; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td class="font-weight-bold"><?php echo e($index + 1); ?></td>
                                                    <td><?php echo e($item->menu_nama); ?></td>
                                                    <td class="text-center"><?php echo e($item->total_jumlah); ?></td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="card shadow flex-fill">
                        <div class="card-header bg-light">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-star mr-2"></i>Menu Paling Laris per Kategori
                            </h5>
                        </div>
                        <div class="card-body">
                            <?php if($bestSellingByJenis->isEmpty()): ?>
                                <div class="text-center py-4">
                                    <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                                    <p class="text-muted mb-0">Belum ada data penjualan untuk periode ini.</p>
                                </div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-hover table-sm mb-0">
                                        <thead class="thead-light">
                                            <tr>
                                                <th class="border-0">Kategori</th>
                                                <th class="border-0">Menu</th>
                                                <th class="border-0 text-center">Jumlah</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__currentLoopData = $bestSellingByJenis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td class="font-weight-bold"><?php echo e($item->nama_jenis); ?></td>
                                                    <td><?php echo e($item->menu_nama); ?></td>
                                                    <td class="text-center"><?php echo e($item->total_jumlah); ?></td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if(in_array($role, ['admin', 'super_admin'], true)): ?>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            var ctxWeeklyTrend = document.getElementById('weeklyTrendChart');
            if (ctxWeeklyTrend) {
                ctxWeeklyTrend = ctxWeeklyTrend.getContext('2d');
                var weeklyTrendLabels = <?php echo json_encode($weeklyTrendLabels); ?>;
                var weeklyTrendCurrent = <?php echo json_encode($weeklyTrendCurrent); ?>;
                var weeklyTrendPrevious = <?php echo json_encode($weeklyTrendPrevious); ?>;
                new Chart(ctxWeeklyTrend, {
                    type: 'line',
                    data: {
                        labels: weeklyTrendLabels,
                        datasets: [
                            {
                                label: 'Minggu Ini',
                                data: weeklyTrendCurrent,
                                borderColor: '#007bff',
                                backgroundColor: 'rgba(0, 123, 255, 0.15)',
                                fill: true,
                                tension: 0.3,
                                pointRadius: 4,
                                pointHoverRadius: 6
                            },
                            {
                                label: 'Minggu Lalu',
                                data: weeklyTrendPrevious,
                                borderColor: '#28a745',
                                backgroundColor: 'rgba(40, 167, 69, 0.15)',
                                fill: true,
                                tension: 0.3,
                                pointRadius: 4,
                                pointHoverRadius: 6
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return 'Rp' + value.toLocaleString('id-ID');
                                    }
                                }
                            },
                            x: {
                                ticks: {
                                    maxRotation: 45,
                                    minRotation: 45
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                position: 'bottom'
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return context.dataset.label + ': Rp ' + context.parsed.y.toLocaleString('id-ID');
                                    }
                                }
                            }
                        }
                    }
                });
            }

            var ctxMonthlyCompare = document.getElementById('monthlyComparisonChart');
            if (ctxMonthlyCompare) {
                ctxMonthlyCompare = ctxMonthlyCompare.getContext('2d');
                var monthlyComparisonLabels = <?php echo json_encode($monthlyComparisonLabels); ?>;
                var monthlyComparisonValues = <?php echo json_encode($monthlyComparisonValues); ?>;
                new Chart(ctxMonthlyCompare, {
                    type: 'bar',
                    data: {
                        labels: monthlyComparisonLabels,
                        datasets: [{
                            label: 'Pendapatan (Rp)',
                            data: monthlyComparisonValues,
                            backgroundColor: ['#28a745', '#007bff'],
                            borderColor: ['#1e7e34', '#0056b3'],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return 'Rp' + value.toLocaleString('id-ID');
                                    }
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                    }
                                }
                            }
                        }
                    }
                });
            }

            <?php if(!empty($pendapatanHarian)): ?>
            var ctx2 = document.getElementById('incomeChart');
            if (ctx2) {
                ctx2 = ctx2.getContext('2d');
                var rawLabels = <?php echo json_encode(array_keys($pendapatanHarian)); ?>;
                var formattedLabels = rawLabels.map(function(date) {
                    var d = new Date(date);
                    return d.toLocaleDateString('id-ID', { day: '2-digit', month: 'short' });
                });
                var incomeChart = new Chart(ctx2, {
                    type: 'bar',
                    data: {
                        labels: formattedLabels,
                        datasets: [{
                            label: 'Pendapatan Harian (Rp)',
                            data: <?php echo json_encode(array_values($pendapatanHarian)); ?>,
                            backgroundColor: '#007bff',
                            borderColor: '#0056b3',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        indexAxis: 'x',
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return 'Rp' + value.toLocaleString('id-ID');
                                    }
                                }
                            },
                            x: {
                                ticks: {
                                    maxRotation: 45,
                                    minRotation: 45
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top'
                            }
                        }
                    }
                });
            }
            <?php endif; ?>

            var ctxStatus = document.getElementById('statusChart');
            if (ctxStatus) {
                ctxStatus = ctxStatus.getContext('2d');
                var statusChart = new Chart(ctxStatus, {
                    type: 'pie',
                    data: {
                        labels: ['Belum Diproses', 'Sedang Diproses', 'Selesai', 'Lunas'],
                        datasets: [{
                            data: [
                                <?php echo e($transaksiBelumDiproses ?? 0); ?>,
                                <?php echo e($transaksiSedangDiproses ?? 0); ?>,
                                <?php echo e($transaksiSelesai ?? 0); ?>,
                                <?php echo e($totalTransaksiLunas ?? 0); ?>

                            ],
                            backgroundColor: [
                                '#dc3545',
                                '#ffc107',
                                '#17a2b8',
                                '#28a745'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 20,
                                    usePointStyle: true
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        var label = context.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        label += context.parsed + ' transaksi';
                                        return label;
                                    }
                                }
                            }
                        }
                    }
                });
            }

            var ctxPaymentMethod = document.getElementById('paymentMethodChart');
            if (ctxPaymentMethod) {
                ctxPaymentMethod = ctxPaymentMethod.getContext('2d');
                var paymentLabels = <?php echo json_encode($paymentMethodDistribution->pluck('metode_pembayaran')); ?>;
                var paymentData = <?php echo json_encode($paymentMethodDistribution->pluck('count')); ?>;
                var paymentColors = ['#007bff', '#28a745', '#ffc107', '#6f42c1', '#fd7e14', '#20c997'];

                new Chart(ctxPaymentMethod, {
                    type: 'doughnut',
                    data: {
                        labels: paymentLabels,
                        datasets: [{
                            data: paymentData,
                            backgroundColor: paymentColors.slice(0, paymentLabels.length),
                            borderColor: '#fff',
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    usePointStyle: true,
                                    padding: 16
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        var label = context.label || '';
                                        var value = context.parsed;
                                        if (label) {
                                            label += ': ';
                                        }
                                        label += value + ' transaksi';
                                        return label;
                                    }
                                }
                            }
                        }
                    }
                });
            }

            function scrollToDashboardCharts() {
                // Scroll ke Grafik Pendapatan Harian
                var chartsSection = document.querySelectorAll('.card');
                var targetSection = null;

                // Mencari card dengan title "Grafik Pendapatan Harian"
                for (var i = 0; i < chartsSection.length; i++) {
                    var cardHeader = chartsSection[i].querySelector('.card-header');
                    if (cardHeader && cardHeader.textContent.includes('Grafik Pendapatan Harian')) {
                        targetSection = chartsSection[i];
                        break;
                    }
                }

                // Jika tidak ketemu, cari berdasarkan ID atau fallback
                if (!targetSection) {
                    var incomeChart = document.getElementById('incomeChart');
                    if (incomeChart) {
                        targetSection = incomeChart.closest('.card');
                    }
                }

                // Scroll ke target dengan smooth animation
                if (targetSection) {
                    targetSection.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }

            function updateDateTime() {
                var now = new Date();
                var options = {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit'
                };
                document.getElementById('currentDateTime').textContent = now.toLocaleDateString('id-ID', options);
            }

            updateDateTime();
            setInterval(updateDateTime, 1000);
        </script>
    <?php else: ?>
        <script>
            function updateDateTime() {
                var now = new Date();
                var options = {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit'
                };
                document.getElementById('currentDateTime').textContent = now.toLocaleDateString('id-ID', options);
            }

            updateDateTime();
            setInterval(updateDateTime, 1000);
        </script>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\omahjawa1-Copy\resources\views/pages/dashboard/index.blade.php ENDPATH**/ ?>
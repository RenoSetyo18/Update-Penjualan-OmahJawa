<?php $__env->startSection('content'); ?>
<style>
@media screen {
    .main-sidebar {
        display: block !important;
        visibility: visible !important;
    }
    .main-sidebar .user-panel .image img {
        display: block !important;
        visibility: visible !important;
    }
}
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Laporan Menu Terlaris</h3>
                    <div class="card-tools">
                        <form method="GET" action="<?php echo e(route('laporan.menu-terlaris')); ?>" class="form-inline">
                            <input type="hidden" name="per_page" value="<?php echo e(request('per_page', 10)); ?>">
                            <div class="form-group mr-2">
                                <label for="start_date" class="mr-2">Tanggal Mulai:</label>
                                <input type="date" name="start_date" id="start_date" class="form-control" value="<?php echo e(request()->get('start_date', $start)); ?>">
                            </div>
                            <div class="form-group mr-2">
                                <label for="end_date" class="mr-2">Tanggal Akhir:</label>
                                <input type="date" name="end_date" id="end_date" class="form-control" value="<?php echo e(request()->get('end_date', $end)); ?>">
                            </div>
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="<?php echo e(route('laporan.cetak-menu-terlaris', ['start_date' => request()->get('start_date', $start), 'end_date' => request()->get('end_date', $end)])); ?>" class="btn btn-secondary ml-2">Cetak</a>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                    <span class="info-box-text">Periode</span>
                                    <span class="info-box-number text-sm"><?php echo e(\Carbon\Carbon::parse($start)->format('d M Y')); ?> - <?php echo e(\Carbon\Carbon::parse($end)->format('d M Y')); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <form method="GET" action="<?php echo e(route('laporan.menu-terlaris')); ?>" class="form-inline">
                            <input type="hidden" name="start_date" value="<?php echo e(request('start_date', $start)); ?>">
                            <input type="hidden" name="end_date" value="<?php echo e(request('end_date', $end)); ?>">
                            <label for="per_page" class="mr-2">Tampilkan:</label>
                            <select name="per_page" id="per_page" class="form-control form-control-sm" onchange="this.form.submit()">
                                <?php $__currentLoopData = [5, 10, 25, 50]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $size): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($size); ?>" <?php echo e(request('per_page', 10) == $size ? 'selected' : ''); ?>><?php echo e($size); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </form>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">Ranking</th>
                                    <th>Nama Menu</th>
                                    <th>Total Terjual</th>
                                    <th>Total Pendapatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $topMenus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td>
                                        <span class="badge badge-primary"><?php echo e($index + 1); ?></span>
                                    </td>
                                    <td><?php echo e($menu->menu->nama ?? 'N/A'); ?></td>
                                    <td><?php echo e(number_format($menu->total_jumlah)); ?></td>
                                    <td>Rp <?php echo e(number_format($menu->total_pendapatan)); ?></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="4" class="text-center">Tidak ada data menu dalam periode ini</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            Menampilkan <?php echo e($topMenus->firstItem() ?? 0); ?> sampai <?php echo e($topMenus->lastItem() ?? 0); ?> dari <?php echo e($topMenus->total()); ?> menu
                        </div>
                        <div>
                            <?php echo e($topMenus->onEachSide(1)->links('pagination::bootstrap-4')); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
                <script>
                    function printReport(url) {
                        fetch(url, { credentials: 'same-origin' })
                            .then(response => response.text())
                            .then(html => {
                                html = html.replace(/window\.print\(\);?/gi, '');
                                html = html.replace(/<body([^>]*)onload=(['"])?[^>]*window\.print\(\)[^>]*\2([^>]*)>/gi, '<body$1$3>');
                                html = html.replace(/onafterprint\s*=\s*(["']).*?\1/gi, '');

                                const iframe = document.createElement('iframe');
                                iframe.style.position = 'fixed';
                                iframe.style.right = '0';
                                iframe.style.bottom = '0';
                                iframe.style.width = '0';
                                iframe.style.height = '0';
                                iframe.style.border = '0';
                                document.body.appendChild(iframe);

                                const idoc = iframe.contentWindow.document;
                                idoc.open();
                                idoc.write(html);
                                idoc.close();

                                const doPrintOnce = () => {
                                    try {
                                        iframe.contentWindow.focus();
                                        iframe.contentWindow.print();
                                    } catch (e) {
                                        window.print();
                                    }
                                    setTimeout(() => {
                                        if (iframe && iframe.parentNode) iframe.parentNode.removeChild(iframe);
                                    }, 1000);
                                };

                                iframe.onload = doPrintOnce;
                                setTimeout(doPrintOnce, 800);
                            })
                            .catch(err => {
                                alert('Gagal memuat halaman cetak: ' + err);
                            });
                    }
                </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\omahjawa1-Copy\resources\views/pages/laporan/menu-terlaris.blade.php ENDPATH**/ ?>
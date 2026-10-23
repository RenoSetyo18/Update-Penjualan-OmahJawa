<?php $__env->startSection('header'); ?>
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Master Menu</h1>
        </div>
        <div class="col-sm-6 text-right">
            <a href="<?php echo e(route('master-menu.create')); ?>" class="btn btn-primary btn-create-action">
                <span class="btn-create-icon" aria-hidden="true"><i class="fas fa-plus"></i></span>
                Tambah Menu
            </a>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php
        $jenisSortOrder = request('sort') === 'jenis' && request('order') === 'asc' ? 'desc' : 'asc';
        $jenisSortUrl = route('master-menu.index', array_merge(request()->except(['sort', 'order', 'page']), [
            'sort' => 'jenis',
            'order' => $jenisSortOrder,
            'per_page' => request('per_page', 10)
        ]));
        $jenisSortIndicator = request('sort') === 'jenis'
            ? (request('order') === 'asc' ? ' <i class="fas fa-sort-up"></i>' : ' <i class="fas fa-sort-down"></i>')
            : ' <i class="fas fa-sort"></i>';
    ?>

    <?php if($message = Session::get('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo e($message); ?>

            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Menu</h3>
        </div>
        <div class="card-body">
            <form method="GET" class="form-inline mb-3">
                <label class="mr-2">Tampilkan per halaman:</label>
                <select name="per_page" class="form-control form-control-sm" onchange="this.form.submit()">
                    <?php $__currentLoopData = [5, 10, 25, 50]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $size): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($size); ?>" <?php echo e(request('per_page', 5) == $size ? 'selected' : ''); ?>><?php echo e($size); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <noscript>
                    <button type="submit" class="btn btn-sm btn-primary ml-2">Terapkan</button>
                </noscript>
            </form>
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Nama Menu</th>
                        <th>
                            <a href="<?php echo e($jenisSortUrl); ?>" style="display: block; width: 100%; height: 100%; color: inherit; text-decoration: none; cursor: pointer;">
                                Jenis<?php echo $jenisSortIndicator; ?>

                            </a>
                        </th>
                        <th>Harga</th>
                        <th>Deskripsi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $menus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($loop->iteration); ?></td>
                            <td><?php echo e($menu->nama); ?></td>
                            <td>
                                <span class="badge badge-primary"><?php echo e($menu->jenis->nama_jenis ?? 'N/A'); ?></span>
                            </td>
                            <td>Rp<?php echo e(number_format($menu->harga, 0, ',', '.')); ?></td>
                            <td><?php echo e(Str::limit($menu->deskripsi ?? '-', 50)); ?></td>
                            <td>
                                <?php if($menu->active): ?>
                                    <span class="badge badge-success">Aktif</span>
                                <?php else: ?>
                                    <span class="badge badge-secondary">Tidak Aktif</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?php echo e(route('master-menu.edit', $menu->id)); ?>" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="<?php echo e(route('master-menu.destroy', $menu->id)); ?>" method="POST" style="display:inline;" class="delete-confirm" data-item-name="menu '<?php echo e($menu->nama); ?>'">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data menu</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <div class="row mt-3 align-items-center">
                <div class="col-sm-6">
                    Menampilkan <?php echo e($menus->firstItem() ?? 0); ?> sampai <?php echo e($menus->lastItem() ?? 0); ?> dari <?php echo e($menus->total()); ?> data
                </div>
                <div class="col-sm-6 d-flex justify-content-end">
                    <?php
                        $currentPage = $menus->currentPage();
                        $lastPage = $menus->lastPage();
                        $pageLinks = [];

                        if ($lastPage <= 6) {
                            $pageLinks = range(1, $lastPage);
                        } else {
                            $pageLinks = [1, 2, 3, 4, 5];
                            if ($currentPage > 5) {
                                $pageLinks[] = 'ellipsis';
                            }
                            $pageLinks[] = $lastPage;
                        }

                        $buildPageUrl = function ($page) {
                            $query = request()->except('page');
                            if ($page > 1) {
                                $query['page'] = $page;
                            } else {
                                unset($query['page']);
                            }

                            return route('master-menu.index', $query);
                        };
                    ?>
                    <?php if($menus->hasPages()): ?>
                        <nav aria-label="Pagination">
                            <ul class="pagination mb-0">
                                <li class="page-item <?php echo e($menus->onFirstPage() ? 'disabled' : ''); ?>">
                                    <?php if($menus->onFirstPage()): ?>
                                        <span class="page-link" aria-hidden="true">&laquo;</span>
                                    <?php else: ?>
                                        <a class="page-link" href="<?php echo e($buildPageUrl($currentPage - 1)); ?>" aria-label="Sebelumnya">&laquo;</a>
                                    <?php endif; ?>
                                </li>

                                <?php $__currentLoopData = $pageLinks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($page === 'ellipsis'): ?>
                                        <li class="page-item disabled">
                                            <span class="page-link">...</span>
                                        </li>
                                    <?php else: ?>
                                        <li class="page-item <?php echo e($page == $currentPage ? 'active' : ''); ?>">
                                            <?php if($page == $currentPage): ?>
                                                <span class="page-link"><?php echo e($page); ?></span>
                                            <?php else: ?>
                                                <a class="page-link" href="<?php echo e($buildPageUrl($page)); ?>"><?php echo e($page); ?></a>
                                            <?php endif; ?>
                                        </li>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                <li class="page-item <?php echo e($menus->hasMorePages() ? '' : 'disabled'); ?>">
                                    <?php if($menus->hasMorePages()): ?>
                                        <a class="page-link" href="<?php echo e($buildPageUrl($currentPage + 1)); ?>" aria-label="Berikutnya">&raquo;</a>
                                    <?php else: ?>
                                        <span class="page-link" aria-hidden="true">&raquo;</span>
                                    <?php endif; ?>
                                </li>
                            </ul>
                        </nav>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\omahjawa1Deploy\resources\views/pages/master/menu/index.blade.php ENDPATH**/ ?>
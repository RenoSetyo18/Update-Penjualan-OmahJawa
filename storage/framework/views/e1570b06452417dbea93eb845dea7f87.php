<?php $__env->startSection('header'); ?>
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Master Jenis Menu</h1>
        </div>
        <div class="col-sm-6 text-right">
            <a href="<?php echo e(route('jenis.create')); ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Jenis
            </a>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
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
            <h3 class="card-title">Daftar Jenis Menu</h3>
        </div>
        <div class="card-body">
            <form method="GET" class="form-inline mb-3">
                <label class="mr-2">Tampilkan per halaman:</label>
                <select name="per_page" class="form-control form-control-sm" onchange="this.form.submit()">
                    <?php $__currentLoopData = [5, 10, 25, 50]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $size): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($size); ?>" <?php echo e(request('per_page', 10) == $size ? 'selected' : ''); ?>><?php echo e($size); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <noscript>
                    <button type="submit" class="btn btn-sm btn-primary ml-2">Terapkan</button>
                </noscript>
            </form>
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Jenis</th>
                        <th>Deskripsi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $jenis_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jenis): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($loop->iteration); ?></td>
                            <td><?php echo e($jenis->nama_jenis); ?></td>
                            <td><?php echo e($jenis->deskripsi ?? '-'); ?></td>
                            <td>
                                <a href="<?php echo e(route('jenis.edit', $jenis->id)); ?>" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="<?php echo e(route('jenis.destroy', $jenis->id)); ?>" method="POST" style="display:inline;" class="delete-confirm" data-item-name="jenis '<?php echo e($jenis->nama_jenis); ?>'">
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
                            <td colspan="4" class="text-center">Tidak ada data jenis menu</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    Menampilkan <?php echo e($jenis_list->firstItem() ?? 0); ?> sampai <?php echo e($jenis_list->lastItem() ?? 0); ?> dari <?php echo e($jenis_list->total()); ?> data
                </div>
                <div>
                    <?php echo e($jenis_list->links()); ?>

                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\omahjawa1-Copy\resources\views/pages/master/jenis/index.blade.php ENDPATH**/ ?>
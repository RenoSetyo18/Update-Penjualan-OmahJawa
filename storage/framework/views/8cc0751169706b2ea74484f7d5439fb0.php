<?php $__env->startSection('content'); ?>
<div class="container mt-4">
    <div class="card p-3">
        <h3>Data Transaksi</h3>

        <!-- ✅ Tombol Filter -->
        <div class="row mb-3">
            <div class="col text-right">
                <button id="btnTransaksi" class="btn btn-primary">Transaksi</button>
                <button id="btnHistory" class="btn btn-secondary">Histori</button>
            </div>
        </div>

        <!-- ✅ Filter tanggal & search -->
        <div class="row mb-2">
            <div class="col-md-3">
                <input type="date" id="startDate" class="form-control" placeholder="Dari">
            </div>
            <div class="col-md-3">
                <input type="date" id="endDate" class="form-control" placeholder="Sampai">
            </div>
            <div class="col-md-2">
                <button class="btn btn-secondary" id="resetFilter">Reset</button>
            </div>
        </div>

        <!-- ✅ Tabel -->
        <table class="table table-bordered" id="kokiTable">
            <thead class="bg-light">
                <tr>
                    <th>Waktu Pesan</th>
                    <th>Menu</th>
                    <th>Status</th>
                    <th>Detail</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $koki; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $showInTransaksi = in_array($item->status, ['belum_diproses', 'sedang_diproses', 'pending']);
                        $showInHistory = in_array($item->status, ['Completed', 'Lunas']);
                    ?>
                    <tr class="rowTransaksi <?php echo e($showInTransaksi ? 'transaksi' : ''); ?> <?php echo e($showInHistory ? 'history' : ''); ?>">
                        <td data-order="<?php echo e(strtotime($item->created_at)); ?>"><?php echo e($item->created_at->format('d-m-Y H:i')); ?></td>
                        <td>
                            <?php $__currentLoopData = $item->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php echo e($detail->menu->nama); ?> (<?php echo e($detail->jumlah); ?>),
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </td>
                        <td>
                            <?php switch($item->status):
                                case ('belum_diproses'): ?> <span class="badge badge-secondary">Belum Diproses</span> <?php break; ?>
                                <?php case ('sedang_diproses'): ?> <span class="badge badge-warning">Sedang Diproses</span> <?php break; ?>
                                <?php case ('pending'): ?> <span class="badge badge-info">Makanan Siap</span> <?php break; ?>
                                <?php case ('Completed'): ?> <span class="badge badge-primary">Selesai</span> <?php break; ?>
                                <?php case ('Lunas'): ?> <span class="badge badge-success">Lunas</span> <?php break; ?>
                                <?php default: ?> <span class="badge badge-light">-</span>
                            <?php endswitch; ?>
                        </td>
                        <td>
                            <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#detailModal-<?php echo e($item->id); ?>">Lihat</button>
                            <?php if($item->status === 'sedang_diproses'): ?>
                            <form action="<?php echo e(route('transaksi.updateStatus', $item->id)); ?>" method="POST" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="status" value="pending">
                                <button type="submit" class="btn btn-success btn-sm ml-1">Selesai</button>
                            </form>
                            <?php endif; ?>
                        </td>
                    </tr>

                    <!-- Modal di luar table -->
                    <div class="modal fade" id="detailModal-<?php echo e($item->id); ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Detail Pesanan</h5>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>Nomor Meja:</strong> <?php echo e($item->nomeja); ?></p>
                                    <p><strong>Atas Nama:</strong> <?php echo e($item->atasnama); ?></p>
                                    <h5>Menu Dipesan:</h5>
                                    <ul>
                                        <?php $__currentLoopData = $item->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li><?php echo e($detail->menu->nama); ?> - <?php echo e($detail->jumlah); ?> x Rp<?php echo e(number_format($detail->harga, 2)); ?></li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                </div>
                                <div class="modal-footer">
                                    <?php if($item->status === 'belum_diproses'): ?>
                                    <form action="<?php echo e(route('transaksi.updateStatus', $item->id)); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="status" value="sedang_diproses">
                                        <button type="submit" class="btn btn-warning">Proses</button>
                                    </form>
                                    <?php endif; ?>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready(function () {
        const table = $('#kokiTable').DataTable({
            paging: true,
            searching: true,
            ordering: true,
            info: true,
            pageLength: 5,
            order: [[0, 'desc']],
            columnDefs: [
                { orderable: false, targets: 3 }
            ],
            language: {
                paginate: { previous: "«", next: "»" },
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ Data",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ Data"
            }
        });

        // Default tampilkan hanya "Transaksi"
        $(".history").hide();
        $(".transaksi").show();

        $("#btnTransaksi").on('click', function () {
            $(".history").hide();
            $(".transaksi").show();
        });

        $("#btnHistory").on('click', function () {
            $(".transaksi").hide();
            $(".history").show();
        });

        $("#resetFilter").on('click', function () {
            $("#startDate, #endDate").val('');
            table.search('').draw();
        });
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\omahjawa1\resources\views/pages/koki/index.blade.php ENDPATH**/ ?>
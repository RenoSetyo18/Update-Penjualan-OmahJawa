<?php $__env->startSection('header'); ?>
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="kasir-page-title">Kasir</h1>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row kasir-page">

        <div class="col-md-12">
            <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
            <div class="card">
    <div class="card-body">
        <!-- Tab Status -->
        <div class="row mb-3">
            <div class="col">
                <style>
                    .kasir-page-title {
                        margin: 0;
                        color: #233b32;
                        font-size: 1.8rem;
                        font-weight: 700;
                        letter-spacing: -0.03em;
                    }
                    .kasir-page > .col-md-12 > .card {
                        overflow: hidden;
                        border: 1px solid #e8ece9;
                        border-radius: 16px;
                        box-shadow: 0 12px 32px rgba(28, 48, 39, 0.08);
                    }
                    .kasir-page > .col-md-12 > .card > .card-body {
                        padding: 1.5rem;
                    }
                    .tab-filter .nav-tabs {
                        gap: 0.5rem;
                        border: 0;
                    }
                    .tab-filter .nav-link {
                        margin: 0;
                        padding: 0.65rem 1rem;
                        border: 1px solid transparent;
                        border-radius: 10px;
                        color: #66736c;
                        font-weight: 600;
                        transition: background-color 0.2s ease, color 0.2s ease, border-color 0.2s ease, transform 0.2s ease;
                    }
                    .tab-filter .nav-link[data-status="belum"] {
                        color: #b4533c;
                    }
                    .tab-filter .nav-link[data-status="lunas"] {
                        color: #287a58;
                    }
                    .tab-filter .nav-link[data-status="semua"] {
                        color: #53665c;
                    }
                    .tab-filter .nav-link.active {
                        transform: translateY(-1px);
                    }
                    .tab-filter .nav-link[data-status="belum"].active {
                        color: #a6402c;
                        border-color: #f2ddd7;
                        background-color: #fff5f2;
                    }
                    .tab-filter .nav-link[data-status="lunas"].active {
                        color: #216a4b;
                        border-color: #d5e9dd;
                        background-color: #eff8f2;
                    }
                    .tab-filter .nav-link[data-status="semua"].active {
                        color: #354d40;
                        border-color: #dce6df;
                        background-color: #f2f6f3;
                    }
                    .tab-filter .nav-link:hover {
                        background-color: #f6f8f6;
                    }
                    .kasir-page .form-control {
                        min-height: 38px;
                        border-color: #dfe6e1;
                        border-radius: 8px;
                        box-shadow: none;
                    }
                    .kasir-page .form-control:focus {
                        border-color: #7da58d;
                        box-shadow: 0 0 0 0.2rem rgba(57, 111, 78, 0.12);
                    }
                    .kasir-date-filter {
                        display: grid;
                        grid-template-columns: repeat(4, minmax(0, 1fr));
                        gap: 0.75rem;
                        align-items: end;
                        margin-right: 0;
                        margin-left: 0;
                    }
                    .kasir-date-filter > * {
                        width: auto;
                        max-width: none;
                        padding-right: 0;
                        padding-left: 0;
                    }
                    .kasir-date-filter .form-group {
                        min-width: 0;
                        margin-bottom: 0;
                    }
                    .kasir-date-filter label {
                        display: block;
                        margin-bottom: 0.35rem;
                        font-weight: 600;
                    }
                    .kasir-date-filter .form-control,
                    .kasir-date-filter .btn {
                        width: 100%;
                        min-width: 0;
                        min-height: 38px;
                    }
                    .kasir-date-filter-actions {
                        display: flex;
                        gap: 0.5rem;
                        margin-bottom: 0;
                    }
                    .kasir-date-filter-actions .btn {
                        flex: 1 1 0;
                    }
                    .kasir-date-filter .kasir-date-filter-print {
                        justify-self: start;
                        width: fit-content;
                        max-width: 100%;
                        margin-bottom: 0;
                        padding-right: 1.1rem;
                        padding-left: 1.1rem;
                        white-space: nowrap;
                    }
                    @media (max-width: 991.98px) {
                        .kasir-date-filter {
                            grid-template-columns: repeat(2, minmax(0, 1fr));
                        }
                    }
                    @media (max-width: 575.98px) {
                        .kasir-date-filter {
                            grid-template-columns: minmax(0, 1fr);
                        }
                    }
                    .kasir-page .btn {
                        border-radius: 8px;
                        font-weight: 600;
                        transition: transform 0.15s ease, box-shadow 0.15s ease;
                    }
                    .kasir-page .btn:hover:not(:disabled) {
                        transform: translateY(-1px);
                        box-shadow: 0 4px 10px rgba(31, 55, 42, 0.12);
                    }
                    .kasir-page .btn-primary {
                        border-color: #315d46;
                        background-color: #315d46;
                    }
                    .kasir-page .btn-primary:hover,
                    .kasir-page .btn-primary:focus {
                        border-color: #244735;
                        background-color: #244735;
                    }
                    .kasir-page .btn-payment,
                    .kasir-page .btn-payment:hover,
                    .kasir-page .btn-payment:focus,
                    .kasir-page .btn-payment:active,
                    .kasir-page .btn-payment:not(:disabled):not(.disabled):active {
                        border-color: #315d46;
                        background-color: #315d46;
                        color: #fff;
                    }
                    .kasir-page .btn-payment:focus,
                    .kasir-page .btn-payment:not(:disabled):not(.disabled):active:focus {
                        box-shadow: 0 0 0 0.2rem rgba(49, 93, 70, 0.25);
                    }
                    .kasir-page .btn-outline-primary {
                        border-color: #315d46;
                        color: #315d46;
                    }
                    .kasir-page .btn-outline-primary:hover {
                        border-color: #315d46;
                        background-color: #315d46;
                        color: #fff;
                    }
                    .kasir-page .table {
                        margin-bottom: 0;
                        border-color: #e8ece9;
                    }
                    .kasir-page .table thead th {
                        padding: 0.9rem 0.75rem;
                        border-bottom: 1px solid #e2e9e4;
                        background: #f5f8f5;
                        color: #43574a;
                        font-size: 0.78rem;
                        font-weight: 700;
                        letter-spacing: 0.035em;
                        text-transform: uppercase;
                        vertical-align: middle;
                    }
                    .kasir-page .table td {
                        padding: 0.8rem 0.75rem;
                        border-color: #edf0ed;
                        color: #46534a;
                        vertical-align: middle;
                    }
                    .kasir-page .table tbody tr {
                        transition: background-color 0.15s ease;
                    }
                    .kasir-page .table tbody tr:hover {
                        background-color: #f8faf8;
                    }
                    .kasir-page .table .badge {
                        padding: 0.45rem 0.7rem;
                        border-radius: 999px;
                        font-size: 0.75rem;
                        font-weight: 600;
                    }
                    .kasir-page .table tfoot th {
                        padding: 0.9rem 0.75rem;
                        border-top: 1px solid #e2e9e4;
                        background: #f5f8f5;
                        color: #35483b;
                        vertical-align: middle;
                    }
                    .kasir-page .dataTables_wrapper {
                        color: #68756c;
                    }
                    .kasir-page .dataTables_wrapper .dataTables_filter input,
                    .kasir-page .dataTables_wrapper .dataTables_length select {
                        margin-left: 0.4rem;
                        border: 1px solid #dfe6e1;
                        border-radius: 8px;
                        padding: 0.35rem 0.55rem;
                    }
                    .kasir-page .dataTables_wrapper .dataTables_paginate .paginate_button {
                        border-radius: 7px !important;
                    }
                    .kasir-page .dataTables_wrapper .dataTables_paginate .paginate_button.current {
                        border-color: #315d46 !important;
                        background: #315d46 !important;
                        color: #fff !important;
                    }
                    .modal-content {
                        overflow: hidden;
                        border: 0;
                        border-radius: 14px;
                        box-shadow: 0 18px 50px rgba(24, 39, 30, 0.2);
                    }
                    .modal-header {
                        border-bottom-color: #edf0ed;
                    }
                    .modal-footer {
                        border-top-color: #edf0ed;
                    }
                    #paymentSuccessModal .payment-success-dialog {
                        max-width: 720px;
                    }
                    #paymentSuccessModal .payment-success-content {
                        border: 1px solid #e3ebe5;
                        border-radius: 18px;
                        background: #fff;
                        box-shadow: 0 24px 64px rgba(24, 39, 30, 0.22);
                    }
                    #paymentSuccessModal .payment-success-header {
                        position: relative;
                        justify-content: center;
                        padding: 1.35rem 3.5rem;
                        border-bottom: 1px solid #edf1ed;
                        background: linear-gradient(180deg, #f7fbf8 0%, #fff 100%);
                    }
                    #paymentSuccessModal .payment-success-header::before {
                        position: absolute;
                        top: 0;
                        right: 0;
                        left: 0;
                        height: 4px;
                        background: linear-gradient(90deg, #315d46 0%, #6e9a78 55%, #c7a56a 100%);
                        content: '';
                    }
                    #paymentSuccessModal .payment-success-title {
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        gap: 0.7rem;
                        color: #294b37;
                        font-size: 1.35rem;
                        font-weight: 700;
                    }
                    #paymentSuccessModal .payment-success-title-icon {
                        display: inline-flex;
                        width: 34px;
                        height: 34px;
                        align-items: center;
                        justify-content: center;
                        border-radius: 50%;
                        background: #e9f5ed;
                        color: #43805a;
                        font-size: 1rem;
                    }
                    #paymentSuccessModal .payment-success-header .close {
                        position: absolute;
                        top: 50%;
                        right: 1.25rem;
                        padding: 0.25rem;
                        color: #7b8980;
                        opacity: 0.8;
                        transform: translateY(-50%);
                        text-shadow: none;
                    }
                    #paymentSuccessModal .payment-success-header .close:hover {
                        color: #294b37;
                        opacity: 1;
                    }
                    #paymentSuccessModal .payment-success-body {
                        padding: 1.6rem 2rem 1.2rem;
                    }
                    #paymentSuccessModal .payment-success-status {
                        display: inline-flex;
                        align-items: center;
                        gap: 0.45rem;
                        margin-bottom: 1.25rem;
                        padding: 0.45rem 0.85rem;
                        border: 1px solid #d4e9da;
                        border-radius: 999px;
                        background: #f0f8f2;
                        color: #34704b;
                        font-size: 0.84rem;
                        font-weight: 700;
                    }
                    #paymentSuccessModal .payment-success-amount {
                        margin: 0 0 1.25rem;
                        padding: 1.4rem 1rem;
                        border: 1px solid #dcebe0;
                        border-radius: 15px;
                        background: linear-gradient(135deg, #f6fbf7 0%, #fff 100%);
                        box-shadow: inset 4px 0 0 #4b8c68;
                    }
                    #paymentSuccessModal .payment-success-amount-label {
                        margin-bottom: 0.35rem;
                        color: #78857c;
                        font-size: 0.9rem;
                        font-weight: 600;
                    }
                    #paymentSuccessModal .payment-success-amount-value {
                        margin: 0;
                        color: #315d46;
                        font-size: clamp(1.7rem, 4vw, 2.2rem);
                        font-weight: 700;
                        letter-spacing: -0.035em;
                        overflow-wrap: anywhere;
                    }
                    #paymentSuccessModal .payment-success-message {
                        max-width: 560px;
                        margin: 0 auto;
                        color: #68756c;
                        line-height: 1.6;
                    }
                    #paymentSuccessModal .payment-success-footer {
                        gap: 0.65rem;
                        padding: 0.4rem 2rem 1.6rem;
                    }
                    #paymentSuccessModal .payment-success-footer .btn {
                        min-height: 44px;
                        border-radius: 9px;
                        font-weight: 700;
                    }
                    #paymentSuccessModal .payment-success-print {
                        border-color: #4b8c68;
                        color: #36744f;
                    }
                    #paymentSuccessModal .payment-success-print:hover {
                        border-color: #36744f;
                        background: #eff8f2;
                        color: #285e3e;
                    }
                    #paymentSuccessModal .payment-success-finish {
                        border-color: #315d46;
                        background: linear-gradient(135deg, #3b6a50, #274a37);
                        box-shadow: 0 5px 13px rgba(49, 93, 70, 0.18);
                    }
                    #paymentSuccessModal .payment-success-finish:hover {
                        border-color: #244735;
                        background: linear-gradient(135deg, #315d46, #1f3e2d);
                    }
                    @media (max-width: 767.98px) {
                        .kasir-page > .col-md-12 > .card > .card-body {
                            padding: 1rem;
                        }
                        .kasir-page .dataTables_wrapper .dataTables_filter,
                        .kasir-page .dataTables_wrapper .dataTables_length {
                            margin-bottom: 0.75rem;
                            text-align: left;
                        }
                        .kasir-page .dataTables_wrapper .dataTables_filter input {
                            max-width: 170px;
                        }
                        #paymentSuccessModal .payment-success-body {
                            padding: 1.25rem 1rem 1rem;
                        }
                        #paymentSuccessModal .payment-success-footer {
                            flex-direction: column-reverse;
                            padding: 0.25rem 1rem 1.25rem;
                        }
                        #paymentSuccessModal .payment-success-footer .btn {
                            width: 100%;
                        }
                    }
                </style>
                <div class="tab-filter">
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <button class="nav-link active" type="button" data-status="belum" id="tabBelum">Belum Lunas</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" type="button" data-status="lunas" id="tabLunas">Lunas</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" type="button" data-status="semua" id="tabSemua">Semua</button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="kasir-date-filter mb-3">
            <div>
                <div class="form-group">
                    <label for="filterTanggalMulai">Dari</label>
                    <input type="date" id="filterTanggalMulai" class="form-control">
                </div>
            </div>
            <div>
                <div class="form-group">
                    <label for="filterTanggalSampai">Sampai</label>
                    <input type="date" id="filterTanggalSampai" class="form-control">
                </div>
            </div>
            <div>
                <div class="kasir-date-filter-actions">
                    <button id="showTanggal" class="btn btn-primary">Tampilkan</button>
                    <button id="resetTanggal" class="btn btn-secondary">Reset</button>
                </div>
            </div>
            <div>
                <button id="btnRekap" class="btn btn-outline-primary kasir-date-filter-print" data-toggle="modal" data-target="#rekapModal"><i class="fa fa-print mr-1"></i>Cetak Rekap</button>
            </div>
        </div>
        <table class="table table-bordered" id="transaksiTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Atas Nama</th>
                                <th>Total Harga</th>
                                <th>Status</th>
                                <th>Tanggal Transaksi</th>
                                <th>Detail Pesanan</th>
                                <th>Bayar</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $groupedTransaksis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $transaksi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr data-transaction-id="<?php echo e($transaksi->id); ?>">
                                    <td><?php echo e($index + 1); ?></td>
                                    <td><?php echo e($transaksi->atasnama); ?></td>
                                    <td>Rp<?php echo e(number_format($transaksi->totalharga, 2)); ?></td>
                                    <td>
                                        <span class="badge <?php echo e($transaksi->status === 'lunas' ? 'badge-success' : 'badge-warning'); ?>">
                                            <?php echo e(\App\Models\Transaksi::kasirStatusLabel($transaksi->status)); ?>

                                        </span>
                                    </td>

                                    <td data-order="<?php echo e(strtotime($transaksi->created_at)); ?>">
                                        <?php echo e(date('d-m-Y H:i', strtotime($transaksi->created_at))); ?>

                                    </td>
                                    <td>
                                        <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#detailModal-<?php echo e($transaksi->id); ?>">Lihat</button>
                                    </td>
                                    <td>
                                        <?php if($transaksi->status === 'lunas'): ?>
                                            <button class="btn btn-secondary" disabled>Lunas</button>
                                        <?php else: ?>
                                            <button class="btn btn-primary btn-payment" data-toggle="modal" data-target="#bayarModal-<?php echo e($transaksi->id); ?>">Bayar</button>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-nowrap">
                                        <a href="<?php echo e(route('kasir.transaksi.edit', $transaksi->id)); ?>" class="btn btn-sm btn-warning" aria-label="Edit transaksi <?php echo e($transaksi->kode); ?>" title="Edit transaksi">
                                            <i class="fas fa-edit" aria-hidden="true"></i>
                                        </a>
                                        <form action="<?php echo e(route('kasir.transaksi.destroy', $transaksi->id)); ?>" method="POST" class="delete-confirm d-inline" data-item-name="transaksi <?php echo e($transaksi->kode); ?><?php echo e($transaksi->status === 'lunas' ? ' yang sudah lunas' : ''); ?>">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-sm btn-danger" aria-label="Hapus transaksi <?php echo e($transaksi->kode); ?>" title="Hapus transaksi">
                                                <i class="fas fa-trash" aria-hidden="true"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Modal Detail Pesanan -->
                                <div class="modal fade" id="detailModal-<?php echo e($transaksi->id); ?>" tabindex="-1" aria-labelledby="detailModalLabel-<?php echo e($transaksi->id); ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="detailModalLabel-<?php echo e($transaksi->id); ?>">Detail Pesanan<?php echo e($transaksi->nomeja !== null ? ' - Meja ' . $transaksi->nomeja : ''); ?></h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
                                            </div>
                                            <div class="modal-body">
                                                <p><strong>Atas Nama:</strong> <?php echo e($transaksi->atasnama); ?></p>
                                                <h5>Menu Dipesan:</h5>
                                                <ul>
                                                    <?php $__currentLoopData = $transaksi->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <li><?php echo e($detail->menu->nama); ?><?php if(!empty($detail->note) && trim($detail->note) !== '-'): ?> (<?php echo e($detail->note); ?>)<?php endif; ?> - <?php echo e($detail->jumlah); ?> x Rp<?php echo e(number_format($detail->harga, 2)); ?></li>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </ul>
                                                <div class="border-top pt-2">
                                                    <div class="d-flex justify-content-between">
                                                        <span>Subtotal</span>
                                                        <span>Rp<?php echo e(number_format($transaksi->totalharga - ($transaksi->ppn_amount ?? 0), 2, ',', '.')); ?></span>
                                                    </div>
                                                    <div class="d-flex justify-content-between">
                                                        <span>PPN<?php echo e($transaksi->ppn_rate !== null ? ' (' . number_format($transaksi->ppn_rate, 2, ',', '.') . '%)' : ''); ?></span>
                                                        <span>Rp<?php echo e(number_format($transaksi->ppn_amount ?? 0, 2, ',', '.')); ?></span>
                                                    </div>
                                                    <div class="d-flex justify-content-between font-weight-bold">
                                                        <span>Total Harga</span>
                                                        <span>Rp<?php echo e(number_format($transaksi->totalharga, 2, ',', '.')); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-primary btn-print-saved-receipt" data-receipt-url="<?php echo e(route('kasir.struk.data', $transaksi->id)); ?>">
                                                    <i class="fa fa-print mr-1"></i> Cetak Struk
                                                </button>
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Modal Detail Pesanan -->

                                <!-- Modal Pembayaran -->
                                <div class="modal fade" id="bayarModal-<?php echo e($transaksi->id); ?>" tabindex="-1" aria-labelledby="bayarModalLabel-<?php echo e($transaksi->id); ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="bayarModalLabel-<?php echo e($transaksi->id); ?>">Pembayaran<?php echo e($transaksi->nomeja !== null ? ' - Meja ' . $transaksi->nomeja : ''); ?></h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
                                            </div>
                                            <div class="modal-body">
                                                <form class="form-bayar" data-id="<?php echo e($transaksi->id); ?>" action="<?php echo e(route('kasir.bayar', $transaksi->id)); ?>" method="POST" target="">

                                                    <?php echo csrf_field(); ?>
                                                    <div class="form-group">
                                                        <label>Total Harga</label>
                                                        <?php
                                                            // Total yang dibayar adalah pesanan yang sedang dipilih, bukan seluruh meja
                                                            $totalHargaGabungan = $transaksi->totalharga;
                                                        ?>
                                                        <input type="text" class="form-control total-harga"
                                                            id="total-harga-<?php echo e($transaksi->id); ?>"
                                                            value="<?php echo e($totalHargaGabungan); ?>" readonly>
                                                    </div>
                                                    <div class="small text-muted mb-3">
                                                        Subtotal Rp<?php echo e(number_format($transaksi->totalharga - ($transaksi->ppn_amount ?? 0), 2, ',', '.')); ?>

                                                        &middot; PPN<?php echo e($transaksi->ppn_rate !== null ? ' (' . number_format($transaksi->ppn_rate, 2, ',', '.') . '%)' : ''); ?>

                                                        Rp<?php echo e(number_format($transaksi->ppn_amount ?? 0, 2, ',', '.')); ?>

                                                    </div>
                                                    <div class="form-group">
                                                        <label>Metode Pembayaran</label>
                                                        <select class="form-control" name="metode_pembayaran" required>
                                                            <option value="">Pilih Metode Pembayaran</option>
                                                            <option value="Cash">Cash</option>
                                                            <option value="Dana">Dana</option>
                                                            <option value="BCA">BCA</option>
                                                            <option value="BRI">BRI</option>
                                                            <option value="Qris">Qris</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Jumlah Bayar</label>
                                                        <input type="number" class="form-control bayar-input"
                                                            id="bayar-<?php echo e($transaksi->id); ?>" name="jumlah_bayar" required
                                                            data-id="<?php echo e($transaksi->id); ?>">
                                                        <button type="button" class="btn btn-outline-primary btn-sm mt-2 btn-exact-payment"
                                                            data-id="<?php echo e($transaksi->id); ?>">Uang Pas</button>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Kembalian</label>
                                                        <input type="text" class="form-control kembalian"
                                                            id="kembalian-<?php echo e($transaksi->id); ?>" readonly>
                                                    </div>


                                                    <button type="submit" class="btn btn-success btn-block mt-3">Konfirmasi Pembayaran</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Modal Pembayaran -->
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="2" class="text-right">Total Harga Keseluruhan:</th>
                                <th>
                                    <input type="text" id="fieldTotalHargaKeseluruhan" class="form-control" style="width:160px;display:inline-block;" readonly value="Rp0,00">
                                </th>
                                <th colspan="5"></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Struk (global, satu saja di bawah) -->
    <div class="modal fade" id="modalStruk" tabindex="-1" aria-labelledby="modalStrukLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalStrukLabel">Struk Pembayaran</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body" style="height:80vh;">
                    <iframe id="iframeStruk" src="" style="width:100%;height:100%;border:none;"></iframe>
                </div>
            </div>
        </div>
    </div>

    <!-- Rekap Modal -->
    <div class="modal fade" id="rekapModal" tabindex="-1" aria-labelledby="rekapModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rekapModalLabel">Rekap Data Pembayaran</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="rekapContent">
                    <p>Memuat data rekap...</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" id="btnCetakRekap">Cetak Rekap</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Pembayaran Berhasil -->
    <div class="modal fade" id="paymentSuccessModal" tabindex="-1" aria-labelledby="paymentSuccessModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered payment-success-dialog">
            <div class="modal-content payment-success-content">
                <div class="modal-header payment-success-header">
                    <h5 class="modal-title payment-success-title" id="paymentSuccessModalLabel">
                        <span class="payment-success-title-icon"><i class="fa fa-check" aria-hidden="true"></i></span>
                        Pembayaran Berhasil
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body payment-success-body">
                    <div class="text-center">
                        <div class="payment-success-status"><i class="fa fa-check-circle" aria-hidden="true"></i>Berhasil</div>
                    </div>
                    <div class="payment-success-amount text-center">
                        <p class="payment-success-amount-label">Total Pembayaran</p>
                        <h3 class="payment-success-amount-value">Rp<span id="successTotalHarga"></span></h3>
                    </div>
                    <p class="payment-success-message text-center mb-0">Pembayaran telah dicatat. Silakan cetak struk atau lanjutkan untuk kembali ke menu Kasir.</p>
                </div>
                <div class="modal-footer border-0 justify-content-center payment-success-footer">
                    <button type="button" class="btn btn-outline-success px-4 payment-success-print" id="btnPrintStruk"><i class="fa fa-print mr-2"></i>Cetak Struk</button>
                    <button type="button" class="btn btn-success px-4 payment-success-finish" id="btnFinishPayment"><i class="fa fa-check mr-2"></i>Selesaikan Transaksi</button>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
$(document).ready(function () {
    console.log('Kasir page script loaded');
    let lastBayarModalId = null;
    let printerDevice = null;
    let printerCharacteristic = null;
    let printerDisconnectHandler = null;
    let isPrintingReceipt = false;

    const printerConfigKey = 'omah-jawa.bluetooth-printer';
    const printerConfig = {
        columns: 32,
        deviceId: ''
    };
    try {
        Object.assign(printerConfig, JSON.parse(localStorage.getItem(printerConfigKey) || '{}'));
    } catch (error) {
        console.warn('Konfigurasi printer tersimpan tidak dapat dibaca:', error);
    }

    const printerBleProfiles = [
        {
            serviceUuid: '000018f0-0000-1000-8000-00805f9b34fb',
            characteristicUuids: ['00002af1-0000-1000-8000-00805f9b34fb']
        },
        {
            serviceUuid: '0000ffe0-0000-1000-8000-00805f9b34fb',
            characteristicUuids: ['0000ffe1-0000-1000-8000-00805f9b34fb']
        },
        {
            serviceUuid: '0000ff00-0000-1000-8000-00805f9b34fb',
            characteristicUuids: [
                '0000ff02-0000-1000-8000-00805f9b34fb',
                '0000ff01-0000-1000-8000-00805f9b34fb'
            ]
        },
        {
            serviceUuid: '0000fff0-0000-1000-8000-00805f9b34fb',
            characteristicUuids: [
                '0000fff2-0000-1000-8000-00805f9b34fb',
                '0000fff1-0000-1000-8000-00805f9b34fb'
            ]
        }
    ];

    function formatReceiptAmount(amount) {
        const numericAmount = Number(amount);
        if (!Number.isFinite(numericAmount)) {
            throw new Error('Nilai nominal pada data struk tidak valid.');
        }

        return Math.round(numericAmount).toLocaleString('id-ID');
    }

    function formatReceiptDate(value) {
        const match = String(value || '').match(/^(\d{2})-(\d{2})-(\d{4})\s+(\d{2}:\d{2})$/);
        if (!match) return value || '-';

        const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        const month = monthNames[Number(match[2]) - 1] || match[2];
        return `${match[1]} ${month} ${match[3]}, ${match[4]}`;
    }

    function toPrinterAscii(value) {
        return String(value ?? '')
            .normalize('NFKD')
            .replace(/[\u0300-\u036f]/g, '')
            .replace(/[^\x20-\x7E]/g, '?');
    }

    function wrapReceiptText(value, width) {
        const words = toPrinterAscii(value).split(/\s+/).filter(Boolean);
        const lines = [];
        let line = '';

        words.forEach(word => {
            while (word.length > width) {
                if (line) {
                    lines.push(line);
                    line = '';
                }
                lines.push(word.slice(0, width));
                word = word.slice(width);
            }

            if (!line) {
                line = word;
            } else if (`${line} ${word}`.length <= width) {
                line += ` ${word}`;
            } else {
                lines.push(line);
                line = word;
            }
        });

        if (line) lines.push(line);
        return lines.length ? lines : [''];
    }

    function receiptPair(label, value, width) {
        const left = toPrinterAscii(label);
        const right = toPrinterAscii(value);
        if (left.length + right.length + 1 > width) {
            return `${left}\n${right.padStart(width)}`;
        }

        return `${left}${' '.repeat(width - left.length - right.length)}${right}`;
    }

    function buildEscPosReceipt(receipt) {
        if (!receipt || !Array.isArray(receipt.items)) {
            throw new Error('Data struk tidak lengkap.');
        }

        const widthInput = Number(printerConfig.columns);
        const width = Number.isInteger(widthInput) ? Math.min(48, Math.max(24, widthInput)) : 32;
        const separator = '-'.repeat(width);
        const paymentMethod = String(receipt.payment_method || '-');
        const paymentLabel = paymentMethod.toLowerCase() === 'cash' ? 'Tunai' : paymentMethod;
        const itemCount = receipt.items.reduce((count, item) => {
            const quantity = Number(item.quantity);
            if (!Number.isFinite(quantity) || quantity < 0) {
                throw new Error('Jumlah item pada struk tidak valid.');
            }
            return count + quantity;
        }, 0);
        const lines = [
            '\x1B\x40\x1B\x61\x01\x1B\x45\x01Omah Jawa',
            'Pusat',
            '',
            'Instagram : warung_omah_jawa',
            '\x1B\x45\x00\x1B\x61\x00' + separator,
            receiptPair('Kasir', receipt.cashier_name || 'Kasir', width),
            receiptPair('Waktu', formatReceiptDate(receipt.paid_at), width),
            receiptPair('No. Struk', receipt.transaction_code || '-', width),
            ...(receipt.status === 'lunas' ? [receiptPair('Bayar', paymentLabel, width)] : []),
            separator,
            `\x1B\x61\x01\x1B\x45\x01*** ${receipt.status_label || (receipt.status === 'lunas' ? 'Lunas' : 'Belum Lunas')} ***\x1B\x45\x00\x1B\x61\x00`,
            separator
        ];

        receipt.items.forEach(item => {
            const itemNameLines = wrapReceiptText(item.name || 'Item', width);
            lines.push('\x1B\x61\x00' + itemNameLines[0], ...itemNameLines.slice(1));
            const quantity = Number(item.quantity);
            const unitPrice = Number(item.price);
            const subtotal = Number(item.subtotal);
            if (![quantity, unitPrice, subtotal].every(Number.isFinite)) {
                throw new Error('Ada rincian item struk yang tidak valid.');
            }
            lines.push(receiptPair(
                `${formatReceiptAmount(unitPrice)} x ${quantity}`,
                formatReceiptAmount(subtotal),
                width
            ));
        });

        lines.push(
            separator,
            receiptPair('Subtotal', formatReceiptAmount(receipt.subtotal ?? receipt.total), width),
        );
        if (Number(receipt.ppn_amount) > 0) {
            lines.push(receiptPair(
                `PPN${receipt.ppn_rate !== null ? ` (${Number(receipt.ppn_rate).toFixed(2)}%)` : ''}`,
                formatReceiptAmount(receipt.ppn_amount),
                width
            ));
        } else {
            lines.push(receiptPair(`PPN${receipt.ppn_rate !== null ? ` (${Number(receipt.ppn_rate).toFixed(2)}%)` : ''}`, '0', width));
        }

        lines.push(
            separator,
            '\x1B\x45\x01' + receiptPair(`Total (${itemCount})`, formatReceiptAmount(receipt.total), width) + '\x1B\x45\x00',
            separator,
            ...(receipt.status === 'lunas'
                ? [
                    receiptPair('Bayar', formatReceiptAmount(receipt.paid), width),
                    receiptPair('Kembali', formatReceiptAmount(receipt.change), width)
                ]
                : [receiptPair('Status', 'Belum Lunas', width)]),
            '',
            '\x1B\x61\x01Panggonan Mewah, Regane Murah,',
            'Ning ati bungah',
            '',
            'Powered by Qasir',
            'www.qasir.id\x1B\x61\x00',
            '',
            '',
            '',
            '\x1D\x56\x00'
        );

        return new TextEncoder().encode(lines.join('\n'));
    }

    async function writePrinterBytes(bytes) {
        if (!printerDevice?.gatt?.connected || !printerCharacteristic) {
            throw new Error('Koneksi printer belum tersedia.');
        }

        const chunkSize = 20;
        for (let offset = 0; offset < bytes.length; offset += chunkSize) {
            const chunk = bytes.slice(offset, offset + chunkSize);
            if (printerCharacteristic.properties.write &&
                typeof printerCharacteristic.writeValueWithResponse === 'function') {
                await printerCharacteristic.writeValueWithResponse(chunk);
            } else if (printerCharacteristic.properties.writeWithoutResponse &&
                typeof printerCharacteristic.writeValueWithoutResponse === 'function') {
                await printerCharacteristic.writeValueWithoutResponse(chunk);
            } else if (printerCharacteristic.properties.write &&
                typeof printerCharacteristic.writeValue === 'function') {
                await printerCharacteristic.writeValue(chunk);
            } else {
                throw new Error('Characteristic printer tidak mendukung operasi write.');
            }
        }
    }

    async function findPrinterCharacteristic(server) {
        let services;
        try {
            services = await server.getPrimaryServices();
        } catch (error) {
            throw new Error('Printer tidak menyediakan layanan BLE GATT yang dapat diakses browser. Pastikan model ini mendukung BLE, bukan hanya Bluetooth Classic.');
        }

        for (const profile of printerBleProfiles) {
            const service = services.find(item => item.uuid === profile.serviceUuid);
            if (!service) continue;

            const characteristics = await service.getCharacteristics();
            const writable = characteristics.filter(characteristic =>
                characteristic.properties.write || characteristic.properties.writeWithoutResponse
            );
            const preferred = profile.characteristicUuids
                .map(uuid => writable.find(characteristic => characteristic.uuid === uuid))
                .find(Boolean);
            const characteristic = preferred || writable[0];
            if (characteristic) {
                return { serviceUuid: service.uuid, characteristic };
            }
        }

        throw new Error('Printer terhubung, tetapi profil BLE ESC/POS tidak dikenali. Model RPP02N memiliki beberapa varian; periksa apakah printer mendukung BLE (bukan Bluetooth Classic) atau berikan UUID dari manualnya.');
    }

    async function connectPrinter(device) {
        if (!device.gatt) {
            throw new Error('Perangkat ini tidak menyediakan BLE GATT. Printer Bluetooth Classic/RFCOMM tidak dapat dicetak langsung memakai Web Bluetooth.');
        }

        const server = await device.gatt.connect();
        let discovered;
        try {
            discovered = await findPrinterCharacteristic(server);
        } catch (error) {
            device.gatt.disconnect();
            throw error;
        }

        if (printerDevice && printerDisconnectHandler) {
            printerDevice.removeEventListener('gattserverdisconnected', printerDisconnectHandler);
        }
        if (printerDevice && printerDevice !== device && printerDevice.gatt?.connected) {
            printerDevice.gatt.disconnect();
        }
        printerDevice = device;
        printerCharacteristic = discovered.characteristic;
        printerDisconnectHandler = function () {
            printerCharacteristic = null;
        };
        printerDevice.addEventListener('gattserverdisconnected', printerDisconnectHandler);
        printerConfig.deviceId = device.id;
        try {
            localStorage.setItem(printerConfigKey, JSON.stringify(printerConfig));
        } catch (error) {
            console.warn('ID printer tidak dapat disimpan untuk koneksi berikutnya:', error);
        }
    }

    async function connectPrinterForPrint() {
        if (!window.isSecureContext || !navigator.bluetooth) {
            throw new Error('Bluetooth tidak tersedia di browser ini. Gunakan browser yang mendukung Web Bluetooth melalui HTTPS atau localhost.');
        }

        if (printerDevice?.gatt?.connected && printerCharacteristic) {
            return;
        }

        if (printerDevice) {
            await connectPrinter(printerDevice);
            return;
        }

        if (typeof navigator.bluetooth.requestDevice !== 'function') {
            throw new Error('Browser ini tidak menyediakan pemilihan printer Bluetooth.');
        }

        const deviceRequest = navigator.bluetooth.requestDevice({
            acceptAllDevices: true,
            optionalServices: printerBleProfiles.map(profile => profile.serviceUuid)
        });
        const device = await deviceRequest;
        await connectPrinter(device);
    }

    async function restorePrinterConnection() {
        if (!window.isSecureContext || !navigator.bluetooth ||
            typeof navigator.bluetooth.getDevices !== 'function') {
            return;
        }

        try {
            const devices = await navigator.bluetooth.getDevices();
            const device = printerConfig.deviceId
                ? devices.find(item => item.id === printerConfig.deviceId)
                : devices.length === 1 ? devices[0] : null;
            if (device) {
                await connectPrinter(device);
            }
        } catch (error) {
            console.warn('Koneksi printer yang diizinkan sebelumnya belum siap:', error);
        }
    }

    async function printReceipt(receipt) {
        if (isPrintingReceipt) return;
        isPrintingReceipt = true;
        $('.btn-print-saved-receipt, #btnPrintStruk').prop('disabled', true);

        try {
            await connectPrinterForPrint();
            await writePrinterBytes(buildEscPosReceipt(receipt));
        } catch (error) {
            console.error('Gagal mencetak struk Bluetooth:', error);
            if (typeof Swal !== 'undefined') {
                Swal.fire({ icon: 'error', title: 'Gagal mencetak', text: error.message });
            } else {
                alert(`Gagal mencetak: ${error.message}`);
            }
        } finally {
            isPrintingReceipt = false;
            $('.btn-print-saved-receipt, #btnPrintStruk').prop('disabled', false);
        }
    }

    restorePrinterConnection();

    const table = $('#transaksiTable').DataTable({
        paging: true,
        searching: true,
        ordering: true,
        info: true,
        lengthMenu: [5, 10, 25, 50, 100],
        order: [[4, 'desc']],
        columnDefs: [{ orderable: false, targets: [0, 5, 6, 7] }],
        language: {
            paginate: { previous: '«', next: '»' },
            search: 'Cari:',
            lengthMenu: 'Tampilkan _MENU_ Data',
            info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ Data'
        }
    });

    const requestedStatus = new URLSearchParams(window.location.search).get('status');
    if (['belum', 'lunas', 'semua'].includes(requestedStatus)) {
        $('.tab-filter .nav-link').removeClass('active');
        $('.tab-filter .nav-link[data-status="' + requestedStatus + '"]').addClass('active');
    }

    // Status filter function
    function statusFilter(settings, data, dataIndex) {
        const selectedTab = $('.tab-filter .nav-link.active').data('status') || 'semua';
        if (selectedTab === 'semua') {
            return true;
        }

        const statusHtml = data[3] || '';
        const statusText = $('<div>').html(statusHtml).text().trim().toLowerCase();
        if (selectedTab === 'belum') {
            return statusText.includes('belum lunas') || statusText.includes('belum bayar');
        }

        if (selectedTab === 'lunas') {
            return statusText === 'lunas';
        }

        return true;
    }

    $.fn.dataTable.ext.search.push(statusFilter);
    $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
        const min = $('#filterTanggalMulai').val();
        const max = $('#filterTanggalSampai').val();
        const tanggalStr = data[4];
        if (!min && !max) return true;

        const tglSplit = tanggalStr.split(' ')[0].split('-');
        const tgl = new Date(`${tglSplit[2]}-${tglSplit[1]}-${tglSplit[0]}`);
        const minDate = min ? new Date(min) : null;
        const maxDate = max ? new Date(max) : null;

        return (minDate === null || tgl >= minDate) && (maxDate === null || tgl <= maxDate);
    });

    $('#showTanggal').on('click', function () {
        table.draw();
        updateTotalHargaKeseluruhan();
    });

    $('#filterTanggalMulai, #filterTanggalSampai').on('change', function () {
        // filter tidak otomatis dijalankan saat tanggal berubah
    });

    $('.tab-filter .nav-link').on('click', function (e) {
        e.preventDefault();
        $('.tab-filter .nav-link').removeClass('active');
        $(this).addClass('active');
        table.draw();
        updateTotalHargaKeseluruhan();
    });

    $('#resetTanggal').on('click', function () {
        $('#filterTanggalMulai').val('');
        $('#filterTanggalSampai').val('');
        table.draw();
        updateTotalHargaKeseluruhan();
    });

    table.on('draw', updateTotalHargaKeseluruhan);
    table.draw();

    function updateTotalHargaKeseluruhan() {
        let total = 0;
        table.rows({ search: 'applied' }).every(function () {
            const hargaStr = this.data()[2];
            const angka = hargaStr.replace('Rp', '').replace(/\./g, '').replace(',00', '');
            total += parseInt(angka) || 0;
        });

        $('#fieldTotalHargaKeseluruhan').val(formatRupiah(total));
    }

    function formatRupiah(angka) {
        return 'Rp' + angka.toLocaleString('id-ID', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    $('.bayar-input').on('input', function () {
        const id = $(this).data('id');
        hitungKembalian(id);
    });

    $('.btn-exact-payment').on('click', function () {
        const id = $(this).data('id');
        $('#bayar-' + id).val($('#total-harga-' + id).val());
        hitungKembalian(id);
    });

    function hitungKembalian(id) {
        const bayar = parseFloat($('#bayar-' + id).val());
        const total = parseFloat($('#total-harga-' + id).val());

        if (!isNaN(bayar) && !isNaN(total)) {
            const kembalian = bayar - total;
            $('#kembalian-' + id).val(kembalian >= 0 ? kembalian.toFixed(2) : "Jumlah bayar kurang");
        } else {
            $('#kembalian-' + id).val('');
        }
    }

    // Use delegated submit handler in case forms are dynamic
    $(document).on('submit', '.form-bayar', function (e) {
        e.preventDefault();
        const $form = $(this);
        const id = $form.data('id');
        const action = $form.attr('action');
        const formData = new FormData($form[0]);

        fetch(action, {
            method: 'POST',
            body: formData,
            credentials: 'same-origin',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(async response => {
            const data = await response.json().catch(() => null);
            if (!response.ok) {
                console.error('Payment request failed:', response.status, data);
                const message = response.status >= 500
                    ? 'Pembayaran gagal diproses oleh server. Silakan coba lagi atau hubungi administrator.'
                    : (data?.message || 'Gagal memproses pembayaran.');
                throw new Error(message);
            }
            if (!data) {
                throw new Error('Respons server tidak valid. Silakan coba lagi.');
            }
            return data;
        })
        .then(data => {
            if (!data.success) {
                throw new Error(data.message || 'Gagal memproses pembayaran');
            }

            $('#bayarModal-' + id).modal('hide');
            $('#successTotalHarga').text(data.total_harga_formatted);
            $('#btnPrintStruk').data('receipt', data.receipt);
            $('#paymentSuccessModal').modal('show');

            table.rows().every(function () {
                const rowNode = this.node();
                const matchesOrder = String($(rowNode).data('transaction-id')) === String(id);
                if (matchesOrder) {
                    $(rowNode).find('td').eq(3).html('<span class="badge badge-success">Lunas</span>');
                    $(rowNode).find('td').eq(6).html('<button class="btn btn-secondary" disabled>Lunas</button>');
                }
            });

        })
        .catch(err => {
            console.error('Payment error:', err);
            if (typeof Swal !== 'undefined') {
                Swal.fire({ icon: 'error', title: 'Gagal', text: err.message || 'Terjadi kesalahan saat memproses pembayaran.' });
            } else {
                alert('Gagal memproses pembayaran: ' + (err.message || err));
            }
        });
    });

    $('.modal').on('hidden.bs.modal', function () {
        const $form = $(this).find('form');
        if ($form.length) {
            $form[0].reset();
            $(this).find('.kembalian').val('');
        }
    });

    $('#btnPrintStruk').on('click', function () {
        const receipt = $(this).data('receipt');
        if (!receipt) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({ icon: 'error', title: 'Gagal mencetak', text: 'Data struk tidak ditemukan. Muat ulang halaman lalu coba kembali.' });
            } else {
                alert('Data struk tidak ditemukan. Muat ulang halaman lalu coba kembali.');
            }
            return;
        }
        printReceipt(receipt);
    });

    $(document).on('click', '.btn-print-saved-receipt', async function () {
        const button = $(this);
        const receiptUrl = button.data('receipt-url');

        button.prop('disabled', true);
        try {
            const response = await fetch(receiptUrl, {
                credentials: 'same-origin',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await response.json();
            if (!response.ok || !data.success) {
                throw new Error(data.message || `HTTP ${response.status}`);
            }
            await printReceipt(data.receipt);
        } catch (error) {
            console.error('Gagal memuat data struk:', error);
            if (typeof Swal !== 'undefined') {
                Swal.fire({ icon: 'error', title: 'Gagal memuat struk', text: error.message });
            } else {
                alert(`Gagal memuat struk: ${error.message}`);
            }
        } finally {
            button.prop('disabled', false);
        }
    });

    $('#btnFinishPayment').on('click', function () {
        $('#paymentSuccessModal').modal('hide');
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'success',
                title: 'Pembayaran Berhasil',
                text: 'Transaksi telah selesai.'
            }).then(() => {
                window.location.href = '<?php echo e(route('kasir.index')); ?>';
            });
        } else {
            alert('Transaksi telah selesai.');
            window.location.href = '<?php echo e(route('kasir.index')); ?>';
        }
    });

    $('#btnCetak').on('click', function (e) {
    const dari = $('#filterTanggalMulai').val();
    const sampai = $('#filterTanggalSampai').val();

    let url = "<?php echo e(route('kasir.cetak.rekap')); ?>";
    if (dari && sampai) {
        url += `?dari=${encodeURIComponent(dari)}&sampai=${encodeURIComponent(sampai)}`;
    }

    $(this).attr('href', url);
});

    $('#btnRekap').on('click', function () {
        buildRekapContent();
        $('#rekapModal').modal('show');
    });

    $('#btnCetakRekap').on('click', function () {
        // Prefer range computed when modal was built; fall back to inputs
        const rekapRange = $(this).data('rekapRange') || {};
        const dari = rekapRange.dari || $('#filterTanggalMulai').val() || '';
        const sampai = rekapRange.sampai || $('#filterTanggalSampai').val() || '';
        const selectedTab = $('.tab-filter .nav-link.active').data('status') || 'semua';
        const mode = selectedTab === 'lunas' ? 'history' : 'transaction';
        let url = '<?php echo e(route('kasir.cetak.rekap')); ?>';
        const params = new URLSearchParams();
        if (dari) params.set('dari', dari);
        if (sampai) params.set('sampai', sampai);
        params.set('mode', mode);
        const query = params.toString();
        if (query) url += '?' + query;
        window.open(url, '_blank');
    });

    function buildRekapContent() {
        // Ambil semua baris dari DOM dan ekstrak isi kolom sebagai array string
        const nodes = table.rows().nodes().toArray();
        const rowsArray = nodes.map(function (node) {
            return $(node).find('td').map(function () { return $(this).html().trim(); }).get();
        });
        const min = $('#filterTanggalMulai').val();
        const max = $('#filterTanggalSampai').val();
        const rows = rowsArray.filter(function (row) {
            const tanggalStr = row[4] || '';
            if (!min && !max) return true;

            const tglSplit = tanggalStr.split(' ')[0].split('-');
            const tgl = new Date(`${tglSplit[2]}-${tglSplit[1]}-${tglSplit[0]}`);
            const minDate = min ? new Date(min) : null;
            const maxDate = max ? new Date(max) : null;

            return (minDate === null || tgl >= minDate) && (maxDate === null || tgl <= maxDate);
        });
        let totalTransaksi = rows.length;
        let totalHarga = 0;
        let statusCounts = {
            belumBayar: 0,
            lunas: 0
        };

        function stripTags(html) {
            const tmp = document.createElement('div');
            tmp.innerHTML = html;
            return tmp.textContent || tmp.innerText || '';
        }

        function mapStatusLabel(statusText) {
            const value = stripTags(statusText).trim().toLowerCase();
            if (value.includes('belum')) {
                return 'Belum Lunas';
            }
            if (value.includes('lunas')) {
                return 'Lunas';
            }
            return 'Belum Lunas';
        }

        let rowsHtml = '';
        for (let i = 0; i < rows.length; i++) {
            const row = rows[i];
            const rawStatus = row[3] || '';
            const statusText = stripTags(rawStatus);
            const mappedStatus = mapStatusLabel(statusText);
            const hargaStr = stripTags(row[2]);
            const hargaAngka = parseInt(hargaStr.replace('Rp', '').replace(/\./g, '').replace(',00', '')) || 0;
            totalHarga += hargaAngka;

            if (mappedStatus === 'Lunas') {
                statusCounts.lunas += 1;
            } else {
                statusCounts.belumBayar += 1;
            }

            rowsHtml += `<tr>
                <td>${i + 1}</td>
                <td>${stripTags(row[1])}</td>
                <td>${hargaStr}</td>
                <td>${mappedStatus}</td>
            </tr>`;
        }

        const contentHtml = `
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="card border-secondary mb-3">
                        <div class="card-body p-3">
                            <h5 class="card-title">Total Transaksi</h5>
                            <p class="card-text h4">${totalTransaksi}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-secondary mb-3">
                        <div class="card-body p-3">
                            <h5 class="card-title">Total Penerimaan</h5>
                            <p class="card-text h4">Rp${totalHarga.toLocaleString('id-ID')}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
    <div class="col-12">
        <h5 class="fw-bold mb-3">Status Transaksi</h5>
    </div>
</div>
<div class="row mb-3">
    <div class="col-md-6 mb-3">
        <div class="p-3 rounded bg-light border h-100 text-center">
            <div class="text-muted small mb-1">Belum Lunas</div>
            <div class="h3 fw-bold text-warning">
                ${statusCounts.belumBayar}
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-3">
        <div class="p-3 rounded bg-light border h-100 text-center">
            <div class="text-muted small mb-1">Lunas</div>
            <div class="h3 fw-bold text-success">
                ${statusCounts.lunas}
            </div>
        </div>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped table-bordered">
        <thead class="table-light">
            <tr>
                <th>No</th>
                <th>Atas Nama</th>
                <th>Total Harga</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            ${rowsHtml}
        </tbody>
    </table>
</div>
        `;

        $('#rekapContent').html(contentHtml);

        // Hitung periode aktual dari rows yang ditampilkan pada preview dan simpan di tombol cetak
        try {
            let minDate = null;
            let maxDate = null;
            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const tanggalStr = (row[4] || '').split(' ')[0];
                if (!tanggalStr) continue;
                const parts = tanggalStr.split('-');
                const dt = new Date(`${parts[2]}-${parts[1]}-${parts[0]}`);
                if (!minDate || dt < minDate) minDate = dt;
                if (!maxDate || dt > maxDate) maxDate = dt;
            }

            const formatISO = (d) => d ? d.toISOString().slice(0,10) : null;
            $('#btnCetakRekap').data('rekapRange', {
                dari: formatISO(minDate),
                sampai: formatISO(maxDate)
            });
        } catch (err) {
            // ignore
        }
    }

});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\omahjawa1Deploy\resources\views/pages/kasir/index.blade.php ENDPATH**/ ?>
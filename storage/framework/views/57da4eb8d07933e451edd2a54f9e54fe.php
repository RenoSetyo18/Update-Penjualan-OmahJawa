<?php $__env->startSection('header'); ?>
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 font-weight-normal">Data Transaksi</h1>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <!-- Tab Status -->
                <div class="row mb-3">
                    <div class="col">
                        <style>
                            .tab-filter .nav-tabs {
                                border-bottom: 1px solid #dee2e6;
                            }
                            .tab-filter .nav-link {
                                border: 1px solid #dee2e6;
                                border-bottom: none;
                                color: #495057;
                                margin-bottom: -1px;
                                transition: background-color 0.2s ease, color 0.2s ease, border-color 0.2s ease;
                            }
                            .tab-filter .nav-link[data-status="belum"] {
                                color: #dc3545;
                                border-color: #dc3545;
                            }
                            .tab-filter .nav-link[data-status="selesai"] {
                                color: #28a745;
                                border-color: #28a745;
                            }
                            .tab-filter .nav-link[data-status="semua"] {
                                color: #0d6efd;
                                border-color: #0d6efd;
                            }
                            .tab-filter .nav-link:not(.active) {
                                background-color: transparent;
                            }
                            .tab-filter .nav-link.active {
                                background-color: rgba(13, 110, 253, 0.08);
                                border-color: #dee2e6 #dee2e6 #fff;
                                font-weight: 600;
                            }
                            .tab-filter .nav-link[data-status="belum"].active {
                                color: #dc3545;
                                border-color: #dc3545;
                                background-color: rgba(220, 53, 69, 0.08);
                            }
                            .tab-filter .nav-link[data-status="selesai"].active {
                                color: #28a745;
                                border-color: #28a745;
                                background-color: rgba(40, 167, 69, 0.08);
                            }
                            .tab-filter .nav-link[data-status="semua"].active {
                                color: #0d6efd;
                                border-color: #0d6efd;
                                background-color: rgba(13, 110, 253, 0.08);
                            }
                            .tab-filter .nav-link:hover {
                                background-color: rgba(0, 0, 0, 0.04);
                            }
                            .table-responsive-custom {
                                width: 100%;
                                max-width: 100%;
                                overflow-x: auto;
                                -webkit-overflow-scrolling: touch;
                            }
                            #kolomData {
                                width: 100%;
                                min-width: 100%;
                                table-layout: fixed;
                            }
                            #kolomData th,
                            #kolomData td {
                                white-space: normal;
                                word-break: break-word;
                                overflow-wrap: anywhere;
                            }
                            #kolomData th {
                                font-size: 0.95rem;
                            }
                            @media (max-width: 992px) {
                                #kolomData th,
                                #kolomData td {
                                    font-size: 0.82rem;
                                }
                                #kolomData .btn {
                                    white-space: normal;
                                }
                            }
                        </style>
                        <div class="tab-filter">
                            <ul class="nav nav-tabs">
                                <li class="nav-item">
                                    <button class="nav-link active" type="button" data-status="belum" id="tabBelum">Belum Selesai</button>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link" type="button" data-status="selesai" id="tabSelesai">Selesai</button>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link" type="button" data-status="semua" id="tabSemua">Semua</button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="row mb-3 align-items-center">
                    <div class="col-md-8 d-flex align-items-center">
                        <label for="filterTanggalMulai" class="mr-2">Dari:</label>
                        <input type="date" id="filterTanggalMulai" class="form-control" style="width: 170px;">
                        <label for="filterTanggalSampai" class="ml-2 mr-2">Sampai:</label>
                        <input type="date" id="filterTanggalSampai" class="form-control" style="width: 170px;">
                        <button id="showTanggal" class="btn btn-primary btn-sm ml-2">Tampilkan</button>
                        <button id="resetTanggal" class="btn btn-secondary btn-sm ml-2">Reset</button>
                    </div>
                    <div class="col-md-4 text-right">
                        <button id="btnRekap" class="btn btn-outline-primary" data-toggle="modal" data-target="#rekapModal"><i class="fa fa-print mr-1"></i>Cetak Rekap</button>
                    </div>
                </div>

                <!-- Tabel Data -->
                <div class="table-responsive table-responsive-custom">
                    <table class="table table-bordered" id="kolomData">
                        <thead>
                            <tr>
                                <th style="display:none;">Priority</th>
                                <th>Kode</th>
                                <th>Nomor Meja</th>
                                <th>Atas Nama</th>
                                <th>Total Items</th>
                                <th>Status</th>
                                <th>Tanggal Transaksi</th>
                                <th>Detail</th>
                            </tr>
                        </thead>
                    <tbody>
                        <?php $__currentLoopData = $transaksis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaksi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr data-transaksi-id="<?php echo e($transaksi->id); ?>">
                            <td style="display:none;" class="priority-col"><?php echo e($transaksi->status === 'makanan_siap' ? 0 : 1); ?></td>
                            <td><?php echo e($transaksi->kode); ?></td>
                            <td><?php echo e($transaksi->nomeja); ?></td>
                            <td><?php echo e($transaksi->atasnama); ?></td>
                            <td><?php echo e($transaksi->details->count()); ?></td>
                            <td>
                                <?php if(in_array($transaksi->status, ['selesai', 'lunas'])): ?>
                                    <span class="badge badge-primary">Selesai</span>
                                <?php elseif($transaksi->status === 'makanan_siap'): ?>
                                    <span class="badge badge-info">Makanan Siap</span>
                                <?php elseif($transaksi->status === 'sedang_diproses'): ?>
                                    <span class="badge badge-secondary">Belum Siap</span>
                                <?php elseif($transaksi->status === 'belum_diproses' || $transaksi->status === 'Pesanan Online'): ?>
                                    <span class="badge badge-secondary">Belum Siap</span>
                                <?php else: ?>
                                    <span class="badge badge-light"><?php echo e(\App\Models\Transaksi::transaksiStatusLabel($transaksi->status)); ?></span>
                                <?php endif; ?>
                            </td>
                            <td data-order="<?php echo e(strtotime($transaksi->created_at)); ?>" data-search="<?php echo e(date('d-m-Y H:i', strtotime($transaksi->created_at))); ?>">
                                <?php echo e(date('d-m-Y H:i', strtotime($transaksi->created_at))); ?>

                            </td>
                            <td>
                                <button class="btn btn-info btn-detail" data-toggle="modal" data-target="#detailModal-<?php echo e($transaksi->id); ?>">Lihat</button>
                                <?php if(auth()->user()->role === 'koki' && ($transaksi->status === 'belum_diproses' || $transaksi->status === 'Pesanan Online')): ?>
                                    <form action="<?php echo e(route('transaksi.updateStatus', $transaksi->id)); ?>" method="POST" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="status" value="sedang_diproses">
                                        <button type="submit" class="btn btn-warning ml-1">Mulai Proses</button>
                                    </form>
                                <?php elseif($transaksi->status === 'makanan_siap'): ?>
                                    <form action="<?php echo e(route('transaksi.updateStatus', $transaksi->id)); ?>" method="POST" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="status" value="selesai">
                                        <?php if($firstReadyToDeliver && $firstReadyToDeliver->id === $transaksi->id): ?>
                                            <button type="submit" class="btn btn-success ml-1">Konfirmasi Diantar</button>
                                        <?php else: ?>
                                            <button type="submit" class="btn btn-secondary ml-1" disabled title="Tunggu pesanan sebelumnya diantar">Dalam Antrian</button>
                                        <?php endif; ?>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                    <tfoot>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail dan Modal Edit Menu (Di luar tabel) -->
<?php $__currentLoopData = $transaksis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaksi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<!-- Modal Detail Transaksi -->
<div class="modal fade" id="detailModal-<?php echo e($transaksi->id); ?>" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Transaksi</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p><strong>Nomor Meja:</strong> <?php echo e($transaksi->nomeja); ?></p>
                <p><strong>Atas Nama:</strong> <?php echo e($transaksi->atasnama); ?></p>
                <p><strong>Total Harga:</strong> <span id="total-harga-<?php echo e($transaksi->id); ?>">Rp<?php echo e(number_format($transaksi->totalharga, 2, ',', '.')); ?></span></p>
                <p><strong>Status:</strong>
                    <?php if(in_array($transaksi->status, ['selesai', 'lunas'])): ?>
                        <span class="badge badge-primary">Selesai</span>
                    <?php elseif($transaksi->status === 'makanan_siap'): ?>
                        <span class="badge badge-info">Makanan Siap</span>
                    <?php elseif($transaksi->status === 'sedang_diproses' || $transaksi->status === 'belum_diproses' || $transaksi->status === 'Pesanan Online'): ?>
                        <span class="badge badge-warning">Belum Siap</span>
                    <?php else: ?>
                        <span class="badge badge-secondary"><?php echo e(\App\Models\Transaksi::transaksiStatusLabel($transaksi->status)); ?></span>
                    <?php endif; ?>
                </p>
                <h5 class="mt-3">Menu Dipesan:</h5>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>Menu</th>
                                <th>Catatan</th>
                                <th>Jumlah</th>
                                <th>Harga</th>
                                <th>Status Menu</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $transaksi->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($detail->menu->nama); ?></td>
                                <td>
                                    <?php if(!empty($detail->note) && trim($detail->note) !== '-'): ?>
                                        <small class="badge badge-info"><?php echo e($detail->note); ?></small>
                                    <?php else: ?>
                                        <small class="text-muted">-</small>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo e($detail->jumlah); ?></td>
                                <td>Rp<?php echo e(number_format($detail->subtotal, 2, ',', '.')); ?></td>
                                <td>
                                    <?php $mstatus = $detail->menu_status ?? \App\Models\DetailTransaksi::MENU_STATUS_BELUM_SIAP; ?>
                                    <?php if($mstatus === \App\Models\DetailTransaksi::MENU_STATUS_BELUM_SIAP): ?>
                                        <span class="badge badge-warning">Belum Siap</span>
                                    <?php else: ?>
                                        <span class="badge badge-success">Siap</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <?php if($transaksi->status === 'belum_diproses'): ?>
                                            <button class="btn btn-sm btn-warning"
                                                data-toggle="modal"
                                                data-target="#editMenuModal-<?php echo e($transaksi->id); ?>-<?php echo e($detail->id); ?>"
                                                title="Edit atau hapus menu">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        <?php else: ?>
                                            <button class="btn btn-sm btn-secondary" disabled title="Hanya bisa edit menu saat status Belum Diproses">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        <?php endif; ?>

                                        <?php if(auth()->user()->role === 'koki' && in_array($transaksi->status, ['sedang_diproses', 'makanan_siap'])): ?>
                                            <?php if($mstatus === \App\Models\DetailTransaksi::MENU_STATUS_BELUM_SIAP): ?>
                                                <button class="btn btn-sm btn-success" onclick="markMenuReady('<?php echo e($transaksi->id); ?>', '<?php echo e($detail->id); ?>', '<?php echo e($detail->menu->nama); ?>')" title="Tandai menu siap">
                                                    <i class="fas fa-check"></i> Siap
                                                </button>
                                            <?php else: ?>
                                                <button class="btn btn-sm btn-info" onclick="markMenuNotReady('<?php echo e($transaksi->id); ?>', '<?php echo e($detail->id); ?>', '<?php echo e($detail->menu->nama); ?>')" title="Tandai menu belum siap">
                                                    <i class="fas fa-undo"></i> Ubah
                                                </button>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Menu -->
<?php $__currentLoopData = $transaksi->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="modal fade" id="editMenuModal-<?php echo e($transaksi->id); ?>-<?php echo e($detail->id); ?>" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Menu - <?php echo e($detail->menu->nama); ?></h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="editMenuForm-<?php echo e($transaksi->id); ?>-<?php echo e($detail->id); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="form-group">
                        <label for="note-<?php echo e($transaksi->id); ?>-<?php echo e($detail->id); ?>"><strong>Catatan</strong></label>
                        <textarea class="form-control"
                            id="note-<?php echo e($transaksi->id); ?>-<?php echo e($detail->id); ?>"
                            name="note"
                            rows="3"
                            placeholder="Masukkan catatan untuk menu ini (contoh: tidak pedas, nasi putih, dll)"><?php echo e($detail->note ?? ''); ?></textarea>
                        <small class="form-text text-muted">Catatan opsional untuk custom pesanan</small>
                    </div>
                    <div class="alert alert-info" role="alert">
                        <strong>Info Menu:</strong><br>
                        Menu: <?php echo e($detail->menu->nama); ?><br>
                        Jumlah: <?php echo e($detail->jumlah); ?> x Rp<?php echo e(number_format($detail->harga, 2, ',', '.')); ?><br>
                        Subtotal: Rp<?php echo e(number_format($detail->subtotal, 2, ',', '.')); ?>

                    </div>
                </form>

                <!-- Section untuk Batalkan Menu -->
                <?php if($detail->jumlah > 1): ?>
                <hr>
                <div class="form-group">
                    <label for="cancelQty-<?php echo e($transaksi->id); ?>-<?php echo e($detail->id); ?>"><strong>Batalkan Berapa Menu?</strong></label>
                    <div class="input-group">
                        <input type="number" class="form-control"
                            id="cancelQty-<?php echo e($transaksi->id); ?>-<?php echo e($detail->id); ?>"
                            name="cancel_qty"
                            min="1"
                            max="<?php echo e($detail->jumlah); ?>"
                            value="1">
                        <div class="input-group-append">
                            <span class="input-group-text">/ <?php echo e($detail->jumlah); ?></span>
                        </div>
                    </div>
                    <small class="form-text text-muted">Pilih jumlah menu yang ingin dibatalkan (1-<?php echo e($detail->jumlah); ?>)</small>
                </div>
                <?php endif; ?>
            </div>
            <div class="modal-footer">
                <?php if($detail->jumlah > 1): ?>
                    <button type="button" class="btn btn-danger"
                        onclick="deleteMenu('<?php echo e($transaksi->id); ?>', '<?php echo e($detail->id); ?>', document.getElementById('cancelQty-<?php echo e($transaksi->id); ?>-<?php echo e($detail->id); ?>').value)">
                        <i class="fas fa-trash mr-1"></i>Batalkan Menu Terpilih
                    </button>
                <?php else: ?>
                    <button type="button" class="btn btn-danger"
                        onclick="deleteMenu('<?php echo e($transaksi->id); ?>', '<?php echo e($detail->id); ?>', 1)">
                        <i class="fas fa-trash mr-1"></i>Batalkan Menu
                    </button>
                <?php endif; ?>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary"
                    onclick="updateMenu('<?php echo e($transaksi->id); ?>', '<?php echo e($detail->id); ?>')">
                    <i class="fas fa-save mr-1"></i>Simpan Catatan
                </button>
            </div>
        </div>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<!-- Rekap Modal -->
<div class="modal fade" id="rekapModal" tabindex="-1" aria-labelledby="rekapModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rekapModalLabel">Rekap Data Pesanan</h5>
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

<!-- Container untuk modal yang akan ditampilkan -->
<div id="modalsContainer"></div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    // Global variable untuk DataTables instance
    let tableInstance = null;

    $(document).ready(function () {
        tableInstance = $('#kolomData').DataTable({
            destroy: true,
            paging: true,
            searching: true,
            ordering: true,
            info: true,
            lengthMenu: [5, 10, 25, 50],
            order: [[0, 'asc'], [6, 'asc']],
            columnDefs: [
                { orderable: false, targets: [7] },
                { visible: false, targets: [0] },
                { type: 'date', targets: [6] }
            ],
            language: {
                paginate: { previous: '«', next: '»' },
                search: 'Cari:',
                lengthMenu: 'Tampilkan _MENU_ Data',
                info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ Data'
            }
        });

        // Status filter function
        function statusFilter(settings, data, dataIndex) {
            const selectedTab = $('.tab-filter .nav-link.active').data('status') || 'semua';
            const status = (data[5] || '').toLowerCase();

            if (selectedTab === 'semua') {
                return true;
            }

            if (selectedTab === 'belum') {
                return !status.includes('lunas') && !status.includes('selesai');
            }

            if (selectedTab === 'selesai') {
                return status.includes('lunas') || status.includes('selesai');
            }

            return true;
        }

        $.fn.dataTable.ext.search.push(statusFilter);
        $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
            const min = $('#filterTanggalMulai').val();
            const max = $('#filterTanggalSampai').val();
            const tanggalStr = data[6];
            if (!min && !max) return true;

            const tglSplit = tanggalStr.split(' ')[0].split('-');
            const tgl = new Date(`${tglSplit[2]}-${tglSplit[1]}-${tglSplit[0]}`);
            const minDate = min ? new Date(min) : null;
            const maxDate = max ? new Date(max) : null;

            return (!minDate || tgl >= minDate) && (!maxDate || tgl <= maxDate);
        });

        function updateTotalPendapatan() {
            let totalItems = 0;

            tableInstance.rows({ search: 'applied' }).every(function () {
                const rowData = this.data();
                const itemCount = parseInt(rowData[4], 10);
                totalItems += Number.isNaN(itemCount) ? 0 : itemCount;
            });

            if ($('#fieldTotalPendapatan').length) {
                $('#fieldTotalPendapatan').val(totalItems.toString());
            }
        }

        $('#showTanggal').on('click', function () {
            tableInstance.draw();
            updateTotalPendapatan();
        });

        $('#filterTanggalMulai, #filterTanggalSampai').on('change', function () {
            // Do not auto-apply filter until button Tampilkan is clicked
        });

        $('.tab-filter .nav-link').on('click', function (e) {
            e.preventDefault();
            $('.tab-filter .nav-link').removeClass('active');
            $(this).addClass('active');
            tableInstance.draw();
            updateTotalPendapatan();
        });

        $('#resetTanggal').on('click', function () {
            $('#filterTanggalMulai, #filterTanggalSampai').val('');
            tableInstance.draw();
            updateTotalPendapatan();
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
            const mode = selectedTab === 'selesai' ? 'history' : 'transaction';
            const tabSession = new URLSearchParams(window.location.search).get('tab_session');
            let url = '<?php echo e(route('transaksi.cetak.rekap')); ?>';
            const params = new URLSearchParams();
            if (dari) params.set('dari', dari);
            if (sampai) params.set('sampai', sampai);
            params.set('mode', mode);
            if (tabSession) params.set('tab_session', tabSession);
            const query = params.toString();
            if (query) url += '?' + query;

            // Open about:blank first to preserve the user gesture and avoid popup blockers
            const newWin = window.open('about:blank', '_blank');
            if (newWin) {
                try {
                    newWin.location.href = url;
                } catch (err) {
                    window.open(url, '_blank');
                }
            } else {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({ icon: 'warning', title: 'Pop-up diblokir', html: 'Pop-up untuk preview diblokir. <br>Silakan izinkan pop-up atau buka URL berikut di tab baru:<br><code>' + url + '</code>' });
                } else {
                    alert('Pop-up diblokir. Buka URL ini di tab baru: ' + url);
                }
            }
        });

        function buildRekapContent() {
            // Ambil semua baris dari DOM, baca tanggal dari kolom ke-6 (index 6)
            const minInput = $('#filterTanggalMulai').val();
            const maxInput = $('#filterTanggalSampai').val();

            const indexes = tableInstance.rows().indexes().toArray();
            const rows = indexes.map(function (idx) {
                const raw = [];
                const text = [];
                // Asumsikan jumlah kolom setidaknya 8 (sesuai tabel)
                for (let col = 0; col <= 7; col++) {
                    try {
                        const cellNode = tableInstance.cell(idx, col).node();
                        raw.push($(cellNode).html().trim());
                        text.push($(cellNode).text().trim());
                    } catch (e) {
                        raw.push('');
                        text.push('');
                    }
                }
                return { raw: raw, text: text };
            }).filter(function (r) {
                const tanggalText = (r.text[6] || '').split(' ')[0];
                if (!minInput && !maxInput) return true;
                if (!tanggalText) return false;

                const parts = tanggalText.split('-');
                if (parts.length < 3) return false;
                const dt = new Date(`${parts[2]}-${parts[1]}-${parts[0]}`);
                const minDate = minInput ? new Date(minInput) : null;
                const maxDate = maxInput ? new Date(maxInput) : null;

                return (!minDate || dt >= minDate) && (!maxDate || dt <= maxDate);
            });
            let totalTransaksi = rows.length;
            let totalItems = 0;
            let statusCounts = {
                belumSiap: 0,
                makananSiap: 0,
                selesai: 0
            };

            function stripTags(html) {
                const tmp = document.createElement('div');
                tmp.innerHTML = html == null ? '' : String(html);
                return tmp.textContent || tmp.innerText || '';
            }

            function escapeHtml(value) {
                return String(value == null ? '' : value)
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#39;');
            }

            function mapStatusLabel(statusText) {
                const value = stripTags(statusText).trim().toLowerCase();
                if (value.includes('selesai') || value.includes('lunas')) {
                    return 'Selesai';
                }
                if (value.includes('makanan siap')) {
                    return 'Makanan Siap';
                }
                return 'Belum Siap';
            }

            let rowsHtml = '';
            try {
                for (let i = 0; i < rows.length; i++) {
                    const row = rows[i];
                    const rawStatus = row.raw[5] || '';
                    const statusText = stripTags(rawStatus);
                    const mappedStatus = mapStatusLabel(statusText);
                    const totalItemsValue = parseInt(row.text[4] || '0', 10) || 0;
                    totalItems += totalItemsValue;

                    if (mappedStatus === 'Selesai') {
                        statusCounts.selesai += 1;
                    } else if (mappedStatus === 'Makanan Siap') {
                        statusCounts.makananSiap += 1;
                    } else {
                        statusCounts.belumSiap += 1;
                    }

                    rowsHtml += `<tr>
                        <td>${escapeHtml(row.text[1] || '')}</td>
                        <td>${escapeHtml(row.text[2] || '')}</td>
                        <td>${escapeHtml(row.text[3] || '')}</td>
                        <td>${totalItemsValue}</td>
                        <td>${escapeHtml(mappedStatus)}</td>
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
                                    <h5 class="card-title">Total Item Pesanan</h5>
                                    <p class="card-text h4">${totalItems}</p>
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
                        <div class="col-md-4 mb-3">
                            <div class="p-3 rounded bg-light border h-100 text-center">
                                <div class="text-muted small mb-1">Belum Siap</div>
                                <div class="h4 fw-bold">${statusCounts.belumSiap}</div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="p-3 rounded bg-light border h-100 text-center">
                                <div class="text-muted small mb-1">Makanan Siap</div>
                                <div class="h4 fw-bold">${statusCounts.makananSiap}</div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="p-3 rounded bg-light border h-100 text-center">
                                <div class="text-muted small mb-1">Selesai</div>
                                <div class="h4 fw-bold">${statusCounts.selesai}</div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Nomor Meja</th>
                                    <th>Atas Nama</th>
                                    <th>Total Items</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${rowsHtml}
                            </tbody>
                        </table>
                    </div>
                `;

                document.getElementById('rekapContent').innerHTML = contentHtml;

                // Hitung periode yang sebenarnya dari rows yang ditampilkan pada preview
                try {
                    let minDate = null;
                    let maxDate = null;
                    for (let i = 0; i < rows.length; i++) {
                        const row = rows[i];
                        const tanggalStr = (row.text[6] || '').split(' ')[0];
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
            } catch (error) {
                console.error('Gagal membangun preview rekap transaksi:', error);
                document.getElementById('rekapContent').innerHTML = '<div class="alert alert-warning mb-0">Preview rekap transaksi tidak bisa dibangun. Silakan coba muat ulang halaman atau buka cetak langsung.</div>';
            }
        }

        tableInstance.on('draw', updateTotalPendapatan);
        tableInstance.draw();
    });

    // Fungsi untuk update catatan menu
    function updateMenu(transaksiId, detailId) {
        const note = $('#note-' + transaksiId + '-' + detailId).val();

        $.ajax({
            url: '/transaksi/' + transaksiId + '/detail/' + detailId,
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                note: note
            },
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: response.message,
                    timer: 2000
                }).then(() => {
                    location.reload();
                });
            },
            error: function(xhr) {
                let message = 'Terjadi kesalahan saat mengupdate menu';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: message
                });
            }
        });
    }

    // Fungsi untuk delete/batalkan menu
    function deleteMenu(transaksiId, detailId, cancelQty) {
        cancelQty = parseInt(cancelQty) || 1;

        let confirmText = 'Apakah Anda yakin ingin membatalkan menu ini? Menu akan dihapus dari pesanan.';
        if (cancelQty > 1) {
            confirmText = `Apakah Anda yakin ingin membatalkan ${cancelQty} menu ini? Menu akan dihapus dari pesanan.`;
        }

        Swal.fire({
            title: 'Batalkan Menu?',
            text: confirmText,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Batalkan',
            cancelButtonText: 'Tidak'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/transaksi/' + transaksiId + '/detail/' + detailId,
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        cancel_qty: cancelQty
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message,
                            timer: 2000
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        let message = 'Terjadi kesalahan saat membatalkan menu';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: message
                        });
                    }
                });
            }
        });
    }

    // Fungsi untuk mark menu siap
    function markMenuReady(transaksiId, detailId, menuName) {
        Swal.fire({
            title: 'Konfirmasi',
            text: `Apakah menu "${menuName}" sudah siap untuk dihidangkan?`,
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Menu Siap',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/transaksi/' + transaksiId + '/menu-status/' + detailId,
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        menu_status: 'siap'
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: `${menuName} sudah ditandai siap!`,
                            timer: 2000
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        let message = 'Terjadi kesalahan saat menandai menu siap';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: message
                        });
                    }
                });
            }
        });
    }

    // Fungsi untuk mark menu belum siap
    function markMenuNotReady(transaksiId, detailId, menuName) {
        Swal.fire({
            title: 'Konfirmasi',
            text: `Ubah status "${menuName}" menjadi belum siap?`,
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#ffc107',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Ubah',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/transaksi/' + transaksiId + '/menu-status/' + detailId,
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        menu_status: 'belum_siap'
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: `Status ${menuName} diubah menjadi belum siap`,
                            timer: 2000
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        let message = 'Terjadi kesalahan saat mengubah status menu';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: message
                        });
                    }
                });
            }
        });
    }

    // ==========================================
    // REAL-TIME UPDATE & NOTIFICATION SYSTEM
    // ==========================================

    // Polling untuk real-time update status menu
    let pollingInterval = null;

    function startRealTimeUpdate(transaksiId) {
        // Jangan jalankan polling jika sudah ada
        if (pollingInterval) clearInterval(pollingInterval);

        // Polling setiap 3 detik untuk update menu status
        pollingInterval = setInterval(function() {
            $.ajax({
                url: '/transaksi/' + transaksiId + '/menu-status',
                method: 'GET',
                success: function(response) {
                    if (response.success) {
                        updateMenuStatusUI(response.details, transaksiId);
                    }
                },
                error: function(xhr) {
                    // Silent error - jangan tampilkan dialog untuk polling background
                    console.error('Polling error:', xhr);
                }
            });
        }, 3000);
    }

    function updateMenuStatusUI(details, transaksiId) {
        const modal = $('#detailModal-' + transaksiId);
        if (!modal.length || !modal.hasClass('show')) return;

        details.forEach(function(detail) {
            const row = modal.find(`[data-detail-id="${detail.id}"]`);
            if (row.length) {
                const statusCell = row.find('.menu-status-cell');
                const statusBadge = statusCell.find('span');

                // Update badge
                statusBadge.removeClass('badge-warning badge-success');
                if (detail.menu_status === 'siap') {
                    statusBadge.addClass('badge-success').text('Siap');
                } else {
                    statusBadge.addClass('badge-warning').text('Belum Siap');
                }
            }
        });
    }

    // Polling untuk notifikasi menu siap (khusus pelayan)
    let notificationPollingInterval = null;
    let shownNotifications = {};

    function startPelayanNotificationPolling() {
        // Cek role user
        const userRole = '<?php echo e(auth()->user()->role); ?>';
        if (userRole !== 'pelayan') return;

        // Polling setiap 5 detik
        notificationPollingInterval = setInterval(function() {
            $.ajax({
                url: '/transaksi/ready-menus-notification',
                method: 'GET',
                success: function(response) {
                    if (response.success && response.count > 0) {
                            // Segera refresh tabel (fetch latest) agar status transaksi berubah otomatis
                            $.ajax({
                                url: '/transaksi/all-for-refresh',
                                method: 'GET',
                                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                                success: function(resp) {
                                    if (resp.success && resp.transaksis) {
                                        updateTableRowsFromAPI(resp.transaksis);
                                    }
                                }
                            });

                            response.ready_menus.forEach(function(notification) {
                                // Hanya tampilkan notifikasi yang belum pernah ditampilkan
                                if (!shownNotifications[notification.id]) {
                                    showNotification(notification);
                                    shownNotifications[notification.id] = true;

                                    // Hapus dari tracking setelah 30 detik
                                    setTimeout(function() {
                                        delete shownNotifications[notification.id];
                                    }, 30000);
                                }
                            });
                        }
                },
                error: function(xhr) {
                    console.error('Notification polling error:', xhr);
                }
            });
        }, 5000);
    }

    // Function untuk menampilkan notifikasi popup di kanan atas
    function showNotification(notification) {
        const notificationHtml = `
            <div class="notification-toast" style="
                position: fixed;
                top: 20px;
                right: 20px;
                background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
                color: white;
                padding: 15px 20px;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                max-width: 350px;
                z-index: 9999;
                animation: slideInRight 0.5s ease-out;
                font-weight: 500;
            ">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-check-circle" style="font-size: 24px;"></i>
                    <div>
                        <strong>Menu Siap!</strong><br>
                        <small>${notification.message}</small>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()" style="
                        background: none;
                        border: none;
                        color: white;
                        font-size: 18px;
                        cursor: pointer;
                        margin-left: auto;
                    ">×</button>
                </div>
            </div>
        `;

        $('body').append(notificationHtml);

        // Auto-remove setelah 5 detik
        setTimeout(function() {
            $('.notification-toast').fadeOut(500, function() {
                $(this).remove();
            });
        }, 5000);

        // Tampilkan sound notification jika browser support
        playNotificationSound();
    }

    // Function untuk play sound notification
    function playNotificationSound() {
        try {
            // Buat audio element dengan base64 encoded beep sound
            const audioContext = new (window.AudioContext || window.webkitAudioContext)();
            const oscillator = audioContext.createOscillator();
            const gain = audioContext.createGain();

            oscillator.connect(gain);
            gain.connect(audioContext.destination);

            oscillator.frequency.value = 1000; // Hz
            oscillator.type = 'sine';

            gain.gain.setValueAtTime(0.3, audioContext.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.5);

            oscillator.start(audioContext.currentTime);
            oscillator.stop(audioContext.currentTime + 0.5);
        } catch (e) {
            console.log('Sound notification not supported');
        }
    }

    // ==========================================
    // POLLING TABLE REFRESH (Auto Update Tabel)
    // ==========================================
    // POLLING TABLE REFRESH (Auto Update Tabel)
    // ==========================================

    let tableRefreshPollingInterval = null;

    function startTableRefreshPolling() {
        // Polling setiap 5 detik untuk check perubahan data
        tableRefreshPollingInterval = setInterval(function() {
            $.ajax({
                url: '/transaksi/all-for-refresh',
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(response) {
                    if (response.success && response.transaksis) {
                        // Update table rows dengan data terbaru
                        updateTableRowsFromAPI(response.transaksis);
                    }
                },
                error: function(xhr) {
                    console.log('Table refresh polling error:', xhr);
                }
            });
        }, 5000); // Check setiap 5 detik
    }

    function stripHtml(html) {
        const div = document.createElement('div');
        div.innerHTML = html || '';
        return div.textContent.trim();
    }

    function getBadgeClass(html) {
        const match = String(html || '').match(/class\s*=\s*"([^"]*)"/i);
        return match ? match[1].trim() : '';
    }

    function updateTableRowsFromAPI(newTransaksis) {
        if (!tableInstance) return;

        const dataMap = {};
        newTransaksis.forEach(function(t) {
            dataMap[String(t.id)] = t;
        });

        let hasUpdated = false;

        tableInstance.rows().every(function () {
            const rowNode = $(this.node());
            const transaksiId = String(rowNode.data('transaksi-id'));
            const newData = dataMap[transaksiId];

            if (!newData) {
                return;
            }

            const cells = rowNode.find('td');
            if (cells.length < 8) {
                return;
            }

            const newPriority = newData.status === 'makanan_siap' ? 0 : 1;
            const priorityCell = cells.eq(0);
            const codeCell = cells.eq(1);
            const tableCell = cells.eq(2);
            const nameCell = cells.eq(3);
            const totalItemsCell = cells.eq(4);
            const statusCell = cells.eq(5);
            const createdAtCell = cells.eq(6);

            let rowChanged = false;

            if (String(priorityCell.text()).trim() !== String(newPriority)) {
                priorityCell.text(newPriority);
                rowChanged = true;
            }

            if (String(codeCell.text()).trim() !== String(newData.kode).trim()) {
                codeCell.text(newData.kode);
                rowChanged = true;
            }

            if (String(tableCell.text()).trim() !== String(newData.nomeja).trim()) {
                tableCell.text(newData.nomeja);
                rowChanged = true;
            }

            if (String(nameCell.text()).trim() !== String(newData.atasnama).trim()) {
                nameCell.text(newData.atasnama);
                rowChanged = true;
            }

            if (Number(totalItemsCell.text().trim() || 0) !== Number(newData.total_items)) {
                totalItemsCell.text(newData.total_items);
                rowChanged = true;
            }

            const statusBadge = statusCell.find('span');
            const newStatusClass = `badge ${newData.status_badge_class}`.trim();
            const newStatusText = String(newData.status_label).trim();

            if (!statusBadge.length) {
                statusCell.html(`<span class="${newStatusClass}">${newStatusText}</span>`);
                rowChanged = true;
            } else {
                if (String(statusBadge.text()).trim() !== newStatusText) {
                    statusBadge.text(newStatusText);
                    rowChanged = true;
                }
                if (String(statusBadge.attr('class')).trim() !== newStatusClass) {
                    statusBadge.attr('class', newStatusClass);
                    rowChanged = true;
                }
            }

            if (String(createdAtCell.text()).trim() !== String(newData.created_at).trim()) {
                createdAtCell.text(newData.created_at);
                rowChanged = true;
            }

            if (rowChanged) {
                tableInstance.row(rowNode).invalidate('dom');
                hasUpdated = true;
            }
        });

        if (hasUpdated) {
            tableInstance.draw(false);
        }
    }

    function stopTableRefreshPolling() {
        if (tableRefreshPollingInterval) {
            clearInterval(tableRefreshPollingInterval);
            tableRefreshPollingInterval = null;
        }
    }

    // Start notification polling on page load
    $(document).ready(function() {
        startPelayanNotificationPolling();
        startTableRefreshPolling(); // Tambah polling untuk refresh tabel

        // Start real-time update when modal is opened
        $(document).on('show.bs.modal', '[id^="detailModal-"]', function(e) {
            const modalId = $(this).attr('id');
            const transaksiId = modalId.replace('detailModal-', '');
            startRealTimeUpdate(transaksiId);

            // Fetch and update modal detail dengan data terbaru
            loadModalDetailData(transaksiId);
        });

        // Stop real-time update when modal is closed
        $(document).on('hide.bs.modal', '[id^="detailModal-"]', function(e) {
            if (pollingInterval) {
                clearInterval(pollingInterval);
                pollingInterval = null;
            }
        });
    });

    // Function untuk load detail modal data dari server
    function loadModalDetailData(transaksiId) {
        $.ajax({
            url: '/transaksi/' + transaksiId + '/detail-modal',
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    updateModalContent(transaksiId, response.transaksi, response.details);
                }
            },
            error: function(xhr) {
                console.log('Error loading modal detail:', xhr);
            }
        });
    }

    // Function untuk update modal content dengan data terbaru
    function updateModalContent(transaksiId, transaksi, details) {
        const modal = $('#detailModal-' + transaksiId);
        if (!modal.length) return;

        // Update header info
        modal.find('strong:contains("Total Harga:")').parent().find('span').text('Rp' + transaksi.totalharga);

        // Update tabel menu details
        const tbody = modal.find('table tbody');
        if (!tbody.length) return;

        // Clear existing rows
        tbody.html('');

        // Build new rows dengan data terbaru
        details.forEach(function(detail) {
            const noteHtml = detail.note !== '-'
                ? `<small class="badge badge-info">${detail.note}</small>`
                : `<small class="text-muted">-</small>`;

            const statusBadgeClass = detail.menu_status_badge;
            const statusLabel = detail.menu_status_label;

            // Build edit button berdasarkan status transaksi
            const editButtonHtml = transaksi.status === 'belum_diproses'
                ? `<button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editMenuModal-${transaksiId}-${detail.id}" title="Edit atau hapus menu"><i class="fas fa-edit"></i></button>`
                : `<button class="btn btn-sm btn-secondary" disabled title="Hanya bisa edit menu saat status Belum Diproses"><i class="fas fa-edit"></i></button>`;

            const row = `
                <tr data-detail-id="${detail.id}">
                    <td>${detail.menu_nama}</td>
                    <td>${noteHtml}</td>
                    <td>${detail.jumlah}</td>
                    <td>Rp${detail.subtotal}</td>
                    <td class="menu-status-cell"><span class="badge ${statusBadgeClass}">${statusLabel}</span></td>
                    <td>
                        <div class="btn-group btn-group-sm" role="group">
                            ${editButtonHtml}
                            ${getActionButtonsHtml(transaksiId, detail)}
                        </div>
                    </td>
                </tr>
            `;
            tbody.append(row);
        });
    }

    // Helper function untuk build action buttons
    function getActionButtonsHtml(transaksiId, detail) {
        const userRole = '<?php echo e(auth()->user()->role); ?>';
        if (userRole !== 'koki') return '';

        if (detail.menu_status === 'belum_siap') {
            return `<button class="btn btn-sm btn-success" onclick="markMenuReady('${transaksiId}', '${detail.id}', '${detail.menu_nama}')" title="Tandai menu siap">
                <i class="fas fa-check"></i> Siap
            </button>`;
        } else {
            return `<button class="btn btn-sm btn-info" onclick="markMenuNotReady('${transaksiId}', '${detail.id}', '${detail.menu_nama}')" title="Tandai menu belum siap">
                <i class="fas fa-undo"></i> Ubah
            </button>`;
        }
    }

    // Add animation CSS
    if (!document.getElementById('notificationAnimations')) {
        const style = document.createElement('style');
        style.id = 'notificationAnimations';
        style.textContent = `
            @keyframes slideInRight {
                from {
                    transform: translateX(400px);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
        `;
        document.head.appendChild(style);
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\omahjawa1-Copy\resources\views/pages/transaksi/index.blade.php ENDPATH**/ ?>
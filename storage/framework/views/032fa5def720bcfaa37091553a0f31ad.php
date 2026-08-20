<?php $__env->startSection('header'); ?>
<div class="row mb-2">
    <div class="col-sm-6">
        <h1>Master Koki</h1>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
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

                <div class="mb-3 d-flex align-items-center flex-wrap">
                    <label for="startDate" class="mr-2 mb-0">Dari:</label>
                    <input type="date" id="startDate" class="form-control form-control-sm mr-2 mb-2" style="width: 170px;">
                    <label for="endDate" class="mr-2 mb-0">Sampai:</label>
                    <input type="date" id="endDate" class="form-control form-control-sm mr-2 mb-2" style="width: 170px;">
                    <button class="btn btn-primary btn-sm mr-2 mb-2" id="showTanggal">Tampilkan</button>
                    <button class="btn btn-secondary btn-sm mb-2" id="resetFilter">Reset</button>
                </div>

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
                                $showInTransaksi = in_array($item->status, ['belum_diproses', 'sedang_diproses', 'makanan_siap', 'Pesanan Online']);
                                $showInHistory = in_array($item->status, ['selesai', 'lunas']);
                            ?>
                            <tr class="rowTransaksi <?php echo e($showInTransaksi ? 'transaksi' : ''); ?> <?php echo e($showInHistory ? 'history' : ''); ?>">
                                <td data-order="<?php echo e(strtotime($item->created_at)); ?>"><?php echo e($item->created_at->format('d-m-Y H:i')); ?></td>
                                <td>
                                    <?php $__currentLoopData = $item->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php echo e($detail->menu->nama); ?><?php if(!empty($detail->note) && trim($detail->note) !== '-'): ?> (<?php echo e($detail->note); ?>)<?php endif; ?>,
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </td>
                                <td>
                                    <?php switch($item->status):
                                        case ('belum_diproses'): ?> <span class="badge badge-secondary">Belum Diproses</span> <?php break; ?>
                                        <?php case ('Pesanan Online'): ?> <span class="badge badge-secondary">Belum Diproses</span> <?php break; ?>
                                        <?php case ('sedang_diproses'): ?> <span class="badge badge-warning">Sedang Diproses</span> <?php break; ?>
                                        <?php case ('makanan_siap'): ?> <span class="badge badge-info">Selesai</span> <?php break; ?>
                                        <?php case ('selesai'): ?> <span class="badge badge-primary">Selesai</span> <?php break; ?>
                                        <?php case ('lunas'): ?> <span class="badge badge-primary">Selesai</span> <?php break; ?>
                                        <?php default: ?> <span class="badge badge-light">-</span>
                                    <?php endswitch; ?>
                                </td>
                                <td>
                                    <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#detailModal-<?php echo e($item->id); ?>">Lihat</button>
                                    <?php if($item->status === 'sedang_diproses'): ?>
                                        <?php
                                            $remainingMenuCount = $item->details->sum(function($detail) {
                                                return ($detail->menu_status ?? \App\Models\DetailTransaksi::MENU_STATUS_BELUM_SIAP) === \App\Models\DetailTransaksi::MENU_STATUS_BELUM_SIAP
                                                    ? $detail->jumlah
                                                    : 0;
                                            });
                                            $remainingLabel = $remainingMenuCount > 0
                                                ? "{$remainingMenuCount} menu belum siap"
                                                : 'Semua menu siap';
                                        ?>
                                        <input type="text" class="form-control form-control-sm d-inline-block ml-1 text-danger" style="width:auto; min-width: 180px; color: #dc3545;" disabled value="<?php echo e($remainingLabel); ?>">
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modals untuk detail transaksi -->
<?php $__currentLoopData = $koki; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="modal fade" id="detailModal-<?php echo e($item->id); ?>" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Pesanan - <?php echo e($item->kode); ?></h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p><strong>Nomor Meja:</strong> <?php echo e($item->nomeja); ?></p>
                <p><strong>Atas Nama:</strong> <?php echo e($item->atasnama); ?></p>
                <p><strong>Status Transaksi:</strong>
                    <?php switch($item->status):
                        case ('belum_diproses'): ?> <span class="badge badge-secondary">Belum Diproses</span> <?php break; ?>
                        <?php case ('Pesanan Online'): ?> <span class="badge badge-secondary">Belum Diproses</span> <?php break; ?>
                        <?php case ('sedang_diproses'): ?> <span class="badge badge-warning">Sedang Diproses</span> <?php break; ?>
                        <?php case ('makanan_siap'): ?> <span class="badge badge-info">Makanan Siap</span> <?php break; ?>
                        <?php case ('selesai'): ?> <span class="badge badge-primary">Selesai</span> <?php break; ?>
                        <?php case ('lunas'): ?> <span class="badge badge-primary">Lunas</span> <?php break; ?>
                        <?php default: ?> <span class="badge badge-light">-</span>
                    <?php endswitch; ?>
                </p>
                <h5 class="mt-3">Menu Dipesan:</h5>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>Menu</th>
                                <th>Catatan</th>
                                <th>Jumlah</th>
                                <th>Status Menu</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $item->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
                                <td>
                                    <?php $mstatus = $detail->menu_status ?? \App\Models\DetailTransaksi::MENU_STATUS_BELUM_SIAP; ?>
                                    <?php if($mstatus === \App\Models\DetailTransaksi::MENU_STATUS_BELUM_SIAP): ?>
                                        <span class="badge badge-warning">Belum Siap</span>
                                    <?php else: ?>
                                        <span class="badge badge-success">Siap</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($item->status === 'sedang_diproses' && $mstatus === \App\Models\DetailTransaksi::MENU_STATUS_BELUM_SIAP): ?>
                                        <button class="btn btn-sm btn-success" onclick='markMenuReady(<?php echo e($item->id); ?>, <?php echo e($detail->id); ?>, <?php echo e(json_encode($detail->menu->nama)); ?>)' title="Tandai menu siap">
                                            <i class="fas fa-check"></i> Siap
                                        </button>
                                    <?php else: ?>
                                        <button class="btn btn-sm btn-secondary" disabled>-</button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>

            // MENENTUKAN TOMBOL PROSES ATAU DALAM ANTRIAN
            <div class="modal-footer">
                <?php if(in_array($item->status, ['belum_diproses', 'Pesanan Online'])): ?>
                    <?php if($item->id === $nextPendingId && !$activeProcessing): ?>
                        <form action="<?php echo e(route('transaksi.updateStatus', $item->id)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="status" value="sedang_diproses">
                            <button type="submit" class="btn btn-warning">Proses</button>
                        </form>
                    <?php else: ?>
                        <button type="button" class="btn btn-secondary" disabled>Dalam Antrian</button>
                    <?php endif; ?>
                <?php endif; ?>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script>
    window.baseUrl = "<?php echo e(url('/')); ?>";

    $(document).ready(function () {
        const table = $('#kokiTable').DataTable({
            paging: true,
            searching: true,
            ordering: true,
            info: true,
            pageLength: 5,
            order: [[0, 'asc']],
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

        function kokiStatusFilter(settings, data, dataIndex) {
            const selectedTab = $('.tab-filter .nav-link.active').data('status') || 'semua';
            const statusHtml = data[2] || '';
            const statusText = statusHtml.replace(/<[^>]+>/g, '').trim().toLowerCase();

            if (selectedTab === 'semua') {
                return true;
            }

            if (selectedTab === 'belum') {
                return !statusText.includes('lunas') && !statusText.includes('selesai');
            }

            if (selectedTab === 'selesai') {
                return statusText.includes('lunas') || statusText.includes('selesai');
            }

            return true;
        }

        $.fn.dataTable.ext.search.push(kokiStatusFilter);

        // Date range filter for Koki table (column 0 contains 'd-m-Y H:i')
        $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
            const min = $('#startDate').val();
            const max = $('#endDate').val();
            const tanggalStr = data[0] || '';
            if (!min && !max) return true;

            const tglSplit = tanggalStr.split(' ')[0].split('-');
            const tgl = new Date(`${tglSplit[2]}-${tglSplit[1]}-${tglSplit[0]}`);
            const minDate = min ? new Date(min) : null;
            const maxDate = max ? new Date(max) : null;

            return (!minDate || tgl >= minDate) && (!maxDate || tgl <= maxDate);
        });

        table.draw();

        $('#showTanggal').on('click', function () {
            table.draw();
        });

        $('.tab-filter .nav-link').on('click', function (e) {
            e.preventDefault();
            $('.tab-filter .nav-link').removeClass('active');
            $(this).addClass('active');
            table.draw();
        });

        $("#resetFilter").on('click', function () {
            $("#startDate, #endDate").val('');
            table.draw();
        });

        // Start notification polling on page load
        startPelayanNotificationPolling();

        // Start real-time update when modal is opened
        $(document).on('show.bs.modal', '[id^="detailModal-"]', function(e) {
            const modalId = $(this).attr('id');
            const transaksiId = modalId.replace('detailModal-', '');
            startRealTimeUpdate(transaksiId);
        });

        window.baseUrl = "<?php echo e(url('/')); ?>";

        // Stop real-time update ketika modal ditutup
        $(document).on('hide.bs.modal', '[id^="detailModal-"]', function(e) {
            if (pollingInterval) {
                clearInterval(pollingInterval);
                pollingInterval = null;
            }
        });
    });

    // ==========================================
    // MENU STATUS FUNCTIONS
    // ==========================================

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
                    url: baseUrl + '/transaksi/' + transaksiId + '/menu-status/' + detailId,
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
                    url: baseUrl + '/transaksi/' + transaksiId + '/menu-status/' + detailId,
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
                url: baseUrl + '/transaksi/' + transaksiId + '/menu-status',
                method: 'GET',
                success: function(response) {
                    if (response.success) {
                        updateMenuStatusUI(response.details, transaksiId);
                    }
                },
                error: function(xhr) {
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
    let notificationQueue = [];
    let isShowingNotification = false;

    function startPelayanNotificationPolling() {
        // Cek role user
        const userRole = '<?php echo e(auth()->user()->role); ?>';
        if (userRole !== 'pelayan' && userRole !== 'koki') return;

        // Polling setiap 5 detik
        notificationPollingInterval = setInterval(function() {
            $.ajax({
                url: baseUrl + '/transaksi/ready-menus-notification',
                method: 'GET',
                success: function(response) {
                    if (response.success && response.count > 0) {
                        response.ready_menus.forEach(function(notification) {
                            // Push ke antrean; backend menandai notified sehingga item tidak akan dikirim ulang
                            notificationQueue.push(notification);
                        });
                        processNotificationQueue();
                    }
                },
                error: function(xhr) {
                    console.error('Notification polling error:', xhr);
                }
            });
        }, 5000);
    }

    function processNotificationQueue() {
        if (isShowingNotification) return;
        if (notificationQueue.length === 0) return;
        isShowingNotification = true;
        const notification = notificationQueue.shift();
        showNotification(notification, function() {
            isShowingNotification = false;
            // proses notifikasi berikutnya setelah jeda singkat
            setTimeout(processNotificationQueue, 300);
        });
    }

    // Function untuk menampilkan notifikasi popup di kanan atas
    function showNotification(notification, onHidden) {
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

        const $el = $(notificationHtml);
        $('body').append($el);

        // Auto-remove setelah 5 detik
        setTimeout(function() {
            $el.fadeOut(500, function() {
                $(this).remove();
                if (typeof onHidden === 'function') onHidden();
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

<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\omahjawa1-Copy\resources\views/pages/koki/index.blade.php ENDPATH**/ ?>


<?php $__env->startSection('header'); ?>
    <div class="row mb-2">
        <div class="col-sm-8">
            <h1>Edit Transaksi</h1>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php
        $menuOptions = $menus->map(fn ($menu) => [
            'id' => $menu->id,
            'name' => $menu->nama,
            'price' => number_format((float) $menu->harga, 0, ',', '.'),
        ])->values();
    ?>
    <style>
        .transaction-menu-search {
            position: relative;
        }

        .transaction-menu-search input {
            padding-right: 2.25rem;
        }

        .transaction-menu-search button {
            position: absolute;
            top: 50%;
            right: 0.65rem;
            padding: 0;
            border: 0;
            background: transparent;
            color: #6c757d;
            font-size: 1.25rem;
            line-height: 1;
            transform: translateY(-50%);
            cursor: pointer;
        }

        .transaction-menu-search button:hover {
            color: #343a40;
        }
    </style>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Transaksi <?php echo e($transaksi->kode); ?></h3>
        </div>
        <form action="<?php echo e(route('kasir.transaksi.update', $transaksi->id)); ?>" method="POST" id="editTransactionForm">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <div class="card-body">
                <?php if($errors->any()): ?>
                    <div class="alert alert-danger" role="alert">
                        <ul class="mb-0">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="atasnama">Atas Nama Konsumen</label>
                        <input type="text" id="atasnama" name="atasnama" class="form-control" value="<?php echo e(old('atasnama', $transaksi->atasnama)); ?>" required maxlength="255">
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="mb-0">Menu Pesanan</h5>
                    <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#addTransactionMenuModal">
                        <i class="fas fa-plus mr-1" aria-hidden="true"></i>Tambah Menu
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered" id="transactionItems">
                        <thead>
                            <tr>
                                <th>Menu</th>
                                <th style="width: 110px;">Jumlah</th>
                                <th>Catatan</th>
                                <th style="width: 70px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="transactionItemsBody">
                            <?php $__currentLoopData = $transaksi->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        <span><?php echo e($detail->menu?->nama ?? 'Menu tidak tersedia'); ?></span>
                                        <input type="hidden" class="transaction-menu-id" name="items[<?php echo e($loop->index); ?>][menu_id]" value="<?php echo e($detail->menu_id); ?>">
                                    </td>
                                    <td><input type="number" class="form-control transaction-quantity" name="items[<?php echo e($loop->index); ?>][jumlah]" min="1" value="<?php echo e($detail->jumlah); ?>" required></td>
                                    <td><input type="text" class="form-control transaction-note" name="items[<?php echo e($loop->index); ?>][note]" maxlength="255" value="<?php echo e($detail->note); ?>"></td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-danger remove-transaction-item" aria-label="Hapus menu dari transaksi" title="Hapus menu">
                                            <i class="fas fa-trash" aria-hidden="true"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                <p class="text-muted mb-0">Harga item mengikuti harga menu saat ini. Total dan PPN akan dihitung ulang saat perubahan disimpan.</p>
            </div>
            <div class="card-footer d-flex justify-content-end">
                <a href="<?php echo e(route('kasir.index')); ?>" class="btn btn-secondary mr-2">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>

    <div class="modal fade" id="addTransactionMenuModal" tabindex="-1" role="dialog" aria-labelledby="addTransactionMenuModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTransactionMenuModalLabel">Pilih Menu untuk Ditambahkan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php if($menus->isEmpty()): ?>
                        <p class="mb-0">Belum ada menu yang tersedia.</p>
                    <?php else: ?>
                        <div class="transaction-menu-search mb-3">
                            <input type="search" id="transaction-menu-search" class="form-control" placeholder="Cari menu..." oninput="filterTransactionMenus()">
                            <button type="button" id="clear-transaction-menu-search" aria-label="Hapus pencarian menu" title="Hapus pencarian" onclick="clearTransactionMenuSearch()" hidden>&times;</button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover mb-0" id="transactionMenuOptions">
                                <thead>
                                    <tr>
                                        <th>Nama Menu</th>
                                        <th>Harga</th>
                                        <th style="width: 90px;">Jumlah</th>
                                        <th style="width: 70px;">Tambah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $menus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr data-menu-name="<?php echo e(strtolower($menu->nama)); ?>" data-menu-page-match="true">
                                            <td><?php echo e($menu->nama); ?></td>
                                            <td>Rp<?php echo e(number_format($menu->harga, 0, ',', '.')); ?></td>
                                            <td class="text-center">
                                                <span class="transaction-menu-selected-quantity" data-quantity="0">0</span>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-success add-transaction-menu" data-menu-id="<?php echo e($menu->id); ?>" aria-label="Tambah <?php echo e($menu->nama); ?>" title="Tambah menu">
                                                    <i class="fas fa-plus" aria-hidden="true"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                        <nav class="d-flex justify-content-between align-items-center mt-3" aria-label="Halaman daftar menu">
                            <span id="transactionMenuPageInfo" class="text-muted small"></span>
                            <div>
                                <button type="button" class="btn btn-sm btn-outline-secondary mr-1" id="transactionMenuPreviousPage">Sebelumnya</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" id="transactionMenuNextPage">Berikutnya</button>
                            </div>
                        </nav>
                    <?php endif; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="saveTransactionMenuSelection">Simpan Menu</button>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script>
        const transactionMenus = <?php echo json_encode($menuOptions, 15, 512) ?>;
        const itemBody = document.getElementById('transactionItemsBody');
        const transactionMenuPageSize = 10;
        let transactionMenuCurrentPage = 1;

        function renumberTransactionItems() {
            itemBody.querySelectorAll('tr').forEach((row, index) => {
                row.querySelector('.transaction-menu-id').name = `items[${index}][menu_id]`;
                row.querySelector('.transaction-quantity').name = `items[${index}][jumlah]`;
                row.querySelector('.transaction-note').name = `items[${index}][note]`;
            });
        }

        function addTransactionItem(menuId, quantity) {
            const existingRow = Array.from(itemBody.querySelectorAll('.transaction-menu-id'))
                .find(input => Number(input.value) === Number(menuId))
                ?.closest('tr');

            if (existingRow) {
                const quantityInput = existingRow.querySelector('.transaction-quantity');
                quantityInput.value = String(Number(quantityInput.value) + quantity);
                return;
            }
            const row = document.createElement('tr');
            const menuCell = document.createElement('td');
            const menu = transactionMenus.find(item => Number(item.id) === Number(menuId));
            const menuName = document.createElement('span');
            menuName.textContent = menu.name;
            const menuIdInput = document.createElement('input');
            menuIdInput.type = 'hidden';
            menuIdInput.className = 'transaction-menu-id';
            menuIdInput.value = String(menuId);
            menuCell.append(menuName, menuIdInput);

            const quantityCell = document.createElement('td');
            const quantityInput = document.createElement('input');
            quantityInput.type = 'number';
            quantityInput.className = 'form-control transaction-quantity';
            quantityInput.min = '1';
            quantityInput.value = String(quantity);
            quantityInput.required = true;
            quantityCell.appendChild(quantityInput);

            const noteCell = document.createElement('td');
            const noteInput = document.createElement('input');
            noteInput.type = 'text';
            noteInput.className = 'form-control transaction-note';
            noteInput.maxLength = 255;
            noteCell.appendChild(noteInput);

            const actionCell = document.createElement('td');
            actionCell.className = 'text-center';
            const removeButton = document.createElement('button');
            removeButton.type = 'button';
            removeButton.className = 'btn btn-sm btn-danger remove-transaction-item';
            removeButton.setAttribute('aria-label', 'Hapus menu dari transaksi');
            removeButton.title = 'Hapus menu';
            removeButton.innerHTML = '<i class="fas fa-trash" aria-hidden="true"></i>';
            actionCell.appendChild(removeButton);

            row.append(menuCell, quantityCell, noteCell, actionCell);
            itemBody.appendChild(row);
            renumberTransactionItems();
        }

        itemBody.addEventListener('click', function (event) {
            const removeButton = event.target.closest('.remove-transaction-item');
            if (!removeButton) return;
            removeButton.closest('tr').remove();
            renumberTransactionItems();
        });

        document.getElementById('transactionMenuOptions')?.addEventListener('click', function (event) {
            const addButton = event.target.closest('.add-transaction-menu');
            if (!addButton) return;
            const quantity = addButton.closest('tr').querySelector('.transaction-menu-selected-quantity');
            const nextQuantity = Number(quantity.dataset.quantity) + 1;
            quantity.dataset.quantity = String(nextQuantity);
            quantity.textContent = String(nextQuantity);
        });

        function resetTransactionMenuSelection() {
            document.querySelectorAll('.transaction-menu-selected-quantity').forEach(quantity => {
                quantity.dataset.quantity = '0';
                quantity.textContent = '0';
            });
        }

        document.getElementById('saveTransactionMenuSelection').addEventListener('click', function () {
            const selections = Array.from(document.querySelectorAll('#transactionMenuOptions tbody tr'))
                .map(row => ({
                    menuId: Number(row.querySelector('.add-transaction-menu').dataset.menuId),
                    quantity: Number(row.querySelector('.transaction-menu-selected-quantity').dataset.quantity),
                }))
                .filter(selection => selection.quantity > 0);

            if (selections.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Pilih menu',
                    text: 'Tambahkan minimal satu menu sebelum menyimpan.',
                });
                return;
            }

            selections.forEach(selection => {
                addTransactionItem(selection.menuId, selection.quantity);
            });
            resetTransactionMenuSelection();
            $('#addTransactionMenuModal').modal('hide');
        });

        $('#addTransactionMenuModal').on('hidden.bs.modal', resetTransactionMenuSelection);

        function filterTransactionMenus() {
            const searchInput = document.getElementById('transaction-menu-search');
            const searchValue = searchInput.value.trim().toLowerCase();
            document.getElementById('clear-transaction-menu-search').hidden = searchInput.value.length === 0;
            transactionMenuCurrentPage = 1;
            document.querySelectorAll('#transactionMenuOptions tbody tr').forEach(row => {
                row.dataset.menuPageMatch = String(row.dataset.menuName.includes(searchValue));
            });
            renderTransactionMenuPage();
        }

        function renderTransactionMenuPage() {
            const table = document.getElementById('transactionMenuOptions');
            if (!table) return;

            const rows = Array.from(table.querySelectorAll('tbody tr'));
            const matchingRows = rows.filter(row => row.dataset.menuPageMatch !== 'false');
            const pageCount = Math.max(1, Math.ceil(matchingRows.length / transactionMenuPageSize));
            transactionMenuCurrentPage = Math.min(transactionMenuCurrentPage, pageCount);
            const firstRowIndex = (transactionMenuCurrentPage - 1) * transactionMenuPageSize;
            const visibleRows = new Set(matchingRows.slice(firstRowIndex, firstRowIndex + transactionMenuPageSize));

            rows.forEach(row => {
                row.hidden = !visibleRows.has(row);
            });

            document.getElementById('transactionMenuPageInfo').textContent = matchingRows.length === 0
                ? 'Tidak ada menu yang cocok'
                : `Halaman ${transactionMenuCurrentPage} dari ${pageCount} (${matchingRows.length} menu)`;
            document.getElementById('transactionMenuPreviousPage').disabled = transactionMenuCurrentPage <= 1;
            document.getElementById('transactionMenuNextPage').disabled = transactionMenuCurrentPage >= pageCount;
        }

        function clearTransactionMenuSearch() {
            const searchInput = document.getElementById('transaction-menu-search');
            searchInput.value = '';
            filterTransactionMenus();
            searchInput.focus();
        }

        document.getElementById('transactionMenuPreviousPage')?.addEventListener('click', function () {
            if (transactionMenuCurrentPage > 1) {
                transactionMenuCurrentPage--;
                renderTransactionMenuPage();
            }
        });

        document.getElementById('transactionMenuNextPage')?.addEventListener('click', function () {
            transactionMenuCurrentPage++;
            renderTransactionMenuPage();
        });

        renumberTransactionItems();
        renderTransactionMenuPage();
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\omahjawa1Deploy\resources\views/pages/kasir/edit.blade.php ENDPATH**/ ?>
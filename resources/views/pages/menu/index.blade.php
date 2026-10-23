@extends('layouts.main')

@section('header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Menu</h1>
        </div>
    </div>
@endsection

@section('content')
<style>
    .menu-order-panel .category-filter {
        border-color: #c8d7cc;
        background-color: #fff;
        color: #30483a;
    }

    .menu-order-panel .category-filter:hover,
    .menu-order-panel .category-filter:focus {
        border-color: #789982;
        background-color: #e8f1ea;
        color: #30483a;
    }

    .menu-order-panel .category-filter.active,
    .menu-order-panel .category-filter.active:hover,
    .menu-order-panel .category-filter.active:focus {
        border-color: #496b52;
        background-color: #496b52;
        color: #fff;
    }

    .menu-order-panel .menu-search-field {
        position: relative;
    }

    .menu-order-panel .menu-search-field input {
        padding-right: 2.25rem;
    }

    .menu-order-panel .menu-search-clear {
        position: absolute;
        top: 50%;
        right: 0.5rem;
        padding: 0;
        border: 0;
        background: transparent;
        color: #6c757d;
        font-size: 1.25rem;
        line-height: 1;
        transform: translateY(-50%);
        cursor: pointer;
    }

    .menu-order-panel .menu-search-clear:hover {
        color: #343a40;
    }

    .menu-order-panel .menu-order-table-container {
        height: 230px;
        overflow-y: auto;
        border-radius: 10px;
    }

    .menu-order-panel .menu-order-summary {
        margin-top: 0.75rem;
        padding: 0.75rem 0.85rem;
        border: 1px solid #e5ebe6;
        border-radius: 12px;
        background: #f8fbf8;
    }

    .menu-order-panel .menu-order-summary-lines {
        color: #68756c;
        font-size: 0.92rem;
    }

    .menu-order-panel .menu-order-total {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 0.65rem;
        margin-top: 0.65rem;
        padding-top: 0.65rem;
        border-top: 1px solid #e3ebe5;
    }

    .menu-order-panel .menu-order-total label {
        flex: 0 0 auto;
        margin: 0;
        color: #30483a;
        font-weight: 700;
        white-space: nowrap;
    }

    .menu-order-panel #total-harga {
        width: 145px;
        min-width: 0;
        flex: 0 1 145px;
        background: #fff;
        font-weight: 700;
        text-align: right;
    }

    .menu-order-panel .menu-save-button {
        flex: 0 0 auto;
        min-width: 86px;
    }

    .menu-order-panel .menu-order-actions {
        display: flex;
        gap: 0.5rem;
    }

    @media (max-width: 767.98px) {
        .menu-order-panel .menu-order-table-container {
            height: 220px;
        }

        .menu-order-panel .menu-order-total {
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 0.45rem;
        }

        .menu-order-panel .menu-order-actions {
            width: 100%;
            flex: 1 0 100%;
        }

        .menu-order-panel .menu-order-actions .btn {
            flex: 1 1 0;
            min-width: 0;
        }

        .menu-order-panel #total-harga {
            flex-basis: 110px;
            width: 110px;
        }
    }
</style>
<div class="p-3 bg-white rounded shadow-sm menu-order-panel">
    <div class="row mb-3">
        <div class="col-md-4">
            <div class="menu-search-field">
                <input type="text" id="search" class="form-control" placeholder="Search menu..." oninput="filterMenu()">
                <button type="button" class="menu-search-clear" id="clear-menu-search" aria-label="Hapus pencarian menu" title="Hapus pencarian" onclick="clearMenuSearch()" hidden>&times;</button>
            </div>
        </div>
    </div>
    <div class="d-flex flex-wrap mb-3" role="group" aria-label="Filter kategori menu">
        <button type="button" class="btn btn-sm mr-2 mb-2 category-filter active" data-category-id="all" onclick="setMenuCategory('all', this)">Semua Menu</button>
        @foreach($jenisList as $jenis)
            <button type="button" class="btn btn-sm mr-2 mb-2 category-filter" data-category-id="{{ $jenis->id }}" onclick="setMenuCategory('{{ $jenis->id }}', this)">{{ $jenis->nama_jenis }}</button>
        @endforeach
        @if($menus->contains(fn ($menu) => $menu->jenis_id === null))
            <button type="button" class="btn btn-sm mr-2 mb-2 category-filter" data-category-id="uncategorized" onclick="setMenuCategory('uncategorized', this)">Tanpa Kategori</button>
        @endif
    </div>
    <div class="row">
        <div class="col-md-6">
            <div style="height: 500px; overflow-y: auto;">
                <table class="table table-bordered" id="menu-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Harga</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="menu-table-body">
                        @foreach($menus as $menu)
                        <tr data-category-id="{{ $menu->jenis_id ?? '' }}">
                            <td>{{ $menu->id }}</td>
                            <td>{{ $menu->nama }}</td>
                            <td>{{ $menu->harga }}</td>
                            <td><button type="button" class="btn btn-success btn-add-menu-item" aria-label="Tambah {{ $menu->nama }} ke pesanan" title="Tambah ke pesanan" onclick="addItem({{ $menu->id }}, '{{ $menu->nama }}', {{ $menu->harga }})"><i class="fas fa-plus" aria-hidden="true"></i></button></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-6">
            <h3>Pesanan</h3>
            <div class="mb-3">
                <input type="text" id="atas-nama" class="form-control" placeholder="Atas Nama Konsumen">
            </div>
            <div class="menu-order-table-container">
                <table class="table table-bordered" id="selected-items">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Jumlah</th>
                            <th>Harga</th>
                            <th>Subtotal</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <div class="menu-order-summary">
                <div class="menu-order-summary-lines d-flex justify-content-between">
                    <span>Subtotal</span>
                    <span id="subtotal-harga">0.00</span>
                </div>
                <div class="menu-order-summary-lines d-flex justify-content-between">
                    <span>{{ $ppnEnabled ? 'PPN (' . number_format((float) $ppnRate, 2) . '%)' : 'PPN (nonaktif)' }}</span>
                    <span id="ppn-harga">0.00</span>
                </div>
                <div class="menu-order-total">
                    <label for="total-harga">Total Harga:</label>
                    <input type="text" id="total-harga" value="0.00" readonly class="form-control">
                    <div class="menu-order-actions">
                        <button type="button" class="btn btn-primary menu-save-button" onclick="saveOrder()">Simpan</button>
                        <button type="button" class="btn btn-success menu-save-button" onclick="openPaymentModal()">Bayar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="menuPaymentModal" tabindex="-1" role="dialog" aria-labelledby="menuPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="menuPaymentModalLabel">Konfirmasi Pembayaran</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="menuPaymentForm">
                    <div class="form-group">
                        <label for="menu-payment-total">Total Harga</label>
                        <input type="text" class="form-control" id="menu-payment-total" readonly>
                    </div>
                    <div class="small text-muted mb-3">
                        Subtotal Rp<span id="menu-payment-subtotal">0,00</span>
                        &middot; PPN{{ $ppnEnabled ? ' (' . number_format((float) $ppnRate, 2, ',', '.') . '%)' : '' }}
                        Rp<span id="menu-payment-ppn">0,00</span>
                    </div>
                    <div class="form-group">
                        <label for="menu-payment-method">Metode Pembayaran</label>
                        <select class="form-control" id="menu-payment-method" required>
                            <option value="">Pilih Metode Pembayaran</option>
                            <option value="Cash">Cash</option>
                            <option value="Dana">Dana</option>
                            <option value="BCA">BCA</option>
                            <option value="BRI">BRI</option>
                            <option value="Qris">Qris</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="menu-payment-amount">Jumlah Bayar</label>
                        <input type="number" class="form-control" id="menu-payment-amount" min="0.01" step="0.01" required>
                        <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="menu-exact-payment">Uang Pas</button>
                    </div>
                    <div class="form-group">
                        <label for="menu-payment-change">Kembalian</label>
                        <input type="text" class="form-control" id="menu-payment-change" readonly>
                    </div>
                    <button type="submit" class="btn btn-success btn-block mt-3">Konfirmasi Pembayaran</button>
                </form>
            </div>
        </div>
    </div>
</div>

    <!-- Add Menu Modal -->
    <div class="modal fade" id="addMenuModal" tabindex="-1" role="dialog" aria-labelledby="addMenuModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addMenuModalLabel">Tambah Menu</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addMenuForm">
                        <div class="form-group">
                            <label for="menu-nama">Nama</label>
                            <input type="text" class="form-control" id="menu-nama" required>
                        </div>
                        <div class="form-group">
                            <label for="menu-harga">Harga</label>
                            <input type="number" class="form-control" id="menu-harga" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('components.receipt-printer-script')
    <script>
        let items = [];
        const ppnRate = @json((float) $ppnRate);
        let isSavingOrder = false;

        function addItem(id, nama, harga) {
            let existingItem = items.find(item => item.menu_id === id);
            if (existingItem) {
                existingItem.jumlah++;
                existingItem.subtotal = existingItem.jumlah * existingItem.harga;
            } else {
                items.push({ menu_id: id, nama: nama, jumlah: 1, harga: harga, subtotal: harga });
            }
            renderSelectedItems();
            updateTotalHarga();
        }

        function removeItem(index) {
            if (items[index].jumlah > 1) {
                items[index].jumlah--;
                items[index].subtotal = items[index].jumlah * items[index].harga;
            } else {
                items.splice(index, 1);
            }
            renderSelectedItems();
            updateTotalHarga();
        }


        function renderSelectedItems() {
            const tableBody = document.getElementById('selected-items').getElementsByTagName('tbody')[0];
            tableBody.innerHTML = '';
            items.forEach((item, index) => {
                let row = tableBody.insertRow();
                row.innerHTML = `
                    <td>${item.nama}</td>
                    <td>${item.jumlah}</td>
                    <td>${item.harga}</td>
                    <td>${item.subtotal}</td>
                    <td><button type="button" class="btn btn-remove-menu-item" aria-label="Kurangi ${item.nama} dari pesanan" title="Kurangi jumlah" onclick="removeItem(${index})"><i class="fas fa-minus" aria-hidden="true"></i></button></td>
                `;
            });
        }

        function updateTotalHarga() {
            const subtotal = items.reduce((total, item) => total + item.subtotal, 0);
            const ppnAmount = Math.round(subtotal * ppnRate) / 100;
            document.getElementById('subtotal-harga').textContent = subtotal.toFixed(2);
            document.getElementById('ppn-harga').textContent = ppnAmount.toFixed(2);
            document.getElementById('total-harga').value = (subtotal + ppnAmount).toFixed(2);
            document.getElementById('menu-payment-total').value = (subtotal + ppnAmount).toFixed(2);
            document.getElementById('menu-payment-subtotal').textContent = subtotal.toLocaleString('id-ID', { minimumFractionDigits: 2 });
            document.getElementById('menu-payment-ppn').textContent = ppnAmount.toLocaleString('id-ID', { minimumFractionDigits: 2 });
            updatePaymentChange();
        }

        function openPaymentModal() {
            const atasNama = document.getElementById('atas-nama').value.trim();
            if (!atasNama || items.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Warning',
                    text: 'Harap isi atas nama dan pilih minimal satu item!',
                });
                return;
            }

            updateTotalHarga();
            $('#menuPaymentModal').modal('show');
        }

        function updatePaymentChange() {
            const amount = parseFloat(document.getElementById('menu-payment-amount').value);
            const total = parseFloat(document.getElementById('menu-payment-total').value);
            const changeField = document.getElementById('menu-payment-change');

            if (!Number.isNaN(amount) && !Number.isNaN(total)) {
                changeField.value = amount >= total
                    ? (amount - total).toLocaleString('id-ID', { minimumFractionDigits: 2 })
                    : 'Jumlah bayar kurang';
            } else {
                changeField.value = '';
            }
        }

        document.getElementById('menu-payment-amount').addEventListener('input', updatePaymentChange);
        document.getElementById('menu-exact-payment').addEventListener('click', function () {
            document.getElementById('menu-payment-amount').value = document.getElementById('menu-payment-total').value;
            updatePaymentChange();
        });
        document.getElementById('menuPaymentForm').addEventListener('submit', function (event) {
            event.preventDefault();
            saveOrder(true);
        });

        async function saveOrder(shouldPay = false) {
            if (isSavingOrder) {
                return;
            }

            const atasNama = document.getElementById('atas-nama').value.trim();
            if (!atasNama || items.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Warning',
                    text: 'Harap isi atas nama dan pilih minimal satu item!',
                });
                return;
            }

            const data = {
                atasnama: atasNama,
                items: items.map(item => ({
                    menu_id: item.menu_id,
                    jumlah: item.jumlah
                }))
            };

            if (shouldPay) {
                data.bayar = 1;
                data.metode_pembayaran = document.getElementById('menu-payment-method').value;
                data.jumlah_bayar = document.getElementById('menu-payment-amount').value;
            }

            const actionButtons = document.querySelectorAll('.menu-save-button, #menuPaymentForm button[type="submit"]');
            isSavingOrder = true;
            actionButtons.forEach(button => { button.disabled = true; });

            try {
                const response = await fetch("{{ route('transaksi.store') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    body: JSON.stringify(data)
                });
                const result = await response.json();
                if (!response.ok) {
                    const validationMessage = Object.values(result.errors || {}).flat()[0];
                    throw new Error(validationMessage || result.message || 'Pesanan gagal disimpan.');
                }
                if (!result.success) {
                    throw new Error(result.message || 'Pesanan gagal disimpan.');
                }

                const receiptResponse = await fetch(result.receiptDataUrl, {
                    credentials: 'same-origin',
                    headers: { 'Accept': 'application/json' }
                });
                const receiptResult = await receiptResponse.json();
                if (!receiptResponse.ok || !receiptResult.success) {
                    throw new Error(receiptResult.message || 'Data struk gagal dimuat.');
                }

                if (shouldPay) {
                    $('#menuPaymentModal').modal('hide');
                    const paymentResult = await Swal.fire({
                        icon: 'success',
                        title: 'Pembayaran Berhasil',
                        text: result.message,
                        showCancelButton: true,
                        confirmButtonText: 'Cetak Struk',
                        cancelButtonText: 'Selesaikan',
                        reverseButtons: true,
                        showLoaderOnConfirm: true,
                        allowOutsideClick: () => !Swal.isLoading(),
                        preConfirm: async () => {
                            try {
                                await window.printBluetoothReceipt(receiptResult.receipt);
                            } catch (error) {
                                Swal.showValidationMessage(error.message || 'Struk gagal dicetak.');
                                return false;
                            }
                        }
                    });
                    if (paymentResult.isConfirmed || paymentResult.dismiss) {
                        window.location.href = result.redirect;
                    }
                    return;
                }

                const printResult = await Swal.fire({
                    icon: 'question',
                    title: 'Cetak struk?',
                    text: 'Pesanan berhasil disimpan. Cetak struk dengan status Belum Lunas?',
                    showCancelButton: true,
                    confirmButtonText: 'Cetak Struk',
                    cancelButtonText: 'Tidak',
                    reverseButtons: true,
                    showLoaderOnConfirm: true,
                    allowOutsideClick: () => !Swal.isLoading(),
                    preConfirm: async () => {
                        try {
                            await window.printBluetoothReceipt(receiptResult.receipt);
                        } catch (error) {
                            Swal.showValidationMessage(error.message || 'Struk gagal dicetak.');
                            return false;
                        }
                    }
                });
                if (printResult.isConfirmed || printResult.dismiss) {
                    await Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: result.message,
                    });
                    window.location.href = result.redirect;
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message || 'Terjadi kesalahan saat menyimpan pesanan. Silakan coba lagi.',
                });
            } finally {
                isSavingOrder = false;
                actionButtons.forEach(button => { button.disabled = false; });
            }
        }

        function filterMenu() {
            const searchInput = document.getElementById('search');
            const searchValue = searchInput.value.trim().toLowerCase();
            document.getElementById('clear-menu-search').hidden = searchInput.value.length === 0;
            const selectedCategory = document.querySelector('.category-filter.active')?.dataset.categoryId || 'all';
            const rows = document.querySelectorAll('#menu-table-body tr');
            rows.forEach(row => {
                const nama = row.cells[1].textContent.toLowerCase();
                const categoryMatches = selectedCategory === 'all'
                    || (selectedCategory === 'uncategorized' && row.dataset.categoryId === '')
                    || row.dataset.categoryId === selectedCategory;
                if (categoryMatches && nama.includes(searchValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        function clearMenuSearch() {
            const searchInput = document.getElementById('search');
            searchInput.value = '';
            filterMenu();
            searchInput.focus();
        }

        function setMenuCategory(categoryId, button) {
            document.querySelectorAll('.category-filter').forEach(filterButton => {
                filterButton.classList.remove('active');
            });

            button.classList.add('active');
            filterMenu();
        }

        function showAddMenuModal() {
            $('#addMenuModal').modal('show');
        }

        document.getElementById('addMenuForm').addEventListener('submit', function(event) {
            event.preventDefault();
            const nama = document.getElementById('menu-nama').value;
            const harga = document.getElementById('menu-harga').value;

            fetch("{{ route('menu.store') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ nama: nama, harga: harga })
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    const tableBody = document.getElementById('menu-table-body');
                    const newRow = tableBody.insertRow();
                    newRow.dataset.categoryId = '';
                    newRow.innerHTML = `
                        <td>${result.id}</td>
                        <td>${result.nama}</td>
                        <td>${result.harga}</td>
                        <td><button type="button" class="btn btn-success btn-add-menu-item" aria-label="Tambah ${result.nama} ke pesanan" title="Tambah ke pesanan" onclick="addItem(${result.id}, '${result.nama.replace(/'/g, "\\'")}', ${result.harga})"><i class="fas fa-plus" aria-hidden="true"></i></button></td>
                    `;
                    $('#addMenuModal').modal('hide');
                    Swal.fire({
                        icon: 'success', // Ensure the success icon is displayed
                        title: 'Success',
                        text: result.message,
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: result.message,
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan saat menyimpan menu. Silakan coba lagi.',
                });
            });
        });
    </script>
@endsection

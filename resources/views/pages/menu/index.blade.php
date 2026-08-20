@extends('layouts.main')

@section('header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Menu</h1>
        </div>
    </div>
@endsection

@section('content')
<div class="p-3 bg-white rounded shadow-sm">
    <div class="row mb-3">
        <div class="col-md-3">
            <input type="text" id="search" class="form-control" placeholder="Search menu..." onkeyup="filterMenu()">
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
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
                        <tr>
                            <td>{{ $menu->id }}</td>
                            <td>{{ $menu->nama }}</td>
                            <td>{{ $menu->harga }}</td>
                            <td><button class="btn btn-success" onclick="addItem({{ $menu->id }}, '{{ $menu->nama }}', {{ $menu->harga }})">+</button></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-4">
            <h3>Pesanan</h3>
            <div class="mb-3">
                <input type="number" id="no-meja" class="form-control mb-2" min="1" max="999" placeholder="Nomor Meja" onblur="checkTransactionByTable()">
                <input type="text" id="atas-nama" class="form-control" placeholder="Atas Nama Konsumen">
            </div>
            <div style="height: 400px; overflow-y: auto;">
                <table class="table table-bordered" id="selected-items">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Note</th>
                            <th>Jumlah</th>
                            <th>Harga</th>
                            <th>Subtotal</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <div class="mt-3 d-flex align-items-center">
                <h5 class="mr-3">Total Harga:</h5>
                <input type="text" id="total-harga" value="0" readonly class="form-control" style="width: 150px; display: inline-block; margin-right: 10px;">
                <button class="btn btn-primary" onclick="saveOrder()">Simpan</button>
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

    <script>
        let items = [];

        function addItem(id, nama, harga) {
            let existingItem = items.find(item => item.menu_id === id);
            if (existingItem) {
                existingItem.jumlah++;
                existingItem.subtotal = existingItem.jumlah * existingItem.harga;
            } else {
                items.push({ menu_id: id, nama: nama, note: '-', jumlah: 1, harga: harga, subtotal: harga });
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
                    <td><input type="text" class="form-control form-control-sm" value="${item.note}" onchange="updateItemNote(${index}, this.value)"></td>
                    <td>${item.jumlah}</td>
                    <td>${item.harga}</td>
                    <td>${item.subtotal}</td>
                    <td><button class="btn btn-danger" onclick="removeItem(${index})">-</button></td>
                `;
            });
        }

        function updateTotalHarga() {
            const totalHarga = items.reduce((total, item) => total + item.subtotal, 0);
            document.getElementById('total-harga').value = totalHarga.toFixed(2);
        }

        function updateItemNote(index, note) {
            items[index].note = note || '-';
        }

        // Fungsi untuk cek transaksi berdasarkan nomor meja
        function checkTransactionByTable() {
            const noMeja = document.getElementById('no-meja').value.trim();
            const atasNamaInput = document.getElementById('atas-nama');

            if (!noMeja) {
                atasNamaInput.value = '';
                atasNamaInput.style.backgroundColor = '#fff';
                return;
            }

            fetch("{{ route('transaksi.getByTable') }}?nomeja=" + encodeURIComponent(noMeja), {
                method: "GET",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                }
            })
            .then(response => response.json())
            .then(result => {
                if (result.found) {
                    // Isi field atas nama dengan data dari transaksi sebelumnya
                    atasNamaInput.value = result.atasnama;
                    atasNamaInput.style.backgroundColor = '#e8f5e9'; // Highlight warna hijau untuk menunjukkan data auto-filled
                } else {
                    // Kosongkan field atas nama jika tidak ditemukan
                    atasNamaInput.value = '';
                    atasNamaInput.style.backgroundColor = '#fff3e0'; // Highlight warna oranye untuk menunjukkan input baru
                }
            })
            .catch(error => {
                console.error('Error:', error);
                atasNamaInput.value = '';
                atasNamaInput.style.backgroundColor = '#fff';
            });
        }

        function saveOrder() {
    const noMeja = document.getElementById('no-meja').value.trim();
    const atasNama = document.getElementById('atas-nama').value.trim();

    if (!/^\d+$/.test(noMeja) || Number(noMeja) < 1 || Number(noMeja) > 999) {
        Swal.fire({
            icon: 'warning',
            title: 'Warning',
            text: 'Nomor meja wajib diisi dengan angka antara 1 sampai 999.',
        });
        return;
    }

    if (!atasNama || items.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Warning',
            text: 'Harap isi atas nama dan pilih minimal satu item!',
        });
        return;
    }

    const data = {
        nomeja: Number(noMeja),
        atasnama: atasNama,
        items: items
    };

    fetch("{{ route('transaksi.store') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(result => {
        console.log('Response:', result); // Debug respons dari server

        if (result.success) {
            Swal.fire({
                icon: 'success', // ✅ Tampilkan icon centang
                title: 'Success',
                text: result.message,
            }).then(() => {
                window.location.reload(); // Reload setelah klik OK
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
        console.error('Error:', error); // Debug error
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Terjadi kesalahan saat menyimpan transaksi. Silakan coba lagi.',
        });
    });
}


        function filterMenu() {
            const searchValue = document.getElementById('search').value.toLowerCase();
            const rows = document.querySelectorAll('#menu-table-body tr');
            rows.forEach(row => {
                const nama = row.cells[1].textContent.toLowerCase();
                if (nama.includes(searchValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
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
                    newRow.innerHTML = `
                        <td>${result.id}</td>
                        <td>${result.nama}</td>
                        <td>${result.harga}</td>
                        <td><button class="btn btn-success" onclick="addItem(${result.id}, '${result.nama.replace(/'/g, "\\'")}', ${result.harga})">+</button></td>
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


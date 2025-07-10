@extends('layouts.main')

@section('content')
<div class="container mt-4">
    <h2>Master Tambah Barang</h2>
    <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#addModal">Tambah Barang</button>

    <!-- Stylesheet untuk DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">

    <table class="table table-bordered" id="barangTable">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Harga</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($menus as $menu)
            <tr id="menu-row-{{ $menu->id }}">
                <td>{{ $menu->nama }}</td>
                <td>Rp{{ number_format($menu->harga, 0, ',', '.') }}</td>
                <td>
                    <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editModal-{{ $menu->id }}">Edit</button>
                    <button class="btn btn-danger btn-sm" onclick="deleteMenu({{ $menu->id }})">Hapus</button>
                </td>
            </tr>

            <!-- Modal Edit -->
            <div class="modal fade" id="editModal-{{ $menu->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form method="POST" action="{{ route('barang.update', $menu->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Menu</h5>
                                <button type="button" class="close" data-dismiss="modal">
                                    <span>&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>Nama</label>
                                    <input type="text" name="nama" class="form-control" value="{{ $menu->nama }}" required>
                                </div>
                                <div class="form-group">
                                    <label>Harga</label>
                                    <input type="number" name="harga" class="form-control" value="{{ $menu->harga }}" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-primary" type="submit">Simpan</button>
                                <button class="btn btn-secondary" data-dismiss="modal" type="button">Tutup</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('barang.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Barang</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Harga</label>
                        <input type="number" name="harga" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="submit">Simpan</button>
                    <button class="btn btn-secondary" data-dismiss="modal" type="button">Tutup</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JS & DataTables -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function () {
        $('#barangTable').DataTable({
            paging: true,
            searching: true,
            ordering: true,
            info: true,
            lengthMenu: [5, 10, 25, 50],
            language: {
                paginate: { previous: '«', next: '»' },
                search: 'Cari:',
                lengthMenu: 'Tampilkan _MENU_ Data',
                info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ Data'
            }
        });
    });

    function deleteMenu(id) {
        Swal.fire({
            title: 'Yakin ingin menghapus menu ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/barang/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: result.message
                        });
                        document.querySelector(`#menu-row-${id}`).remove();
                    } else {
                        Swal.fire('Error', result.message, 'error');
                    }
                })
                .catch(error => {
                    console.error(error);
                    Swal.fire('Error', 'Terjadi kesalahan saat menghapus.', 'error');
                });
            }
        });
    }
</script>
@endsection

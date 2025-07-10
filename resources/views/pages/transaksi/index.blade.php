@extends('layouts.main')

@section('header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1>Data Transaksi</h1>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <!-- Filter Status -->
                <div class="row mb-3">
                    <div class="col text-right">
                        <button id="btnTransaction" class="btn btn-primary">Transaksi</button>
                        <button id="btnHistory" class="btn btn-secondary ml-2">Histori</button>
                    </div>
                </div>

                <!-- Filter Tanggal -->
                <div class="mb-3 d-flex align-items-center">
                    <label for="filterTanggalMulai" class="mr-2">Dari:</label>
                    <input type="date" id="filterTanggalMulai" class="form-control" style="width: 170px;">
                    <label for="filterTanggalSampai" class="ml-2 mr-2">Sampai:</label>
                    <input type="date" id="filterTanggalSampai" class="form-control" style="width: 170px;">
                    <button id="resetTanggal" class="btn btn-secondary btn-sm ml-2">Reset</button>
                </div>

                <!-- Stylesheets -->
                <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
                <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

                <!-- Tabel Data -->
                <table class="table table-bordered" id="kolomData">
                    <thead>
                        <tr>
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
                        @foreach($transaksis as $transaksi)
                        <tr>
                            <td>{{ $transaksi->kode }}</td>
                            <td>{{ $transaksi->nomeja }}</td>
                            <td>{{ $transaksi->atasnama }}</td>
                            <td>{{ $transaksi->details->count() }}</td>
                            <td>
                                @if($transaksi->status === 'Lunas')
                                    <span class="badge badge-success">Lunas</span>
                                @elseif($transaksi->status === 'Completed')
                                    <span class="badge badge-primary">Selesai</span>
                                @elseif($transaksi->status === 'pending')
                                    <span class="badge badge-info">Makanan Siap</span>
                                @elseif($transaksi->status === 'Processing' || $transaksi->status === 'sedang_diproses')
                                    <span class="badge badge-warning">Sedang Diproses</span>
                                @elseif($transaksi->status === 'belum_diproses')
                                    <span class="badge badge-secondary">Belum Diproses</span>
                                @else
                                    <span class="badge badge-light">Belum Diproses</span>
                                @endif
                            </td>
                            <td data-order="{{ strtotime($transaksi->created_at) }}">
                                {{ date('d-m-Y H:i', strtotime($transaksi->created_at)) }}
                            </td>
                            <td>
                                <button class="btn btn-info btn-detail" data-toggle="modal" data-target="#detailModal-{{ $transaksi->id }}">Lihat</button>
                                @if($transaksi->status === 'pending')
                                    <form action="{{ route('transaksi.updateStatus', $transaksi->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="status" value="Completed">
                                        <button type="submit" class="btn btn-success ml-1">Selesai</button>
                                    </form>
                                @endif
                            </td>
                        </tr>

                        <!-- Modal Detail -->
                        <div class="modal fade" id="detailModal-{{ $transaksi->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Detail Transaksi</h5>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        <p><strong>Nomor Meja:</strong> {{ $transaksi->nomeja }}</p>
                                        <p><strong>Atas Nama:</strong> {{ $transaksi->atasnama }}</p>
                                        <p><strong>Total Harga:</strong> Rp{{ number_format($transaksi->totalharga, 2, ',', '.') }}</p>
                                        <p><strong>Status:</strong> {{ $transaksi->status }}</p>
                                        <h5>Menu Dipesan:</h5>
                                        <ul>
                                            @foreach($transaksi->details as $detail)
                                            <li>{{ $detail->menu->nama }} - {{ $detail->jumlah }} x Rp{{ number_format($detail->harga, 2, ',', '.') }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                    <tfoot>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function () {
        const table = $('#kolomData').DataTable({
            paging: true,
            searching: true,
            ordering: true,
            info: true,
            lengthMenu: [5, 10, 25, 50],
            order: [[5, 'desc']],
            columnDefs: [
                { orderable: false, targets: [6] }
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
            const status = data[4].toLowerCase();
            const isHistory = $('#btnHistory').hasClass('active');
            const isTransaction = $('#btnTransaction').hasClass('active');

            if (!isHistory && !isTransaction) return true;

            if (isHistory) {
                return status.includes('lunas') || status.includes('selesai');
            } else {
                return status.includes('siap') ||
                       status.includes('diproses') ||
                       status.includes('pending') ||
                       status.includes('belum');
            }
        }

        $.fn.dataTable.ext.search.push(statusFilter);
        $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
            const min = $('#filterTanggalMulai').val();
            const max = $('#filterTanggalSampai').val();
            const tanggalStr = data[5];
            if (!min && !max) return true;

            const tglSplit = tanggalStr.split(' ')[0].split('-');
            const tgl = new Date(`${tglSplit[2]}-${tglSplit[1]}-${tglSplit[0]}`);
            const minDate = min ? new Date(min) : null;
            const maxDate = max ? new Date(max) : null;

            return (!minDate || tgl >= minDate) && (!maxDate || tgl <= maxDate);
        });

        function updateTotalPendapatan() {
            let total = 0;
            table.rows({ search: 'applied' }).every(function () {
                const hargaStr = this.data()[3].replace(/[^\d,]/g, '').replace(/\./g, '').replace(',', '.');
                total += parseFloat(hargaStr) || 0;
            });
            $('#fieldTotalPendapatan').val('Rp' + total.toLocaleString('id-ID') + ',00');
        }

        $('#filterTanggalMulai, #filterTanggalSampai').on('change', function () {
            table.draw();
            updateTotalPendapatan();
        });

        $('#btnTransaction').on('click', function() {
            $(this).addClass('active');
            $('#btnHistory').removeClass('active');
            table.draw();
            updateTotalPendapatan();
        });

        $('#btnHistory').on('click', function() {
            $(this).addClass('active');
            $('#btnTransaction').removeClass('active');
            table.draw();
            updateTotalPendapatan();
        });

        $('#resetTanggal').on('click', function () {
            $('#filterTanggalMulai, #filterTanggalSampai').val('');
            table.draw();
            updateTotalPendapatan();
        });

        table.on('draw', updateTotalPendapatan);
        updateTotalPendapatan();
    });
</script>
@endsection

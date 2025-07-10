@extends('layouts.main')

@section('content')
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
                @foreach($koki as $item)
                    @php
                        $showInTransaksi = in_array($item->status, ['belum_diproses', 'sedang_diproses', 'pending']);
                        $showInHistory = in_array($item->status, ['Completed', 'Lunas']);
                    @endphp
                    <tr class="rowTransaksi {{ $showInTransaksi ? 'transaksi' : '' }} {{ $showInHistory ? 'history' : '' }}">
                        <td data-order="{{ strtotime($item->created_at) }}">{{ $item->created_at->format('d-m-Y H:i') }}</td>
                        <td>
                            @foreach($item->details as $detail)
                                {{ $detail->menu->nama }} ({{ $detail->jumlah }}),
                            @endforeach
                        </td>
                        <td>
                            @switch($item->status)
                                @case('belum_diproses') <span class="badge badge-secondary">Belum Diproses</span> @break
                                @case('sedang_diproses') <span class="badge badge-warning">Sedang Diproses</span> @break
                                @case('pending') <span class="badge badge-info">Makanan Siap</span> @break
                                @case('Completed') <span class="badge badge-primary">Selesai</span> @break
                                @case('Lunas') <span class="badge badge-success">Lunas</span> @break
                                @default <span class="badge badge-light">-</span>
                            @endswitch
                        </td>
                        <td>
                            <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#detailModal-{{ $item->id }}">Lihat</button>
                            @if($item->status === 'sedang_diproses')
                            <form action="{{ route('transaksi.updateStatus', $item->id) }}" method="POST" class="d-inline">
                                @csrf
                                <input type="hidden" name="status" value="pending">
                                <button type="submit" class="btn btn-success btn-sm ml-1">Selesai</button>
                            </form>
                            @endif
                        </td>
                    </tr>

                    <!-- Modal di luar table -->
                    <div class="modal fade" id="detailModal-{{ $item->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Detail Pesanan</h5>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>Nomor Meja:</strong> {{ $item->nomeja }}</p>
                                    <p><strong>Atas Nama:</strong> {{ $item->atasnama }}</p>
                                    <h5>Menu Dipesan:</h5>
                                    <ul>
                                        @foreach($item->details as $detail)
                                            <li>{{ $detail->menu->nama }} - {{ $detail->jumlah }} x Rp{{ number_format($detail->harga, 2) }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="modal-footer">
                                    @if($item->status === 'belum_diproses')
                                    <form action="{{ route('transaksi.updateStatus', $item->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="sedang_diproses">
                                        <button type="submit" class="btn btn-warning">Proses</button>
                                    </form>
                                    @endif
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
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
@endsection

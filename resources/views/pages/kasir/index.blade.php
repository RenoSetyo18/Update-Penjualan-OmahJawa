@extends('layouts.main')

@section('header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Kasir</h1>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">

        <div class="col-md-12">
            <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
            <div class="card">
    <div class="card-header d-flex align-items-center">
    <h3 class="card-title mb-0">Daftar Transaksi</h3>
    <div class="ml-auto">
        <a id="btnCetak" class="btn btn-outline-primary btn-sm" target="_blank">
            <i class="fas fa-print mr-1"></i> Cetak
        </a>
    </div>
</div>

    <div class="card-body">
        <!-- Filter Status -->
        <div class="row mb-3">
            <div class="col text-right">
                <button id="btnTransaction" class="btn btn-primary">Transaksi</button>
                <button id="btnHistory" class="btn btn-secondary ml-2">Histori</button>
            </div>
        </div>

        <div class="row mb-3 align-items-center">
            <div class="col-auto d-flex align-items-center">
                <label for="filterTanggalMulai" class="mr-2 mb-0">Dari:</label>
                <input type="date" id="filterTanggalMulai" class="form-control form-control-sm mr-2" style="width: 140px;">
            </div>
            <div class="col-auto d-flex align-items-center">
                <label for="filterTanggalSampai" class="mr-2 mb-0">Sampai:</label>
                <input type="date" id="filterTanggalSampai" class="form-control form-control-sm mr-2" style="width: 140px;">
            </div>
            <div class="col-auto">
                <button id="resetTanggal" class="btn btn-secondary btn-sm">Reset</button>
            </div>
        </div>
        <table class="table table-bordered" id="transaksiTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nomor Meja</th>
                                <th>Atas Nama</th>
                                <th>Total Harga</th>
                                <th>Status</th>
                                <th>Tanggal Transaksi</th>
                                <th>Detail Pesanan</th>
                                <th>Bayar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transaksis as $index => $transaksi)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $transaksi->nomeja }}</td>
                                    <td>{{ $transaksi->atasnama }}</td>
                                    <td>Rp{{ number_format($transaksi->totalharga, 2) }}</td>
                                    <td>
                                        @switch($transaksi->status)
                                            @case('belum_diproses')
                                                <span class="badge badge-secondary">Belum Diproses</span>
                                                @break
                                            @case('sedang_diproses')
                                                <span class="badge badge-warning">Sedang Diproses</span>
                                                @break
                                            @case('pending')
                                                <span class="badge badge-info">Makanan Siap</span>
                                                @break
                                            @case('Completed')
                                                <span class="badge badge-primary">Selesai</span>
                                                @break
                                            @case('Lunas')
                                                <span class="badge badge-success">Lunas</span>
                                                @break
                                            @default
                                                <span class="badge badge-light">Status Tidak Diketahui</span>
                                        @endswitch
                                    </td>

                                    <td data-order="{{ strtotime($transaksi->created_at) }}">
                                        {{ date('d-m-Y H:i', strtotime($transaksi->created_at)) }}
                                    </td>
                                    <td>
                                        <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#detailModal-{{ $transaksi->id }}">Lihat</button>
                                    </td>
                                    <td>
                                        @if($transaksi->status === 'Lunas')
                                            <button class="btn btn-secondary" disabled>Lunas</button>
                                        @else
                                            <button class="btn btn-primary" data-toggle="modal" data-target="#bayarModal-{{ $transaksi->id }}">Bayar</button>
                                        @endif
                                    </td>
                                </tr>

                                <!-- Modal Detail Pesanan -->
                                <div class="modal fade" id="detailModal-{{ $transaksi->id }}" tabindex="-1" aria-labelledby="detailModalLabel-{{ $transaksi->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="detailModalLabel-{{ $transaksi->id }}">Detail Pesanan - Meja {{ $transaksi->nomeja }}</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
                                            </div>
                                            <div class="modal-body">
                                                <p><strong>Atas Nama:</strong> {{ $transaksi->atasnama }}</p>
                                                <h5>Menu Dipesan:</h5>
                                                <ul>
                                                    @foreach($transaksi->details as $detail)
                                                        <li>{{ $detail->menu->nama }} - {{ $detail->jumlah }} x Rp{{ number_format($detail->harga, 2) }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Modal Detail Pesanan -->

                                <!-- Modal Pembayaran -->
                                <div class="modal fade" id="bayarModal-{{ $transaksi->id }}" tabindex="-1" aria-labelledby="bayarModalLabel-{{ $transaksi->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="bayarModalLabel-{{ $transaksi->id }}">Pembayaran - Meja {{ $transaksi->nomeja }}</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
                                            </div>
                                            <div class="modal-body">
                                                <form class="form-bayar" data-id="{{ $transaksi->id }}" action="{{ route('kasir.bayar', $transaksi->id) }}" method="POST" target="">

                                                    @csrf
                                                    <div class="form-group">
                                                        <label>Total Harga</label>
                                                        <input type="text" class="form-control total-harga"
                                                            id="total-harga-{{ $transaksi->id }}"
                                                            value="{{ $transaksi->totalharga }}" readonly>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Jumlah Bayar</label>
                                                        <input type="number" class="form-control bayar-input"
                                                            id="bayar-{{ $transaksi->id }}" name="jumlah_bayar" required
                                                            data-id="{{ $transaksi->id }}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Kembalian</label>
                                                        <input type="text" class="form-control kembalian"
                                                            id="kembalian-{{ $transaksi->id }}" readonly>
                                                    </div>


                                                    <div class="form-group mt-3">
                                                        <label>Cetak Struk?</label><br>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="cetak_struk" id="cetakYa-{{ $transaksi->id }}" value="ya" checked>
                                                            <label class="form-check-label" for="cetakYa-{{ $transaksi->id }}">Ya</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="cetak_struk" id="cetakTidak-{{ $transaksi->id }}" value="tidak">
                                                            <label class="form-check-label" for="cetakTidak-{{ $transaksi->id }}">Tidak</label>
                                                        </div>
                                                    </div>

                                                    <button type="submit" class="btn btn-success btn-block mt-3">Konfirmasi Pembayaran</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Modal Pembayaran -->
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-right">Total Harga Keseluruhan:</th>
                                <th>
                                    <input type="text" id="fieldTotalHargaKeseluruhan" class="form-control" style="width:160px;display:inline-block;" readonly value="Rp0,00">
                                </th>
                                <th colspan="4"></th>
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
@endsection

@section('scripts')
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

@section('scripts')
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function () {
    let lastBayarModalId = null;

    const table = $('#transaksiTable').DataTable({
        paging: true,
        searching: true,
        ordering: true,
        info: true,
        lengthMenu: [5, 10, 25, 50, 100],
        order: [[5, 'desc']],
        columnDefs: [{ orderable: false, targets: [0, 6, 7] }],
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
        return status.includes('lunas');
    } else {
        return !status.includes('lunas'); // Semua selain 'lunas'
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

        return (minDate === null || tgl >= minDate) && (maxDate === null || tgl <= maxDate);
    });

    $('#filterTanggalMulai, #filterTanggalSampai').on('change', function () {
        table.draw();
        updateTotalHargaKeseluruhan();
    });

    $('#btnTransaction').on('click', function() {
        $(this).addClass('active');
        $('#btnHistory').removeClass('active');
        table.draw();
        updateTotalHargaKeseluruhan();
    });

    $('#btnHistory').on('click', function() {
        $(this).addClass('active');
        $('#btnTransaction').removeClass('active');
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
    updateTotalHargaKeseluruhan();

    function updateTotalHargaKeseluruhan() {
        let total = 0;
        table.rows({ search: 'applied' }).every(function () {
            const hargaStr = this.data()[3];
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

    $('.form-bayar').on('submit', function (e) {
        const $form = $(this);
        const id = $form.data('id');
        const cetakStruk = $form.find('input[name="cetak_struk"]:checked').val();

        // Simpan ID untuk pengecekan balik nanti
        window.lastBayarModalId = id;

        // Tutup modal pembayaran sebelum submit
        $('#bayarModal-' + id).modal('hide');

        if (cetakStruk === 'ya') {
            $form.attr('target', '_blank'); // buka di tab baru
        } else {
            $form.removeAttr('target');
        }

        return true; // lanjut submit
    });

    $('.modal').on('hidden.bs.modal', function () {
        const $form = $(this).find('form');
        if ($form.length) {
            $form[0].reset();
            $(this).find('.kembalian').val('');
        }
    });

    $('#modalStruk').on('hidden.bs.modal', function () {
        $('#iframeStruk').attr('src', '');
    });

    // Ketika user kembali dari tab struk
    $(window).on('focus', function () {
        if (window.lastBayarModalId) {
            $('#bayarModal-' + window.lastBayarModalId).modal('hide');
            $('#modalStruk').modal('hide');
            $('#iframeStruk').attr('src', '');
            $('.form-bayar').each(function () {
                this.reset();
                $(this).closest('.modal').find('.kembalian').val('');
            });

            table.draw();
            window.lastBayarModalId = null;
        }
    });
    $('#btnCetak').on('click', function (e) {
    const dari = $('#filterTanggalMulai').val();
    const sampai = $('#filterTanggalSampai').val();

    let url = "{{ route('kasir.cetak.rekap') }}";
    if (dari && sampai) {
        url += `?dari=${encodeURIComponent(dari)}&sampai=${encodeURIComponent(sampai)}`;
    }

    $(this).attr('href', url);
});

});
</script>
@endsection




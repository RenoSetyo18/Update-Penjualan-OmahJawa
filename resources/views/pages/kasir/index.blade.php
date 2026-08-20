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
            <div class="col-md-8 d-flex align-items-center flex-wrap">
                <label for="filterTanggalMulai" class="mr-2 mb-0">Dari:</label>
                <input type="date" id="filterTanggalMulai" class="form-control form-control-sm mr-2 mb-2" style="width: 140px;">
                <label for="filterTanggalSampai" class="mr-2 mb-0">Sampai:</label>
                <input type="date" id="filterTanggalSampai" class="form-control form-control-sm mr-2 mb-2" style="width: 140px;">
                <button id="showTanggal" class="btn btn-primary btn-sm mr-2 mb-2">Tampilkan</button>
                <button id="resetTanggal" class="btn btn-secondary btn-sm mb-2">Reset</button>
            </div>
            <div class="col-md-4 text-right">
                <button id="btnRekap" class="btn btn-outline-primary ml-2" data-toggle="modal" data-target="#rekapModal"><i class="fa fa-print mr-1"></i>Cetak Rekap</button>
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
                            @foreach($groupedTransaksis as $index => $transaksi)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $transaksi->nomeja }}</td>
                                    <td>{{ $transaksi->atasnama }}</td>
                                    <td>Rp{{ number_format($transaksi->totalharga, 2) }}</td>
                                    <td>
                                        @if($transaksi->isOnlineOrder())
                                            <span class="badge badge-success">Sudah Bayar Online</span>
                                        @else
                                            @switch($transaksi->status)
                                                @case('belum_diproses')
                                                @case('sedang_diproses')
                                                @case('makanan_siap')
                                                    <span class="badge badge-secondary">Menunggu</span>
                                                    @break
                                                @case('selesai')
                                                    <span class="badge badge-warning">Bayar</span>
                                                    @break
                                                @case('lunas')
                                                    <span class="badge badge-success">Lunas</span>
                                                    @break
                                                @default
                                                    <span class="badge badge-light">{{ \App\Models\Transaksi::kasirStatusLabel($transaksi->status) }}</span>
                                            @endswitch
                                        @endif
                                    </td>

                                    <td data-order="{{ strtotime($transaksi->created_at) }}">
                                        {{ date('d-m-Y H:i', strtotime($transaksi->created_at)) }}
                                    </td>
                                    <td>
                                        <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#detailModal-{{ $transaksi->id }}">Lihat</button>
                                    </td>
                                    <td>
                                        @if($transaksi->isAlreadyPaidOnline())
                                            <button class="btn btn-secondary" disabled>Lunas</button>
                                        @elseif($transaksi->status === 'selesai')
                                            <button class="btn btn-primary" data-toggle="modal" data-target="#bayarModal-{{ $transaksi->id }}">Bayar</button>
                                        @else
                                            <button class="btn btn-secondary" disabled>Menunggu</button>
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
                                                        <li>{{ $detail->menu->nama }}@if(!empty($detail->note) && trim($detail->note) !== '-') ({{ $detail->note }})@endif - {{ $detail->jumlah }} x Rp{{ number_format($detail->harga, 2) }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                            <div class="modal-footer">
                                                @if($transaksi->status === 'lunas' || $transaksi->isOnlineOrder())
                                                    <a href="{{ route('kasir.cetak.struk', $transaksi->id) }}" target="_blank" class="btn btn-outline-primary">
                                                        <i class="fa fa-print mr-1"></i> Cetak Struk
                                                    </a>
                                                @endif
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
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
                                                <form class="form-bayar" data-id="{{ $transaksi->id }}" data-meja="{{ $transaksi->nomeja }}" action="{{ route('kasir.bayar', $transaksi->id) }}" method="POST" target="">

                                                    @csrf
                                                    <div class="form-group">
                                                        <label>Total Harga</label>
                                                        @php
                                                            // Total yang dibayar adalah pesanan yang sedang dipilih, bukan seluruh meja
                                                            $totalHargaGabungan = $transaksi->totalharga;
                                                        @endphp
                                                        <input type="text" class="form-control total-harga"
                                                            id="total-harga-{{ $transaksi->id }}"
                                                            value="{{ $totalHargaGabungan }}" readonly>
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
                                                            id="bayar-{{ $transaksi->id }}" name="jumlah_bayar" required
                                                            data-id="{{ $transaksi->id }}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Kembalian</label>
                                                        <input type="text" class="form-control kembalian"
                                                            id="kembalian-{{ $transaksi->id }}" readonly>
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
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-success shadow-sm">
                <div class="modal-header bg-success text-white border-0">
                    <h5 class="modal-title" id="paymentSuccessModalLabel"><i class="fa fa-check-circle mr-2"></i>Pembayaran Berhasil</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4">
                    <div class="text-center mb-4">
                        <div class="badge badge-pill badge-success px-4 py-2 shadow-sm">Berhasil</div>
                    </div>
                    <div class="card border-success shadow-sm mb-4">
                        <div class="card-body text-center">
                            <p class="text-muted mb-2">Total Pembayaran</p>
                            <h3 class="font-weight-bold text-success">Rp<span id="successTotalHarga"></span></h3>
                        </div>
                    </div>
                    <p class="text-center text-secondary mb-0">Pembayaran telah dicatat. Silakan cetak struk atau lanjutkan untuk kembali ke menu Kasir.</p>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="btn btn-outline-success px-4" id="btnPrintStruk"><i class="fa fa-print mr-2"></i>Cetak Struk</button>
                    <button type="button" class="btn btn-success px-4" id="btnFinishPayment"><i class="fa fa-check mr-2"></i>Selesaikan Transaksi</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
$(document).ready(function () {
    console.log('Kasir page script loaded');
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
        const selectedTab = $('.tab-filter .nav-link.active').data('status') || 'semua';
        if (selectedTab === 'semua') {
            return true;
        }

        const statusHtml = data[4] || '';
        const statusText = $('<div>').html(statusHtml).text().trim().toLowerCase();
        const isOnline = statusText.includes('pesanan online') || statusText.includes('online');

        if (selectedTab === 'belum') {
            return !statusText.includes('lunas') && !isOnline;
        }

        if (selectedTab === 'selesai') {
            return statusText.includes('lunas') || isOnline;
        }

        return true;
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
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => { throw new Error(text || ('HTTP ' + response.status)); });
            }
            return response.json();
        })
        .then(data => {
            if (!data.success) {
                throw new Error(data.message || 'Gagal memproses pembayaran');
            }

            $('#bayarModal-' + id).modal('hide');
            $('#successTotalHarga').text(data.total_harga_formatted);
            $('#btnPrintStruk').data('print-url', data.print_url);
            $('#paymentSuccessModal').modal('show');

            const meja = $form.data('meja');
            table.rows().every(function () {
                const rowNode = this.node();
                const rowMejaText = $(rowNode).find('td').eq(1).text().trim();
                if (rowMejaText == meja) {
                    $(rowNode).find('td').eq(4).html('<span class="badge badge-success">Lunas</span>');
                    $(rowNode).find('td').eq(7).html('<button class="btn btn-secondary" disabled>Lunas</button>');
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
        const printUrl = $(this).data('print-url');
        if (printUrl) {
            window.open(printUrl, '_blank');
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
                window.location.href = '{{ route('kasir.index') }}';
            });
        } else {
            alert('Transaksi telah selesai.');
            window.location.href = '{{ route('kasir.index') }}';
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
        let url = '{{ route('kasir.cetak.rekap') }}';
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
            const tanggalStr = row[5] || '';
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
            menunggu: 0,
            bayar: 0,
            lunas: 0
        };

        function stripTags(html) {
            const tmp = document.createElement('div');
            tmp.innerHTML = html;
            return tmp.textContent || tmp.innerText || '';
        }

        function mapStatusLabel(statusText) {
            const value = stripTags(statusText).trim().toLowerCase();
            if (value.includes('lunas') || value.includes('pesanan online') || value.includes('online')) {
                return 'Lunas';
            }
            if (value.includes('bayar')) {
                return 'Bayar';
            }
            return 'Menunggu';
        }

        let rowsHtml = '';
        for (let i = 0; i < rows.length; i++) {
            const row = rows[i];
            const rawStatus = row[4] || '';
            const statusText = stripTags(rawStatus);
            const mappedStatus = mapStatusLabel(statusText);
            const hargaStr = stripTags(row[3]);
            const hargaAngka = parseInt(hargaStr.replace('Rp', '').replace(/\./g, '').replace(',00', '')) || 0;
            totalHarga += hargaAngka;

            if (mappedStatus === 'Lunas') {
                statusCounts.lunas += 1;
            } else if (mappedStatus === 'Bayar') {
                statusCounts.bayar += 1;
            } else {
                statusCounts.menunggu += 1;
            }

            rowsHtml += `<tr>
                <td>${i + 1}</td>
                <td>${stripTags(row[1])}</td>
                <td>${stripTags(row[2])}</td>
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
    <div class="col-md-4 mb-3">
        <div class="p-3 rounded bg-light border h-100 text-center">
            <div class="text-muted small mb-1">Menunggu</div>
            <div class="h3 fw-bold text-secondary">
                ${statusCounts.menunggu}
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-3">
        <div class="p-3 rounded bg-light border h-100 text-center">
            <div class="text-muted small mb-1">Bayar</div>
            <div class="h3 fw-bold text-warning">
                ${statusCounts.bayar}
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-3">
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
                <th>Nomor Meja</th>
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
                const tanggalStr = (row[5] || '').split(' ')[0];
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
@endsection




@extends('layouts.main')

@section('content')
<style>
@media screen {
    .main-sidebar {
        display: block !important;
        visibility: visible !important;
    }
    .main-sidebar .user-panel .image img {
        display: block !important;
        visibility: visible !important;
    }
}
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Laporan Penjualan</h3>
                    <div class="card-tools">
                        <form method="GET" action="{{ route('laporan.penjualan') }}" class="form-inline">
                            <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
                            <div class="form-group mr-2">
                                <label for="start_date" class="mr-2">Tanggal Mulai:</label>
                                <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request()->get('start_date', $start) }}">
                            </div>
                            <div class="form-group mr-2">
                                <label for="end_date" class="mr-2">Tanggal Akhir:</label>
                                <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request()->get('end_date', $end) }}">
                            </div>
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="{{ route('laporan.cetak-penjualan', ['start_date' => request()->get('start_date', $start), 'end_date' => request()->get('end_date', $end)]) }}" class="btn btn-secondary ml-2">Cetak</a>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                    <span class="info-box-text">Jumlah Transaksi</span>
                                    <span class="info-box-number">{{ number_format($jumlahTransaksi) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Penjualan</span>
                                    <span class="info-box-number">Rp {{ number_format($totalPenjualan) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                    <span class="info-box-text">Periode</span>
                                    <span class="info-box-number text-sm">{{ \Carbon\Carbon::parse($start)->format('d M Y') }} - {{ \Carbon\Carbon::parse($end)->format('d M Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <form method="GET" action="{{ route('laporan.penjualan') }}" class="form-inline">
                            <input type="hidden" name="start_date" value="{{ request('start_date', $start) }}">
                            <input type="hidden" name="end_date" value="{{ request('end_date', $end) }}">
                            <label for="per_page" class="mr-2">Tampilkan:</label>
                            <select name="per_page" id="per_page" class="form-control form-control-sm" onchange="this.form.submit()">
                                @foreach([5, 10, 25, 50] as $size)
                                    <option value="{{ $size }}" {{ request('per_page', 10) == $size ? 'selected' : '' }}>{{ $size }}</option>
                                @endforeach
                            </select>
                        </form>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Atas Nama</th>
                                    <th>Total Harga</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transaksis as $transaksi)
                                <tr>
                                    <td>{{ $transaksi->kode }}</td>
                                    <td>{{ $transaksi->atasnama }}</td>
                                    <td>Rp {{ number_format($transaksi->totalharga) }}</td>
                                    <td><span class="badge badge-info">{{ \App\Models\Transaksi::transaksiStatusLabel($transaksi->status) }}</span></td>
                                    <td>{{ $transaksi->created_at->format('d M Y') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada data transaksi dalam periode ini</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            Menampilkan {{ $transaksis->firstItem() ?? 0 }} sampai {{ $transaksis->lastItem() ?? 0 }} dari {{ $transaksis->total() }} transaksi
                        </div>
                        <div>
                            {{ $transaksis->onEachSide(1)->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
                <script>
                    function printReport(url) {
                        fetch(url, { credentials: 'same-origin' })
                            .then(response => response.text())
                            .then(html => {
                                // remove any auto-print calls from the fetched HTML to avoid double print
                                html = html.replace(/window\.print\(\);?/gi, '');
                                html = html.replace(/<body([^>]*)onload=(['"])?[^>]*window\.print\(\)[^>]*\2([^>]*)>/gi, '<body$1$3>');
                                html = html.replace(/onafterprint\s*=\s*(["']).*?\1/gi, '');

                                const iframe = document.createElement('iframe');
                                iframe.style.position = 'fixed';
                                iframe.style.right = '0';
                                iframe.style.bottom = '0';
                                iframe.style.width = '0';
                                iframe.style.height = '0';
                                iframe.style.border = '0';
                                document.body.appendChild(iframe);

                                const idoc = iframe.contentWindow.document;
                                idoc.open();
                                idoc.write(html);
                                idoc.close();

                                const doPrintOnce = () => {
                                    try {
                                        iframe.contentWindow.focus();
                                        iframe.contentWindow.print();
                                    } catch (e) {
                                        window.print();
                                    }
                                    setTimeout(() => {
                                        if (iframe && iframe.parentNode) iframe.parentNode.removeChild(iframe);
                                    }, 1000);
                                };

                                iframe.onload = doPrintOnce;
                                setTimeout(doPrintOnce, 800);
                            })
                            .catch(err => {
                                alert('Gagal memuat halaman cetak: ' + err);
                            });
                    }
                </script>
                @endsection

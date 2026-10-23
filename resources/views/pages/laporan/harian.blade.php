@extends('layouts.main')

@section('content')
<style>
    @media print {
        .main-sidebar, .main-header, .content-header, .no-print, .card-tools {
            display: none !important;
        }
        .content-wrapper, .container-fluid, .card, .card-body {
            margin: 0 !important;
            padding: 0 !important;
            box-shadow: none !important;
            width: 100% !important;
        }
        .card {
            border: 0 !important;
        }
    }
</style>

<div class="container-fluid">
    @include('components.laporan-menu')

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Laporan Harian</h3>
            <div class="card-tools no-print">
                <form method="GET" action="{{ route('laporan.harian') }}" class="form-inline">
                    <label for="tanggal" class="mr-2">Tanggal:</label>
                    <input type="date" name="tanggal" id="tanggal" class="form-control mr-2" value="{{ $tanggal }}">
                    <button type="submit" class="btn btn-primary mr-2">Tampilkan</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="window.print()">
                        <i class="fas fa-print mr-1"></i>Cetak
                    </button>
                </form>
            </div>
        </div>
        <div class="card-body">
            <h4 class="mb-3">Pendapatan tanggal {{ \Carbon\Carbon::parse($tanggal)->format('d/m/Y') }}</h4>
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="info-box bg-light">
                        <div class="info-box-content">
                            <span class="info-box-text">Total Pendapatan</span>
                            <span class="info-box-number">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-box bg-light">
                        <div class="info-box-content">
                            <span class="info-box-text">Transaksi Lunas</span>
                            <span class="info-box-number">{{ number_format($transaksis->count()) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Kode</th>
                            <th>Meja</th>
                            <th>Atas Nama</th>
                            <th>Waktu Pembayaran</th>
                            <th>Metode Pembayaran</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transaksis as $index => $transaksi)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $transaksi->kode }}</td>
                                <td>{{ $transaksi->nomeja }}</td>
                                <td>{{ $transaksi->atasnama }}</td>
                                <td>{{ \Carbon\Carbon::parse($transaksi->paid_at)->format('H:i') }}</td>
                                <td>{{ $transaksi->metode_pembayaran }}</td>
                                <td>Rp {{ number_format($transaksi->totalharga, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Belum ada pembayaran lunas pada tanggal ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="6" class="text-right">Total Pendapatan</th>
                            <th>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

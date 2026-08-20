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
<div class="container">
    <h1>Tren Penjualan</h1>
    <p class="text-muted mb-4">Lihat perkembangan penjualan dan bandingkan dengan periode sebelumnya.</p>

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('laporan.tren-penjualan') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="start_date" class="form-label">Tanggal Mulai</label>
                    <input type="date" id="start_date" name="start_date" class="form-control" value="{{ request()->get('start_date', $start) }}">
                </div>
                <div class="col-md-4">
                    <label for="end_date" class="form-label">Tanggal Akhir</label>
                    <input type="date" id="end_date" name="end_date" class="form-control" value="{{ request()->get('end_date', $end) }}">
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                    <button type="button" onclick="window.print()" class="btn btn-secondary">Cetak</button>
                </div>
            </form>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Penjualan</h5>
                    <p class="h3">Rp {{ number_format($totalSales, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Rata-rata per Hari</h5>
                    <p class="h3">Rp {{ number_format($averageSales, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Periode Sebelumnya</h5>
                    <p class="mb-1">{{ \Carbon\Carbon::parse($start)->subDays(
                        \Carbon\Carbon::parse($start)->diffInDays(\Carbon\Carbon::parse($end)) + 1
                    )->format('d M Y') }} - {{ \Carbon\Carbon::parse($start)->subDay()->format('d M Y') }}</p>
                    <p class="h4 mb-0">Rp {{ number_format($previousTotalSales, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Perubahan Tren</h4>
            <p class="mb-2">{{ $trendValue >= 0 ? 'Naik' : 'Turun' }} Rp {{ number_format(abs($trendValue), 0, ',', '.') }} ({{ number_format(abs($trendPercent), 1) }}%) dibanding periode sebelumnya.</p>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Total Penjualan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dailySales as $date => $total)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($date)->format('d M Y') }}</td>
                            <td>Rp {{ number_format($total, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="text-center">Tidak ada data penjualan untuk periode ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

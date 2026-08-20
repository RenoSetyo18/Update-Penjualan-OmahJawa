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
    <h1>Laporan Transaksi</h1>

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('laporan.transaksi') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="start_date" class="form-label">Tanggal Mulai</label>
                    <input type="date" id="start_date" name="start_date" class="form-control" value="{{ request()->get('start_date') }}">
                </div>
                <div class="col-md-4">
                    <label for="end_date" class="form-label">Tanggal Akhir</label>
                    <input type="date" id="end_date" name="end_date" class="form-control" value="{{ request()->get('end_date') }}">
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                    <button type="button" onclick="window.print()" class="btn btn-secondary">Cetak</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Daftar Transaksi</h4>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Kode</th>
                            <th>Meja</th>
                            <th>Atas Nama</th>
                            <th>Total Harga</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transaksis as $transaksi)
                        <tr>
                            <td>{{ $transaksi->id }}</td>
                            <td>{{ $transaksi->kode }}</td>
                            <td>{{ $transaksi->nomeja }}</td>
                            <td>{{ $transaksi->atasnama }}</td>
                            <td>Rp {{ number_format($transaksi->totalharga, 0, ',', '.') }}</td>
                            <td>{{ \App\Models\Transaksi::transaksiStatusLabel($transaksi->status) }}</td>
                            <td>{{ $transaksi->created_at->format('d M Y H:i') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada transaksi pada periode ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

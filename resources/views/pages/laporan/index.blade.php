@extends('layouts.main')

@section('content')
<div class="container">
    <h1>Laporan Transaksi</h1>

    <!-- Tombol Cetak dengan parameter filter -->
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('laporan.cetak', ['start_date' => request('start_date'), 'end_date' => request('end_date')]) }}"
           class="btn btn-primary" target="_blank">
            <i class="fas fa-print"></i> Cetak
        </a>
    </div>

    <!-- Form Filter Laporan -->
    <form method="GET" action="{{ route('laporan.index') }}" class="mb-3">
        <div class="row">
            <div class="col-md-5">
                <input type="date" name="start_date" class="form-control"
                       value="{{ request('start_date') }}">
            </div>
            <div class="col-md-5">
                <input type="date" name="end_date" class="form-control"
                       value="{{ request('end_date') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </div>
    </form>

    <!-- Tabel Laporan -->
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tanggal</th>
                <th>Total Harga</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksis as $transaksi)
            <tr>
                <td>{{ $transaksi->id }}</td>
                <td>{{ $transaksi->created_at->format('Y-m-d H:i') }}</td>
                <td>Rp {{ number_format($transaksi->totalharga, 0, ',', '.') }}</td>
                <td>{{ $transaksi->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if($transaksis->isEmpty())
        <div class="alert alert-warning text-center">Tidak ada transaksi pada periode ini.</div>
    @endif
</div>
@endsection

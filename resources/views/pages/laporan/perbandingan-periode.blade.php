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
    <h1>Perbandingan Antar Periode</h1>
    <p class="text-muted mb-4">Bandingkan performa pendapatan antara dua periode yang berurutan.</p>

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('laporan.perbandingan-periode') }}" class="row g-3 align-items-end">
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
                    <h5 class="card-title">Periode Saat Ini</h5>
                    <p class="mb-1">{{ \Carbon\Carbon::parse($start)->format('d M Y') }} - {{ \Carbon\Carbon::parse($end)->format('d M Y') }}</p>
                    <p class="h4">Rp {{ number_format($currentTotal, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Periode Sebelumnya</h5>
                    <p class="mb-1">{{ \Carbon\Carbon::parse($previousStart)->format('d M Y') }} - {{ \Carbon\Carbon::parse($previousEnd)->format('d M Y') }}</p>
                    <p class="h4">Rp {{ number_format($previousTotal, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Perbedaan</h5>
                    <p class="h4 mb-1">Rp {{ number_format($differenceValue, 0, ',', '.') }}</p>
                    <p class="mb-0 {{ $differenceValue >= 0 ? 'text-success' : 'text-danger' }}">
                        {{ $differenceValue >= 0 ? 'Kenaikan' : 'Penurunan' }} {{ number_format(abs($differencePercent), 1) }}%
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Ringkasan Perbandingan</h4>
            <p class="card-text">Perbandingan ini mengambil periode sebelumnya dengan panjang yang sama dan menampilkan total pendapatan untuk kedua periode.</p>
            <ul>
                <li>Periode saat ini: {{ \Carbon\Carbon::parse($start)->format('d M Y') }} - {{ \Carbon\Carbon::parse($end)->format('d M Y') }}</li>
                <li>Periode sebelumnya: {{ \Carbon\Carbon::parse($previousStart)->format('d M Y') }} - {{ \Carbon\Carbon::parse($previousEnd)->format('d M Y') }}</li>
            </ul>
        </div>
    </div>
</div>
@endsection

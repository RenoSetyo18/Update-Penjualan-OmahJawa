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
    <h1>Laporan Jam Sibuk (Peak Hour)</h1>
    <p class="text-muted mb-4">Analisis transaksi per jam untuk mengetahui waktu tersibuk dalam sehari.</p>

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('laporan.jam-sibuk') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="date" class="form-label">Tanggal</label>
                    <input type="date" id="date" name="date" class="form-control" value="{{ request()->get('date', $date) }}">
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                    <button type="button" onclick="window.print()" class="btn btn-secondary">Cetak</button>
                </div>
            </form>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Tanggal Analisis</h5>
                    <p class="h4">{{ \Carbon\Carbon::parse($date)->format('d M Y') }}</p>
                </div>
            </div>
        </div>
        @if($peakHour)
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Jam Paling Sibuk</h5>
                    <p class="h4">{{ $peakHour['hour'] }}:00 - {{ $peakHour['hour'] + 1 }}:00</p>
                    <p class="mb-0">{{ $peakHour['jumlah_transaksi'] }} transaksi (Rp {{ number_format($peakHour['total_omzet'], 0, ',', '.') }})</p>
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Detail Transaksi per Jam</h4>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Jam</th>
                            <th>Jumlah Transaksi</th>
                            <th>Total Omzet</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @for($hour = 0; $hour < 24; $hour++)
                        <tr>
                            <td>{{ $hour }}:00 - {{ $hour + 1 }}:00</td>
                            <td>{{ $hourlyData[$hour]['jumlah_transaksi'] ?? 0 }}</td>
                            <td>Rp {{ number_format($hourlyData[$hour]['total_omzet'] ?? 0, 0, ',', '.') }}</td>
                            <td>
                                @if(($hourlyData[$hour]['jumlah_transaksi'] ?? 0) > 0)
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-secondary">Tidak Ada</span>
                                @endif
                            </td>
                        </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

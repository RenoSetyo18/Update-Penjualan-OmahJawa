@extends('layouts.main')

@section('header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Dashboard</h1>
        </div>
        <div class="col-sm-6 text-right">
            <form action="{{ route('dashboard.index') }}" method="GET" class="d-flex align-items-center justify-content-end">
                <label for="startDate" class="mb-0 mr-2">Dari:</label>
                <input type="date" name="startDate" id="startDate" class="form-control d-inline-block w-auto mr-2"
                    value="{{ $startDate }}">
                <label for="endDate" class="mb-0 mr-2">Sampai:</label>
                <input type="date" name="endDate" id="endDate" class="form-control d-inline-block w-auto mr-2"
                    value="{{ $endDate }}">
                <button type="submit" class="btn btn-primary">Tampilkan</button>
            </form>
        </div>
    </div>
@endsection

@section('content')
    @php
        $startDate = $startDate ?? '';
        $endDate = $endDate ?? '';
    @endphp

    <div class="row justify-content-center">
    <div class="col-lg-3 col-md-6">
        <div class="card bg-warning text-white h-100">
            <div class="card-body text-center d-flex flex-column justify-content-center align-items-center" style="min-height: 150px;">
                <h5>Total Transaksi</h5>
                <h3>{{ $totalTransaksiLunas ?? 0 }}</h3>
                <div class="small mt-2">Transaksi yang sudah <b>Lunas</b></div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card bg-info text-white h-100">
            <div class="card-body text-center d-flex flex-column justify-content-center align-items-center" style="min-height: 150px;">
                <h5>Jumlah Pesanan</h5>
                <h3>{{ $totalTransaksi ?? 0 }}</h3>
                <div class="small mt-2">Total transaksi masuk</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mt-3 mt-lg-0">
        <div class="card bg-success text-white h-100">
            <div class="card-body text-center d-flex flex-column justify-content-center align-items-center" style="min-height: 150px;">
                <h5 class="text-wrap mb-1">Total Pendapatan</h5>
                <h3>Rp{{ number_format($totalPendapatanLunas, 2) }}</h3>
            </div>
        </div>
    </div>
</div>


    {{-- Distribusi Status Transaksi Dihapus --}}
    {{-- Transaksi Terbaru Dihapus --}}

    <div class="row mt-4">
        <div class="col-md-6">
            <h3>Grafik Pendapatan Harian</h3>
            <canvas id="incomeChart" height="200"></canvas>
        </div>
        <div class="col-md-6">
            <h3>Grafik Total Penjualan Harian</h3>
            <canvas id="penjualanChart" height="200"></canvas>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        var ctx2 = document.getElementById('incomeChart').getContext('2d');
        var incomeChart = new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: {!! json_encode(array_keys((array) $pendapatanHarian)) !!},
                datasets: [{
                    label: 'Pendapatan Harian (Rp)',
                    data: {!! json_encode(array_values((array) $pendapatanHarian)) !!},
                    backgroundColor: '#007bff'
                }]
            }
        });

        var ctx3 = document.getElementById('penjualanChart').getContext('2d');
        var penjualanChart = new Chart(ctx3, {
            type: 'line',
            data: {
                labels: {!! json_encode(array_keys((array) $penjualanHarian)) !!},
                datasets: [{
                    label: 'Total Penjualan Harian',
                    data: {!! json_encode(array_values((array) $penjualanHarian)) !!},
                    borderColor: '#fd7e14',
                    backgroundColor: 'rgba(253, 126, 20, 0.2)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endsection

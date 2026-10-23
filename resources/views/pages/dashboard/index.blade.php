@extends('layouts.main')

@section('header')
    <div class="row mb-4">
        <div class="col-sm-6">
            <h1 class="h3 mb-0">Dashboard</h1>
            <small class="text-muted" id="currentDateTime"></small>
        </div>
        <div class="col-sm-6 text-right">
            <form action="{{ route('dashboard.index') }}" method="GET" class="dashboard-filter-form">
                <div class="dashboard-filter-field">
                    <label for="startDate" class="font-weight-bold">Dari:</label>
                    <input type="date" name="startDate" id="startDate" class="form-control" value="{{ $startDate }}">
                </div>
                <div class="dashboard-filter-field">
                    <label for="endDate" class="font-weight-bold">Sampai:</label>
                    <input type="date" name="endDate" id="endDate" class="form-control" value="{{ $endDate }}">
                </div>
                <button type="submit" class="btn btn-primary dashboard-filter-button">
                    <i class="fas fa-filter mr-1"></i>Tampilkan
                </button>
            </form>
        </div>
    </div>
    <style>
        /* Soften the dashboard visuals without changing structure */
        .card {
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(20,20,50,0.06);
            transition: transform .18s ease, box-shadow .18s ease;
            overflow: hidden;
        }
        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 30px rgba(20,20,50,0.08);
        }
        .card-header {
            background: transparent;
            border-bottom: none;
            padding: .9rem 1.25rem;
        }
        .card-body {
            padding: 1.25rem;
        }
        .table thead th {
            background: transparent;
            border-bottom: 1px solid #e9ecef;
        }
        .table tbody tr:hover {
            background: rgba(0,0,0,0.02);
        }
        .chart-container { padding: 0.75rem; }
        /* Make welcome header feel less cramped */
        .dashboard-welcome {
    position: relative;
    overflow: hidden;
    background: linear-gradient(135deg, #4D55CC 0%, #5863ED 100%);
    padding: 1.3rem;
    color: white;
    border-radius: 18px;
    min-height: 160px;
}

/* Bubble besar kanan */
.dashboard-welcome::before {
    content: '';
    position: absolute;
    width: 420px;
    height: 420px;
    background: rgba(255,255,255,0.08);
    border-radius: 50%;
    right: -120px;
    top: 20px;
}

/* Bubble atas */
.dashboard-welcome::after {
    content: '';
    position: absolute;
    width: 240px;
    height: 240px;
    background: rgba(255,255,255,0.06);
    border-radius: 50%;
    top: -140px;
    right: 220px;
}

/* Gelombang kanan bawah */
.dashboard-wave {
    position: absolute;
    right: 120px;
    bottom: -90px;
    width: 420px;
    height: 220px;
    background: rgba(255,255,255,0.05);
    border-radius: 50%;
    transform: rotate(-8deg);
}

/* Lingkaran kecil */
.dashboard-circle {
    position: absolute;
    width: 55px;
    height: 55px;
    background: rgba(255,255,255,0.08);
    border-radius: 50%;
    right: 260px;
    bottom: 25px;
}

/* Titik-titik */
.dashboard-dots {
    position: absolute;
    top: 22px;
    right: 460px;
    display: grid;
    grid-template-columns: repeat(4, 8px);
    gap: 10px;
}

.dashboard-dots span {
    width: 4px;
    height: 4px;
    background: rgba(255,255,255,0.5);
    border-radius: 50%;
}

/* Konten utama */
.dashboard-content {
    position: relative;
    z-index: 2;
}
@media (max-width: 992px) {
    .card { border-radius: 8px; }
    .card-body { padding: 1rem; }
}
.dashboard-filter-form {
    display: grid;
    grid-template-columns: minmax(0, 1fr) minmax(0, 1fr) auto;
    align-items: end;
    gap: 0.6rem;
    width: 100%;
    max-width: 100%;
    min-width: 0;
    padding: 0.55rem;
    border: 1px solid #e3eae5;
    border-radius: 12px;
    background: #fff;
    box-shadow: 0 5px 16px rgba(28, 48, 39, 0.06);
}
.dashboard-filter-field {
    min-width: 0;
}
.dashboard-filter-form .dashboard-filter-field label {
    display: block;
    margin: 0 0 0.35rem;
    color: #596a60;
    font-size: 0.9rem;
}
.dashboard-filter-form .form-control {
    width: 100%;
    min-width: 0;
    min-height: 38px;
    border-color: #e1e8e3;
    border-radius: 8px;
    background: #f9fbf9;
}
.dashboard-filter-form .form-control:focus {
    border-color: #7da58d;
    background: #fff;
    box-shadow: 0 0 0 0.2rem rgba(57, 111, 78, 0.12);
}
.dashboard-filter-button {
    justify-self: stretch;
    min-width: 0;
    min-height: 38px;
    margin: 0;
    padding: 0.45rem 0.8rem;
    border: 0 !important;
    border-radius: 8px;
    background: linear-gradient(135deg, #3b6a50, #274a37) !important;
    box-shadow: 0 5px 12px rgba(49, 93, 70, 0.2);
    letter-spacing: 0.01em;
}
.dashboard-filter-button:hover,
.dashboard-filter-button:focus {
    background: linear-gradient(135deg, #315d46, #1f3e2d) !important;
    box-shadow: 0 7px 16px rgba(49, 93, 70, 0.28);
    transform: translateY(-1px);
}
@media (max-width: 1199.98px) {
    .dashboard-filter-form {
        grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
    }
    .dashboard-filter-button {
        grid-column: 1 / -1;
    }
}
.dashboard-metric-card {
    position: relative;
    min-height: 164px;
    overflow: hidden;
    border: 1px solid #e5ebe6;
    border-radius: 16px;
    background: #fff;
    color: #263a30;
    box-shadow: 0 8px 24px rgba(28, 48, 39, 0.07);
    transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
}
.dashboard-metric-card::after {
    position: absolute;
    right: -30px;
    bottom: -62px;
    width: 150px;
    height: 150px;
    border-radius: 50%;
    background: var(--metric-tint);
    content: '';
    pointer-events: none;
}
.dashboard-metric-card:hover,
.dashboard-metric-card:focus-within {
    transform: translateY(-4px);
    border-color: var(--metric-border);
    box-shadow: 0 15px 30px rgba(28, 48, 39, 0.12);
}
.dashboard-metric-card.metric-transactions {
    --metric-color: #315d46;
    --metric-tint: rgba(49, 93, 70, 0.08);
    --metric-border: #c9ddce;
    --metric-icon-bg: #edf5ef;
}
.dashboard-metric-card.metric-revenue {
    --metric-color: #92733f;
    --metric-tint: rgba(199, 165, 106, 0.12);
    --metric-border: #e8dcc4;
    --metric-icon-bg: #f8f2e7;
}
.dashboard-metric-card.metric-best-seller {
    --metric-color: #527d68;
    --metric-tint: rgba(82, 125, 104, 0.1);
    --metric-border: #d1e1d6;
    --metric-icon-bg: #eff6f1;
}
.dashboard-metric-card.metric-daily-chart {
    --metric-color: #596b60;
    --metric-tint: rgba(89, 107, 96, 0.08);
    --metric-border: #d9e1db;
    --metric-icon-bg: #f0f4f1;
}
.dashboard-metric-card .card-body {
    position: relative;
    z-index: 1;
    min-height: 164px;
    padding: 1.25rem !important;
    text-align: left !important;
}
.dashboard-metric-card .metric-icon {
    display: inline-flex;
    width: 46px;
    height: 46px;
    flex: 0 0 46px;
    align-items: center;
    justify-content: center;
    margin-bottom: 1rem;
    border: 1px solid var(--metric-border);
    border-radius: 14px;
    background: var(--metric-icon-bg);
    color: var(--metric-color);
    font-size: 1.1rem;
}
.dashboard-metric-card .metric-label {
    margin-bottom: 0.35rem;
    color: #748078;
    font-size: 0.82rem;
    font-weight: 700;
    letter-spacing: 0.045em;
    text-transform: uppercase;
}
.dashboard-metric-card .metric-value {
    margin-bottom: 0.3rem;
    color: #263a30;
    font-size: clamp(1.35rem, 1.7vw, 1.8rem);
    font-weight: 700;
    letter-spacing: -0.04em;
    line-height: 1.2;
    overflow-wrap: anywhere;
}
.dashboard-metric-card .metric-note {
    color: #89938c;
    font-size: 0.83rem;
}
.dashboard-metric-card .metric-arrow {
    position: absolute;
    top: 1.45rem;
    right: 1.25rem;
    color: #a1aaa3;
    font-size: 0.85rem;
    transition: transform 0.2s ease, color 0.2s ease;
}
.dashboard-metric-card:hover .metric-arrow {
    transform: translateX(3px);
    color: var(--metric-color);
}
.dashboard-chart-card {
    overflow: hidden;
    border: 1px solid #e5ebe6;
    border-radius: 16px;
    background: #fff;
    box-shadow: 0 10px 28px rgba(28, 48, 39, 0.07);
    transition: box-shadow 0.2s ease, transform 0.2s ease;
}
.dashboard-chart-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 16px 34px rgba(28, 48, 39, 0.11);
}
.dashboard-chart-card .card-header {
    position: relative;
    padding: 1rem 1.25rem;
    border-bottom: 1px solid #edf1ed;
    background: linear-gradient(135deg, #fff 0%, #f8faf8 100%) !important;
}
.dashboard-chart-card .card-header .card-title {
    display: flex;
    align-items: center;
    color: #30483a;
    font-size: 1rem;
    font-weight: 700;
    line-height: 1.4;
}
.dashboard-chart-card .card-header .card-title i {
    display: inline-flex;
    width: 34px;
    height: 34px;
    margin-right: 0.65rem !important;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    background: #edf5ef;
    color: #315d46;
    font-size: 0.9rem;
}
.dashboard-chart-card .card-body {
    padding: 1.25rem;
}
.dashboard-chart-card .chart-container {
    min-height: 320px;
    padding: 0.9rem 0.75rem 0.35rem;
    border: 1px solid #edf2ee;
    border-radius: 14px;
    background: linear-gradient(145deg, #fbfdfb 0%, #f6f9f6 100%);
}
.dashboard-chart-card .chart-container canvas {
    display: block;
}
.dashboard-chart-summary {
    padding: 0.9rem 1rem;
    border: 1px solid #e8efe9;
    border-radius: 12px;
    background: #f8fbf8;
}
.dashboard-chart-summary h6 {
    color: #758178;
    font-size: 0.78rem;
    font-weight: 700;
    letter-spacing: 0.04em;
    text-transform: uppercase;
}
.dashboard-chart-summary h3,
.dashboard-chart-summary h5 {
    color: #315d46;
    font-weight: 700;
    letter-spacing: -0.025em;
}
@media (max-width: 767.98px) {
    .dashboard-filter-form {
        grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
        margin-top: 0.75rem;
        padding: 0.75rem;
    }
    .dashboard-filter-button {
        grid-column: 1 / -1;
    }
    .dashboard-chart-card .chart-container {
        min-height: 280px;
    }
}
@media (max-width: 399.98px) {
    .dashboard-filter-form {
        grid-template-columns: minmax(0, 1fr);
    }
    .dashboard-filter-button {
        grid-column: auto;
    }
}
    </style>
@endsection

@section('content')
    @php
        $startDate = $startDate ?? '';
        $endDate = $endDate ?? '';
        $role = Auth::user()->role ?? '';
        $roleLabel = \App\Models\User::roleLabel($role);
    @endphp

    <div class="row mb-2">
        <div class="col-12 mb-0">
            <div class="card shadow-lg border-0 overflow-hidden">
                <div class="card-body p-0">
                    <div class="dashboard-welcome d-flex align-items-center">

    <!-- dekorasi -->
    <div class="dashboard-wave"></div>
    <div class="dashboard-circle"></div>

    <div class="dashboard-dots">
        <span></span><span></span><span></span><span></span>
        <span></span><span></span><span></span><span></span>
        <span></span><span></span><span></span><span></span>
    </div>

    <!-- content -->
    <div class="dashboard-content d-flex align-items-center">
                        <div class="mr-4 d-flex align-items-center justify-content-center rounded-circle" style="width: 70px; height: 70px; background: rgba(255,255,255,0.2); font-size: 1.75rem; font-weight: 700;">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-white-50 mb-1" style="font-size: 0.9rem;">Selamat datang</p>
                            <h4 class="font-weight-bold mb-1">{{ Auth::user()->name }}</h4>
                            <p class="mb-2" style="opacity: 0.85;">{{ Auth::user()->email }}</p>
                            <span class="badge badge-light" style="letter-spacing: 0.05em;">{{ $roleLabel }}</span>
                                </div>
    </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    @if($role === 'kasir')
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-3">
                <a href="{{ route('kasir.index') }}" class="text-decoration-none text-white">
                    <div class="card bg-primary h-100 shadow" style="cursor: pointer;">
                        <div class="card-body text-center d-flex flex-column justify-content-center align-items-center" style="min-height: 150px;">
                            <i class="fas fa-money-bill-wave fa-2x mb-2"></i>
                            <h5 class="card-title">Pendapatan Hari Ini</h5>
                            <h3 class="mb-1">Rp {{ number_format($kasirTodayRevenue, 0, ',', '.') }}</h3>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <a href="{{ route('kasir.index') }}" class="text-decoration-none text-white">
                    <div class="card bg-info h-100 shadow" style="cursor: pointer;">
                        <div class="card-body text-center d-flex flex-column justify-content-center align-items-center" style="min-height: 150px;">
                            <i class="fas fa-receipt fa-2x mb-2"></i>
                            <h5 class="card-title">Transaksi Hari Ini</h5>
                            <h3 class="mb-1">{{ $kasirTodayTransactions }}</h3>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <a href="{{ route('kasir.index') }}" class="text-decoration-none text-white">
                    <div class="card bg-success h-100 shadow" style="cursor: pointer;">
                        <div class="card-body text-center d-flex flex-column justify-content-center align-items-center" style="min-height: 150px;">
                            <i class="fas fa-check fa-2x mb-2"></i>
                            <h5 class="card-title">Lunas Hari Ini</h5>
                            <h3 class="mb-1">{{ $kasirTodayLunas }}</h3>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <a href="{{ route('kasir.index') }}" class="text-decoration-none text-white">
                    <div class="card bg-danger h-100 shadow" style="cursor: pointer;">
                        <div class="card-body text-center d-flex flex-column justify-content-center align-items-center" style="min-height: 150px;">
                            <i class="fas fa-times-circle fa-2x mb-2"></i>
                            <h5 class="card-title">Belum Lunas</h5>
                            <h3 class="mb-1">{{ $kasirTodayBelumLunas }}</h3>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="card shadow">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="fas fa-list-ul mr-2"></i>Data Belum Lunas (Hanya Lihat)
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-sm mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Meja</th>
                                <th>Atas Nama</th>
                                <th>Status</th>
                                <th>Total</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kasirUnpaidTransactions as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->kode ?? '-' }}</td>
                                    <td>{{ $item->nomeja ?? '-' }}</td>
                                    <td>{{ $item->atasnama ?? '-' }}</td>
                                    <td>
                                        <span class="badge badge-warning text-dark">
                                            {{ \App\Models\Transaksi::kasirStatusLabel($item->status) }}
                                        </span>
                                    </td>
                                    <td>Rp {{ number_format($item->totalharga ?? 0, 0, ',', '.') }}</td>
                                    <td>{{ $item->created_at ? $item->created_at->translatedFormat('d M Y H:i') : '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">Tidak ada data tagihan belum lunas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        @if(($transaksiBelumBayar ?? 0) > 0)
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <strong>Perhatian!</strong> Ada <strong>{{ $transaksiBelumBayar }}</strong> transaksi yang belum dibayar.
                <a href="{{ route('kasir.index') }}" class="alert-link">Kelola di Kasir</a>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-3">
                <a href="{{ route('laporan.penjualan') }}" class="text-decoration-none">
                    <div class="card dashboard-metric-card metric-transactions h-100">
                        <div class="card-body">
                            <i class="fas fa-arrow-right metric-arrow" aria-hidden="true"></i>
                            <span class="metric-icon"><i class="fas fa-receipt" aria-hidden="true"></i></span>
                            <div class="metric-label">Total Transaksi</div>
                            <div class="metric-value">{{ $totalTransaksi }}</div>
                            <small class="metric-note">Total transaksi periode ini</small>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <a href="{{ route('laporan.penjualan') }}" class="text-decoration-none">
                    <div class="card dashboard-metric-card metric-revenue h-100">
                        <div class="card-body">
                            <i class="fas fa-arrow-right metric-arrow" aria-hidden="true"></i>
                            <span class="metric-icon"><i class="fas fa-wallet" aria-hidden="true"></i></span>
                            <div class="metric-label">Pendapatan</div>
                            <div class="metric-value">Rp{{ number_format($totalPendapatanLunas, 0, ',', '.') }}</div>
                            <small class="metric-note">Dari transaksi lunas</small>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <a href="javascript:void(0)" onclick="scrollToDashboardCharts()" class="text-decoration-none">
                    <div class="card dashboard-metric-card metric-best-seller h-100">
                        <div class="card-body">
                            <i class="fas fa-arrow-right metric-arrow" aria-hidden="true"></i>
                            <span class="metric-icon"><i class="fas fa-award" aria-hidden="true"></i></span>
                            <div class="metric-label">Menu Paling Laris</div>
                            <div class="metric-value">Top 3</div>
                            <small class="metric-note">Periode ini</small>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <a href="javascript:void(0)" onclick="scrollToDashboardCharts()" class="text-decoration-none">
                    <div class="card dashboard-metric-card metric-daily-chart h-100">
                        <div class="card-body">
                            <i class="fas fa-arrow-right metric-arrow" aria-hidden="true"></i>
                            <span class="metric-icon"><i class="fas fa-chart-line" aria-hidden="true"></i></span>
                            <div class="metric-label">Grafik Harian</div>
                            <div class="metric-value">Pendapatan</div>
                            <small class="metric-note">Perbandingan harian</small>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-lg-6 col-md-12 mb-3">
                <div class="card shadow h-100 dashboard-chart-card">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-chart-line mr-2"></i>Tren Penjualan Mingguan
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-2 text-muted">Bandingkan pendapatan per hari untuk minggu ini dan minggu lalu.</p>
                        <div class="dashboard-chart-summary d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <h6 class="mb-1">Minggu ini</h6>
                                <h3 class="mb-0">Rp {{ number_format($currentWeekSales, 0, ',', '.') }}</h3>
                            </div>
                            <div class="text-right">
                                <h6 class="mb-1">Minggu lalu</h6>
                                <h5 class="mb-0">Rp {{ number_format($previousWeekSales, 0, ',', '.') }}</h5>
                            </div>
                        </div>
                        <div class="chart-container" style="position: relative; min-height: 320px;">
                            <canvas id="weeklyTrendChart" style="height: 100%; width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-md-12 mb-3">
                <div class="card shadow h-100 dashboard-chart-card">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-exchange-alt mr-2"></i>Perbandingan Periode Bulanan
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-2 text-muted">Bandingkan total pendapatan bulan ini dengan bulan lalu.</p>
                        <div class="dashboard-chart-summary d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <h6 class="mb-1">Bulan ini</h6>
                                <h3 class="mb-0">Rp {{ number_format($currentMonthSales, 0, ',', '.') }}</h3>
                            </div>
                            <div class="text-right">
                                <h6 class="mb-1">Bulan lalu</h6>
                                <h5 class="mb-0">Rp {{ number_format($previousMonthSales, 0, ',', '.') }}</h5>
                            </div>
                        </div>
                        <div class="chart-container" style="position: relative; min-height: 320px;">
                            <canvas id="monthlyComparisonChart" style="height: 100%; width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4" id="dashboard-bottom-row">
            <div class="col-lg-4 col-md-12 mb-4">
                <div class="card shadow h-100 dashboard-chart-card">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-chart-bar mr-2"></i>Grafik Pendapatan Harian
                        </h5>
                    </div>
                    <div class="card-body" style="min-height: 360px; padding-bottom: 1.5rem;">
                        @if(empty($pendapatanHarian))
                            <div class="text-center py-4">
                                <i class="fas fa-chart-bar fa-3x text-muted mb-3"></i>
                                <p class="text-muted mb-0">Belum ada data pendapatan untuk periode ini.</p>
                            </div>
                        @else
                            <div class="chart-container" style="position: relative; min-height: 320px;">
                                <canvas id="incomeChart" style="height: 100%; width: 100%;"></canvas>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-12 mb-4">
                <div class="card shadow h-100 dashboard-chart-card">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-credit-card mr-2"></i>Distribusi Penggunaan Metode Pembayaran
                        </h5>
                    </div>
                    <div class="card-body" style="min-height: 360px;">
                        @if($paymentMethodDistribution->isEmpty())
                            <div class="text-center py-4">
                                <i class="fas fa-credit-card fa-3x text-muted mb-3"></i>
                                <p class="text-muted mb-0">Belum ada transaksi lunas dengan metode pembayaran pada periode ini.</p>
                            </div>
                        @else
                            <div class="chart-container" style="position: relative; min-height: 320px;">
                                <canvas id="paymentMethodChart" style="height: 100%; width: 100%;"></canvas>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-12 mb-4">
                <div class="d-flex flex-column h-100">
                    <div class="card shadow mb-3 flex-fill">
                        <div class="card-header bg-light">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-trophy mr-2"></i>Top 3 Menu Paling Laris
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($bestSellingOverall->isEmpty())
                                <div class="text-center py-4">
                                    <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                                    <p class="text-muted mb-0">Belum ada data penjualan untuk periode ini.</p>
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-hover table-sm mb-0">
                                        <thead class="thead-light">
                                            <tr>
                                                <th class="border-0">Posisi</th>
                                                <th class="border-0">Menu</th>
                                                <th class="border-0 text-center">Jumlah</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($bestSellingOverall as $index => $item)
                                                <tr>
                                                    <td class="font-weight-bold">{{ $index + 1 }}</td>
                                                    <td>{{ $item->menu_nama }}</td>
                                                    <td class="text-center">{{ $item->total_jumlah }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="card shadow flex-fill">
                        <div class="card-header bg-light">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-star mr-2"></i>Menu Paling Laris per Kategori
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($bestSellingByJenis->isEmpty())
                                <div class="text-center py-4">
                                    <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                                    <p class="text-muted mb-0">Belum ada data penjualan untuk periode ini.</p>
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-hover table-sm mb-0">
                                        <thead class="thead-light">
                                            <tr>
                                                <th class="border-0">Kategori</th>
                                                <th class="border-0">Menu</th>
                                                <th class="border-0 text-center">Jumlah</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($bestSellingByJenis as $item)
                                                <tr>
                                                    <td class="font-weight-bold">{{ $item->nama_jenis }}</td>
                                                    <td>{{ $item->menu_nama }}</td>
                                                    <td class="text-center">{{ $item->total_jumlah }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(in_array($role, ['admin', 'super_admin'], true))
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            Chart.defaults.font.family = '"Source Sans Pro", "Segoe UI", sans-serif';
            Chart.defaults.color = '#748078';
            Chart.defaults.plugins.legend.labels.usePointStyle = true;
            Chart.defaults.plugins.legend.labels.boxWidth = 8;
            Chart.defaults.plugins.legend.labels.padding = 18;
            Chart.defaults.plugins.tooltip.backgroundColor = '#203c2d';
            Chart.defaults.plugins.tooltip.titleColor = '#ffffff';
            Chart.defaults.plugins.tooltip.bodyColor = '#f2f7f3';
            Chart.defaults.plugins.tooltip.padding = 12;
            Chart.defaults.plugins.tooltip.cornerRadius = 10;

            var ctxWeeklyTrend = document.getElementById('weeklyTrendChart');
            if (ctxWeeklyTrend) {
                ctxWeeklyTrend = ctxWeeklyTrend.getContext('2d');
                var weeklyTrendLabels = {!! json_encode($weeklyTrendLabels) !!};
                var weeklyTrendCurrent = {!! json_encode($weeklyTrendCurrent) !!};
                var weeklyTrendPrevious = {!! json_encode($weeklyTrendPrevious) !!};
                new Chart(ctxWeeklyTrend, {
                    type: 'line',
                    data: {
                        labels: weeklyTrendLabels,
                        datasets: [
                            {
                                label: 'Minggu Ini',
                                data: weeklyTrendCurrent,
                                borderColor: '#315d46',
                                backgroundColor: 'rgba(49, 93, 70, 0.12)',
                                fill: true,
                                tension: 0.42,
                                borderWidth: 3,
                                pointRadius: 3,
                                pointHoverRadius: 6,
                                pointBackgroundColor: '#ffffff',
                                pointBorderWidth: 2
                            },
                            {
                                label: 'Minggu Lalu',
                                data: weeklyTrendPrevious,
                                borderColor: '#c7a56a',
                                backgroundColor: 'rgba(199, 165, 106, 0.1)',
                                fill: true,
                                tension: 0.42,
                                borderWidth: 3,
                                pointRadius: 3,
                                pointHoverRadius: 6,
                                pointBackgroundColor: '#ffffff',
                                pointBorderWidth: 2
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(49, 93, 70, 0.08)',
                                    drawBorder: false
                                },
                                ticks: {
                                    padding: 10,
                                    callback: function(value) {
                                        return 'Rp' + value.toLocaleString('id-ID');
                                    }
                                }
                            },
                            x: {
                                grid: {
                                    display: false,
                                    drawBorder: false
                                },
                                ticks: {
                                    maxRotation: 0,
                                    minRotation: 0,
                                    padding: 8
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                position: 'bottom'
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return context.dataset.label + ': Rp ' + context.parsed.y.toLocaleString('id-ID');
                                    }
                                }
                            }
                        }
                    }
                });
            }

            var ctxMonthlyCompare = document.getElementById('monthlyComparisonChart');
            if (ctxMonthlyCompare) {
                ctxMonthlyCompare = ctxMonthlyCompare.getContext('2d');
                var monthlyComparisonLabels = {!! json_encode($monthlyComparisonLabels) !!};
                var monthlyComparisonValues = {!! json_encode($monthlyComparisonValues) !!};
                new Chart(ctxMonthlyCompare, {
                    type: 'bar',
                    data: {
                        labels: monthlyComparisonLabels,
                        datasets: [{
                            label: 'Pendapatan (Rp)',
                            data: monthlyComparisonValues,
                            backgroundColor: ['rgba(49, 93, 70, 0.86)', 'rgba(199, 165, 106, 0.88)'],
                            borderColor: ['#315d46', '#c7a56a'],
                            borderWidth: 1,
                            borderRadius: 10,
                            borderSkipped: false,
                            maxBarThickness: 76
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(49, 93, 70, 0.08)',
                                    drawBorder: false
                                },
                                ticks: {
                                    padding: 10,
                                    callback: function(value) {
                                        return 'Rp' + value.toLocaleString('id-ID');
                                    }
                                }
                            },
                            x: {
                                grid: {
                                    display: false,
                                    drawBorder: false
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                    }
                                }
                            }
                        }
                    }
                });
            }

            @if(!empty($pendapatanHarian))
            var ctx2 = document.getElementById('incomeChart');
            if (ctx2) {
                ctx2 = ctx2.getContext('2d');
                var rawLabels = {!! json_encode(array_keys($pendapatanHarian)) !!};
                var formattedLabels = rawLabels.map(function(date) {
                    var d = new Date(date);
                    return d.toLocaleDateString('id-ID', { day: '2-digit', month: 'short' });
                });
                var incomeChart = new Chart(ctx2, {
                    type: 'bar',
                    data: {
                        labels: formattedLabels,
                        datasets: [{
                            label: 'Pendapatan Harian (Rp)',
                            data: {!! json_encode(array_values($pendapatanHarian)) !!},
                            backgroundColor: 'rgba(49, 93, 70, 0.82)',
                            borderColor: '#315d46',
                            borderWidth: 1,
                            borderRadius: 7,
                            borderSkipped: false,
                            maxBarThickness: 34
                        }]
                    },
                    options: {
                        indexAxis: 'x',
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(49, 93, 70, 0.08)',
                                    drawBorder: false
                                },
                                ticks: {
                                    padding: 10,
                                    callback: function(value) {
                                        return 'Rp' + value.toLocaleString('id-ID');
                                    }
                                }
                            },
                            x: {
                                ticks: {
                                    maxRotation: 0,
                                    minRotation: 0,
                                    padding: 8
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top'
                            }
                        }
                    }
                });
            }
            @endif

            var ctxStatus = document.getElementById('statusChart');
            if (ctxStatus) {
                ctxStatus = ctxStatus.getContext('2d');
                var statusChart = new Chart(ctxStatus, {
                    type: 'pie',
                    data: {
                        labels: ['Belum Bayar', 'Lunas'],
                        datasets: [{
                            data: [
                                {{ $transaksiBelumBayar ?? 0 }},
                                {{ $totalTransaksiLunas ?? 0 }}
                            ],
                            backgroundColor: [
                                '#c76b58',
                                '#4b8c68'
                            ],
                            borderColor: '#fff',
                            borderWidth: 4,
                            hoverOffset: 8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 20,
                                    usePointStyle: true
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        var label = context.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        label += context.parsed + ' transaksi';
                                        return label;
                                    }
                                }
                            }
                        }
                    }
                });
            }

            var ctxPaymentMethod = document.getElementById('paymentMethodChart');
            if (ctxPaymentMethod) {
                ctxPaymentMethod = ctxPaymentMethod.getContext('2d');
                var paymentLabels = {!! json_encode($paymentMethodDistribution->pluck('metode_pembayaran')) !!};
                var paymentData = {!! json_encode($paymentMethodDistribution->pluck('count')) !!};
                var paymentColors = ['#315d46', '#c7a56a', '#6f947b', '#d18a70', '#8eaa90', '#b58a4c'];

                new Chart(ctxPaymentMethod, {
                    type: 'doughnut',
                    data: {
                        labels: paymentLabels,
                        datasets: [{
                            data: paymentData,
                            backgroundColor: paymentColors.slice(0, paymentLabels.length),
                            borderColor: '#fff',
                            borderWidth: 4,
                            hoverOffset: 8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '68%',
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    usePointStyle: true,
                                    padding: 16
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        var label = context.label || '';
                                        var value = context.parsed;
                                        if (label) {
                                            label += ': ';
                                        }
                                        label += value + ' transaksi';
                                        return label;
                                    }
                                }
                            }
                        }
                    }
                });
            }

            function scrollToDashboardCharts() {
                // Scroll ke Grafik Pendapatan Harian
                var chartsSection = document.querySelectorAll('.card');
                var targetSection = null;

                // Mencari card dengan title "Grafik Pendapatan Harian"
                for (var i = 0; i < chartsSection.length; i++) {
                    var cardHeader = chartsSection[i].querySelector('.card-header');
                    if (cardHeader && cardHeader.textContent.includes('Grafik Pendapatan Harian')) {
                        targetSection = chartsSection[i];
                        break;
                    }
                }

                // Jika tidak ketemu, cari berdasarkan ID atau fallback
                if (!targetSection) {
                    var incomeChart = document.getElementById('incomeChart');
                    if (incomeChart) {
                        targetSection = incomeChart.closest('.card');
                    }
                }

                // Scroll ke target dengan smooth animation
                if (targetSection) {
                    targetSection.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }

            function updateDateTime() {
                var now = new Date();
                var options = {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit'
                };
                document.getElementById('currentDateTime').textContent = now.toLocaleDateString('id-ID', options);
            }

            updateDateTime();
            setInterval(updateDateTime, 1000);
        </script>
    @else
        <script>
            function updateDateTime() {
                var now = new Date();
                var options = {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit'
                };
                document.getElementById('currentDateTime').textContent = now.toLocaleDateString('id-ID', options);
            }

            updateDateTime();
            setInterval(updateDateTime, 1000);
        </script>
    @endif
@endsection

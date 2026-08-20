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
                    <h3 class="card-title">Laporan Metode Pembayaran</h3>
                    <div class="card-tools">
                        <form method="GET" class="form-inline">
                            <div class="form-group mr-2">
                                <label for="start_date" class="mr-2">Tanggal Mulai:</label>
                                <input type="date" name="start_date" id="start_date" class="form-control" value="{{ $start }}">
                            </div>
                            <div class="form-group mr-2">
                                <label for="end_date" class="mr-2">Tanggal Akhir:</label>
                                <input type="date" name="end_date" id="end_date" class="form-control" value="{{ $end }}">
                            </div>
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="{{ route('laporan.cetak-metode-pembayaran', ['start_date' => $start, 'end_date' => $end]) }}" class="btn btn-secondary ml-2">Cetak</a>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Transaksi</span>
                                    <span class="info-box-number">{{ number_format($totalTransaksi) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Pendapatan</span>
                                    <span class="info-box-number">Rp {{ number_format($totalPendapatan) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Layout Baru: Tabel dan Grafik Sebelah Menyebelah -->
                    <div class="row mb-4">
                        <!-- Tabel di Sebelah Kiri -->
                        <div class="col-md-8">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Metode Pembayaran</th>
                                            <th>Jumlah Transaksi</th>
                                            <th>Persentase Transaksi</th>
                                            <th>Total Pendapatan</th>
                                            <th>Persentase Pendapatan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($metodeStats as $stat)
                                        <tr>
                                            <td>
                                                <span class="badge badge-primary">{{ $stat->metode_pembayaran }}</span>
                                            </td>
                                            <td>{{ number_format($stat->jumlah_transaksi) }}</td>
                                            <td>
                                                {{ $totalTransaksi > 0 ? number_format(($stat->jumlah_transaksi / $totalTransaksi) * 100, 1) : 0 }}%
                                            </td>
                                            <td>Rp {{ number_format($stat->total_pendapatan) }}</td>
                                            <td>
                                                {{ $totalPendapatan > 0 ? number_format(($stat->total_pendapatan / $totalPendapatan) * 100, 1) : 0 }}%
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center">Tidak ada data transaksi dalam periode ini</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Diagram Pie Chart di Sebelah Kanan -->
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header bg-primary text-white p-2">
                                    <h5 class="card-title mb-0">Distribusi Penggunaan Metode Pembayaran</h5>
                                </div>
                                <div class="card-body p-3" style="display: flex; align-items: center; justify-content: center; min-height: 280px;">
                                    <div style="width: 220px; height: 220px; display: flex; align-items: center; justify-content: center;">
                                        <canvas id="pieChart" style="max-width: 100%; max-height: 100%;"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Data untuk grafik
    const metodeData = {!! json_encode($metodeStats->pluck('metode_pembayaran')->toArray()) !!};
    const transaksiData = {!! json_encode($metodeStats->pluck('jumlah_transaksi')->toArray()) !!};
    const warna = ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'];

    // Pie Chart - Distribusi Penggunaan
    const pieCtx = document.getElementById('pieChart').getContext('2d');
    new Chart(pieCtx, {
        type: 'doughnut',
        data: {
            labels: metodeData,
            datasets: [{
                data: transaksiData,
                backgroundColor: warna.slice(0, metodeData.length),
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const total = transaksiData.reduce((a, b) => a + b, 0);
                            const percentage = ((context.raw / total) * 100).toFixed(1);
                            return context.label + ': ' + context.raw + ' (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });
    // Bar Chart dihapus - tidak lagi digunakan

</script>
<script>
    function printReport(url) {
        fetch(url, { credentials: 'same-origin' })
            .then(response => response.text())
            .then(html => {
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

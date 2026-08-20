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
    <h1>Laporan</h1>
    <p class="text-muted mb-4">Pilih laporan yang ingin ditampilkan. Halaman ini menjadi master menu laporan untuk mengakses semua laporan yang tersedia.</p>

    @include('components.laporan-menu')

    <div class="card mt-3">
        <div class="card-body">
            <h5 class="card-title">Master Laporan</h5>
            <p class="card-text mb-0">Gunakan menu di atas untuk mengakses laporan:</p>
            <ul class="mb-0">
                <li>Laporan Transaksi</li>
                <li>Laporan Penjualan</li>
                <li>Menu Terlaris</li>
            </ul>
        </div>
    </div>
</div>
@endsection

<div class="row mb-4">
    <div class="col-md-4 mb-2">
        <a href="{{ route('laporan.transaksi') }}" class="btn {{ request()->routeIs('laporan.transaksi') ? 'btn-primary' : 'btn-outline-primary' }} btn-block py-3">
            <i class="fas fa-file-alt mr-2"></i> Laporan Transaksi
        </a>
    </div>
    <div class="col-md-4 mb-2">
        <a href="{{ route('laporan.penjualan') }}" class="btn {{ request()->routeIs('laporan.penjualan') ? 'btn-primary' : 'btn-outline-primary' }} btn-block py-3">
            <i class="fas fa-shopping-cart mr-2"></i> Laporan Penjualan
        </a>
    </div>
    <div class="col-md-4 mb-2">
        <a href="{{ route('laporan.menu-terlaris') }}" class="btn {{ request()->routeIs('laporan.menu-terlaris') ? 'btn-primary' : 'btn-outline-primary' }} btn-block py-3">
            <i class="fas fa-star mr-2"></i> Menu Terlaris
        </a>
    </div>
</div>

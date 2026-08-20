{{--
FILE UNTUK DITAMBAHKAN KE SIDEBAR/NAVBAR DI LAYOUT UTAMA
Path yang mungkin: resources/views/layouts/sidebar.blade.php
atau tambahkan snippet ini ke dalam struktur menu yang sudah ada
--}}

<!-- Master Data Menu Group -->
<li class="nav-item has-treeview {{ request()->is('master*', 'karyawan*', 'jenis*', 'master-menu*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link {{ request()->is('master*', 'karyawan*', 'jenis*', 'master-menu*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-database"></i>
        <p>
            Master Data
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <!-- Master Karyawan -->
        <li class="nav-item">
            <a href="{{ route('karyawan.index') }}"
               class="nav-link {{ request()->is('karyawan*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Master Karyawan</p>
            </a>
        </li>

        <!-- Master Jenis Menu -->
        <li class="nav-item">
            <a href="{{ route('jenis.index') }}"
               class="nav-link {{ request()->is('jenis*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Master Jenis Menu</p>
            </a>
        </li>

        <!-- Master Menu -->
        <li class="nav-item">
            <a href="{{ route('master-menu.index') }}"
               class="nav-link {{ request()->is('master-menu*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Master Menu</p>
            </a>
        </li>

        <!-- Master Menu -->
        <li class="nav-item">
            <a href="{{ route('master-menu.index') }}"
               class="nav-link {{ request()->is('master-menu*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Master Menu</p>
            </a>
        </li>
    </ul>
</li>

<!-- Laporan Menu Group -->
<li class="nav-item has-treeview {{ request()->is('laporan*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link {{ request()->is('laporan*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-chart-line"></i>
        <p>
            Laporan
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <!-- Laporan Penjualan -->
        <li class="nav-item">
            <a href="{{ route('laporan.penjualan') }}"
               class="nav-link {{ request()->is('laporan/penjualan') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Laporan Penjualan</p>
            </a>
        </li>

        <!-- Laporan Status Pesanan -->
        <li class="nav-item">
            <a href="{{ route('laporan.status-pesanan') }}"
               class="nav-link {{ request()->is('laporan/status-pesanan') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Laporan Status Pesanan</p>
            </a>
        </li>

        <!-- Laporan Menu Terlaris -->
        <li class="nav-item">
            <a href="{{ route('laporan.menu-terlaris') }}"
               class="nav-link {{ request()->is('laporan/menu-terlaris') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Laporan Menu Terlaris</p>
            </a>
        </li>

        <!-- Laporan Pendapatan per Kategori -->
        <li class="nav-item">
            <a href="{{ route('laporan.pendapatan-kategori') }}"
               class="nav-link {{ request()->is('laporan/pendapatan-kategori') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Laporan Pendapatan per Kategori</p>
            </a>
        </li>

        <!-- Laporan Pembayaran -->
        <li class="nav-item">
            <a href="{{ route('laporan.pembayaran') }}"
               class="nav-link {{ request()->is('laporan/pembayaran') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Laporan Pembayaran</p>
            </a>
        </li>
    </ul>
</li>

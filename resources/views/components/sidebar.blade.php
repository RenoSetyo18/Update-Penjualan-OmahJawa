<aside class="main-sidebar sidebar-dark-primary elevation-4" style="background-color: #4D55CC; color: white;">
    <div class="sidebar">
      <!-- User Panel -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex flex-column align-items-center">
        <div class="image">
          <img src="dataset/dist/img/LogoOmahJawa.png" class="img-circle elevation-2" alt="User Image" style="width: 100px; height: 100px;">
        </div>
        <div class="info mt-2">
          <a href="#" class="d-block" style="color: white;">OMAH JAWA</a>
        </div>
      </div>

      <!-- Search -->
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search" style="background-color: white; color: black;">
          <div class="input-group-append">
            <button class="btn btn-sidebar" style="background-color: white;">
              <i class="fas fa-search fa-fw" style="color: black;"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

          {{-- Admin --}}
          @if(Auth::user()->role === 'admin')
          <li class="nav-item">
            <a href="/dashboard" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}" style="color: white;">
              <i class="nav-icon fas fa-home"></i>
              <p>Dashboard</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="/menu" class="nav-link {{ request()->is('menu') ? 'active' : '' }}" style="color: white;">
              <i class="nav-icon fas fa-utensils"></i>
              <p>Menu</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="/transaksi" class="nav-link {{ request()->is('transaksi') ? 'active' : '' }}" style="color: white;">
              <i class="nav-icon fas fa-exchange-alt"></i>
              <p>Transaksi</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="/kasir" class="nav-link {{ request()->is('kasir') ? 'active' : '' }}" style="color: white;">
              <i class="nav-icon fas fa-cash-register"></i>
              <p>Kasir</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="/koki" class="nav-link {{ request()->is('koki') ? 'active' : '' }}" style="color: white;">
              <i class="nav-icon fas fa-user-tie"></i>
              <p>Koki</p>
            </a>
          </li>
          @endif

          {{-- Pelayan --}}
          @if(Auth::user()->role === 'pelayan')
          <li class="nav-item">
            <a href="/menu" class="nav-link {{ request()->is('menu') ? 'active' : '' }}" style="color: white;">
              <i class="nav-icon fas fa-utensils"></i>
              <p>Menu</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="/transaksi" class="nav-link {{ request()->is('transaksi') ? 'active' : '' }}" style="color: white;">
              <i class="nav-icon fas fa-exchange-alt"></i>
              <p>Transaksi</p>
            </a>
          </li>
          @endif

          {{-- Kasir --}}
          @if(Auth::user()->role === 'kasir')
          <li class="nav-item">
            <a href="/menu" class="nav-link {{ request()->is('menu') ? 'active' : '' }}" style="color: white;">
              <i class="nav-icon fas fa-utensils"></i>
              <p>Menu</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="/kasir" class="nav-link {{ request()->is('kasir') ? 'active' : '' }}" style="color: white;">
              <i class="nav-icon fas fa-cash-register"></i>
              <p>Kasir</p>
            </a>
          </li>
          @endif

          {{-- Koki --}}
          @if(Auth::user()->role === 'koki')
          <li class="nav-item">
            <a href="/koki" class="nav-link {{ request()->is('koki') ? 'active' : '' }}" style="color: white;">
              <i class="nav-icon fas fa-user-tie"></i>
              <p>Koki</p>
            </a>
          </li>
          @endif

          {{-- Tambah Barang (admin only) --}}
          @if(Auth::user()->role === 'admin')
          <li class="nav-item mt-0">
            <a href="{{ route('barang.index') }}" class="nav-link {{ request()->is('barang*') ? 'active' : '' }}" style="color: white;">
              <i class="nav-icon fas fa-plus-square"></i>
              <p>Tambah Barang</p>
            </a>
          </li>
          @endif

        </ul>
      </nav>
    </div>
</aside>

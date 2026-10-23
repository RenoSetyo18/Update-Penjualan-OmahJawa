<aside class="main-sidebar sidebar-dark-primary elevation-4" style="background-color: #4D55CC; color: white;">
    <style>
        .main-sidebar {
            background: linear-gradient(180deg, #1f4034 0%, #152d25 100%) !important;
            box-shadow: 8px 0 28px rgba(17, 35, 27, 0.14) !important;
            scrollbar-color: rgba(255, 255, 255, 0.28) transparent;
            scrollbar-width: thin;
        }
        @media (max-width: 992px) {
            body.sidebar-open #sidebar-overlay {
                display: block;
            }
            .main-sidebar {
                position: fixed !important;
                top: 0;
                left: 0;
                width: min(250px, 85vw) !important;
                max-width: 85vw;
                height: 100vh !important;
                margin-left: 0;
                overflow-y: auto;
                transform: translateX(-100%);
                z-index: 1050;
                transition: transform 0.3s ease-in-out;
                box-shadow: none !important;
                -webkit-overflow-scrolling: touch;
            }
            body.sidebar-open .main-sidebar {
                transform: translateX(0);
                box-shadow: 8px 0 28px rgba(17, 35, 27, 0.2) !important;
            }
        }
        .main-sidebar .user-panel {
            margin: 1rem 0.75rem 1.25rem !important;
            padding: 0.75rem 0 1.1rem !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.14) !important;
        }
        .main-sidebar .user-panel img {
            border: 3px solid rgba(255, 255, 255, 0.24);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2) !important;
        }
        .main-sidebar .user-panel .info a {
            font-size: 0.95rem;
            font-weight: 700;
            letter-spacing: 0.12em;
        }
        .main-sidebar .nav-sidebar {
            padding: 0 0.65rem 1rem;
        }
        .main-sidebar .nav-sidebar .nav-link {
            margin: 0.16rem 0;
            border-radius: 9px;
            color: rgba(246, 249, 246, 0.8) !important;
            transition: background-color 0.18s ease, color 0.18s ease, transform 0.18s ease;
        }
        .main-sidebar .nav-sidebar .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1) !important;
            color: #fff !important;
            transform: translateX(2px);
        }
        .main-sidebar .nav-sidebar .nav-link.active,
        .main-sidebar .nav-sidebar .menu-open > .nav-link {
            background: linear-gradient(115deg, #d4b477 0%, #c7a56a 100%) !important;
            color: #1d3429 !important;
            box-shadow: 0 5px 14px rgba(0, 0, 0, 0.16);
            font-weight: 700;
        }
        .main-sidebar .nav-sidebar .nav-link.active .nav-icon,
        .main-sidebar .nav-sidebar .menu-open > .nav-link .nav-icon {
            color: #1d3429 !important;
        }
        .main-sidebar .nav-treeview {
            margin: 0.2rem 0 0.45rem;
            border-left: 1px solid rgba(255, 255, 255, 0.2);
        }
        .main-sidebar .nav-treeview .nav-link {
            font-size: 0.92rem;
        }
    </style>
    <div class="sidebar">
      <!-- User Panel -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex flex-column align-items-center">
        <div class="image">
          <img src="{{ asset('dataset/dist/img/LogoOmahJawa.png') }}" class="img-circle elevation-2" alt="User Image" style="width: 100px; height: 100px;">
        </div>
        <div class="info mt-2">
          <a href="#" class="d-block" style="color: white;">OMAH JAWA</a>
        </div>
      </div>

      <!-- Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

          {{-- Super Admin --}}
          @if(Auth::check() && Auth::user()->role === 'super_admin')
          <li class="nav-item">
            <a href="{{ route('dashboard.index') }}" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}" style="color: white;">
              <i class="nav-icon fas fa-home"></i>
              <p>Dashboard</p>
            </a>
          </li>
          <li class="nav-item has-treeview {{ request()->is('master*') || request()->is('jenis*') || request()->is('karyawan*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ request()->is('master*') || request()->is('jenis*') || request()->is('karyawan*') ? 'active' : '' }}" style="color: white; {{ request()->is('master*') || request()->is('jenis*') || request()->is('karyawan*') ? 'background-color: #007bff;' : '' }}">
              <i class="nav-icon fas fa-cogs"></i>
              <p>
                Master Data
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview" style="padding-left: 1rem;">
              <li class="nav-item">
                <a href="{{ route('master-menu.index') }}" class="nav-link {{ request()->is('master-menu*') ? 'active' : '' }}" style="color: white; {{ request()->is('master-menu*') ? 'background-color: #007bff;' : '' }}">
                  <i class="nav-icon fas fa-layer-group"></i>
                  <p>Master Menu</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('master-ppn.index') }}" class="nav-link {{ request()->is('master/ppn') ? 'active' : '' }}" style="color: white; {{ request()->is('master/ppn') ? 'background-color: #007bff;' : '' }}">
                  <i class="nav-icon fas fa-percent"></i>
                  <p>PPN</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('jenis.index') }}" class="nav-link {{ request()->is('jenis*') ? 'active' : '' }}" style="color: white; {{ request()->is('jenis*') ? 'background-color: #007bff;' : '' }}">
                  <i class="nav-icon fas fa-list-alt"></i>
                  <p>Jenis Menu</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('karyawan.index') }}" class="nav-link {{ request()->is('karyawan*') ? 'active' : '' }}" style="color: white; {{ request()->is('karyawan*') ? 'background-color: #007bff;' : '' }}">
                  <i class="nav-icon fas fa-users"></i>
                  <p>Karyawan</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="{{ route('menu.index') }}" class="nav-link {{ request()->is('menu') ? 'active' : '' }}" style="color: white;">
              <i class="nav-icon fas fa-utensils"></i>
              <p>Menu</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('kasir.index') }}" class="nav-link {{ request()->is('kasir') ? 'active' : '' }}" style="color: white;">
              <i class="nav-icon fas fa-cash-register"></i>
              <p>Kasir</p>
            </a>
          </li>
          <li class="nav-item has-treeview {{ request()->is('laporan*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ request()->is('laporan*') ? 'active' : '' }}" style="color: white; {{ request()->is('laporan*') ? 'background-color: #007bff;' : '' }}">
              <i class="nav-icon fas fa-chart-line"></i>
              <p>
                Laporan
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview" style="padding-left: 1rem;">
              <li class="nav-item">
                <a href="{{ route('laporan.penjualan') }}" class="nav-link {{ request()->is('laporan/penjualan') ? 'active' : '' }}" style="color: white; {{ request()->is('laporan/penjualan') ? 'background-color: #007bff;' : '' }}">
                  <span class="nav-icon d-inline-flex align-items-center justify-content-center" style="width: 1.25rem; height: 1.25rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 1.25rem; height: 1.25rem;">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 7.5V6.108c0-1.135.845-2.098 1.976-2.192.373-.03.748-.057 1.123-.08M15.75 18H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08M15.75 18.75v-1.875a3.375 3.375 0 0 0-3.375-3.375h-1.5a1.125 1.125 0 0 1-1.125-1.125v-1.5A3.375 3.375 0 0 0 6.375 7.5H5.25m11.9-3.664A2.251 2.251 0 0 0 15 2.25h-1.5a2.251 2.251 0 0 0-2.15 1.586m5.8 0c.065.21.1.433.1.664v.75h-6V4.5c0-.231.035-.454.1-.664M6.75 7.5H4.875c-.621 0-1.125.504-1.125 1.125v12c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V16.5a9 9 0 0 0-9-9Z" />
                    </svg>
                  </span>
                  <p>Laporan Penjualan</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('laporan.menu-terlaris') }}" class="nav-link {{ request()->is('laporan/menu-terlaris') ? 'active' : '' }}" style="color: white; {{ request()->is('laporan/menu-terlaris') ? 'background-color: #007bff;' : '' }}">
                  <i class="nav-icon fas fa-star"></i>
                  <p>Laporan Menu Terlaris</p>
                </a>
              </li>
            </ul>
          </li>
          @endif

          {{-- Admin --}}
          @if(Auth::check() && Auth::user()->role === 'admin')
          <li class="nav-item">
            <a href="{{ route('dashboard.index') }}" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}" style="color: white;">
              <i class="nav-icon fas fa-home"></i>
              <p>Dashboard</p>
            </a>
          </li>
          <li class="nav-item has-treeview {{ request()->is('master*') || request()->is('jenis*') || request()->is('karyawan*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ request()->is('master*') || request()->is('jenis*') || request()->is('karyawan*') ? 'active' : '' }}" style="color: white; {{ request()->is('master*') || request()->is('jenis*') || request()->is('karyawan*') ? 'background-color: #007bff;' : '' }}">
              <i class="nav-icon fas fa-cogs"></i>
              <p>
                Master Data
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview" style="padding-left: 1rem;">
              <li class="nav-item">
                <a href="{{ route('master-menu.index') }}" class="nav-link {{ request()->is('master-menu*') ? 'active' : '' }}" style="color: white; {{ request()->is('master-menu*') ? 'background-color: #007bff;' : '' }}">
                  <i class="nav-icon fas fa-layer-group"></i>
                  <p>Master Menu</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('master-ppn.index') }}" class="nav-link {{ request()->is('master/ppn') ? 'active' : '' }}" style="color: white; {{ request()->is('master/ppn') ? 'background-color: #007bff;' : '' }}">
                  <i class="nav-icon fas fa-percent"></i>
                  <p>PPN</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('jenis.index') }}" class="nav-link {{ request()->is('jenis*') ? 'active' : '' }}" style="color: white; {{ request()->is('jenis*') ? 'background-color: #007bff;' : '' }}">
                  <i class="nav-icon fas fa-list-alt"></i>
                  <p>Jenis Menu</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('karyawan.index') }}" class="nav-link {{ request()->is('karyawan*') ? 'active' : '' }}" style="color: white; {{ request()->is('karyawan*') ? 'background-color: #007bff;' : '' }}">
                  <i class="nav-icon fas fa-users"></i>
                  <p>Karyawan</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item has-treeview {{ request()->is('laporan*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ request()->is('laporan*') ? 'active' : '' }}" style="color: white; {{ request()->is('laporan*') ? 'background-color: #007bff;' : '' }}">
              <i class="nav-icon fas fa-chart-line"></i>
              <p>
                Laporan
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview" style="padding-left: 1rem;">
              <li class="nav-item">
                <a href="{{ route('laporan.penjualan') }}" class="nav-link {{ request()->is('laporan/penjualan') ? 'active' : '' }}" style="color: white; {{ request()->is('laporan/penjualan') ? 'background-color: #007bff;' : '' }}">
                  <span class="nav-icon d-inline-flex align-items-center justify-content-center" style="width: 1.25rem; height: 1.25rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 1.25rem; height: 1.25rem;">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 7.5V6.108c0-1.135.845-2.098 1.976-2.192.373-.03.748-.057 1.123-.08M15.75 18H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08M15.75 18.75v-1.875a3.375 3.375 0 0 0-3.375-3.375h-1.5a1.125 1.125 0 0 1-1.125-1.125v-1.5A3.375 3.375 0 0 0 6.375 7.5H5.25m11.9-3.664A2.251 2.251 0 0 0 15 2.25h-1.5a2.251 2.251 0 0 0-2.15 1.586m5.8 0c.065.21.1.433.1.664v.75h-6V4.5c0-.231.035-.454.1-.664M6.75 7.5H4.875c-.621 0-1.125.504-1.125 1.125v12c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V16.5a9 9 0 0 0-9-9Z" />
                    </svg>
                  </span>
                  <p>Laporan Penjualan</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('laporan.menu-terlaris') }}" class="nav-link {{ request()->is('laporan/menu-terlaris') ? 'active' : '' }}" style="color: white; {{ request()->is('laporan/menu-terlaris') ? 'background-color: #007bff;' : '' }}">
                  <i class="nav-icon fas fa-star"></i>
                  <p>Laporan Menu Terlaris</p>
                </a>
              </li>
            </ul>
          </li>
          @endif

          {{-- Pelayan --}}
          @if(Auth::check() && Auth::user()->role === 'pelayan')
          <li class="nav-item">
            <a href="{{ route('menu.index') }}" class="nav-link {{ request()->is('menu') ? 'active' : '' }}" style="color: white;">
              <i class="nav-icon fas fa-utensils"></i>
              <p>Menu</p>
            </a>
          </li>
          @endif

          {{-- Kasir --}}
          @if(Auth::check() && Auth::user()->role === 'kasir')
          <li class="nav-item">
            <a href="{{ route('dashboard.index') }}" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}" style="color: white;">
              <i class="nav-icon fas fa-home"></i>
              <p>Dashboard</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('menu.index') }}" class="nav-link {{ request()->is('menu') ? 'active' : '' }}" style="color: white;">
              <i class="nav-icon fas fa-utensils"></i>
              <p>Menu</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('kasir.index') }}" class="nav-link {{ request()->is('kasir') ? 'active' : '' }}" style="color: white;">
              <i class="nav-icon fas fa-cash-register"></i>
              <p>Kasir</p>
            </a>
          </li>
          @endif

        </ul>
      </nav>
    </div>
</aside>

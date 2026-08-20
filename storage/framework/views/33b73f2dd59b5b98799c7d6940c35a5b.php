<aside class="main-sidebar sidebar-dark-primary elevation-4" style="background-color: #4D55CC; color: white; position: fixed !important; top: 0; left: 0; height: 100vh !important; overflow-y: auto; z-index: 1030;">
    <style>
        @media (min-width: 768px) {
            .content-wrapper {
                margin-left: 250px !important;
            }
        }
        @media (max-width: 767.98px) {
            .main-sidebar {
                position: relative !important;
                height: auto !important;
            }
            .content-wrapper {
                margin-left: 0 !important;
            }
        }
    </style>
    <div class="sidebar">
      <!-- User Panel -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex flex-column align-items-center">
        <div class="image">
          <img src="<?php echo e(asset('dataset/dist/img/LogoOmahJawa.png')); ?>" class="img-circle elevation-2" alt="User Image" style="width: 100px; height: 100px;">
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

          
          <?php if(Auth::check() && Auth::user()->role === 'super_admin'): ?>
          <li class="nav-item">
            <a href="<?php echo e(route('dashboard.index')); ?>" class="nav-link <?php echo e(request()->is('dashboard') ? 'active' : ''); ?>" style="color: white;">
              <i class="nav-icon fas fa-home"></i>
              <p>Dashboard</p>
            </a>
          </li>
          <li class="nav-item has-treeview <?php echo e(request()->is('master*') || request()->is('jenis*') || request()->is('karyawan*') ? 'menu-open' : ''); ?>">
            <a href="#" class="nav-link <?php echo e(request()->is('master*') || request()->is('jenis*') || request()->is('karyawan*') ? 'active' : ''); ?>" style="color: white; <?php echo e(request()->is('master*') || request()->is('jenis*') || request()->is('karyawan*') ? 'background-color: #007bff;' : ''); ?>">
              <i class="nav-icon fas fa-cogs"></i>
              <p>
                Master Data
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview" style="padding-left: 1rem;">
              <li class="nav-item">
                <a href="<?php echo e(route('master-menu.index')); ?>" class="nav-link <?php echo e(request()->is('master-menu*') ? 'active' : ''); ?>" style="color: white; <?php echo e(request()->is('master-menu*') ? 'background-color: #007bff;' : ''); ?>">
                  <i class="nav-icon fas fa-layer-group"></i>
                  <p>Master Menu</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo e(route('jenis.index')); ?>" class="nav-link <?php echo e(request()->is('jenis*') ? 'active' : ''); ?>" style="color: white; <?php echo e(request()->is('jenis*') ? 'background-color: #007bff;' : ''); ?>">
                  <i class="nav-icon fas fa-list-alt"></i>
                  <p>Jenis Menu</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo e(route('karyawan.index')); ?>" class="nav-link <?php echo e(request()->is('karyawan*') ? 'active' : ''); ?>" style="color: white; <?php echo e(request()->is('karyawan*') ? 'background-color: #007bff;' : ''); ?>">
                  <i class="nav-icon fas fa-users"></i>
                  <p>Karyawan</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="<?php echo e(route('menu.index')); ?>" class="nav-link <?php echo e(request()->is('menu') ? 'active' : ''); ?>" style="color: white;">
              <i class="nav-icon fas fa-utensils"></i>
              <p>Menu</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo e(route('transaksi.index')); ?>" class="nav-link <?php echo e(request()->is('transaksi') ? 'active' : ''); ?>" style="color: white;">
              <i class="nav-icon fas fa-exchange-alt"></i>
              <p>Transaksi</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo e(route('kasir.index')); ?>" class="nav-link <?php echo e(request()->is('kasir') ? 'active' : ''); ?>" style="color: white;">
              <i class="nav-icon fas fa-cash-register"></i>
              <p>Kasir</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo e(route('koki.index')); ?>" class="nav-link <?php echo e(request()->is('koki') ? 'active' : ''); ?>" style="color: white;">
              <i class="nav-icon fas fa-user-tie"></i>
              <p>Koki</p>
            </a>
          </li>
          <li class="nav-item has-treeview <?php echo e(request()->is('laporan*') ? 'menu-open' : ''); ?>">
            <a href="#" class="nav-link <?php echo e(request()->is('laporan*') ? 'active' : ''); ?>" style="color: white; <?php echo e(request()->is('laporan*') ? 'background-color: #007bff;' : ''); ?>">
              <i class="nav-icon fas fa-chart-line"></i>
              <p>
                Laporan
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview" style="padding-left: 1rem;">
              <li class="nav-item">
                <a href="<?php echo e(route('laporan.penjualan')); ?>" class="nav-link <?php echo e(request()->is('laporan/penjualan') ? 'active' : ''); ?>" style="color: white; <?php echo e(request()->is('laporan/penjualan') ? 'background-color: #007bff;' : ''); ?>">
                  <span class="nav-icon d-inline-flex align-items-center justify-content-center" style="width: 1.25rem; height: 1.25rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 1.25rem; height: 1.25rem;">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 7.5V6.108c0-1.135.845-2.098 1.976-2.192.373-.03.748-.057 1.123-.08M15.75 18H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08M15.75 18.75v-1.875a3.375 3.375 0 0 0-3.375-3.375h-1.5a1.125 1.125 0 0 1-1.125-1.125v-1.5A3.375 3.375 0 0 0 6.375 7.5H5.25m11.9-3.664A2.251 2.251 0 0 0 15 2.25h-1.5a2.251 2.251 0 0 0-2.15 1.586m5.8 0c.065.21.1.433.1.664v.75h-6V4.5c0-.231.035-.454.1-.664M6.75 7.5H4.875c-.621 0-1.125.504-1.125 1.125v12c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V16.5a9 9 0 0 0-9-9Z" />
                    </svg>
                  </span>
                  <p>Laporan Penjualan</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo e(route('laporan.menu-terlaris')); ?>" class="nav-link <?php echo e(request()->is('laporan/menu-terlaris') ? 'active' : ''); ?>" style="color: white; <?php echo e(request()->is('laporan/menu-terlaris') ? 'background-color: #007bff;' : ''); ?>">
                  <i class="nav-icon fas fa-star"></i>
                  <p>Laporan Menu Terlaris</p>
                </a>
              </li>
            </ul>
          </li>
          <?php endif; ?>

          
          <?php if(Auth::check() && Auth::user()->role === 'admin'): ?>
          <li class="nav-item">
            <a href="<?php echo e(route('dashboard.index')); ?>" class="nav-link <?php echo e(request()->is('dashboard') ? 'active' : ''); ?>" style="color: white;">
              <i class="nav-icon fas fa-home"></i>
              <p>Dashboard</p>
            </a>
          </li>
          <li class="nav-item has-treeview <?php echo e(request()->is('master*') || request()->is('jenis*') || request()->is('karyawan*') ? 'menu-open' : ''); ?>">
            <a href="#" class="nav-link <?php echo e(request()->is('master*') || request()->is('jenis*') || request()->is('karyawan*') ? 'active' : ''); ?>" style="color: white; <?php echo e(request()->is('master*') || request()->is('jenis*') || request()->is('karyawan*') ? 'background-color: #007bff;' : ''); ?>">
              <i class="nav-icon fas fa-cogs"></i>
              <p>
                Master Data
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview" style="padding-left: 1rem;">
              <li class="nav-item">
                <a href="<?php echo e(route('master-menu.index')); ?>" class="nav-link <?php echo e(request()->is('master-menu*') ? 'active' : ''); ?>" style="color: white; <?php echo e(request()->is('master-menu*') ? 'background-color: #007bff;' : ''); ?>">
                  <i class="nav-icon fas fa-layer-group"></i>
                  <p>Master Menu</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo e(route('jenis.index')); ?>" class="nav-link <?php echo e(request()->is('jenis*') ? 'active' : ''); ?>" style="color: white; <?php echo e(request()->is('jenis*') ? 'background-color: #007bff;' : ''); ?>">
                  <i class="nav-icon fas fa-list-alt"></i>
                  <p>Jenis Menu</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo e(route('karyawan.index')); ?>" class="nav-link <?php echo e(request()->is('karyawan*') ? 'active' : ''); ?>" style="color: white; <?php echo e(request()->is('karyawan*') ? 'background-color: #007bff;' : ''); ?>">
                  <i class="nav-icon fas fa-users"></i>
                  <p>Karyawan</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item has-treeview <?php echo e(request()->is('laporan*') ? 'menu-open' : ''); ?>">
            <a href="#" class="nav-link <?php echo e(request()->is('laporan*') ? 'active' : ''); ?>" style="color: white; <?php echo e(request()->is('laporan*') ? 'background-color: #007bff;' : ''); ?>">
              <i class="nav-icon fas fa-chart-line"></i>
              <p>
                Laporan
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview" style="padding-left: 1rem;">
              <li class="nav-item">
                <a href="<?php echo e(route('laporan.penjualan')); ?>" class="nav-link <?php echo e(request()->is('laporan/penjualan') ? 'active' : ''); ?>" style="color: white; <?php echo e(request()->is('laporan/penjualan') ? 'background-color: #007bff;' : ''); ?>">
                  <span class="nav-icon d-inline-flex align-items-center justify-content-center" style="width: 1.25rem; height: 1.25rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 1.25rem; height: 1.25rem;">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 7.5V6.108c0-1.135.845-2.098 1.976-2.192.373-.03.748-.057 1.123-.08M15.75 18H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08M15.75 18.75v-1.875a3.375 3.375 0 0 0-3.375-3.375h-1.5a1.125 1.125 0 0 1-1.125-1.125v-1.5A3.375 3.375 0 0 0 6.375 7.5H5.25m11.9-3.664A2.251 2.251 0 0 0 15 2.25h-1.5a2.251 2.251 0 0 0-2.15 1.586m5.8 0c.065.21.1.433.1.664v.75h-6V4.5c0-.231.035-.454.1-.664M6.75 7.5H4.875c-.621 0-1.125.504-1.125 1.125v12c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V16.5a9 9 0 0 0-9-9Z" />
                    </svg>
                  </span>
                  <p>Laporan Penjualan</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo e(route('laporan.menu-terlaris')); ?>" class="nav-link <?php echo e(request()->is('laporan/menu-terlaris') ? 'active' : ''); ?>" style="color: white; <?php echo e(request()->is('laporan/menu-terlaris') ? 'background-color: #007bff;' : ''); ?>">
                  <i class="nav-icon fas fa-star"></i>
                  <p>Laporan Menu Terlaris</p>
                </a>
              </li>
            </ul>
          </li>
          <?php endif; ?>

          
          <?php if(Auth::check() && Auth::user()->role === 'pelayan'): ?>
          <li class="nav-item">
            <a href="<?php echo e(route('menu.index')); ?>" class="nav-link <?php echo e(request()->is('menu') ? 'active' : ''); ?>" style="color: white;">
              <i class="nav-icon fas fa-utensils"></i>
              <p>Menu</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo e(route('transaksi.index')); ?>" class="nav-link <?php echo e(request()->is('transaksi') ? 'active' : ''); ?>" style="color: white;">
              <i class="nav-icon fas fa-exchange-alt"></i>
              <p>Transaksi</p>
            </a>
          </li>
          <?php endif; ?>

          
          <?php if(Auth::check() && Auth::user()->role === 'kasir'): ?>
          <li class="nav-item">
            <a href="<?php echo e(route('dashboard.index')); ?>" class="nav-link <?php echo e(request()->is('dashboard') ? 'active' : ''); ?>" style="color: white;">
              <i class="nav-icon fas fa-home"></i>
              <p>Dashboard</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo e(route('menu.index')); ?>" class="nav-link <?php echo e(request()->is('menu') ? 'active' : ''); ?>" style="color: white;">
              <i class="nav-icon fas fa-utensils"></i>
              <p>Menu</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo e(route('kasir.index')); ?>" class="nav-link <?php echo e(request()->is('kasir') ? 'active' : ''); ?>" style="color: white;">
              <i class="nav-icon fas fa-cash-register"></i>
              <p>Kasir</p>
            </a>
          </li>
          <?php endif; ?>

          
          <?php if(Auth::check() && Auth::user()->role === 'koki'): ?>
          <li class="nav-item">
            <a href="<?php echo e(route('dashboard.index')); ?>" class="nav-link <?php echo e(request()->is('dashboard') ? 'active' : ''); ?>" style="color: white;">
              <i class="nav-icon fas fa-home"></i>
              <p>Dashboard</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo e(route('koki.index')); ?>" class="nav-link <?php echo e(request()->is('koki') ? 'active' : ''); ?>" style="color: white;">
              <i class="nav-icon fas fa-user-tie"></i>
              <p>Koki</p>
            </a>
          </li>
          <?php endif; ?>

        </ul>
      </nav>
    </div>
</aside>
<?php /**PATH C:\laragon\www\omahjawa1-Copy\resources\views/components/sidebar.blade.php ENDPATH**/ ?>
<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-light-primary elevation-4">
  <!-- Brand Logo -->
  <a href="<?= base_url('/') ?>" class="brand-link">
    <img src="<?= base_url('dist/img/logo.png') ?>" alt="App Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">Shift IMS</span>
  </a>
  <div class="sidebar">
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column nav-legacy nav-flat nav-collapse-hide-child" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item">
          <a href="<?= base_url() ?>" class="nav-link <?= nav_active($this, 'dashboard') ?>">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
          </a>
        </li>
        <li class="nav-item <?= menu_open($this, 'report') ?>">
          <a href="#" class="nav-link <?= menu_active($this, 'report') ?>">
            <i class="nav-icon fas fa-chart-pie"></i>
            <p>
              Laporan
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?= base_url('/reports/income-statement') ?>" class="nav-link <?= nav_active($this, 'income-statement-report') ?>">
                <i class="nav-icon fas fa-file-contract"></i>
                <p>Lap. Laba Rugi</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url('/reports/sales-by-category') ?>" class="nav-link <?= nav_active($this, 'sales-by-category') ?>">
                <i class="nav-icon fas fa-file-contract"></i>
                <p><small>Lap. Penjualan per Kategori</small></p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url('/reports/stock-assets') ?>" class="nav-link <?= nav_active($this, 'stock-report') ?>">
                <i class="nav-icon fas fa-file-contract"></i>
                <p>Lap. Stok</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item <?= menu_open($this, 'sales-order') ?>">
          <a href="#" class="nav-link <?= menu_active($this, 'sales-order') ?>">
            <i class="nav-icon fas fa-store"></i>
            <p>
              Penjualan
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?= base_url('/sales-orders/add') ?>" class="nav-link <?= nav_active($this, 'add-sales-order') ?>">
                <i class="fas fa-cart-plus nav-icon"></i>
                <p>Penjualan Baru</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url('/sales-orders/') ?>" class="nav-link <?= nav_active($this, 'sales-order') ?>">
                <i class="nav-icon fas fa-cart-shopping"></i>
                <p>Penjualan</p>
              </a>
            </li>
          </ul>
        </li>
        <?php if (env('REPAIR_SERVICE_MODULE')) : ?>
          <li class="nav-item <?= menu_open($this, 'service-order') ?>">
            <a href="#" class="nav-link <?= menu_active($this, 'service-order') ?>">
              <i class="nav-icon fas fa-house-laptop"></i>
              <p>
                Servis
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?= base_url('/service-orders/edit/0') ?>" class="nav-link <?= nav_active($this, 'edit-service-order') ?>">
                  <i class="fas fa-hand-holding-medical nav-icon"></i>
                  <p>Penerimaan servis</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= base_url('/service-orders/') ?>" class="nav-link <?= nav_active($this, 'service-order') ?>">
                  <i class="nav-icon fas fa-screwdriver-wrench"></i>
                  <p>Servis</p>
                </a>
              </li>
            </ul>
          </li>
        <?php endif ?>
        <li class="nav-item <?= menu_open($this, 'customer') ?>">
          <a href="#" class="nav-link <?= menu_active($this, 'customer') ?>">
            <i class="nav-icon fas fa-users"></i>
            <p>
              Pelanggan
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?= base_url('/customers/edit/0') ?>" class="nav-link <?= nav_active($this, 'edit-customer') ?>">
                <i class="fas fa-user-plus nav-icon"></i>
                <p>Pelanggan Baru</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url('/customers/') ?>" class="nav-link <?= nav_active($this, 'customer') ?>">
                <i class="nav-icon fas fa-users"></i>
                <p>Pelanggan</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item <?= menu_open($this, 'inventory') ?>">
          <a href="#" class="nav-link <?= menu_active($this, 'inventory') ?>">
            <i class="nav-icon fas fa-warehouse"></i>
            <p>
              Inventori
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?= base_url('/stock-adjustments/') ?>" class="nav-link <?= nav_active($this, 'stock-adjustment') ?>">
                <i class="nav-icon fas fa-sliders"></i>
                <p>Penyesuaian Stok</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url('/stock-updates/') ?>" class="nav-link <?= nav_active($this, 'stock-update') ?>">
                <i class="nav-icon fas fa-clock-rotate-left"></i>
                <p>Riwayat Stok</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url('/products/') ?>" class="nav-link <?= nav_active($this, 'product') ?>">
                <i class="nav-icon fas fa-cubes"></i>
                <p>Produk</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url('/product-categories/') ?>" class="nav-link <?= nav_active($this, 'product-category') ?>">
                <i class="fas fa-boxes nav-icon"></i>
                <p>Kategori Produk</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item <?= menu_open($this, 'purchase-order') ?>">
          <a href="#" class="nav-link <?= menu_active($this, 'purchase-order') ?>">
            <i class="nav-icon fas fa-truck-fast"></i>
            <p>
              Pembelian
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?= base_url('/purchase-orders/') ?>" class="nav-link <?= nav_active($this, 'purchase-order') ?>">
                <i class="nav-icon fas fa-truck-ramp-box"></i>
                <p>Pembelian</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url('/suppliers/') ?>" class="nav-link <?= nav_active($this, 'supplier') ?>">
                <i class="nav-icon fas fa-truck"></i>
                <p>Pemasok</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item <?= menu_open($this, 'system') ?>">
          <a href="#" class="nav-link <?= menu_active($this, 'system') ?>">
            <i class="nav-icon fas fa-gears"></i>
            <p>
              Sistem
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?= base_url('/users/') ?>" class="nav-link <?= nav_active($this, 'users') ?>">
                <i class="nav-icon fas fa-users"></i>
                <p>Pengguna</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url('/users/profile/') ?>" class="nav-link <?= nav_active($this, 'profile') ?>">
                <i class="nav-icon fas fa-user"></i>
                <p>Profil Saya</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url('/system/settings') ?>" class="nav-link <?= nav_active($this, 'system-settings') ?>">
                <i class="nav-icon fas fa-gear"></i>
                <p>Pengaturan</p>
              </a>
            </li>
          </ul>
        </li>
      </ul>
    </nav>
  </div>
</aside>
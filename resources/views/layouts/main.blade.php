<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Omah Jawa')</title>

  <!-- CSRF Token untuk AJAX -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('dataset/plugins/fontawesome-free/css/all.min.css') }}">

  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('dataset/dist/css/adminlte.min.css') }}">

  <!-- Custom CSS -->
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">

  <!-- DataTables -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
  <style>
    :root {
      --oj-ink: #263a30;
      --oj-muted: #748078;
      --oj-green: #315d46;
      --oj-green-dark: #244735;
      --oj-gold: #c7a56a;
      --oj-border: #e5ebe6;
      --oj-surface: #fff;
      --oj-page: #f4f7f4;
      --oj-shadow: 0 10px 28px rgba(28, 48, 39, 0.07);
    }

    body {
      background: var(--oj-page);
      color: var(--oj-ink);
      font-family: "Source Sans Pro", "Segoe UI", sans-serif;
    }

    .content-wrapper {
      min-height: 100vh;
      background: var(--oj-page);
      transition: transform 0.3s ease-in-out, margin-left 0.3s ease-in-out;
    }

    .content-header {
      padding: 1.35rem 1.5rem 0.65rem;
    }

    .content-header h1,
    .content-header .h1,
    .content-header .h3 {
      color: var(--oj-ink);
      font-weight: 700;
      letter-spacing: -0.025em;
    }

    .content-header .btn-create-action {
      display: inline-flex;
      min-height: 42px;
      align-items: center;
      justify-content: center;
      gap: 0.55rem;
      padding: 0.55rem 1rem;
      border: 1px solid var(--oj-green);
      border-radius: 10px;
      background: linear-gradient(135deg, #3b6a50 0%, #274a37 100%);
      color: #fff;
      box-shadow: 0 5px 13px rgba(49, 93, 70, 0.18);
      font-weight: 700;
      letter-spacing: 0.01em;
      transition: transform 0.18s ease, box-shadow 0.18s ease, background 0.18s ease;
    }

    .content-header .btn-create-action:hover,
    .content-header .btn-create-action:focus {
      transform: translateY(-1px);
      border-color: var(--oj-green-dark);
      background: linear-gradient(135deg, #315d46 0%, #1f3e2d 100%);
      color: #fff;
      box-shadow: 0 8px 17px rgba(49, 93, 70, 0.25);
    }

    .content-header .btn-create-action .btn-create-icon {
      display: inline-flex;
      width: 23px;
      height: 23px;
      align-items: center;
      justify-content: center;
      border-radius: 7px;
      background: rgba(255, 255, 255, 0.18);
      font-size: 0.72rem;
    }

    .content {
      padding: 0 1.5rem 1.75rem;
    }

    .content .card {
      border: 1px solid var(--oj-border);
      border-radius: 14px;
      box-shadow: var(--oj-shadow);
    }

    .content .card-header {
      padding: 1rem 1.25rem;
      border-bottom: 1px solid #edf1ed;
      background: var(--oj-surface);
    }

    .content .card-header .card-title {
      margin: 0;
      color: var(--oj-ink);
      font-size: 1.05rem;
      font-weight: 700;
    }

    .content .card-body {
      padding: 1.25rem;
    }

    .content .card-footer {
      padding: 1rem 1.25rem;
      border-top: 1px solid #edf1ed;
      background: #fafcfb;
    }

    .content > .container-fluid > .p-3.bg-white.rounded.shadow-sm {
      border: 1px solid var(--oj-border);
      border-radius: 14px !important;
      box-shadow: var(--oj-shadow) !important;
    }

    .content .btn {
      border-radius: 8px;
      font-weight: 600;
      transition: background-color 0.18s ease, border-color 0.18s ease, color 0.18s ease, box-shadow 0.18s ease, transform 0.18s ease;
    }

    .content .btn:hover:not(:disabled) {
      transform: translateY(-1px);
      box-shadow: 0 4px 11px rgba(31, 55, 42, 0.12);
    }

    .content .btn-primary,
    .content .btn-primary:focus {
      border-color: var(--oj-green);
      background-color: var(--oj-green);
    }

    .content .btn-primary:hover {
      border-color: var(--oj-green-dark);
      background-color: var(--oj-green-dark);
    }

    .content .btn-outline-primary {
      border-color: var(--oj-green);
      color: var(--oj-green);
    }

    .content .btn-outline-primary:hover {
      border-color: var(--oj-green);
      background-color: var(--oj-green);
      color: #fff;
    }

    .content .btn-add-menu-item {
      display: inline-flex;
      width: 36px;
      height: 36px;
      align-items: center;
      justify-content: center;
      padding: 0;
      border: 1px solid #315d46;
      border-radius: 11px;
      background: linear-gradient(135deg, #4d8060 0%, #315d46 100%);
      color: #fff;
      box-shadow: 0 4px 10px rgba(49, 93, 70, 0.16);
      font-size: 0.95rem;
      transition: transform 0.18s ease, box-shadow 0.18s ease, background 0.18s ease;
    }

    .content .btn-add-menu-item:hover,
    .content .btn-add-menu-item:focus {
      transform: translateY(-2px);
      border-color: #244735;
      background: linear-gradient(135deg, #3b6a50 0%, #244735 100%);
      color: #fff;
      box-shadow: 0 7px 14px rgba(49, 93, 70, 0.24);
    }

    .content .btn-add-menu-item:active {
      transform: translateY(0);
    }

    .content .btn-remove-menu-item {
      display: inline-flex;
      width: 34px;
      height: 34px;
      align-items: center;
      justify-content: center;
      padding: 0;
      border: 1px solid #ecd6d2;
      border-radius: 10px;
      background: #fff7f5;
      color: #ad5143;
      font-size: 0.8rem;
      transition: transform 0.18s ease, border-color 0.18s ease, background 0.18s ease, color 0.18s ease, box-shadow 0.18s ease;
    }

    .content .btn-remove-menu-item:hover,
    .content .btn-remove-menu-item:focus {
      transform: translateY(-1px);
      border-color: #cf7567;
      background: #b95748;
      color: #fff;
      box-shadow: 0 5px 12px rgba(185, 87, 72, 0.2);
    }

    .content .btn-remove-menu-item:active {
      transform: translateY(0);
    }

    .content .btn-unavailable-action {
      display: inline-flex;
      width: 34px;
      height: 34px;
      align-items: center;
      justify-content: center;
      padding: 0;
      border: 1px solid #e4e9e5;
      border-radius: 10px;
      background: #f4f6f4;
      color: #929c95;
      opacity: 1;
    }

    .content-header .btn-back-action {
      display: inline-flex;
      min-height: 40px;
      align-items: center;
      justify-content: center;
      gap: 0.55rem;
      padding: 0.5rem 0.9rem;
      border: 1px solid #dce5de;
      border-radius: 9px;
      background: #fff;
      color: #42614e;
      box-shadow: 0 3px 10px rgba(28, 48, 39, 0.05);
      font-weight: 600;
      transition: transform 0.18s ease, border-color 0.18s ease, background 0.18s ease, color 0.18s ease, box-shadow 0.18s ease;
    }

    .content-header .btn-back-action:hover,
    .content-header .btn-back-action:focus {
      transform: translateX(-2px);
      border-color: #b7cabb;
      background: #f4f8f4;
      color: #294b37;
      box-shadow: 0 5px 13px rgba(28, 48, 39, 0.1);
    }

    .content-header .btn-back-action i {
      color: #65826e;
      transition: transform 0.18s ease;
    }

    .content-header .btn-back-action:hover i {
      transform: translateX(-2px);
    }

    .content .form-control,
    .modal .form-control {
      min-height: 38px;
      border-color: #dfe6e1;
      border-radius: 8px;
      color: var(--oj-ink);
      box-shadow: none;
    }

    .content .form-control:focus,
    .modal .form-control:focus {
      border-color: #7da58d;
      box-shadow: 0 0 0 0.2rem rgba(57, 111, 78, 0.12);
    }

    .content label,
    .content .text-muted {
      color: var(--oj-muted);
    }

    .content label {
      font-weight: 600;
    }

    .content .table {
      margin-bottom: 0;
      border-color: var(--oj-border);
      color: #46534a;
    }

    .content .table thead th {
      padding: 0.85rem 0.75rem;
      border-bottom: 1px solid #e2e9e4;
      background: #f5f8f5;
      color: #43574a;
      font-size: 0.78rem;
      font-weight: 700;
      letter-spacing: 0.035em;
      text-transform: uppercase;
      vertical-align: middle;
    }

    .content .table td {
      padding: 0.75rem;
      border-color: #edf0ed;
      vertical-align: middle;
    }

    .content .table tbody tr {
      transition: background-color 0.15s ease;
    }

    .content .table tbody tr:hover {
      background-color: #f8faf8;
    }

    .content .table .badge {
      padding: 0.42rem 0.65rem;
      border-radius: 999px;
      font-size: 0.75rem;
      font-weight: 600;
    }

    .content .tab-filter .nav-tabs {
      gap: 0.5rem;
      border: 0;
    }

    .content .tab-filter .nav-link {
      margin: 0;
      padding: 0.65rem 1rem;
      border: 1px solid transparent;
      border-radius: 9px;
      color: #66736c;
      font-weight: 600;
    }

    .content .tab-filter .nav-link[data-status="belum"].active {
      border-color: #f2ddd7;
      background: #fff5f2;
      color: #a6402c;
    }

    .content .tab-filter .nav-link[data-status="selesai"].active,
    .content .tab-filter .nav-link[data-status="lunas"].active {
      border-color: #d5e9dd;
      background: #eff8f2;
      color: #216a4b;
    }

    .content .tab-filter .nav-link[data-status="semua"].active {
      border-color: #dce6df;
      background: #f2f6f3;
      color: #354d40;
    }

    .content .tab-filter .nav-link:hover {
      background-color: #f6f8f6;
    }

    .content .pagination .page-link {
      margin: 0 2px;
      border: 0;
      border-radius: 7px;
      color: var(--oj-green);
    }

    .content .pagination .page-item.active .page-link {
      background: var(--oj-green);
      color: #fff;
    }

    .content .alert {
      border: 0;
      border-radius: 10px;
      box-shadow: 0 4px 14px rgba(28, 48, 39, 0.05);
    }

    .content .alert-success {
      border-left: 4px solid #4b8c68;
      background: #eff8f2;
      color: #315d46;
    }

    .content .alert-danger,
    .content .alert-error {
      border-left: 4px solid #c76b58;
      background: #fff3f0;
      color: #934638;
    }

    .content .alert-warning {
      border-left: 4px solid #c7a56a;
      background: #fbf6eb;
      color: #806633;
    }

    .content .alert-info {
      border-left: 4px solid #6f947b;
      background: #f0f6f1;
      color: #42614e;
    }

    .content .alert .close {
      color: inherit;
      opacity: 0.65;
      text-shadow: none;
    }

    .content .alert .close:hover,
    .content .alert .close:focus {
      color: inherit;
      opacity: 1;
    }

    .swal2-popup {
      padding: 1.6rem 1.5rem 1.35rem;
      border: 1px solid var(--oj-border);
      border-radius: 18px;
      background: #fff;
      box-shadow: 0 22px 60px rgba(24, 39, 30, 0.2);
      font-family: "Source Sans Pro", "Segoe UI", sans-serif;
    }

    .swal2-popup .swal2-title {
      padding-top: 0.5rem;
      color: var(--oj-ink);
      font-size: 1.45rem;
      font-weight: 700;
      letter-spacing: -0.02em;
    }

    .swal2-popup .swal2-html-container {
      color: #68756c;
      font-size: 1rem;
      line-height: 1.55;
    }

    .swal2-popup .swal2-icon {
      margin-top: 0.35rem;
    }

    .swal2-popup .swal2-icon.swal2-success {
      border-color: #4b8c68;
      color: #4b8c68;
    }

    .swal2-popup .swal2-success .swal2-success-ring {
      border-color: rgba(75, 140, 104, 0.22);
    }

    .swal2-popup .swal2-success [class^="swal2-success-line"] {
      background-color: #4b8c68;
    }

    .swal2-popup .swal2-icon.swal2-error {
      border-color: #c76b58;
      color: #c76b58;
    }

    .swal2-popup .swal2-error [class^="swal2-x-mark-line"] {
      background-color: #c76b58;
    }

    .swal2-popup .swal2-icon.swal2-warning {
      border-color: #c7a56a;
      color: #b38c4a;
    }

    .swal2-popup .swal2-actions {
      gap: 0.65rem;
      margin-top: 1.35rem;
    }

    .swal2-popup .swal2-styled {
      min-height: 40px;
      margin: 0;
      padding: 0.6rem 1.15rem;
      border: 0;
      border-radius: 9px;
      box-shadow: none;
      font-size: 0.95rem;
      font-weight: 700;
      transition: transform 0.16s ease, box-shadow 0.16s ease;
    }

    .swal2-popup .swal2-styled:hover {
      transform: translateY(-1px);
      box-shadow: 0 5px 12px rgba(28, 48, 39, 0.14);
    }

    .swal2-popup .swal2-styled.swal2-confirm {
      background-color: var(--oj-green);
    }

    .swal2-popup .swal2-styled.swal2-cancel {
      background-color: #edf1ed;
      color: #526258;
    }

    .swal2-popup .swal2-styled.swal2-deny {
      background-color: #b95748;
    }

    .swal2-popup .swal2-validation-message {
      border-radius: 8px;
      background: #fff3f0;
      color: #934638;
    }

    .swal2-toast {
      border-radius: 12px;
      box-shadow: 0 10px 30px rgba(24, 39, 30, 0.16);
    }

    .content .dashboard-welcome {
      background: linear-gradient(125deg, #315d46 0%, #244735 100%) !important;
      border-radius: 16px;
    }

    .main-header.navbar {
      min-height: 58px;
      margin-left: 250px;
      border-bottom: 1px solid var(--oj-border);
      background: rgba(255, 255, 255, 0.96);
      box-shadow: 0 3px 14px rgba(28, 48, 39, 0.04);
      transition: transform 0.3s ease-in-out, margin-left 0.3s ease-in-out;
    }

    .content-wrapper {
      margin-left: 250px;
    }

    body.sidebar-collapse .main-header,
    body.sidebar-collapse .content-wrapper {
      margin-left: 80px;
    }

    .main-header .nav-link {
      color: #526258;
      font-weight: 600;
    }

    .modal-content {
      overflow: hidden;
      border: 1px solid var(--oj-border);
      border-radius: 14px;
      box-shadow: 0 18px 50px rgba(24, 39, 30, 0.2);
    }

    .modal-header {
      border-bottom-color: #edf0ed;
    }

    .modal-footer {
      border-top-color: #edf0ed;
    }

    .dataTables_wrapper {
      color: var(--oj-muted);
    }

    .dataTables_wrapper .dataTables_filter input,
    .dataTables_wrapper .dataTables_length select {
      margin-left: 0.4rem;
      border: 1px solid #dfe6e1;
      border-radius: 8px;
      padding: 0.35rem 0.55rem;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
      border-color: var(--oj-green) !important;
      background: var(--oj-green) !important;
      color: #fff !important;
    }

    @media (max-width: 992px) {
      .main-header.navbar,
      .content-wrapper,
      body.sidebar-collapse .main-header,
      body.sidebar-collapse .content-wrapper {
        margin-left: 0 !important;
      }

      .content-header {
        padding-right: 1rem;
        padding-left: 1rem;
      }

      .content {
        padding-right: 1rem;
        padding-left: 1rem;
      }

      .content .container-fluid {
        padding-right: 0;
        padding-left: 0;
      }

      .table-responsive,
      .table-responsive-custom {
        display: block;
        width: 100%;
        max-width: 100%;
        overflow-x: auto !important;
        -webkit-overflow-scrolling: touch;
      }

      .table-responsive > .table,
      .table-responsive-custom > .table {
        min-width: 620px;
      }

      .card-header,
      .card-footer {
        flex-wrap: wrap;
        gap: 0.5rem;
      }
    }

    @media (max-width: 767.98px) {
      .content-header {
        padding: 0.85rem 0.75rem 0.45rem;
      }

      .content {
        padding: 0 0.75rem 1rem;
      }

      .content .card-body {
        padding: 0.85rem;
      }

      .content-header .text-right {
        margin-top: 0.75rem;
        text-align: left !important;
      }

      .content-header .btn-create-action {
        min-height: 40px;
      }

      .content-header .btn-back-action {
        min-height: 40px;
      }

      .content .table-responsive .table {
        min-width: 560px;
      }

      .content-header .row {
        row-gap: 0.6rem;
      }

      .content-header h1 {
        font-size: 1.45rem;
      }

      .main-header.navbar {
        min-height: 52px;
        padding: 0.25rem 0.5rem;
      }

      .main-header .nav-link {
        padding-right: 0.55rem;
        padding-left: 0.55rem;
      }

      .main-header .navbar-nav {
        flex-direction: row;
        align-items: center;
      }

      .main-header .navbar-nav.ml-auto {
        margin-left: auto;
      }

      .modal-dialog {
        margin: 0.5rem;
      }

      .modal-dialog-centered {
        min-height: calc(100% - 1rem);
      }
    }
  </style>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <!-- Navbar -->
  @include('components.navbar')

  <!-- Main Sidebar Container -->
  @include('components.sidebar')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        @yield('header')
      </div>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        @yield('content')
      </div>
    </section>

  </div>

  <!-- Optional Footer -->
  {{-- @include('partials.footer') --}}

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
</div>

<!-- jQuery -->
<script src="{{ asset('/dataset/plugins/jquery/jquery.min.js') }}"></script>

<!-- Bootstrap 4 -->
<script src="{{ asset('/dataset/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<!-- AdminLTE App -->
<script src="{{ asset('/dataset/dist/js/adminlte.min.js') }}"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Notifikasi SweetAlert dari session -->
<script>
  @if(session('success'))
    Swal.fire({
      icon: 'success',
      title: 'Success',
      text: '{{ session('success') }}',
    });
  @endif

  @if(session('error'))
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: '{{ session('error') }}',
    });
  @endif

  @if(session('info'))
    Swal.fire({
      icon: 'info',
      title: 'Info',
      text: '{{ session('info') }}',
    });
  @endif
</script>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('form.delete-confirm').forEach(function (form) {
      form.addEventListener('submit', function (event) {
        event.preventDefault();

        var itemName = form.dataset.itemName || 'data ini';

        Swal.fire({
          title: 'Yakin ingin menghapus ' + itemName + '?',
          text: 'Data yang dihapus tidak bisa dikembalikan.',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Ya, hapus!',
          cancelButtonText: 'Batal',
          reverseButtons: true,
        }).then(function (result) {
          if (result.isConfirmed) {
            form.submit();
          }
        });
      });
    });
  });
</script>

<!-- Script tambahan halaman -->
@yield('scripts')

</body>
</html>

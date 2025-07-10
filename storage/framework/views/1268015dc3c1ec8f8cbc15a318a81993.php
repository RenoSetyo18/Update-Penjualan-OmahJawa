<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo $__env->yieldContent('title', 'Omah Jawa'); ?></title>

  <!-- CSRF Token untuk AJAX -->
  <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo e(asset('dataset/plugins/fontawesome-free/css/all.min.css')); ?>">

  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo e(asset('dataset/dist/css/adminlte.min.css')); ?>">

  <!-- DataTables -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <!-- Navbar -->
  <?php echo $__env->make('components.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

  <!-- Main Sidebar Container -->
  <?php echo $__env->make('components.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <?php echo $__env->yieldContent('header'); ?>
      </div>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <?php echo $__env->yieldContent('content'); ?>
      </div>
    </section>

    <a id="back-to-top" href="#" class="btn btn-primary back-to-top" role="button" aria-label="Scroll to top">
      <i class="fas fa-chevron-up"></i>
    </a>
  </div>

  <!-- Optional Footer -->
  

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
</div>

<!-- jQuery -->
<script src="<?php echo e(asset('/dataset/plugins/jquery/jquery.min.js')); ?>"></script>

<!-- Bootstrap 4 -->
<script src="<?php echo e(asset('/dataset/plugins/bootstrap/js/bootstrap.bundle.min.js')); ?>"></script>

<!-- AdminLTE App -->
<script src="<?php echo e(asset('/dataset/dist/js/adminlte.min.js')); ?>"></script>

<!-- AdminLTE for demo purposes -->
<script src="<?php echo e(asset('/dataset/dist/js/demo.js')); ?>"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Notifikasi SweetAlert dari session -->
<script>
  <?php if(session('success')): ?>
    Swal.fire({
      icon: 'success',
      title: 'Success',
      text: '<?php echo e(session('success')); ?>',
    });
  <?php endif; ?>

  <?php if(session('error')): ?>
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: '<?php echo e(session('error')); ?>',
    });
  <?php endif; ?>

  <?php if(session('info')): ?>
    Swal.fire({
      icon: 'info',
      title: 'Info',
      text: '<?php echo e(session('info')); ?>',
    });
  <?php endif; ?>
</script>

<!-- Script tambahan halaman -->
<?php echo $__env->yieldContent('scripts'); ?>

</body>
</html>
<?php /**PATH C:\laragon\www\omahjawa1\resources\views/layouts/main.blade.php ENDPATH**/ ?>
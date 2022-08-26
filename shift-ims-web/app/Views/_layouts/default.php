<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= $this->title  ?> - Shift IMS</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="<?= base_url('plugins/fontawesome-free/css/all.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('plugins/toastr/toastr.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('plugins/select2/css/select2.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('plugins/daterangepicker/daterangepicker.css') ?>">
  <link rel="stylesheet" href="<?= base_url('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('dist/css/adminlte.min.css') ?>">
  <style>
    .table td,
    .table th {
      padding-top: 0.25rem;
      padding-bottom: 0.25rem;
      vertical-align: middle;
    }

    .form-error {
      color: red;
    }

    .card .card-header.line {
      padding: 0.1rem;
    }

    .print a{color:#212529; text-decoration: none;}
    .plus-btn {
      border-radius: 100%;
      width:2rem;
      height:2rem;
    }
    .plus-btn .fa {line-height: 1.7;}
  </style>
</head>

<?php if (!empty($print)): ?>
  <?= $this->renderSection('content') ?>
<?php else: ?>
<body class="hold-transition sidebar-mini layout-navbar-fixed layout-fixed">
  <div class="wrapper">
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
      </ul>
    </nav>
    <?= $this->include('_layouts/sidebar.php') ?>
    <div class="content-wrapper">
      <section class="content-header">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <h1 class="m-0">
                <?php if (!empty($this->title)): ?>
                  <?php if(!empty($this->titleIcon)): ?>
                      <small class="text-muted"><i class="fa <?= $this->titleIcon ?> mr-2"></i></small>
                  <?php endif ?>
                  <span class="mr-2"><?= $this->title ?></span>
                  <?php if(!empty($this->addButtonLink)): ?>
                    <a title="<?= $this->addButtonLink['text'] ?>" href="<?= base_url($this->addButtonLink['url']) ?>" class="btn btn-sm plus-btn btn-primary"><i class="fa <?= $this->addButtonLink['icon'] ?>"></i></a>
                  <?php endif ?>
                <?php endif ?>
              </h1>
            </div>
          </div>
        </div>
      </section>
      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
              <?= $this->renderSection('content') ?>
            </div>
          </div>
        </div>
      </section>
    </div>
    <footer class="main-footer">
      <div class="float-right d-none d-sm-inline">Shift IMS v1.0.0</div>&copy; Shift IT Solution 2022
    </footer>
  </div>
  <script src="<?= base_url('plugins/jquery/jquery.min.js') ?>"></script>
  <script src="<?= base_url('plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
  <script src="<?= base_url('plugins/toastr/toastr.min.js') ?>"></script>
  <script src="<?= base_url('plugins/select2/js/select2.full.min.js') ?>"></script>
  <script src="<?= base_url('plugins/moment/moment.min.js') ?>"></script>
  <script src="<?= base_url('plugins/daterangepicker/daterangepicker.js') ?>"></script>
  <script src="<?= base_url('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') ?>"></script>
  <script src="<?= base_url('plugins/jszip/jszip.min.js') ?>"></script>
  <script src="<?= base_url('plugins/pdfmake/pdfmake.min.js') ?>"></script>
  <script src="<?= base_url('plugins/pdfmake/vfs_fonts.js') ?>"></script>
  <script src="<?= base_url('plugins/datatables/jquery.dataTables.min.js') ?>"></script>
  <script src="<?= base_url('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
  <script src="<?= base_url('plugins/datatables-responsive/js/dataTables.responsive.min.js') ?>"></script>
  <script src="<?= base_url('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') ?>"></script>
  <script src="<?= base_url('plugins/datatables-buttons/js/dataTables.buttons.min.js') ?>"></script>
  <script src="<?= base_url('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') ?>"></script>
  <script src="<?= base_url('plugins/datatables-buttons/js/buttons.html5.min.js') ?>"></script>
  <script src="<?= base_url('plugins/datatables-buttons/js/buttons.print.min.js') ?>"></script>
  <script src="<?= base_url('plugins/datatables-buttons/js/buttons.colVis.min.js') ?>"></script>
  <script src="<?= base_url('dist/js/adminlte.min.js') ?>"></script>
  <script>
    toastr.options = {
      "closeButton": true,
      "debug": false,
      "newestOnTop": false,
      "progressBar": false,
      "positionClass": "toast-top-right",
      "preventDuplicates": true,
      "onclick": null,
      "showDuration": "300",
      "hideDuration": "1000",
      "timeOut": "5000",
      "extendedTimeOut": "1000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut"
    }
    <?php if ($info = session()->getFlashdata('info')): ?>
      toastr["info"]('<?= $info ?>');
    <?php endif?>
    <?php if ($warning = session()->getFlashdata('warning')): ?>
      toastr["warning"]('<?= $warning ?>');
    <?php endif?>
    <?php if ($error = session()->getFlashdata('error')): ?>
      toastr["error"]('<?= $error ?>');
    <?php endif?>
  </script>
  <?= $this->renderSection('footscript') ?>

<?php endif ?>
</body>
</html>
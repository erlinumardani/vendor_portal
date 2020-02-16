<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{title}</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="referrer" content="origin">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="{base_url}assets/adminlte/plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="{base_url}assets/adminlte/dist/css/ionicons.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="{base_url}assets/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="{base_url}assets/adminlte/plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="{base_url}assets/adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="{base_url}assets/adminlte/dist/css/adminlte.min.css">
  <!-- daterange picker -->
  <link rel="stylesheet" href="{base_url}assets/adminlte/plugins/daterangepicker/daterangepicker.css">
  <!-- Google Font: Source Sans Pro -->
  <!-- <link href="{base_url}assets/adminlte/dist/css/fonts.googleapis.com.css" rel="stylesheet"> -->
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="{base_url}assets/adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="{base_url}assets/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Favicon-->
  <link rel="icon" href="{base_url}{icon}" type="image/x-icon">
  <style  type="text/css">

    .preloader {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: 9999;
      background-color: #fff;
    }
    .preloader .loading {
      position: absolute;
      left: 50%;
      top: 50%;
      transform: translate(-50%,-50%);
      font: 14px arial;
    }
    .header{
      background-color: rgb(163, 19, 9);
      background-image: url({base_url}assets/adminlte/dist/img/header-bg.png);
    }
    .content-bg{
      background: url('{base_url}assets/adminlte/dist/img/content-bg.png') repeat; --darkreader-inline-bgcolor: initial;
    }

  </style>
  <!-- jQuery -->
  <script src="{base_url}assets/adminlte/plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="{base_url}assets/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- DataTables -->
  <script src="{base_url}assets/adminlte/plugins/datatables/jquery.dataTables.js"></script>
  <script src="{base_url}assets/adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
  <!-- AdminLTE App -->
  <script src="{base_url}assets/adminlte/dist/js/adminlte.min.js"></script>
  <!-- AdminLTE for demo purposes -->
  <!-- <script src="{base_url}assets/adminlte/dist/js/demo.js"></script> -->
  <!-- InputMask -->
  <script src="{base_url}assets/adminlte/plugins/moment/moment.min.js"></script>
  <script src="{base_url}assets/adminlte/plugins/inputmask/min/jquery.inputmask.bundle.min.js"></script>
  <!-- date-range-picker -->
  <script src="{base_url}assets/adminlte/plugins/daterangepicker/daterangepicker.js"></script>
  <!-- jquery-validation -->
  <script src="{base_url}assets/adminlte/plugins/jquery-validation/jquery.validate.min.js"></script>
  <script src="{base_url}assets/adminlte/plugins/jquery-validation/additional-methods.min.js"></script>
  <!-- Select2 -->
  <script src="{base_url}assets/adminlte/plugins/select2/js/select2.full.min.js"></script>
  <!-- SweetAlert2 -->
  <script src="{base_url}assets/adminlte/plugins/sweetalert2/sweetalert2.min.js"></script>
  <!-- page script -->
  <script>
    $(document).ready(function() {
      $(".preloader").delay(100).fadeOut();
      $('[data-mask]').inputmask();
      $('.select2').select2();
      
    });
  </script>
</head>
<body class="hold-transition sidebar-mini layout-navbar-fixed layout-fixed">
<div class="preloader">
	<div class="loading">
		<img src="{base_url}assets/adminlte/dist/img/loading.gif" width="88">
		<!--       <div>
		<p>Harap Tunggu...</p>
		</div>-->
	</div>
</div>
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-dark navbar-danger header">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
      </li>
      <!-- <li class="nav-item d-none d-sm-inline-block">
        <a href="{base_url}assets/adminlte/index3.html" class="nav-link">Home</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Contact</a>
      </li> -->
    </ul>

    <!-- SEARCH FORM -->
    <!-- <form class="form-inline ml-3">
      <div class="input-group input-group-sm">
        <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-navbar" type="submit">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>
    </form> -->

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
          <!-- <span class="badge badge-warning navbar-badge">15</span> -->
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header">0 Notifications</span>
          <!-- <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-envelope mr-2"></i> 4 new messages
            <span class="float-right text-muted text-sm">3 mins</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-users mr-2"></i> 8 friend requests
            <span class="float-right text-muted text-sm">12 hours</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-file mr-2"></i> 3 new reports
            <span class="float-right text-muted text-sm">2 days</span>
          </a> -->
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
        </div>
      </li>
      <!-- <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#">
          <i class="fas fa-th-large"></i>
        </a>
      </li> -->
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
      <img src="{base_url}assets/adminlte/dist/img/favicon.ico"
           class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">{title}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{base_url}<?php echo strlen($this->session->userdata('photo'))>1?$this->session->userdata('photo'):'assets/adminlte/dist/img/user2-160x160.jpg'; ?>" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="{base_url}profile/data/update" class="d-block"><?=$this->session->userdata('fullname')?></a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          
          {menus}

          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-briefcase"></i>
              <p>
                  Process 1
                  <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a id="users" href="#" class="nav-link menu">
                <i class="nav-icon fas fa-circle"></i>
                <p>
                    Flow 1
                </p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item">
            <a href="{base_url}auth/logout" class="menu nav-link">
              <i class="nav-icon fas fa-power-off"></i>
              <p>
                Logout
              </p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper content-bg">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{content_title}</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">{content_title}</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    {content}
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="float-right d-none d-sm-block">
     <!--  <b>Version</b> 3.0.1 -->
    </div>
    <!-- <strong>Copyright &copy; 2014-2019 <a href="http://adminlte.io">AdminLTE.io</a>.</strong> All rights
    reserved. -->
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- Custom Validation -->
<script src="{base_url}assets/adminlte/dist/js/custom_validation.js"></script>


{extrascript}
</body>
</html>

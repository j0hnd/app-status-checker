<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Fontawesome -->
    <link href="{{ asset('css/plugins/all.min.css') }}" rel="stylesheet">

    <!-- Overlay Scrollbars -->
    <link href="{{ asset('css/plugins/OverlayScrollbars.min.css') }}" rel="stylesheet">

    <!-- Toastr -->
    <link href="{{ asset('css/plugins/toastr.min.css') }}" rel="stylesheet">

    <!-- SweetAlert -->
    <link href="{{ asset('css/plugins/sweetalert2.min.css') }}" rel="stylesheet">

    <!-- Summernote -->
    <link href="{{ asset('css/plugins/summernote.min.css') }}" rel="stylesheet">

    <!-- Ionicons -->
    <link rel="stylesheet" href="//code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

    <!-- DataTables -->
    <link href="{{ asset('css/plugins/dataTables.bootstrap4.min.css') }}" rel="stylesheet">

    <!-- Select2 -->
    <link href="{{ asset('css/plugins/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/plugins/select2-bootstrap4.min.css') }}" rel="stylesheet">

    @yield("custom-styles")
</head>

<body class="hold-transition sidebar-mini layout-fixed">
<!-- Site wrapper -->
<div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="javascript:void(0)" role="button"><i class="fas fa-bars"></i></a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="javascript:void(0)" class="nav-link">{{ $page_title }}</a>
            </li>
        </ul>

         <ul class="navbar-nav ml-auto">
             <li class="nav-item">
                 <a class="nav-link" data-widget="fullscreen" data-slide="true" href="javascript:void(0)" role="button">
                     <i class="fas fa-compress-arrows-alt"></i>
                 </a>
             </li>
             <li class="nav-item">
                 <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="javascript:void(0)" role="button">
                     <i class="fas fa-th-large"></i>
                 </a>
             </li>
         </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="javascript:void(0)" class="brand-link">
            <img src="/img/logo-small.png"
                 alt="App Status Checker"
                 class="brand-image img-circle elevation-3"
                 style="opacity: .8">
            <span class="brand-text font-weight-light"><strong>App</strong> Status Checker</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar user (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img src="/img/avatar/avatar04.png" class="img-circle elevation-2" alt="User Image">
                </div>
                <div class="info">
                    <a href="javascript:void(0)" class="d-block">{{ ucwords(Auth::user()->firstname ) }} {{ ucwords(Auth::user()->lastname) }}</a>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <!-- Add icons to the links using the .nav-icon class
                         with font-awesome or any other icon font library -->
                    <li class="nav-item has-treeview">
                        <a href="{{ route('dashboard.index') }}" class="nav-link {{ $page_title == "Dashboard" ? "active" : "" }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('application.index') }}" class="nav-link {{ $page_title == "Applications" ? "active" : "" }}">
                            <i class="nav-icon fas fa-th"></i>
                            <p>Applications</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('webhook.index') }}" class="nav-link {{ $page_title == "Webhooks" ? "active" : "" }}">
                            <i class="nav-icon fas fa-copy"></i>
                            <p>Webhooks</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('heartbeat.index') }}" class="nav-link {{ $page_title == "Logs" ? "active" : "" }}">
                            <i class="nav-icon fas fa-list-ul" aria-hidden="true"></i>
                            <p>Logs</p>
                        </a>
                    </li>
                    <li class="nav-item has-treeview {{ $page_title == 'Users' ? ' menu-is-opening menu-open' : '' }}">
                        <a href="javascript:void(0)" class="nav-link">
                            <i class="nav-icon fas fa-cog"></i>
                            <p>Settings<i class="fas fa-angle-left right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('user.index') }}" class="nav-link {{ $page_title == "Users" ? "active" : "" }}">
                                    <i class="fa fa-angle-right nav-icon"></i>
                                    <p>Users</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('user.change_password') }}" class="nav-link {{ $page_title == "Change Password" ? "active" : "" }}">
                                    <i class="fa fa-angle-right nav-icon"></i>
                                    <p>Change Password</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
                <ul class="nav nav-pills nav-sidebar flex-column mt-5" data-widget="treeview" role="menu" data-accordion="false">
                    <li class="nav-item">
                        <a href="{{ route('logout') }}" class="nav-link">
                            <i class="nav-icon fas fa-sign-out"></i>
                            <p>Logout</p>
                        </a>
                    </li>
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>{{ $page_title }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="javascript:void(0)">{{ $breadcrumb_parent }}</a></li>
                            @if(isset($breadcrumb_child))
                            <li class="breadcrumb-item"><a href="javascript:void(0)">{{ $breadcrumb_child }}</a></li>
                            @endif
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">

            <div class="container-fluid">
                @yield('content')
            </div>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <footer class="main-footer">
        <div class="float-right d-none d-sm-block">
            <b>Version</b> 1.0.0
        </div>
        <strong>Copyright &copy; 2014-2019 <a href="http://adminlte.io">AdminLTE.io</a>.</strong> All rights
        reserved.
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>

{{-- plugins --}}
<script src="{{ asset('js/plugins/jquery.validate.min.js') }}"></script>
<script src="{{ asset('js/plugins/additional-methods.js') }}"></script>
<script src="{{ asset('js/plugins/jquery.overlayScrollbars.min.js') }}"></script>
<script src="{{ asset('js/plugins/toastr.min.js') }}"></script>
<script src="{{ asset('js/plugins/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('js/plugins/summernote.min.js') }}"></script>
<script src="{{ asset('js/plugins/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/plugins/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('js/plugins/select2.full.min.js') }}"></script>
<script src="{{ asset('js/plugins/matchHeight.js') }}"></script>

{{--components--}}
<script src="{{ asset('js/components/common.js') }}"></script>
<script src="{{ asset('js/components/application.js') }}"></script>
<script src="{{ asset('js/components/dashboard.js') }}"></script>

<!-- Custom Scripts -->
@yield('custom-scripts')
</body>
</html>

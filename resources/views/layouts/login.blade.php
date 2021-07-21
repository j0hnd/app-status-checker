<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} | Login</title>

    <!-- Fontawesome -->
    <link href="{{ asset('css/plugins/all.min.css') }}" rel="stylesheet">

    <!-- icheck bootstrap -->
    <link href="{{ asset('css/plugins/icheck-bootstrap.min.css') }}" rel="stylesheet">

    <!-- Ionicons -->
    <link rel="stylesheet" href="//code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

    <!-- Styles -->
    <link href="{{ asset('css/plugins/adminlte.min.css') }}" rel="stylesheet">

    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

    @yield('custom-styles')
</head>

<body class="hold-transition login-page">
@yield('content')
<!-- ./wrapper -->

<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
<script>
    var authenticateUrl = "{{ route('authenticate') }}";
    var forgotPasswordUrl = "{{ route('forgot_password') }}";
</script>

{{-- plugins --}}
<script src="{{ asset('js/plugins/jquery.validate.min.js') }}"></script>
<script src="{{ asset('js/plugins/additional-methods.js') }}"></script>
<script src="{{ asset('js/plugins/adminlte.min.js') }}"></script>
<script src="{{ asset('js/plugins/matchHeight.js') }}"></script>
<script src="{{ asset('js/components/login.js') }}"></script>
<script src="{{ asset('js/components/reset_password.js') }}"></script>
@yield('custom-scripts')
</body>
</html>

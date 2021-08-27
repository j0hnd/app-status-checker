@extends('layouts.login')

@section('content')
    <div class="login-box">
        <div class="login-logo">
            <img src="/img/logo.png" class="brand-image logo img-circle elevation-3" alt="Logo">
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Sign in to start your session</p>

                <form id="loginForm" method="post">
                    <p id="login-error" class="bg-danger p-2 d-none"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> <span id="login-error-message">Invalid email address and/or password</span></p>
                    <p id="login-success" class="bg-success p-2 d-none"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> <span id="login-success-message">Invalid email address and/or password</span></p>

                    <div class="input-group mb-3">
                        <input type="email" class="form-control" name="email" id="email" placeholder="Email">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" name="password" id="password" placeholder="Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" id="remember">
                                <label for="remember">
                                    Remember Me
                                </label>
                            </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>

                <p class="mb-1">
                    <a href="javascript: void(0);" id="toggle-forgot-password">I forgot my password</a>
                </p>
            </div>
            <!-- /.login-card-body -->

            <div class="card-body forgot-password-card-body d-none">
                <p class="login-box-msg">Reset your password</p>

                <form id="forgotPasswordForm" method="post">
                    <p id="forgot-password-error" class="bg-danger p-2 d-none"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> <span id="forgot-password-error-message">Invalid email address and/or password</span></p>
                    <p id="forgot-password-success" class="bg-success p-2 d-none"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> <span id="forgot-password-success-message">Invalid email address and/or password</span></p>

                    <div class="input-group mb-3">
                        <input type="email" class="form-control" name="email" id="email" placeholder="Email">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 text-right">
                            <button type="button" class="btn btn-link" id="toggle-cancel-forgot-password">Cancel</button>
                            <button type="button" class="btn btn-primary" id="toggle-submit-forgot-password">Reset Password</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
            </div>
        </div>
@endsection

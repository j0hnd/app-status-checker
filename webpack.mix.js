const mix = require('laravel-mix');
const del = require('del');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')

    // javascripts
    .copy('resources/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js', 'public/js/plugins')
    .copy('resources/plugins/jquery-validation/jquery.validate.min.js', 'public/js/plugins')
    .copy('resources/plugins/jquery-validation/additional-methods.js', 'public/js/plugins')
    .copy('resources/plugins/toastr/toastr.min.js', 'public/js/plugins')
    .copy('resources/plugins/toastr/toastr.js.map', 'public/js/plugins')
    .copy('resources/plugins/sweetalert2/sweetalert2.all.min.js', 'public/js/plugins')
    .copy('resources/plugins/summernote/summernote.min.js', 'public/js/plugins')
    .copy('resources/plugins/summernote/summernote.min.js.map', 'public/js/plugins')
    .copy('resources/plugins/datatables/jquery.dataTables.min.js', 'public/js/plugins')
    .copy('resources/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js', 'public/js/plugins')
    .copy('resources/plugins/datatables-buttons/js/buttons.bootstrap4.min.js', 'public/js/plugins')
    .copy('resources/plugins/select2/js/select2.full.min.js', 'public/js/plugins')
    .copy('resources/plugins/adminlte/js/adminlte.min.js', 'public/js/plugins')
    .copy('resources/plugins/match-height/matchHeight.js', 'public/js/plugins')

    .combine([
        'resources/js/components/common.js',
        'resources/js/components/application.js',
        'resources/js/components/dashboard.js'
    ], 'public/js/components/app-component.js')

    .combine([
        'resources/js/components/login.js',
        'resources/js/components/reset_password.js'
    ], 'public/js/components/login-component.js')

    // css
    .copy('resources/plugins/overlayScrollbars/css/OverlayScrollbars.min.css', 'public/css/plugins')
    .copy('resources/plugins/toastr/toastr.min.css', 'public/css/plugins')
    .copy('resources/plugins/sweetalert2/sweetalert2.min.css', 'public/css/plugins')
    .copy('resources/plugins/summernote/summernote.min.css', 'public/css/plugins')
    .copy('resources/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css', 'public/css/plugins')
    .copy('resources/plugins/datatables-buttons/css/buttons.bootstrap4.min.css', 'public/css/plugins')
    .copy('resources/plugins/select2/css/select2.min.css', 'public/css/plugins')
    .copy('resources/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css', 'public/css/plugins')
    .copy('resources/plugins/icheck-bootstrap/icheck-bootstrap.min.css', 'public/css/plugins')

    // font
    .copy('resources/plugins/fontawesome-free/webfonts', 'public/css/webfonts')
    .copy('resources/plugins/summernote/font', 'public/css/plugins/font')

    .sourceMaps()
    .minify('public/css/app.css')
    .minify('public/js/app.js')
    .minify('public/js/components/app-component.js')
    .minify('public/js/components/login-component.js');

if (mix.inProduction()) {
    mix.version();

    del('public/css/app.css');
    del('public/js/app.js');
    del('public/js/components/app-component.js');
    del('public/js/components/login-component.js');
}


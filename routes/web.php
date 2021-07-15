<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [App\Http\Controllers\Auth\LoginController::class, 'index']);
Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'index'])->name('login');
Route::get('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
Route::post('/authenticate', [App\Http\Controllers\Auth\LoginController::class, 'authenticate'])->name('authenticate');
Route::post('/forgot/password', [App\Http\Controllers\Auth\LoginController::class, 'forgot_password'])->name('forgot_password');

Route::middleware('auth')->group(function () {
    Route::prefix('dashboard')->group(function () {
        Route::get('/', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard.index');
    });

    Route::prefix('application')->group(function () {
        Route::get('/', [App\Http\Controllers\ApplicationController::class, 'index'])->name('application.index');
        Route::get('/add', [App\Http\Controllers\ApplicationController::class, 'create'])->name('application.add');
        Route::get('/edit/{code}', [App\Http\Controllers\ApplicationController::class, 'edit'])->name('application.edit');
        Route::get('/get-data', [App\Http\Controllers\ApplicationController::class, 'get_data'])->name('application.get');
        Route::get('/create-endpoint-param-row', [App\Http\Controllers\ApplicationController::class, 'create_endpoint_param_row'])->name('application.create_endpoint_param_row');

        Route::post('/save', [App\Http\Controllers\ApplicationController::class, 'store'])->name('application.save');

        Route::put('/update/{code}', [App\Http\Controllers\ApplicationController::class, 'update'])->name('application.update');
        Route::put('/delete', [App\Http\Controllers\ApplicationController::class, 'delete'])->name('application.delete');
    });

    Route::prefix('webhook')->group(function () {
        Route::get('/', [App\Http\Controllers\WebhookController::class, 'index'])->name('webhook.index');
        Route::get('/add', [App\Http\Controllers\WebhookController::class, 'create'])->name('webhook.add');
        Route::get('/edit/{code}', [App\Http\Controllers\WebhookController::class, 'edit'])->name('webhook.edit');
        Route::get('/get-data', [App\Http\Controllers\WebhookController::class, 'get_data'])->name('webhook.get');

        Route::post('/save', [App\Http\Controllers\WebhookController::class, 'store'])->name('webhook.save');

        Route::put('/update/{code}', [App\Http\Controllers\WebhookController::class, 'update'])->name('webhook.update');
        Route::put('/delete', [App\Http\Controllers\WebhookController::class, 'delete'])->name('webhook.delete');
    });

    Route::prefix('users')->group(function () {
        Route::get('/', [App\Http\Controllers\UserController::class, 'index'])->name('user.index');
        Route::get('/add', [App\Http\Controllers\UserController::class, 'create'])->name('user.add');
        Route::get('/edit/{code}', [App\Http\Controllers\UserController::class, 'edit'])->name('user.edit');
        Route::get('/get-data', [App\Http\Controllers\UserController::class, 'get_data'])->name('user.get');
        Route::get('/change/password', [App\Http\Controllers\UserController::class, 'change_password'])->name('user.change_password');

        Route::post('/save', [App\Http\Controllers\UserController::class, 'store'])->name('user.save');

        Route::put('/reset/password/{code}', [App\Http\Controllers\UserController::class, 'reset_password'])->name('user.reset_password');
        Route::put('/change/password', [App\Http\Controllers\UserController::class, 'save_change_password'])->name('user.save_change_password');
        Route::put('/update/{code}', [App\Http\Controllers\UserController::class, 'update'])->name('user.update');
        Route::put('/delete', [App\Http\Controllers\UserController::class, 'delete'])->name('user.delete');
    });

    Route::prefix('heartbeat')->group(function () {
        Route::get('/logs/{code}',  [App\Http\Controllers\HeartbeatController::class, 'logs'])->name('heartbeat.logs');
    });
});

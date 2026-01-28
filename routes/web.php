<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

Route::get('/', function () {
    return redirect('/login');
});

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout']);

Route::get('/dashboard', [AuthController::class, 'dashboard'])
    ->name('dashboard');

/*
|--------------------------------------------------------------------------
| RECUPERACIÓN DE CONTRASEÑA
|--------------------------------------------------------------------------
*/
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForm'])
    ->name('password.request');

Route::post('/forgot-password',
    [ForgotPasswordController::class, 'sendEmail']
)->name('password.email');


Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])
    ->name('password.reset');

Route::post('/reset-password',
    [ResetPasswordController::class, 'updatePassword']
)->name('password.update');


/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/

Route::get('/admin', function () {
    return 'Solo ADMIN';
})->middleware('rol:ADMIN');

Route::get('/personas', [PersonaController::class, 'index'])
    ->middleware('rol:ADMIN');

Route::middleware(['rol:ADMIN'])->group(function () {

    Route::get('/usuarios', [UsuarioController::class, 'index'])
        ->name('usuarios.index');

    Route::get('/usuarios/crear', [UsuarioController::class, 'create'])
        ->name('usuarios.create');

    Route::get('/usuarios/{id}/editar', [UsuarioController::class, 'edit'])
        ->name('usuarios.edit');

    Route::put('/usuarios/{id}', [UsuarioController::class, 'update'])
        ->name('usuarios.update');

    Route::delete('/usuarios/{id}', [UsuarioController::class, 'destroy'])
        ->name('usuarios.destroy');
});


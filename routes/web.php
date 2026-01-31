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

    Route::post('/usuarios', [UsuarioController::class, 'store'])
            ->name('usuarios.store');

    Route::patch('/usuarios/{id}/estado', [UsuarioController::class, 'cambiarEstado'])
            ->name('usuarios.cambiarEstado');
});

//------------------------------------------------------------------------------
// TEXTURA
//-------------------------------------------------------------------------------
use App\Http\Controllers\TexturaController;

Route::middleware(['rol:ANALISTA,ADMIN'])->group(function () {

    Route::get(
            '/ingreso-datos/textura',
            [TexturaController::class, 'archivos']
    )->name('textura.index');

    Route::get(
            '/ingreso-datos/textura/{archivo}/muestras',
            [TexturaController::class, 'muestras']
    )->name('textura.muestras');
    
    
    Route::get(
            '/ingreso-datos/textura/muestra/{id}/editar',
            [PermeabilidadAireController::class, 'edit']
    )->name('textura.muestra.edit');    
    
});

//------------------------------------------------------------------------------
// DENSIDAD APARENTE
//-------------------------------------------------------------------------------
//------------------------------------------------------------------------------
// Permeabilidad de Aire PLANTILLA
//-------------------------------------------------------------------------------
use App\Http\Controllers\PermeabilidadAireController;

Route::middleware(['rol:ANALISTA,ADMIN'])->group(function () {

    //url
    //controller
    //sidebar
    Route::get(
            '/ingreso-datos/permeabilidad-aire',
            [PermeabilidadAireController::class, 'lotes']
    )->name('pa.index');

    Route::get(
            '/ingreso-datos/permeabilidad-aire/{lote}/muestras',
            [PermeabilidadAireController::class, 'muestras']
    )->name('pa.muestras');

    Route::get(
            '/ingreso-datos/permeabilidad-aire/muestra/{id}/editar',
            [PermeabilidadAireController::class, 'edit']
    )->name('pa.muestra.edit');

    Route::put(
            '/ingreso-datos/permeabilidad-aire/muestra/{id}',
            [PermeabilidadAireController::class, 'update']
    )->name('pa.muestra.update');
});
//------------------------------------------------------------------------------
// ----------------------------------------------------------PLANTILLA
//-------------------------------------------------------------------------------
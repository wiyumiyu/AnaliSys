<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Administracion\BitacoraController;


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
        // ===============================
    // BITÁCORA
    // ===============================
    Route::get('/bitacora', [BitacoraController::class, 'index'])
            ->name('bitacora.index');
    Route::get(
        '/bitacora/{id}',
        [\App\Http\Controllers\Administracion\BitacoraController::class, 'show']
        )->name('bitacora.show');

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

    // editar muestra
    Route::get(
            '/ingreso-datos/textura/muestra/{id}/editar',
            [TexturaController::class, 'edit']
    )->name('textura.muestra.edit');

    // actualizar muestra
    Route::put(
            '/ingreso-datos/textura/muestra/{id}',
            [TexturaController::class, 'update']
    )->name('textura.muestra.update');

    // anular muestra
//    Route::patch(
//            '/ingreso-datos/textura/muestra/{id}/anular',
//            [TexturaController::class, 'anular']
//    )->name('textura.muestra.anular');

    // anular y activar muestra
    Route::patch(
            '/ingreso-datos/textura/muestra/{id}/estado',
            [TexturaController::class, 'toggleEstado']
    )->name('textura.muestra.toggle');

    // eliminar muestra
    Route::delete(
            '/ingreso-datos/textura/muestra/{id}',
            [TexturaController::class, 'destroy']
    )->name('textura.muestra.destroy');
});

//------------------------------------------------------------------------------
// DENSIDAD APARENTE
//-------------------------------------------------------------------------------
use App\Http\Controllers\DensidadAparenteController;

Route::middleware(['rol:ANALISTA,ADMIN'])->group(function () {

    Route::get(
        '/ingreso-datos/densidad-aparente',
        [DensidadAparenteController::class, 'archivos']
    )->name('densidad_aparente.index');

    Route::get(
        '/ingreso-datos/densidad-aparente/{archivo}/muestras',
        [DensidadAparenteController::class, 'muestras']
    )->name('densidad_aparente.muestras');

    Route::get(
        '/ingreso-datos/densidad-aparente/muestra/{id}/editar',
        [DensidadAparenteController::class, 'edit']
    )->name('densidad_aparente.muestra.edit');

    Route::put(
        '/ingreso-datos/densidad-aparente/muestra/{id}',
        [DensidadAparenteController::class, 'update']
    )->name('densidad_aparente.muestra.update');

});

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
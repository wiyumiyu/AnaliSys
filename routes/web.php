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
    
    Route::post(
    '/ingreso-datos/textura/importar',
    [TexturaController::class, 'importar']
)->name('textura.importar');
    
    
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

    Route::patch(
        '/ingreso-datos/densidad-aparente/muestra/{id}/estado',
        [DensidadAparenteController::class, 'toggleEstado']
    )->name('densidad_aparente.muestra.toggle');

    Route::delete(
        '/ingreso-datos/densidad-aparente/muestra/{id}',
        [DensidadAparenteController::class, 'destroy']
     )->name('densidad_aparente.muestra.destroy');

     Route::delete(
        '/ingreso-datos/densidad-aparente/{id}',
        [DensidadAparenteController::class, 'destroyArchivo']
     )->name('densidad_aparente.destroy');

     Route::post(
       '/ingreso-datos/densidad-aparente/importar',
        [DensidadAparenteController::class, 'importar']
     )->name('densidad_aparente.importar');
    

});

//------------------------------------------------------------------------------
// DENSIDAD PARTICULAS
//-------------------------------------------------------------------------------
use App\Http\Controllers\DensidadParticulasController;

Route::middleware(['rol:ANALISTA,ADMIN'])->group(function () {

    Route::get(
        '/ingreso-datos/densidad-particulas',
        [DensidadParticulasController::class, 'archivos']
    )->name('densidad_particulas.index');

    Route::get(
        '/ingreso-datos/densidad-particulas/{archivo}/muestras',
        [DensidadParticulasController::class, 'muestras']
    )->name('densidad_particulas.muestras');

    Route::get(
        '/ingreso-datos/densidad-particulas/muestra/{id}/editar',
        [DensidadParticulasController::class, 'edit']
    )->name('densidad_particulas.muestra.edit');

    Route::put(
        '/ingreso-datos/densidad-particulas/muestra/{id}',
        [DensidadParticulasController::class, 'update']
    )->name('densidad_particulas.muestra.update');

    Route::patch(
        '/ingreso-datos/densidad-particulas/muestra/{id}/estado',
        [DensidadParticulasController::class, 'toggleEstado']
    )->name('densidad_particulas.muestra.toggle');

    Route::delete(
        '/ingreso-datos/densidad-particulas/muestra/{id}',
        [DensidadParticulasController::class, 'destroy']
     )->name('densidad_particulas.muestra.destroy');

     Route::delete(
        '/ingreso-datos/densidad-particulas/{id}',
        [DensidadParticulasController::class, 'destroyArchivo']
     )->name('densidad_particulas.destroy');

     Route::post(
       '/ingreso-datos/densidad-particulas/importar',
        [DensidadParticulasController::class, 'importar']
     )->name('densidad_particulas.importar');
    

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
// HUMEDAD GRAVIMÉTRICA
//-------------------------------------------------------------------------------
use App\Http\Controllers\HumedadGravimetricaController;

Route::middleware(['rol:ANALISTA,ADMIN'])->group(function () {

    //Listado de archivos
    Route::get(
        '/ingreso-datos/humedad-gravimetrica',
        [HumedadGravimetricaController::class, 'archivos']
    )->name('humedad_gravimetrica.index');

    //Listado de muestras por archivo
    Route::get(
        '/ingreso-datos/humedad-gravimetrica/{archivo}/muestras',
        [HumedadGravimetricaController::class, 'muestras']
    )->name('humedad_gravimetrica.muestras');

    //Editar muestra
    Route::get(
        '/ingreso-datos/humedad-gravimetrica/muestra/{id}/editar',
        [HumedadGravimetricaController::class, 'edit']
    )->name('humedad_gravimetrica.muestra.edit');

    //Actualizar muestra
    Route::put(
        '/ingreso-datos/humedad-gravimetrica/muestra/{id}',
        [HumedadGravimetricaController::class, 'update']
    )->name('humedad_gravimetrica.muestra.update');

    //Activar / anular muestra
    Route::patch(
        '/ingreso-datos/humedad-gravimetrica/muestra/{id}/estado',
        [HumedadGravimetricaController::class, 'toggleEstado']
    )->name('humedad_gravimetrica.muestra.toggle');

    //Eliminar muestra
    Route::delete(
        '/ingreso-datos/humedad-gravimetrica/muestra/{id}',
        [HumedadGravimetricaController::class, 'destroy']
    )->name('humedad_gravimetrica.muestra.destroy');

    //Eliminar archivo completo
    Route::delete(
        '/ingreso-datos/humedad-gravimetrica/{id}',
        [HumedadGravimetricaController::class, 'destroyArchivo']
    )->name('humedad_gravimetrica.destroy');

    //Importar archivo
    Route::post(
        '/ingreso-datos/humedad-gravimetrica/importar',
        [HumedadGravimetricaController::class, 'importar']
    )->name('humedad_gravimetrica.importar');

});

//------------------------------------------------------------------------------
// CONDUCTIVIDAD HIDRÁULICA
//-------------------------------------------------------------------------------
use App\Http\Controllers\ConductividadHidraulicaController;

Route::middleware(['rol:ANALISTA,ADMIN'])->group(function () {

    Route::get(
        '/ingreso-datos/conductividad-hidraulica',
        [ConductividadHidraulicaController::class, 'archivos']
    )->name('conductividad_hidraulica.index');

    Route::get(
        '/ingreso-datos/conductividad-hidraulica/{archivo}/muestras',
        [ConductividadHidraulicaController::class, 'muestras']
    )->name('conductividad_hidraulica.muestras');

    Route::get(
        '/ingreso-datos/conductividad-hidraulica/muestra/{id}/editar',
        [ConductividadHidraulicaController::class, 'edit']
    )->name('conductividad_hidraulica.muestra.edit');

    Route::put(
        '/ingreso-datos/conductividad-hidraulica/muestra/{id}',
        [ConductividadHidraulicaController::class, 'update']
    )->name('conductividad_hidraulica.muestra.update');

    Route::patch(
        '/ingreso-datos/conductividad-hidraulica/muestra/{id}/estado',
        [ConductividadHidraulicaController::class, 'toggleEstado']
    )->name('conductividad_hidraulica.muestra.toggle');

    Route::delete(
        '/ingreso-datos/conductividad-hidraulica/muestra/{id}',
        [ConductividadHidraulicaController::class, 'destroy']
    )->name('conductividad_hidraulica.muestra.destroy');

    Route::delete(
        '/ingreso-datos/conductividad-hidraulica/{id}',
        [ConductividadHidraulicaController::class, 'destroyArchivo']
    )->name('conductividad_hidraulica.destroy');

    Route::post(
        '/ingreso-datos/conductividad-hidraulica/importar',
        [ConductividadHidraulicaController::class, 'importar']
    )->name('conductividad_hidraulica.importar');

});


//------------------------------------------------------------------------------
// RETENCIÓN DE HUMEDAD
//-------------------------------------------------------------------------------
use App\Http\Controllers\RetencionHumedadController;

Route::middleware(['rol:ANALISTA,ADMIN'])->group(function () {

    Route::get(
        '/ingreso-datos/retencion-humedad',
        [RetencionHumedadController::class, 'archivos']
    )->name('retencion_humedad.index');

    Route::get(
        '/ingreso-datos/retencion-humedad/{archivo}/muestras',
        [RetencionHumedadController::class, 'muestras']
    )->name('retencion_humedad.muestras');

    Route::get(
        '/ingreso-datos/retencion-humedad/muestra/{id}/editar',
        [RetencionHumedadController::class, 'edit']
    )->name('retencion_humedad.muestra.edit');

    Route::put(
        '/ingreso-datos/retencion-humedad/muestra/{id}',
        [RetencionHumedadController::class, 'update']
    )->name('retencion_humedad.muestra.update');

    Route::patch(
        '/ingreso-datos/retencion-humedad/muestra/{id}/estado',
        [RetencionHumedadController::class, 'toggleEstado']
    )->name('retencion_humedad.muestra.toggle');

    Route::delete(
        '/ingreso-datos/retencion-humedad/muestra/{id}',
        [RetencionHumedadController::class, 'destroy']
    )->name('retencion_humedad.muestra.destroy');

    Route::delete(
        '/ingreso-datos/retencion-humedad/{id}',
        [RetencionHumedadController::class, 'destroyArchivo']
    )->name('retencion_humedad.destroy');

    Route::post(
        '/ingreso-datos/retencion-humedad/importar',
        [RetencionHumedadController::class, 'importar']
    )->name('retencion_humedad.importar');

});




//------------------------------------------------------------------------------
// ----------------------------------------------------------PLANTILLA
//-------------------------------------------------------------------------------
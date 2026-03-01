<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Administracion\BitacoraController;
use App\Http\Controllers\Resultados\ResultadosController;
use App\Http\Controllers\Administracion\UsuarioController;
use App\Http\Controllers\IngresoDatos\DensidadAparenteController;
use App\Http\Controllers\IngresoDatos\TexturaController as IngresoTexturaController;
use App\Http\Controllers\Controles\TexturaController as ControlTexturaController;
use App\Http\Controllers\IngresoDatos\CoeficienteExtensibilidadController;
use App\Http\Controllers\IngresoDatos\EstabilidadAgregadosController;
use App\Http\Controllers\IngresoDatos\GranulometriaController;
use App\Http\Controllers\IngresoDatos\RetencionHumedadController;
use App\Http\Controllers\IngresoDatos\ConductividadHidraulicaController;
use App\Http\Controllers\IngresoDatos\HumedadGravimetricaController;
use App\Http\Controllers\IngresoDatos\DensidadParticulasController;
use App\Http\Controllers\IngresoDatos\PermeabilidadAireController;
use App\Http\Controllers\ReportesClientes\ReporteClienteController;

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



Route::middleware(['rol:ANALISTA,ADMIN'])->group(function () {

    Route::get(
            '/ingreso-datos/textura',
            [IngresoTexturaController::class, 'index']
    )->name('textura.index');

    Route::get(
            '/ingreso-datos/textura/{archivo}/muestras',
            [IngresoTexturaController::class, 'muestras']
    )->name('textura.muestras');

    Route::get(
            '/ingreso-datos/textura/muestra/{id}/editar',
            [IngresoTexturaController::class, 'edit']
    )->name('textura.muestra.edit');

    Route::put(
            '/ingreso-datos/textura/muestra/{id}',
            [IngresoTexturaController::class, 'update']
    )->name('textura.muestra.update');

    Route::patch(
            '/ingreso-datos/textura/muestra/{id}/estado',
            [IngresoTexturaController::class, 'toggleEstado']
    )->name('textura.muestra.toggle');

    Route::delete(
            '/ingreso-datos/textura/muestra/{id}',
            [IngresoTexturaController::class, 'destroy']
    )->name('textura.muestra.destroy');

    Route::post(
            '/ingreso-datos/textura/importar',
            [IngresoTexturaController::class, 'importar']
    )->name('textura.importar');
});

//------------------------------------------------------------------------------
// CONTROL DE TEXTURA
//-------------------------------------------------------------------------------

Route::middleware(['rol:ANALISTA,ADMIN'])->group(function () {

    Route::get(
            '/controles/textura',
            [ControlTexturaController::class, 'index']
    )->name('controlTextura.index');

    Route::get(
            '/controles/textura/consecutivo',
            [ControlTexturaController::class, 'traerConsecutivo']
    )->name('controlTextura.consecutivo');

    Route::post(
            '/controles/textura',
            [ControlTexturaController::class, 'guardarControl']
    )->name('controlTextura.store');

    Route::delete(
            '/control-textura/{id}',
            [ControlTexturaController::class, 'destroy']
    )->name('controlTextura.destroy');

    Route::get('/controles/textura/{id}/graficos',
            [ControlTexturaController::class, 'graficos']
    )->name('controles.textura.graficos');

    Route::post('/controles/textura/{id}/comentario',
            [ControlTexturaController::class, 'guardarComentario']
    )->name('controles.textura.comentar');
});

//------------------------------------------------------------------------------
// DENSIDAD APARENTE
//-------------------------------------------------------------------------------


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


Route::middleware(['rol:ANALISTA,ADMIN'])->group(function () {

    Route::get(
            '/ingreso-datos/permeabilidad-aire',
            [PermeabilidadAireController::class, 'archivos']
    )->name('permeabilidad_aire.index');

    Route::get(
            '/ingreso-datos/permeabilidad-aire/{archivo}/muestras',
            [PermeabilidadAireController::class, 'muestras']
    )->name('permeabilidad_aire.muestras');

    Route::get(
            '/ingreso-datos/permeabilidad-aire/muestra/{id}/editar',
            [PermeabilidadAireController::class, 'edit']
    )->name('permeabilidad_aire.muestra.edit');

    Route::put(
            '/ingreso-datos/permeabilidad-aire/muestra/{id}',
            [PermeabilidadAireController::class, 'update']
    )->name('permeabilidad_aire.muestra.update');

    Route::patch(
            '/ingreso-datos/permeabilidad-aire/muestra/{id}/estado',
            [PermeabilidadAireController::class, 'toggleEstado']
    )->name('permeabilidad_aire.muestra.toggle');

    Route::delete(
            '/ingreso-datos/permeabilidad-aire/muestra/{id}',
            [PermeabilidadAireController::class, 'destroy']
    )->name('permeabilidad_aire.muestra.destroy');

    Route::delete(
            '/ingreso-datos/permeabilidad-aire/{id}',
            [PermeabilidadAireController::class, 'destroyArchivo']
    )->name('permeabilidad_aire.destroy');

    Route::post(
            '/ingreso-datos/permeabilidad-aire/importar',
            [PermeabilidadAireController::class, 'importar']
    )->name('permeabilidad_aire.importar');
});

//------------------------------------------------------------------------------
// HUMEDAD GRAVIMÉTRICA
//-------------------------------------------------------------------------------

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
// Granulometría
//-------------------------------------------------------------------------------

Route::middleware(['rol:ANALISTA,ADMIN'])->group(function () {

    Route::get(
            '/ingreso-datos/granulometria',
            [GranulometriaController::class, 'archivos']
    )->name('granulometria.index');

    Route::get(
            '/ingreso-datos/granulometria/{archivo}/muestras',
            [GranulometriaController::class, 'muestras']
    )->name('granulometria.muestras');

    Route::get(
            '/ingreso-datos/granulometria/muestra/{id}/editar',
            [GranulometriaController::class, 'edit']
    )->name('granulometria.muestra.edit');

    Route::put(
            '/ingreso-datos/granulometria/muestra/{id}',
            [GranulometriaController::class, 'update']
    )->name('granulometria.muestra.update');

    Route::patch(
            '/ingreso-datos/granulometria/muestra/{id}/estado',
            [GranulometriaController::class, 'toggleEstado']
    )->name('granulometria.muestra.toggle');

    Route::delete(
            '/ingreso-datos/granulometria/muestra/{id}',
            [GranulometriaController::class, 'destroy']
    )->name('granulometria.muestra.destroy');

    Route::delete(
            '/ingreso-datos/granulometria/{id}',
            [GranulometriaController::class, 'destroyArchivo']
    )->name('granulometria.destroy');

    Route::post(
            '/ingreso-datos/granulometria/importar',
            [GranulometriaController::class, 'importar']
    )->name('granulometria.importar');
});

//------------------------------------------------------------------------------
// Estabilidad de Agregados
//-------------------------------------------------------------------------------

Route::middleware(['rol:ANALISTA,ADMIN'])->group(function () {

    Route::get(
            '/ingreso-datos/estabilidad-agregados',
            [EstabilidadAgregadosController::class, 'archivos']
    )->name('estabilidad_agregados.index');

    Route::get(
            '/ingreso-datos/estabilidad-agregados/{archivo}/muestras',
            [EstabilidadAgregadosController::class, 'muestras']
    )->name('estabilidad_agregados.muestras');

    Route::get(
            '/ingreso-datos/estabilidad-agregados/muestra/{id}/editar',
            [EstabilidadAgregadosController::class, 'edit']
    )->name('estabilidad_agregados.muestra.edit');

    Route::put(
            '/ingreso-datos/estabilidad-agregados/muestra/{id}',
            [EstabilidadAgregadosController::class, 'update']
    )->name('estabilidad_agregados.muestra.update');

    Route::patch(
            '/ingreso-datos/estabilidad-agregados/muestra/{id}/estado',
            [EstabilidadAgregadosController::class, 'toggleEstado']
    )->name('estabilidad_agregados.muestra.toggle');

    Route::delete(
            '/ingreso-datos/estabilidad-agregados/muestra/{id}',
            [EstabilidadAgregadosController::class, 'destroy']
    )->name('estabilidad_agregados.muestra.destroy');

    Route::delete(
            '/ingreso-datos/estabilidad-agregados/{id}',
            [EstabilidadAgregadosController::class, 'destroyArchivo']
    )->name('estabilidad_agregados.destroy');

    Route::post(
            '/ingreso-datos/estabilidad-agregados/importar',
            [EstabilidadAgregadosController::class, 'importar']
    )->name('estabilidad_agregados.importar');
});

//------------------------------------------------------------------------------
// Coeficiente de Extensibilidad
//-------------------------------------------------------------------------------

Route::middleware(['rol:ANALISTA,ADMIN'])->group(function () {

    Route::get(
            '/ingreso-datos/coeficiente-extensibilidad',
            [CoeficienteExtensibilidadController::class, 'archivos']
    )->name('coeficiente_extensibilidad.index');

    Route::get(
            '/ingreso-datos/coeficiente-extensibilidad/{archivo}/muestras',
            [CoeficienteExtensibilidadController::class, 'muestras']
    )->name('coeficiente_extensibilidad.muestras');

    Route::get(
            '/ingreso-datos/coeficiente-extensibilidad/muestra/{id}/editar',
            [CoeficienteExtensibilidadController::class, 'edit']
    )->name('coeficiente_extensibilidad.muestra.edit');

    Route::put(
            '/ingreso-datos/coeficiente-extensibilidad/muestra/{id}',
            [CoeficienteExtensibilidadController::class, 'update']
    )->name('coeficiente_extensibilidad.muestra.update');

    Route::patch(
            '/ingreso-datos/coeficiente-extensibilidad/muestra/{id}/estado',
            [CoeficienteExtensibilidadController::class, 'toggleEstado']
    )->name('coeficiente_extensibilidad.muestra.toggle');

    Route::delete(
            '/ingreso-datos/coeficiente-extensibilidad/muestra/{id}',
            [CoeficienteExtensibilidadController::class, 'destroy']
    )->name('coeficiente_extensibilidad.muestra.destroy');

    Route::delete(
            '/ingreso-datos/coeficiente-extensibilidad/{id}',
            [CoeficienteExtensibilidadController::class, 'destroyArchivo']
    )->name('coeficiente_extensibilidad.destroy');

    Route::post(
            '/ingreso-datos/coeficiente-extensibilidad/importar',
            [CoeficienteExtensibilidadController::class, 'importar']
    )->name('coeficiente_extensibilidad.importar');
});

//------------------------------------------------------------------------------
// RESULTADOS
//------------------------------------------------------------------------------

Route::middleware(['rol:ANALISTA,ADMIN'])->group(function () {

    // Listado
    Route::get(
        '/resultados',
        [ResultadosController::class, 'index']
    )->name('resultados.index');

    // Guardar
    Route::post(
        '/resultados',
        [ResultadosController::class, 'guardarResultado']
    )->name('resultados.store');

    // Eliminar
    Route::delete(
        '/resultados/{id}',
        [ResultadosController::class, 'destroy']
    )->name('resultados.destroy');

    // (Opcional) Ver detalle
    Route::get(
        '/resultados/{id}',
        [ResultadosController::class, 'show']
    )->name('resultados.show');

});


//------------------------------------------------------------------------------
// REPORTES CLIENTES
//------------------------------------------------------------------------------
Route::prefix('reportes-clientes')->group(function () {
    Route::get('/', [ReporteClienteController::class, 'index'])
        ->name('reportes_clientes.index');

    Route::get('/{id}', [ReporteClienteController::class, 'show'])
        ->name('reportes_clientes.show');
});
//------------------------------------------------------------------------------
// ----------------------------------------------------------PLANTILLA


//------------------------------------------------------------------------------
// ----------------------------------------------------------PLANTILLA
//-------------------------------------------------------------------------------
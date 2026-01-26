<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\UsuarioController;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/dashboard', [AuthController::class, 'dashboard'])
        ->name('dashboard');

Route::post('/logout', [AuthController::class, 'logout']);

Route::get('/admin', function () {
    return 'Solo ADMIN';
})->middleware('rol:ADMIN');
    
Route::get('/forgot-password', function () {
return view('auth.auth-reset-password');
})->name('password.request');

Route::get('/personas', [PersonaController::class, 'index'])
        ->middleware('rol:ADMIN');


Route::middleware(['rol:ADMIN'])->group(function () {

    Route::get('/usuarios', [UsuarioController::class, 'index'])
        ->name('usuarios.index');

    Route::get('/usuarios/{id}/editar', [UsuarioController::class, 'edit'])
        ->name('usuarios.edit');

    Route::put('/usuarios/{id}', [UsuarioController::class, 'update'])
        ->name('usuarios.update');

    Route::delete('/usuarios/{id}', [UsuarioController::class, 'destroy'])
        ->name('usuarios.destroy');
    
    Route::get('/usuarios/crear', [UsuarioController::class, 'create'])
    ->name('usuarios.create');

});

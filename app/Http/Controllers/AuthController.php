<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;



class AuthController extends Controller {

    public function showLogin() {
        return view('auth.login');
    }

    public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    // Llamar al Stored Procedure
    $result = DB::select(
        'CALL sp_login_persona(?)',
        [$request->email]
    );

    // El SP devuelve un array
    if (empty($result)) {
        return back()->withErrors(['login' => 'Usuario no encontrado o inactivo']);
    }

    $persona = $result[0];

    // Verificar contraseña (bcrypt)
    if (!Hash::check($request->password, $persona->contrasena)) {
        return back()->withErrors(['login' => 'Contraseña incorrecta']);
    }

    // Crear sesión (alineado a tu modelo)
    session([
        'id_persona'   => $persona->id_persona,
        'nombre'       => $persona->nombre,
        'apellido1'    => $persona->apellido1,
        'apellido2'    => $persona->apellido2,
        'rol'          => $persona->rol_nombre,
        'logueado'     => true,
    ]);

    return redirect('/dashboard');
}

public function dashboard()
{
    if (!session()->get('logueado')) {
        return redirect('/login');
    }

    return view('dashboard');
}

public function logout()
{
    session()->flush();
    return redirect('/login');
}
}

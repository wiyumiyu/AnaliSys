<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|string',
            'password' => 'required'
        ]);

        /* ===============================
         * NORMALIZAR CORREO
         * =============================== */
        $correo = strtolower(trim($request->email));

        // Si no tiene @, asumir dominio UCR
        if (!str_contains($correo, '@')) {
            $correo .= '@ucr.ac.cr';
        }

        /* ===============================
         * LOGIN POR CORREO PRINCIPAL
         * =============================== */
        $result = DB::select(
            'CALL sp_login_persona(?)',
            [$correo]
        );

        if (empty($result)) {
            return back()->withErrors([
                'login' => 'Usuario no encontrado, correo no principal o inactivo'
            ])->withInput();
        }

        $persona = $result[0];

        if (!Hash::check($request->password, $persona->contrasena)) {
            return back()->withErrors([
                'login' => 'Contraseña incorrecta'
            ])->withInput();
        }

        session([
            'id_persona' => $persona->id_persona,
            'nombre'     => $persona->nombre,
            'apellido1'  => $persona->apellido1,
            'apellido2'  => $persona->apellido2,
            'rol'        => $persona->rol_nombre,
            'correo'     => $correo,
            'logueado'   => true,
        ]);

        return redirect('/dashboard');
    }

    public function dashboard()
    {
        if (!session('logueado')) {
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

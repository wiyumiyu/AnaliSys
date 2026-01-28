<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Mail\ResetPasswordMail;

class ForgotPasswordController extends Controller
{
    public function showForm()
    {
        return view('auth.forgot-password');
    }

    public function sendEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        // 1️⃣ Obtener usuario desde TU SP
        $result = DB::select(
            'CALL sp_validar_correo_principal(?)',
            [$request->email]
        );

        if (empty($result)) {
            return back()->withErrors([
                'email' => 'El correo no está registrado o no es el principal'
            ]);
        }

        $persona = $result[0];


        // 2️⃣ Generar token seguro
        $token = Str::random(64);
        $tokenHash = hash('sha256', $token);

        // 3️⃣ Eliminar tokens previos
        DB::table('tbl_password_resets')
            ->where('id_persona', $persona->id_persona)
            ->delete();

        // 4️⃣ Guardar token
        DB::table('tbl_password_resets')->insert([
            'id_persona' => $persona->id_persona,
            'token_hash' => $tokenHash,
            'expires_at' => Carbon::now()->addMinutes(30),
        ]);

        // 5️⃣ Enviar correo REAL
        Mail::to($request->email)->send(
            new ResetPasswordMail($token)
        );


        return back()->with('status', 'Se envió el correo de recuperación');
    }
}

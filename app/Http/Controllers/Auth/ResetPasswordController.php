<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class ResetPasswordController extends Controller
{
    /**
     * Mostrar formulario de cambio de contraseña
     */
    public function showResetForm($token)
    {
        $tokenHash = hash('sha256', $token);

        $record = DB::table('tbl_password_resets')
            ->where('token_hash', $tokenHash)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (!$record) {
            abort(403, 'Token inválido o expirado');
        }

        return view('auth.reset-password', [
            'token' => $token
        ]);
    }

    /**
     * Procesar nueva contraseña
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'password' => 'required|confirmed|min:8',
        ]);

        $tokenHash = hash('sha256', $request->token);

        $record = DB::table('tbl_password_resets')
            ->where('token_hash', $tokenHash)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (!$record) {
            return back()->withErrors([
                'password' => 'Token inválido o expirado'
            ]);
        }

        // 🔐 Actualizar contraseña usando tu SP
        DB::table('tbl_persona')
        ->where('id_persona', $record->id_persona)
        ->update([
            'contrasena' => Hash::make($request->password),
            'actualizado_en' => now(),
        ]);

        // 🧹 Eliminar token
        DB::table('tbl_password_resets')
            ->where('id_persona', $record->id_persona)
            ->delete();

        return redirect('/login')
            ->with('status', 'Contraseña actualizada correctamente');
    }
}

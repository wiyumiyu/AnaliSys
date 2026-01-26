<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsuarioController extends Controller {

    /**
     * Listado de usuarios
     */
    public function index() {
        $users = DB::select('CALL sp_listado_usuarios()');

        return view('usuarios.index', [
            'users' => $users
        ]);
    }

    /**
     * Soft delete (inactivar usuario)
     */
    public function destroy($id) {
        DB::statement('CALL sp_eliminar_persona(?)', [$id]);

        return redirect()
                        ->route('usuarios.index')
                        ->with('success', 'Usuario eliminado correctamente');
    }

    public function create() {
        return view('usuarios.create');
    }

    public function edit($id) {
        // 1️⃣ Usuario
        $usuario = collect(
                DB::select('CALL sp_obtener_persona(?)', [$id])
                )->first();

        if (!$usuario) {
            abort(404);
        }

        // 2️⃣ Correos
        $correos = DB::select('CALL sp_listar_correos_persona(?)', [$id]);

        // 3️⃣ Teléfonos
        $telefonos = DB::select('CALL sp_listar_telefonos_persona(?)', [$id]);

        // 4️⃣ Tipos de teléfono
        $tiposTelefono = DB::select('CALL sp_listar_tipos_telefono()');

        // 5️⃣ Roles
        $roles = DB::select('CALL sp_listar_roles()');

        // 6️⃣ Rol actual
        $rolActual = collect(
                DB::select('CALL sp_obtener_roles_persona(?)', [$id])
                )->first();

        $rolActualId = $rolActual->id ?? null;

        // 7️⃣ Desde perfil (opcional)
        $fromPerfil = request()->query('from') === 'perfil';

        $passwordError = null;

        // 8️⃣ Enviar TODO a la vista
        return view('usuarios.edit', compact(
                        'usuario',
                        'correos',
                        'telefonos',
                        'tiposTelefono',
                        'roles',
                        'rolActualId',
                        'fromPerfil',
                        'passwordError' // 👈 IMPORTANTE
                ));
    }
}

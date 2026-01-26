<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsuarioController extends Controller
{
    /* ===============================
     * LISTADO
     * =============================== */
    public function index()
    {
        $users = DB::select('CALL sp_listado_usuarios()');
        return view('usuarios.index', compact('users'));
    }

    /* ===============================
     * EDITAR (GET)
     * =============================== */
    public function edit(Request $request, $id)
    {
        $fromPerfil = $request->query('from') === 'perfil';

        $usuario = collect(
            DB::select('CALL sp_obtener_persona(?)', [$id])
        )->first();

        if (!$usuario) {
            abort(404);
        }

        $correos        = DB::select('CALL sp_listar_correos_persona(?)', [$id]);
        $telefonos      = DB::select('CALL sp_listar_telefonos_persona(?)', [$id]);
        $tiposTelefono  = DB::select('CALL sp_listar_tipos_telefono()');
        $roles          = DB::select('CALL sp_listar_roles()');

        $rolActual = collect(
            DB::select('CALL sp_obtener_roles_persona(?)', [$id])
        )->first();

        $rolActualId = $rolActual->id ?? null;

        return view('usuarios.edit', compact(
            'usuario',
            'correos',
            'telefonos',
            'tiposTelefono',
            'roles',
            'rolActualId',
            'fromPerfil'
        ));
    }

    /* ===============================
     * EDITAR (POST)
     * =============================== */
    public function update(Request $request, $id)
    {
        /* DATOS GENERALES */
        DB::statement('CALL sp_editar_persona(?, ?, ?, ?, ?, ?, ?, ?)', [
            $id,
            $request->nombre,
            $request->apellido1,
            $request->apellido2,
            0,
            $request->cedula,
            '1990-01-01',
            ''
        ]);

        DB::statement('CALL sp_actualizar_estado_persona(?, ?)', [
            $id,
            $request->estado
        ]);

        /* ROL */
        if ($request->filled('rol_id')) {
            DB::statement('CALL sp_actualizar_rol_persona(?, ?)', [
                $id,
                $request->rol_id
            ]);
        }

        /* CORREOS */
        if ($request->nuevo_correo) {
            foreach ($request->nuevo_correo as $i => $correo) {
                DB::statement('CALL sp_agregar_persona_correo(?, ?, ?)', [
                    $id,
                    $correo,
                    $request->correo_desc[$i]
                ]);
            }
        }

        /* TELÉFONOS */
        if ($request->nuevo_telefono) {
            foreach ($request->nuevo_telefono as $i => $telefono) {
                DB::statement('CALL sp_agregar_persona_telefono(?, ?, ?)', [
                    $id,
                    $request->telefono_tipo[$i],
                    $telefono
                ]);
            }
        }

        return redirect()
            ->route('usuarios.edit', $id)
            ->with('success', 'Usuario actualizado correctamente');
    }
public function create()
{
    return view('usuarios.create');
}
    /* ===============================
     * ELIMINAR USUARIO
     * =============================== */
    public function destroy($id)
    {
        DB::statement('CALL sp_eliminar_persona(?)', [$id]);

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuario eliminado');
    }
}

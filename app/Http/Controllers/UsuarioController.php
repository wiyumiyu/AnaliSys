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
        $esPerfilPropio = session('id_persona') == $id;

        $usuario = collect(
            DB::select('CALL sp_obtener_persona(?)', [$id])
        )->first();

        if (!$usuario) {
            abort(404);
        }

        $correos = DB::select('CALL sp_listar_correos_persona(?)', [$id]);
        $telefonos = DB::select('CALL sp_listar_telefonos_persona(?)', [$id]);
        $tiposTelefono = DB::select('CALL sp_listar_tipos_telefono()');
        $roles = DB::select('CALL sp_listar_roles()');

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
            'esPerfilPropio'
        ));
    }

    /* ===============================
     * EDITAR (POST)
     * =============================== */
    public function update(Request $request, $id)
    {
        /* 🔐 CONTEXTO BITÁCORA */
        DB::statement('SET @usuario = ?', [session('id_persona')]);
        DB::statement('SET @ip = ?', [$request->ip()]);
        DB::statement('SET @operacion_id = UUID()');

        $esPerfilPropio = session('id_persona') == $id;

        /* ===============================
         * ELIMINAR CORREO (AJAX)
         * =============================== */
if ($request->has('del_correo')) {

    DB::statement('CALL sp_eliminar_persona_correo(?)', [
        $request->del_correo
    ]);

    // 🔒 FORZAR SNAPSHOT
    DB::statement(
        'UPDATE tbl_persona SET actualizado_en = CURRENT_TIMESTAMP WHERE id_persona = ?',
        [$id]
    );

    return response()->noContent();
}


        /* ===============================
         * ELIMINAR TELÉFONO (AJAX)
         * =============================== */
        if ($request->has('del_tel')) {

    DB::statement('CALL sp_eliminar_persona_telefono(?)', [
        $request->del_tel
    ]);

    // 🔒 FORZAR SNAPSHOT
    DB::statement(
        'UPDATE tbl_persona SET actualizado_en = CURRENT_TIMESTAMP WHERE id_persona = ?',
        [$id]
    );

    return response()->noContent();
}


        /* ===============================
         * DATOS GENERALES
         * =============================== */
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

        /* ===============================
         * ROL
         * =============================== */
        if (!$esPerfilPropio && $request->filled('rol_id')) {
            DB::statement('CALL sp_actualizar_rol_persona(?, ?)', [
                $id,
                $request->rol_id
            ]);
        }

        /* ===============================
         * NUEVOS CORREOS
         * =============================== */
        if ($request->nuevo_correo) {
            foreach ($request->nuevo_correo as $i => $correo) {
                DB::statement('CALL sp_agregar_persona_correo(?, ?, ?)', [
                    $id,
                    $correo,
                    $request->correo_desc[$i]
                ]);
            }
        }

        /* ===============================
         * EDITAR CORREOS EXISTENTES
         * =============================== */
        if ($request->correo_existente) {
            foreach ($request->correo_existente as $idCorreo => $valor) {
                DB::statement('CALL sp_editar_persona_correo(?, ?, ?)', [
                    $idCorreo,
                    $request->correo_tipo_existente[$idCorreo],
                    $valor
                ]);
            }
        }

        /* ===============================
         * NUEVOS TELÉFONOS
         * =============================== */
        if ($request->nuevo_telefono) {
            foreach ($request->nuevo_telefono as $i => $telefono) {
                DB::statement('CALL sp_agregar_persona_telefono(?, ?, ?)', [
                    $id,
                    $request->telefono_tipo[$i],
                    $telefono
                ]);
            }
        }

        /* ===============================
         * EDITAR TELÉFONOS EXISTENTES
         * =============================== */
        if ($request->telefono_existente) {
            foreach ($request->telefono_existente as $idTel => $valor) {
                DB::statement('CALL sp_editar_persona_telefono(?, ?, ?)', [
                    $idTel,
                    $request->telefono_tipo_existente[$idTel],
                    $valor
                ]);
            }
        }

        /* ===============================
         * CAMBIO DE CONTRASEÑA
         * =============================== */
        if (
            $esPerfilPropio &&
            $request->filled(['password_actual', 'password_nueva', 'password_confirmar'])
        ) {
            if ($request->password_nueva !== $request->password_confirmar) {
                return back()->withErrors(['password' => 'Las contraseñas no coinciden.']);
            }

            $actual = collect(
                DB::select('CALL sp_obtener_contrasena_persona(?)', [$id])
            )->first();

            if (!$actual || !password_verify($request->password_actual, $actual->contrasena)) {
                return back()->withErrors(['password' => 'Contraseña actual incorrecta.']);
            }

            $hash = password_hash($request->password_nueva, PASSWORD_DEFAULT);
            DB::statement('CALL sp_actualizar_contrasena(?, ?)', [$id, $hash]);
        }

        /* ===============================
         * 🔒 COMMIT LÓGICO (BITÁCORA)
         * =============================== */
        DB::statement(
            'UPDATE tbl_persona SET actualizado_en = CURRENT_TIMESTAMP WHERE id_persona = ?',
            [$id]
        );

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuario actualizado correctamente');
    }

    /* ===============================
     * CREAR (GET)
     * =============================== */
    public function create()
    {
        $roles = DB::select('CALL sp_listar_roles()');
        $tiposTelefono = DB::select('CALL sp_listar_tipos_telefono()');

        return view('usuarios.create', compact('roles', 'tiposTelefono'));
    }

    /* ===============================
     * CREAR (POST)
     * =============================== */
    public function store(Request $request)
    {
        DB::statement('SET @usuario = ?', [session('id_persona')]);
        DB::statement('SET @ip = ?', [$request->ip()]);
        DB::statement('SET @operacion_id = UUID()');

        $request->validate([
            'nombre' => 'required',
            'apellido1' => 'required',
            'apellido2' => 'required',
            'cedula' => 'required',
            'rol_id' => 'required',
            'password_nueva' => 'required',
            'password_confirmar' => 'required'
        ]);

        if ($request->password_nueva !== $request->password_confirmar) {
            return back()->withErrors(['password' => 'Las contraseñas no coinciden.']);
        }

        $hash = password_hash($request->password_nueva, PASSWORD_DEFAULT);

        $result = DB::select('CALL sp_crear_persona(?, ?, ?, ?, ?, ?, ?, ?)', [
            $request->nombre,
            $request->apellido1,
            $request->apellido2,
            0,
            $request->cedula,
            '1990-01-01',
            $hash,
            ''
        ]);

        $id_persona = $result[0]->id_persona;

        DB::statement('CALL sp_asignar_rol_persona(?, ?)', [
            $id_persona,
            $request->rol_id
        ]);

        DB::statement(
            'UPDATE tbl_persona SET actualizado_en = CURRENT_TIMESTAMP WHERE id_persona = ?',
            [$id_persona]
        );

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuario creado correctamente');
    }

    /* ===============================
     * CAMBIAR ESTADO
     * =============================== */
    public function cambiarEstado(Request $request, $id)
    {
        DB::statement('SET @usuario = ?', [session('id_persona')]);
        DB::statement('SET @ip = ?', [$request->ip()]);
        DB::statement('SET @operacion_id = UUID()');

        $usuario = collect(
            DB::select('CALL sp_obtener_persona(?)', [$id])
        )->first();

        if (!$usuario) {
            abort(404);
        }

        $nuevoEstado = $usuario->id_estado == 1 ? 0 : 1;

        DB::statement('CALL sp_actualizar_estado_persona(?, ?)', [
            $id,
            $nuevoEstado
        ]);

        return redirect()
            ->route('usuarios.index')
            ->with(
                'success',
                $nuevoEstado == 1
                    ? 'Usuario activado correctamente.'
                    : 'Usuario inactivado correctamente.'
            );
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsuarioController extends Controller {
    /* ===============================
     * LISTADO
     * =============================== */

    public function index() {
        $users = DB::select('CALL sp_listado_usuarios()');
        return view('usuarios.index', compact('users'));
    }

    /* ===============================
     * EDITAR (GET)
     * =============================== */

    public function edit(Request $request, $id) {
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

    public function update(Request $request, $id) {

        $esPerfilPropio = session('id_persona') == $id;

        /* ===============================
         * ELIMINAR CORREO
         * =============================== */
        if ($request->has('del_correo')) {
            DB::statement('CALL sp_eliminar_persona_correo(?)', [
                $request->del_correo
            ]);

            return response()->noContent(); // <- para fetch()
        }


        /* ===============================
         * ELIMINAR TELÉFONO
         * =============================== */
        if ($request->has('del_tel')) {
            DB::statement('CALL sp_eliminar_persona_telefono(?)', [
                $request->del_tel
            ]);

            return response()->noContent(); // <- para fetch()
        }

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
        if (!$esPerfilPropio && $request->filled('rol_id')) {
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


        /* ===============================
         * CORREOS EXISTENTES (EDICIÓN)
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

        /* ===============================
         * TELÉFONOS EXISTENTES (EDICIÓN)
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
         * CAMBIO DE CONTRASEÑA (SOLO PERFIL PROPIO)
         * =============================== */
        $esPerfilPropio = session('id_persona') == $id;

        if ($esPerfilPropio && $request->filled([
                    'password_actual',
                    'password_nueva',
                    'password_confirmar'
                ])) {

            if ($request->password_nueva !== $request->password_confirmar) {
                return back()->withErrors([
                            'password' => 'Las contraseñas nuevas no coinciden.'
                ]);
            }

            $actual = collect(
                    DB::select('CALL sp_obtener_contrasena_persona(?)', [$id])
                    )->first();

            if (!$actual || !password_verify($request->password_actual, $actual->contrasena)) {
                return back()->withErrors([
                            'password' => 'La contraseña actual es incorrecta.'
                ]);
            }

            $regex = '/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/';
            if (!preg_match($regex, $request->password_nueva)) {
                return back()->withErrors([
                            'password' => 'La contraseña debe tener mínimo 8 caracteres, una mayúscula, un número y un símbolo.'
                ]);
            }

            $hash = password_hash($request->password_nueva, PASSWORD_DEFAULT);

            DB::statement('CALL sp_actualizar_contrasena(?, ?)', [$id, $hash]);
        }


        $rolUsuario = session('rol'); // ADMIN | ANALISTA

        if ($esPerfilPropio) {

            if ($rolUsuario === 'ANALISTA') {
                return redirect()
                                ->route('dashboard')
                                ->with('success', 'Perfil actualizado correctamente');
            }

            // ADMIN editando su propio perfil
            return redirect()
                            ->route('usuarios.index')
                            ->with('success', 'Perfil actualizado correctamente');
        }

// Admin editando a otro usuario
        return redirect()
                        ->route('usuarios.index')
                        ->with('success', 'Usuario actualizado correctamente');
    }

    public function create() {
        $roles = DB::select('CALL sp_listar_roles()');
        $tiposTelefono = DB::select('CALL sp_listar_tipos_telefono()');

        return view('usuarios.create', compact(
                        'roles',
                        'tiposTelefono'
                ));
    }

    public function store(Request $request) {
        /* ===============================
         * VALIDACIONES BÁSICAS
         * =============================== */
        $request->validate([
            'nombre' => 'required|string',
            'apellido1' => 'required|string',
            'apellido2' => 'required|string',
            'cedula' => 'required|string',
            'rol_id' => 'required',
            'password_nueva' => 'required',
            'password_confirmar' => 'required'
        ]);

        if ($request->password_nueva !== $request->password_confirmar) {
            return back()->withErrors([
                        'password' => 'Las contraseñas no coinciden.'
                    ])->withInput();
        }

        $regex = '/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/';
        if (!preg_match($regex, $request->password_nueva)) {
            return back()->withErrors([
                        'password' => 'La contraseña debe tener mínimo 8 caracteres, una mayúscula, un número y un símbolo.'
                    ])->withInput();
        }

        $hash = password_hash($request->password_nueva, PASSWORD_DEFAULT);

        try {

            /* ===============================
             * CREAR PERSONA
             * =============================== */
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

            /* ===============================
             * ASIGNAR ROL
             * =============================== */
            DB::statement('CALL sp_asignar_rol_persona(?, ?)', [
                $id_persona,
                $request->rol_id
            ]);

            /* ===============================
             * CORREOS
             * =============================== */
            if ($request->nuevo_correo) {
                foreach ($request->nuevo_correo as $i => $correo) {
                    DB::statement('CALL sp_agregar_persona_correo(?, ?, ?)', [
                        $id_persona,
                        $correo,
                        $request->correo_desc[$i]
                    ]);
                }
            }

            /* ===============================
             * TELÉFONOS
             * =============================== */
            if ($request->nuevo_telefono) {
                foreach ($request->nuevo_telefono as $i => $telefono) {
                    DB::statement('CALL sp_agregar_persona_telefono(?, ?, ?)', [
                        $id_persona,
                        $request->telefono_tipo[$i],
                        $telefono
                    ]);
                }
            }

            return redirect()
                            ->route('usuarios.index')
                            ->with('success', 'Usuario creado correctamente');
        } catch (\Illuminate\Database\QueryException $e) {

            // Cédula duplicada
            if ($e->getCode() === '23000') {
                return back()->withErrors([
                            'cedula' => 'Ya existe un usuario registrado con esa cédula.'
                        ])->withInput();
            }

            throw $e;
        }
    }

    /* ===============================
     * ELIMINAR USUARIO
     * =============================== */

//    public function destroy($id) {
//        DB::statement('CALL sp_eliminar_persona(?)', [$id]);
//
//        return redirect()
//                        ->route('usuarios.index')
//                        ->with('success', 'Usuario inactivado correctamente');
//    }

    public function cambiarEstado($id)
    {
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

} // 👈 ESTA LLAVE CIERRA LA CLASE



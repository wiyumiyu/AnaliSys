<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsuarioController extends Controller
{
    /**
     * Listado de usuarios
     */
    public function index()
    {
        $users = DB::select('CALL sp_listado_usuarios()');

        return view('usuarios.index', [
            'users' => $users
        ]);
    }

    /**
     * Soft delete (inactivar usuario)
     */
    public function destroy($id)
    {
        DB::statement('CALL sp_eliminar_persona(?)', [$id]);

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuario eliminado correctamente');
    }
    
    public function create()
{
    return view('usuarios.create');
}

public function edit($id)
{
    // por ahora solo mostramos placeholder
    return view('usuarios.edit', [
        'id' => $id
    ]);
}

}

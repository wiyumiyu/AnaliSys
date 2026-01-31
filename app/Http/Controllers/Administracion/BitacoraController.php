<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class BitacoraController extends Controller
{
    /* ===============================
     * LISTADO
     * =============================== */
    public function index()
    {
        $bitacoras = DB::select('CALL sp_listar_bitacora()');

        return view('bitacora.index', compact('bitacoras'));
    }
    
    public function show($id)
    {
        $data = DB::select(
            'CALL sp_obtener_bitacora_completa(?)',
            [$id]
        );

        if (empty($data)) {
            return response()->json(['error' => 'No encontrado'], 404);
        }

        return response()->json($data[0]);
    }

}

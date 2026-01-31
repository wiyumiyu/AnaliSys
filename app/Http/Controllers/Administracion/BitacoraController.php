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
}

<?php

namespace App\Http\Controllers\ReportesClientes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteClienteController extends Controller
{
    public function index(Request $request)
    {
        $periodo   = $request->get('periodo', date('Y'));
        $estadoRep = $request->get('estado', 0);
        $buscar    = $request->get('buscar', '');

        $solicitudes = DB::select(
            'CALL sp_listar_reportes_clientes(?, ?, ?)',
            [$periodo, $estadoRep, $buscar]
        );

        return view('reportes_clientes.index', compact(
            'solicitudes',
            'periodo',
            'estadoRep',
            'buscar'
        ));
    }

    public function show($id)
    {
        $resultado = DB::select(
            'CALL sp_obtener_reporte_cliente(?)',
            [$id]
        );

        $solicitud = $resultado[0] ?? null;

        return view('reportes_clientes.show', compact('solicitud'));
    }
}
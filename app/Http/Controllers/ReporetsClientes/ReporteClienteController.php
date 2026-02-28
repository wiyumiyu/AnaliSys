<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteClienteController extends Controller
{
    public function index(Request $request)
    {
        $periodo = $request->get('periodo', date('Y'));
        $impresa = $request->get('impresa', 0);

        $solicitudes = DB::table('tbm_solicitud as s')
            ->join('tbm_cliente as c', 'c.id_cliente', '=', 's.id_cliente')
            ->select(
                's.id_solicitud',
                's.numero',
                's.fecha',
                's.estado',
                'c.nombre'
            )
            ->whereYear('s.fecha', $periodo)
            ->where('s.entrega', $impresa)
            ->orderBy('s.fecha', 'desc')
            ->get();

        return view('reportes_clientes.index', compact(
            'solicitudes',
            'periodo',
            'impresa'
        ));
    }

    public function show($id)
    {
        $solicitud = DB::table('tbm_solicitud as s')
            ->join('tbm_cliente as c', 'c.id_cliente', '=', 's.id_cliente')
            ->where('s.id_solicitud', $id)
            ->select('s.*', 'c.nombre')
            ->first();

        return view('reportes_clientes.show', compact('solicitud'));
    }
}

?>

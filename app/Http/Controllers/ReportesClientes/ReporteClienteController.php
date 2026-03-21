<?php

namespace App\Http\Controllers\ReportesClientes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\Calculos\Textura;

class ReporteClienteController extends Controller {

    public function index(Request $request) {
        $periodo = $request->get('periodo');

        if (!$periodo) {
            $periodo = DB::table('tbm_solicitud')
                    ->selectRaw('MAX(YEAR(fecha)) as anio')
                    ->value('anio');
        }

        $estadoRep = $request->get('estado', 0);
        $buscar = $request->get('buscar', '');

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

    public function show($id) {
        
        // ENCABEZADO //
        /*------------------------------------------------*/
        $encabezado = DB::select(
                'CALL sp_reporte_cliente_encabezado(?)',
                [$id]
        );
        /*------------------------------------------------*/
        // ID USUARIO Y IDLAB //
        /*------------------------------------------------*/
        $datos = DB::select(
                'CALL sp_obtener_reporte_cliente(?)',
                [$id]
        );
        /*------------------------------------------------*/

        
        /*------------------------------------------------*/
        // TEXTURA //
        /*------------------------------------------------*/
        $textura = DB::select(
                'CALL sp_reporte_cliente_textura(?)',
                [$id]
        );
      
        $texturas = Textura::calcularTexturas($textura);
        
        /*------------------------------------------------*/
        // DENSIDAD APARENTE //
        /*------------------------------------------------*/
        
        
        /*------------------------------------------------*/
        // DENSIDAD PARTICULAS //
        /*------------------------------------------------*/
        
        
        /*------------------------------------------------*/
        // POROSIDAD //
        /*------------------------------------------------*/
        
        
        
        return view('reportes_clientes.vista', [
            'encabezado' => $encabezado[0] ?? null,
            'datos' => $datos,
            'texturas' => $texturas
        ]);
    }
}

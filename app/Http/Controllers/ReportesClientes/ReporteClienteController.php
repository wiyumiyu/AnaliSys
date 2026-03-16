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

        $encabezado = DB::select(
                'CALL sp_reporte_cliente_encabezado(?)',
                [$id]
        );

        $datos = DB::select(
                'CALL sp_obtener_reporte_cliente(?)',
                [$id]
        );

        $textura = DB::select(
                'CALL sp_reporte_cliente_textura(?)',
                [$id]
        );

        $texturas = [];
        $blancos = [];

        /* detectar blancos */
        foreach ($textura as $t) {

            $idlab = strtoupper($t->idlab);

            if (
                    $idlab == 'BLANCO' ||
                    $idlab == 'BLK' ||
                    $idlab == 'BLANK' ||
                    $idlab == 'CBL' ||
                    $idlab == 'C-BL' ||
                    $idlab == 'BL'
            ) {
                $blancos[$t->id_textura] = $t;
            }
        }
//dd($textura);
        /* calcular muestras */
        foreach ($textura as $m) {

            if (str_contains(strtoupper($m->idlab), 'BL')) {
                continue;
            }

            $blanco = $blancos[$m->id_textura] ?? null;

            if (!$blanco) {
                continue;
            }

            $R = [$m->R1, $m->R2, $m->R3, $m->R4];

            $RL = [
                $blanco->R1,
                $blanco->R2,
                $blanco->R3,
                $blanco->R4
            ];

            $Temp = [$m->TEMP1, $m->TEMP2, $m->TEMP3, $m->TEMP4];

            $Tiempo = [
                $m->TIEMPO1,
                $m->TIEMPO2,
                $m->TIEMPO3,
                $m->TIEMPO4
            ];

            $resultado = Textura::calcular_textura_suelo(
                    DB::connection()->getPdo(),
                    $m->peso_seco,
                    $R,
                    $RL,
                    $Temp,
                    $Tiempo
            );

            $texturas[$m->idlab] = $resultado;
        }
//dd($texturas);
        return view('reportes_clientes.vista', [
            'encabezado' => $encabezado[0] ?? null,
            'datos' => $datos,
            'texturas' => $texturas
        ]);
    }
}

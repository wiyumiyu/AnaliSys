<?php

namespace App\Http\Controllers\ReportesClientes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\Calculos\Textura;
use App\Helpers\Calculos\DensidadAparente;
use App\Helpers\Calculos\DensidadParticulas;
use App\Helpers\Calculos\HumedadGravimetrica;
use App\Helpers\Calculos\conductividadHidraulica;

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
        /* ------------------------------------------------ */
        $encabezado = DB::select(
                'CALL sp_reporte_cliente_encabezado(?)',
                [$id]
        );

        /* ------------------------------------------------ */
        // ID USUARIO Y IDLAB //
        /* ------------------------------------------------ */
        $datos = DB::select(
                'CALL sp_obtener_reporte_cliente(?)',
                [$id]
        );
        /* ------------------------------------------------ */


        /* ------------------------------------------------ */
        // TEXTURA //
        /* ------------------------------------------------ */
        $textura = DB::select(
                'CALL sp_reporte_cliente_textura(?)',
                [$id]
        );

        $texturas = Textura::calcularTexturas($textura);

        /* ------------------------------------------------ */
        // DENSIDAD APARENTE //
        /* ------------------------------------------------ */

        $densidadAparente = DB::select(
                'CALL sp_reporte_cliente_densidad_aparente(?)',
                [$id]
        );

        //dd($densidadAparente);
        $densidades = [];

        foreach ($densidadAparente as $m) {
           
            $da = DensidadAparente::calcular_densidad(
                    $m->altura_cilindro,
                    $m->diametro_cilindro,
                    $m->peso_seco,
                    $m->peso_cilindro
            );
            //dd($da);
            if ($da !== null) {
                $densidades[(string) $m->idlab] = $da;
            }
        }
           // dd($densidades);    
        /* ------------------------------------------------ */
        // DENSIDAD PARTICULAS //
        /* ------------------------------------------------ */

        $densidadParticulas = DB::select(
                'CALL sp_reporte_cliente_densidad_particulas(?)',
                [$id]
        );

        //dd($densidadParticulas);

        $densidadesParticulas = [];

        foreach ($densidadParticulas as $m) {
           //dd($m);
            $dp = DensidadParticulas::calcular(
                    $m->numero_balon,
                    $m->p1,
                    $m->p2,
                    $m->p3,
                    $m->temperatura
            );
            
            if ($dp !== null) {
                $densidadesParticulas[(string) $m->idlab] = $dp;
            }
        }

        /* ------------------------------------------------ */
        // POROSIDAD //
        /* ------------------------------------------------ */


        $porosidades = [];

        foreach ($datos as $row) {

            $idlab = trim((string) $row->idlab);

            $da = $densidades[$idlab] ?? null;
            $dp = $densidadesParticulas[$idlab] ?? null;

            if ($da !== null && $dp !== null && $dp != 0) {

                $p = (1 - ($da / $dp)) * 100;

                $porosidades[$idlab] = round($p, 2);
            }
        }


        /* ------------------------------------------------ */
        // HUMEDAD GRAVIMETRICA //
        /* ------------------------------------------------ */

        $humedadGravimetrica = DB::select(
                'CALL sp_reporte_cliente_humedad_gravimetrica(?)',
                [$id]
        );

        //dd($humedadGravimetrica);

        $humedades = [];

        foreach ($humedadGravimetrica as $m) {

            $hg = HumedadGravimetrica::calcular(
                    $m->pc,
                    $m->ph,
                    $m->ps
            );

            if ($hg !== null) {
                $humedades[(string) $m->idlab] = $hg;
                //$humedades[trim((string)$m->idlab)] = $hg;
            }
        }
        
        
        /* ------------------------------------------------ */
        // CONDUCTIVIDAD HIDRAULICA //
        /* ------------------------------------------------ */

        /* ------------------------------------------------ */
        // GRANULOMETRIA //
        /* ------------------------------------------------ */
       /* $granulometrias = [];

        foreach ($datos as $row) {

            $idlab = trim((string) $row->idlab);

            $da = $densidades[$idlab] ?? null;
            $dp = $densidadesParticulas[$idlab] ?? null;

            if ($da !== null && $dp !== null && $dp != 0) {

                $p = (1 - ($da / $dp)) * 100;

                $granulometrias[$idlab] = round($p, 2);
            }
        }*/

        $conductividadHidraulica = DB::select(
                'CALL sp_reporte_cliente_conductividad_hidraulica(?)',
                [$id]
        ); 
        
        $conductividades = conductividadHidraulica::calcularConductividades($conductividadHidraulica);

        return view('reportes_clientes.vista', [
            'encabezado' => $encabezado[0] ?? null,
            'datos' => $datos,
            'texturas' => $texturas,
            'densidades' => $densidades,
            'densidadesParticulas' => $densidadesParticulas,
            'porosidades' => $porosidades,
            'humedades' => $humedades,
            'conductividades' => $conductividades
        ]);
    }
    
}

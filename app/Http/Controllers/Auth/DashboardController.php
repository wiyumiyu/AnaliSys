<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $anio = date('Y');

        /*
        |--------------------------------------------------------------------------
        | REPORTES DE CLIENTE GENERADOS EN EL AÑO
        |--------------------------------------------------------------------------
        */
        $reportesAnio = DB::table('tbm_solicitud_impresa')
            ->whereYear('fecha', $anio)
            ->count();

        /*
        |--------------------------------------------------------------------------
        | SOLICITUDES PENDIENTES DE REPORTE
        | id_laboratorio = 9
        | estado > 1
        | No tienen registro en tbm_solicitud_impresa
        |--------------------------------------------------------------------------
        */
        $pendientes = DB::table('tbm_solicitud as s')
            ->leftJoin('tbm_solicitud_impresa as si', 's.id_solicitud', '=', 'si.id_solicitud')
            ->where('s.id_laboratorio', 9)
            ->where('s.estado', '>', 1)
            ->whereNull('si.id_solicitud')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | CONTROLES GENERADOS EN EL AÑO
        |--------------------------------------------------------------------------
        */
        $controlesAnio = 5;
//                DB::table('tbm_control_textura')
//            ->whereYear('fecha', $anio)
//            ->count();

        /*
        |--------------------------------------------------------------------------
        | RESULTADOS GENERADOS EN EL AÑO
        |--------------------------------------------------------------------------
        */
        $resultadosAnio = 10;
//                DB::table('tbm_resultados')
//            ->whereYear('fecha', $anio)
//            ->count();

        /*
        |--------------------------------------------------------------------------
        | ARCHIVOS GENERADOS POR MÓDULO EN EL AÑO
        |--------------------------------------------------------------------------
        */
        $textura = DB::table('trn_textura')
            ->whereYear('fecha', $anio)
            ->count();

        $densidadAparente = DB::table('trn_densidad_aparente')
            ->whereYear('fecha', $anio)
            ->count();

        $densidadParticulas = DB::table('trn_densidad_particulas')
            ->whereYear('fecha', $anio)
            ->count();



        $humedad = DB::table('trn_humedad_gravimetrica')
            ->whereYear('fecha', $anio)
            ->count();

        $retencion = DB::table('trn_retencion_humedad')
            ->whereYear('fecha', $anio)
            ->count();
        $curvatura = 5;

//        $curvatura = DB::table('trn_curvatura_retencion')
//            ->whereYear('fecha', $anio)
//            ->count();

        $granulometria = DB::table('trn_granulometria')
            ->whereYear('fecha', $anio)
            ->count();

        $estabilidad = DB::table('trn_estabilidad_agregados')
            ->whereYear('fecha', $anio)
            ->count();

        $coel = DB::table('trn_coeficiente_extensibilidad')
            ->whereYear('fecha', $anio)
            ->count();

        /*
        |--------------------------------------------------------------------------
        | TIEMPO ENTRE SOLICITUD Y REPORTE (EN DÍAS)
        |--------------------------------------------------------------------------
        */
        $tiempos = DB::table('tbm_solicitud as s')
            ->join('tbm_solicitud_impresa as si', 's.id_solicitud', '=', 'si.id_solicitud')
            ->whereYear('si.fecha', $anio)
            ->selectRaw('
                AVG(DATEDIFF(si.fecha, s.fecha)) as promedio,
                MIN(DATEDIFF(si.fecha, s.fecha)) as mas_rapido,
                MAX(DATEDIFF(si.fecha, s.fecha)) as mas_lento
            ')
            ->first();

        /*
        |--------------------------------------------------------------------------
        | PROTEGER CONTRA NULL (si no hay datos aún)
        |--------------------------------------------------------------------------
        */
        $promedio = $tiempos->promedio ?? 0;
        $masRapido = $tiempos->mas_rapido ?? 0;
        $masLento = $tiempos->mas_lento ?? 0;

        return view('dashboard', [
            'anio' => $anio,
            'reportesAnio' => $reportesAnio,
            'pendientes' => $pendientes,
            'controlesAnio' => $controlesAnio,
            'resultadosAnio' => $resultadosAnio,

            'textura' => $textura,
            'densidadAparente' => $densidadAparente,
            'densidadParticulas' => $densidadParticulas,
            'humedad' => $humedad,
            'retencion' => $retencion,
            'curvatura' => $curvatura,
            'granulometria' => $granulometria,
            'estabilidad' => $estabilidad,
            'coel' => $coel,

            'promedio' => round($promedio, 1),
            'masRapido' => $masRapido,
            'masLento' => $masLento,
        ]);
    }
}








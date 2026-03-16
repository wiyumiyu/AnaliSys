<?php

namespace App\Helpers\Calculos;

use Illuminate\Support\Facades\DB;

class Textura {
    /* Corrección por blanco */

    public static function correccion_blanco($R, $RL) {
        return $R - $RL;
    }

    /* Concentración inicial */

    public static function concentracion_inicial($Ms, $volumen = 1000) {
        return $Ms / $volumen;
    }

    /* Porcentaje en suspensión */

//    public static function porcentaje_suspension($Cn, $C0) {
//        return ($Cn / $C0) * 100;
//    }
public static function porcentaje_suspension($Cn, $Ms)
{
    return ($Cn / $Ms) * 100;
}
    /* Profundidad efectiva */

    public static function profundidad_efectiva($R) {
        return (0.164 * $R) + 16.3;
    }

    /* Obtener viscosidad desde tabla */

    public static function obtener_viscosidad($temperatura) {
        $row = DB::table('trn_viscosidad_temperatura')
                ->select('viscosidad_h2o_hmp_poise')
                ->orderByRaw('ABS(temperatura_c - ?)', [$temperatura])
                ->first();

        return $row->viscosidad_h2o_hmp_poise ?? 0;
    }

    public static function obtener_viscosidad_cp($temperatura) {
        $row = DB::table('trn_viscosidad_temperatura')
                ->select('viscosidad_cp')
                ->orderByRaw('ABS(temperatura_c - ?)', [$temperatura])
                ->first();

        return $row->viscosidad_cp ?? 1.0;
    }

    /* Obtener factor N */

    public static function obtener_factor_n($viscosidad_cp) {
        $row = DB::table('trn_factor_sedimentacion')
                ->select('factor_n')
                ->orderByRaw('ABS(viscosidad_cp - ?)', [$viscosidad_cp])
                ->first();

        return $row->factor_n ?? 0;
    }

    /* Diámetro equivalente de partícula */

//    public static function diametro_particula($N, $h, $t) {
//        return sqrt((30 * $N * $h) / $t);
//    }
    public static function diametro_particula($N,$h,$t)
{
    return 0.0136 * sqrt(($N * $h) / $t);
}

    /* Interpolación logarítmica */
//
//    public static function interpolacion_log($P1, $P2, $X1, $X2, $valor) {
//
//        $deltaX = $X2 - $X1;
//
//        if (abs($deltaX) < 1e-9) {
//            return 0; // o un valor seguro
//        }
//        $m = (log10($P2) - log10($P1)) / ($X2 - $X1);
//        $b = log10($P2) - ($m * $X2);
//
//        return pow(10, ($m * $valor) + $b);
//    }
//public static function interpolacion_log($P1,$P2,$X1,$X2,$valor)
//{
//    $P1 = max($P1,0.0001);
//    $P2 = max($P2,0.0001);
//
//    $logX1 = log10($X1);
//    $logX2 = log10($X2);
//
//    $m = (log10($P2) - log10($P1)) / ($logX2 - $logX1);
//    $b = log10($P2) - $m * $logX2;
//
//    return pow(10, $m * log10($valor) + $b);
//}

public static function interpolacion_log($P1,$P2,$X1,$X2,$X)
{
    $P1 = max($P1,0.0001);
    $P2 = max($P2,0.0001);

    $X1 = max($X1,0.0000001);
    $X2 = max($X2,0.0000001);

    $logX1 = log10($X1);
    $logX2 = log10($X2);

    if(abs($logX2 - $logX1) < 1e-9){
        return ($P1 + $P2)/2;
    }

    $m = ($P2 - $P1) / ($logX2 - $logX1);

    return $P1 + $m * (log10($X) - $logX1);
}
    /* FUNCIÓN PRINCIPAL */

    public static function calcular_textura_suelo(
            $conn,
            $Ms,
            $R,
            $RL,
            $Temp,
            $Tiempo
    ) {

        $result = [];

       // $C0 = self::concentracion_inicial($Ms);

        for ($i = 0; $i < 4; $i++) {

            $Cn = self::correccion_blanco($R[$i], $RL[$i]);

            //$Pn = self::porcentaje_suspension($Cn, $C0);
            $Pn = self::porcentaje_suspension($Cn, $Ms);
           // $Pn = max($Pn, 0.0001);
            $Pn = min(max($Pn,0.0001),100);
//            $deltaX = $X2 - $X1;
//
//            if (abs($deltaX) < 1e-9) {
//                return 0; // o un valor seguro
//            }
            $h = self::profundidad_efectiva($R[$i]);

            $mu = self::obtener_viscosidad_cp($Temp[$i]);

            $mu_cp = $mu * 1;

            $N = self::obtener_factor_n($mu_cp);

            $X = self::diametro_particula($N, $h, $Tiempo[$i]);

//            dd([
//    'Cn'=>$Cn,
//    'Pn'=>$Pn,
//    'h'=>$h,
//    'mu_cp'=>$mu_cp,
//    'N'=>$N,
//    
//    'X'=>$X
//]);
            
            $result[] = [
                'C' => $Cn,
                'P' => $Pn,
                'h' => $h,
                'mu' => $mu,
                'N' => $N,
                'X' => $X
            ];
        }

        $P1 = $result[0]['P'];
        $P2 = $result[1]['P'];
        $P3 = $result[2]['P'];
        $P4 = $result[3]['P'];

        $X1 = $result[0]['X'];
        $X2 = $result[1]['X'];
        $X3 = $result[2]['X'];
        $X4 = $result[3]['X'];

//        $P50 = self::interpolacion_log($P1, $P2, $X1, $X2, 50);
//        $P2 = self::interpolacion_log($P3, $P4, $X3, $X4, 2);

$P_limo_arcilla = self::interpolacion_log($P1,$P2,$X1,$X2,0.05);
$P_arcilla      = self::interpolacion_log($P3,$P4,$X3,$X4,0.002);
        
//        $arcilla = $P2;
//        $arena = 100 - $P50;
//        $limo = 100 - $arcilla - $arena;
$arcilla = $P_arcilla;
$limo    = $P_limo_arcilla - $arcilla;
$arena   = 100 - $P_limo_arcilla;


        return [
            "arcilla" => $arcilla,
            "limo" => $limo,
            "arena" => $arena
        ];
    }
}

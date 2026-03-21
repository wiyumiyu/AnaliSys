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
public static function porcentaje_suspension($Cn, $Ms) {
    return ($Cn / $Ms) * 100;
}
//    public static function porcentaje_suspension($Cn, $C0) {
//        return ($Cn / $C0) * 100;
//    }

    /* Profundidad efectiva */

    public static function profundidad_efectiva($R) {
        return (0.164 * $R) + 16.3;
    }

    /* Obtener viscosidad desde tabla */

    public static function obtener_viscosidad_cp($temperatura) {
        $result = DB::select('CALL sp_obtener_viscosidad_cp(?)', [$temperatura]);

        return $result[0]->viscosidad_cp ?? 1.0;
    }

    /* Obtener factor N */

    public static function obtener_factor_n($viscosidad_cp) {
        $result = DB::select('CALL sp_obtener_factor_n(?)', [$viscosidad_cp]);

        return $result[0]->factor_n ?? 0;
    }

    public static function obtener_densidad($temperatura) {
        $result = DB::select('CALL sp_obtener_densidad(?)', [$temperatura]);

        return [
            'agua' => $result[0]->densidad_agua ?? 0,
            'agua_hmp' => $result[0]->densidad_agua_hmp ?? 0
        ];
    }

    public static function diametro_particula($N, $h, $t, $Ps, $g, $PI) {
        //Xn =  1000(  (30*N*h) / (g(Ps-Pl)) )^0.5 * T^ (-0.5)
        $den = max($Ps - $PI, 0.0001);
        return 1000 * sqrt((30 * $N * $h) / ($g * ($den))) * pow($t, -0.5);
    }

    /* FUNCIÓN PRINCIPAL */

    public static function calcular_textura_suelo(
            $Ms, $R, $RL, $Temp, $Tiempo
    ) {
        $R1 = $R[0] ?? 0;
        $R2 = $R[1] ?? 0;
        $R3 = $R[2] ?? 0;
        $R4 = $R[3] ?? 0;
        $Temp1 = $Temp[0] ?? 0;
        $Temp2 = $Temp[1] ?? 0;
        $Temp3 = $Temp[2] ?? 0;
        $Temp4 = $Temp[3] ?? 0;
        $Tiempo1 = $Tiempo[0] ?? 0;
        $Tiempo2 = $Tiempo[1] ?? 0;
        $Tiempo3 = $Tiempo[2] ?? 0;
        $Tiempo4 = $Tiempo[3] ?? 0;
        $Tiempo1 = max($Tiempo1, 0.0001);
        $Tiempo2 = max($Tiempo2, 0.0001);
        $Tiempo3 = max($Tiempo3, 0.0001);
        $Tiempo4 = max($Tiempo4, 0.0001);
        $RL1 = $RL[0] ?? 0;
        $RL2 = $RL[1] ?? 0;
        $RL3 = $RL[2] ?? 0;
        $RL4 = $RL[3] ?? 0;

        $C1 = 0;
        $C2 = 0;
        $C3 = 0;
        $C4 = 0;
        $C0 = 0;
        $P1 = 0;
        $P2 = 0;
        $P3 = 0;
        $P4 = 0;
        $h1 = 0;
        $h2 = 0;
        $h3 = 0;
        $h4 = 0;
        $v1 = 0;
        $v2 = 0;
        $v3 = 0;
        $v4 = 0;
        $N1 = 0;
        $N2 = 0;
        $N3 = 0;
        $N4 = 0;
        $X1 = 0;
        $X2 = 0;
        $X3 = 0;
        $X4 = 0;
        $PS = 0;
        $g = 0;
        $PI1;
        $PI2;
        $PI3;
        $PI4;
        $m1 = 0;
        $m2 = 0;
        $b1 = 0;
        $b2 = 0;
        $P50 = 0;
        $PP2 = 0;
        $arcilla = 0;
        $limo = 0;
        $arena = 0;

        /* Corrección por blanco */
        $C1 = self::correccion_blanco($R1, $RL1);
        $C2 = self::correccion_blanco($R2, $RL2);
        $C3 = self::correccion_blanco($R3, $RL3);
        $C4 = self::correccion_blanco($R4, $RL4);

        /* Concentración inicial */
        $C0 = self::concentracion_inicial($Ms);

        /* Porcentaje en suspensión */
//        $P1 = self::porcentaje_suspension($C1, $C0);
//        $P2 = self::porcentaje_suspension($C2, $C0);
//        $P3 = self::porcentaje_suspension($C3, $C0);
//        $P4 = self::porcentaje_suspension($C4, $C0);
        $P1 = self::porcentaje_suspension($C1, $Ms);
        $P2 = self::porcentaje_suspension($C2, $Ms);
        $P3 = self::porcentaje_suspension($C3, $Ms);
        $P4 = self::porcentaje_suspension($C4, $Ms);
        /* Profundidad efectiva */
        $h1 = self::profundidad_efectiva($R1);
        $h2 = self::profundidad_efectiva($R2);
        $h3 = self::profundidad_efectiva($R3);
        $h4 = self::profundidad_efectiva($R4);

        /* Obtener viscosidad desde tabla */
        $v1 = self::obtener_viscosidad_cp($Temp1);
        $v2 = self::obtener_viscosidad_cp($Temp2);
        $v3 = self::obtener_viscosidad_cp($Temp3);
        $v4 = self::obtener_viscosidad_cp($Temp4);

        /* Obtener factor N */
        $N1 = self::obtener_factor_n($v1);
        $N2 = self::obtener_factor_n($v2);
        $N3 = self::obtener_factor_n($v3);
        $N4 = self::obtener_factor_n($v4);

        /* Diámetro equivalente de partícula */
        $Ps = 2.65;                         //Ps= densidad particulas = 2, 65 g/cm3        
        $g = 9.8;                           //g = 9.8 m/s2 gravedad
        //Pl= densidad del liquido tabulado a partir de la depuratura
        $densidad = self::obtener_densidad($Temp1);
        $PI1 = $densidad['agua_hmp'];
        $densidad = self::obtener_densidad($Temp2);
        $PI2 = $densidad['agua_hmp'];
        $densidad = self::obtener_densidad($Temp3);
        $PI3 = $densidad['agua_hmp'];
        $densidad = self::obtener_densidad($Temp4);
        $PI4 = $densidad['agua_hmp'];

        $X1 = self::diametro_particula($N1, $h1, $Tiempo1, $Ps, $g, $PI1);
        $X2 = self::diametro_particula($N2, $h2, $Tiempo2, $Ps, $g, $PI2);
        $X3 = self::diametro_particula($N3, $h3, $Tiempo3, $Ps, $g, $PI3);
        $X4 = self::diametro_particula($N4, $h4, $Tiempo4, $Ps, $g, $PI4);

        if (($X2 - $X1) == 0 || ($X4 - $X3) == 0) {
            return ["arcilla" => 0, "limo" => 0, "arena" => 0];
        }

        //if ($P1 <= 0) {     $P1 = 0.0001; }
        $P1 = max($P1, 0.0001);
        $P2 = max($P2, 0.0001);
        $P3 = max($P3, 0.0001);
        $P4 = max($P4, 0.0001);

        $m1 = (log10($P2) - log10($P1)) / ($X2 - $X1);
        $m2 = (log10($P4) - log10($P3)) / ($X4 - $X3);

        $b1 = log10($P2) - ($m1 * $X2);
        $b2 = log10($P4) - ($m2 * $X4);
        $P50 = pow(10, ($m1 * 50) + $b1);   //P50 = 10^(m1*50 + b1)
        $PP2 = pow(10, ($m2 * 2) + $b2);     //P2 = 10^(m2*2 + b2) 

        $arcilla = $PP2;
        $limo = 100 - $PP2 - $P50;
        $arena = 100 - $P50;

        return [
            "arcilla" => $arcilla,
            "limo" => $limo,
            "arena" => $arena
        ];
    }

    //--------------------------------------------------------------------------------

    public static function calcularTexturas($textura) {
        $texturas = [];
        $blancos = [];

        /* detectar blancos */
        foreach ($textura as $t) {

            if (self::esBlanco($t->idlab)) {

                if (!isset($blancos[$t->id_textura])) {
                    $blancos[$t->id_textura] = $t;
                }
            }
        }

        /* calcular muestras */
        foreach ($textura as $m) {

            // evitar procesar blancos
            if (self::esBlanco($m->idlab)) {
                continue;
            }

            $blanco = $blancos[$m->id_textura] ?? null;

            if (!$blanco) {
                continue;
            }

            if (!$m->peso_seco) {
                continue;
            }

            $R = [
                $m->R1 ?? 0,
                $m->R2 ?? 0,
                $m->R3 ?? 0,
                $m->R4 ?? 0
            ];

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

            $resultado = self::calcular_textura_suelo(
                    $m->peso_seco,
                    $R,
                    $RL,
                    $Temp,
                    $Tiempo
            );

            $texturas[$m->idlab] = $resultado;
        }

        return $texturas;
    }

    private static function esBlanco($idlab) {
        $idlab = strtoupper($idlab);

        return in_array($idlab, [
            'BLANCO', 'BLK', 'BLANK', 'CBL', 'C-BL', 'BL'
        ]);
    }
}

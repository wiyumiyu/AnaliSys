<?php

namespace App\Helpers\Calculos;

class RetencionHumedad {

    /* Peso suelo sin lata */
    public static function peso_suelo($ph_L, $L) {
        return $ph_L - $L;
    }

    /* Humedad gravimétrica */
    public static function humedad_gravimetrica($ph, $ps) {

        $ps = max($ps, 0.0001);

        return ($ph - $ps) / $ps;
    }

    /* FUNCIÓN PRINCIPAL (por muestra) */
    public static function calcular_retencion(
        $ph1_L1, $ps1_L1,
        $ph2_L2, $ps2_L2,
        $L1, $L2
    ) {

        // Pesos sin lata
        $ph1 = self::peso_suelo($ph1_L1, $L1);
        $ps1 = self::peso_suelo($ps1_L1, $L1);

        $ph2 = self::peso_suelo($ph2_L2, $L2);
        $ps2 = self::peso_suelo($ps2_L2, $L2);

        // Hg para cada presión
        $Hg1 = self::humedad_gravimetrica($ph1, $ps1); // 33 kPa
        $Hg2 = self::humedad_gravimetrica($ph2, $ps2); // 1500 kPa

        // Agua disponible
        $AD = $Hg1 - $Hg2;

        return [
            "Hg_33" => round($Hg1, 4),
            "Hg_1500" => round($Hg2, 4),
            "agua_disponible" => round($AD, 4)
        ];
    }

    /* PROCESAR LISTA (tipo Textura) */
    public static function calcularRetenciones($muestras) {

        $resultados = [];

        foreach ($muestras as $m) {

            // convertir a número (importante por VARCHAR)
            $ph1_L1 = (float) ($m->ph1_L1 ?? 0);
            $ps1_L1 = (float) ($m->ps1_L1 ?? 0);
            $ph2_L2 = (float) ($m->ph_L2 ?? 0);
            $ps2_L2 = (float) ($m->ps2_L2 ?? 0);
            $L1 = (float) ($m->L1 ?? 0);
            $L2 = (float) ($m->L2 ?? 0);

            // validación mínima
            if ($ph1_L1 <= 0 || $ps1_L1 <= 0 || $L1 <= 0) {
                continue;
            }

            $resultado = self::calcular_retencion(
                $ph1_L1,
                $ps1_L1,
                $ph2_L2,
                $ps2_L2,
                $L1,
                $L2
            );

            $resultados[trim($m->idlab)] = $resultado;
        }

        return $resultados;
    }
}

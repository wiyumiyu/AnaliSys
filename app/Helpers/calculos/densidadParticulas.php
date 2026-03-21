<?php

namespace App\Helpers\Calculos;

class DensidadParticulas {

    public static function calcular(
        $numero_balon,
        $p1,
        $p2,
        $p3,
        $temperatura
    ) {

        if (
            $numero_balon <= 0 ||
            $p1 === null ||
            $p2 === null ||
            $p3 === null ||
            $temperatura <= 0
        ) {
            return null;
        }

        // Ms
        $Ms = $p2 - $p1;

        if ($Ms <= 0) return null;

        // Mw
        $Mw = $p3 - $p2;

        if ($Mw <= 0) return null;

        // Vw
        $Vw = $Mw / $temperatura;

        // Vs
        $Vs = $numero_balon - $Vw;

        if ($Vs <= 0) return null;

        // DP
        $Dp = $Ms / $Vs;

        return round($Dp, 4);
    }
}
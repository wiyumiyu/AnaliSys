<?php

namespace App\Helpers\Calculos;

class HumedadGravimetrica {

    public static function calcular($pc, $ph, $ps)
    {
        if ($pc === null || $ph === null || $ps === null) {
            return null;
        }

        // Suelo húmedo
        $Phs = $ph - $pc;

        // Suelo seco
        $Pss = $ps - $pc;

        if ($Pss <= 0) {
            return null;
        }

        $Hg = (($Phs - $Pss) / $Pss) * 100;

        return round($Hg, 2);
    }
}
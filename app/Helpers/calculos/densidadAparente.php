<?php

namespace App\Helpers\Calculos;

class DensidadAparente {

    public static function calcular_densidad(
        $altura,
        $diametro,
        $peso_seco,
        $peso_cilindro
    ) {
        
        // Validaciones
        if (
            $altura <= 0 ||
            $diametro <= 0 ||
            $peso_seco === null ||
            $peso_cilindro === null
        ) {
            return null;
        }

        // 1. Masa de suelo seco
        $Ms = $peso_seco - $peso_cilindro;
        
        if ($Ms <= 0) {
            return null;
        }

        // 2. Volumen del cilindro
        $Vt = pi() * (pow($diametro, 2) / 4) * $altura;

        if ($Vt <= 0) {
            return null;
        }
        
        // 3. Densidad aparente
        $Da = $Ms / $Vt;
       // dd($Da);
        return round($Da, 4);
    }
}
<?php

namespace App\Helpers\Calculos;

class Porosidad {

    public static function calcular(
         $densidades,
        $densidadesParticulas,
        $datos
    ) {
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
       
        
       // dd($Da);
        return $porosidades;
    }
}


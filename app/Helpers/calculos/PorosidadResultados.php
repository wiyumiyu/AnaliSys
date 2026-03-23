<?php

namespace App\Helpers\Calculos;

class PorosidadResultados
{
    public static function calcularPorRep($densidades, $densidadesParticulas)
    {
        $porosidades = [];

        foreach ($densidades as $idlab => $reps) {

            foreach ($reps as $rep => $da) {

                $dp = $densidadesParticulas[$idlab][$rep] ?? null;

                if ($da !== null && $dp !== null && $dp != 0) {

                    $p = (1 - ($da / $dp)) * 100;

                    $porosidades[$idlab][$rep] = round($p, 2);
                }
            }
        }

        return $porosidades;
    }
}
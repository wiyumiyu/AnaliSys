<?php

namespace App\Helpers\Calculos;

class DensidadParticulasResultados {

    public static function calcularPorRep($data) {
        $densidades = [];

        foreach ($data as $m) {

            $dp = DensidadParticulas::calcular(
                    $m->numero_balon,
                    $m->p1,
                    $m->p2,
                    $m->p3,
                    $m->temperatura
            );

            if ($dp !== null) {
                $densidades[$m->idlab][$m->rep] = $dp;
            }
        }

        return $densidades;
    }
}

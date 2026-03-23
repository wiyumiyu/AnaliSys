<?php

namespace App\Helpers\Calculos;

class HumedadGravimetricaResultados {

    public static function calcularPorRep($data) {
        $humedades = [];

        foreach ($data as $m) {

            $hg = HumedadGravimetrica::calcular(
                    $m->pc,
                    $m->ph,
                    $m->ps
            );

            if ($hg !== null) {
                $humedades[$m->idlab][$m->rep] = $hg;
            }
        }

        return $humedades;
    }
}

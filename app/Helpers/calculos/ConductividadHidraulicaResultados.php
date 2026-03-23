<?php

namespace App\Helpers\Calculos;

class ConductividadHidraulicaResultados
{
    public static function calcularPorRep($data)
    {
        $chs = [];

        foreach ($data as $m) {

            $res = ConductividadHidraulica::calcular_conductividad(
                $m->L,
                $m->D,
                $m->h,
                $m->V,
                $m->t
            );

            $K = $res['conductividad_hidraulica'] ?? null;

            if ($K !== null) {
                $chs[$m->idlab][$m->rep] = $K;
            }
        }

        return $chs;
    }
}
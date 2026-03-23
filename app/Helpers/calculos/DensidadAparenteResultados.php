<?php

namespace App\Helpers\Calculos;

class DensidadAparenteResultados
{
    public static function calcularPorRep($data)
    {
        $densidades = [];

        foreach ($data as $m) {

            $da = DensidadAparente::calcular_densidad(
                $m->altura_cilindro,
                $m->diametro_cilindro,
                $m->peso_seco,
                $m->peso_cilindro
            );

            if ($da !== null) {
                $densidades[$m->idlab][$m->rep] = $da;
            }
        }

        return $densidades;
    }

    public static function promedio($rows, $densidades)
    {
        $vals = [];

        foreach ($rows as $r) {
            $d = $densidades[$r->idlab][$r->rep] ?? null;

            if ($d !== null) {
                $vals[] = $d;
            }
        }

        return count($vals) ? array_sum($vals)/count($vals) : null;
    }
}
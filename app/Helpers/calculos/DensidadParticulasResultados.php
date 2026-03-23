<?php

namespace App\Helpers\Calculos;

class DensidadParticulasResultados
{
    public static function calcularPorRep($data)
    {
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
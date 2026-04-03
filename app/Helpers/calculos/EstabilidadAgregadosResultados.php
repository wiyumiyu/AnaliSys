<?php

namespace App\Helpers\Calculos;

class EstabilidadAgregadosResultados
{
    public static function calcularPorRep($data)
    {
        $res = [];

        foreach ($data as $m) {

            $Pt = (float) ($m->Pt ?? 0);

            if ($Pt <= 0) continue;

            $tamices = [2, 1, 0.5, 0.25, 0];

            $pesos = [
                (float) ($m->Pri2 ?? 0),
                (float) ($m->Pri1 ?? 0),
                (float) ($m->Pri05 ?? 0),
                (float) ($m->Pri025 ?? 0),
                (float) ($m->Pri0 ?? 0)
            ];

            if (array_sum($pesos) <= 0) continue;

            $dmp = EstabilidadAgregados::calcular_dmp($tamices, $pesos, $Pt);
            $eaa = EstabilidadAgregados::calcular_eaa($tamices, $pesos, $Pt);

            $res[$m->idlab][$m->rep] = [
                "dmp" => $dmp,
                "eaa" => $eaa
            ];
        }

        return $res;
    }

    public static function estadisticas($rows, $datos)
    {
        $dmp = [];
        $eaa = [];

        foreach ($rows as $r) {

            $ea = $datos[$r->idlab][$r->rep] ?? null;

            if ($ea) {
                if ($ea['dmp'] !== null) $dmp[] = $ea['dmp'];
                if ($ea['eaa'] !== null) $eaa[] = $ea['eaa'];
            }
        }

        return [
            'dmp' => Estadisticas::calcularDesdeArray($dmp),
            'eaa' => Estadisticas::calcularDesdeArray($eaa),
        ];
    }
}
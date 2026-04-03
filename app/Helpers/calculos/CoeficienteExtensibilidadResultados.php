<?php

namespace App\Helpers\Calculos;

class CoeficienteExtensibilidadResultados
{
    public static function calcularPorRep($data)
    {
        $res = [];

        foreach ($data as $m) {

            // Validación mínima
            if (!$m->altura_cilindro_od || !$m->diametro_cilindro_od) continue;

            $calc = CoeficienteExtensibilidad::calcular([
                'altura_cilindro_od' => (float) $m->altura_cilindro_od,
                'diametro_cilindro_od' => (float) $m->diametro_cilindro_od,
                'peso_cilindro_suelo_seco_od' => (float) $m->peso_cilindro_suelo_seco_od,
                'peso_cilindro_vacio_od' => (float) $m->peso_cilindro_vacio_od,

                'altura_cilindro_33kpa' => (float) $m->altura_cilindro_33kpa,
                'diametro_cilindro_33kpa' => (float) $m->diametro_cilindro_33kpa,
                'peso_cilindro_suelo_33kpa' => (float) $m->peso_cilindro_suelo_33kpa,
                'peso_cilindro_vacio_33kpa' => (float) $m->peso_cilindro_vacio_33kpa,
            ]);

            $res[$m->idlab][$m->rep] = $calc;
        }

        return $res;
    }

    public static function estadisticas($rows, $datos)
    {
        $cole = [];

        foreach ($rows as $r) {

            $ea = $datos[$r->idlab][$r->rep] ?? null;

            if ($ea && $ea['cole'] !== null) {
                $cole[] = $ea['cole'];
            }
        }

        return [
            'cole' => Estadisticas::calcularDesdeArray($cole)
        ];
    }
}
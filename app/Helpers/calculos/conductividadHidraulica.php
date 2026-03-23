<?php

namespace App\Helpers\Calculos;

class ConductividadHidraulica {

    /* Área transversal */
    public static function area($diametro) {
        $diametro = max($diametro, 0.0001);
        return (pi() * pow($diametro, 2)) / 4;
    }

    /* Conductividad hidráulica */
    public static function conductividad_hidraulica($Q, $L, $A, $t, $h) {

        $A = max($A, 0.0001);
        $t = max($t, 0.0001);
        $h = max($h, 0.0001);

        return ($Q * $L) / ($A * $t * $h);
    }

    /* FUNCIÓN PRINCIPAL */
    public static function calcular_conductividad(
        $longitud,
        $diametro,
        $carga_hidraulica,
        $volumen,
        $tiempo
    ) {

        $L = $longitud ?? 0;
        $D = $diametro ?? 0;
        $h = $carga_hidraulica ?? 0;
        $Q = $volumen ?? 0;
        $t = $tiempo ?? 0;

        // Calcular área
        $A = self::area($D);

        // Calcular K
        $K = self::conductividad_hidraulica($Q, $L, $A, $t, $h);

        return [
            "area" => round($A, 4),
            "conductividad_hidraulica" => round($K, 6)
        ];
    }

    /* PROCESAR LISTA (tipo calcularTexturas) */
    public static function calcularConductividades($muestras) {

        $resultados = [];

        foreach ($muestras as $m) {

            if (!$m->longitud_muestra || !$m->diametro_interno) {
                continue;
            }

            $resultado = self::calcular_conductividad(
                $m->longitud_muestra,
                $m->diametro_interno,
                $m->carga_hidraulica,
                $m->volumen,
                $m->tiempo
            );

            $resultados[$m->idlab] = $resultado;
        }

        return $resultados;
    }
}
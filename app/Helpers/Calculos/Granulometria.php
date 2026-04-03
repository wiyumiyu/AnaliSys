<?php

namespace App\Helpers\Calculos;

class Granulometria
{
    /* ===============================
     * MASA TOTAL
     * =============================== */
    public static function masa_total($peso_total, $peso_lata)
    {
        if ($peso_total === null || $peso_lata === null) return 0;

        return $peso_total - $peso_lata;
    }

    /* ===============================
     * % RETENIDO
     * =============================== */
    public static function porcentaje_retenido($peso, $ms)
    {
        if ($ms <= 0) return 0;

        return ($peso / $ms) * 100;
    }

    /* ===============================
     * FUNCIÓN PRINCIPAL (UNA MUESTRA)
     * =============================== */
    public static function calcular($m)
    {
        // Masa total
        $ms = self::masa_total(
            $m->peso_seco_lata ?? 0,
            $m->peso_lata ?? 0
        );

        if ($ms <= 0) return [];

        // Orden fijo de tamices (CRÍTICO)
        $tamices = [
            19   => $m->T19 ?? 0,
            9.5  => $m->T9 ?? 0,
            4.75 => $m->T4 ?? 0,
            2    => $m->T2 ?? 0,
            1    => $m->T1 ?? 0,
            0.5  => $m->T05 ?? 0,
            0.25 => $m->T025 ?? 0,
            0.125=> $m->T0125 ?? 0,
            0    => $m->T0 ?? 0,
        ];

        $resultado = [];
        $acumulado = 0;

        foreach ($tamices as $tamiz => $peso) {

            $peso = $peso ?? 0;

            $retenido = self::porcentaje_retenido($peso, $ms);

            $acumulado += $retenido;

            $pasante = 100 - $acumulado;

            $resultado[] = [
                'tamiz'      => $tamiz,
                'peso'       => $peso,
                'retenido'   => $retenido,
                'acumulado'  => $acumulado,
                'pasante'    => $pasante,
                'idlab'      => $m->idlab,
                'etiqueta'   => $m->etiqueta ?? null
            ];
        }

        return $resultado;
    }

    /* ===============================
     * AGRUPAR TODAS LAS MUESTRAS
     * =============================== */
    public static function calcularPorMuestras($data)
    {
        $granulometrias = [];

        foreach ($data as $m) {

            if (!$m->idlab) continue;

            $granulometrias[$m->idlab] = self::calcular($m);
        }

        return $granulometrias;
    }
}
<?php

namespace App\Helpers\Calculos;

class TexturaResultados
{
    public static function calcularPorRep($textura)
    {
        $texturas = [];
        $blancos = [];

        /* ===========================
         * 1. DETECTAR BLANCOS
         * =========================== */
        foreach ($textura as $t) {

            if (self::esBlanco($t->idlab)) {

                if (!isset($blancos[$t->id_textura])) {
                    $blancos[$t->id_textura] = $t;
                }
            }
        }

        /* ===========================
         * 2. CALCULAR POR REP
         * =========================== */
        foreach ($textura as $m) {

            if (self::esBlanco($m->idlab)) {
                continue;
            }

            $blanco = $blancos[$m->id_textura] ?? null;

            // 🔥 VALIDACIONES REALES
            if ($blanco === null) continue;
            if ($m->peso_seco === null) continue;

            if (
                $m->R1 === null || $m->R2 === null ||
                $m->R3 === null || $m->R4 === null ||
                $blanco->R1 === null || $blanco->R2 === null ||
                $blanco->R3 === null || $blanco->R4 === null
            ) continue;

            if (
                $m->TEMP1 === null || $m->TEMP2 === null ||
                $m->TEMP3 === null || $m->TEMP4 === null
            ) continue;

            if (
                $m->TIEMPO1 === null || $m->TIEMPO2 === null ||
                $m->TIEMPO3 === null || $m->TIEMPO4 === null
            ) continue;

            // 🔥 DATOS LIMPIOS (SIN ?? 0)
            $R = [
                $m->R1,
                $m->R2,
                $m->R3,
                $m->R4
            ];

            $RL = [
                $blanco->R1,
                $blanco->R2,
                $blanco->R3,
                $blanco->R4
            ];


            $Temp = [
                $m->TEMP1,
                $m->TEMP2,
                $m->TEMP3,
                $m->TEMP4
            ];

            $Tiempo = [
                $m->TIEMPO1,
                $m->TIEMPO2,
                $m->TIEMPO3,
                $m->TIEMPO4
            ];

            $resultado = Textura::calcular_textura_suelo(
                $m->peso_seco,
                $R,
                $RL,
                $Temp,
                $Tiempo
            );

            // 🔥 GUARDAR SOLO SI ES VÁLIDO
            if ($resultado !== null) {
                $texturas[$m->idlab][$m->rep] = $resultado;
            }
        }

        return $texturas;
    }

    /* ===========================
     * PROMEDIO
     * =========================== */
    public static function promedio($rows, $texturas)
    {
        $arena = [];
        $limo = [];
        $arcilla = [];

        foreach ($rows as $r) {

            $t = $texturas[$r->idlab][$r->rep] ?? null;

            if ($t) {
                $arena[] = $t['arena'];
                $limo[] = $t['limo'];
                $arcilla[] = $t['arcilla'];
            }
        }

        return [
            'arena' => count($arena) > 1 ? array_sum($arena)/count($arena) : null,
            'limo' => count($limo) > 1 ? array_sum($limo)/count($limo) : null,
            'arcilla' => count($arcilla) > 1 ? array_sum($arcilla)/count($arcilla) : null,
        ];
    }

    /* ===========================
     * DESVIACIÓN ESTÁNDAR
     * =========================== */
    public static function desviacion($rows, $texturas, $prom)
    {
        $calc = function($values, $mean) {
            if (count($values) <= 1 || $mean === null) return null;

            $sum = 0;
            foreach ($values as $v) {
                $sum += pow($v - $mean, 2);
            }

            return sqrt($sum / count($values));
        };

        $arena = [];
        $limo = [];
        $arcilla = [];

        foreach ($rows as $r) {

            $t = $texturas[$r->idlab][$r->rep] ?? null;

            if ($t) {
                $arena[] = $t['arena'];
                $limo[] = $t['limo'];
                $arcilla[] = $t['arcilla'];
            }
        }

        return [
            'arena' => $calc($arena, $prom['arena']),
            'limo' => $calc($limo, $prom['limo']),
            'arcilla' => $calc($arcilla, $prom['arcilla']),
        ];
    }

    /* ===========================
     * CV
     * =========================== */
    public static function cv($desv, $prom)
    {
        $calc = function($d, $p) {
            if ($d === null || $p == 0) return null;
            return ($d / $p) * 100;
        };

        return [
            'arena' => $calc($desv['arena'], $prom['arena']),
            'limo' => $calc($desv['limo'], $prom['limo']),
            'arcilla' => $calc($desv['arcilla'], $prom['arcilla']),
        ];
    }

    private static function esBlanco($idlab)
    {
        $idlab = strtoupper($idlab);

        return in_array($idlab, [
            'BLANCO', 'BLK', 'BLANK', 'CBL', 'C-BL', 'BL'
        ]);
    }
}
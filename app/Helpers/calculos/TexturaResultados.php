<?php

namespace App\Helpers\Calculos;

use App\Helpers\Calculos\Estadisticas;

class TexturaResultados {

    public static function calcularPorRep($textura) {
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
            if ($blanco === null)
                continue;
            if ($m->peso_seco === null)
                continue;

            if (
                    $m->R1 === null || $m->R2 === null ||
                    $m->R3 === null || $m->R4 === null ||
                    $blanco->R1 === null || $blanco->R2 === null ||
                    $blanco->R3 === null || $blanco->R4 === null
            )
                continue;

            if (
                    $m->TEMP1 === null || $m->TEMP2 === null ||
                    $m->TEMP3 === null || $m->TEMP4 === null
            )
                continue;

            if (
                    $m->TIEMPO1 === null || $m->TIEMPO2 === null ||
                    $m->TIEMPO3 === null || $m->TIEMPO4 === null
            )
                continue;

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

    public static function estadisticas($rows, $texturas) {
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
            'arena' => Estadisticas::calcularDesdeArray($arena),
            'limo' => Estadisticas::calcularDesdeArray($limo),
            'arcilla' => Estadisticas::calcularDesdeArray($arcilla),
        ];
    }

    private static function esBlanco($idlab) {
        $idlab = strtoupper($idlab);

        return in_array($idlab, [
            'BLANCO', 'BLK', 'BLANK', 'CBL', 'C-BL', 'BL'
        ]);
    }
}

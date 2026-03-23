<?php

namespace App\Helpers\Calculos;

class Estadisticas {
    /* PARA LOS QUE SON EN ARCHIVOS  --------------------------------------------------------- */

    /* ===========================
     * OBTENER VALORES VÁLIDOS
     * =========================== */

    public static function valores($rows, $data) {
        $vals = [];

        foreach ($rows as $r) {
            $v = $data[$r->idlab][$r->rep] ?? null;

            if ($v !== null) {
                $vals[] = $v;
            }
        }

        return $vals;
    }

    /* ===========================
     * PROMEDIO
     * =========================== */

    public static function promedio($vals) {
        return count($vals) > 1 ? array_sum($vals) / count($vals) : null;
    }

    /* ===========================
     * DESVIACIÓN ESTÁNDAR
     * =========================== */

    public static function desviacion($vals, $prom) {
        if (count($vals) <= 1 || $prom === null)
            return null;

        $sum = 0;

        foreach ($vals as $v) {
            $sum += pow($v - $prom, 2);
        }

        return sqrt($sum / count($vals));
    }

    /* ===========================
     * COEFICIENTE DE VARIACIÓN
     * =========================== */

    public static function cv($desv, $prom) {
        if ($desv === null || $prom == 0)
            return null;

        return ($desv / $prom) * 100;
    }

    /* ===========================
     * TODO EN UNO (RECOMENDADO)
     * =========================== */

    public static function calcular($rows, $data) {
        $vals = self::valores($rows, $data);

        $prom = self::promedio($vals);
        $desv = self::desviacion($vals, $prom);
        $cv = self::cv($desv, $prom);

        return [
            'vals' => $vals,
            'prom' => $prom,
            'desv' => $desv,
            'cv' => $cv
        ];
    }

    /* PARA LOS QUE SON EN ARRAY EN ESTE CASO TEXTURA  --------------------------------------------------------- */

    public static function calcularDesdeArray($vals) {
        $prom = self::promedio($vals);
        $desv = self::desviacion($vals, $prom);
        $cv = self::cv($desv, $prom);

        return [
            'vals' => $vals,
            'prom' => $prom,
            'desv' => $desv,
            'cv' => $cv
        ];
    }
}

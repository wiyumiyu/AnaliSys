<?php

namespace App\Helpers\Calculos;

class CoeficienteExtensibilidad
{
    /* ===============================
     * VOLUMEN DEL CILINDRO
     * =============================== */
    public static function volumen($altura, $diametro)
    {
        if (!$altura || !$diametro) return 0;

        return pi() * pow($diametro, 2) / 4 * $altura;
    }

    /* ===============================
     * MASA DEL SUELO
     * =============================== */
    public static function masa_suelo($peso_total, $peso_vacio)
    {
        if ($peso_total === null || $peso_vacio === null) return 0;

        return $peso_total - $peso_vacio;
    }

    /* ===============================
     * DENSIDAD APARENTE
     * =============================== */
    public static function densidad_aparente($masa, $volumen)
    {
        if ($volumen <= 0) return 0;

        return $masa / $volumen;
    }

    /* ===============================
     * COLE
     * =============================== */
    public static function calcular_cole($da_od, $da_33)
    {
        if ($da_od <= 0 || $da_33 <= 0) return 0;

        return pow(($da_od / $da_33), (-1/3)) - 1;
    }

    /* ===============================
     * CÁLCULO POR MUESTRA
     * =============================== */
    public static function calcular($datos)
    {
        // ===== OD =====
        $vt_od = self::volumen(
            $datos['altura_cilindro_od'] ?? 0,
            $datos['diametro_cilindro_od'] ?? 0
        );

        $ms_od = self::masa_suelo(
            $datos['peso_cilindro_suelo_seco_od'] ?? 0,
            $datos['peso_cilindro_vacio_od'] ?? 0
        );

        $da_od = self::densidad_aparente($ms_od, $vt_od);

        // ===== 33 kPa =====
        $vt_33 = self::volumen(
            $datos['altura_cilindro_33kpa'] ?? 0,
            $datos['diametro_cilindro_33kpa'] ?? 0
        );

        $ms_33 = self::masa_suelo(
            $datos['peso_cilindro_suelo_33kpa'] ?? 0,
            $datos['peso_cilindro_vacio_33kpa'] ?? 0
        );

        $da_33 = self::densidad_aparente($ms_33, $vt_33);

        // ===== COLE =====
        $cole = self::calcular_cole($da_od, $da_33);

        return [
            'vt_od' => round($vt_od, 4),
            'vt_33' => round($vt_33, 4),
            'ms_od' => round($ms_od, 4),
            'ms_33' => round($ms_33, 4),
            'da_od' => round($da_od, 4),
            'da_33' => round($da_33, 4),
            'cole'  => round($cole, 4),
        ];
    }

    /* ===============================
     * PROMEDIO
     * =============================== */
    public static function promedio($valores, $campo)
    {
        if (empty($valores)) return 0;

        $suma = array_sum(array_column($valores, $campo));
        return $suma / count($valores);
    }

    /* ===============================
     * DESVIACIÓN ESTÁNDAR
     * =============================== */
    public static function desviacion($valores, $campo)
    {
        $n = count($valores);
        if ($n <= 1) return 0;

        $media = self::promedio($valores, $campo);

        $suma = 0;
        foreach ($valores as $v) {
            $suma += pow(($v[$campo] - $media), 2);
        }

        return sqrt($suma / ($n - 1));
    }

    /* ===============================
     * COEFICIENTE DE VARIACIÓN
     * =============================== */
    public static function cv($valores, $campo)
    {
        $media = self::promedio($valores, $campo);
        if ($media == 0) return 0;

        $desv = self::desviacion($valores, $campo);

        return ($desv / $media) * 100;
    }

    /* ===============================
     * AGRUPAR POR IDLAB + ESTADÍSTICAS
     * =============================== */
    public static function calcularPorMuestras($muestras)
    {
        $agrupados = [];

        /* ===== AGRUPAR ===== */
        foreach ($muestras as $m) {

            $resultado = self::calcular([
                'altura_cilindro_od' => $m->altura_cilindro_od,
                'diametro_cilindro_od' => $m->diametro_cilindro_od,
                'peso_cilindro_suelo_seco_od' => $m->peso_cilindro_suelo_seco_od,
                'peso_cilindro_vacio_od' => $m->peso_cilindro_vacio_od,

                'altura_cilindro_33kpa' => $m->altura_cilindro_33kpa,
                'diametro_cilindro_33kpa' => $m->diametro_cilindro_33kpa,
                'peso_cilindro_suelo_33kpa' => $m->peso_cilindro_suelo_33kpa,
                'peso_cilindro_vacio_33kpa' => $m->peso_cilindro_vacio_33kpa,
            ]);

            $agrupados[$m->idlab][] = $resultado;
        }

        /* ===== CALCULAR ESTADÍSTICAS ===== */
        $final = [];

        foreach ($agrupados as $idlab => $valores) {

            $final[$idlab] = [
                'cole' => round(self::promedio($valores, 'cole'), 4),
                'desv' => round(self::desviacion($valores, 'cole'), 4),
                'cv'   => round(self::cv($valores, 'cole'), 2),
            ];
        }

        return $final;
    }
}
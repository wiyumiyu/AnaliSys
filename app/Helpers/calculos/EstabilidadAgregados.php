<?php

namespace App\Helpers\Calculos;

class EstabilidadAgregados
{

    /* ===== Calcular DMP ===== */
    public static function calcular_dmp($tamices, $pesos, $Pt)
    {
        if ($Pt <= 0) return 0;

        $sum = 0;

        for ($i = 0; $i < count($pesos); $i++) {

            $wi = $pesos[$i] / $Pt;

            $xi = $tamices[$i];

            $sum += ($xi * $wi);
        }

        return round($sum, 4);
    }

    /* ===== Calcular EAA ===== */
    public static function calcular_eaa($tamices, $pesos, $Pt)
    {
        if ($Pt <= 0) return 0;

        $suma = 0;

        for ($i = 0; $i < count($pesos); $i++) {

            if ($tamices[$i] > 0.25) {
                $suma += $pesos[$i];
            }
        }

        return round(($suma / $Pt) * 100, 2);
    }

    /* ===== FUNCIÓN PRINCIPAL ===== */
    public static function calcular($data)
    {
        $resultados = [];

        foreach ($data as $m) {

            if (!$m->Pt) continue;

            // 🔹 Tamices fijos (tu sistema)
            $tamices = [2, 1, 0.5, 0.25, 0];

            // 🔹 Pesos
            $pesos = [
                $m->Pri2 ?? 0,
                $m->Pri1 ?? 0,
                $m->Pri05 ?? 0,
                $m->Pri025 ?? 0,
                $m->Pri0 ?? 0
            ];

            // 🔹 Validación básica
            $suma = array_sum($pesos);

            if ($suma <= 0) continue;

            // 🔹 Cálculos
            $dmp = self::calcular_dmp($tamices, $pesos, $m->Pt);
            $eaa = self::calcular_eaa($tamices, $pesos, $m->Pt);

            $resultados[$m->idlab] = [
                "dmp" => $dmp,
                "eaa" => $eaa
            ];
        }

        return $resultados;
    }
}


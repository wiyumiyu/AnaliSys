<?php

namespace App\Helpers\Calculos;

class RetencionHumedadResultados
{
    public static function calcularPorRep($data)
    {
        $ret = [];

        foreach ($data as $m) {

            // 🔥 convertir a número (igual que tu clase original)
            $ph1_L1 = (float) ($m->ph1_L1 ?? 0);
            $ps1_L1 = (float) ($m->ps1_L1 ?? 0);
            $ph2_L2 = (float) ($m->ph_L2 ?? 0);
            $ps2_L2 = (float) ($m->ps2_L2 ?? 0);
            $L1 = (float) ($m->L1 ?? 0);
            $L2 = (float) ($m->L2 ?? 0);

            // 🔥 MISMA validación que ya usás
            if ($ph1_L1 <= 0 || $ps1_L1 <= 0 || $L1 <= 0) {
                continue;
            }

            // 🔥 reutilizamos TU lógica
            $resultado = RetencionHumedad::calcular_retencion(
                $ph1_L1,
                $ps1_L1,
                $ph2_L2,
                $ps2_L2,
                $L1,
                $L2
            );

            if ($resultado) {
                $ret[$m->idlab][$m->rep] = $resultado;
            }
        }

        return $ret;
    }

    public static function estadisticas($rows, $retenciones)
    {
        $h33 = [];
        $h1500 = [];
        $agua = [];

        foreach ($rows as $r) {

            $rh = $retenciones[$r->idlab][$r->rep] ?? null;

            if ($rh) {

                if ($rh['Hg_33'] !== null) $h33[] = $rh['Hg_33'];
                if ($rh['Hg_1500'] !== null) $h1500[] = $rh['Hg_1500'];
                if ($rh['agua_disponible'] !== null) $agua[] = $rh['agua_disponible'];
            }
        }

        return [
            'Hg_33' => Estadisticas::calcularDesdeArray($h33),
            'Hg_1500' => Estadisticas::calcularDesdeArray($h1500),
            'agua_disponible' => Estadisticas::calcularDesdeArray($agua),
        ];
    }
}
<?php

namespace App\Helpers\Calculos;

class UIHelper
{
    public static function color($valor, $tipo)
    {
        if ($valor === null) return '';

        switch ($tipo) {

            // ================= TEXTURA =================
            case 'porcentaje':
                if ($valor > 60) return 'bg-warning-subtle text-danger'; // 🟠 alto
                if ($valor < 20) return 'bg-info-subtle';       // 🔵 bajo
                break;

            // ================= DENSIDADES =================
            case 'densidad':
                if ($valor > 2.2) return 'bg-warning-subtle text-danger';
                if ($valor < 1.2) return 'bg-info-subtle';
                break;

            // ================= POROSIDAD =================
            case 'porosidad':
                if ($valor > 60) return 'bg-warning-subtle ';
                if ($valor < 30) return 'bg-info-subtle';
                break;

            // ================= HUMEDAD =================
            case 'humedad':
                if ($valor > 8) return 'bg-warning-subtle text-danger';
                if ($valor < 3) return 'bg-info-subtle';
                break;

            // ================= CONDUCTIVIDAD =================
            case 'ch':
                if ($valor > 0.02) return 'bg-warning-subtle text-danger';
                if ($valor < 0.005) return 'bg-info-subtle';
                break;

            // ================= RETENCION =================
            case 'retencion':
                if ($valor > 0.08) return 'bg-warning-subtle text-danger';
                if ($valor < 0.02) return 'bg-info-subtle';
                break;

            // ================= DMP =================
            case 'dmp':
                if ($valor > 1.2) return 'bg-warning-subtle text-danger';
                if ($valor < 0.4) return 'bg-info-subtle';
                break;

            // ================= EAA =================
            case 'eaa':
                if ($valor > 70) return 'bg-warning-subtle text-danger';
                if ($valor < 30) return 'bg-info-subtle';
                break;

            // ================= COEL =================
            case 'coel':
                if ($valor > 0.6) return 'bg-warning-subtle text-danger';
                if ($valor < 0.2) return 'bg-info-subtle';
                break;
        }

        return '';
    }
}
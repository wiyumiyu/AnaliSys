<?php

namespace App\Helpers\Calculos;

class DensidadParticulas {

//    public static function calcular(
//        $numero_balon,
//        $p1,
//        $p2,
//        $p3,
//        $temperatura
//    ) {
//
//        if (
//            $numero_balon <= 0 ||
//            $p1 === null ||
//            $p2 === null ||
//            $p3 === null ||
//            $temperatura <= 0
//        ) {
//            return null;
//        }
//        
//        //Pw densidad del agua
//        $densidad = textura::obtener_densidad($temperatura);
//        $Pw = $densidad['agua'];
//        
//        //dd($dens);
//        // Ms
//        $Ms = $p2 - $p1;
//
//        if ($Ms <= 0) return null;
//
//        // P3 es la masa total
//        
//        // Mw
//        $Mw = $p3 - $p1;
//      
//        
//        if ($Pw == 0) return null;
//
//        // Vw
//        $Vw = $Mw / $Pw;
//        //dd($Vw);
//        //if ($Vw == 0) return null;
//        
//        // Vs
//        //volumen de balon - $Vw
//        //$Vs = $numero_balon - $Vw;
//        // volumen desplazado por el suelo
//        $Vs = ($Ms) / (($p3 - $p1) / $numero_balon);
//
//        if ($Vs == 0) return null;
//
//        // DP
//        $Dp = $Ms / $Vs;
//
//        return round($Dp, 4);
//    }
    
    public static function calcular(
    $numero_balon, // volumen del balón en cm³
    $p1,           // peso balón vacío
    $p2,           // balón + suelo seco
    $p3,           // balón + suelo + agua
    $temperatura
) {

    // 🔒 Validaciones básicas
    if (
        $numero_balon <= 0 ||
        $p1 === null ||
        $p2 === null ||
        $p3 === null ||
        $temperatura <= 0
    ) {
        return null;
    }

    // 💧 Densidad del agua (g/cm³)
    $densidad = Textura::obtener_densidad($temperatura);
    $Pw = $densidad['agua'];

    if ($Pw <= 0) return null;

    // 🧱 Masa de suelo seco
    $Ms = $p2 - $p1;
    if ($Ms <= 0) return null;

    // 💧 Masa total (balón + agua + suelo)
    $Mtotal = $p3 - $p1;
    if ($Mtotal <= 0) return null;

    // 📦 Volumen total ocupado (agua + suelo)
    $Vtotal = $Mtotal / $Pw;

    // 🧪 Volumen del suelo (desplazado)
    $Vs = $numero_balon - $Vtotal;

    // 🔥 Validación crítica
    if ($Vs <= 0) return null;

    // 🪨 Densidad de partículas
    $Dp = $Ms / $Vs;

    // 🎯 Validación tipo laboratorio (opcional)
    if ($Dp < 2 || $Dp > 3) {
        // puedes comentar esto si no quieres filtrar
        // return null;
    }

    return round($Dp, 4);
}
}
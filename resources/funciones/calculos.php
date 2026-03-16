<?php

/*  
 Flujo final del cálculo
 ------------------------
Lecturas hidrómetro
        ↓
Corrección por blanco (Cn)
        ↓
Concentración inicial (C0)
        ↓
Porcentaje suspensión (Pn)
        ↓
Profundidad efectiva (hn)
        ↓
Temperatura → viscosidad
        ↓
Viscosidad → factor N
        ↓
Diámetro partícula (Xn)
        ↓
Interpolación logarítmica
        ↓
P50 y P2
        ↓
%Arcilla %Limo %Arena
 
*/


// Corrección por blanco
function correccion_blanco($R, $RL){
    return $R - $RL;
}

// Concentración inicial
function concentracion_inicial($Ms, $volumen){
    //$volumen = 1; // L
    return $Ms / $volumen;
}

// Porcentaje en suspensión
function porcentaje_suspension($Cn, $C0){
    return ($Cn / $C0) * 100;
}

// Profundidad efectiva
function profundidad_efectiva($R){
    return (0.164 * $R) + 16.3;
}

// Obtener viscosidad desde la tabla
function obtener_viscosidad($conn, $temperatura){

    $sql = "
        SELECT viscosidad_h2o_hmp_poise
        FROM trn_viscosidad_temperatura
        ORDER BY ABS(temperatura_c - ?)
        LIMIT 1
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("d",$temperatura);
    $stmt->execute();

    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    return $row['viscosidad_h2o_hmp_poise'];
}

// Obtener factor N
function obtener_factor_n($conn, $viscosidad_cp){

    $sql = "
        SELECT factor_n
        FROM trn_factor_sedimentacion
        ORDER BY ABS(viscosidad_cp - ?)
        LIMIT 1
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("d",$viscosidad_cp);
    $stmt->execute();

    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    return $row['factor_n'];
}

// Diámetro equivalente de partícula
function diametro_particula($N, $h, $t){

    return sqrt((30 * $N * $h) / $t);

}

// Interpolación logarítmica
function interpolacion_log($P1,$P2,$X1,$X2,$valor){

    $m = (log10($P2) - log10($P1)) / ($X2 - $X1);
    $b = log10($P2) - ($m * $X2);

    return pow(10, ($m * $valor) + $b);
}

// Función final (calcular textura)
function calcular_textura_suelo(
    $conn,
    $Ms,
    $R,$RL,
    $Temp,
    $Tiempo
){

    $result = [];

    $C0 = concentracion_inicial($Ms);

    for($i=0;$i<4;$i++){

        $Cn = correccion_blanco($R[$i],$RL[$i]);

        $Pn = porcentaje_suspension($Cn,$C0);

        $h = profundidad_efectiva($R[$i]);

        $mu = obtener_viscosidad($conn,$Temp[$i]);

        $mu_cp = $mu * 100; // conversión poise → centipoise

        $N = obtener_factor_n($conn,$mu_cp);

        $X = diametro_particula($N,$h,$Tiempo[$i]);

        $result[]=[
            'C'=>$Cn,
            'P'=>$Pn,
            'h'=>$h,
            'mu'=>$mu,
            'N'=>$N,
            'X'=>$X
        ];
    }

    $P1=$result[0]['P'];
    $P2=$result[1]['P'];
    $P3=$result[2]['P'];
    $P4=$result[3]['P'];

    $X1=$result[0]['X'];
    $X2=$result[1]['X'];
    $X3=$result[2]['X'];
    $X4=$result[3]['X'];

    $P50 = interpolacion_log($P1,$P2,$X1,$X2,50);
    $P2  = interpolacion_log($P3,$P4,$X3,$X4,2);

    $arcilla = $P2;
    $arena = 100 - $P50;
    $limo = 100 - $arcilla - $arena;

    return [
        "arcilla"=>$arcilla,
        "limo"=>$limo,
        "arena"=>$arena
    ];

}


?>

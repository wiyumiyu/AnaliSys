<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DensidadAparenteController extends Controller {

    /* ===============================
     * LISTADO DE ARCHIVOS
     * =============================== */
    public function archivos(Request $request) {
        $anio = $request->get('anio', date('Y'));

        // QUEMADO POR AHORA
        $archivos = [
            (object) [
                'id_archivo' => 1,
                'archivo' => 'DA-2026-001.xls',
                'fecha' => '2026-01-20',
                'analista' => 'Juan Pérez'
            ]
        ];

        return view(
            'ingreso_datos.densidad_aparente.index',
            compact('archivos', 'anio')
        );
    }

    /* ===============================
     * LISTADO DE MUESTRAS POR ARCHIVO
     * =============================== */
    public function muestras($idArchivo) {

        // QUEMADO POR AHORA
        $archivo = 'DA-2026-001';

        $muestras = [
        (object) [
            'id' => 1,
            'idlab' => 'LAB-010',
            'rep' => 1,
            'material' => 'Suelo arcilloso',
            'metodo' => 'ASTM',
            'tipomuestra' => 'Disturbada',
            'longitud' => 10,
            'diametrointerno' => 5,
            'areatransversal' => 19.6,
            'volumen' => 196,
            'temperaturaaire' => 24,
            'promedio' => 1.35,
            'desvEst' => 0.02
    ]
];


        return view(
            'ingreso_datos.densidad_aparente.muestras',
            compact('muestras', 'archivo', 'idArchivo')
        );
    }

    /* ===============================
     * EDITAR MUESTRA
     * =============================== */
    public function edit($id) {

        // QUEMADO POR AHORA
        $muestra = (object) [
        'id' => $id,
        'idlab' => 'LAB-010',
        'rep' => 1,
        'material' => 'Suelo arcilloso',
        'metodo' => 'ASTM',
        'tipomuestra' => 'Disturbada',
        'longitud' => 10,
        'diametrointerno' => 5,
        'areatransversal' => 19.6,
        'volumen' => 196,
        'temperaturaaire' => 24,
        'resultado' => 1.35
    ];

        return view(
            'ingreso_datos.densidad_aparente.editar',
            compact('muestra')
        );
    }

    public function update(Request $request, $id) {
        // SP
        return redirect()
            ->back()
            ->with('success', 'Muestra de densidad aparente actualizada correctamente');
    }
}

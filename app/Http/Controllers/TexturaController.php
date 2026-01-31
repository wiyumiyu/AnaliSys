<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TexturaController extends Controller {
    /* ===============================
     * LISTADO DE ARCHIVOS
     * =============================== */

    public function archivos(Request $request) {
        $anio = $request->get('anio', date('Y'));

        // 🔥 QUEMADO POR AHORA (luego SP)
        $archivos = [
            (object) [
                'id_archivo' => 1,
                'archivo' => 'PA-2026-001.xls',
                'fecha' => '2026-01-15',
                'analista' => 'Juan Pérez'
            ]
        ];

        return view(
                'ingreso_datos.textura.indextextura',
                compact('archivos', 'anio')
        );
    }

    /* ===============================
     * LISTADO DE MUESTRAS POR LOTE
     * =============================== */

    public function muestras($idArchivo) {
        // 🔥 QUEMADO POR AHORA
        $archivo = 'PA-2026-001';

        $muestras = [
            (object) [
                'id' => 1,
                'idlab' => 'LAB-001',
                'rep' => 1,
                'material' => 'Suelo franco',
                'metodo' => 'ASTM',
                'tipomuestra' => 'Indisturbada',
                'longitud' => 10.5,
                'diametrointerno' => 5.0,
                'areatransversal' => 19.63,
                'volumen' => 196.3,
                'temperaturaaire' => 25,
                'promedio' => null,
                'desvEst' => null
            ]
        ];

        return view(
                'ingreso_datos.textura.muestras',
                compact('muestras', 'archivo', 'idArchivo')
        );
    }

    /* ===============================
     * EDITAR MUESTRA
     * =============================== */

    public function edit($id) {
        // 🔥 QUEMADO POR AHORA
        $muestra = (object) [
                    'id' => $id,
                    'idlab' => 'LAB-001',
                    'rep' => 1,
                    'material' => 'Suelo franco',
                    'metodo' => 'ASTM',
                    'tipomuestra' => 'Indisturbada',
                    'longitud' => 10.5,
                    'diametrointerno' => 5.0,
                    'areatransversal' => 19.63,
                    'volumen' => 196.3,
                    'temperaturaaire' => 25
        ];

        return view(
                'ingreso_datos.textura.edit',
                compact('muestra')
        );
    }

    public function update(Request $request, $id) {
        // luego aquí va el SP
        return redirect()
                        ->back()
                        ->with('success', 'Muestra actualizada correctamente');
    }
}

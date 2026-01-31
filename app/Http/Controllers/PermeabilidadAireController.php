<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermeabilidadAireController extends Controller {
    /* ===============================
     * LISTADO DE LOTES
     * =============================== */

    public function lotes(Request $request) {
        $anio = $request->get('anio', date('Y'));

        // 🔥 QUEMADO POR AHORA (luego SP)
        $lotes = [
            (object) [
                'id_lote' => 1,
                'lote' => 'PA-2026-001',
                'fecha' => '2026-01-15',
                'analista' => 'Juan Pérez'
            ]
        ];

        return view(
                'ingreso_datos.permeabilidad_aire.index',
                compact('lotes', 'anio')
        );
    }

    /* ===============================
     * LISTADO DE MUESTRAS POR LOTE
     * =============================== */

    public function muestras($idLote) {
        // 🔥 QUEMADO POR AHORA
        $lote = 'PA-2026-001';

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
                'ingreso_datos.permeabilidad_aire.muestras',
                compact('muestras', 'lote', 'idLote')
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
                'ingreso_datos.permeabilidad_aire.edit',
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

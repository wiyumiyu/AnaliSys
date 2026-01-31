<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TexturaController extends Controller {
    /* ===============================
     * LISTADO DE ARCHIVOS
     * =============================== */

    public function archivos(Request $request) {
        $periodo = $request->get('periodo', date('Y'));

        $archivos = DB::select('CALL sp_listar_textura_por_periodo(?)', [$periodo]);
        return view(
                'ingreso_datos.textura.index',
                compact('archivos', 'periodo')
        );
    }

    /* ===============================
     * LISTADO DE MUESTRAS POR LOTE
     * =============================== */

    public function muestras($idArchivo) {
        // nombre del archivo (luego lo sacas de BD)
        $archivo = 'PA-2026-001';

        $muestras = DB::select(
                'CALL sp_listar_muestras_textura_detalle(?)',
                [$idArchivo]
        );

        return view(
                'ingreso_datos.textura.muestras',
                compact('muestras', 'archivo', 'idArchivo')
        );
    }

    /* ===============================
     * EDITAR MUESTRA
     * =============================== */

    public function edit($id) {
        $muestra = collect(
                DB::select('CALL sp_obtener_muestra_textura(?)', [$id])
                )->first();

        $resultados = DB::select(
                'CALL sp_listar_resultados_textura_por_muestra(?)',
                [$id]
        );

        return view(
                'ingreso_datos.textura.editar',
                compact('muestra', 'resultados')
        );
    }
public function update(Request $request, $id)
{
    // obtener id_textura antes de actualizar
    $muestra = collect(
        DB::select('CALL sp_obtener_muestra_textura(?)', [$id])
    )->first();

    // actualizar muestra
    DB::statement(
        'CALL sp_actualizar_muestra_textura(?,?,?,?,?,?)',
        [
            $id,
            $request->rep,
            $request->material,
            $request->tipo,
            $request->posicion,
            $request->estado
        ]
    );

    // actualizar resultados
    if ($request->has('resultados')) {
        foreach ($request->resultados as $idResultado => $valor) {
            DB::statement(
                'CALL sp_actualizar_resultado_textura(?,?)',
                [$idResultado, $valor]
            );
        }
    }

    // 👉 REDIRECCIÓN AL LISTADO DE MUESTRAS
    return redirect()
        ->route('textura.muestras', $muestra->id_textura)
        ->with('success', 'Muestra actualizada correctamente');
}


    public function toggleEstado($id) {
        DB::statement(
                'CALL sp_toggle_estado_muestra_textura(?)',
                [$id]
        );

        return redirect()
                        ->back()
                        ->with('success', 'Estado de la muestra actualizado correctamente');
    }

    public function destroy($id) {
        DB::statement('CALL sp_eliminar_muestra_textura(?)', [$id]);

        return redirect()
                        ->back()
                        ->with('success', 'Muestra eliminada correctamente');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DensidadAparenteController extends Controller
{
    /* ===============================
     * LISTADO DE ARCHIVOS
     * =============================== */
    public function archivos(Request $request)
    {
        $anio = $request->get('anio', date('Y'));

        $archivos = DB::select(
            'CALL sp_listar_densidad_aparente_por_periodo(?)',
            [$anio]
        );

        return view(
            'ingreso_datos.densidad_aparente.index',
            compact('archivos', 'anio')
        );
    }

    /* ===============================
     * LISTADO DE MUESTRAS POR ARCHIVO
     * =============================== */
    public function muestras($idArchivo)
    {
        // nombre del archivo (luego puedes traerlo desde BD si quieres)
        $archivo = 'DA-2026-001';

        $muestras = DB::select(
            'CALL sp_listar_muestras_densidad_aparente_detalle(?)',
            [$idArchivo]
        );

        return view(
            'ingreso_datos.densidad_aparente.muestras',
            compact('muestras', 'archivo', 'idArchivo')
        );
    }

    /* ===============================
     * EDITAR MUESTRA
     * =============================== */
    public function edit($id)
    {
        $muestra = collect(
            DB::select(
                'CALL sp_obtener_muestra_densidad_aparente(?)',
                [$id]
            )
        )->first();

        $resultados = DB::select(
            'CALL sp_listar_resultados_densidad_aparente_por_muestra(?)',
            [$id]
        );

        return view(
            'ingreso_datos.densidad_aparente.editar',
            compact('muestra', 'resultados')
        );
    }

    /* ===============================
     * ACTUALIZAR MUESTRA
     * =============================== */
    public function update(Request $request, $id)
    {
        // obtener id_densidad_aparente antes de actualizar
        $muestra = collect(
            DB::select(
                'CALL sp_obtener_muestra_densidad_aparente(?)',
                [$id]
            )
        )->first();

        // actualizar muestra
        DB::statement(
            'CALL sp_actualizar_muestra_densidad_aparente(?,?,?,?,?,?)',
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
                    'CALL sp_actualizar_resultado_densidad_aparente(?,?)',
                    [$idResultado, $valor]
                );
            }
        }

        // redirigir al listado de muestras
        return redirect()
            ->route(
                'densidad_aparente.muestras',
                $muestra->id_densidad_aparente
            )
            ->with('success', 'Muestra actualizada correctamente');
    }

    /* ===============================
     * TOGGLE ESTADO
     * =============================== */
    public function toggleEstado($id)
    {
        DB::statement(
            'CALL sp_toggle_estado_muestra_densidad_aparente(?)',
            [$id]
        );

        return redirect()
            ->back()
            ->with('success', 'Estado de la muestra actualizado correctamente');
    }

    /* ===============================
     * ELIMINAR MUESTRA
     * =============================== */
    public function destroy($id)
    {
        DB::statement(
            'CALL sp_eliminar_muestra_densidad_aparente(?)',
            [$id]
        );

        return redirect()
            ->back()
            ->with('success', 'Muestra eliminada correctamente');
    }

    public function destroyArchivo($id)
    {
        DB::statement(
            'CALL sp_eliminar_densidad_aparente(?)',
            [$id]
        );

        return redirect()
            ->route('densidad_aparente.index')
            ->with('success', 'Archivo eliminado correctamente');
    }

}

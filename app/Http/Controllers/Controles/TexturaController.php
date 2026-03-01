<?php

namespace App\Http\Controllers\Controles;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * ============================================================
 *  CONTROL DE TEXTURA
 * ============================================================
 *  Maneja la creación, listado y eliminación de controles
 *  asociados a archivos de textura.
 *
 *  Tipo fijo: 1 (TEXTURA)
 * ============================================================
 */
class TexturaController extends Controller {

    /**
     * ------------------------------------------------------------
     * Listado de controles por período
     * ------------------------------------------------------------
     */
    public function index(Request $request) {
        $periodo = $request->get('periodo', date('Y'));
        $tipo = 1;

        $consecutivosControles = DB::select(
                'CALL sp_listar_controles_por_anio(?,?)',
                [$periodo, $tipo]
        );
        $archivosDisponibles = DB::select(
                'CALL sp_traer_archivos_textura(?)',
                [$periodo]
        );

        $siguienteConsecutivo = collect(DB::select(
                        'CALL sp_traer_consecutivo_controles(?,?)',
                        [$tipo, $periodo]
                ))->first();

        return view('controles.textura.index', compact(
                        'consecutivosControles',
                        'periodo',
                        'archivosDisponibles',
                        'siguienteConsecutivo'
                ));
    }

    /**
     * ------------------------------------------------------------
     * Obtener consecutivo dinámicamente (AJAX)
     * ------------------------------------------------------------
     */
    public function traerConsecutivo(Request $request) {
        $tipo = 1;
        $periodo = $request->periodo;

        $consecutivo = collect(DB::select(
                        'CALL sp_traer_consecutivo(?,?)',
                        [$tipo, $periodo]
                ))->first();

        return response()->json($consecutivo);
    }

    /**
     * ------------------------------------------------------------
     * Guardar nuevo control
     * ------------------------------------------------------------
     */
    public function guardarControl(Request $request) {
        $request->validate([
            'anio' => 'required|integer',
            'consecutivo' => 'required|integer|min:1',
            'archivos' => 'required|array|min:1'
        ]);

        $listaArchivos = implode(',', $request->archivos);

        try {

            DB::select(
                    'CALL sp_guardar_control(?, ?, ?, ?, ?)',
                    [
                        1,
                        $request->anio,
                        $request->consecutivo,
                        session('id_persona'),
                        $listaArchivos
                    ]
            );

            return redirect()
                            ->route('controlTextura.index')
                            ->with('success', 'Control creado correctamente.');
        } catch (\Exception $e) {

            return back()
                            ->withInput()
                            ->with('error', $e->getMessage());
        }
    }

    /**
     * ------------------------------------------------------------
     * Eliminar control
     * ------------------------------------------------------------
     */
    public function destroy($id) {
        try {

            DB::statement('CALL sp_eliminar_control(?)', [$id]);

            return redirect()
                            ->route('controlTextura.index')
                            ->with('success', 'Control eliminado correctamente.');
        } catch (\Exception $e) {

            return back()
                            ->with('error', 'No se pudo eliminar el control.');
        }
    }

    public function guardarComentario(Request $request, $id) {
        $accion = $request->accion;

        if ($accion === 'aprobar') {

            DB::statement('CALL sp_agregar_comentario_control(?, ?, ?, ?)', [
                $id,
                session('id_persona'),
                null,
                1
            ]);
        } else {

            DB::statement('CALL sp_agregar_comentario_control(?, ?, ?, ?)', [
                $id,
                session('id_persona'),
                $request->comentario,
                0
            ]);
        }

        return back();
    }

    public function graficos($id) {
        $archivos = collect(DB::select(
                        'CALL sp_traer_archivos_textura_por_control(?)',
                        [$id]
                ));

        $muestras = collect(DB::select(
                        'CALL sp_traer_ids_muestras_textura_por_control(?)',
                        [$id]
                ));

        // 🔥 Agrupar SOLO por archivo
        $porArchivo = $muestras->groupBy('id_archivo');

        $comentarios = collect(DB::select(
                        'CALL sp_traer_comentarios_control(?)',
                        [$id]
                ));
        $valoresBlanco = collect(DB::select(
                        'CALL sp_traer_valores_blanco_por_control_textura(?)',
                        [$id]
                ));

        $mapaAnalisis = [
            2 => 'R1',
            3 => 'R2',
            4 => 'R3',
            5 => 'R4',
            6 => 'Temp1',
            7 => 'Temp2',
            8 => 'Temp3',
            9 => 'Temp4',
            10 => 'Tiempo1',
            11 => 'Tiempo2',
            12 => 'Tiempo3',
            13 => 'Tiempo4',
        ];

// Inicializar todos los gráficos vacíos
        $datosGraficos = [
            'R1' => [], 'R2' => [], 'R3' => [], 'R4' => [],
            'Temp1' => [], 'Temp2' => [], 'Temp3' => [], 'Temp4' => [],
            'Tiempo1' => [], 'Tiempo2' => [], 'Tiempo3' => [], 'Tiempo4' => []
        ];

        foreach ($valoresBlanco as $v) {

            if (isset($mapaAnalisis[$v->id_analisis])) {

                $clave = $mapaAnalisis[$v->id_analisis];

                $datosGraficos[$clave][] = (float) $v->resultado;
            }
        }
        return view('controles.textura.graficos', compact(
                        'archivos',
                        'porArchivo',
                        'comentarios',
                        'datosGraficos'
                ));
    }
}

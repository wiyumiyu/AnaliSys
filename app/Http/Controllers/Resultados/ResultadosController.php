<?php

namespace App\Http\Controllers\Resultados;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResultadosController extends Controller {

    /**
     * ------------------------------------------------------------
     * Listado de resultados por período
     * ------------------------------------------------------------
     */
    public function index(Request $request) {
        $periodo = $request->get('periodo', date('Y'));

        $rows = DB::select(
                'CALL sp_listar_resultados_por_anio(?)',
                [$periodo]
        );

        // Agrupar archivos por resultado (igual que hiciste antes)
        $resultados = collect($rows)
                ->groupBy('id')
                ->map(function ($items) {

                    $first = $items->first();

                    return (object) [
                                'id' => $first->id,
                                'consecutivo' => $first->consecutivo,
                                'fecha' => $first->fecha,
                                'analista' => $first->analista,
                                'archivos' => $items
                                        ->filter(fn($item) => !is_null($item->archivo))
                                        ->map(function ($item) {
                                            return (object) [
                                                        'tipo' => $item->tipo,
                                                        'archivo' => $item->archivo
                                            ];
                                        })
                                        ->values()
                    ];
                })
                ->values();

        $archivosDisponibles = DB::select(
                'CALL sp_listar_todos_los_archivos(?)',
                [$periodo]
        );

        $siguienteConsecutivo = collect(DB::select(
                                'CALL sp_traer_consecutivo_resultados(?)',
                                [$periodo]
                        ))->first()->siguiente_consecutivo ?? 1;

        return view('resultados.index', compact(
                        'resultados',
                        'periodo',
                        'archivosDisponibles',
                        'siguienteConsecutivo'
                ));
    }

    /**
     * ------------------------------------------------------------
     * Guardar resultado
     * ------------------------------------------------------------
     */
    public function guardarResultado(Request $request) {
        $request->validate([
            'consecutivo' => 'required|integer|min:1',
            'archivos' => 'required|array|min:1'
        ]);

        $listaArchivos = implode(',', $request->archivos);

        try {

            DB::statement(
                    'CALL sp_guardar_resultado(?,?,?)',
                    [
                        $request->consecutivo,
                        session('id_persona'),
                        $listaArchivos
                    ]
            );

            return redirect()
                            ->route('resultados.index')
                            ->with('success', 'Resultado creado correctamente.');
        } catch (\Exception $e) {

            return back()
                            ->withInput()
                            ->with('error', $e->getMessage());
        }
    }

    /**
     * ------------------------------------------------------------
     * Eliminar resultado
     * ------------------------------------------------------------
     */
    public function destroy($id) {
        try {

            DB::statement(
                    'CALL sp_eliminar_resultado(?)',
                    [$id]
            );

            return redirect()
                            ->route('resultados.index')
                            ->with('success', 'Resultado eliminado correctamente.');
        } catch (\Exception $e) {

            return back()
                            ->with('error', 'No se pudo eliminar el resultado.');
        }
    }

    /**
     * ------------------------------------------------------------
     * Vista detalle resultado
     * ------------------------------------------------------------
     */
    public function show($id) {

        $archivos = collect(DB::select(
                        'CALL sp_traer_archivos_por_resultado(?)',
                        [$id]
                ));

        $comentarios = collect(DB::select(
                        'CALL sp_traer_comentarios_resultado(?)',
                        [$id]
                ));

        return view('resultados.vista', compact(
                        'archivos',
                        'comentarios'
                ));
    }

    /**
     * ------------------------------------------------------------
     * Agregar comentario o aprobar
     * ------------------------------------------------------------
     */
    public function guardarComentario(Request $request, $id) {

        $accion = $request->accion;

        if ($accion === 'aprobar') {

            DB::statement(
                    'CALL sp_agregar_comentario_resultado(?,?,?,?)',
                    [
                        $id,
                        session('id_persona'),
                        null,
                        1
                    ]
            );
        } else {

            DB::statement(
                    'CALL sp_agregar_comentario_resultado(?,?,?,?)',
                    [
                        $id,
                        session('id_persona'),
                        $request->comentario,
                        0
                    ]
            );
        }

        return back();
    }
}

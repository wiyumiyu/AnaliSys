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
class TexturaController extends Controller
{
    /**
     * ------------------------------------------------------------
     * Listado de controles por período
     * ------------------------------------------------------------
     */
    public function index(Request $request)
    {
        $periodo = $request->get('periodo', date('Y'));
        $tipo = 1;

        $consecutivosControles = DB::select(
            'CALL sp_listar_controles_por_anio(?,?)',
            [$periodo, $tipo]
        );

        $archivosDisponibles = DB::select(
            'CALL sp_traer_archivos_textura()'
        );

        $siguienteConsecutivo = collect(DB::select(
            'CALL sp_traer_consecutivo(?,?)',
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
    public function traerConsecutivo(Request $request)
    {
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
    public function store(Request $request)
    {
        $request->validate([
            'anio'        => 'required|integer',
            'consecutivo' => 'required|integer|min:1',
            'archivos'    => 'required|array|min:1'
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
    public function destroy($id)
    {
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
}
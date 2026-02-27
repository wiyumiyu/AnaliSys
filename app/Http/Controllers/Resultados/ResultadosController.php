<?php

namespace App\Http\Controllers\Resultados;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResultadosController extends Controller
{
    /**
     * ------------------------------------------------------------
     * Listado de resultados por período
     * ------------------------------------------------------------
     */
    public function index(Request $request)
    {
        $periodo = $request->get('periodo', date('Y'));

        $resultados = DB::select(
            'CALL sp_listar_resultados_por_anio(?)',
            [$periodo]
        );

        $archivosDisponibles = DB::select(
            'CALL sp_listar_todos_los_archivos(?)',
            [$periodo]
        );

        return view('resultados.index', compact(
            'resultados',
            'periodo',
            'archivosDisponibles'
        ));
    }

    /**
     * ------------------------------------------------------------
     * Guardar nuevo resultado
     * ------------------------------------------------------------
     */
    public function guardarResultado(Request $request)
    {
        $request->validate([
            'tipo' => 'required|integer',
            'consecutivo' => 'required|integer|min:1',
            'archivos' => 'required|array|min:1'
        ]);

        $listaArchivos = implode(',', $request->archivos);

        try {

            DB::select(
                'CALL sp_guardar_resultado(?,?,?,?)',
                [
                    $request->tipo,
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
    public function destroy($id)
    {
        try {

            DB::statement('CALL sp_eliminar_resultado(?)', [$id]);

            return redirect()
                ->route('resultados.index')
                ->with('success', 'Resultado eliminado correctamente.');

        } catch (\Exception $e) {

            return back()
                ->with('error', 'No se pudo eliminar el resultado.');
        }
    }
}
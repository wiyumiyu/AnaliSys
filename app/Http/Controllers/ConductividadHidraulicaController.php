<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ConductividadHidraulicaController extends Controller
{
    /* ===============================
     * LISTADO DE ARCHIVOS
     * =============================== */
    public function archivos(Request $request)
    {
        $anio = $request->get('anio', date('Y'));

        $archivos = DB::select(
            'CALL sp_listar_conductividad_hidraulica_por_periodo(?)',
            [$anio]
        );

        return view(
            'ingreso_datos.conductividad_hidraulica.index',
            compact('archivos', 'anio')
        );
    }

    /* ===============================
     * LISTADO DE MUESTRAS POR ARCHIVO
     * =============================== */
    public function muestras($idArchivo)
    {
        $archivo = 'CH-2026-001'; // opcional, luego puedes traerlo desde BD

        $muestras = DB::select(
            'CALL sp_listar_muestras_conductividad_hidraulica_detalle(?)',
            [$idArchivo]
        );

        return view(
            'ingreso_datos.conductividad_hidraulica.muestras',
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
                'CALL sp_obtener_muestra_conductividad_hidraulica(?)',
                [$id]
            )
        )->first();

        $resultados = DB::select(
            'CALL sp_listar_resultados_conductividad_hidraulica_por_muestra(?)',
            [$id]
        );

        return view(
            'ingreso_datos.conductividad_hidraulica.editar',
            compact('muestra', 'resultados')
        );
    }

    /* ===============================
     * ACTUALIZAR MUESTRA
     * =============================== */
    public function update(Request $request, $id)
    {
        $muestra = collect(
            DB::select(
                'CALL sp_obtener_muestra_conductividad_hidraulica(?)',
                [$id]
            )
        )->first();

        DB::statement(
            'CALL sp_actualizar_muestra_conductividad_hidraulica(?,?,?,?,?,?)',
            [
                $id,
                $request->rep,
                $request->material,
                $request->tipo,
                $request->posicion,
                $request->estado
            ]
        );

        if ($request->has('resultados')) {
            foreach ($request->resultados as $idResultado => $valor) {
                DB::statement(
                    'CALL sp_actualizar_resultado_conductividad_hidraulica(?,?)',
                    [$idResultado, $valor]
                );
            }
        }

        return redirect()
            ->route(
                'conductividad_hidraulica.muestras',
                $muestra->id_conductividad_hidraulica
            )
            ->with('success', 'Muestra actualizada correctamente');
    }

    /* ===============================
     * TOGGLE ESTADO
     * =============================== */
    public function toggleEstado($id)
    {
        DB::statement(
            'CALL sp_toggle_estado_muestra_conductividad_hidraulica(?)',
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
            'CALL sp_eliminar_muestra_conductividad_hidraulica(?)',
            [$id]
        );

        return redirect()
            ->back()
            ->with('success', 'Muestra eliminada correctamente');
    }

    /* ===============================
     * ELIMINAR ARCHIVO
     * =============================== */
    public function destroyArchivo($id)
    {
        DB::statement(
            'CALL sp_eliminar_conductividad_hidraulica(?)',
            [$id]
        );

        return redirect()
            ->route('conductividad_hidraulica.index')
            ->with('success', 'Archivo eliminado correctamente');
    }

    /* ===============================
     * IMPORTAR EXCEL
     * =============================== */
    public function importar(Request $request)
    {
        $request->validate([
            'archivo' => 'required|file|mimes:xlsx,xls'
        ]);

        DB::beginTransaction();

        try {

            $idConductividad = DB::table('trn_conductividad_hidraulica')->insertGetId([
                'periodo'  => date('Y'),
                'archivo'  => $request->file('archivo')->getClientOriginalName(),
                'fecha'    => now(),
                'analista' => session('id_persona')
            ]);

            $analisisMap = DB::table('trn_analisis')
                ->where('origen', 'CONDUCTIVIDAD_HIDRAULICA')
                ->pluck('id', 'siglas')
                ->toArray();

            $spreadsheet = IOFactory::load(
                $request->file('archivo')->getPathname()
            );

            $rows = $spreadsheet
                ->getActiveSheet()
                ->toArray(null, true, true, true);

            $i = 1;
            foreach ($rows as $fila => $row) {

                if ($fila < 3 || empty($row['A'])) {
                    continue;
                }

                $idMuestra = DB::table('trn_conductividad_hidraulica_muestras')->insertGetId([
                    'id_conductividad_hidraulica' => $idConductividad,
                    'idlab'     => $row['A'],
                    'rep'       => $row['B'],
                    'material'  => 1,
                    'tipo'      => 1,
                    'posicion'  => $i,
                    'estado'    => 1,
                    'ri'        => 0
                ]);

                $valores = [
                    'longitud_muestra'                    => $row['C'],
                    'diametro_interno'                    => $row['D'],
                    'area_transversal'                    => $row['E'],
                    'temperatura_agua'                    => $row['F'],
                    'condicion_compactacion_saturacion'   => $row['G'],
                ];

                foreach ($valores as $sigla => $resultado) {
                    if (!isset($analisisMap[$sigla])) {
                        continue;
                    }

                    DB::table('trn_conductividad_hidraulica_resultados')->insert([
                        'id_conductividad_hidraulica_muestras' => $idMuestra,
                        'id_analisis' => $analisisMap[$sigla],
                        'resultado'   => $resultado,
                        'estado'      => 1
                    ]);
                }

                $i++;
            }

            DB::commit();

            return redirect()
                ->route('conductividad_hidraulica.index')
                ->with('success', 'Archivo importado correctamente');

        } catch (\Throwable $e) {

            DB::rollBack();

            return back()->withErrors(
                'Error al importar: ' . $e->getMessage()
            );
        }
    }
}
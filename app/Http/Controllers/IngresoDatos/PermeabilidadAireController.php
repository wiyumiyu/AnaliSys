<?php

namespace App\Http\Controllers\IngresoDatos;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Http\Controllers\Controller;

class PermeabilidadAireController extends Controller {
    /* ===============================
     * LISTADO DE ARCHIVOS
     * =============================== */

    public function archivos(Request $request) {
        $anio = $request->get('anio', date('Y'));
        $archivos = DB::select(
                'CALL sp_listar_permeabilidad_aire_por_periodo(?)',
                [$anio]
        );

        return view(
                'ingreso_datos.permeabilidad_aire.index',
                compact('archivos', 'anio')
        );
    }

    /* ===============================
     * LISTADO DE MUESTRAS POR ARCHIVO
     * =============================== */

    public function muestras($idArchivo) {
        // nombre del archivo (luego puedes traerlo desde BD si quieres)
        $archivo = 'PA-2024-001';

        $archivo = DB::table('trn_permeabilidad_aire')
                ->where('id', $idArchivo)
                ->value('archivo');
        $muestras = DB::select(
                'CALL sp_listar_muestras_permeabilidad_aire_detalle(?)',
                [$idArchivo]
        );

        return view(
                'ingreso_datos.permeabilidad_aire.muestras',
                compact('muestras', 'archivo', 'idArchivo')
        );
    }

    /* ===============================
     * EDITAR MUESTRA
     * =============================== */

    public function edit($id) {
        $muestra = collect(
                DB::select(
                        'CALL sp_obtener_muestra_permeabilidad_aire(?)',
                        [$id]
                )
                )->first();

        $resultados = DB::select(
                'CALL sp_listar_resultados_permeabilidad_aire_por_muestra(?)',
                [$id]
        );

        return view(
                'ingreso_datos.permeabilidad_aire.editar',
                compact('muestra', 'resultados')
        );
    }

    /* ===============================
     * ACTUALIZAR MUESTRA
     * =============================== */

    public function update(Request $request, $id) {

        $muestra = collect(
                DB::select(
                        'CALL sp_obtener_muestra_permeabilidad_aire(?)',
                        [$id]
                )
                )->first();

        // actualizar muestra
        DB::statement(
                'CALL sp_actualizar_muestra_permeabilidad_aire(?,?,?,?,?,?)',
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
                        'CALL sp_actualizar_resultado_permeabilidad_aire(?,?)',
                        [$idResultado, $valor]
                );
            }
        }

        // redirigir al listado de muestras
        return redirect()
                        ->route(
                                'permeabilidad_aire.muestras',
                                $muestra->id_permeabilidad_aire
                        )
                        ->with('success', 'Muestra actualizada correctamente');
    }

    /* ===============================
     * TOGGLE ESTADO
     * =============================== */

    public function toggleEstado($id) {
        DB::statement(
                'CALL sp_toggle_estado_muestra_permeabilidad_aire(?)',
                [$id]
        );

        return redirect()
                        ->back()
                        ->with('success', 'Estado de la muestra actualizado correctamente');
    }

    /* ===============================
     * ELIMINAR MUESTRA
     * =============================== */

    public function destroy($id) {
        DB::statement(
                'CALL sp_eliminar_muestra_permeabilidad_aire(?)',
                [$id]
        );

        return redirect()
                        ->route('permeabilidad_aire.index')
                        ->with('success', 'Archivo eliminado correctamente');
    }

    public function destroyArchivo($id) {
        DB::statement(
                'CALL sp_eliminar_permeabilidad_aire(?)',
                [$id]
        );
    }

    public function importar(Request $request) {
        $request->validate([
            'archivo' => 'required|file|mimes:xlsx,xls'
        ]);

        DB::beginTransaction();

        try {

            /* ==== Crear archivo de permeabilidad aire ==== */
            $idPermeabilidadAire = DB::table('trn_permeabilidad_aire')->insertGetId([
                'periodo' => date('Y'),
                'archivo' => $request->file('archivo')->getClientOriginalName(),
                'fecha' => now(),
                'analista' => session('id_persona')
            ]);

            /* ==== Mapa de análisis PERMEABILIDAD DEL AIRE ==== */

            $analisisMap = DB::table('trn_analisis')
                    ->where('origen', 'PERMEABILIDAD AIRE')
                    ->pluck('id', 'siglas')
                    ->toArray();

            /* ==== Leer Excel ===== */
            $spreadsheet = IOFactory::load(
                    $request->file('archivo')->getPathname()
            );

            $rows = $spreadsheet
                    ->getActiveSheet()
                    ->toArray(null, true, true, true);

            /* ==== Recorrer filas (desde fila 3) ===== */
            $i = 1;
            $tipo = 1;
            foreach ($rows as $fila => $row) {

                if ($fila < 3) {
                    continue; // título y encabezados
                }

                if (empty($row['A'])) {
                    continue; // IDLab vacío
                }
                $tipo = 1;
                if (!is_numeric($row['A'])) {
                    $tipo = 2;
                }

                /* ===== Insert Muestra ===== */
                $idMuestra = DB::table('trn_permeabilidad_aire_muestras')->insertGetId([
                    'id_permeabilidad_aire' => $idPermeabilidadAire,
                    'idlab' => $row['A'],
                    'rep' => $row['B'],
                    'material' => 1, // placeholder
                    'tipo' => $tipo,
                    'posicion' => $i,
                    'estado' => 1,
                    'ri' => 0
                ]);

                /* ===== Resultados ===== */
                $valores = [
                    'longitud_muestra' => $row['C'],
                    'diametro_interno' => $row['D'],
                    'area_transversal' => $row['E'],
                    'volumen_muestra' => $row['F'],
                    'temperatura_aire' => $row['G']
                ];

                foreach ($valores as $sigla => $resultado) {


                    if (!isset($analisisMap[$sigla])) {
                        continue;
                    }

                    DB::table('trn_permeabilidad_aire_resultados')->insert([
                        'id_permeabilidad_aire_muestras' => $idMuestra,
                        'id_analisis' => $analisisMap[$sigla],
                        'resultado' => $resultado,
                        'estado' => 1
                    ]);
                }
                $i += 1;
            }
            /* ==== Commit FINAL ==== */
            DB::commit();

            return redirect()
                            ->route('permeabilidad_aire.index')
                            ->with('success', 'Archivo importado correctamente');
        } catch (\Throwable $e) {

            DB::rollBack();

            return back()->withErrors(
                            'Error al importar: ' . $e->getMessage()
                    );
        }
    }
}

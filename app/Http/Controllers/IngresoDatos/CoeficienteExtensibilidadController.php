<?php

namespace App\Http\Controllers\IngresoDatos;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Http\Controllers\Controller;

class CoeficienteExtensibilidadController extends Controller {
    /* ===============================
     * LISTADO DE ARCHIVOS
     * =============================== */

    public function archivos(Request $request) {


        $anio = $request->get('anio', date('Y'));

        $archivos = DB::select(
                'CALL sp_listar_coeficiente_extensibilidad_por_periodo(?)',
                [$anio]
        );

        return view(
                'ingreso_datos.coeficiente_extensibilidad.index',
                compact('archivos', 'anio')
        );
    }

    /* ===============================
     * LISTADO DE MUESTRAS POR ARCHIVO
     * =============================== */

    public function muestras($idArchivo) {
        // nombre del archivo (luego puedes traerlo desde BD si quieres)
        $archivo = 'CE-2024-001';

        $muestras = DB::select(
                'CALL sp_listar_muestras_coeficiente_extensibilidad_detalle(?)',
                [$idArchivo]
        );

        return view(
                'ingreso_datos.coeficiente_extensibilidad.muestras',
                compact('muestras', 'archivo', 'idArchivo')
        );
    }

    /* ===============================
     * EDITAR MUESTRA
     * =============================== */

    public function edit($id) {
        $muestra = collect(
                DB::select(
                        'CALL sp_obtener_muestra_coeficiente_extensibilidad(?)',
                        [$id]
                )
                )->first();

        $resultados = DB::select(
                'CALL sp_listar_resultados_coeficiente_extensibilidad_por_muestra(?)',
                [$id]
        );

        return view(
                'ingreso_datos.coeficiente_extensibilidad.editar',
                compact('muestra', 'resultados')
        );
    }

    /* ===============================
     * ACTUALIZAR MUESTRA
     * =============================== */

    public function update(Request $request, $id) {
        DB::statement('SET @bitacora_usuario = ?', [session('id_persona') ?? 0]);
        DB::statement('SET @bitacora_ip = ?', [$request->ip() ?? 'UNKNOWN']);

        // obtener id_coeficiente_extensibilidad antes de actualizar
        $muestra = collect(
                DB::select(
                        'CALL sp_obtener_muestra_coeficiente_extensibilidad(?)',
                        [$id]
                )
                )->first();

        // actualizar muestra
        DB::statement(
                'CALL sp_actualizar_muestra_coeficiente_extensibilidad(?,?,?,?,?,?)',
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
                        'CALL sp_actualizar_resultado_coeficiente_extensibilidad(?,?)',
                        [$idResultado, $valor]
                );
            }
        }

        // redirigir al listado de muestras
        return redirect()
                        ->route(
                                'coeficiente_extensibilidad.muestras',
                                $muestra->id_coeficiente_extensibilidad
                        )
                        ->with('success', 'Muestra actualizada correctamente');
    }

    /* ===============================
     * TOGGLE ESTADO
     * =============================== */

    public function toggleEstado($id) {
        DB::statement(
                'CALL sp_toggle_estado_muestra_coeficiente_extensibilidad(?)',
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
                'CALL sp_eliminar_muestra_coeficiente_extensibilidad(?)',
                [$id]
        );

        return redirect()
                        ->back()
                        ->with('success', 'Muestra eliminada correctamente');
    }

    public function destroyArchivo($id) {
        DB::statement(
                'CALL sp_eliminar_coeficiente_extensibilidad(?)',
                [$id]
        );

        return redirect()
                        ->route('coeficiente_extensibilidad.index')
                        ->with('success', 'Archivo eliminado correctamente');
    }

    public function importar(Request $request) {
        $request->validate([
            'archivo' => 'required|file|mimes:xlsx,xls'
        ]);

        DB::beginTransaction();

        try {

            /* ==== Crear archivo ==== */
            $idCoeficienteExtensibilidad = DB::table('trn_coeficiente_extensibilidad')->insertGetId([
                'periodo' => date('Y'),
                'archivo' => $request->file('archivo')->getClientOriginalName(),
                'fecha' => now(),
                'analista' => session('id_persona')
            ]);

            /* ==== MAPA CORREGIDO ==== */
            $analisisMap = DB::table('trn_analisis')
                    ->where('origen', 'COEFICIENTE_EXTENSIBILIDAD') // 👈 FIX
                    ->pluck('id', 'siglas')
                    ->toArray();

            /* ==== Leer Excel ==== */
            $spreadsheet = IOFactory::load(
                    $request->file('archivo')->getPathname()
            );

            $sheet = $spreadsheet->getActiveSheet();
            $rows = [];

            foreach ($sheet->getRowIterator() as $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);

                $fila = [];

                foreach ($cellIterator as $cell) {
                    $fila[] = $cell->getValue(); // 👈 VALOR REAL (NO FORMATEADO)
                }

                $rows[] = $fila;
            }
            //dd($rows);
            $i = 1;
            //dd($rows);
            foreach ($rows as $fila => $row) {

                // limpiar valores
                $idlab = trim($row['0']);
                $rep = trim($row['1']);

                // saltar encabezado o basura
                if (!is_numeric($idlab) || !is_numeric($rep)) {
                    continue;
                }

                $tipo = 1;

                /* ==== Muestra ==== */
                $idMuestra = DB::table('trn_coeficiente_extensibilidad_muestras')->insertGetId([
                    'id_coeficiente_extensibilidad' => $idCoeficienteExtensibilidad,
                    'idlab' => $idlab,
                    'rep' => $rep,
                    'material' => 1,
                    'tipo' => 1,
                    'posicion' => $i,
                    'estado' => 1, // 👈 SIEMPRE 1
                    'ri' => 0
                ]);

                /* ==== NUEVO MAPEO ==== */
                $valores = [
                    'altura_cilindro_od' => $row[2],
                    'diametro_cilindro_od' =>  $row[3],
                    'peso_cilindro_suelo_seco_od' =>  $row[4],
                    'peso_cilindro_vacio_od' =>  $row[5],
                    'altura_cilindro_33kpa' => $row[6],
                    'diametro_cilindro_33kpa' => $row[7],
                    'peso_cilindro_suelo_33kpa' =>  $row[8],
                    'peso_cilindro_vacio_33kpa' =>  $row[9],
                ];

                foreach ($valores as $sigla => $resultado) {

                    if (!isset($analisisMap[$sigla])) {
                        continue;
                    }

                    DB::table('trn_coeficiente_extensibilidad_resultados')->insert([
                        'id_coeficiente_extensibilidad_muestras' => $idMuestra,
                        'id_analisis' => $analisisMap[$sigla],
                        'resultado' => $resultado,
                        'estado' => 1
                    ]);
                }

                $i++;
            }

            DB::commit();

            return redirect()
                            ->route('coeficiente_extensibilidad.index')
                            ->with('success', 'Archivo importado correctamente');
        } catch (\Throwable $e) {

            DB::rollBack();

            return back()->withErrors(
                            'Error al importar: ' . $e->getMessage()
                    );
        }
    }
}

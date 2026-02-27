<?php

namespace App\Http\Controllers\IngresoDatos;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Http\Controllers\Controller;

class DensidadAparenteController extends Controller {
    /* ===============================
     * LISTADO DE ARCHIVOS
     * =============================== */

    public function archivos(Request $request) {
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

    public function muestras($idArchivo) {
        $archivo = DB::table('trn_densidad_aparente')
                ->where('id', $idArchivo)
                ->value('archivo');

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

    public function edit($id) {
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

    public function update(Request $request, $id) {

        DB::statement('SET @bitacora_usuario = ?', [session('id_persona') ?? 0]);
        DB::statement('SET @bitacora_ip = ?', [$request->ip() ?? 'UNKNOWN']);

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

    public function toggleEstado(Request $request, $id)
    {
        DB::statement('SET @bitacora_usuario = ?', [session('id_persona') ?? 0]);
        DB::statement('SET @bitacora_ip = ?', [$request->ip() ?? 'UNKNOWN']);

        DB::statement(
                'CALL sp_toggle_estado_muestra_densidad_aparente(?)',
                [$id]
        );

        return redirect()->back()->with('success', 'Estado actualizado correctamente');
     }

    /* ===============================
     * ELIMINAR MUESTRA
     * =============================== */

    public function destroy(Request $request, $id)
    {
        DB::statement('SET @bitacora_usuario = ?', [session('id_persona') ?? 0]);
        DB::statement('SET @bitacora_ip = ?', [$request->ip() ?? 'UNKNOWN']);

        DB::statement(
                'CALL sp_eliminar_muestra_densidad_aparente(?)',
                [$id]
        );

        return redirect()->back()->with('success', 'Muestra eliminada correctamente');
    }

    public function destroyArchivo(Request $request, $id) {
        
        DB::statement('SET @bitacora_usuario = ?', [session('id_persona') ?? 0]);
        DB::statement('SET @bitacora_ip = ?', [$request->ip() ?? 'UNKNOWN']);

        DB::statement(
                'CALL sp_eliminar_densidad_aparente(?)',
                [$id]
        );

        return redirect()
                        ->route('densidad_aparente.index')
                        ->with('success', 'Archivo eliminado correctamente');
    }

    public function importar(Request $request) {
        
        DB::statement('SET @bitacora_usuario = ?', [session('id_persona') ?? 0]);
        DB::statement('SET @bitacora_ip = ?', [$request->ip() ?? 'UNKNOWN']);

        $request->validate([
            'archivo' => 'required|file|mimes:xlsx,xls'
        ]);

        DB::beginTransaction();

        try {

            /* ===== Crear archivo ===== */
            $archivoResult = DB::select(
                    'CALL sp_crear_densidad_aparente(?,?,?)',
                    [
                        date('Y'),
                        $request->file('archivo')->getClientOriginalName(),
                        session('id_persona')
                    ]
            );

            $idDensidadAparente = $archivoResult[0]->id_generado;

            /* ===== Leer Excel ===== */
            $spreadsheet = IOFactory::load(
                    $request->file('archivo')->getPathname()
            );

            $rows = $spreadsheet
                    ->getActiveSheet()
                    ->toArray(null, true, true, true);

            $i = 1;

            foreach ($rows as $fila => $row) {

                if ($fila < 3)
                    continue;
                if (empty($row['A']))
                    continue;

                $tipo = is_numeric($row['A']) ? 1 : 2;

                /* ===== Insertar muestra ===== */
                $muestraResult = DB::select(
                        'CALL sp_insertar_muestra_densidad_aparente(?,?,?,?,?,?)',
                        [
                            $idDensidadAparente,
                            $row['A'],
                            $row['B'],
                            1,
                            $tipo,
                            $i
                        ]
                );

                $idMuestra = $muestraResult[0]->id_muestra;

                /* ===== Insertar resultados ===== */
                $valores = [
                    'altura' => $row['C'],
                    'diametro' => $row['D'],
                    'peso_cilindro_suelo' => $row['E'],
                    'peso_cilindro' => $row['F'],
                    'temperatura' => $row['G'],
                    'secado' => $row['H'],
                ];

                foreach ($valores as $sigla => $resultado) {

                    if ($resultado === null || $resultado === '') {
                        continue;
                    }

                    DB::statement(
                            'CALL sp_insertar_resultado_densidad_aparente(?,?,?)',
                            [
                                $idMuestra,
                                $sigla,
                                $resultado
                            ]
                    );
                }

                $i++;
            }

            DB::commit();

            return redirect()
                            ->route('densidad_aparente.index')
                            ->with('success', 'Archivo importado correctamente');
        } catch (\Throwable $e) {

            DB::rollBack();

            return back()->withErrors(
                            'Error al importar: ' . $e->getMessage()
                    );
        }
    }

}

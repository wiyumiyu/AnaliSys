<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

class EstabilidadAgregadosController extends Controller
{
    /* ===============================
     * LISTADO DE ARCHIVOS
     * =============================== */
    public function archivos(Request $request)
    {
        $anio = $request->get('anio', date('Y'));

        $archivos = DB::select(
            'CALL sp_listar_estabilidad_agregados_por_periodo(?)',
            [$anio]
        );

        return view(
            'ingreso_datos.estabilidad_agregados.index',
            compact('archivos', 'anio')
        );
    }

    /* ===============================
     * LISTADO DE MUESTRAS POR ARCHIVO
     * =============================== */
    public function muestras($idArchivo)
    {
        //Nombre del archivo
        $archivo = 'HG-2026-001';

        $muestras = DB::select(
            'CALL sp_listar_muestras_estabilidad_agregados_detalle(?)',
            [$idArchivo]
        );

        return view(
            'ingreso_datos.estabilidad_agregados.muestras',
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
                'CALL sp_obtener_muestra_estabilidad_agregados(?)',
                [$id]
            )
        )->first();

        $resultados = DB::select(
            'CALL sp_listar_resultados_estabilidad_agregados_por_muestra(?)',
            [$id]
        );

        return view(
            'ingreso_datos.estabilidad_agregados.editar',
            compact('muestra', 'resultados')
        );
    }

    /* ===============================
     * ACTUALIZAR MUESTRA
     * =============================== */
    public function update(Request $request, $id)
    {
        // obtener id_estabilidad_agregados antes de actualizar
        $muestra = collect(
            DB::select(
                'CALL sp_obtener_muestra_estabilidad_agregados(?)',
                [$id]
            )
        )->first();

        // actualizar muestra
        DB::statement(
            'CALL sp_actualizar_muestra_estabilidad_agregados(?,?,?,?,?,?)',
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
                    'CALL sp_actualizar_resultado_estabilidad_agregados(?,?)',
                    [$idResultado, $valor]
                );
            }
        }

        return redirect()
            ->route(
                'estabilidad_agregados.muestras',
                $muestra->id_estabilidad_agregados
            )
            ->with('success', 'Muestra actualizada correctamente');
    }

    /* ===============================
     * TOGGLE ESTADO
     * =============================== */
    public function toggleEstado($id)
    {
        DB::statement(
            'CALL sp_toggle_estado_muestra_estabilidad_agregados(?)',
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
            'CALL sp_eliminar_muestra_estabilidad_agregados(?)',
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
            'CALL sp_eliminar_estabilidad_agregados(?)',
            [$id]
        );

        return redirect()
            ->route('estabilidad_agregados.index')
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

            /* ==== Crear archivo de estabilidad de agregados ==== */
            $idEstabilidadAgregados = DB::table('trn_estabilidad_agregados')->insertGetId([
                'periodo'  => date('Y'),
                'archivo'  => $request->file('archivo')->getClientOriginalName(),
                'fecha'    => now(),
                'analista' => session('id_persona')
            ]);

            /* ==== Mapa de análisis Estabilidad de Agregados ==== */
            $analisisMap = DB::table('trn_analisis')
                ->where('origen', 'ESTABILIDAD_AGREGADOS')
                ->pluck('id', 'siglas')
                ->toArray();

            /* ==== Leer Excel ==== */
            $spreadsheet = IOFactory::load(
                $request->file('archivo')->getPathname()
            );

            $rows = $spreadsheet
                ->getActiveSheet()
                ->toArray(null, true, true, true);

            /* ==== Recorrer filas (desde fila 3) ==== */
            $i = 1;
            foreach ($rows as $fila => $row) {

                if ($fila < 3) continue;
                if (empty($row['A'])) continue;

                /* ==== Insert muestra ==== */
                $idMuestra = DB::table('trn_estabilidad_agregados_muestras')->insertGetId([
                    'id_estabilidad_agregados' => $idEstabilidadAgregados,
                    'idlab'     => $row['A'],
                    'rep'       => $row['B'],
                    'material'  => 1,
                    'tipo'      => 1,
                    'posicion'  => $i,
                    'estado'    => 1,
                    'ri'        => 0
                ]);

                /* ==== Resultados ==== */
                $valores = [
                    'peso_suelo_seco'                => $row['C'],
                    'peso_tamices'                   => $row['D'],
                    'temperatura'                    => $row['E'],
                    'humedad_ambiental'              => $row['F'],
                    'fecha_inicio'                   => $row['G'],
                ];

                foreach ($valores as $sigla => $resultado) {
                    if (!isset($analisisMap[$sigla])) continue;

                    DB::table('trn_estabilidad_agregados_resultados')->insert([
                        'id_estabilidad_agregados_muestra' => $idMuestra,
                        'id_analisis' => $analisisMap[$sigla],
                        'resultado'   => $resultado,
                        'estado'      => 1
                    ]);
                }

                $i++;
            }

            DB::commit();

            return redirect()
                ->route('estabilidad_agregados.index')
                ->with('success', 'Archivo importado correctamente');

        } catch (\Throwable $e) {

            DB::rollBack();

            return back()->withErrors(
                'Error al importar: ' . $e->getMessage()
            );
        }
    }
}

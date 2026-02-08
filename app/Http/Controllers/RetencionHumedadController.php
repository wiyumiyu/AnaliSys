<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

class RetencionHumedadController extends Controller
{
    /* ===============================
     * LISTADO DE ARCHIVOS
     * =============================== */
    public function archivos(Request $request)
    {
        $anio = $request->get('anio', date('Y'));

        $archivos = DB::select(
            'CALL sp_listar_retencion_humedad_por_periodo(?)',
            [$anio]
        );

        return view(
            'ingreso_datos.retencion_humedad.index',
            compact('archivos', 'anio')
        );
    }

    /* ===============================
     * LISTADO DE MUESTRAS POR ARCHIVO
     * =============================== */
    public function muestras($idArchivo)
    {
        $archivo = 'RH-2026-001'; // luego puedes traerlo desde BD

        $muestras = DB::select(
            'CALL sp_listar_muestras_retencion_humedad_detalle(?)',
            [$idArchivo]
        );

        return view(
            'ingreso_datos.retencion_humedad.muestras',
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
                'CALL sp_obtener_muestra_retencion_humedad(?)',
                [$id]
            )
        )->first();

        $resultados = DB::select(
            'CALL sp_listar_resultados_retencion_humedad_por_muestra(?)',
            [$id]
        );

        return view(
            'ingreso_datos.retencion_humedad.editar',
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
                'CALL sp_obtener_muestra_retencion_humedad(?)',
                [$id]
            )
        )->first();

        DB::statement(
            'CALL sp_actualizar_muestra_retencion_humedad(?,?,?,?,?,?)',
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
                    'CALL sp_actualizar_resultado_retencion_humedad(?,?)',
                    [$idResultado, $valor]
                );
            }
        }

        return redirect()
            ->route(
                'retencion_humedad.muestras',
                $muestra->id_retencion_humedad
            )
            ->with('success', 'Muestra actualizada correctamente');
    }

    /* ===============================
     * TOGGLE ESTADO
     * =============================== */
    public function toggleEstado($id)
    {
        DB::statement(
            'CALL sp_toggle_estado_muestra_retencion_humedad(?)',
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
            'CALL sp_eliminar_muestra_retencion_humedad(?)',
            [$id]
        );

        return redirect()
            ->back()
            ->with('success', 'Muestra eliminada correctamente');
    }

    public function destroyArchivo($id)
    {
        DB::statement(
            'CALL sp_eliminar_retencion_humedad(?)',
            [$id]
        );

        return redirect()
            ->route('retencion_humedad.index')
            ->with('success', 'Archivo eliminado correctamente');
    }

    /* ===============================
     * IMPORTAR DESDE EXCEL
     * =============================== */
    public function importar(Request $request)
    {
        $request->validate([
            'archivo' => 'required|file|mimes:xlsx,xls'
        ]);

        DB::beginTransaction();

        try {

            /* ==== Crear archivo ==== */
            $idRetencion = DB::table('trn_retencion_humedad')->insertGetId([
                'periodo'  => date('Y'),
                'archivo'  => $request->file('archivo')->getClientOriginalName(),
                'fecha'    => now(),
                'analista' => session('id_persona')
            ]);

            /* ==== Mapa de análisis ==== */
            $analisisMap = DB::table('trn_analisis')
                ->where('origen', 'RETENCION_HUMEDAD')
                ->pluck('id', 'siglas')
                ->toArray();

            /* ==== Leer Excel ==== */
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

                $tipo = is_numeric($row['A']) ? 1 : 2;

                /* ==== Insert muestra ==== */
                $idMuestra = DB::table('trn_retencion_humedad_muestras')->insertGetId([
                    'id_retencion_humedad' => $idRetencion,
                    'idlab'    => $row['A'],
                    'rep'      => $row['B'],
                    'material' => 1,
                    'tipo'     => $tipo,
                    'posicion' => $i,
                    'estado'   => 1,
                    'ri'       => 0
                ]);

                /* ==== Resultados ==== */
                $valores = [
                    'presion_aplicada' => $row['C'],
                    'ph1_L1'           => $row['D'],
                    'ps1_L1'           => $row['E'],
                    'ph_L2'            => $row['F'],
                    'ps2_L2'           => $row['G'],
                    'L1'               => $row['H'],
                    'L2'               => $row['I'],
                ];

                foreach ($valores as $sigla => $resultado) {

                    if (!isset($analisisMap[$sigla])) {
                        continue;
                    }

                    DB::table('trn_retencion_humedad_resultados')->insert([
                        'id_retencion_humedad_muestras' => $idMuestra,
                        'id_analisis' => $analisisMap[$sigla],
                        'resultado'   => $resultado,
                        'estado'      => 1
                    ]);
                }

                $i++;
            }

            DB::commit();

            return redirect()
                ->route('retencion_humedad.index')
                ->with('success', 'Archivo importado correctamente');

        } catch (\Throwable $e) {

            DB::rollBack();

            return back()->withErrors(
                'Error al importar: ' . $e->getMessage()
            );
        }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

class HumedadGravimetricaController extends Controller
{
    /* ===============================
     * LISTADO DE ARCHIVOS
     * =============================== */
    public function archivos(Request $request)
    {
        $anio = $request->get('anio', date('Y'));

        $archivos = DB::select(
            'CALL sp_listar_humedad_gravimetrica_por_periodo(?)',
            [$anio]
        );

        return view(
            'ingreso_datos.humedad_gravimetrica.index',
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
            'CALL sp_listar_muestras_humedad_gravimetrica_detalle(?)',
            [$idArchivo]
        );

        return view(
            'ingreso_datos.humedad_gravimetrica.muestras',
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
                'CALL sp_obtener_muestra_humedad_gravimetrica(?)',
                [$id]
            )
        )->first();

        $resultados = DB::select(
            'CALL sp_listar_resultados_humedad_gravimetrica_por_muestra(?)',
            [$id]
        );

        return view(
            'ingreso_datos.humedad_gravimetrica.editar',
            compact('muestra', 'resultados')
        );
    }

    /* ===============================
     * ACTUALIZAR MUESTRA
     * =============================== */
    public function update(Request $request, $id)
    {
        // obtener id_humedad_gravimetrica antes de actualizar
        $muestra = collect(
            DB::select(
                'CALL sp_obtener_muestra_humedad_gravimetrica(?)',
                [$id]
            )
        )->first();

        // actualizar muestra
        DB::statement(
            'CALL sp_actualizar_muestra_humedad_gravimetrica(?,?,?,?,?,?)',
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
                    'CALL sp_actualizar_resultado_humedad_gravimetrica(?,?)',
                    [$idResultado, $valor]
                );
            }
        }

        return redirect()
            ->route(
                'humedad_gravimetrica.muestras',
                $muestra->id_humedad_gravimetrica
            )
            ->with('success', 'Muestra actualizada correctamente');
    }

    /* ===============================
     * TOGGLE ESTADO
     * =============================== */
    public function toggleEstado($id)
    {
        DB::statement(
            'CALL sp_toggle_estado_muestra_humedad_gravimetrica(?)',
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
            'CALL sp_eliminar_muestra_humedad_gravimetrica(?)',
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
            'CALL sp_eliminar_humedad_gravimetrica(?)',
            [$id]
        );

        return redirect()
            ->route('humedad_gravimetrica.index')
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

            /* ==== Crear archivo de humedad gravimétrica ==== */
            $idHumedad = DB::table('trn_humedad_gravimetrica')->insertGetId([
                'periodo'  => date('Y'),
                'archivo'  => $request->file('archivo')->getClientOriginalName(),
                'fecha'    => now(),
                'analista' => session('id_persona')
            ]);

            /* ==== Mapa de análisis HUMEDAD ==== */
            $analisisMap = DB::table('trn_analisis')
                ->where('origen', 'HUMEDAD_GRAVIMETRICA')
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
                $idMuestra = DB::table('trn_humedad_gravimetrica_muestras')->insertGetId([
                    'id_humedad_gravimetrica' => $idHumedad,
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
                    'peso_capsula_vacia'        => $row['C'],
                    'peso_capsula_suelohumedo' => $row['D'],
                    'peso_capsula_sueloseco'   => $row['E'],
                    'temperatura_secado'       => $row['F'],
                    'tiempo_secado'            => $row['G'],
                ];

                foreach ($valores as $sigla => $resultado) {
                    if (!isset($analisisMap[$sigla])) continue;

                    DB::table('trn_humedad_gravimetrica_resultados')->insert([
                        'id_humedad_gravimetrica_muestras' => $idMuestra,
                        'id_analisis' => $analisisMap[$sigla],
                        'resultado'   => $resultado,
                        'estado'      => 1
                    ]);
                }

                $i++;
            }

            DB::commit();

            return redirect()
                ->route('humedad_gravimetrica.index')
                ->with('success', 'Archivo importado correctamente');

        } catch (\Throwable $e) {

            DB::rollBack();

            return back()->withErrors(
                'Error al importar: ' . $e->getMessage()
            );
        }
    }
}

<?php

namespace App\Http\Controllers\IngresoDatos;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Http\Controllers\Controller;

class GranulometriaController extends Controller
{
    /* ===============================
     * LISTADO DE ARCHIVOS
     * =============================== */
    public function archivos(Request $request)
    {
        $anio = $request->get('anio', date('Y'));

        $archivos = DB::select(
            'CALL sp_listar_granulometria_por_periodo(?)',
            [$anio]
        );

        return view(
            'ingreso_datos.granulometria.index',
            compact('archivos', 'anio')
        );
    }

    /* ===============================
     * LISTADO DE MUESTRAS POR ARCHIVO
     * =============================== */
public function muestras($idArchivo)
{
    // Traer nombre real del archivo
    $archivo = DB::table('trn_granulometria')
        ->where('id', $idArchivo)
        ->value('archivo');

    $muestras = DB::select(
        'CALL sp_listar_muestras_granulometria_detalle(?)',
        [$idArchivo]
    );

    return view(
        'ingreso_datos.granulometria.muestras',
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
                'CALL sp_obtener_muestra_granulometria(?)',
                [$id]
            )
        )->first();

        $resultados = DB::select(
            'CALL sp_listar_resultados_granulometria_por_muestra(?)',
            [$id]
        );

        return view(
            'ingreso_datos.granulometria.editar',
            compact('muestra', 'resultados')
        );
    }

    /* ===============================
     * ACTUALIZAR MUESTRA
     * =============================== */
    public function update(Request $request, $id)
    {
                DB::statement('SET @bitacora_usuario = ?', [session('id_persona') ?? 0]);
        DB::statement('SET @bitacora_ip = ?', [$request->ip() ?? 'UNKNOWN']);
        // obtener id_granulometria antes de actualizar
        $muestra = collect(
            DB::select(
                'CALL sp_obtener_muestra_granulometria(?)',
                [$id]
            )
        )->first();

        // actualizar muestra
        DB::statement(
            'CALL sp_actualizar_muestra_granulometria(?,?,?,?,?,?)',
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
                    'CALL sp_actualizar_resultado_granulometria(?,?)',
                    [$idResultado, $valor]
                );
            }
        }

        return redirect()
            ->route(
                'granulometria.muestras',
                $muestra->id_granulometria
            )
            ->with('success', 'Muestra actualizada correctamente');
    }

    /* ===============================
     * TOGGLE ESTADO
     * =============================== */
    public function toggleEstado($id)
    {
        DB::statement(
            'CALL sp_toggle_estado_muestra_granulometria(?)',
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
            'CALL sp_eliminar_muestra_granulometria(?)',
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
            'CALL sp_eliminar_granulometria(?)',
            [$id]
        );

        return redirect()
            ->route('granulometria.index')
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

            /* ==== Crear archivo de granulometría ==== */
            $idGranulometria = DB::table('trn_granulometria')->insertGetId([
                'periodo'  => date('Y'),
                'archivo'  => $request->file('archivo')->getClientOriginalName(),
                'fecha'    => now(),
                'analista' => session('id_persona')
            ]);

            /* ==== Mapa de análisis GRANULOMETRÍA ==== */
            $analisisMap = DB::table('trn_analisis')
                ->where('origen', 'GRANULOMETRIA')
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
                $idMuestra = DB::table('trn_granulometria_muestras')->insertGetId([
                    'id_granulometria' => $idGranulometria,
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
                    'peso_seco'                => $row['C'],
                    'peso_lata'                => $row['D'],
                    'temperatura_secado'       => $row['E'],
                    'tiempo_secado'            => $row['F'],
                    'fecha_secado'             => $row['G'],
                ];

                foreach ($valores as $sigla => $resultado) {
                    if (!isset($analisisMap[$sigla])) continue;

                    DB::table('trn_granulometria_resultados')->insert([
                        'id_granulometria_muestras' => $idMuestra,
                        'id_analisis' => $analisisMap[$sigla],
                        'resultado'   => $resultado,
                        'estado'      => 1
                    ]);
                }

                $i++;
            }

            DB::commit();

            return redirect()
                ->route('granulometria.index')
                ->with('success', 'Archivo importado correctamente');

        } catch (\Throwable $e) {

            DB::rollBack();

            return back()->withErrors(
                'Error al importar: ' . $e->getMessage()
            );
        }
    }
}

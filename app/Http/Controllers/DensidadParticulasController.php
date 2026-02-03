<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

class DensidadParticulasController extends Controller
{
    /* ===============================
     * LISTADO DE ARCHIVOS
     * =============================== */
    public function archivos(Request $request)
    {
        $anio = $request->get('anio', date('Y'));

        $archivos = DB::select(
            'CALL sp_listar_densidad_particulas_por_periodo(?)',
            [$anio]
        );

        return view(
            'ingreso_datos.densidad_particulas.index',
            compact('archivos', 'anio')
        );
    }

    /* ===============================
     * LISTADO DE MUESTRAS POR ARCHIVO
     * =============================== */
    public function muestras($idArchivo)
    {
        // nombre del archivo (luego puedes traerlo desde BD si quieres)
        $archivo = 'DA-2026-001';

        $muestras = DB::select(
            'CALL sp_listar_muestras_densidad_particulas_detalle(?)',
            [$idArchivo]
        );

        return view(
            'ingreso_datos.densidad_particulas.muestras',
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
                'CALL sp_obtener_muestra_densidad_particulas(?)',
                [$id]
            )
        )->first();

        $resultados = DB::select(
            'CALL sp_listar_resultados_densidad_particulas_por_muestra(?)',
            [$id]
        );

        return view(
            'ingreso_datos.densidad_particulas.editar',
            compact('muestra', 'resultados')
        );
    }

    /* ===============================
     * ACTUALIZAR MUESTRA
     * =============================== */
    public function update(Request $request, $id)
    {
        // obtener id_densidad_particulas antes de actualizar
        $muestra = collect(
            DB::select(
                'CALL sp_obtener_muestra_densidad_particulas(?)',
                [$id]
            )
        )->first();

        // actualizar muestra
        DB::statement(
            'CALL sp_actualizar_muestra_densidad_particulas(?,?,?,?,?,?)',
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
                    'CALL sp_actualizar_resultado_densidad_particulas(?,?)',
                    [$idResultado, $valor]
                );
            }
        }

        // redirigir al listado de muestras
        return redirect()
            ->route(
                'densidad_particulas.muestras',
                $muestra->id_densidad_particulas
            )
            ->with('success', 'Muestra actualizada correctamente');
    }

    /* ===============================
     * TOGGLE ESTADO
     * =============================== */
    public function toggleEstado($id)
    {
        DB::statement(
            'CALL sp_toggle_estado_muestra_densidad_particulas(?)',
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
            'CALL sp_eliminar_muestra_densidad_particulas(?)',
            [$id]
        );

        return redirect()
            ->back()
            ->with('success', 'Muestra eliminada correctamente');
    }

    public function destroyArchivo($id)
    {
        DB::statement(
            'CALL sp_eliminar_densidad_particulas(?)',
            [$id]
        );

        return redirect()
            ->route('densidad_particulas.index')
            ->with('success', 'Archivo eliminado correctamente');
    }

    public function importar(Request $request)
    {
        $request->validate([
            'archivo' => 'required|file|mimes:xlsx,xls'
        ]);

        DB::beginTransaction();

      //  try {

            /* ==== Crear archivo de densidad particulas ==== */
            $idDensidadParticulas = DB::table('trn_densidad_particulas')->insertGetId([
                'periodo'  => date('Y'),
                'archivo'  => $request->file('archivo')->getClientOriginalName(),
                'fecha'    => now(),
                'analista' => session('id_persona')

            ]);

            /* ==== Mapa de análisis DENSIDAD PARTICULAS ==== */

            $analisisMap = DB::table('trn_analisis')
                ->where('origen', 'DENSIDAD_PARTICULAS')
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
                $idMuestra = DB::table('trn_densidad_particulas_muestras')->insertGetId([
                    'id_densidad_particulas' => $idDensidadParticulas,
                    'idlab'                => $row['A'],
                    'rep'                  => $row['B'],
                    'material'             => 1, // placeholder
                    'tipo'                 => $tipo,
                    'posicion'             => $i,
                    'estado'               => 1,
                    'ri'                   => 0
                ]);

                /* ===== Resultados ===== */
                $valores = [
                    'numero_balon_vol'               => $row['C'],
                    'peso_balon_vol_vacio_p1'             => $row['D'],
                    'peso_balon_vol_suelo_seco_p2'  => $row['E'],
                    'peso_balon_vol_suelo_agua_p3'        => $row['F'],
                    'temperatura_agua'          => $row['G']

                ];

//dd($analisisMap, array_keys($valores));
                foreach ($valores as $sigla => $resultado) {


                    if (!isset($analisisMap[$sigla])) {
                        continue;
                    }

                    DB::table('trn_densidad_particulas_resultados')->insert([
                        'id_densidad_particulas_muestras' => $idMuestra,
                        'id_analisis'         => $analisisMap[$sigla],
                        'resultado'           => $resultado,
                        'estado'              => 1
                    ]);
                }
                $i += 1;
            }
            /* ==== Commit FINAL ==== */
            DB::commit();

            return redirect()
                ->route('densidad_particulas.index')
                ->with('success', 'Archivo importado correctamente');
//        } catch (\Throwable $e) {
//
//            DB::rollBack();
//
//            return back()->withErrors(
//                'Error al importar: ' . $e->getMessage()
//            );
//        }
    }
}

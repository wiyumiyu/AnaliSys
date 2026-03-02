<?php

namespace App\Http\Controllers\IngresoDatos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * ============================================================
 *  INGRESO DE DATOS - TEXTURA
 * ============================================================
 *  Maneja:
 *   - Carga de archivos Excel
 *   - Listado de archivos importados
 *   - Gestión de muestras
 *   - Gestión de resultados
 * ============================================================
 */
class TexturaController extends Controller
{
    /**
     * ------------------------------------------------------------
     * Listado de archivos importados
     * ------------------------------------------------------------
     */
    
    public function index(Request $request)
    {
        $periodo = $request->get('periodo', date('Y'));

        $archivos = DB::select(
            'CALL sp_listar_textura_por_periodo(?)',
            [$periodo]
        );

        return view('ingreso_datos.textura.index', compact(
            'archivos',
            'periodo'
        ));
    }
  /* ===============================
     * LISTADO DE MUESTRAS POR LOTE
     * =============================== */

public function muestras($idArchivo)
{
    $archivo = DB::table('trn_textura')
        ->where('id', $idArchivo)
        ->value('archivo');

    $muestras = DB::select(
        'CALL sp_listar_muestras_textura_detalle(?)',
        [$idArchivo]
    );

    return view(
        'ingreso_datos.textura.muestras',
        compact('muestras', 'archivo', 'idArchivo')
    );
}

    /* ===============================
     * EDITAR MUESTRA
     * =============================== */

    public function edit($id) {
        $muestra = collect(
                DB::select('CALL sp_obtener_muestra_textura(?)', [$id])
                )->first();

        $resultados = DB::select(
                'CALL sp_listar_resultados_textura_por_muestra(?)',
                [$id]
        );

        return view(
                'ingreso_datos.textura.editar',
                compact('muestra', 'resultados')
        );
    }

    public function update(Request $request, $id) {
        // obtener id_textura antes de actualizar
        $muestra = collect(
                DB::select('CALL sp_obtener_muestra_textura(?)', [$id])
                )->first();

        // actualizar muestra
        DB::statement(
                'CALL sp_actualizar_muestra_textura(?,?,?,?,?,?)',
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
                        'CALL sp_actualizar_resultado_textura(?,?)',
                        [$idResultado, $valor]
                );
            }
        }

        // 👉 REDIRECCIÓN AL LISTADO DE MUESTRAS
        return redirect()
                        ->route('textura.muestras', $muestra->id_textura)
                        ->with('success', 'Muestra actualizada correctamente');
    }

    public function toggleEstado($id) {
        DB::statement(
                'CALL sp_toggle_estado_muestra_textura(?)',
                [$id]
        );

        return redirect()
                        ->back()
                        ->with('success', 'Estado de la muestra actualizado correctamente');
    }

    public function destroy($id) {
        DB::statement('CALL sp_eliminar_muestra_textura(?)', [$id]);

        return redirect()
                        ->back()
                        ->with('success', 'Muestra eliminada correctamente');
    }


public function importar(Request $request)
{
    $request->validate([
        'archivo' => 'required|file|mimes:xlsx,xls'
    ]);

    DB::beginTransaction();

    try {

        /* ===============================
         * 1. Crear archivo de textura
         * =============================== */
        $idTextura = DB::table('trn_textura')->insertGetId([
            'periodo'  => date('Y'),
            'archivo'  => $request->file('archivo')->getClientOriginalName(),
            'fecha'    => now(),
            'analista' => session('id_persona')

        ]);

        /* ===============================
         * 2. Mapa de análisis TEXTURA
         * =============================== */
            //aqui se relacionan el nombre de la tabla, y el origen de la tabla trn_analisis
        $analisisMap = DB::table('trn_analisis')
            ->where('origen', 'TEXTURA')
            ->pluck('id', 'siglas')
            ->toArray();

        /* ===============================
         * 3. Leer Excel
         * =============================== */
        $spreadsheet = IOFactory::load(
            $request->file('archivo')->getPathname()
        );

        $rows = $spreadsheet
            ->getActiveSheet()
            ->toArray(null, true, true, true);

        /* ===============================
         * 4. Recorrer filas (desde fila 3)
         * =============================== */
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
            if(!is_numeric($row['A'])){
                $tipo = 2;
            }
            /* ===== INSERT MUESTRA ===== */
            $idMuestra = DB::table('trn_textura_muestras')->insertGetId([
                'id_textura' => $idTextura,
                'idlab'      => $row['A'],
                'rep'        => $row['B'],
                'material'   => 1, // placeholder
                'tipo'       => $tipo,
                'posicion'   => $i,
                'estado'     => 1,
                'ri'         => 0
            ]);

            /* ===== RESULTADOS ===== */
            $valores = [
                'PESO_SECO' => $row['C'],
                'R1'        => $row['D'],
                'R2'        => $row['E'],
                'R3'        => $row['F'],
                'R4'        => $row['G'],
                'TEMP1'     => $row['H'],
                'TEMP2'     => $row['I'],
                'TEMP3'     => $row['J'],
                'TEMP4'     => $row['K'],
                'TIEMPO1'   => $row['L'],
                'TIEMPO2'   => $row['M'],
                'TIEMPO3'   => $row['N'],
                'TIEMPO4'   => $row['O'],
            ];

            foreach ($valores as $sigla => $resultado) {

                // evita undefined index si falta un análisis
                if (!isset($analisisMap[$sigla])) {
                    continue;
                }

                DB::table('trn_textura_resultados')->insert([
                    'id_textura_muestras' => $idMuestra,
                    'id_analisis'         => $analisisMap[$sigla],
                    'resultado'           => $resultado,
                    'estado'              => 1
                ]);
            }
            $i +=1;
        }

        /* ===============================
         * 5. Commit FINAL
         * =============================== */
        DB::commit();

        return redirect()
            ->route('textura.index')
            ->with('success', 'Archivo importado correctamente');

    } catch (\Throwable $e) {

        DB::rollBack();

        return back()->withErrors(
            'Error al importar: ' . $e->getMessage()
        );
    }
}
    
    public function archivos(Request $request) {
        $periodo = $request->get('periodo', date('Y'));

        $archivos = DB::select('CALL sp_listar_textura_por_periodo(?)', [$periodo]);
        return view(
                'ingreso_datos.textura.index',
                compact('archivos', 'periodo')
        );
    }
}
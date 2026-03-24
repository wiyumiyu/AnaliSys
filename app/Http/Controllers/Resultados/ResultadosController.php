<?php

namespace App\Http\Controllers\Resultados;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\Calculos\TexturaResultados;
use App\Helpers\Calculos\DensidadAparenteResultados;
use App\Helpers\Calculos\DensidadParticulasResultados;
use App\Helpers\Calculos\Estadisticas;
use App\Helpers\Calculos\HumedadGravimetricaResultados;
use App\Helpers\Calculos\PorosidadResultados;
use App\Helpers\Calculos\ConductividadHidraulicaResultados;
use App\Helpers\Calculos\RetencionHumedadResultados;

class ResultadosController extends Controller {

    /**
     * ------------------------------------------------------------
     * Listado de resultados por período
     * ------------------------------------------------------------
     */
    public function index(Request $request) {
        $periodo = $request->get('periodo', date('Y'));

        $rows = DB::select(
                'CALL sp_listar_resultados_por_anio(?)',
                [$periodo]
        );

        // Agrupar archivos por resultado (igual que hiciste antes)
        $resultados = collect($rows)
                ->groupBy('id')
                ->map(function ($items) {

                    $first = $items->first();

                    return (object) [
                                'id' => $first->id,
                                'consecutivo' => $first->consecutivo,
                                'fecha' => $first->fecha,
                                'analista' => $first->analista,
                                'archivos' => $items
                                        ->filter(fn($item) => !is_null($item->archivo))
                                        ->map(function ($item) {
                                            return (object) [
                                                        'tipo' => $item->tipo,
                                                        'archivo' => $item->archivo
                                            ];
                                        })
                                        ->values()
                    ];
                })
                ->values();

        $archivosDisponibles = DB::select(
                'CALL sp_listar_todos_los_archivos(?)',
                [$periodo]
        );

        $siguienteConsecutivo = collect(DB::select(
                                'CALL sp_traer_consecutivo_resultados(?)',
                                [$periodo]
                        ))->first()->siguiente_consecutivo ?? 1;

        return view('resultados.index', compact(
                        'resultados',
                        'periodo',
                        'archivosDisponibles',
                        'siguienteConsecutivo'
                ));
    }

    /**
     * ------------------------------------------------------------
     * Guardar resultado
     * ------------------------------------------------------------
     */
    public function guardarResultado(Request $request) {
        $request->validate([
            'consecutivo' => 'required|integer|min:1',
            'archivos' => 'required|array|min:1'
        ]);

        $listaArchivos = implode(',', $request->archivos);

        try {

            DB::statement(
                    'CALL sp_guardar_resultado(?,?,?)',
                    [
                        $request->consecutivo,
                        session('id_persona'),
                        $listaArchivos
                    ]
            );

            return redirect()
                            ->route('resultados.index')
                            ->with('success', 'Resultado creado correctamente.');
        } catch (\Exception $e) {

            return back()
                            ->withInput()
                            ->with('error', $e->getMessage());
        }
    }

    /**
     * ------------------------------------------------------------
     * Eliminar resultado
     * ------------------------------------------------------------
     */
    public function destroy($id) {
        try {

            DB::statement(
                    'CALL sp_eliminar_resultado(?)',
                    [$id]
            );

            return redirect()
                            ->route('resultados.index')
                            ->with('success', 'Resultado eliminado correctamente.');
        } catch (\Exception $e) {

            return back()
                            ->with('error', 'No se pudo eliminar el resultado.');
        }
    }

    /**
     * ------------------------------------------------------------
     * Vista detalle resultado
     * ------------------------------------------------------------
     */
    public function show($id) {

        /* ------------------------------------------------ */
        // ARCHIVOS DEL RESULTADO
        /* ------------------------------------------------ */
        $archivos = collect(DB::select(
                        'CALL sp_traer_archivos_por_resultado(?)',
                        [$id]
                ));

        /* ------------------------------------------------ */
        // COMENTARIOS
        /* ------------------------------------------------ */
        $comentarios = collect(DB::select(
                        'CALL sp_traer_comentarios_resultado(?)',
                        [$id]
                ));

        /* ------------------------------------------------ */
        // DATOS BASE (IDLAB, REP, ETC)
        /* ------------------------------------------------ */
        $datos = collect(DB::select(
                        'CALL sp_resultado_vista(?)',
                        [$id]
                ));

        /* ------------------------------------------------ */
        //  TEXTURA (MULTI ARCHIVO)
        /* ------------------------------------------------ */
        $archivosTextura = $archivos
                ->where('tipo', 'TEXTURA')
                ->pluck('archivo')
                ->filter()
                ->values()
                ->toArray();

        $texturas = [];

        if (!empty($archivosTextura)) {

            $lista = implode(',', $archivosTextura);

            $textura = DB::select(
                    'CALL sp_reporte_cliente_textura_resultados(?)',
                    [$lista]
            );

            $texturas = \App\Helpers\Calculos\TexturaResultados::calcularPorRep($textura);
        }

        // ARCHIVOS DA
        $archivosDA = $archivos
                ->where('tipo', 'DENSIDAD_APARENTE')
                ->pluck('archivo')
                ->toArray();

        $densidades = [];

        if (!empty($archivosDA)) {

            $lista = implode(',', $archivosDA);

            $da = DB::select(
                    'CALL sp_densidad_aparente_resultados(?)',
                    [$lista]
            );

            $densidades = DensidadAparenteResultados::calcularPorRep($da);
        }

        $archivosDP = $archivos
                ->where('tipo', 'DENSIDAD_PARTICULAS')
                ->pluck('archivo')
                ->filter()
                ->toArray();

        $densidadesParticulas = [];

        if (!empty($archivosDP)) {

            $lista = implode(',', $archivosDP);

            $dp = DB::select(
                    'CALL sp_densidad_particulas_resultados(?)',
                    [$lista]
            );

            $densidadesParticulas = DensidadParticulasResultados::calcularPorRep($dp);
        }

        $porosidades = PorosidadResultados::calcularPorRep(
                $densidades,
                $densidadesParticulas
        );

        $archivosHG = $archivos
                ->where('tipo', 'HUMEDAD_GRAVIMETRICA')
                ->pluck('archivo')
                ->filter()
                ->toArray();

        $humedades = [];

        if (!empty($archivosHG)) {

            $lista = implode(',', $archivosHG);

            $hg = DB::select(
                    'CALL sp_humedad_gravimetrica_resultados(?)',
                    [$lista]
            );

            $humedades = HumedadGravimetricaResultados::calcularPorRep($hg);
        }

        $archivosCH = $archivos
                ->where('tipo', 'CONDUCTIVIDAD_HIDRAULICA')
                ->pluck('archivo')
                ->filter()
                ->toArray();

        $conductividades = [];

        if (!empty($archivosCH)) {

            $lista = implode(',', $archivosCH);

            $ch = DB::select(
                    'CALL sp_conductividad_hidraulica_resultados(?)',
                    [$lista]
            );

            $conductividades = ConductividadHidraulicaResultados::calcularPorRep($ch);
        }

        $archivosRH = $archivos
                ->where('tipo', 'RETENCION_HUMEDAD')
                ->pluck('archivo')
                ->filter()
                ->toArray();

        $retenciones = [];

        if (!empty($archivosRH)) {

            $lista = implode(',', $archivosRH);

            $rh = DB::select(
                    'CALL sp_retencion_humedad_resultados(?)',
                    [$lista]
            );

            $retenciones = RetencionHumedadResultados::calcularPorRep($rh);
        }


        /* ------------------------------------------------ */
        // AGRUPAR POR IDLAB (CARDS)
        /* ------------------------------------------------ */
        $cards = $datos->groupBy('idlab')->map(function ($rows) {

            $rows = collect($rows);

            return (object) [
                        'rows' => $rows,
                        'first' => $rows->first(),
                        'count' => $rows->count()
            ];
        });
        $estadisticasTextura = [];

        foreach ($cards as $key => $card) {

            $rows = $card->rows;

            $estadisticasTextura[$key] = TexturaResultados::estadisticas($rows, $texturas);
        }

        $estadisticasDA = [];
        $estadisticasDP = [];
        $estadisticasPor = [];
        $estadisticasHG = [];
        $estadisticasCH = [];
        $estadisticasRH = [];

        foreach ($cards as $key => $card) {

            $rows = $card->rows;

            $estadisticasDA[$key] = Estadisticas::calcular($rows, $densidades);
            $estadisticasDP[$key] = Estadisticas::calcular($rows, $densidadesParticulas);
            $estadisticasPor[$key] = Estadisticas::calcular($rows, $porosidades);
            $estadisticasHG[$key] = Estadisticas::calcular($rows, $humedades);
            $estadisticasCH[$key] = Estadisticas::calcular($rows, $conductividades);
            $estadisticasRH[$key] = RetencionHumedadResultados::estadisticas($rows, $retenciones);
        }

        /* ------------------------------------------------ */
        // VIEW
        /* ------------------------------------------------ */
        return view('resultados.vista', compact(
                        'archivos',
                        'comentarios',
                        'cards',
                        'texturas',
                        'densidades',
                        'estadisticasTextura',
                        'densidadesParticulas',
                        'estadisticasDA',
                        'porosidades',
                        'estadisticasPor',
                        'estadisticasDP',
                        'estadisticasHG',
                        'humedades',
                        'conductividades',
                        'estadisticasCH',
                        'retenciones',
                        'estadisticasRH'
                ));
    }

    /**
     * ------------------------------------------------------------
     * Agregar comentario o aprobar
     * ------------------------------------------------------------
     */
    public function guardarComentario(Request $request, $id) {

        $accion = $request->accion;

        if ($accion === 'aprobar') {

            DB::statement(
                    'CALL sp_agregar_comentario_resultado(?,?,?,?)',
                    [
                        $id,
                        session('id_persona'),
                        null,
                        1
                    ]
            );
        } else {

            DB::statement(
                    'CALL sp_agregar_comentario_resultado(?,?,?,?)',
                    [
                        $id,
                        session('id_persona'),
                        $request->comentario,
                        0
                    ]
            );
        }

        return back();
    }
}

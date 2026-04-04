<?php

namespace App\Http\Controllers\ReportesClientes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\Calculos\Textura;
use App\Helpers\Calculos\DensidadAparente;
use App\Helpers\Calculos\DensidadParticulas;
use App\Helpers\Calculos\HumedadGravimetrica;
use App\Helpers\Calculos\conductividadHidraulica;
use App\Helpers\Calculos\RetencionHumedad;
use App\Helpers\Calculos\Porosidad;
use App\Helpers\Calculos\EstabilidadAgregados;
use App\Helpers\Calculos\CoeficienteExtensibilidad;
use App\Helpers\Calculos\Granulometria;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ReporteClienteController extends Controller {

    public function index(Request $request) {
        $periodo = $request->get('periodo');

        if (!$periodo) {
            $periodo = DB::table('tbm_solicitud')
                    ->selectRaw('MAX(YEAR(fecha)) as anio')
                    ->value('anio');
        }

        $estadoRep = $request->get('estado', 0);
        $buscar = $request->get('buscar', '');

        $solicitudes = DB::select(
                'CALL sp_listar_reportes_clientes(?, ?, ?)',
                [$periodo, $estadoRep, $buscar]
        );

        return view('reportes_clientes.index', compact(
                        'solicitudes',
                        'periodo',
                        'estadoRep',
                        'buscar'
                ));
    }

    public function show($id) {
        $data = $this->getDatosReporte($id);

        return view('reportes_clientes.vista', $data);
    }

    public function exportar($id) {
        $data = $this->getDatosReporte($id);

        $ruta = storage_path('app/plantillas/Rep01.xlsx');

        if (!file_exists($ruta)) {
            dd('NO EXISTE:', $ruta);
        }

        $spreadsheet = IOFactory::load($ruta);

        // 🔥 hojas
        $sheet1 = $spreadsheet->getSheet(0);
        $sheet2 = $spreadsheet->getSheet(1);

        // 🔥 encabezado en ambas hojas
        $this->llenarEncabezado($sheet1, $data);
        $this->llenarEncabezado($sheet2, $data);

        //------------------------------------------------------------------------
        // llenar encabezados de analisis
        //------------------------------------------------------------------------        
        $col = 5; // E
        $cantidadHojas = count($data['datos']) > 15 ? 2 : 1;

        if ($this->hayTexturaPorDatos($data['datos'], $data['texturas'])) {
            $col = $this->dibujarEncabezadoTextura($spreadsheet, $col, $cantidadHojas);
        }

//        if ($this->hayDensidadPorDatos($data['datos'], $data['texturas'])) {
//            $col = $this->dibujarEncabezadoDensidad($spreadsheet, $col, $cantidadHojas);
//        }

        //------------------------------------------------------------------------
        // llenar datos
        //------------------------------------------------------------------------


        $col = 5;

        if ($this->hayTexturaPorDatos($data['datos'], $data['texturas'])) {

            $colTextura = $col; // 🔥 guardás inicio

            // $col = $this->dibujarEncabezadoTextura($spreadsheet, $col, $cantidadHojas);

            // 🔥 llenar datos usando el inicio
            $this->llenarDatosTextura(
                    $sheet1,
                    $sheet2,
                    $data['datos'],
                    $data['texturas'],
                    $colTextura
            );
        }

        $filaBase = 19;

        foreach ($data['datos'] as $index => $row) {

            // 🔥 decidir hoja
            if ($index < 15) {
                $sheet = $sheet1;
                $fila = $filaBase + $index;
            } else {
                $sheet = $sheet2;
                $fila = $filaBase + ($index - 15);
            }

            // --------------------------------------------------------
            // ID USUARIO (A-C merge)
            $sheet->mergeCells("A{$fila}:C{$fila}");

            $sheet->getStyle("A{$fila}:C{$fila}")
                    ->getBorders()
                    ->getLeft()
                    ->setBorderStyle(Border::BORDER_THIN);

            $sheet->getStyle("A{$fila}:C{$fila}")
                    ->getBorders()
                    ->getRight()
                    ->setBorderStyle(Border::BORDER_THIN);

            $sheet->setCellValue("A{$fila}", $row->etiqueta);

            // --------------------------------------------------------
            // ID LAB (D)
            $sheet->getStyle("D{$fila}")
                    ->getBorders()
                    ->getLeft()
                    ->setBorderStyle(Border::BORDER_THIN);

            $sheet->getStyle("D{$fila}")
                    ->getBorders()
                    ->getRight()
                    ->setBorderStyle(Border::BORDER_THIN);

            $sheet->setCellValue("D{$fila}", $row->idlab);
        }

        // 🔥 ocultar hoja 2 si no se usa
        if (count($data['datos']) <= 15) {
            $sheet2->setSheetState(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::SHEETSTATE_HIDDEN);
        }

        // 🔥 descargar
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

        $fileName = 'reporte_' . $id . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=\"$fileName\"");
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    private function dibujarEncabezadoTextura($spreadsheet, $col, $cantidadHojas) {
        for ($i = 0; $i < $cantidadHojas; $i++) {

            $sheet = $spreadsheet->getSheet($i);

            $colLetra = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
            $colFin = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col + 3);

            // --------------------------------------------------------
            // TITULO
            $sheet->mergeCells("{$colLetra}16:{$colFin}16");
            $sheet->setCellValue("{$colLetra}16", "Textura");

            // --------------------------------------------------------
            // SUBHEADERS
            $sheet->setCellValue(
                    \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col) . "17",
                    "Arena"
            );
            $sheet->setCellValue(
                    \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col + 1) . "17",
                    "Limo"
            );
            $sheet->setCellValue(
                    \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col + 2) . "17",
                    "Arcilla"
            );
            $sheet->setCellValue(
                    \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col + 3) . "17",
                    "Clase Textural"
            );

            // --------------------------------------------------------
            // %
            $sheet->mergeCells(
                    \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col) . "18:" .
                    \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col + 2) . "18"
            );

            $sheet->setCellValue(
                    \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col) . "18",
                    "%"
            );

            // --------------------------------------------------------
            // ANCHOS
            $sheet->getColumnDimension(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col))->setWidth(6);
            $sheet->getColumnDimension(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col + 1))->setWidth(6);
            $sheet->getColumnDimension(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col + 2))->setWidth(6);
            $sheet->getColumnDimension(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col + 3))->setWidth(25);

            // --------------------------------------------------------
            // ESTILOS
            $sheet->getStyle("{$colLetra}16:{$colFin}18")->applyFromArray([
                'font' => [
                    'bold' => true,
                    'size' => 10,
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ]);

            // --------------------------------------------------------
            // BORDES
            $sheet->getStyle("{$colLetra}16:{$colFin}18")
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        }

        // 🔥 devolver nueva posición de columna
        return $col + 4;
    }

    private function llenarDatosTextura($sheet1, $sheet2, $datos, $texturas, $col) {
        $filaBase = 19;

        for ($i = 0; $i < count($datos); $i++) {

            $row = $datos[$i];

            // 🔥 decidir hoja
            if ($i < 15) {
                $sheet = $sheet1;
                $fila = $filaBase + $i;
            } else {
                $sheet = $sheet2;
                $fila = $filaBase + ($i - 15);
            }

            $idlab = (string) $row->idlab;
            $t = $texturas[$idlab] ?? null;

            // columnas dinámicas
            $col1 = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);     // arena
            $col2 = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col + 1); // limo
            $col3 = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col + 2); // arcilla
            $col4 = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col + 3); // clase
            // --------------------------------------------------------
            // VALORES
            if ($t) {
                $sheet->setCellValue("{$col1}{$fila}", round($t['arena'], 1));
                $sheet->setCellValue("{$col2}{$fila}", round($t['limo'], 1));
                $sheet->setCellValue("{$col3}{$fila}", round($t['arcilla'], 1));
                $sheet->setCellValue("{$col4}{$fila}", $t['clase']);
            }

            // --------------------------------------------------------
            // 🔥 BORDES SOLO DONDE QUERÉS
            // arcilla → borde derecho
            $sheet->getStyle("{$col3}{$fila}")
                    ->getBorders()
                    ->getRight()
                    ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

            // clase textural → borde derecho
            $sheet->getStyle("{$col4}{$fila}")
                    ->getBorders()
                    ->getRight()
                    ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

            // --------------------------------------------------------
            // 🔥 ALINEACIÓN
            $sheet->getStyle("{$col1}{$fila}:{$col4}{$fila}")
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        }

        // 🔥 devolver siguiente columna
        return $col + 4;
    }

    private function hayTexturaPorDatos($datos, $texturas) {
        foreach ($datos as $row) {
            $idlab = (string) $row->idlab;

            if (!empty($texturas[$idlab])) {
                return true;
            }
        }

        return false;
    }

    private function llenarEncabezado($sheet, $data) {
        $sheet->setCellValue('B8', $data['encabezado']->numero ?? '');
        $sheet->setCellValue('B9', $data['encabezado']->cliente ?? '');
        $sheet->setCellValue('B10', $data['encabezado']->subcliente ?? '');
        $sheet->setCellValue('B11', $data['encabezado']->responsable ?? '');
        $sheet->setCellValue('B12', "-");
        $sheet->setCellValue('B13', ($data['encabezado']->provincia ?? '') . ", " . ($data['encabezado']->canton ?? ''));
        $sheet->setCellValue('B14', "-");
        $sheet->setCellValue('N11', $data['encabezado']->cultivo ?? '');
        $sheet->setCellValue('N13', $data['encabezado']->numero_muestras ?? '');

        // fecha recepción
        if (!empty($data['encabezado']->fecha)) {
            $excelDate = \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel(
                    \Carbon\Carbon::parse($data['encabezado']->fecha)
            );

            $sheet->setCellValue('Z11', $excelDate);
            $sheet->getStyle('Z11')->getNumberFormat()->setFormatCode('dd/mm/yyyy');
        }

        // fecha emisión
        $excelDate = \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel(now());

        $sheet->setCellValue('Z12', $excelDate);
        $sheet->getStyle('Z12')->getNumberFormat()->setFormatCode('dd/mm/yyyy');
    }

    private function getDatosReporte($id) {
        /* ------------------------------------------------ */
        // ENCABEZADO
        /* ------------------------------------------------ */
        $encabezado = DB::select(
                'CALL sp_reporte_cliente_encabezado(?)',
                [$id]
        );

        /* ------------------------------------------------ */
        // DATOS BASE
        /* ------------------------------------------------ */
        $datos = DB::select(
                'CALL sp_obtener_reporte_cliente(?)',
                [$id]
        );

        /* ------------------------------------------------ */
        // TEXTURA
        /* ------------------------------------------------ */
        $textura = DB::select(
                'CALL sp_reporte_cliente_textura(?)',
                [$id]
        );
        $texturas = Textura::calcularTexturas($textura);

        /* ------------------------------------------------ */
        // DENSIDAD APARENTE
        /* ------------------------------------------------ */
        $densidadAparente = DB::select(
                'CALL sp_reporte_cliente_densidad_aparente(?)',
                [$id]
        );

        $densidades = [];
        foreach ($densidadAparente as $m) {
            $da = DensidadAparente::calcular_densidad(
                    $m->altura_cilindro,
                    $m->diametro_cilindro,
                    $m->peso_seco,
                    $m->peso_cilindro
            );

            if ($da !== null) {
                $densidades[(string) $m->idlab] = $da;
            }
        }

        /* ------------------------------------------------ */
        // DENSIDAD PARTICULAS
        /* ------------------------------------------------ */
        $densidadParticulas = DB::select(
                'CALL sp_reporte_cliente_densidad_particulas(?)',
                [$id]
        );

        $densidadesParticulas = [];
        foreach ($densidadParticulas as $m) {
            $dp = DensidadParticulas::calcular(
                    $m->numero_balon,
                    $m->p1,
                    $m->p2,
                    $m->p3,
                    $m->temperatura
            );

            if ($dp !== null) {
                $densidadesParticulas[(string) $m->idlab] = $dp;
            }
        }

        /* ------------------------------------------------ */
        // POROSIDAD
        /* ------------------------------------------------ */
        $porosidades = Porosidad::calcular(
                $densidades,
                $densidadesParticulas,
                $datos
        );

        /* ------------------------------------------------ */
        // HUMEDAD
        /* ------------------------------------------------ */
        $humedadGravimetrica = DB::select(
                'CALL sp_reporte_cliente_humedad_gravimetrica(?)',
                [$id]
        );

        $humedades = [];
        foreach ($humedadGravimetrica as $m) {
            $hg = HumedadGravimetrica::calcular($m->pc, $m->ph, $m->ps);

            if ($hg !== null) {
                $humedades[(string) $m->idlab] = $hg;
            }
        }

        /* ------------------------------------------------ */
        // CONDUCTIVIDAD
        /* ------------------------------------------------ */
        $conductividadHidraulica = DB::select(
                'CALL sp_reporte_cliente_conductividad_hidraulica(?)',
                [$id]
        );
        $conductividades = conductividadHidraulica::calcularConductividades($conductividadHidraulica);

        /* ------------------------------------------------ */
        // RETENCION
        /* ------------------------------------------------ */
        $retencionHumedad = DB::select(
                'CALL sp_reporte_cliente_retencion_humedad(?)',
                [$id]
        );
        $retenciones = RetencionHumedad::calcularRetenciones($retencionHumedad);

        /* ------------------------------------------------ */
        // ESTABILIDAD
        /* ------------------------------------------------ */
        $estabilidadAgregados = DB::select(
                'CALL sp_reporte_cliente_estabilidad_agregados(?)',
                [$id]
        );
        $estabilidades = EstabilidadAgregados::calcular($estabilidadAgregados);

        /* ------------------------------------------------ */
        // COEL
        /* ------------------------------------------------ */
        $coel = DB::select(
                'CALL sp_reporte_cliente_coeficiente_extensibilidad(?)',
                [$id]
        );
        $coeles = CoeficienteExtensibilidad::calcularPorMuestras($coel);

        /* ------------------------------------------------ */
        // GRANULOMETRIA
        /* ------------------------------------------------ */
        $granulometria = DB::select(
                'CALL sp_reporte_cliente_granulometria(?)',
                [$id]
        );
        $granulometrias = Granulometria::calcularPorMuestras($granulometria);

        return [
            'id' => $id,
            'encabezado' => $encabezado[0] ?? null,
            'datos' => $datos,
            'texturas' => $texturas,
            'densidades' => $densidades,
            'densidadesParticulas' => $densidadesParticulas,
            'porosidades' => $porosidades,
            'humedades' => $humedades,
            'conductividades' => $conductividades,
            'retenciones' => $retenciones,
            'estabilidades' => $estabilidades,
            'coeles' => $coeles,
            'granulometrias' => $granulometrias
        ];
    }
}

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

        // hojas
        $sheet1 = $spreadsheet->getSheet(0);
        $sheet2 = $spreadsheet->getSheet(1);

        // encabezado en ambas hojas
        $this->llenarEncabezado($sheet1, $data);
        $this->llenarEncabezado($sheet2, $data);

        //------------------------------------------------------------------------
        // llenar encabezados de analisis
        //------------------------------------------------------------------------        
        $col = 5; // E
        $cantidadHojas = count($data['datos']) > 15 ? 2 : 1;

        //TEXTURA
        if ($this->hayTexturaPorDatos($data['datos'], $data['texturas'])) {
            $col = $this->dibujarEncabezadoTextura($spreadsheet, $col, $cantidadHojas);
        }

        //DA
        if ($this->hayDensidadAparentePorDatos($data['datos'], $data['densidades'])) {
            $col = $this->dibujarEncabezadoDensidadAparente($spreadsheet, $col, $cantidadHojas);
        }

        //DP
        if ($this->hayDensidadParticulasPorDatos($data['datos'], $data['densidadesParticulas'])) {
            $col = $this->dibujarEncabezadoDensidadParticulas($spreadsheet, $col, $cantidadHojas);
        }

        // POROSIDAD
        if ($this->hayPorosidadPorDatos(
                        $data['datos'],
                        $data['densidades'],
                        $data['densidadesParticulas']
                )) {
            $col = $this->dibujarEncabezadoPorosidad($spreadsheet, $col, $cantidadHojas);
        }

        // HUMEDAD
        if ($this->hayHumedadPorDatos($data['datos'], $data['humedades'])) {
            $col = $this->dibujarEncabezadoHumedad($spreadsheet, $col, $cantidadHojas);
        }

        // CONDUCTIVIDAD
        if ($this->hayConductividadPorDatos($data['datos'], $data['conductividades'])) {
            $col = $this->dibujarEncabezadoConductividad($spreadsheet, $col, $cantidadHojas);
        }

        // RETENCION DE HUMEDAD
        if ($this->hayRetencionHumedadPorDatos($data['datos'], $data['retenciones'])) {
            $col = $this->dibujarEncabezadoRetencionHumedad($spreadsheet, $col, $cantidadHojas);
        }

        // ESTABILIDAD DE AGREGADOS
        if ($this->hayEstabilidadAgregadosPorDatos($data['datos'], $data['estabilidades'])) {
            $col = $this->dibujarEncabezadoEstabilidadAgregados($spreadsheet, $col, $cantidadHojas);
        }

        // COEFICIENTE DE EXTENSIBILIDAD
        if ($this->hayCoeficienteExtensibilidadPorDatos($data['datos'], $data['coeficientes'])) {
            $col = $this->dibujarEncabezadoCoeficienteExtensibilidad($spreadsheet, $col, $cantidadHojas);
        }


        //------------------------------------------------------------------------
        //------------------------------------------------------------------------
        // llenar datos
        //------------------------------------------------------------------------
        // Textura
        // -----------------------------------------------------------------------
        $col = 5;
        if ($this->hayTexturaPorDatos($data['datos'], $data['texturas'])) {

            $colTextura = $col; // guardar inicio
            // llenar datos usando el inicio
            $this->llenarDatosTextura(
                    $sheet1,
                    $sheet2,
                    $data['datos'],
                    $data['texturas'],
                    $colTextura
            );

            $col += 4;
        }
        // DA
        // -----------------------------------------------------------------------

        if ($this->hayDensidadAparentePorDatos($data['datos'], $data['densidades'])) {

            $this->llenarDatosDensidadAparente(
                    $sheet1,
                    $sheet2,
                    $data['datos'],
                    $data['densidades'],
                    $col
            );

            $col += 1;
        }

        // DP
        // -----------------------------------------------------------------------
        if ($this->hayDensidadParticulasPorDatos($data['datos'], $data['densidadesParticulas'])) {

            $colDP = $col;

            $this->llenarDatosDensidadParticulas(
                    $sheet1,
                    $sheet2,
                    $data['datos'],
                    $data['densidadesParticulas'],
                    $colDP
            );

            $col += 1;
        }

        // POROSIDAD
        if ($this->hayPorosidadPorDatos(
                        $data['datos'],
                        $data['densidades'],
                        $data['densidadesParticulas']
                )) {

            $colP = $col;

            $this->llenarDatosPorosidad(
                    $sheet1,
                    $sheet2,
                    $data['datos'],
                    $data['densidades'],
                    $data['densidadesParticulas'],
                    $colP
            );

            $col += 1;
        }

        // HUMEDAD
        if ($this->hayHumedadPorDatos($data['datos'], $data['humedades'])) {

            $colH = $col;

            $this->llenarDatosHumedad(
                    $sheet1,
                    $sheet2,
                    $data['datos'],
                    $data['humedades'],
                    $colH
            );

            $col += 1;
        }

        // CONDUCTIVIDAD
        if ($this->hayConductividadPorDatos($data['datos'], $data['conductividades'])) {

            $colC = $col;

            $this->llenarDatosConductividad(
                    $sheet1,
                    $sheet2,
                    $data['datos'],
                    $data['conductividades'],
                    $colC
            );

            $col += 1;
        }

        //RETENCION DE HUMEDAD
        if ($this->hayRetencionHumedadPorDatos($data['datos'], $data['retenciones'])) {

            $colRH = $col;

            $this->llenarDatosRetencionHumedad(
                    $sheet1,
                    $sheet2,
                    $data['datos'],
                    $data['retenciones'],
                    $colRH
            );

            $col += 3;
        }

        // ESTABILIDAD DE AGREGADOS   
        if ($this->hayEstabilidadAgregadosPorDatos($data['datos'], $data['estabilidades'])) {

            $colEA = $col;

            $this->llenarDatosEstabilidadAgregados(
                    $sheet1,
                    $sheet2,
                    $data['datos'],
                    $data['estabilidades'],
                    $colEA
            );

            $col += 2;
        }

        // COEFICIENTE DE EXTENSIBILIDAD
        if ($this->hayCoeficienteExtensibilidadPorDatos($data['datos'], $data['coeficientes'])) {

            $colCE = $col;

            $this->llenarDatosCoeficienteExtensibilidad(
                    $sheet1,
                    $sheet2,
                    $data['datos'],
                    $data['coeficientes'],
                    $colCE
            );

            $col += 1;
        }

        //---------------------------------------------------------------------------
        // IDS
        //---------------------------------------------------------------------------
        $filaBase = 19;

        foreach ($data['datos'] as $index => $row) {

            // decidir hoja
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

        // ocultar hoja 2 si no se usa
        if (count($data['datos']) <= 15) {
            $sheet2->setSheetState(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::SHEETSTATE_HIDDEN);
        }

        // descargar
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

        // devolver nueva posición de columna
        return $col + 4;
    }

    private function dibujarEncabezadoDensidadAparente($spreadsheet, $col, $cantidadHojas) {

        for ($i = 0; $i < $cantidadHojas; $i++) {

            $sheet = $spreadsheet->getSheet($i);

            $colLetra = Coordinate::stringFromColumnIndex($col);

            // TITULO
            $sheet->setCellValue("{$colLetra}16", "DA");

            // SUBHEADER
            $sheet->setCellValue("{$colLetra}17", "Densidad");

            // %
            $sheet->setCellValue("{$colLetra}18", "");

            // ancho
            $sheet->getColumnDimension($colLetra)->setWidth(10);

            // estilos
            $sheet->getStyle("{$colLetra}16:{$colLetra}18")->applyFromArray([
                'font' => ['bold' => true, 'size' => 10],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ]);

            // bordes
            $sheet->getStyle("{$colLetra}16:{$colLetra}18")
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);
        }

        return $col + 1;
    }

    private function dibujarEncabezadoDensidadParticulas($spreadsheet, $col, $cantidadHojas) {

        for ($i = 0; $i < $cantidadHojas; $i++) {

            $sheet = $spreadsheet->getSheet($i);

            $colLetra = Coordinate::stringFromColumnIndex($col);

            // TITULO
            $sheet->setCellValue("{$colLetra}16", "DP");

            // SUBHEADER
            $sheet->setCellValue("{$colLetra}17", "Densidad");

            // vacío fila 18
            $sheet->setCellValue("{$colLetra}18", "");

            // ancho
            $sheet->getColumnDimension($colLetra)->setWidth(10);

            // estilos
            $sheet->getStyle("{$colLetra}16:{$colLetra}18")->applyFromArray([
                'font' => ['bold' => true, 'size' => 10],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ]);

            // bordes
            $sheet->getStyle("{$colLetra}16:{$colLetra}18")
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);
        }

        return $col + 1;
    }

    private function dibujarEncabezadoPorosidad($spreadsheet, $col, $cantidadHojas) {

        for ($i = 0; $i < $cantidadHojas; $i++) {

            $sheet = $spreadsheet->getSheet($i);

            $colLetra = Coordinate::stringFromColumnIndex($col);

            // TITULO
            $sheet->setCellValue("{$colLetra}16", "Porosidad");

            // SUBHEADER
            $sheet->setCellValue("{$colLetra}17", "P");

            // %
            $sheet->setCellValue("{$colLetra}18", "%");

            // ancho
            $sheet->getColumnDimension($colLetra)->setWidth(10);

            // estilos
            $sheet->getStyle("{$colLetra}16:{$colLetra}18")->applyFromArray([
                'font' => ['bold' => true, 'size' => 10],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ]);

            // bordes
            $sheet->getStyle("{$colLetra}16:{$colLetra}18")
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);
        }

        return $col + 1;
    }

    private function dibujarEncabezadoHumedad($spreadsheet, $col, $cantidadHojas) {

        for ($i = 0; $i < $cantidadHojas; $i++) {

            $sheet = $spreadsheet->getSheet($i);
            $colLetra = Coordinate::stringFromColumnIndex($col);

            // TITULO
            $sheet->setCellValue("{$colLetra}16", "Humedad");

            // SUBHEADER
            $sheet->setCellValue("{$colLetra}17", "Hg");

            // %
            $sheet->setCellValue("{$colLetra}18", "%");

            // ancho
            $sheet->getColumnDimension($colLetra)->setWidth(10);

            // estilos
            $sheet->getStyle("{$colLetra}16:{$colLetra}18")->applyFromArray([
                'font' => ['bold' => true, 'size' => 10],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ]);

            // bordes
            $sheet->getStyle("{$colLetra}16:{$colLetra}18")
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);
        }

        return $col + 1;
    }

    private function dibujarEncabezadoConductividad($spreadsheet, $col, $cantidadHojas) {

        for ($i = 0; $i < $cantidadHojas; $i++) {

            $sheet = $spreadsheet->getSheet($i);
            $colLetra = Coordinate::stringFromColumnIndex($col);

            // TITULO
            $sheet->setCellValue("{$colLetra}16", "Conductividad");

            // SUBHEADER
            $sheet->setCellValue("{$colLetra}17", "K");

            // UNIDAD
            $sheet->setCellValue("{$colLetra}18", "cm/s");

            // ancho
            $sheet->getColumnDimension($colLetra)->setWidth(12);

            // estilos
            $sheet->getStyle("{$colLetra}16:{$colLetra}18")->applyFromArray([
                'font' => ['bold' => true, 'size' => 10],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ]);

            // bordes
            $sheet->getStyle("{$colLetra}16:{$colLetra}18")
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);
        }

        return $col + 1;
    }

    private function dibujarEncabezadoRetencionHumedad($spreadsheet, $col, $cantidadHojas) {

        for ($i = 0; $i < $cantidadHojas; $i++) {

            $sheet = $spreadsheet->getSheet($i);

            $colLetra = Coordinate::stringFromColumnIndex($col);
            $colFin = Coordinate::stringFromColumnIndex($col + 2);

            // -------------------------------
            // TITULO (merge 3 columnas)
            // -------------------------------
            $sheet->mergeCells("{$colLetra}16:{$colFin}16");
            $sheet->setCellValue("{$colLetra}16", "Ret. Humedad");

            // -------------------------------
            // SUBHEADERS
            // -------------------------------
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($col) . "17", "Hg 33 kPa");
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($col + 1) . "17", "Hg 1500 kPa");
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($col + 2) . "17", "Agua Disp.");

            // -------------------------------
            // %
            // -------------------------------
            $sheet->mergeCells("{$colLetra}18:{$colFin}18");
            $sheet->setCellValue("{$colLetra}18", "%");

            // -------------------------------
            // ANCHO (opcional pero recomendado)
            // -------------------------------
            $sheet->getColumnDimension(Coordinate::stringFromColumnIndex($col))->setWidth(12);
            $sheet->getColumnDimension(Coordinate::stringFromColumnIndex($col + 1))->setWidth(12);
            $sheet->getColumnDimension(Coordinate::stringFromColumnIndex($col + 2))->setWidth(14);

            // -------------------------------
            // ESTILOS
            // -------------------------------
            $sheet->getStyle("{$colLetra}16:{$colFin}18")->applyFromArray([
                'font' => ['bold' => true, 'size' => 10],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ]);

            // -------------------------------
            // BORDES
            // -------------------------------
            $sheet->getStyle("{$colLetra}16:{$colFin}18")
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);
        }

        // 🔥 AVANZA 3 COLUMNAS (CORRECTO)
        return $col + 3;
    }

    private function dibujarEncabezadoEstabilidadAgregados($spreadsheet, $col, $cantidadHojas) {

        for ($i = 0; $i < $cantidadHojas; $i++) {

            $sheet = $spreadsheet->getSheet($i);

            $colLetra = Coordinate::stringFromColumnIndex($col);
            $colFin = Coordinate::stringFromColumnIndex($col + 1);

            // -------------------------------
            // TITULO (merge 2 columnas)
            // -------------------------------
            $sheet->mergeCells("{$colLetra}16:{$colFin}16");
            $sheet->setCellValue("{$colLetra}16", "Est. Agregados");

            // -------------------------------
            // SUBHEADERS
            // -------------------------------
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($col) . "17", "DMP");
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($col + 1) . "17", "EAA");

            // -------------------------------
            // UNIDADES
            // -------------------------------
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($col) . "18", "mm");
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($col + 1) . "18", "%");

            // -------------------------------
            // ANCHO
            // -------------------------------
            $sheet->getColumnDimension(Coordinate::stringFromColumnIndex($col))->setWidth(10);
            $sheet->getColumnDimension(Coordinate::stringFromColumnIndex($col + 1))->setWidth(10);

            // -------------------------------
            // ESTILOS
            // -------------------------------
            $sheet->getStyle("{$colLetra}16:{$colFin}18")->applyFromArray([
                'font' => ['bold' => true, 'size' => 10],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ]);

            // -------------------------------
            // BORDES
            // -------------------------------
            $sheet->getStyle("{$colLetra}16:{$colFin}18")
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);
        }


        return $col + 2;
    }

    private function dibujarEncabezadoCoeficienteExtensibilidad($spreadsheet, $col, $cantidadHojas) {
        for ($i = 0; $i < $cantidadHojas; $i++) {

            $sheet = $spreadsheet->getSheet($i);
            $colLetra = Coordinate::stringFromColumnIndex($col);

            // TITULO
            $sheet->setCellValue("{$colLetra}16", "Coeficiente de Extensibilidad");

            // SUBHEADER
            $sheet->setCellValue("{$colLetra}17", "CE");

            // UNIDAD
            $sheet->setCellValue("{$colLetra}18", "");

            // ancho
            $sheet->getColumnDimension($colLetra)->setWidth(20);

            // estilos
            $sheet->getStyle("{$colLetra}16:{$colLetra}18")->applyFromArray([
                'font' => ['bold' => true, 'size' => 10],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ]);

            // bordes
            $sheet->getStyle("{$colLetra}16:{$colLetra}18")
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);
        }

        return $col + 1;
    }

    private function llenarDatosTextura($sheet1, $sheet2, $datos, $texturas, $col) {
        $filaBase = 19;

        for ($i = 0; $i < count($datos); $i++) {

            $row = $datos[$i];

            // decidir hoja
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
            // BORDES
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
            // ALINEACIÓN
            $sheet->getStyle("{$col1}{$fila}:{$col4}{$fila}")
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        }

        // devolver siguiente columna
        return $col + 4;
    }

    private function llenarDatosDensidadAparente($sheet1, $sheet2, $datos, $densidades, $col) {

        $filaBase = 19;

        for ($i = 0; $i < count($datos); $i++) {

            $row = $datos[$i];

            if ($i < 15) {
                $sheet = $sheet1;
                $fila = $filaBase + $i;
            } else {
                $sheet = $sheet2;
                $fila = $filaBase + ($i - 15);
            }

            $idlab = (string) $row->idlab;
            $valor = $densidades[$idlab] ?? null;

            $colLetra = Coordinate::stringFromColumnIndex($col);

            if ($valor !== null) {
                $sheet->setCellValue("{$colLetra}{$fila}", round($valor, 3));
            }

            // borde derecho
            $sheet->getStyle("{$colLetra}{$fila}")
                    ->getBorders()
                    ->getRight()
                    ->setBorderStyle(Border::BORDER_THIN);

            // alineación
            $sheet->getStyle("{$colLetra}{$fila}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        return $col + 1;
    }

    private function llenarDatosDensidadParticulas($sheet1, $sheet2, $datos, $densidadesParticulas, $col) {

        $filaBase = 19;

        for ($i = 0; $i < count($datos); $i++) {

            $row = $datos[$i];

            // hoja
            if ($i < 15) {
                $sheet = $sheet1;
                $fila = $filaBase + $i;
            } else {
                $sheet = $sheet2;
                $fila = $filaBase + ($i - 15);
            }

            $idlab = (string) $row->idlab;
            $dp = $densidadesParticulas[$idlab] ?? null;

            $colLetra = Coordinate::stringFromColumnIndex($col);

            if ($dp !== null) {
                $sheet->setCellValue("{$colLetra}{$fila}", round($dp, 3));
            }

            // borde derecho
            $sheet->getStyle("{$colLetra}{$fila}")
                    ->getBorders()
                    ->getRight()
                    ->setBorderStyle(Border::BORDER_THIN);

            // centrado
            $sheet->getStyle("{$colLetra}{$fila}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        return $col + 1;
    }

    private function llenarDatosPorosidad(
            $sheet1,
            $sheet2,
            $datos,
            $densidades,
            $densidadesParticulas,
            $col
    ) {

        $filaBase = 19;

        for ($i = 0; $i < count($datos); $i++) {

            $row = $datos[$i];

            // hoja
            if ($i < 15) {
                $sheet = $sheet1;
                $fila = $filaBase + $i;
            } else {
                $sheet = $sheet2;
                $fila = $filaBase + ($i - 15);
            }

            $idlab = (string) $row->idlab;

            $da = $densidades[$idlab] ?? null;
            $dp = $densidadesParticulas[$idlab] ?? null;

            $colLetra = Coordinate::stringFromColumnIndex($col);

            if ($da !== null && $dp !== null && $dp != 0) {

                $p = (1 - ($da / $dp)) * 100;

                $sheet->setCellValue("{$colLetra}{$fila}", round($p, 2));
            }

            // borde
            $sheet->getStyle("{$colLetra}{$fila}")
                    ->getBorders()
                    ->getRight()
                    ->setBorderStyle(Border::BORDER_THIN);

            // centrado
            $sheet->getStyle("{$colLetra}{$fila}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        return $col + 1;
    }

    private function llenarDatosHumedad($sheet1, $sheet2, $datos, $humedades, $col) {

        $filaBase = 19;

        for ($i = 0; $i < count($datos); $i++) {

            $row = $datos[$i];

            if ($i < 15) {
                $sheet = $sheet1;
                $fila = $filaBase + $i;
            } else {
                $sheet = $sheet2;
                $fila = $filaBase + ($i - 15);
            }

            $idlab = (string) $row->idlab;
            $hg = $humedades[$idlab] ?? null;

            $colLetra = Coordinate::stringFromColumnIndex($col);

            if ($hg !== null) {
                $sheet->setCellValue("{$colLetra}{$fila}", round($hg, 2));
            }

            // borde derecho
            $sheet->getStyle("{$colLetra}{$fila}")
                    ->getBorders()
                    ->getRight()
                    ->setBorderStyle(Border::BORDER_THIN);

            // centrado
            $sheet->getStyle("{$colLetra}{$fila}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        return $col + 1;
    }

    private function llenarDatosConductividad($sheet1, $sheet2, $datos, $conductividades, $col) {

        $filaBase = 19;

        for ($i = 0; $i < count($datos); $i++) {

            $row = $datos[$i];

            if ($i < 15) {
                $sheet = $sheet1;
                $fila = $filaBase + $i;
            } else {
                $sheet = $sheet2;
                $fila = $filaBase + ($i - 15);
            }

            $idlab = (string) $row->idlab;

            $k = $conductividades[$idlab]['conductividad_hidraulica'] ?? null;

            $colLetra = Coordinate::stringFromColumnIndex($col);

            if ($k !== null) {
                $sheet->setCellValue("{$colLetra}{$fila}", $k);
            }

            // borde derecho
            $sheet->getStyle("{$colLetra}{$fila}")
                    ->getBorders()
                    ->getRight()
                    ->setBorderStyle(Border::BORDER_THIN);

            // centrado
            $sheet->getStyle("{$colLetra}{$fila}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        return $col + 1;
    }

    private function llenarDatosRetencionHumedad($sheet1, $sheet2, $datos, $retenciones, $col) {

        $filaBase = 19;

        for ($i = 0; $i < count($datos); $i++) {

            $row = $datos[$i];

            // hoja
            if ($i < 15) {
                $sheet = $sheet1;
                $fila = $filaBase + $i;
            } else {
                $sheet = $sheet2;
                $fila = $filaBase + ($i - 15);
            }

            $idlab = (string) $row->idlab;

            // 🔥 OBTENER TODO EL OBJETO
            $rh = $retenciones[$idlab] ?? null;

            // columnas
            $col1 = Coordinate::stringFromColumnIndex($col);
            $col2 = Coordinate::stringFromColumnIndex($col + 1);
            $col3 = Coordinate::stringFromColumnIndex($col + 2);

            // -------------------------------
            // VALORES
            // -------------------------------
            if ($rh) {

                $sheet->setCellValue("{$col1}{$fila}", isset($rh['Hg_33']) ? round($rh['Hg_33'], 4) : null);
                $sheet->setCellValue("{$col2}{$fila}", isset($rh['Hg_1500']) ? round($rh['Hg_1500'], 4) : null);
                $sheet->setCellValue("{$col3}{$fila}", isset($rh['agua_disponible']) ? round($rh['agua_disponible'], 4) : null);
            }

            // -------------------------------
            // BORDES
            // -------------------------------
            $sheet->getStyle("{$col1}{$fila}:{$col3}{$fila}")
                    ->getBorders()
                    ->getRight()
                    ->setBorderStyle(Border::BORDER_THIN);

            // -------------------------------
            // CENTRADO
            // -------------------------------
            $sheet->getStyle("{$col1}{$fila}:{$col3}{$fila}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        // 🔥 AVANZA 3 COLUMNAS
        return $col + 3;
    }

    private function llenarDatosEstabilidadAgregados($sheet1, $sheet2, $datos, $estabilidades, $col) {

        $filaBase = 19;

        for ($i = 0; $i < count($datos); $i++) {

            $row = $datos[$i];

            // hoja
            if ($i < 15) {
                $sheet = $sheet1;
                $fila = $filaBase + $i;
            } else {
                $sheet = $sheet2;
                $fila = $filaBase + ($i - 15);
            }

            $idlab = (string) $row->idlab;

            // 🔥 obtener objeto completo
            $ea = $estabilidades[$idlab] ?? null;

            // columnas
            $col1 = Coordinate::stringFromColumnIndex($col);
            $col2 = Coordinate::stringFromColumnIndex($col + 1);

            // -------------------------------
            // VALORES
            // -------------------------------
            if ($ea) {
                $sheet->setCellValue("{$col1}{$fila}", round($ea['dmp'], 2));
                $sheet->setCellValue("{$col2}{$fila}", round($ea['eaa'], 2));
            }

            // -------------------------------
            // BORDES
            // -------------------------------
            $sheet->getStyle("{$col1}{$fila}:{$col2}{$fila}")
                    ->getBorders()
                    ->getRight()
                    ->setBorderStyle(Border::BORDER_THIN);

            // -------------------------------
            // CENTRADO
            // -------------------------------
            $sheet->getStyle("{$col1}{$fila}:{$col2}{$fila}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        // 🔥 AVANZA 2 COLUMNAS
        return $col + 2;
    }

    private function llenarDatosCoeficienteExtensibilidad($sheet1, $sheet2, $datos, $coeficientes, $col) {
        $filaBase = 19;

        for ($i = 0; $i < count($datos); $i++) {

            $row = $datos[$i];

            if ($i < 15) {
                $sheet = $sheet1;
                $fila = $filaBase + $i;
            } else {
                $sheet = $sheet2;
                $fila = $filaBase + ($i - 15);
            }

          $idlab = (int) $row->idlab;
            

           $ce = $coeficientes[$idlab]['cole'] ?? null;
            //dd($coeficientes, $ce);
            $colLetra = Coordinate::stringFromColumnIndex($col);

$sheet->setCellValue(
    "{$colLetra}{$fila}",
    $ce !== null ? round($ce, 4) : ''
);

            // borde derecho
            $sheet->getStyle("{$colLetra}{$fila}")
                    ->getBorders()
                    ->getRight()
                    ->setBorderStyle(Border::BORDER_THIN);

            // centrado
            $sheet->getStyle("{$colLetra}{$fila}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        return $col + 1;
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

    private function hayDensidadAparentePorDatos($datos, $densidades) {
        foreach ($datos as $row) {
            $idlab = (string) $row->idlab;

            if (isset($densidades[$idlab])) {
                return true;
            }
        }

        return false;
    }

    private function hayDensidadParticulasPorDatos($datos, $densidadesParticulas) {
        foreach ($datos as $row) {
            $idlab = (string) $row->idlab;

            if (isset($densidadesParticulas[$idlab])) {
                return true;
            }
        }

        return false;
    }

    private function hayPorosidadPorDatos($datos, $densidades, $densidadesParticulas) {
        foreach ($datos as $row) {
            $idlab = (string) $row->idlab;

            if (
                    isset($densidades[$idlab]) &&
                    isset($densidadesParticulas[$idlab])
            ) {
                return true;
            }
        }

        return false;
    }

    private function hayHumedadPorDatos($datos, $humedades) {
        foreach ($datos as $row) {
            $idlab = (string) $row->idlab;

            if (isset($humedades[$idlab])) {
                return true;
            }
        }

        return false;
    }

    private function hayConductividadPorDatos($datos, $conductividades) {
        foreach ($datos as $row) {
            $idlab = (string) $row->idlab;

            if (isset($conductividades[$idlab])) {
                return true;
            }
        }

        return false;
    }

    private function hayRetencionHumedadPorDatos($datos, $retenciones) {
        foreach ($datos as $row) {
            $idlab = (string) $row->idlab;

            if (isset($retenciones[$idlab])) {
                return true;
            }
        }

        return false;
    }

    private function hayEstabilidadAgregadosPorDatos($datos, $estabilidades) {
        foreach ($datos as $row) {
            $idlab = (string) $row->idlab;

            if (isset($estabilidades[$idlab])) {
                return true;
            }
        }

        return false;
    }

    private function hayCoeficienteExtensibilidadPorDatos($datos, $coeficientes) {
        foreach ($datos as $row) {
            $idlab = (string) $row->idlab;

            if (isset($coeficientes[$idlab])) {
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
        //dd($retenciones);

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
        //dd($coeles);
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
            'coeficientes' => $coeles,
            'granulometrias' => $granulometrias
        ];
    }
}

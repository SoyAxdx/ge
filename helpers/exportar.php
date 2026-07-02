<?php
// ============================================
// FUNCIONES PARA EXPORTAR DATOS
// ============================================

use Dompdf\Dompdf;
use Dompdf\Options;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * Exportar datos a PDF usando dompdf
 */
function exportarPDF($html, $titulo = 'Reporte') {
    $options = new Options();
    $options->set('defaultFont', 'Arial');
    
    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream($titulo . '.pdf', ['Attachment' => true]);
    exit();
}

/**
 * Exportar datos a Excel usando PhpSpreadsheet
 */
function exportarExcel($datos, $columnas, $titulo = 'Reporte') {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    
    // Encabezados
    $col = 'A';
    foreach ($columnas as $nombre) {
        $sheet->setCellValue($col . '1', $nombre);
        $sheet->getColumnDimension($col)->setAutoSize(true);
        $col++;
    }
    
    // Datos
    $fila = 2;
    foreach ($datos as $row) {
        $col = 'A';
        foreach ($row as $valor) {
            $sheet->setCellValue($col . $fila, $valor);
            $col++;
        }
        $fila++;
    }
    
    $writer = new Xlsx($spreadsheet);
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $titulo . '.xlsx"');
    $writer->save('php://output');
    exit();
}
?>
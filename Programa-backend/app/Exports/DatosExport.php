<?php
namespace App\Exports;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class DatosExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths, WithEvents
{
    protected $datos;
    protected $headings;
    
    public function __construct($datos, $headings)
    {
        $this->datos = $datos;
        $this->headings = $headings;
    }
    public function collection()
    {
        return collect($this->datos);
    }
    public function headings(): array
    {
        // Extraer las horas de los headings recibidos
        $horas = [];
        foreach ($this->headings as $h) {
            if (preg_match('/^(\d{1,2}):00 Productividad$/', $h, $m)) {
                $horas[] = $m[1];
            }
        }
        
        // Cambiar encabezados principales
        $headerRow1 = ['N°','Asesor', 'Asesor Real', 'Cartera', 'Logueo'];
        foreach ($horas as $hora) {
            $headerRow1[] = sprintf('%02d:00', $hora);
            $headerRow1[] = '';
        }
        // Para Total, igual que las horas: dos columnas
        $headerRow1[] = 'Total';
        $headerRow1[] = '';
        // Para Novedad, una sola columna
        $headerRow1[] = 'Novedades';

        $headerRow2 = ['N°','Asesor', 'Asesor Real', 'Cartera', ''];
        foreach ($horas as $hora) {
            $headerRow2[] = 'Huella';
            $headerRow2[] = 'Marcación';
        }
        $headerRow2[] = 'Huella';
        $headerRow2[] = 'Marcación';
        // Para Novedad, celda vacía
        $headerRow2[] = '';

        $headerRow1 = array_slice($headerRow1, 0, count($headerRow2));

        return [$headerRow1, $headerRow2];
    }
    public function columnWidths(): array
    {
        $widths = [
            'A' => 3.5,
            'B' => 40,
            'C' => 40,
            'D' => 25,
            'E' => 15, 
        ];

        $horas = [];
        foreach ($this->headings as $h) {
            if (preg_match('/^(\d{1,2}):00 Productividad$/', $h)) {
                $horas[] = $h;
            }
        }

        $colIndex = 6; 
        foreach ($horas as $hora) {
            $widths[Coordinate::stringFromColumnIndex($colIndex++)] = 9; // Huella
            $widths[Coordinate::stringFromColumnIndex($colIndex++)] = 9; // Marcación
        }

        
        $widths[Coordinate::stringFromColumnIndex($colIndex++)] = 9;
        $widths[Coordinate::stringFromColumnIndex($colIndex++)] = 9;

        
        $widths[Coordinate::stringFromColumnIndex($colIndex)] = 15;

        return $widths;
    }
    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        $headerRange = 'A1:' . $highestColumn . '2';
        $sheet->getStyle($headerRange)->getFont()->setBold(true);
        $sheet->getStyle($headerRange)->getFill()->setFillType('solid')->getStartColor()->setARGB('FFB7E1FA');
        $sheet->getStyle($headerRange)->getAlignment()->setHorizontal('center');

        $tableRange = 'A1:' . $highestColumn . $highestRow;
        $sheet->getStyle($tableRange)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        return [];
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Unificar las cinco primeras columnas en las filas 1 y 2
                $sheet->mergeCells('A1:A2');
                $sheet->mergeCells('B1:B2');
                $sheet->mergeCells('C1:C2');
                $sheet->mergeCells('D1:D2');
                $sheet->mergeCells('E1:E2'); // Nueva Columna
                $sheet->getStyle('A1:E2')->getAlignment()->setHorizontal('center');
                $sheet->getStyle('A1:E2')->getAlignment()->setVertical('center');
                $sheet->getStyle('A1:E2')->getFont()->setBold(true);

                // Extraer las horas reales de los headings
                $horas = [];
                foreach ($this->headings as $h) {
                    if (preg_match('/^(\d{1,2}):00 Productividad$/', $h, $m)) {
                        $horas[] = $m[1];
                    }
                }

                $startColumnIndex = 6; // Las horas empiezan en la columna F
                foreach ($horas as $hora) {
                    $col1 = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startColumnIndex);
                    $col2 = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startColumnIndex + 1);
                    $sheet->mergeCells("{$col1}1:{$col2}1");
                    $sheet->setCellValue("{$col1}1", sprintf('%02d:00', $hora));
                    $sheet->getStyle("{$col1}1:{$col2}1")->getAlignment()->setHorizontal('center');
                    $sheet->getStyle("{$col1}1:{$col2}1")->getFont()->setBold(true);
                    $sheet->getStyle("{$col1}1:{$col2}1")->getFill()->setFillType('solid')->getStartColor()->setARGB('FFB7E1FA');
                    $startColumnIndex += 2;
                }

                // Para Total, igual que las horas: dos columnas
                $col1 = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startColumnIndex);
                $col2 = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startColumnIndex + 1);
                $sheet->mergeCells("{$col1}1:{$col2}1");
                $sheet->setCellValue("{$col1}1", 'Total');
                $sheet->getStyle("{$col1}1:{$col2}1")->getAlignment()->setHorizontal('center');
                $sheet->getStyle("{$col1}1:{$col2}1")->getFont()->setBold(true);
                $sheet->getStyle("{$col1}1:{$col2}1")->getFill()->setFillType('solid')->getStartColor()->setARGB('FFB7E1FA');

                // Unificar la última columna (Novedad) en las filas 1 y 2
                $colEnd = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($sheet->getHighestColumn());
                $lastColLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colEnd);
                $sheet->mergeCells("{$lastColLetter}1:{$lastColLetter}2");
                $sheet->getStyle("{$lastColLetter}1:{$lastColLetter}2")->getAlignment()->setHorizontal('center');
                $sheet->getStyle("{$lastColLetter}1:{$lastColLetter}2")->getAlignment()->setVertical('center');
                $sheet->getStyle("{$lastColLetter}1:{$lastColLetter}2")->getFont()->setBold(true);

                // Rellenar con ceros las celdas vacías en las columnas de horas y total
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();
                $colStart = 4; 
                $colEnd = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);
                for ($row = 3; $row <= $highestRow; $row++) { 
                    for ($col = $colStart; $col <= $colEnd; $col++) {
                        $cell = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col) . $row;
                        $value = $sheet->getCell($cell)->getValue();
                        if ($value === null || $value === '') {
                            $sheet->setCellValue($cell, 0);
                            $value = 0;
                        }
                        // Colorear de rojo si es cero
                        if (is_numeric($value) && $value == 0) {
                            $sheet->getStyle($cell)->getFill()->setFillType('solid')->getStartColor()->setARGB('FFFF0000');
                        }
                        // Colorear solo archivo 1 (Productividad/Huella): columnas pares (D, F, H, ...)
                        if (is_numeric($value) && $col >= $colStart && ($col - $colStart) % 2 == 0) {
                            if ($value >= 1 && $value <= 3) {
                                $sheet->getStyle($cell)->getFill()->setFillType('solid')->getStartColor()->setARGB('FFFF0000'); // Rojo
                            } elseif ($value >= 4 && $value <= 6) {
                                $sheet->getStyle($cell)->getFill()->setFillType('solid')->getStartColor()->setARGB('FFFFFF00'); // Amarillo
                            } elseif ($value >= 7) {
                                $sheet->getStyle($cell)->getFill()->setFillType('solid')->getStartColor()->setARGB('FF00FF00'); // Verde
                            }
                        }
                    }
                }

                
                // Obtener índices de las dos últimas columnas
                $totalCol1 = $colEnd - 2; // Total Huella
                $totalCol2 = $colEnd - 1; // Total Marcación
                // Recopilar valores de cada columna de total 
                $valoresTotal1 = [];
                $valoresTotal2 = [];
                for ($row = 3; $row <= $highestRow; $row++) {
                    $cell1 = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($totalCol1) . $row;
                    $cell2 = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($totalCol2) . $row;
                    $v1 = $sheet->getCell($cell1)->getValue();
                    $v2 = $sheet->getCell($cell2)->getValue();
                    if (is_numeric($v1)) $valoresTotal1[] = $v1;
                    if (is_numeric($v2)) $valoresTotal2[] = $v2;
                }
                // Calcular min y max para cada columna
                $min1 = count($valoresTotal1) ? min($valoresTotal1) : 0;
                $max1 = count($valoresTotal1) ? max($valoresTotal1) : 1;
                $min2 = count($valoresTotal2) ? min($valoresTotal2) : 0;
                $max2 = count($valoresTotal2) ? max($valoresTotal2) : 1;
                // Aplicar degradado
                for ($row = 3; $row <= $highestRow; $row++) {
                    // Total Huella
                    $cell1 = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($totalCol1) . $row;
                    $v1 = $sheet->getCell($cell1)->getValue();
                    if (is_numeric($v1)) {
                        $porcentaje = $max1 > 0 ? ($v1 / $max1) : 0;
                        if ($porcentaje >= 0.7) {
                            $color = 'FF00FF00'; // Verde
                        } elseif ($porcentaje >= 0.5) {
                            $color = 'FFFFFF00'; // Amarillo
                        } else {
                            $color = 'FFFF0000'; // Rojo
                        }
                        $sheet->getStyle($cell1)->getFill()->setFillType('solid')->getStartColor()->setARGB($color);
                    }
                    // Total Marcación
                    $cell2 = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($totalCol2) . $row;
                    $v2 = $sheet->getCell($cell2)->getValue();
                    if (is_numeric($v2)) {
                        $porcentaje = $max2 > 0 ? ($v2 / $max2) : 0;
                        if ($porcentaje >= 0.7) {
                            $color = 'FF00FF00'; // Verde
                        } elseif ($porcentaje >= 0.5) {
                            $color = 'FFFFFF00'; // Amarillo
                        } else {
                            $color = 'FFFF0000'; // Rojo
                        }
                        $sheet->getStyle($cell2)->getFill()->setFillType('solid')->getStartColor()->setARGB($color);
                    }
                }

                // Degradado en las columnas de horas del segundo archivo (Marcación/Grabaciones)
                // Calcular para cada columna impar desde F en adelante (Marcación)
                for ($col = $colStart + 1; $col <= $colEnd - 2; $col += 2) { // Excluye las dos últimas (totales)
                    $valoresCol = [];
                    for ($row = 3; $row <= $highestRow; $row++) {
                        $cell = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col) . $row;
                        $v = $sheet->getCell($cell)->getValue();
                        if (is_numeric($v)) $valoresCol[] = $v;
                    }
                    $min = count($valoresCol) ? min($valoresCol) : 0;
                    $max = count($valoresCol) ? max($valoresCol) : 1;
                    for ($row = 3; $row <= $highestRow; $row++) {
                        $cell = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col) . $row;
                        $v = $sheet->getCell($cell)->getValue();
                        if (is_numeric($v)) {
                            if ($v == $min) {
                                $color = 'FFFF0000'; // Rojo
                            } elseif ($v == $max) {
                                $color = 'FF00FF00'; // Verde
                            } else {
                                $color = 'FFFFFF00'; // Amarillo
                            }
                            $sheet->getStyle($cell)->getFill()->setFillType('solid')->getStartColor()->setARGB($color);
                        }
                    }
                }

                // Colorear la columna de Novedad
                $novedadCol = $colEnd;
                for ($row = 3; $row <= $highestRow; $row++) {
                    $cell = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($novedadCol) . $row;
                    $valor = $sheet->getCell($cell)->getValue();
                    if ($valor === 'NOVEDAD') {
                        $sheet->getStyle($cell)->getFill()->setFillType('solid')->getStartColor()->setARGB('FFFF0000'); // Rojo
                    } elseif ($valor === 'SIN NOVEDAD') {
                        $sheet->getStyle($cell)->getFill()->setFillType('solid')->getStartColor()->setARGB('FF00B0F0'); // Azul
                    }
                }

                // Colorear la columna de numeración (N°) con un color pastel diferente por cartera
                $coloresCartera = [
                    'CASTIGO' => 'FFFFF2CC', // Amarillo pastel
                    'DESISTIDOS' => 'FFD9EAD3', // Verde pastel
                    'DESOCUPADOS' => 'FFD9D2E9', // Lila pastel
                    'DESOCUPADOS 2022-2023' => 'FFFCE5CD', // Naranja pastel
                    'SUPERNUMERARIO' => 'FFCCE5FF', // Azul pastel
                    '' => 'FFFFFFFF', // Blanco para vacío
                ];
                $colCartera = 'D'; // Columna Cartera
                $colNum = 'A'; // Columna N°
                for ($row = 3; $row <= $highestRow; $row++) {
                    $cartera = $sheet->getCell($colCartera . $row)->getValue();
                    $color = $coloresCartera[$cartera] ?? 'FFFFFFFF';
                    $sheet->getStyle($colNum . $row)->getFill()->setFillType('solid')->getStartColor()->setARGB($color);
                }

                // Convertir la columna de Asesor (B) a mayúsculas
                $highestRow = $sheet->getHighestRow();
                for ($row = 3; $row <= $highestRow; $row++) {
                    $cell = 'B' . $row; 
                    $valor = $sheet->getCell($cell)->getValue();
                    if ($valor !== null && $valor !== '') {
                        $sheet->setCellValue($cell, mb_strtoupper($valor, 'UTF-8'));
                    }
                }

                // Excepción: primera columna de huella (primer hora)
                // Determinar la columna de la primera huella (después de las columnas fijas y primer marcación)
                $colPrimeraHuella = 6;
                for ($row = 3; $row <= $highestRow; $row++) {
                    $cell = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colPrimeraHuella) . $row;
                    $v = $sheet->getCell($cell)->getValue();
                    if (is_numeric($v)) {
                        if ($v >= 0 && $v <= 2) {
                            $color = 'FFFF0000'; // Rojo
                        } elseif ($v == 3 || $v == 4) {
                            $color = 'FFFFFF00'; // Amarillo
                        } elseif ($v > 4) {
                            $color = 'FF00FF00'; // Verde
                        } else {
                            $color = 'FFFFFFFF'; // Blanco por defecto
                        }
                        $sheet->getStyle($cell)->getFill()->setFillType('solid')->getStartColor()->setARGB($color);
                    }
                }
            }
        ];
    }
}
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
        // Extraer las horas dinámicamente de los headings recibidos
        $horas = [];
        foreach ($this->headings as $h) {
            if (preg_match('/^(\d{1,2}):00 Productividad$/', $h, $m)) {
                $horas[] = $m[1];
            }
        }

        // Cambiar encabezados principales
        $headerRow1 = ['N°','Asesor', 'Asesor Real', 'Cartera'];
        foreach ($horas as $hora) {
            $headerRow1[] = sprintf('%02d:00', $hora);
            $headerRow1[] = '';
        }
        // Para Total, igual que las horas: dos columnas
        $headerRow1[] = 'Total';
        $headerRow1[] = '';
        // Para Novedad, una sola columna
        $headerRow1[] = 'Novedades';

        $headerRow2 = ['N°','Asesor', 'Asesor Real', 'Cartera'];
        foreach ($horas as $hora) {
            $headerRow2[] = 'Huella';
            $headerRow2[] = 'Marcación';
        }
        $headerRow2[] = 'Huella';
        $headerRow2[] = 'Marcación';
        // Para Novedad, celda vacía
        $headerRow2[] = '';

        // Asegurarse de que ambos arrays tengan la misma longitud
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
    ];

    $horas = [];
    foreach ($this->headings as $h) {
        if (preg_match('/^(\d{1,2}):00 Productividad$/', $h)) {
            $horas[] = $h;
        }
    }

    $colIndex = 5; // E = 5
    foreach ($horas as $hora) {
        $widths[Coordinate::stringFromColumnIndex($colIndex++)] = 9; // Huella
        $widths[Coordinate::stringFromColumnIndex($colIndex++)] = 9; // Marcación
    }

    // Total (2 columnas)
    $widths[Coordinate::stringFromColumnIndex($colIndex++)] = 9;
    $widths[Coordinate::stringFromColumnIndex($colIndex++)] = 9;

    // Novedades (última columna) con ancho 15
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

                // Unificar las cuatro primeras columnas en las filas 1 y 2
                $sheet->mergeCells('A1:A2');
                $sheet->mergeCells('B1:B2');
                $sheet->mergeCells('C1:C2');
                $sheet->mergeCells('D1:D2');
                $sheet->getStyle('A1:D2')->getAlignment()->setHorizontal('center');
                $sheet->getStyle('A1:D2')->getAlignment()->setVertical('center');
                $sheet->getStyle('A1:D2')->getFont()->setBold(true);

                // Extraer las horas reales de los headings
                $horas = [];
                foreach ($this->headings as $h) {
                    if (preg_match('/^(\d{1,2}):00 Productividad$/', $h, $m)) {
                        $horas[] = $m[1];
                    }
                }

                $startColumnIndex = 5; // Ahora las horas empiezan en la columna E
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
                $colStart = 4; // Columna D
                $colEnd = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);
                for ($row = 3; $row <= $highestRow; $row++) { // Desde la fila 3 (datos)
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

                // Degradado en las columnas de Total (las dos últimas columnas)
                // Obtener índices de las dos últimas columnas
                $totalCol1 = $colEnd - 1;
                $totalCol2 = $colEnd;
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
                    // Total Productividad
                    $cell1 = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($totalCol1) . $row;
                    $v1 = $sheet->getCell($cell1)->getValue();
                    if (is_numeric($v1)) {
                        $color = 'FFFF0000'; // Rojo por defecto
                        if ($max1 > $min1) {
                            $percent = ($v1 - $min1) / ($max1 - $min1);
                            if ($percent <= 0.33) {
                                // Rojo a Naranja
                                $local = $percent / 0.33;
                                $r = 255;
                                $g = intval(0 + (165 * $local)); // 0 a 165
                                $b = 0;
                            } elseif ($percent <= 0.66) {
                                // Naranja a Amarillo
                                $local = ($percent - 0.33) / 0.33;
                                $r = 255;
                                $g = intval(165 + (90 * $local)); // 165 a 255
                                $b = 0;
                            } else {
                                // Amarillo a Verde
                                $local = ($percent - 0.66) / 0.34;
                                $r = intval(255 - (255 * $local)); // 255 a 0
                                $g = 255;
                                $b = 0;
                            }
                            $color = sprintf('FF%02X%02X%02X', $r, $g, $b);
                        }
                        $sheet->getStyle($cell1)->getFill()->setFillType('solid')->getStartColor()->setARGB($color);
                    }
                    // Total Grabaciones
                    $cell2 = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($totalCol2) . $row;
                    $v2 = $sheet->getCell($cell2)->getValue();
                    if (is_numeric($v2)) {
                        $color = 'FFFF0000';
                        if ($max2 > $min2) {
                            $percent = ($v2 - $min2) / ($max2 - $min2);
                            if ($percent <= 0.33) {
                                $local = $percent / 0.33;
                                $r = 255;
                                $g = intval(0 + (165 * $local));
                                $b = 0;
                            } elseif ($percent <= 0.66) {
                                $local = ($percent - 0.33) / 0.33;
                                $r = 255;
                                $g = intval(165 + (90 * $local));
                                $b = 0;
                            } else {
                                $local = ($percent - 0.66) / 0.34;
                                $r = intval(255 - (255 * $local));
                                $g = 255;
                                $b = 0;
                            }
                            $color = sprintf('FF%02X%02X%02X', $r, $g, $b);
                        }
                        $sheet->getStyle($cell2)->getFill()->setFillType('solid')->getStartColor()->setARGB($color);
                    }
                }

                // Degradado en las columnas de horas del segundo archivo (Marcación/Grabaciones)
                // Calcular para cada columna impar desde D en adelante (Marcación)
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
                            $color = 'FFFF0000';
                            if ($max > $min) {
                                $percent = ($v - $min) / ($max - $min);
                                if ($percent <= 0.33) {
                                    $local = $percent / 0.33;
                                    $r = 255;
                                    $g = intval(0 + (165 * $local));
                                    $b = 0;
                                } elseif ($percent <= 0.66) {
                                    $local = ($percent - 0.33) / 0.33;
                                    $r = 255;
                                    $g = intval(165 + (90 * $local));
                                    $b = 0;
                                } else {
                                    $local = ($percent - 0.66) / 0.34;
                                    $r = intval(255 - (255 * $local));
                                    $g = 255;
                                    $b = 0;
                                }
                                $color = sprintf('FF%02X%02X%02X', $r, $g, $b);
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
                // Definir colores pastel para cada cartera
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

                // Convertir la columna de Asesor (A) a mayúsculas en todas las filas de datos
                for ($row = 3; $row <= $highestRow; $row++) {
                    $cell = 'A' . $row;
                    $valor = $sheet->getCell($cell)->getValue();
                    if ($valor !== null && $valor !== '') {
                        $sheet->setCellValue($cell, mb_strtoupper($valor, 'UTF-8'));
                    }
                }
            }
        ];
    }
}
<?php

   namespace App\Exports;

   use Maatwebsite\Excel\Concerns\FromCollection;
   use Maatwebsite\Excel\Concerns\WithHeadings;
   use Maatwebsite\Excel\Concerns\WithStyles;
   use Maatwebsite\Excel\Concerns\WithColumnWidths;
   use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

   class DatosExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths
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
           return $this->headings;
       }

       public function columnWidths(): array
       {
           $widths = [
               'A' => 40, // Primera columna en blanco
               'B' => 40, // Segunda columna
               'C' => 45, // Tercera columna
               'D' => 15, // Cuarta columna
           ];
           // Si hay más columnas, asignar ancho por defecto
           $colCount = count($this->headings);
           $colLetter = 'A';
           for ($i = 5; $i <= $colCount; $i++) {
               $colLetter = chr(ord('A') + $i - 1);
               // Si es la última columna (NOVEDAD), ancho 14
               if ($i === $colCount) {
                   $widths[$colLetter] = 14;
               } else {
                   $widths[$colLetter] = 10; // Ancho por defecto para otras
               }
           }
           return $widths;
       }

       public function styles(Worksheet $sheet)
       {
           $highestRow = $sheet->getHighestRow();
           $highestColumn = $sheet->getHighestColumn();
           
           // Aplicar formato de negrita a las cabeceras
           $headerRange = 'A1:' . $highestColumn . '1';
           $sheet->getStyle($headerRange)->getFont()->setBold(true);
           // Aplicar fondo azul celeste a las cabeceras
           $sheet->getStyle($headerRange)->getFill()->setFillType('solid')->getStartColor()->setARGB('FFB7E1FA');
           
           // Asume que los datos empiezan en la fila 2 (después de los encabezados)
           // Para informe por horas: A=Nombre Excel, B=Nombre BD, C=Cartera, D+=Horas, última=Total
           // Para informe por cartera: A=Cartera, B=Total Personas, C=Total Gestiones, D=Promedio
           // Las horas están en las columnas D en adelante (solo para informe por horas)
           // La última columna es el total, así que no la coloreamos
           $startCol = 'D'; // Columna 4 (primera columna de horas o datos numéricos)
           $endCol = chr(ord($highestColumn) - 1); // Penúltima columna (antes del total)
           for ($row = 2; $row <= $highestRow; $row++) {
               for ($col = $startCol; $col <= $endCol; $col++) {
                   $cell = $col . $row;
                   $value = $sheet->getCell($cell)->getValue();
                   // Si el valor es vacío o no numérico, poner cero
                   if ($value === null || $value === '' || !is_numeric($value)) {
                       $sheet->setCellValue($cell, 0);
                       $value = 0;
                   }
                   if (is_numeric($value)) {
                       if ($value == 0) {
                           $sheet->getStyle($cell)->getFill()->setFillType('solid')->getStartColor()->setARGB('FF0000'); // Rojo para ceros
                       } elseif ($value < 4) {
                           $sheet->getStyle($cell)->getFill()->setFillType('solid')->getStartColor()->setARGB('FF0000'); // Rojo claro para bajos
                       } elseif ($value >= 4 && $value <= 6) {
                           $sheet->getStyle($cell)->getFill()->setFillType('solid')->getStartColor()->setARGB('FFFFFF00'); // Amarillo
                       } elseif ($value > 6) {
                           $sheet->getStyle($cell)->getFill()->setFillType('solid')->getStartColor()->setARGB('FF00FF00'); // Verde
                       }
                   }
               }
           }
           // Aplicar bordes a toda la tabla
           $tableRange = 'A1:' . $highestColumn . $highestRow;
           $sheet->getStyle($tableRange)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

           // Colorear la columna de NOVEDAD
           $novedadColIndex = count($this->headings); // Última columna
           for ($row = 2; $row <= $highestRow; $row++) {
               $cell = $sheet->getCellByColumnAndRow($novedadColIndex, $row);
               $value = $cell->getValue();
               if ($value === 'NOVEDAD') {
                   $sheet->getStyleByColumnAndRow($novedadColIndex, $row)
                       ->getFill()->setFillType('solid')
                       ->getStartColor()->setARGB('FFFFA500'); // Naranja
               } elseif ($value === 'SIN NOVEDAD') {
                   $sheet->getStyleByColumnAndRow($novedadColIndex, $row)
                       ->getFill()->setFillType('solid')
                       ->getStartColor()->setARGB('FF00FF00'); // Verde
               }
           }
           // Degradado de color en la penúltima columna (antes de 'Novedad')
           $totalColIndex = count($this->headings) - 1; // Penúltima columna
           $totals = [];
           for ($row = 2; $row <= $highestRow; $row++) {
               $cell = $sheet->getCellByColumnAndRow($totalColIndex, $row);
               $value = $cell->getValue();
               if (is_numeric($value) && $value > 0) {
                   $totals[] = $value;
               }
           }
           if (count($totals) > 0) {
               $max = max($totals);
               $min = min($totals);
               $range = $max - $min ?: 1; // Evita división por cero
               for ($row = 2; $row <= $highestRow; $row++) {
                   $cell = $sheet->getCellByColumnAndRow($totalColIndex, $row);
                   $value = $cell->getValue();
                   if (is_numeric($value)) {
                       if ($value == 0) {
                           $color = 'FFFF0000'; // Rojo puro para ceros
                       } else {
                           $percent = ($max - $value) / $range;
                           if ($percent <= 0.33) {
                               // Verde a Amarillo
                               $local = $percent / 0.33;
                               $r = intval(255 * $local);
                               $g = 255;
                               $b = 0;
                           } elseif ($percent <= 0.66) {
                               // Amarillo a Naranja
                               $local = ($percent - 0.33) / 0.33;
                               $r = 255;
                               $g = intval(255 - (90 * $local)); // 255 a 165
                               $b = 0;
                           } else {
                               // Naranja a Rojo
                               $local = ($percent - 0.66) / 0.34;
                               $r = 255;
                               $g = intval(165 * (1 - $local)); // 165 a 0
                               $b = 0;
                           }
                           $color = sprintf('FF%02X%02X%02X', $r, $g, $b);
                       }
                       $sheet->getStyleByColumnAndRow($totalColIndex, $row)
                           ->getFill()->setFillType('solid')
                           ->getStartColor()->setARGB($color);
                   }
               }
           }
           return [];
       }
   }
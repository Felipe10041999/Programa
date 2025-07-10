<?php

   namespace App\Exports;

   use Maatwebsite\Excel\Concerns\FromCollection;
   use Maatwebsite\Excel\Concerns\WithHeadings;
   use Maatwebsite\Excel\Concerns\WithStyles;
   use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

   class DatosExport implements FromCollection, WithHeadings, WithStyles
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

       public function styles(Worksheet $sheet)
       {
           $highestRow = $sheet->getHighestRow();
           $highestColumn = $sheet->getHighestColumn();
           // Asume que los datos empiezan en la fila 2 (después de los encabezados)
           // Y que las horas están en las columnas B a L (ajusta si tu rango cambia)
           // La última columna es el total, así que no la coloreamos
           $startCol = 'B'; // Columna 2 (8:00 am)
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
                           $sheet->getStyle($cell)->getFill()->setFillType('solid')->getStartColor()->setARGB('FF92D050'); // Verde
                       }
                   }
               }
           }
           // Aplicar bordes a toda la tabla
           $tableRange = 'A1:' . $highestColumn . $highestRow;
           $sheet->getStyle($tableRange)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
           return [];
       }
   }
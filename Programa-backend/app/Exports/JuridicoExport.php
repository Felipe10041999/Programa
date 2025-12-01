<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class JuridicoExport implements FromArray, WithHeadings, WithStyles
{
    protected $rows;

    public function __construct(array $rows)
    {
        $this->rows = $rows;
    }

    public function array(): array
    {
        // Excluir la primera fila de encabezados si ya la incluimos en headings
        // Aquí devolvemos las filas a partir de la segunda fila para que WithHeadings ponga la cabecera
        $data = $this->rows;
        // Si la primera fila es la cabecera, quitarla y usar WithHeadings
        if (count($data) > 0) {
            // dejamos todo menos la primera fila
            array_shift($data);
        }
        return $data;
    }

    public function headings(): array
    {
        if (count($this->rows) > 0) {
            return $this->rows[0];
        }
        return ['Extensión', 'Segundos', 'HH:MM:SS'];
    }

    public function styles(Worksheet $sheet)
    {
        // Obtener la cantidad de columnas dinámicamente (basado en la cabecera)
        $headerCount = count($this->rows[0] ?? []);
        
        // Calcular el rango de columnas (A, B, C, ... hasta la última)
        $lastColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($headerCount);
        $headerRange = "A1:{$lastColumn}1";
        
        // Estilo para la cabecera: azul suave, texto en negrita y centrado
        $sheet->getStyle($headerRange)->getFont()->setBold(true);
        $sheet->getStyle($headerRange)->getAlignment()->setHorizontal('center');
        // Fill azul suave (pastel) para todo el encabezado
        $sheet->getStyle($headerRange)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFCCE5FF');

        // Ajustar ancho de columnas: Usuario BestVoIper (A) más ancho, Extensión (B) un poco más grande
        $sheet->getColumnDimension('A')->setWidth(36);
        $sheet->getColumnDimension('B')->setWidth(14);
        
        // Ajustar ancho de las demás columnas (horas y total)
        for ($i = 3; $i <= $headerCount; $i++) {
            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i);
            $sheet->getColumnDimension($colLetter)->setWidth(14);
        }

        // Aumentar ligeramente la fuente en las columnas A y B para mejor legibilidad
        $sheet->getStyle('A')->getFont()->setSize(11);
        $sheet->getStyle('B')->getFont()->setSize(11);

        return [];
    }
}

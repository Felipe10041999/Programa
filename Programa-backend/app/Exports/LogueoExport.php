<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;

class LogueoExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths, WithTitle, ShouldAutoSize
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        $dataConMinutosSobrantes = array_map(function($fila) {
            $hora = $fila['Logueo'];
            $horaActual = strtotime($hora);
            $horaLimite = strtotime('07:30:00');
            if ($horaActual > $horaLimite) {
                $diferencia = $horaActual - $horaLimite;
                $minutosSobrantes = floor($diferencia / 60);
            } else {
                $minutosSobrantes = 0;
                
            }
            return [
                'Asesor' => $fila['Asesor'],
                'Extensión' => $fila['Extensión'],
                'Cartera' => $fila['Cartera'],
                'Logueo' => $fila['Logueo'],
                'Tiempo a reponer' => $minutosSobrantes. ' min',
            ];
        }, $this->data);
        return $dataConMinutosSobrantes;
    }

    public function headings(): array
    {
        return [
            'Asesor',
            'Extensión',
            'Cartera',
            'Logueo',
            'Tiempo a reponer',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Estilo para el encabezado
        $sheet->getStyle('A1:E1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 12
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '2C3E50']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '34495E']
                ]
            ]
        ]);

        // Estilo para los datos
        $lastRow = count($this->data) + 1;
        if ($lastRow > 1) {
            $sheet->getStyle('A2:E' . $lastRow)->applyFromArray([
                'font' => [
                    'size' => 11,
                    'color' => ['rgb' => '2C3E50']
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'F8F9FA']
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'E9ECEF']
                    ]
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER
                ]
            ]);
        }

        // Estilo para la columna de hora (formato especial)
        $sheet->getStyle('D2:D' . $lastRow)->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => '27AE60']
            ]
        ]);

        // Estilo para los minutos sobrantes (misma color que la hora)
        $sheet->getStyle('E2:E' . $lastRow)->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => '27AE60']
            ]
        ]);

        // Alinear a la izquierda los nombres de Asesor (columna A)
        $sheet->getStyle('A2:A' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

        // Altura de filas
        $sheet->getRowDimension('1')->setRowHeight(25);
        for ($row = 2; $row <= $lastRow; $row++) {
            $cellValue = $sheet->getCell('A' . $row)->getValue();
            if (strpos((string)$cellValue, 'CARTERA:') === false && !empty($cellValue)) {
                $sheet->getRowDimension($row)->setRowHeight(20);
            }
        }

        // Centrar todo el contenido
        $sheet->getStyle('A1:E' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:E' . $lastRow)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        return $sheet;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 35, // Asesor
            'B' => 15, // Extensión
            'C' => 25, // Cartera
            'D' => 25, // Logueo
            'E' => 20, // Minutos Sobrantes
        ];
    }

    public function title(): string
    {
        return 'Reporte de Logueos';
    }
}

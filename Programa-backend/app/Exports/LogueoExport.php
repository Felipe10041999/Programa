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
            $minutosSobrantes = 0;

            if (!empty($hora) && strtotime($hora)) {
                // Redondear al minuto más cercano
                $timestamp = strtotime($hora);
                $segundos = date('s', $timestamp);

                if ((int)$segundos >= 30) {
                    $timestamp += (60 - (int)$segundos);
                } else {
                    $timestamp -= (int)$segundos;
                }

                $horaRedondeada = date('H:i', $timestamp);

                // Comparar con la hora límite
                $horaLimite = strtotime('07:30:00');
                if ($timestamp > $horaLimite) {
                    $diferencia = $timestamp - $horaLimite;
                    $minutosSobrantes = floor($diferencia / 60);
                }

                $horaFinal = $horaRedondeada;
            } else {
                $horaFinal = 'NOVEDAD';
                $minutosSobrantes = 'N/A';
            }

            return [
                'Asesor' => $fila['Asesor'],
                'Extensión' => $fila['Extensión'],
                'Cartera' => $fila['Cartera'],
                'Logueo' => $horaFinal,
                'Tiempo a reponer' => is_numeric($minutosSobrantes) ? $minutosSobrantes . ' min' : $minutosSobrantes,
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

        // Colores condicionales en columna Logueo
        for ($row = 2; $row <= $lastRow; $row++) {
            $horaLogueo = $sheet->getCell('D' . $row)->getFormattedValue();

            if (!empty($horaLogueo) && $horaLogueo !== 'NOVEDAD' && strtotime($horaLogueo)) {
                $horaLogueoTimestamp = strtotime($horaLogueo);
                $horaVerde = strtotime('07:30:00');
                $horaAmarillo = strtotime('07:35:59');
                $colorFondo = null;

                if (!empty($horaLogueo)) {
                    if ($horaLogueo === 'NOVEDAD') {
                        // Estilo azul para "NOVEDAD"
                        $sheet->getStyle('D' . $row)->applyFromArray([
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'D6EAF8'] // azul claro
                            ],
                            'font' => [
                                'bold' => true,
                                'color' => ['rgb' => '154360']
                            ],
                            'alignment' => [
                                'horizontal' => Alignment::HORIZONTAL_CENTER,
                                'vertical' => Alignment::VERTICAL_CENTER
                            ],
                            'borders' => [
                                'allBorders' => [
                                    'borderStyle' => Border::BORDER_THIN,
                                    'color' => ['rgb' => 'E9ECEF']
                                ]
                            ]
                        ]);
                    } elseif (strtotime($horaLogueo)) {
                        $horaLogueoTimestamp = strtotime($horaLogueo);
                        $horaVerde = strtotime('07:30:29');
                        $horaAmarillo = strtotime('07:35:59');
                        $colorFondo = null;
                
                        if ($horaLogueoTimestamp < $horaVerde) {
                            $colorFondo = 'D4EFDF'; // verde claro
                        } elseif ($horaLogueoTimestamp <= $horaAmarillo) {
                            $colorFondo = 'FCF3CF'; // amarillo claro
                        } else {
                            $colorFondo = 'F5B7B1'; // rojo claro
                        }
                
                        $sheet->getStyle('D' . $row)->applyFromArray([
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => $colorFondo]
                            ],
                            'font' => [
                                'size' => 11,
                                'color' => ['rgb' => '2C3E50']
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
                }
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
            'A' => 35,
            'B' => 15,
            'C' => 25,
            'D' => 25,
            'E' => 20,
        ];
    }

    public function title(): string
    {
        return 'Reporte de Logueos';
    }
}
    
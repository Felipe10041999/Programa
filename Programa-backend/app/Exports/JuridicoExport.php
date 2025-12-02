<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Conditional;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class JuridicoExport implements FromArray, WithHeadings, WithStyles, WithEvents
{
    protected $rows;

    public function __construct(array $rows)
    {
        $this->rows = $rows;
    }

    public function array(): array
    {
        $data = $this->rows;
        if (count($data) > 0) {
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

    // Aplica estilos base y ajusta anchos de columna.

    public function styles(Worksheet $sheet)
    {
        // 1. Estilo de Cabecera
        $this->applyHeaderStyles($sheet);
        
        // 2. Ajuste de Ancho de Columnas
        $this->adjustColumnWidths($sheet);

        // 3. Centrar toda la hoja excepto la columna A (Encabezado también se centra)
        $this->centerAllButFirstColumn($sheet);

        return [];
    }
    
    // Aplica estilo a la cabecera (fila 1).

    protected function applyHeaderStyles(Worksheet $sheet)
    {
        $headerCount = count($this->rows[0] ?? []);
        $lastColumn = Coordinate::stringFromColumnIndex($headerCount);
        $headerRange = "A1:{$lastColumn}1";
        
        // Estilo de texto: negrita y centrado
        $sheet->getStyle($headerRange)->getFont()->setBold(true);
        $sheet->getStyle($headerRange)->getAlignment()->setHorizontal('center');
        
        // Color de fondo: azul suave (pastel)
        $sheet->getStyle($headerRange)->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFCCE5FF');
    }
    
    
    // Centra todo el contenido de la hoja de cálculo, excepto la columna A.

    protected function centerAllButFirstColumn(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        
        // Centrar de la columna B hasta la última, desde la fila 2
        $dataRange = "B2:{$highestColumn}{$highestRow}";
        $sheet->getStyle($dataRange)->getAlignment()->setHorizontal('center');
        
        // Nota: La cabecera (fila 1) ya está centrada en applyHeaderStyles.
    }
    
    // Ajusta el ancho y la fuente de las columnas.

    protected function adjustColumnWidths(Worksheet $sheet)
    {
        $headerCount = count($this->rows[0] ?? []);

        // Anchos específicos
        $sheet->getColumnDimension('A')->setWidth(36);
        $sheet->getColumnDimension('B')->setWidth(10);
        
        // Ancho para las demás columnas
        for ($i = 3; $i <= $headerCount; $i++) {
            $colLetter = Coordinate::stringFromColumnIndex($i);
            $sheet->getColumnDimension($colLetter)->setWidth(14);
        }

        // Fuente ligeramente más grande para A y B
        $sheet->getStyle('A')->getFont()->setSize(11);
        $sheet->getStyle('B')->getFont()->setSize(11);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                // 1. Obtener el rango de columnas horarias
                list($startCol, $endCol, $highestRow) = $this->getHourlyRangeAndRows($sheet);

                if (empty($startCol) || empty($endCol)) {
                    return;
                }

                // 2. Aplicar formato directo (ALMUERZO en magenta)
                // Esto DEBE ejecutarse antes del formato condicional
                $this->applyDirectFormattingForLunch($sheet, $startCol, $endCol, $highestRow);

                // 3. Aplicar formato condicional (solo a celdas numéricas)
                $this->applyConditionalFormatting($sheet, $startCol, $endCol, $highestRow);
            },
        ];
    }
    
    // Determina el rango de columnas para los datos por hora.
    
    protected function getHourlyRangeAndRows(Worksheet $sheet): array
    {
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        $headerRow = $sheet->rangeToArray('A1:' . $highestColumn . '1')[0] ?? [];
        $totalColumns = count($headerRow);

        $logIndex = array_search('Logueo', $headerRow);
        $totalGestionIndex = array_search('Total Gestion', $headerRow);

        // Índice de inicio (C o más allá)
        $startColIndex = ($logIndex !== false) ? ($logIndex + 2) : 3; 
        
        // Índice de fin (antes de Total Gestion)
        if ($totalGestionIndex !== false) {
            $endColIndex = $totalGestionIndex; 
        } else {
            $endColIndex = max(3, $totalColumns - 2);
        }

        if ($endColIndex <= $startColIndex) {
            return [null, null, $highestRow];
        }

        $startCol = Coordinate::stringFromColumnIndex($startColIndex);
        $endCol = Coordinate::stringFromColumnIndex($endColIndex);

        return [$startCol, $endCol, $highestRow];
    }

    
    //Aplica el formato directo (color magenta) SÓLO a las celdas 'ALMUERZO'.
    
    protected function applyDirectFormattingForLunch(Worksheet $sheet, string $startCol, string $endCol, int $highestRow): void
    {
        $startColIndexNum = Coordinate::columnIndexFromString($startCol);
        $endColIndexNum = Coordinate::columnIndexFromString($endCol);

        for ($row = 2; $row <= $highestRow; $row++) {
            for ($col = $startColIndexNum; $col <= $endColIndexNum; $col++) {
                $cellRef = Coordinate::stringFromColumnIndex($col) . $row;
                $cellVal = $sheet->getCell($cellRef)->getCalculatedValue(); // Usar CalculatedValue

                // Si es 'ALMUERZO', aplicar color magenta (esto no será anulado por las reglas numéricas)
                if (is_string($cellVal) && strtoupper(trim($cellVal)) === 'ALMUERZO') {
                    $sheet->getStyle($cellRef)->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()->setARGB('FFFF00FF'); // Magenta fuerte
                    $sheet->getStyle($cellRef)->getFont()->setBold(true);
                }
            }
        }
    }

    // Aplica el formato condicional al rango de celdas horarias.
    protected function applyConditionalFormatting(Worksheet $sheet, string $startCol, string $endCol, int $highestRow): void
    {
        // Rango de aplicación del formato condicional (solo filas de datos)
        $condRange = sprintf('%s2:%s%d', $startCol, $endCol, $highestRow);

        // Las reglas condicionales se definen para valores numéricos, no afectarán a 'ALMUERZO' (texto).

        // Condicional 1: <= 10 -> rojo claro
        $cond1 = new Conditional();
        $cond1->setConditionType(Conditional::CONDITION_CELLIS)
            ->setOperatorType(Conditional::OPERATOR_LESSTHANOREQUAL)
            ->addCondition('10')
            ->getStyle()->getFill()->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFFF8A8A');

        // Condicional 2: entre 11 y 20 -> amarillo claro
        $cond2 = new Conditional();
        $cond2->setConditionType(Conditional::CONDITION_CELLIS)
            ->setOperatorType(Conditional::OPERATOR_BETWEEN)
            ->addCondition('11')
            ->addCondition('20')
            ->getStyle()->getFill()->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFFFD966');

        // Condicional 3: >= 21 -> verde claro
        $cond3 = new Conditional();
        $cond3->setConditionType(Conditional::CONDITION_CELLIS)
            ->setOperatorType(Conditional::OPERATOR_GREATERTHANOREQUAL)
            ->addCondition('21')
            ->getStyle()->getFill()->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FF92D050');

        $sheet->getStyle($condRange)->setConditionalStyles([$cond1, $cond2, $cond3]);
    }
}
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


    public function styles(Worksheet $sheet)
    {
        $this->applyHeaderStyles($sheet);
        
        $this->adjustColumnWidths($sheet);

        $this->centerAllButFirstColumn($sheet);

        return [];
    }
    

    protected function applyHeaderStyles(Worksheet $sheet)
    {
        $headerCount = count($this->rows[0] ?? []);
        $lastColumn = Coordinate::stringFromColumnIndex($headerCount);
        $headerRange = "A1:{$lastColumn}1";
        
        $sheet->getStyle($headerRange)->getFont()->setBold(true);
        $sheet->getStyle($headerRange)->getAlignment()->setHorizontal('center');
        
        $sheet->getStyle($headerRange)->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFCCE5FF');
    }
    
    protected function centerAllButFirstColumn(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        
        $dataRange = "B2:{$highestColumn}{$highestRow}";
        $sheet->getStyle($dataRange)->getAlignment()->setHorizontal('center');
        
    }
    

    protected function adjustColumnWidths(Worksheet $sheet)
    {
        $headerCount = count($this->rows[0] ?? []);

        $sheet->getColumnDimension('A')->setWidth(36);
        $sheet->getColumnDimension('B')->setWidth(10);
        
        for ($i = 3; $i <= $headerCount; $i++) {
            $colLetter = Coordinate::stringFromColumnIndex($i);
            $sheet->getColumnDimension($colLetter)->setWidth(14);
        }

        $sheet->getStyle('A')->getFont()->setSize(11);
        $sheet->getStyle('B')->getFont()->setSize(11);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                list($startCol, $endCol, $highestRow) = $this->getHourlyRangeAndRows($sheet);

                if (empty($startCol) || empty($endCol)) {
                    return;
                }

                $this->applyDirectFormattingForLunch($sheet, $startCol, $endCol, $highestRow);

                $this->applyConditionalFormatting($sheet, $startCol, $endCol, $highestRow);
            },
        ];
    }
    
    
    protected function getHourlyRangeAndRows(Worksheet $sheet): array
    {
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        $headerRow = $sheet->rangeToArray('A1:' . $highestColumn . '1')[0] ?? [];
        $totalColumns = count($headerRow);

        $logIndex = array_search('Logueo', $headerRow);
        $totalGestionIndex = array_search('Total Gestion', $headerRow);

        $startColIndex = ($logIndex !== false) ? ($logIndex + 2) : 3; 
        
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

    
    
    protected function applyDirectFormattingForLunch(Worksheet $sheet, string $startCol, string $endCol, int $highestRow): void
    {
        $startColIndexNum = Coordinate::columnIndexFromString($startCol);
        $endColIndexNum = Coordinate::columnIndexFromString($endCol);

        for ($row = 2; $row <= $highestRow; $row++) {
            for ($col = $startColIndexNum; $col <= $endColIndexNum; $col++) {
                $cellRef = Coordinate::stringFromColumnIndex($col) . $row;
                $cellVal = $sheet->getCell($cellRef)->getCalculatedValue(); 

                if (is_string($cellVal) && strtoupper(trim($cellVal)) === 'ALMUERZO') {
                    $sheet->getStyle($cellRef)->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()->setARGB('FFFF00FF'); 
                    $sheet->getStyle($cellRef)->getFont()->setBold(true);
                }
            }
        }
    }

    protected function applyConditionalFormatting(Worksheet $sheet, string $startCol, string $endCol, int $highestRow): void
    {
        $condRange = sprintf('%s2:%s%d', $startCol, $endCol, $highestRow);


        $cond1 = new Conditional();
        $cond1->setConditionType(Conditional::CONDITION_CELLIS)
            ->setOperatorType(Conditional::OPERATOR_LESSTHANOREQUAL)
            ->addCondition('10')
            ->getStyle()->getFill()->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFFF8A8A');

        $cond2 = new Conditional();
        $cond2->setConditionType(Conditional::CONDITION_CELLIS)
            ->setOperatorType(Conditional::OPERATOR_BETWEEN)
            ->addCondition('11')
            ->addCondition('20')
            ->getStyle()->getFill()->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFFFD966');

        $cond3 = new Conditional();
        $cond3->setConditionType(Conditional::CONDITION_CELLIS)
            ->setOperatorType(Conditional::OPERATOR_GREATERTHANOREQUAL)
            ->addCondition('21')
            ->getStyle()->getFill()->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FF92D050');

        $sheet->getStyle($condRange)->setConditionalStyles([$cond1, $cond2, $cond3]);
    }
}
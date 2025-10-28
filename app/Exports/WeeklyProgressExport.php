<?php

namespace App\Exports;

use App\Models\WeeklyProgress;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Carbon\Carbon;

class WeeklyProgressExport implements FromCollection, WithStyles, WithColumnWidths
{
    protected $weekFrom;
    protected $weekTo;
    protected $year;

    public function __construct($weekFrom, $weekTo, $year)
    {
        $this->weekFrom = $weekFrom;
        $this->weekTo = $weekTo;
        $this->year = $year;
    }

    public function collection()
    {
        $data = collect();
        
        // Loop through each week in the range
        for ($week = $this->weekFrom; $week <= $this->weekTo; $week++) {
            // Calculate week date range
            $dateRange = $this->getWeekDateRange($this->year, $week);
            
            // Add week header row with merged cells info
            $data->push([
                'week_header' => true,
                'week' => $week,
                'year' => $this->year,
                'date_range' => $dateRange,
                'name' => "Week {$week} ({$dateRange})",
                'last_week_status' => '',
                'p1' => '',
                'p2' => '',
                'p3' => ''
            ]);
            
            // Add column headers
            $data->push([
                'week_header' => false,
                'column_header' => true,
                'name' => 'Name',
                'last_week_status' => 'Last Week Status',
                'p1' => 'P1',
                'p2' => 'P2',
                'p3' => 'P3'
            ]);
            
            // Get weekly progress for this week
            $weeklyProgresses = WeeklyProgress::with('user')
                ->where('year', $this->year)
                ->where('week_number', $week)
                ->orderBy('user_id')
                ->get();
            
            if ($weeklyProgresses->isEmpty()) {
                // Add "No data" row if no progress for this week
                $data->push([
                    'week_header' => false,
                    'column_header' => false,
                    'name' => 'No data available for this week',
                    'last_week_status' => '-',
                    'p1' => '-',
                    'p2' => '-',
                    'p3' => '-'
                ]);
            } else {
                // Add data rows
                foreach ($weeklyProgresses as $progress) {
                    $data->push([
                        'week_header' => false,
                        'column_header' => false,
                        'name' => $progress->user->name ?? 'Unknown',
                        'last_week_status' => $progress->last_week_status ?? '-',
                        'p1' => $progress->p1 ?? '-',
                        'p2' => $progress->p2 ?? '-',
                        'p3' => $progress->p3 ?? '-'
                    ]);
                }
            }
            
            // Add empty row as separator between weeks (except for last week)
            if ($week < $this->weekTo) {
                $data->push([
                    'week_header' => false,
                    'separator' => true,
                    'name' => '',
                    'last_week_status' => '',
                    'p1' => '',
                    'p2' => '',
                    'p3' => ''
                ]);
            }
        }
        
        return $data;
    }

    private function getWeekDateRange($year, $weekNumber)
    {
        $dto = new \DateTime();
        $dto->setISODate($year, $weekNumber);
        $monday = $dto->format('d M');
        
        $dto->modify('+4 days'); // Go to Friday
        $friday = $dto->format('d M Y');
        
        return "{$monday} - {$friday}";
    }

    public function columnWidths(): array
    {
        return [
            'A' => 25,  // Name
            'B' => 35,  // Last Week Status
            'C' => 35,  // P1
            'D' => 35,  // P2
            'E' => 35,  // P3
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();
        $currentRow = 1;
        
        // Loop through all rows to apply conditional styling
        for ($row = 1; $row <= $highestRow; $row++) {
            $cellValue = $sheet->getCell("A{$row}")->getValue();
            
            // Check if it's a week header (starts with "Week ")
            if (is_string($cellValue) && strpos($cellValue, 'Week ') === 0) {
                // Merge cells for week header
                $sheet->mergeCells("A{$row}:E{$row}");
                
                // Style week header
                $sheet->getStyle("A{$row}:E{$row}")->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 12,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '2196F3'], // Blue
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);
                $sheet->getRowDimension($row)->setRowHeight(25);
            }
            // Check if it's a column header row
            elseif ($cellValue === 'Name') {
                // Style column headers
                $sheet->getStyle("A{$row}:E{$row}")->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '4CAF50'], // Green
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);
                $sheet->getRowDimension($row)->setRowHeight(20);
            }
            // Regular data rows
            elseif (!empty($cellValue) && $cellValue !== '') {
                // Style data rows
                $sheet->getStyle("A{$row}:E{$row}")->applyFromArray([
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_TOP,
                        'wrapText' => true,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'CCCCCC'],
                        ],
                    ],
                ]);
                
                // Center align Name column
                $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            }
        }
        
        return [];
    }
}

<?php

namespace App\Exports;

use App\Models\WeeklyProgress;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

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
        
        // Get all users
        $allUsers = User::orderBy('name')->get();
        
        // Loop through each week in the range
        for ($week = $this->weekFrom; $week <= $this->weekTo; $week++) {
            // Calculate week date range
            $dateRange = $this->getWeekDateRange($this->year, $week);
            
            // Add week header row (will be merged in styles)
            $data->push([
                "Week {$week} ({$dateRange})",
                '',
                '',
                '',
                ''
            ]);
            
            // Add column headers
            $data->push([
                'Name',
                'Last Week Status',
                'P1',
                'P2',
                'P3'
            ]);
            
            // Get weekly progress for this week, indexed by user_id
            $weeklyProgressByUser = WeeklyProgress::where('year', $this->year)
                ->where('week_number', $week)
                ->get()
                ->keyBy('user_id');
            
            // Add data rows for ALL users
            foreach ($allUsers as $user) {
                $progress = $weeklyProgressByUser->get($user->id);
                
                $data->push([
                    $user->name,
                    $this->formatTextWithLineBreaks($progress ? ($progress->last_week_status ?? '-') : '-'),
                    $this->formatTextWithLineBreaks($progress ? ($progress->p1 ?? '-') : '-'),
                    $this->formatTextWithLineBreaks($progress ? ($progress->p2 ?? '-') : '-'),
                    $this->formatTextWithLineBreaks($progress ? ($progress->p3 ?? '-') : '-')
                ]);
            }
            
            // Add empty row as separator between weeks (except for last week)
            if ($week < $this->weekTo) {
                $data->push(['', '', '', '', '']);
            }
        }
        
        return $data;
    }

    private function formatTextWithLineBreaks($text)
    {
        if ($text === '-' || empty($text)) {
            return $text;
        }
        
        // Convert common newline characters to Excel line break (LF only)
        // Handle \r\n (Windows), \r (Mac), and \n (Unix)
        $text = str_replace(["\r\n", "\r"], "\n", $text);
        
        return $text;
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
            // Regular data rows (not empty and not headers)
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
                
                // Left align Name column
                $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                
                // Calculate row height based on content with line breaks
                $maxLines = 1;
                for ($col = 'A'; $col <= 'E'; $col++) {
                    $cellContent = $sheet->getCell("{$col}{$row}")->getValue();
                    if (!empty($cellContent) && $cellContent !== '-') {
                        $lineCount = substr_count($cellContent, "\n") + 1;
                        $maxLines = max($maxLines, $lineCount);
                    }
                }
                
                // Set row height: 15 pixels per line + 5 pixels padding
                if ($maxLines > 1) {
                    $sheet->getRowDimension($row)->setRowHeight($maxLines * 15 + 5);
                }
            }
        }
        
        return [];
    }
}

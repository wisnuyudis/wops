<?php

namespace App\Exports;

use App\Models\DailyActivity;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class DailyActivityExport implements FromQuery, WithHeadings, WithMapping, WithColumnWidths, WithStyles
{
    protected $userId;
    protected $sorId;
    protected $dateFrom;
    protected $dateTo;
    protected $rowNumber = 0;

    public function __construct($userId, $sorId, $dateFrom, $dateTo)
    {
        $this->userId = $userId;
        $this->sorId = $sorId;
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
    }

    public function query()
    {
        $query = DailyActivity::query()
            ->with(['user', 'sor', 'jobType', 'jobItem'])
            ->where('user_id', $this->userId)
            ->whereBetween('date', [$this->dateFrom, $this->dateTo])
            ->orderBy('date', 'asc');

        // Optional SOR filter
        if ($this->sorId) {
            $query->where('sor_id', $this->sorId);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'No',
            'Date',
            'SOR',
            'Action',
            'Cust Name',
            'PIC',
            'Product',
            'Job Type',
            'Job Items',
            'Objectives',
            'Result Of Issue'
        ];
    }

    public function map($activity): array
    {
        $this->rowNumber++;
        
        return [
            $this->rowNumber,
            \Carbon\Carbon::parse($activity->date)->format('d/m/Y'),
            $activity->sor ? $activity->sor->sor_code : '-',
            $activity->action ?? '-',
            $activity->cust_name ?? '-',
            $activity->pic ?? '-',
            $activity->product ?? '-',
            $activity->jobType ? $activity->jobType->name : '-',
            $activity->jobItem ? $activity->jobItem->name : '-',
            $activity->objective ?? '-',
            $activity->result_of_issue ?? '-'
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,   // NO
            'B' => 12,  // Date
            'C' => 15,  // SOR
            'D' => 15,  // Action
            'E' => 25,  // Cust Name
            'F' => 15,  // PIC
            'G' => 25,  // Product
            'H' => 15,  // Job Type
            'I' => 15,  // Job Items
            'J' => 35,  // Objectives
            'K' => 35,  // Result Of Issue
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style for header row
        $sheet->getStyle('A1:K1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 11,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4CAF50'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Set row height for header
        $sheet->getRowDimension(1)->setRowHeight(25);

        // Set alignment for all data rows
        $highestRow = $sheet->getHighestRow();
        
        // Center align NO column
        $sheet->getStyle('A2:A' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        // Center align Date column
        $sheet->getStyle('B2:B' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        // Wrap text for long content columns
        $sheet->getStyle('J2:K' . $highestRow)->getAlignment()->setWrapText(true);
        
        // Set vertical alignment to top for all cells
        $sheet->getStyle('A2:K' . $highestRow)->getAlignment()->setVertical(Alignment::VERTICAL_TOP);

        return [];
    }
}

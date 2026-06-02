<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MonitoringExport implements FromArray, WithHeadings, WithTitle, ShouldAutoSize, WithStyles
{
    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'No',
            'ID SubSLS',
            'Kecamatan',
            'Desa',
            'SLS',
            'PCL',
            'PML',
            'Target Usaha',
            'Realisasi Usaha',
            'Realisasi Ruta',
            'Progress (%)',
            'Status',
        ];
    }

    public function title(): string
    {
        return 'Monitoring Sensus Ekonomi 2026';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '0c82eb'] // BPS Blue
                ]
            ],
        ];
    }
}

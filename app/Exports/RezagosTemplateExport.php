<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Events\AfterSheet;

class RezagosTemplateExport implements FromArray, WithHeadings, WithStyles, WithEvents, ShouldAutoSize
{
    public function headings(): array
    {
        return [
            'codigo',
            'destinatario',
            'telefono',
            'peso',
            'aduana',
            'zona',
            'tipo',
            'ciudad',
        ];
    }

    public function array(): array
    {
        return [
            ['EN000001LP', 'Juan Perez', '77000000', 1.250, 'Central', 'Norte', 'Documento', 'La Paz'],
            ['', '', '', '', '', '', '', ''],
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['argb' => 'FFFFFFFF'],
                    'size' => 11,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF1F4E78'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $sheet->freezePane('A2');
                $sheet->setAutoFilter('A1:H1');
                $sheet->getRowDimension(1)->setRowHeight(24);

                $sheet->getStyle('A1:H3')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                $sheet->getStyle('A2:H3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            },
        ];
    }
}

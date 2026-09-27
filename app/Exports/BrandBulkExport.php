<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BrandBulkExport implements WithMultipleSheets
{
    use Exportable;

    public function sheets(): array
    {
        return [
            new BrandTemplateSheet(),
        ];
    }
}

trait AutoSizeColumns
{
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                foreach ($sheet->getColumnIterator() as $column) {
                    $sheet->getColumnDimension($column->getColumnIndex())
                          ->setAutoSize(true);
                }
            },
        ];
    }
}

class BrandTemplateSheet implements FromCollection, WithTitle, WithHeadings, WithStyles, WithEvents
{
    use AutoSizeColumns;

    public function title(): string
    {
        return 'Brand Template';
    }

    public function headings(): array
    {
        return [
            'name', 'logo', 'meta_title', 'meta_description', 'meta_keywords',
        ];
    }

    public function collection()
    {
        return collect([
            [
                'Demo Brand 1', '', 'Demo Meta Title', 'Demo Meta Description', 'Demo Meta Keyword, Demo Meta keyword2',
            ],
        ]);
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font'  => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill'  => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF4472C4']],
            ],
        ];
    }
}
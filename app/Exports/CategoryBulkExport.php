<?php

namespace App\Exports;

use App\Models\Attribute;
use App\Models\Category;
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

class CategoryBulkExport implements WithMultipleSheets
{
    use Exportable;

    public function sheets(): array
    {
        return [
            new CategoryTemplateSheet(),
            new CategorySheet(),
            new AttributeSheet(),
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

class CategoryTemplateSheet implements FromCollection, WithTitle, WithHeadings, WithStyles, WithEvents
{
    use AutoSizeColumns;

    public function title(): string
    {
        return 'Category Template';
    }

    public function headings(): array
    {
        return [
            'name','digital', 'parent_category_id', 'ordering_number', 'banner', 'icon','cover_image', 'meta_title', 'meta_description', 'meta_keywords','filtering_attribute_id',
        ];
    }

    public function collection()
    {
        return collect([
            [
                'Demo Category 1', 0, 1, 2, '', '', '', 'Demo Meta Title', 'Demo Meta Description', 'Demo Meta keyword1, Demo Meta keyword2', '1,2,3',
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

class CategorySheet implements FromCollection, WithTitle, WithHeadings, WithStyles, WithEvents
{
    use AutoSizeColumns;

    public function title(): string { return 'Category'; }
    public function headings(): array { return ['ID', 'Name']; }

    public function collection()
    {
        return Category::select('id', 'name')->orderBy('id')->get()
            ->map(fn($c) => [$c->id, $c->name]);
    }

    public function styles(Worksheet $sheet): array
    {
        return [1 => ['font' => ['bold' => true], 'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFFFC000']]]];
    }
}

class AttributeSheet implements FromCollection, WithTitle, WithHeadings, WithStyles, WithEvents
{
    use AutoSizeColumns;

    public function title(): string { return 'Attribute'; }
    public function headings(): array { return ['ID', 'Name']; }

    public function collection()
    {
        return Attribute::select('id', 'name')->orderBy('id')->get()
            ->map(fn($c) => [$c->id, $c->name, $c->code]);
    }

    public function styles(Worksheet $sheet): array
    {
        return [1 => ['font' => ['bold' => true], 'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFFFC000']]]];
    }
}
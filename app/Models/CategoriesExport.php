<?php

namespace App\Models;

use App\Models\Category;
use App\Traits\PreventDemoModeChanges;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;

class CategoriesExport implements WithMultipleSheets
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

class CategoryTemplateSheet implements FromCollection, WithMapping, WithHeadings, WithStyles, WithTitle, WithEvents
{
    use PreventDemoModeChanges;

    use AutoSizeColumns;

    public function title(): string
    {
        return 'All Category';
    }

    public function collection()
    {
        return Category::get();
    }

    public function headings(): array
    {
        return [
            'name',
            'digital',
            'parent_category_id',
            'ordering_number',
            'banner',
            'icon',
            'cover_image',
            'meta_title',
            'meta_description',
            'meta_keywords',
            'filtering_attribute_id',
        ];
    }

    public function map($category): array
    {
        return [
            $category->name,
            $category->digital,
            $category->parent_id,
            $category->order_level,
            $this->getUploadLink($category->banner),
            $this->getUploadLink($category->icon),
            $this->getUploadLink($category->cover_image),
            $category->meta_title,
            $category->meta_description,
            $category->meta_keywords,
            $category->attributes->pluck('id')->implode(','),

        ];
    }

    private function getUploadLink($uploadId)
    {
        if (empty($uploadId)) {
            return null;
        }

        $upload = Upload::find($uploadId);

        if (!$upload) {
            return null;
        }

        if (!empty($upload->external_link)) {
            return $upload->external_link;
        }

        $path = ltrim($upload->file_name, '/');

        if (!str_starts_with($path, 'public/')) {
            $path = 'public/' . $path;
        }

        return asset($path);
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF4472C4']],
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
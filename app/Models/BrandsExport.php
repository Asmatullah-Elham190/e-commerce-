<?php

namespace App\Models;

use App\Models\Brand;
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

class BrandsExport implements WithMultipleSheets
{
    use Exportable;

    public function sheets(): array
    {
        return [
            new BrandTemplateSheet(),
        ];
    }
}

class BrandTemplateSheet implements FromCollection, WithMapping, WithHeadings, WithStyles, WithTitle, WithEvents
{
    use PreventDemoModeChanges;

    use AutoSizeColumns;

    public function title(): string
    {
        return 'All Brand';
    }

    public function collection()
    {
        return Brand::get();
    }

    public function headings(): array
    {
        return [
            'name',
            'logo',
            'meta_title',
            'meta_description',
            'meta_keywords',
        ];
    }
    public function map($brand): array
    {

        return [
            $brand->name,
            $this->getUploadLink($brand->logo),
            $brand->meta_title,
            $brand->meta_description,
            $brand->meta_keywords,
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
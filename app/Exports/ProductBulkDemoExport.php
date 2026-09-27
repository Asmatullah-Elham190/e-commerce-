<?php

namespace App\Exports;

use App\Models\Brand;
use App\Models\Category;
use App\Models\FlashDeal;
use App\Models\Note;
use App\Models\Product;
use App\Models\Unit;
use App\Models\Warranty;
use App\Traits\PreventDemoModeChanges;
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

class ProductBulkDemoExport implements WithMultipleSheets
{
    use Exportable;

    public function sheets(): array
    {
        return [
            new ProductTemplateSheet(),
            new CategorySheet(),
            new ProductSheet(),
            new BrandSheet(),
            new FlashSaleSheet(),
            new UnitSheet(),
            new NoteSheet(),
            new WarrantySheet(),
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

class ProductTemplateSheet implements FromCollection, WithTitle, WithHeadings, WithStyles, WithEvents
{
    use PreventDemoModeChanges;
    use AutoSizeColumns;

    public function title(): string
    {
        return 'Product Template';
    }

    public function headings(): array
    {
        return [
            'name', 
            'main_category_id', 
            'brand_id', 
            'related_categories', 
            'unit_id',
            'weight', 
            'minimum_purchase_qty', 
            'barcode', 
            'tags',
            'thumbnail_img', 
            'gallery_images', 
            'youtube_video_link', 
            'videos',
            'video_thumbnail', 
            'pdf_specification', 
            'description',
            'meta_title', 
            'meta_description', 
            'meta_image', 
            'meta_tags',
            'unit_price', 
            'discount_start_date', 
            'discount_end_date' , 
            'discount', 
            'discount_type',
            'stock', 
            'sku', 
            'product_external_link', 
            'external_link_button_text',
            'published', 
            'featured', 
            'todays_deal',
            'flash_sale_id', 
            'flash_sale_discount', 
            'flash_sale_discount_type',
            'refundable', 
            'show_refund_notes', 
            'refund_note_id', 
            'club_point',
            'has_warranty', 
            'warranty_id', 
            'show_warranty_note', 
            'warranty_note_id',
            'shipping_type', 
            'shipping_cost', 
            'is_product_quantity_multiply',
            'est_shipping_days', 
            'show_shipping_days',

            'show_shipping_notes',
            'shipping_note_id',
            'enable_cash_on_delivery',
            'show_cash_on_delivery_notes',
            'cash_on_delivery_note_id',
            'hsn_code',
            'gst_rate',
            'stock_visibility_state',
            'low_stock_quantity',
            'frequently_bought_selection_type',
            'fq_bought_product_ids',
            'fq_bought_category_ids',
        ];
    }

    public function collection()
    {
        return collect([
            [
                'Demo Product 22', 
                1, 
                1, 
                '1,2', 
                1,
                0.5, 
                3, 
                'cwae123124cn', 
                'Best Selling,demo Product,Baby product',
                '', 
                '', 
                'https://www.youtube.com/watch?v=m9coOXt5nuw, https://www.youtube.com/watch?v=QC8iQqtG0hg',
                '', 
                '', 
                '', 
                'Demo Description',
                'Demo Meta Title', 
                'Demo Meta Description', 
                '', 
                'Demo Meta Tag1, Demo Meta Tag2',
                100, 
                'Y-D-M', 
                'Y-D-M', 
                20, 
                'amount/percent',
                100, 
                'demo-product-231', 
                'https://www.youtube.com/watch?v=m9coOXt5nuw', 
                'Demo Link',
                '0/1', 
                '0/1', 
                '0/1',
                3, 
                30, 
                'amount/percent',
                '0/1', 
                '0/1', 
                2, 
                20,
                '0/1', 
                5, 
                '0/1', 
                3,
                'free/ flat_rate', 
                150, 
                '0/1',
                7, 
                '0/1',

                '0/1',
                4,
                '0/1',
                '0/1',
                7,
                'adsb21',
                5,
                'hide/quantity/text',
                5,
                'product/category',
                '2,3,5',
                '1,6,7',
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

class ProductSheet implements FromCollection, WithTitle, WithHeadings, WithStyles, WithEvents
{
    use AutoSizeColumns;

    public function title(): string { return 'Product'; }
    public function headings(): array { return ['ID', 'Name']; }

    public function collection()
    {
        return Product::select('id', 'name')->orderBy('id')->get()
            ->map(fn($c) => [$c->id, $c->name]);
    }

    public function styles(Worksheet $sheet): array
    {
        return [1 => ['font' => ['bold' => true], 'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFFFC000']]]];
    }
}

class BrandSheet implements FromCollection, WithTitle, WithHeadings, WithStyles, WithEvents
{
    use AutoSizeColumns;

    public function title(): string { return 'Brand'; }

    public function headings(): array { return ['ID', 'Name']; }

    public function collection()
    {
        return Brand::select('id', 'name')->orderBy('id')->get()
            ->map(fn($b) => [$b->id, $b->name]);
    }

    public function styles(Worksheet $sheet): array
    {
        return [1 => ['font' => ['bold' => true], 'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFFFC000']]]];
    }
}

class FlashSaleSheet implements FromCollection, WithTitle, WithHeadings, WithStyles, WithEvents
{
    use AutoSizeColumns;

    public function title(): string { return 'Flash Sale'; }
    public function headings(): array { return ['ID', 'Title']; }

    public function collection()
    {
        return FlashDeal::select('id', 'title')->orderBy('id')->get()
            ->map(fn($f) => [$f->id, $f->title]);
    }

    public function styles(Worksheet $sheet): array
    {
        return [1 => ['font' => ['bold' => true], 'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFFFC000']]]];
    }
}

class UnitSheet implements FromCollection, WithTitle, WithHeadings, WithStyles, WithEvents
{
    use AutoSizeColumns;

    public function title(): string { return 'Unit'; }
    public function headings(): array { return ['ID', 'Name']; }

    public function collection()
    {
        return Unit::select('id', 'name')->orderBy('id')->get()
            ->map(fn($u) => [$u->id, $u->name]);
    }

    public function styles(Worksheet $sheet): array
    {
        return [1 => ['font' => ['bold' => true], 'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFFFC000']]]];
    }
}

class NoteSheet implements FromCollection, WithTitle, WithHeadings, WithStyles, WithEvents
{
    use AutoSizeColumns;

    public function title(): string { return 'Note'; }

    public function headings(): array { return ['ID', 'Description', 'Note Type']; }

    public function collection()
    {
        return Note::select('id', 'description', 'note_type')->orderBy('id')->get()
            ->map(fn($n) => [$n->id, $n->description, $n->note_type]);
    }

    public function styles(Worksheet $sheet): array
    {
        return [1 => ['font' => ['bold' => true], 'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFFFC000']]]];
    }
}

class WarrantySheet implements FromCollection, WithTitle, WithHeadings, WithStyles, WithEvents
{
    use AutoSizeColumns;

    public function title(): string { return 'Warranty'; }

    public function headings(): array { return ['ID', 'Warranty Text']; }

    public function collection()
    {
        return Warranty::select('id', 'text')->orderBy('id')->get()
            ->map(fn($w) => [$w->id, $w->text]);
    }

    public function styles(Worksheet $sheet): array
    {
        return [1 => ['font' => ['bold' => true], 'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFFFC000']]]];
    }
}

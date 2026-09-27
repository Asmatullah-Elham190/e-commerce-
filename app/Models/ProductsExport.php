<?php

namespace App\Models;

use App\Models\Product;
use App\Traits\PreventDemoModeChanges;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Carbon\Carbon;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ProductsExport implements WithMultipleSheets
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

class ProductTemplateSheet implements FromCollection, WithMapping, WithHeadings, WithStyles, WithEvents, WithTitle
{
    use PreventDemoModeChanges;

    use AutoSizeColumns;

    public function title(): string
    {
        return 'Products';
    }

    public function collection()
    {
        return Product::with(['stocks', 'product_categories'])->get();
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
            'discount_end_date',
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

    public function map($product): array
    {
        $qty = 0;
        foreach ($product->stocks as $stock) {
            $qty += $stock->qty;
        }

        $sku = optional($product->stocks->first())->sku;

        $relatedCategories = $product->product_categories
            ->pluck('category_id')
            ->reject(fn($id) => $id == $product->category_id)
            ->implode(',');

        $flashDealProduct = $product->flash_deal_products->first();

        $fqProducts = $product->frequently_bought_products()
            ->whereNotNull('frequently_bought_product_id')
            ->pluck('frequently_bought_product_id')
            ->implode(',');

        $fqCategories = $product->frequently_bought_products()
            ->whereNotNull('category_id')
            ->pluck('category_id')
            ->implode(',');

        return [
            $product->name,
            $product->category_id,
            $product->brand_id,
            $relatedCategories,
            $product->unit,
            $product->weight,
            $product->min_qty,
            $product->barcode,
            $product->tags,
            $this->getUploadLink($product->thumbnail_img),
            $this->getUploadLinks($product->photos),
            $product->video_link,
            $this->getUploadLinks($product->short_video),
            $this->getUploadLink($product->short_video_thumbnail),
            $this->getUploadLink($product->pdf),
            $product->description,
            $product->meta_title,
            $product->meta_description,
            $this->getUploadLink($product->meta_img),
            $product->meta_keywords,
            $product->unit_price,
            $product->discount_start_date ? Carbon::createFromTimestamp($product->discount_start_date)->format('Y-d-m') : null,
            $product->discount_end_date ? Carbon::createFromTimestamp($product->discount_end_date)->format('Y-d-m') : null,
            $product->discount,
            $product->discount_type,
            $product->current_stock,
            $sku,
            $product->external_link,
            $product->external_link_btn,
            $product->published,
            $product->featured,
            $product->todays_deal,
            $flashDealProduct->flash_deal_id ?? null,
            $flashDealProduct->discount ?? null,
            $flashDealProduct->discount_type ?? null,
            $product->refundable,
            $product->show_refund_notes,
            $product->refund_note_id,
            $product->earn_point,
            $product->has_warranty,
            $product->warranty_id,
            $product->show_warranty_note,
            $product->warranty_note_id,
            $product->shipping_type,
            $product->shipping_cost,
            $product->is_quantity_multiplied,
            $product->est_shipping_days,
            $product->show_estimated_shipping_time,
            $product->show_shipping_note,
            $product->shipping_note_id,
            $product->cash_on_delivery,
            $product->show_delivery_notes,
            $product->delivery_note_id,
            $product->hsn_code,
            $product->gst_rate,
            $product->stock_visibility_state,
            $product->low_stock_quantity,
            $product->frequently_bought_selection_type,
            $fqProducts,
            $fqCategories,
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

    private function getUploadLinks($uploadIds)
    {
        if (empty($uploadIds)) return null;
        $ids = explode(',', $uploadIds);
        $links = [];
        foreach ($ids as $id) {
            $link = $this->getUploadLink(trim($id));
            if ($link) $links[] = $link;
        }
        return implode(',', $links);
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

    public function title(): string
    {
        return 'Category';
    }
    public function headings(): array
    {
        return ['ID', 'Name'];
    }

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

    public function title(): string
    {
        return 'Product';
    }
    public function headings(): array
    {
        return ['ID', 'Name'];
    }

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

    public function title(): string
    {
        return 'Brand';
    }

    public function headings(): array
    {
        return ['ID', 'Name'];
    }

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

    public function title(): string
    {
        return 'Flash Sale';
    }
    public function headings(): array
    {
        return ['ID', 'Title'];
    }

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

    public function title(): string
    {
        return 'Unit';
    }
    public function headings(): array
    {
        return ['ID', 'Name'];
    }

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

    public function title(): string
    {
        return 'Note';
    }

    public function headings(): array
    {
        return ['ID', 'Description', 'Note Type'];
    }

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

    public function title(): string
    {
        return 'Warranty';
    }

    public function headings(): array
    {
        return ['ID', 'Warranty Text'];
    }

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

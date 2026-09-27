<?php

namespace App\Models;

use App\Models\ProductTranslation;
use App\Models\ProductStock;
use App\Models\User;
use App\Traits\PreventDemoModeChanges;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Str;
use Auth;
use Carbon\Carbon;

class ProductsImport implements ToCollection, WithHeadingRow, WithMultipleSheets
{
    use PreventDemoModeChanges;

    public function sheets(): array
    {
        return [
            0 => $this,
        ];
    }

    public function collection(Collection $rows)
    {
        $canImport = true;
        $user = Auth::user();
        if ($user->user_type == 'seller' && addon_is_activated('seller_subscription')) {
            if (
                (count($rows) + $user->products()->count()) > $user->shop->product_upload_limit
                || $user->shop->package_invalid_at == null
                || Carbon::now()->diffInDays(Carbon::parse($user->shop->package_invalid_at), false) < 0
            ) {
                $canImport = false;
                flash(translate('Please upgrade your package.'))->warning();
            }
        }

        if ($canImport) {
            foreach ($rows as $row) {

                $requiredErrors = [];

                if (empty($row['name'])) {
                    $requiredErrors[] = 'Name is required.';
                }
                if (empty($row['unit_price']) || !is_numeric($row['unit_price'])) {
                    $requiredErrors[] = 'Unit price is required and must be numeric.';
                }
                if (empty($row['main_category_id']) || !is_numeric($row['main_category_id'])) {
                    $requiredErrors[] = 'Main Category ID is required and must be numeric.';
                }
                if (empty($row['unit_id']) || !is_numeric($row['unit_id'])) {
                    $requiredErrors[] = 'Unit ID is required and must be numeric.';
                }
                if (!isset($row['stock']) || !is_numeric($row['stock'])) {
                    $requiredErrors[] = 'Stock is required and must be numeric.';
                }

                if (!empty($requiredErrors)) {
                    flash(translate('Skipped row "' . ($row['name'] ?? 'unknown') . '": ' . implode(' ', $requiredErrors)))->error();
                    continue; 
                }

                $optionalWarnings = [];

                $brandId = $row['brand_id'] ?? null;
                if (!empty($brandId) && !is_numeric($brandId)) {
                    $optionalWarnings[] = 'Brand ID was invalid, skipped.';
                    $brandId = null;
                }

                $discountType = $row['discount_type'] ?? null;
                if (!empty($discountType) && !in_array($discountType, ['amount', 'percent'])) {
                    $optionalWarnings[] = 'Discount type was invalid, skipped.';
                    $discountType = null;
                }

                $stockVisibilityState = $row['stock_visibility_state'] ?? null;
                if (!empty($stockVisibilityState) && !in_array($stockVisibilityState, ['hide', 'quantity', 'text'])) {
                    $optionalWarnings[] = 'Stock Visibility State was invalid, skipped.';
                    $stockVisibilityState = null;
                }

                $frequentlyBoughtSelectionType = $row['frequently_bought_selection_type'] ?? null;
                if (!empty($frequentlyBoughtSelectionType) && !in_array($frequentlyBoughtSelectionType, ['product', 'category'])) {
                    $optionalWarnings[] = 'Frequently Bought Selection Type was invalid, skipped.';
                    $frequentlyBoughtSelectionType = null;
                }

                $shippingType = $row['shipping_type'] ?? null;
                if (!empty($shippingType) && !in_array($shippingType, ['free', 'flat_rate'])) {
                    $optionalWarnings[] = 'Shipping type was invalid, set to free.';
                    $shippingType = 'free';
                }

                $boolFields = ['published', 'featured', 'todays_deal', 'refundable', 'show_refund_notes', 'has_warranty', 'show_warranty_note', 'is_product_quantity_multiply', 'show_shipping_days','show_shipping_notes', 'enable_cash_on_delivery', 'show_cash_on_delivery_notes', 'low_stock_quantity_warning',];
                $boolValues = [];
                foreach ($boolFields as $field) {
                    $val = $row[$field] ?? 0;
                    if (!in_array((string)$val, ['0', '1'])) {
                        $optionalWarnings[] = "$field was invalid, set to 0.";
                        $val = 0;
                    }
                    $boolValues[$field] = (int)$val;
                }

                if (!empty($optionalWarnings)) {
                    flash(translate('Row "' . $row['name'] . '" imported with warnings: ' . implode(' ', $optionalWarnings)))->warning();
                }

                $approved = 1;
                if ($user->user_type == 'seller' && get_setting('product_approve_by_admin') == 1) {
                    $approved = 0;
                }

                try {
                    $product = new Product();

                    $product->added_by = $user->user_type == 'seller' ? 'seller' : 'admin';
                    $product->user_id  = $user->user_type == 'seller' ? $user->id : User::where('user_type', 'admin')->first()->id;
                    $product->approved = $approved;
                    $product->slug     = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', strtolower($row['name']))) . '-' . Str::random(5);

                    $product->name             = $row['name'];
                    $product->category_id      = $row['main_category_id'];
                    $product->brand_id         = $brandId; 
                    $product->unit             = $row['unit_id'];
                    $product->weight           = $row['weight'] ?? null;
                    $product->min_qty          = $row['minimum_purchase_qty'] ?? 1;
                    $product->barcode          = $row['barcode'] ?? null;
                    $product->tags             = $row['tags'] ?? null;

                    $product->thumbnail_img         = $this->uploadImage($row['thumbnail_img'] ?? null);
                    $product->photos                = $this->uploadImages($row['gallery_images'] ?? null);
                    $product->video_link            = $row['youtube_video_link'] ?? null;
                    $product->short_video           = $this->uploadVideos($row['videos'] ?? null);
                    $product->short_video_thumbnail = $this->uploadImage($row['video_thumbnail'] ?? null);
                    $product->pdf                   = $this->uploadPdf($row['pdf_specification'] ?? null);

                    $product->description      = $row['description'] ?? null;
                    $product->meta_title       = $row['meta_title'] ?? null;
                    $product->meta_description = $row['meta_description'] ?? null;
                    $product->meta_img         = $this->uploadImage($row['meta_image'] ?? null);
                    $product->meta_keywords    = $row['meta_tags'] ?? null;

                    $product->unit_price  = $row['unit_price'];
                    $product->discount    = $row['discount'] ?? 0;
                    $product->discount_type       = $discountType;
                    $product->discount_start_date = !empty($row['discount_start_date'])
                        ? Carbon::createFromFormat('Y-d-m', $row['discount_start_date'])->timestamp
                        : null;
                    $product->discount_end_date   = !empty($row['discount_end_date'])
                        ? Carbon::createFromFormat('Y-d-m', $row['discount_end_date'])->timestamp
                        : null;

                    $product->current_stock    = $row['stock'];
                    $product->external_link    = $row['product_external_link'] ?? null;
                    $product->external_link_btn = $row['external_link_button_text'] ?? null;

                    $product->published    = $boolValues['published'];
                    $product->featured     = $boolValues['featured'];
                    $product->todays_deal  = $boolValues['todays_deal'];

                    $product->has_warranty        = $boolValues['has_warranty'];
                    $product->warranty_id         = $row['warranty_id'] ?? null;
                    $product->warranty_note_id    = $row['warranty_note_id'] ?? null;
                    $product->show_warranty_note  = $boolValues['show_warranty_note'];

                    $product->shipping_type               = $shippingType;
                    $product->shipping_cost               = $row['shipping_cost'] ?? 0;
                    $product->is_quantity_multiplied      = $boolValues['is_product_quantity_multiply'];
                    $product->est_shipping_days           = $row['est_shipping_days'] ?? null;
                    $product->show_estimated_shipping_time = $boolValues['show_shipping_days'];
                    $product->colors = json_encode(array());
                    $product->choice_options = json_encode(array());

                    $product->show_shipping_note = $boolValues['show_shipping_notes'];
                    $product->shipping_note_id    = $row['shipping_note_id'] ?? null;
                    $product->cash_on_delivery = $boolValues['enable_cash_on_delivery'];
                    $product->show_delivery_notes = $boolValues['show_cash_on_delivery_notes'];
                    $product->delivery_note_id    = $row['cash_on_delivery_note_id'] ?? null;
                    $product->hsn_code    = $row['hsn_code'];
                    $product->gst_rate    = $row['gst_rate'];
                    $product->stock_visibility_state       = $stockVisibilityState;
                    $product->low_stock_quantity = $row['low_stock_quantity'];
                    $product->frequently_bought_selection_type       = $frequentlyBoughtSelectionType;

                    $product->save();

                    if (isset($row['fq_bought_product_ids']) && 
                        $row['fq_bought_product_ids'] != null && 
                        $row['frequently_bought_selection_type'] == 'product') {

                        $fqProductIds = array_filter(
                            array_map('trim', explode(',', (string)$row['fq_bought_product_ids']))
                        );

                        foreach ($fqProductIds as $fq_product) {
                            if (is_numeric($fq_product)) {
                                FrequentlyBoughtProduct::insert([
                                    'product_id'    => $product->id,
                                    'frequently_bought_product_id' => $fq_product,
                                ]);
                            }
                        }
                    }
                    elseif (isset($row['fq_bought_category_ids']) && 
                            $row['fq_bought_category_ids'] != null && 
                            $row['frequently_bought_selection_type'] == 'category') {

                        $fqCategoryIds = array_filter(
                            array_map('trim', explode(',', (string)$row['fq_bought_category_ids']))
                        );

                        foreach ($fqCategoryIds as $fq_category) {
                            if (is_numeric($fq_category)) {
                                FrequentlyBoughtProduct::insert([
                                    'product_id'  => $product->id,
                                    'category_id' => $fq_category,
                                ]);
                            }
                        }
                    }

                    if (addon_is_activated('refund_request')) {
                        $product->refundable       = $boolValues['refundable'];
                        $product->refund_note_id   = $row['refund_note_id'] ?? null;
                        $product->show_refund_notes = $boolValues['show_refund_notes'];
                        $product->save();
                    }

                    if (addon_is_activated('club_point')) {
                        $product->earn_point = $row['club_point'] ?? 0;
                        $product->save();
                    }

                    if (!empty($row['flash_sale_id'])) {
                        $flash = FlashDeal::find($row['flash_sale_id']);
                        if ($flash) {
                            FlashDealProduct::create([
                                'flash_deal_id' => $row['flash_sale_id'],
                                'product_id'    => $product->id,
                                'discount'      => $row['flash_sale_discount'] ?? 0,
                                'discount_type' => $row['flash_sale_discount_type'] ?? null,
                            ]);
                            $product->discount_start_date = $flash->start_date;
                            $product->discount_end_date   = $flash->end_date;
                            $product->promotional         = 1;
                            $product->save();
                        }
                    }

                    if ($boolValues['todays_deal'] === 1) {
                        $product->promotional = 1;
                        $product->save();
                    }

                    $stock = new ProductStock();
                    $stock->product_id = $product->id;
                    $stock->qty        = $row['stock'];
                    $stock->price      = $row['unit_price'];
                    $stock->sku        = $row['sku'] ?? null;
                    $stock->variant    = '';
                    $stock->save();

                    if (!empty($row['related_categories'])) {
                        foreach (explode(',', $row['related_categories']) as $category_id) {
                            $category_id = trim($category_id);
                            if ($category_id) {
                                ProductCategory::insert([
                                    'product_id'  => $product->id,
                                    'category_id' => $category_id,
                                ]);
                            }
                        }
                    }
                    ProductCategory::insert([
                        'product_id'  => $product->id,
                        'category_id' => $row['main_category_id'],
                    ]);

                    $translation = new ProductTranslation();
                    $translation->lang        = env('DEFAULT_LANGUAGE', 'en');
                    $translation->name        = $row['name'];
                    $translation->unit        = $row['unit_id'];
                    $translation->description = $row['description'] ?? null;
                    $translation->product_id  = $product->id;
                    $translation->save();
                } catch (\Throwable $e) {
                    flash(translate('Error on row "' . ($row['name'] ?? '?') . '": ' . $e->getMessage()))->error();
                }
            }

            flash(translate('Products imported successfully'))->success();
        }
    }


    private function uploadImage($url)
    {
        if (empty($url)) return null;
        try {
            $upload = new Upload();
            $upload->external_link = trim($url);
            $upload->type = 'image';
            $upload->save();
            return $upload->id;
        } catch (\Exception $e) {
            return null;
        }
    }

    private function uploadImages($urls)
    {
        if (empty($urls)) return null;
        $data = [];
        foreach (explode(',', str_replace(' ', '', $urls)) as $url) {
            if ($url) $data[] = $this->uploadImage($url);
        }
        return implode(',', array_filter($data));
    }

    private function uploadVideo($url)
    {
        if (empty($url)) return null;
        try {
            $upload = new Upload();
            $upload->external_link = trim($url);
            $upload->type = 'video';
            $upload->save();
            return $upload->id;
        } catch (\Exception $e) {
            return null;
        }
    }

    private function uploadVideos($urls)
    {
        if (empty($urls)) return null;
        $data = [];
        foreach (explode(',', str_replace(' ', '', $urls)) as $url) {
            if ($url) $data[] = $this->uploadVideo($url);
        }
        return implode(',', array_filter($data));
    }

    private function uploadPdf($url)
    {
        if (empty($url)) return null;
        try {
            $upload = new Upload();
            $upload->external_link = trim($url);
            $upload->type = 'document';
            $upload->save();
            return $upload->id;
        } catch (\Exception $e) {
            return null;
        }
    }
}

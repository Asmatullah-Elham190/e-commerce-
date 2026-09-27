<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Google\Client as GoogleClient;

class GoogleMerchantCenterController extends Controller
{
    private $merchantId;
    
    public function __construct()
    {
        $this->merchantId = config('google.merchant_id');
    }

    public function productFeedGenerate(Request $request)
    {
        $seller_type = 'all';
        $product_types = ['All Products', 'Inhouse Products', 'Seller Products'];
        $categories = Category::where('parent_id', 0)->with('childrenCategories')->get();
        
        return view('backend.gmc.index', compact('seller_type', 'product_types', 'categories'));
    }

    public function generateXMLFeed(Request $request)
    {
        $productIds = $request->product_ids ? explode(',', $request->product_ids) : [];
        
        if (empty($productIds)) {
            Log::error('No products selected for XML export');
            return response()->json(['error' => 'No products selected'], 400);
        }
        
        $products = Product::with(['brand', 'main_category', 'stocks'])
            ->whereIn('id', $productIds)
            ->where('published', 1)
            ->where('approved', 1)
            ->where('draft', 0)
            ->get();
        
        if ($products->isEmpty()) {
            Log::error('No valid products found for XML export');
            return response()->json(['error' => 'No valid products found'], 404);
        }
        
        return $this->generateXML($products);
    }

    public function generateCSVFeed(Request $request)
    {
        $productIds = $request->product_ids ? explode(',', $request->product_ids) : [];
        
        if (empty($productIds)) {
            return response()->json(['error' => 'No products selected'], 400);
        }
        
        $products = Product::with(['brand', 'main_category', 'stocks'])
            ->whereIn('id', $productIds)
            ->where('published', 1)
            ->where('approved', 1)
            ->where('draft', 0)
            ->get();
        
        if ($products->isEmpty()) {
            return response()->json(['error' => 'No valid products found'], 404);
        }
        
        return $this->generateCSV($products);
    }

    /**
     * Get accurate stock count for a product (handles variants)
     */
    private function getProductStock($product)
    {
        if (!$product) {
            return 0;
        }
        
        // If product has variants, sum all variant stock
        if ($product->variant_product == 1) {
            return $product->stocks->sum('qty') ?? 0;
        } else {
            // For simple products, use current_stock
            return $product->current_stock ?? 0;
        }
    }

    /**
     * Get availability status with proper caching headers
     */
    private function getAvailability($product)
    {
        $stock = $this->getProductStock($product);
        return $stock > 0 ? 'in stock' : 'out of stock';
    }

    private function generateXML($products)
    {
        try {
            $currency = get_system_currency();
            $siteUrl = url('/');
            $siteName = get_setting('site_name');
            
            $xmlString = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
            $xmlString .= '<rss version="2.0" xmlns:g="http://base.google.com/ns/1.0">' . "\n";
            $xmlString .= '<channel>' . "\n";
            
            $xmlString .= '<title>' . $this->xmlEscape($siteName . ' - Product Feed') . '</title>' . "\n";
            $xmlString .= '<link>' . $siteUrl . '</link>' . "\n";
            $xmlString .= '<description>' . $this->xmlEscape('Product feed for Google Merchant Center') . '</description>' . "\n";
            
            $processedCount = 0;
            $stockDebug = [];
            
            foreach ($products as $product) {
                try {
                    // Debug stock information
                    $stock = $this->getProductStock($product);
                    $availability = $this->getAvailability($product);
                    
                    $stockDebug[] = [
                        'id' => $product->id,
                        'name' => $product->name,
                        'variant_product' => $product->variant_product,
                        'current_stock' => $product->current_stock,
                        'total_stock' => $stock,
                        'availability' => $availability
                    ];
                    
                    $xmlString .= '<item>' . "\n";
                    
                    $xmlString .= '<g:id>' . $product->id . '</g:id>' . "\n";
                    $xmlString .= '<title>' . $this->xmlEscape($product->getTranslation('name')) . '</title>' . "\n";
                    
                    $description = strip_tags($product->getTranslation('description'));
                    $description = $this->cleanString($description);
                    $description = substr($description, 0, 5000);
                    $xmlString .= '<description>' . $this->xmlEscape($description) . '</description>' . "\n";
                    $xmlString .= '<g:link>' . $this->xmlEscape(route('product', $product->slug)) . '</g:link>' . "\n";
                    
                    $xmlString .= '<g:image_link>' . $this->xmlEscape(uploaded_asset($product->thumbnail_img)) . '</g:image_link>' . "\n";
                    
                    if ($product->photos) {
                        $images = explode(',', $product->photos);
                        if (!empty($images)) {
                            $xmlString .= '<g:additional_image_link>' . $this->xmlEscape(uploaded_asset(trim($images[0]))) . '</g:additional_image_link>' . "\n";
                        }
                    }
                    
                    $originalPrice = $product->unit_price;
                    $currencyCode = $currency->code;
                    
                    $salePrice = home_discounted_base_price($product, false);
                    
                    $formattedOriginalPrice = number_format($originalPrice, 2, '.', '') . ' ' . $currencyCode;
                    $xmlString .= '<g:price>' . $formattedOriginalPrice . '</g:price>' . "\n";
                    
                    if ($salePrice !== null && $salePrice < $originalPrice) {
                        $formattedSalePrice = number_format($salePrice, 2, '.', '') . ' ' . $currencyCode;
                        $xmlString .= '<g:sale_price>' . $formattedSalePrice . '</g:sale_price>' . "\n";
                        if ($product->discount_start_date && $product->discount_end_date) {
                            $saleDate = $product->discount_start_date . 'T00:00:00+0000/' . $product->discount_end_date . 'T23:59:59+0000';
                            $xmlString .= '<g:sale_price_effective_date>' . $saleDate . '</g:sale_price_effective_date>' . "\n";
                        }
                    }
                    
                    $xmlString .= '<g:availability>' . $availability . '</g:availability>' . "\n";
                    
                    $brand = $product->brand->name ?? 'No Brand';
                    $xmlString .= '<g:brand>' . $this->xmlEscape($brand) . '</g:brand>' . "\n";
                    
                    $xmlString .= '<g:condition>new</g:condition>' . "\n";
                    
                    $mpn = $product->sku ?? (string)$product->id;
                    $xmlString .= '<g:mpn>' . $this->xmlEscape($mpn) . '</g:mpn>' . "\n";
                    
                    $productType = $product->main_category->name ?? 'Uncategorized';
                    $xmlString .= '<g:product_type>' . $this->xmlEscape($productType) . '</g:product_type>' . "\n";
                    
                    $googleCategory = $this->getGoogleProductCategory($product->main_category->name ?? '');
                    $xmlString .= '<g:google_product_category>' . $this->xmlEscape($googleCategory) . '</g:google_product_category>' . "\n";
                    
                    $xmlString .= '</item>' . "\n";
                    $processedCount++;
                    
                } catch (\Exception $e) {
                    //Log::error('Error processing product for Google XML', [
                    //    'product_id' => $product->id,
                    //    'error' => $e->getMessage()
                    //]);
                    continue;
                }
            }
            
            $xmlString .= '</channel>' . "\n";
            $xmlString .= '</rss>';
            
            $xmlString = $this->cleanInvalidXmlChars($xmlString);
            
            $response = response($xmlString, 200);
            $response->header('Content-Type', 'application/xml; charset=UTF-8');
            $response->header('Content-Disposition', 'attachment; filename="google_feed_' . date('Y-m-d_H-i-s') . '.xml"');
            
            // Add no-cache headers to prevent stale data
            $response->header('Cache-Control', 'no-cache, no-store, must-revalidate');
            $response->header('Pragma', 'no-cache');
            $response->header('Expires', '0');
            
            return $response;
            
        } catch (\Exception $e) {
            Log::error('Google XML generation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'XML generation failed: ' . $e->getMessage()
            ], 500);
        }
    }

    private function generateCSV($products)
    {
        try {
            $currency = get_system_currency();
            $currencyCode = $currency->code;
            $filename = 'google_feed_' . date('Y-m-d_H-i-s') . '.csv';
            $handle = fopen('php://temp', 'w+');
            
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Headers matching Google Merchant Center requirements
            $headers = [
                'id', 'title', 'description', 'link', 'image_link', 'additional_image_link',
                'price', 'sale_price', 'sale_price_effective_date', 'availability', 
                'brand', 'condition', 'mpn', 'product_type', 'google_product_category',
                'item_group_id', 'gender', 'color', 'size', 'age_group', 'material',
                'pattern', 'shipping', 'shipping_weight', 'gtin'
            ];
            
            fputcsv($handle, $headers);
            $processedCount = 0;
            $stockDebug = [];
            
            foreach ($products as $product) {
                try {
                    $originalPrice = $product->unit_price;
                    $salePrice = home_discounted_base_price($product, false);
                    
                    $formattedOriginalPrice = number_format($originalPrice, 2, '.', '') . ' ' . $currencyCode;
                    
                    $formattedSalePrice = ($salePrice !== null && $salePrice < $originalPrice) 
                        ? number_format($salePrice, 2, '.', '') . ' ' . $currencyCode 
                        : '';
                    
                    $saleDate = '';
                    if ($salePrice !== null && $salePrice < $originalPrice && $product->discount_start_date && $product->discount_end_date) {
                        $saleDate = $product->discount_start_date . 'T00:00:00+0000/' . $product->discount_end_date . 'T23:59:59+0000';
                    }
                    
                    $imageUrl = uploaded_asset($product->thumbnail_img);
                    
                    $additionalImage = '';
                    if ($product->photos) {
                        $images = explode(',', $product->photos);
                        if (!empty($images)) {
                            $additionalImage = uploaded_asset(trim($images[0]));
                        }
                    }
                    
                    $availability = $this->getAvailability($product);
                    
                    $stock = $this->getProductStock($product);
                    $stockDebug[] = [
                        'id' => $product->id,
                        'name' => $product->name,
                        'variant_product' => $product->variant_product,
                        'current_stock' => $product->current_stock,
                        'total_stock' => $stock,
                        'availability' => $availability
                    ];
                    
                    $row = [
                        'id' => (string) $product->id,
                        'title' => $this->cleanCsvField($product->getTranslation('name')),
                        'description' => $this->cleanCsvField(substr(strip_tags($product->getTranslation('description')), 0, 5000)),
                        'link' => route('product', $product->slug),
                        'image_link' => $imageUrl,
                        'additional_image_link' => $additionalImage,
                        'price' => $formattedOriginalPrice,
                        'sale_price' => $formattedSalePrice,
                        'sale_price_effective_date' => $saleDate,
                        'availability' => $availability,
                        'brand' => $this->cleanCsvField($product->brand->name ?? 'Generic'),
                        'condition' => 'new',
                        'mpn' => $product->sku ?? (string) $product->id,
                        'product_type' => $this->cleanCsvField($product->main_category->name ?? 'Uncategorized'),
                        'google_product_category' => $this->getGoogleProductCategory($product->main_category->name ?? ''),
                        'item_group_id' => '',
                        'gender' => '',
                        'color' => '',
                        'size' => '',
                        'age_group' => '',
                        'material' => '',
                        'pattern' => '',
                        'shipping' => '',
                        'shipping_weight' => '',
                        'gtin' => ''
                    ];
                    
                    fputcsv($handle, $row);
                    $processedCount++;
                    
                } catch (\Exception $e) {
                    Log::error('Error processing product for Google CSV', [
                        'product_id' => $product->id,
                        'error' => $e->getMessage()
                    ]);
                    continue;
                }
            }
            
            rewind($handle);
            $csvContent = stream_get_contents($handle);
            fclose($handle);
            
            $response = response($csvContent, 200);
            $response->header('Content-Type', 'text/csv; charset=UTF-8');
            $response->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
            
            $response->header('Cache-Control', 'no-cache, no-store, must-revalidate');
            $response->header('Pragma', 'no-cache');
            $response->header('Expires', '0');
            
            return $response;
            
        } catch (\Exception $e) {
            Log::error('Google CSV generation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'CSV generation failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function exportProducts(Request $request)
    {
        try {
            $request->validate(['format' => 'required|in:xml,csv,txt']);

            $products = Product::with(['brand', 'main_category', 'stocks'])
                ->where('published', 1)
                ->where('approved', 1)
                ->where('draft', 0)
                ->where('gmc', 1)
                ->limit(5000)
                ->get();

            if($request->product_ids) {
                $productIds = is_array($request->product_ids) 
                    ? $request->product_ids 
                    : explode(',', $request->product_ids);
                $products = $products->whereIn('id', $productIds);
            }

            if ($products->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No products found.'
                ], 404);
            }

            switch ($request->format) {
                case 'xml':
                    return $this->generateXML($products);
                case 'csv':
                    return $this->generateCSV($products);
                default:
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid format selected.'
                    ], 400);
            }
            
        } catch (\Exception $e) {
            Log::error('exportAllProducts for Google failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Export failed: ' . $e->getMessage()
            ], 500);
        }
    }

    private function xmlEscape($string)
    {
        if ($string === null || $string === '') {
            return '';
        }
        
        $string = (string) $string;
        $string = $this->cleanInvalidXmlChars($string);
        
        $string = str_replace(
            ['&', '<', '>', '"', "'"],
            ['&amp;', '&lt;', '&gt;', '&quot;', '&apos;'],
            $string
        );
        
        $string = preg_replace('/&amp;(?!amp;|lt;|gt;|quot;|apos;)/', '&', $string);
        $string = str_replace('&', '&amp;', $string);
        
        return $string;
    }

    private function cleanString($string)
    {
        if ($string === null || $string === '') {
            return '';
        }
        
        $string = str_replace(["\r\n", "\r", "\n", "\t"], ' ', $string);
        $string = preg_replace('/\s+/', ' ', $string);
        
        return trim($string);
    }

    private function cleanInvalidXmlChars($string)
    {
        if ($string === null || $string === '') {
            return '';
        }
        
        $pattern = '/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u';
        $string = preg_replace($pattern, ' ', $string);
        
        return $string;
    }

    private function cleanCsvField($string)
    {
        if (empty($string)) {
            return '';
        }
        
        $string = strip_tags($string);
        $string = str_replace(["\r\n", "\r", "\n", "\t"], ' ', $string);
        $string = preg_replace('/\s+/', ' ', $string);
        $string = trim($string);
        
        return $string;
    }

    private function clean($string)
    {
        return htmlspecialchars(strip_tags($string), ENT_QUOTES, 'UTF-8');
    }

    private function getGoogleProductCategory($category)
    {
        $mapping = [
            'Electronics' => 'Electronics',
            'Mobiles & Tabs' => 'Electronics > Communications > Telephony > Mobile Phones',
            'Computers & Accessories' => 'Electronics > Computers',
            'Womens World' => 'Apparel & Accessories > Clothing',
            'Jewellery & Watches' => 'Apparel & Accessories > Jewelry',
            'Kids & Toys' => 'Toys & Games',
            'Home & Garden' => 'Home & Garden',
            'Automobiles' => 'Vehicles & Parts',
            'Pet Supplies' => 'Animals > Pet Supplies'
        ];
        
        return $mapping[$category] ?? 'Other';
    }

    public function get_gmc_products(Request $request)
    {
        $col_name = null;
        $query = null;
        $sort_search = null;
        
        $products = Product::where('auction_product', 0)
            ->where('wholesale_product', 0)
            ->where('draft', 0)
            ->where('gmc', 1)
            ->where('published', 1)
            ->where('approved', 1);
        
        if ($request->seller_type == 'admin') {
            $products = $products->where('added_by', 'admin');
        } elseif ($request->seller_type == 'seller') {
            $products = $products->where('added_by', 'seller');
            if ($request->user_id != null) {
                $products = $products->where('user_id', $request->user_id);
            }
        }

        if ($request->search != null) {
            $sort_search = $request->search;
            $products = $products->where(function($query) use ($sort_search) {
                $query->where('name', 'like', '%' . $sort_search . '%')
                    ->orWhereHas('stocks', function ($q) use ($sort_search) {
                        $q->where('sku', 'like', '%' . $sort_search . '%');
                    });
            });
        }
        
        if ($request->type != null) {
            $var = explode(",", $request->type);
            $col_name = $var[0];
            $query = $var[1];
            $products = $products->orderBy($col_name, $query);
        }

        $filters = $request->selected_filter ?? [];
        if (!empty($filters)) {
            if (in_array('low-stock', $filters)) {
                $products->where(function ($query) {
                    $query->whereRaw("
                        (
                            SELECT CASE
                                WHEN products.variant_product = 1 
                                    THEN (SELECT SUM(qty) FROM product_stocks WHERE product_stocks.product_id = products.id)
                                ELSE 
                                    (SELECT qty FROM product_stocks WHERE product_stocks.product_id = products.id LIMIT 1)
                            END
                        ) <= products.low_stock_quantity
                    ");
                });
            }
            if (in_array('all-discount', $filters)) {
                $products->where('discount', '>', 0);
            }
            if (in_array('all-publish', $filters)) {
                $products->where('published', 1);
            }
            if (in_array('refundable', $filters)) {
                $products->where('refundable', 1);
            }
        }
        
        $products = $products->orderBy('created_at', 'desc')->paginate(15);
        
        $type = $request->seller_type;
        $view = view('backend.gmc.products_table',
            compact('products', 'type', 'col_name', 'query', 'sort_search')
        )->render();

        return response()->json(['html' => $view]);
    }

    public function productUpdateToGMC(Request $request)
    {
        $productIds = $request->id ?? $request->ids;
        if (!$productIds && $request->gmc) {
            return response()->json([
                'success' => false, 
                'message' => translate('No products selected')
            ], 400);
        }
        
        if (is_string($productIds)) {
            $productIds = explode(',', $productIds);
        }
        if (!isset ($request->gmc)) {
            Product::where('gmc', 1)->update(['gmc' => 0]);
            if (!empty($productIds)) {
               Product::whereIn('id', $productIds)->update(['gmc' => 1]);
            }
        }else{
            Product::whereIn('id', $productIds)->update(['gmc' => $request->gmc]);
        }

        return response()->json([
            'success' => true,
            'message' => ('GMC Products Updated successfully')
        ]);
    }

    public function gmc_products_search(Request $request)
    {
        $gmc = 1;
        $products = Product::where('auction_product', 0)->where('wholesale_product', 0)->where('draft', 0)->where('published', 1)->where('approved', 1);
        if($request->search_key != null){
            $products = $products->where('name', 'like', '%' . $request->search_key . '%');
        }
        if($request->category != null){
            $category = Category::with('childrenCategories')->find($request->category);
            $products = $category->products();
        }
        $products = $products->get();
         //$products = $products->paginate(100);
        $single_select = $request->single_select ?? 0;
        return view('partials.product.products_search', compact('products', 'single_select', 'gmc'));
    }


    public function xmlFeed()
    {
        $products = Product::with(['brand', 'main_category', 'stocks'])
            ->where('auction_product', 0)
            ->where('wholesale_product', 0)
            ->where('draft', 0)
            ->where('gmc', 1)
            ->where('published', 1)
            ->where('approved', 1)
            ->get();
        
        $currency = get_system_currency();
        $currencyCode = $currency->code;
        
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<rss version="2.0" xmlns:g="http://base.google.com/ns/1.0">' . "\n";
        $xml .= '<channel>' . "\n";
        $xml .= '<title>' . config('app.name') . ' Products</title>' . "\n";
        $xml .= '<link>' . url('/') . '</link>' . "\n";
        $xml .= '<description>Product Feed for Google Merchant Center</description>' . "\n";
        
        $stockDebug = [];
        
        foreach($products as $product) {
            $originalPrice = $product->unit_price;
            $salePrice = home_discounted_base_price($product, false);
            
            // Get accurate availability
            $availability = $this->getAvailability($product);
            $stock = $this->getProductStock($product);
            
            $stockDebug[] = [
                'id' => $product->id,
                'name' => $product->name,
                'variant_product' => $product->variant_product,
                'current_stock' => $product->current_stock,
                'total_stock' => $stock,
                'availability' => $availability
            ];
            
            $xml .= '<item>' . "\n";
            $xml .= '<g:id>' . $product->id . '</g:id>' . "\n";
            $xml .= '<title>' . $this->clean($product->name) . '</title>' . "\n";
            $xml .= '<description>' . $this->clean(substr($product->description, 0, 5000)) . '</description>' . "\n";
            $xml .= '<g:link>' . url('/product/' . $product->slug) . '</g:link>' . "\n";
            $xml .= '<g:image_link>' . uploaded_asset($product->thumbnail_img) . '</g:image_link>' . "\n";
            
            $xml .= '<g:price>' . number_format($originalPrice, 2, '.', '') . ' ' . $currencyCode . '</g:price>' . "\n";
            
            if ($salePrice !== null && $salePrice < $originalPrice) {
                $xml .= '<g:sale_price>' . number_format($salePrice, 2, '.', '') . ' ' . $currencyCode . '</g:sale_price>' . "\n";
                
                if ($product->discount_start_date && $product->discount_end_date) {
                    $saleDate = $product->discount_start_date . 'T00:00:00+0000/' . $product->discount_end_date . 'T23:59:59+0000';
                    $xml .= '<g:sale_price_effective_date>' . $saleDate . '</g:sale_price_effective_date>' . "\n";
                }
            }
            
            $xml .= '<g:availability>' . $availability . '</g:availability>' . "\n";
            
            $xml .= '<g:brand>' . ($product->brand->name ?? 'Generic') . '</g:brand>' . "\n";
            $xml .= '<g:condition>new</g:condition>' . "\n";
            $xml .= '<g:mpn>' . $product->id . '</g:mpn>' . "\n";
            $xml .= '<g:product_type>' . $this->clean($product->main_category->name ?? 'Uncategorized') . '</g:product_type>' . "\n";
            $xml .= '</item>' . "\n";
        }
        
        $xml .= '</channel>' . "\n";
        $xml .= '</rss>';
        
        // Add no-cache headers
        $response = response($xml, 200);
        $response->header('Content-Type', 'application/xml; charset=UTF-8');
        $response->header('Cache-Control', 'no-cache, no-store, must-revalidate');
        $response->header('Pragma', 'no-cache');
        $response->header('Expires', '0');
        
        return $response;
    }
    
    public function manualPushToGoogle(Request $request)
    {
        try {
            $products = Product::with(['stocks'])
                ->where('auction_product', 0)
                ->where('wholesale_product', 0)
                ->where('draft', 0)
                ->where('gmc', 1)
                ->where('published', 1)
                ->where('approved', 1)
                ->get();

            if($request->id) {
                $productIds = is_array($request->id) ? $request->id : [$request->id];
                $products = $products->whereIn('id', $productIds);
            }
            
            $successCount = 0;
            $failedCount = 0;
            $failedProducts = [];
            
            foreach($products as $product) {
                $response = $this->sendToGoogleAPI($product);
                
                if($response && $response->successful()) {
                    $successCount++;
                } else {
                    $failedCount++;
                    $failedProducts[] = $product->id;
                    Log::error('Google Merchant push failed for product: ' . $product->id);
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => "Sync complete! Success: {$successCount}, Failed: {$failedCount}",
                'failed_products' => $failedProducts
            ]);
            
        } catch(\Exception $e) {
            Log::error('Google Merchant sync error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Sync failed: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function pushSingleProduct($productId)
    {
        try {
            $product = Product::with(['stocks'])->findOrFail($productId);
            $response = $this->sendToGoogleAPI($product);
            
            if($response && $response->successful()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Product pushed to Google successfully!'
                ]);
            }
            
            $errorMessage = $response ? $response->body() : 'No response from Google API';
            return response()->json([
                'success' => false,
                'message' => 'Push failed: ' . $errorMessage
            ], 400);
            
        } catch(\Exception $e) {
            \Log::error('Push error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
    
    private function sendToGoogleAPI($product)
    {
        $accessToken = $this->getAccessToken();
        
        if(!$accessToken) {
            Log::error('Failed to get access token');
            return null;
        }
        
        $googleApiUrl = "https://www.googleapis.com/content/v2.1/{$this->merchantId}/products";
        
        $currencyCode = get_system_currency()->code;
        $originalPrice = $product->unit_price;
        $salePrice = home_discounted_base_price($product, false);
        $availability = $this->getAvailability($product);
        
        $data = [
            'offerId' => (string) $product->id,
            'title' => $this->clean($product->name),
            'description' => $this->clean(substr($product->description, 0, 5000)),
            'link' => url('/product/' . $product->slug),
            'imageLink' => uploaded_asset($product->thumbnail_img), 
            'contentLanguage' => 'en',
            'targetCountry' => 'US',
            'channel' => 'online',
            'availability' => $availability,
            'price' => [
                'value' => (string) number_format($originalPrice, 2, '.', ''),
                'currency' => $currencyCode
            ],
            'brand' => $this->clean($product->brand->name ?? 'Generic'),
            'condition' => 'new',
            'mpn' => (string) $product->id,
            'productTypes' => [$this->clean($product->main_category->name ?? 'Uncategorized')],
            'identifierExists' => true 
        ];
        
        // Add sale price if discount exists
        if ($salePrice !== null && $salePrice < $originalPrice) {
            $data['salePrice'] = [
                'value' => (string) number_format($salePrice, 2, '.', ''),
                'currency' => $currencyCode
            ];
            
            if ($product->discount_start_date && $product->discount_end_date) {
                $data['salePriceEffectiveDate'] = [
                    'start' => $product->discount_start_date . 'T00:00:00+0000',
                    'end' => $product->discount_end_date . 'T23:59:59+0000'
                ];
            }
        }
        
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json'
            ])->post($googleApiUrl, $data);
            
            // Log::info('Google API Response', [
            //     'product_id' => $product->id,
            //     'status' => $response->status(),
            //     'body' => $response->body()
            // ]);
            
            return $response;
            
        } catch(\Exception $e) {
            Log::error('HTTP Error: ' . $e->getMessage());
            return null;
        }
    }
    
    private function getAccessToken()
    {
        try {
            $client = new GoogleClient();
            $jsonPath = storage_path(config('google.account_json_path'));
            
            if(!file_exists($jsonPath)) {
                Log::error('Google Service Account JSON not found at: ' . $jsonPath);
                return null;
            }
            
            $client->setAuthConfig($jsonPath);
            $client->addScope('https://www.googleapis.com/auth/content');
            $token = $client->fetchAccessTokenWithAssertion();
            
            if(isset($token['access_token'])) {
                return $token['access_token'];
            }
            
            Log::error('Failed to get access token: ' . json_encode($token));
            return null;
            
        } catch(\Exception $e) {
            Log::error('Google Token Error: ' . $e->getMessage());
            return null;
        }
    }

    public function gmc_configuration(Request $request)
    {
        return view('backend.setup_configurations.google_configuration.gmc_configuration');
    }
}
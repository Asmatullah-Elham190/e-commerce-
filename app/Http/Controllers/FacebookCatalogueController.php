<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FacebookCatalogueController extends Controller
{
    private $catalogueId;
    private $accessToken;
    
    public function __construct()
    {
        $this->catalogueId = env('FACEBOOK_CATALOGUE_ID');
        $this->accessToken = env('FACEBOOK_ACCESS_TOKEN');
    }

    public function productFeedGenerate(Request $request)
    {
        $seller_type = 'all';
        $product_types = ['All Products', 'Inhouse Products', 'Seller Products'];
        $categories = Category::where('parent_id', 0)->with('childrenCategories')->get();
        
        return view('backend.catalogue.index', compact('seller_type', 'product_types', 'categories'));
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
            Log::error('No products selected for CSV export');
            return response()->json(['error' => 'No products selected'], 400);
        }
        
        $products = Product::with(['brand', 'main_category', 'stocks'])
            ->whereIn('id', $productIds)
            ->where('published', 1)
            ->where('approved', 1)
            ->where('draft', 0)
            ->get();
        
        
        if ($products->isEmpty()) {
            Log::error('No valid products found for CSV export');
            return response()->json(['error' => 'No valid products found'], 404);
        }
        
        return $this->generateCSV($products);
    }

    private function getProductStock($product)
    {
        if (!$product) {
            return 0;
        }
        
        if ($product->variant_product == 1) {
            return $product->stocks->sum('qty') ?? 0;
        } else {
            return $product->current_stock ?? 0;
        }
    }

    /**
     * Get availability status
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
            
            $xmlString .= '<title>' . $this->xmlEscape($siteName . ' - Facebook Catalog') . '</title>' . "\n";
            $xmlString .= '<link>' . $siteUrl . '</link>' . "\n";
            $xmlString .= '<description>' . $this->xmlEscape('Product feed for Meta Facebook Catalog') . '</description>' . "\n";
            
            $processedCount = 0;
            $stockDebug = [];
            
            foreach ($products as $product) {
                try {
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
               
                    $originalPrice = $product->unit_price;
                    $formattedOriginalPrice = number_format($originalPrice, 2, '.', '') . ' ' . $currency->code;
                    $xmlString .= '<g:price>' . $formattedOriginalPrice . '</g:price>' . "\n";
            
                    $salePrice = home_discounted_base_price($product, false);
                    if ($salePrice !== null && $salePrice < $originalPrice) {
                        $formattedSalePrice = number_format($salePrice, 2, '.', '') . ' ' . $currency->code;
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
                    
                    $xmlString .= '</item>' . "\n";
                    
                    $processedCount++;
                    
                } catch (\Exception $e) {
                    Log::error('Error processing product for Facebook XML', [
                        'product_id' => $product->id,
                        'error' => $e->getMessage()
                    ]);
                    continue;
                }
            }
            
            $xmlString .= '</channel>' . "\n";
            $xmlString .= '</rss>';
            
            $xmlString = $this->cleanInvalidXmlChars($xmlString);
            
            $response = response($xmlString, 200);
            $response->header('Content-Type', 'application/xml; charset=UTF-8');
            $response->header('Content-Disposition', 'attachment; filename="facebook_catalogue_' . date('Y-m-d_H-i-s') . '.xml"');
            $response->header('Cache-Control', 'no-cache, no-store, must-revalidate');
            $response->header('Pragma', 'no-cache');
            $response->header('Expires', '0');
            
            return $response;
            
        } catch (\Exception $e) {
            Log::error('Facebook XML generation failed', [
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
            
            $filename = 'facebook_catalogue_' . date('Y-m-d_H-i-s') . '.csv';
            $handle = fopen('php://temp', 'w+');
            
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
            
            $headers = [
                'id', 'title', 'description', 'availability', 'condition', 'price',
                'link', 'image_link', 'brand', 'google_product_category', 'fb_product_category',
                'quantity_to_sell_on_facebook', 'sale_price', 'sale_price_effective_date',
                'item_group_id', 'gender', 'color', 'size', 'age_group', 'material',
                'pattern', 'shipping', 'shipping_weight', 'gtin', 'mpn'
            ];
            
            fputcsv($handle, $headers);
            
            $currencyCode = get_system_currency()->code;
            $processedCount = 0;
            $stockDebug = [];
            
            foreach ($products as $product) {
                try {
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
                    
                    $originalPrice = $product->unit_price;
                    $formattedOriginalPrice = number_format($originalPrice, 2, '.', '') . ' ' . $currencyCode;
                    
                    $salePrice = home_discounted_base_price($product, false); 
                    $formattedSalePrice = ($salePrice !== null && $salePrice < $originalPrice) 
                        ? number_format($salePrice, 2, '.', '') . ' ' . $currencyCode 
                        : '';
                    
                    $saleDate = '';
                    if ($salePrice !== null && $salePrice < $originalPrice && $product->discount_start_date && $product->discount_end_date) {
                        $saleDate = $product->discount_start_date . 'T00:00:00+0000/' . $product->discount_end_date . 'T23:59:59+0000';
                    }
                    
                    $row = [
                        'id' => (string) $product->id,
                        'title' => $this->cleanCsvField($product->getTranslation('name')),
                        'description' => $this->cleanCsvField(substr(strip_tags($product->getTranslation('description')), 0, 5000)),
                        'availability' => $availability,
                        'condition' => 'new',
                        'price' => $formattedOriginalPrice,
                        'link' => route('product', $product->slug),
                        'image_link' => uploaded_asset($product->thumbnail_img),
                        'brand' => $this->cleanCsvField($product->brand->name ?? 'Generic'),
                        'google_product_category' => $this->getGoogleProductCategory($product->main_category->name ?? ''),
                        'fb_product_category' => $this->getFacebookProductCategory($product->main_category->name ?? ''),
                        'quantity_to_sell_on_facebook' => (int) max(0, $stock),
                        'sale_price' => $formattedSalePrice,
                        'sale_price_effective_date' => $saleDate,
                        'item_group_id' => '',
                        'gender' => '',
                        'color' => '',
                        'size' => '',
                        'age_group' => '',
                        'material' => '',
                        'pattern' => '',
                        'shipping' => '',
                        'shipping_weight' => '',
                        'gtin' => '',
                        'mpn' => $product->sku ?? (string) $product->id
                    ];
                    
                    fputcsv($handle, $row);
                    $processedCount++;
                    
                } catch (\Exception $e) {
                    Log::error('Error processing product for Facebook CSV', [
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
            Log::error('Facebook CSV generation failed', [
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

            $products = Product::with(['brand', 'main_category', 'stocks'])->where('published', 1)->where('approved', 1)->where('draft', 0)->where('facebook_catalogue', 1)->limit(5000)->get();

            if($request->product_ids) {
                $productIds = is_array($request->product_ids) ? $request->product_ids : explode(',', $request->product_ids);
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
            Log::error('exportAllProducts for Facebook failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Export failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function get_catalogue_products(Request $request)
    {
        $col_name = null;
        $query = null;
        $sort_search = null;
        
        $products = Product::where('auction_product', 0)
            ->where('wholesale_product', 0)
            ->where('draft', 0)
            ->where('facebook_catalogue', 1)
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

        $products = $products->orderBy('created_at', 'desc')->paginate(15);
        
        $type = $request->seller_type;
        $view = view('backend.catalogue.products_table',
            compact('products', 'type', 'col_name', 'query', 'sort_search')
        )->render();

        return response()->json(['html' => $view]);
    }

    public function productUpdateToCatalog(Request $request)
    {
        $productIds = $request->id ?? $request->ids;
        
        if (!$productIds && $request->facebook_catalogue) {
            return response()->json([
                'success' => false, 
                'message' => translate('No products selected')
            ], 400);
        }
        
        if (is_string($productIds)) {
            $productIds = explode(',', $productIds);
        }
        
        if (!isset($request->facebook_catalogue)) {
            Product::where('facebook_catalogue', 1)->update(['facebook_catalogue' => 0]);
            if (!empty($productIds)) {
                Product::whereIn('id', $productIds)->update(['facebook_catalogue' => 1]);
            }
        } else {
            Product::whereIn('id', $productIds)->update(['facebook_catalogue' => $request->facebook_catalogue]);
        }

        return response()->json([
            'success' => true,
            'message' => translate('Facebook Catalog products updated successfully')
        ]);
    }

    public function catalogue_products_search(Request $request)
    {
        $facebook_catalogue = 1;
        $products = Product::where('auction_product', 0)
            ->where('wholesale_product', 0)
            ->where('draft', 0)
            ->where('published', 1)
            ->where('approved', 1);
        
        if($request->search_key != null){
            $products = $products->where('name', 'like', '%' . $request->search_key . '%');
        }
        
        if($request->category != null){
            $category = Category::with('childrenCategories')->find($request->category);
            $products = $category->products();
        }
        
        $products = $products->get();
        $single_select = $request->single_select ?? 0;
        
        return view('partials.product.products_search', compact('products', 'single_select', 'facebook_catalogue'));
    }

    /**
     * Public XML Feed - FIXED with proper stock handling
     */
    public function xmlFeed()
    {
        $products = Product::with(['brand', 'main_category', 'stocks'])
            ->where('auction_product', 0)
            ->where('wholesale_product', 0)
            ->where('draft', 0)
            ->where('facebook_catalogue', 1)
            ->where('published', 1)
            ->where('approved', 1)
            ->get();
        
        $currency = get_system_currency();
        $currencyCode = $currency->code;
        
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<rss version="2.0" xmlns:g="http://base.google.com/ns/1.0">';
        $xml .= '<channel>';
        $xml .= '<title>' . config('app.name') . ' - Facebook Catalog</title>';
        $xml .= '<link>' . url('/') . '</link>';
        $xml .= '<description>Product Feed for Meta Facebook Catalog</description>';
        
        $stockDebug = [];
        
        foreach($products as $product) {
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
            
            $xml .= '<item>';
            $xml .= '<g:id>' . $product->id . '</g:id>';
            $xml .= '<title>' . $this->clean($product->name) . '</title>';
            $xml .= '<description>' . $this->clean(substr($product->description, 0, 5000)) . '</description>';
            $xml .= '<g:link>' . url('/product/' . $product->slug) . '</g:link>';
            $xml .= '<g:image_link>' . uploaded_asset($product->thumbnail_img) . '</g:image_link>';
            
            $originalPrice = $product->unit_price;
            $xml .= '<g:price>' . number_format($originalPrice, 2, '.', '') . ' ' . $currencyCode . '</g:price>';
            
            $salePrice = home_discounted_base_price($product, false);
            if ($salePrice !== null && $salePrice < $originalPrice) {
                $xml .= '<g:sale_price>' . number_format($salePrice, 2, '.', '') . ' ' . $currencyCode . '</g:sale_price>';
                
                if ($product->discount_start_date && $product->discount_end_date) {
                    $saleDate = $product->discount_start_date . 'T00:00:00+0000/' . $product->discount_end_date . 'T23:59:59+0000';
                    $xml .= '<g:sale_price_effective_date>' . $saleDate . '</g:sale_price_effective_date>';
                }
            }
            $xml .= '<g:availability>' . $availability . '</g:availability>';
            $xml .= '<g:brand>' . ($product->brand->name ?? 'Generic') . '</g:brand>';
            $xml .= '<g:condition>new</g:condition>';
            $xml .= '<g:mpn>' . ($product->sku ?? $product->id) . '</g:mpn>';
            $xml .= '<g:product_type>' . $this->clean($product->main_category->name ?? 'Uncategorized') . '</g:product_type>';
            $xml .= '</item>';
        }
        
        $xml .= '</channel></rss>';
        
        $response = response($xml, 200);
        $response->header('Content-Type', 'application/xml; charset=UTF-8');
        $response->header('Cache-Control', 'no-cache, no-store, must-revalidate');
        $response->header('Pragma', 'no-cache');
        $response->header('Expires', '0');
        
        return $response;
    }

    public function manualPushToCatalog(Request $request)
    {
        try {
            $products = Product::with(['stocks'])
                ->where('auction_product', 0)
                ->where('wholesale_product', 0)
                ->where('draft', 0)
                ->where('facebook_catalogue', 1)
                ->where('published', 1)
                ->where('approved', 1)
                ->get();

            if($request->id) {
                $products = $products->whereIn('id', $request->id);
            }
            
            $successCount = 0;
            $failedCount = 0;
            $failedProducts = [];
            
            foreach($products as $product) {
                $response = $this->sendToFacebookAPI($product);
                
                if($response && isset($response['success']) && $response['success']) {
                    $successCount++;
                } else {
                    $failedCount++;
                    $failedProducts[] = $product->id;
                    //Log::error('Facebook Catalog push failed for product: ' . $product->id);
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => "Sync complete! Success: {$successCount}, Failed: {$failedCount}",
                'failed_products' => $failedProducts
            ]);
            
        } catch(\Exception $e) {
            Log::error('Facebook Catalog sync error: ' . $e->getMessage());
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
            $response = $this->sendToFacebookAPI($product);
            
            if($response && isset($response['success']) && $response['success']) {
                return response()->json([
                    'success' => true,
                    'message' => 'Product pushed to Facebook Catalog successfully!'
                ]);
            }
            
            $errorMessage = $response['error'] ?? 'No response from Facebook API';
            return response()->json([
                'success' => false,
                'message' => 'Push failed: ' . $errorMessage
            ], 400);
            
        } catch(\Exception $e) {
            \Log::error('Push to Facebook error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    private function sendToFacebookAPI($product)
    {
        if(!$this->catalogueId || !$this->accessToken) {
            Log::error('Facebook Catalog ID or Access Token missing');
            return ['success' => false, 'error' => 'Catalog ID or Access Token missing'];
        }
        
        $url = "https://graph.facebook.com/v18.0/{$this->catalogueId}/products";
        
        $originalPrice = $product->unit_price;
        $currencyCode = get_system_currency()->code;
        
        $salePrice = home_discounted_base_price($product, false);
        $stock = $this->getProductStock($product);
        $availability = $this->getAvailability($product);
        $domain = env('APP_URL', url('/'));
        $productUrl = $domain . '/product/' . $product->slug;
        
        $data = [
            'retailer_id'  => (string) $product->id,
            'name'         => $this->clean($product->name),
            'description'  => $this->clean(substr($product->description, 0, 5000)),
            'availability' => $availability,
            'price'        => (int) round($originalPrice * 100),
            'currency'     => $currencyCode,
            'url'          => $productUrl,
            'image_url'    => uploaded_asset($product->thumbnail_img),
            'brand'        => $product->brand->name ?? 'Generic',
            'condition'    => 'new',
            'inventory'    => (int) max(0, $stock),
            'access_token' => $this->accessToken
        ];
        
        if ($salePrice !== null && $salePrice < $originalPrice) {
            $data['sale_price'] = (int) round($salePrice * 100);
            if ($product->discount_start_date && $product->discount_end_date) {
                $data['sale_price_start_date'] = $product->discount_start_date . 'T00:00:00+0000';
                $data['sale_price_end_date'] = $product->discount_end_date . 'T23:59:59+0000';
            }
        }
        
        try {
            $response = Http::asForm()->post($url, $data);
            $result = $response->json();
            
            if($response->successful() && isset($result['id'])) {
                return ['success' => true, 'product_id' => $result['id']];
            }
            
            $errorMsg = $result['error']['message'] ?? 'Unknown error';
            return ['success' => false, 'error' => $errorMsg];
            
        } catch(\Exception $e) {
            Log::error('Facebook API Error: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
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

    private function getFacebookProductCategory($category)
    {
        $mapping = [
            'Electronics' => 'Electronics',
            'Mobiles & Tabs' => 'Electronics',
            'Computers & Accessories' => 'Electronics',
            'Womens World' => 'Clothing & Accessories > Clothing',
            'Jewellery & Watches' => 'Clothing & Accessories > Jewelry & Watches',
            'Kids & Toys' => 'Toys & Games',
            'Home & Garden' => 'Home & Garden',
            'Automobiles' => 'Vehicles & Parts',
            'Pet Supplies' => 'Animals > Pet Supplies'
        ];
        
        return $mapping[$category] ?? 'Other';
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

    private function clean($string)
    {
        return htmlspecialchars(strip_tags($string), ENT_QUOTES, 'UTF-8');
    }

    public function catalogue_configuration(Request $request)
    {
        return view('backend.setup_configurations.facebook_configuration.catalogue_configuration');
    }
}
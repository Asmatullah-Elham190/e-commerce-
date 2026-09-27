<?php

namespace App\Services;

use App\Models\Currency;
use App\Models\Product;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FacebookConversionService
{
    /**
     * Get Facebook API configuration
     */
    private function getBaseConfig()
    {
        $pixelId = env('FACEBOOK_PIXEL_ID');
        $accessToken = env('FACEBOOK_PIXEL_API');

        return [
            'url' => "https://graph.facebook.com/v18.0/{$pixelId}/events",
            'access_token' => $accessToken
        ];
    }


    private function buildUserData()
    {
        $user = auth()->user();

        $userData = [
            "client_ip_address" => request()->ip(),
            "client_user_agent" => request()->userAgent(),
            "fbp" => request()->cookie('_fbp'),
            "fbc" => request()->cookie('_fbc'),
        ];

        if ($user) {
            if ($user->email) {
                $userData["em"] = hash('sha256', strtolower(trim($user->email)));
            }
            
            if ($user->phone) {
                $phone = preg_replace('/[^0-9]/', '', $user->phone);
                if (strlen($phone) === 10) {
                    $phone = '1' . $phone;
                }
                $userData["ph"] = hash('sha256', $phone);
            }
            
            if ($user->name) {
                $nameParts = explode(' ', trim($user->name), 2);
                $firstName = $nameParts[0] ?? '';
                $lastName = $nameParts[1] ?? '';
                
                if ($firstName) {
                    $userData["fn"] = hash('sha256', strtolower(trim($firstName)));
                }
                if ($lastName) {
                    $userData["ln"] = hash('sha256', strtolower(trim($lastName)));
                }
            }
            
            $userData["external_id"] = hash('sha256', (string) $user->id);
        
            if ($user->postal_code) {
                $userData["zp"] = hash('sha256', strtolower(trim($user->postal_code)));
            }
            
            if ($user->city) {
                $userData["ct"] = hash('sha256', strtolower(trim($user->city)));
            }
            
            if ($user->state) {
                $userData["st"] = hash('sha256', strtolower(trim($user->state)));
            }
            
            if ($user->country) {
                $userData["country"] = hash('sha256', strtolower(trim($user->country)));
            }
        }

        return array_filter($userData, function($value) {
            return !is_null($value) && $value !== '' && $value !== '0';
        });
    }

    private function sendToFacebook($eventName, $customData = [], $eventId = null)
    {
        $config = $this->getBaseConfig();
        
        // Validate configuration
        if (empty($config['access_token']) || empty(env('FACEBOOK_PIXEL_ID'))) {
            Log::warning('Facebook CAPI: Missing configuration', [
                'pixel_id' => env('FACEBOOK_PIXEL_ID') ? 'set' : 'missing',
                'access_token' => $config['access_token'] ? 'set' : 'missing'
            ]);
            return null;
        }

        // Ensure event_id is always set
        $eventId = $eventId ?? uniqid('fb_' . $eventName . '_', true);

        // Ensure proper data types for Facebook
        if (isset($customData['value'])) {
            $customData['value'] = (float) $customData['value'];
        }
        if (isset($customData['num_items'])) {
            $customData['num_items'] = (int) $customData['num_items'];
        }
        if (isset($customData['content_ids']) && is_array($customData['content_ids'])) {
            $customData['content_ids'] = array_map('strval', $customData['content_ids']);
        }
        if (isset($customData['currency'])) {
            $customData['currency'] = (string) $customData['currency'];
        }

        $payload = [
            "data" => [
                [
                    "event_name" => $eventName,
                    "event_time" => time(),
                    "event_id" => $eventId,
                    "action_source" => "website",
                    "event_source_url" => url()->current(),
                    "user_data" => $this->buildUserData(),
                    "custom_data" => $customData
                ]
            ]
        ];

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($config['url'] . '?access_token=' . $config['access_token'], $payload);

            // Log response for debugging
            if ($response->status() !== 200) {
                Log::error('Facebook CAPI Error', [
                    'status' => $response->status(),
                    'response' => $response->json(),
                    'event' => $eventName,
                    'event_id' => $eventId
                ]);
            }

            return $response;

        } catch (\Exception $e) {
            Log::error('Facebook CAPI Exception', [
                'message' => $e->getMessage(),
                'event' => $eventName,
                'event_id' => $eventId,
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    public function sendPurchase($combinedOrder, $eventId = null)
    {
        $contentIds = [];
        $value = 0;
        $contentNames = [];
        $contentCategories = [];
        $contentPrices = [];

        foreach ($combinedOrder->orders as $order) {
            foreach ($order->orderDetails as $detail) {
                $contentIds[] = (string) $detail->product_id;
                $value += $detail->price * $detail->quantity;
                
                $product = $detail->product;
                if ($product) {
                    $contentNames[] = $product->getTranslation('name');
                    $contentCategories[] = $product->main_category?->getTranslation('name') ?? 'Uncategorized';
                    $contentPrices[] = (float) $detail->price;
                }
            }
        }

        if (!$eventId) {
            $eventId = 'purchase_' . $combinedOrder->id . '_' . time();
        }

        return $this->sendToFacebook("Purchase", [
            "currency" => Currency::findOrFail(get_setting('system_default_currency'))->code,
            "value" => (float) $combinedOrder->grand_total,
            "content_ids" => $contentIds,
            "content_type" => "product",
            "num_items" => count($contentIds),
            "order_id" => (string) $combinedOrder->id,
            "contents" => array_map(function($id, $name, $cat, $price) {
                return [
                    'id' => $id,
                    'name' => $name,
                    'category' => $cat,
                    'price' => $price
                ];
            }, $contentIds, $contentNames, $contentCategories, $contentPrices)
        ], $eventId);
    }

    public function sendAddToCart($product, $price, $eventId = null, $quantity = 1)
    {
        if (!$eventId) {
            $eventId = 'atc_' . $product->id . '_' . time() . '_' . uniqid();
        }

        return $this->sendToFacebook("AddToCart", [
            "currency" => Currency::findOrFail(get_setting('system_default_currency'))->code,
            "value" => (float) ($price * $quantity),
            "content_ids" => [(string) $product->id],
            "content_type" => "product",
            "content_name" => $product->getTranslation('name'),
            "content_category" => $product->main_category?->getTranslation('name') ?? 'Uncategorized',
            "content_brand" => $product->brand?->name ?? 'No Brand',
            "num_items" => (int) $quantity,
            "contents" => [
                [
                    'id' => (string) $product->id,
                    'name' => $product->getTranslation('name'),
                    'category' => $product->main_category?->getTranslation('name') ?? 'Uncategorized',
                    'brand' => $product->brand?->name ?? 'No Brand',
                    'price' => (float) $price,
                    'quantity' => (int) $quantity
                ]
            ]
        ], $eventId);
    }
    public function sendAddToWishlist($productId, $eventId = null)
    {
        if (!$eventId) {
            $eventId = 'wishlist_' . $productId . '_' . time();
        }

        $customData = [
            "content_ids" => [(string) $productId],
            "content_type" => "product"
        ];

        $product= Product::find($productId);

        if ($product) {
            $customData["content_name"] = $product->getTranslation('name');
            $customData["content_category"] = $product->main_category?->getTranslation('name') ?? 'Uncategorized';
            $customData["content_brand"] = $product->brand?->name ?? 'No Brand';
            $customData["value"] = (float) home_discounted_price($product);
            $customData["currency"] = Currency::findOrFail(get_setting('system_default_currency'))->code;
        }

        return $this->sendToFacebook("AddToWishlist", $customData, $eventId);
    }

    public function sendViewContent($product, $eventId = null)
    {
        if (!$eventId) {
            $eventId = 'view_' . $product->id . '_' . time();
        }

        return $this->sendToFacebook("ViewContent", [
            "content_ids" => [(string) $product->id],
            "content_name" => $product->getTranslation('name'),
            "content_type" => "product",
            "value" => (float) home_discounted_price($product),
            "currency" => Currency::findOrFail(get_setting('system_default_currency'))->code,
            "content_category" => $product->main_category?->getTranslation('name') ?? 'Uncategorized',
            "content_brand" => $product->brand?->name ?? 'No Brand',
            "contents" => [
                [
                    'id' => (string) $product->id,
                    'name' => $product->getTranslation('name'),
                    'category' => $product->main_category?->getTranslation('name') ?? 'Uncategorized',
                    'brand' => $product->brand?->name ?? 'No Brand',
                    'price' => (float) home_discounted_price($product)
                ]
            ]
        ], $eventId);
    }


    public function sendPageView($eventId = null)
    {
        if (!$eventId) {
            $eventId = 'pageview_' . time() . '_' . uniqid();
        }
        
        return $this->sendToFacebook("PageView", [], $eventId);
    }

    public function sendInitiateCheckout($cartData, $eventId = null)
    {
        $contentIds = [];
        $value = 0;
        $contentNames = [];
        $contentCategories = [];
        $contentPrices = [];
        $contentQuantities = [];

        // Handle different types of cart data
        if (isset($cartData['carts']) && is_iterable($cartData['carts'])) {
            foreach ($cartData['carts'] as $cart) {
                $contentIds[] = (string) $cart->product_id;
                $value += $cart->price * $cart->quantity;
                $contentQuantities[] = (int) $cart->quantity;
                $contentPrices[] = (float) $cart->price;
                
                // Get product details if available
                if (isset($cart->product)) {
                    $contentNames[] = $cart->product->getTranslation('name');
                    $contentCategories[] = $cart->product->main_category?->getTranslation('name') ?? 'Uncategorized';
                }
            }
        } else if (isset($cartData['product_ids']) && is_array($cartData['product_ids'])) {
            $contentIds = $cartData['product_ids'];
            $value = $cartData['total'] ?? 0;
        }
        
        if (!$eventId) {
            $userId = auth()->id() ?? session()->get('temp_user_id') ?? 'guest';
            $eventId = 'initiate_checkout_' . $userId . '_' . time() . '_' . uniqid();
        }

        $contents = [];
        foreach ($contentIds as $key => $id) {
            $contents[] = [
                'id' => $id,
                'name' => $contentNames[$key] ?? 'Product',
                'category' => $contentCategories[$key] ?? 'Uncategorized',
                'price' => $contentPrices[$key] ?? 0,
                'quantity' => $contentQuantities[$key] ?? 1
            ];
        }
        
        return $this->sendToFacebook("InitiateCheckout", [
            "currency" => Currency::findOrFail(get_setting('system_default_currency'))->code,
            "value" => (float) $value,
            "content_ids" => $contentIds,
            "content_type" => "product",
            "num_items" => count($contentIds),
            "contents" => $contents
        ], $eventId);
    }

    /**
     * Send CompleteRegistration Event
     */
    public function sendCompleteRegistration($userId = null, $eventId = null)
    {
        if (!$eventId) {
            $userId = $userId ?? time();
            $eventId = 'registration_' . $userId . '_' . time();
        }
        
        return $this->sendToFacebook("CompleteRegistration", [
            "currency" => Currency::findOrFail(get_setting('system_default_currency'))->code,
        ], $eventId);
    }

    public function sendSearch($searchQuery, $eventId = null)
    {
        if (!$eventId) {
            $eventId = 'search_' . md5($searchQuery) . '_' . time();
        }

        return $this->sendToFacebook("Search", [
            "search_string" => (string) $searchQuery,
            "currency" => Currency::findOrFail(get_setting('system_default_currency'))->code,
        ], $eventId);
    }

    public function sendCustomEvent($eventName, $customData = [], $eventId = null)
    {
        if (!$eventId) {
            $eventId = $eventName . '_' . time() . '_' . uniqid();
        }

        return $this->sendToFacebook($eventName, $customData, $eventId);
    }

}
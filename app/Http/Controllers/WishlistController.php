<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Auth;
use App\Models\Wishlist;
use App\Services\FacebookConversionService;

class WishlistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $wishlists = get_wishlists()->paginate(15);
        return view('frontend.user.view_wishlist', compact('wishlists'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(Auth::check()){
            $wishlist = Wishlist::where('user_id', Auth::user()->id)->where('product_id', $request->id)->first();
            $ga4Data = null;
            if($wishlist == null){
                $wishlist = new Wishlist;
                $wishlist->user_id = Auth::user()->id;
                $wishlist->product_id = $request->id;
                $wishlist->save();
                $product = Product::find($request->id);
                $ga4Data = [
                    'event' => 'add_to_wishlist',
                    'ecommerce' => [
                        'currency' => get_system_currency()->code,
                        'value' => (float) ($product->unit_price - ($product->discount ?? 0)),
                        'items' => [[
                            'item_id' => (string) $product->id,
                            'item_name' => $product->getTranslation('name'),
                            'item_category' => optional($product->main_category)->name ?? 'General',
                            'price' => (float) $product->unit_price,
                            'discount' => (float) ($product->discount ?? 0),
                        ]],
                    ],
                ];
            
                if(get_setting('facebook_pixel_capi') == 1){
                    $eventId = 'wishlist_' . $wishlist->id . '_' . time();
                    $fb = new FacebookConversionService();
                    $fb->sendAddToWishlist($wishlist->product_id, $eventId);
                }
            }
            $responseData = [
                'ga4Data' => $ga4Data
            ];
            
            if(get_setting('header_element') == 5){
                $responseData['html'] = view('frontend.partials.wishlistText')->render();
            } else {
                $responseData['html'] = view('frontend.partials.wishlist')->render();
            }
            
            return response()->json($responseData);
        }
        return 0;
    }

    public function remove(Request $request)
    {
        $wishlist = Wishlist::findOrFail($request->id);
        if($wishlist!=null){
            if(Wishlist::destroy($request->id)){
                if(get_setting('header_element') ==5){
                    return view('frontend.partials.wishlistText');
                }else{
                    return view('frontend.partials.wishlist');
                }
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

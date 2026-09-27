<?php

namespace App\Http\Controllers\Seller;

use App\Models\BusinessSetting;
use App\Models\Category;
use App\Models\Country;
use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\State;
use App\Models\User;
use App\Notifications\ShopVerificationNotification;
use Auth;
use Illuminate\Support\Facades\Notification;

class ShopController extends Controller
{
    public function index()
    {
        $shop = Auth::user()->shop;
        return view('seller.shop', compact('shop'));
    }

    public function shop_design()
    {
        $shop = Auth::user()->shop;
        return view('seller.shop_design', compact('shop'));
    }

    public function update(Request $request)
    {
        $shop = Shop::find($request->shop_id);

        if ($request->has('name') && $request->has('address')) {
            if ($request->has('shipping_cost')) {
                $shop->shipping_cost = $request->shipping_cost;
            }

            $shop->name             = $request->name;
            $shop->address          = $request->address;
            $shop->phone            = $request->phone;
            $shop->slug             = preg_replace('/\s+/', '-', $request->name) . '-' . $shop->id;
            $shop->meta_title       = $request->meta_title;
            $shop->meta_description = $request->meta_description;
            $shop->logo             = $request->logo;
        }

        if ($request->has('navbar_bg_color')) {
            $shop->navbar_bg_color             = $request->navbar_bg_color;
        }

        if ($request->has('navbar_text_color')) {
            $shop->navbar_text_color             = $request->navbar_text_color;
        }

        if ($request->has('delivery_pickup_longitude') && $request->has('delivery_pickup_latitude'))
        {
            $shop->delivery_pickup_longitude    = $request->delivery_pickup_longitude;
            $shop->delivery_pickup_latitude     = $request->delivery_pickup_latitude;
        } 
        elseif ($request->has('facebook') || $request->has('google') || $request->has('twitter') ||$request->has('youtube') || $request->has('instagram'))
        {
            $shop->facebook = $request->facebook;
            $shop->instagram = $request->instagram;
            $shop->google = $request->google;
            $shop->twitter = $request->twitter;
            $shop->youtube = $request->youtube;
        }

        $business_info = json_decode($shop->business_info, true) ?? [];
        if ($request->has('certificate_number')) {

            $business_info['certificate_number'] = $request->certificate_number;
            // Replace certificate file
            if ($request->hasFile('certificate')) {
                    if (!empty($business_info['certificate']) && file_exists(public_path($business_info['certificate']))) {
                        unlink(public_path($business_info['certificate']));
                    }
                    $business_info['certificate'] =
                    $request->file('certificate')->store('uploads/verification_form');
                }
            $business_info['country'] = Country::find($request->country_id)?->name;
            $business_info['state']   = State::find($request->state_id)?->name;
        }

        if ($request->has('seller_photo') || $request->has('live_selfie') || $request->has('id_card')) {

           
            // Replace seller_photo file
            if ($request->hasFile('seller_photo')) {
                if (!empty($business_info['seller_photo']) && file_exists(public_path($business_info['seller_photo']))) {
                    unlink(public_path($business_info['seller_photo']));
                }
                $business_info['seller_photo'] =
                $request->file('seller_photo')->store('uploads/verification_form');
            }

            if ($request->hasFile('id_card')) {
                if (!empty($business_info['id_card']) && file_exists(public_path($business_info['id_card']))) {
                    unlink(public_path($business_info['id_card']));
                }
                $business_info['id_card'] =
                $request->file('id_card')->store('uploads/verification_form');
            }

            if ($request->live_selfie) {
                if (!empty($business_info['seller_selfie']) &&
                    file_exists(public_path($business_info['seller_selfie']))) {
                    unlink(public_path($business_info['seller_selfie']));
                }
                $image = $request->live_selfie;  // your base64 encoded
                $image = str_replace('data:image/png;base64,', '', $image);
                $image = str_replace(' ', '+', $image);
                $imageName = 'uploads/verification_form/seller_selfie_' . time() . '.png';
                \File::put(public_path($imageName), base64_decode($image));
                $business_info['seller_selfie'] = $imageName;
            }
        }

        if (addon_is_activated('gst_system') && $request->has('gstin_number')) {
            $business_info['gstin'] = $request->gstin_number;
            if ($request->hasFile('gstin_certificate')) {
                if (!empty($business_info['gstin_certificate']) &&
                    file_exists(public_path($business_info['gstin_certificate']))) {
                    unlink(public_path($business_info['gstin_certificate']));
                }
                $business_info['gstin_certificate'] =
                $request->file('gstin_certificate')->store('uploads/verification_form');
            }
        }
        $shop->business_info = json_encode($business_info);


        if ($shop->save()) {
            flash(translate('Your Shop has been updated successfully!'))->success();
            return back();
        }

        flash(translate('Sorry! Something went wrong.'))->error();
        return back();
    }

    public function bannerUpdate(Request $request){
        $shop = Shop::find($request->shop_id);
        $shop->top_banner_image     = $request->top_banner_image;
        $shop->top_banner_link      = $request->top_banner_link;
        $shop->slider_images        = $request->slider_images;
        $shop->slider_links         = $request->slider_links;
        $shop->banner_full_width_1_images   = $request->banner_full_width_1_images;
        $shop->banner_full_width_1_links    = $request->banner_full_width_1_links;
        $shop->banners_half_width_images    = $request->banners_half_width_images;
        $shop->banners_half_width_links     = $request->banners_half_width_links;
        $shop->banner_full_width_2_images   = $request->banner_full_width_2_images;
        $shop->banner_full_width_2_links    = $request->banner_full_width_2_links;
        if ($shop->save()) {
            flash(translate('Your Shop banners has been updated successfully!'))->success();
            return back();
        }

        flash(translate('Sorry! Something went wrong.'))->error();
        return back();
    }

    public function verify_form()
    {
        if (Auth::user()->shop->verification_info == null) {
            $shop = Auth::user()->shop;
            return view('seller.verify_form', compact('shop'));
        } else {
            flash(translate('Sorry! You have sent verification request already.'))->error();
            return back();
        }
    }

    public function verify_form_store(Request $request)
    {
        $data = array();
        $i = 0;
        foreach (json_decode(BusinessSetting::where('type', 'verification_form')->first()->value) as $key => $element) {
            $item = array();
            if ($element->type == 'text') {
                $item['type'] = 'text';
                $item['label'] = $element->label;
                $item['value'] = $request['element_' . $i];
            } elseif ($element->type == 'select' || $element->type == 'radio') {
                $item['type'] = 'select';
                $item['label'] = $element->label;
                $item['value'] = $request['element_' . $i];
            } elseif ($element->type == 'multi_select') {
                $item['type'] = 'multi_select';
                $item['label'] = $element->label;
                $item['value'] = json_encode($request['element_' . $i]);
            } elseif ($element->type == 'file') {
                $item['type'] = 'file';
                $item['label'] = $element->label;
                $item['value'] = $request['element_' . $i]->store('uploads/verification_form');
            }
            array_push($data, $item);
            $i++;
        }
        $shop = Auth::user()->shop;
        $shop->verification_info = json_encode($data);
        if ($shop->save()) {
            $users = User::findMany([User::where('user_type', 'admin')->first()->id]);
            $data = array();
            $data['shop'] = $shop;
            $data['status'] = 'submitted';
            $data['notification_type_id'] = get_notification_type('shop_verify_request_submitted', 'type')->id;
            Notification::send($users, new ShopVerificationNotification($data));
            
            flash(translate('Your shop verification request has been submitted successfully!'))->success();
            return redirect()->route('seller.dashboard');
        }

        flash(translate('Sorry! Something went wrong.'))->error();
        return back();
    }

    public function show()
    {
    }

    public function categoriesWiseCommission(Request $request){
        $sort_search =null;
        $categories = Category::orderBy('order_level', 'desc');
        if ($request->has('search')){
            $sort_search = $request->search;
            $categories = $categories->where('name', 'like', '%'.$sort_search.'%');
        }
        $categories = $categories->paginate(15);
        return view('seller.categoryWise_commission', compact('categories'))->render();
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\SellerRegistrationRequest;
use App\Models\AffiliateConfig;
use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\User;
use App\Models\BusinessSetting;
use App\Models\RegistrationVerificationCode;
use App\Models\SmsTemplate;
use App\Services\SendSmsService;
use Auth;
use Hash;
use App\Utility\EmailUtility;
use Illuminate\Support\Facades\Notification;
use App\Http\Controllers\OTPVerificationController;
use Cookie;
use Illuminate\Support\Facades\Session;

class ShopController extends Controller
{

    public function __construct()
    {
        $this->middleware('user', ['only' => ['index']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shop = Auth::user()->shop;
        return view('seller.shop', compact('shop'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // check if the seller verification enable
        if(get_setting('seller_registration_verify') === '1' || addon_is_activated('portfolio_system') == 1 ){
            abort(404);
        }


        // default registration page
        $email = null;
        $phone = null;
        if (Auth::check()) {
            if ((Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'customer')) {
                flash(translate('Admin or Customer cannot be a seller'))->error();
                return back();
            }
            if (Auth::user()->user_type == 'seller') {
                flash(translate('This user already a seller'))->error();
                return back();
            }
        } else {
            
            return view('auth.'.get_setting('authentication_layout_select').'.seller_registration', compact('email','phone'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SellerRegistrationRequest $request)
    {
        $cleanPhone = preg_replace('/\D+/', '', $request->phone);
        $user = new User;
        $user->name = $request->name. ($request->filled('l_name') ? ' ' . $request->l_name : '');
        $user->email = $request->email;
        $user->phone = ($request->filled('country_code') ? ' ' . $request->country_code : '').$cleanPhone;
        $user->user_type = "seller";
        $user->password = Hash::make($request->password);
        $user->email_verified_at = date('Y-m-d H:m:s');

        if ($user->save()) {
            $shop = new Shop;
            $shop->user_id = $user->id;
            $shop->name = $request->shop_name;
            $shop->address = $request->address;
            if(addon_is_activated('portfolio_system')){
                $shop->registration_approval= 1;
                $shop->verification_status= 0;
                $shop->phone =$user->phone = ($request->filled('country_code') ? ' ' . $request->country_code : '').$cleanPhone;
            }else{
                $shop->registration_approval= 0;
            }
            $shop->registration_approval= 0;
            $shop->slug = preg_replace('/\s+/', '-', str_replace("/", " ", $request->shop_name));
            $shop->save();

            //auth()->login($user, true);
            // if (BusinessSetting::where('type', 'email_verification')->first()->value == 0) {
            //     $user->email_verified_at = date('Y-m-d H:m:s');
            //     $user->save();
            // } else {
            //     try {
            //         EmailUtility::email_verification($user, 'seller');
            //     } catch (\Throwable $th) {
            //         $shop->delete();
            //         $user->delete();
            //         flash(translate('Seller registration failed. Please try again later.'))->error();
            //         return back();
            //     }
            // }

            // Account Opening Email to Seller
            if ((get_email_template_data('registration_email_to_seller', 'status') == 1)) {
                try {
                    EmailUtility::selelr_registration_email('registration_email_to_seller', $user, null);
                } catch (\Exception $e) {}
            }

            // Seller Account Opening Email to Admin
            if ((get_email_template_data('seller_reg_email_to_admin', 'status') == 1)) {
                try {
                    EmailUtility::selelr_registration_email('seller_reg_email_to_admin', $user, null);
                } catch (\Exception $e) {}
            }

            if(addon_is_activated('portfolio_system')){
                auth()->login($user, true);
                return redirect()->route('dashboard');
            }

            flash(translate('Your Shop has been created successfully! Your seller account is under review. We will notify you once approved. '))->success();
            return redirect()->route('home');
        }

        flash(translate('Sorry! Something went wrong.'))->error();
        return back();
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

    public function destroy($id)
    {
        //
    }

    public function verifyRegEmailorPhone(){
        $type = 'seller';
        if (Auth::check()) {
            if ((Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'customer')) {
                flash(translate('Admin or Customer cannot be a seller'))->error();
                return back();
            }
            if (Auth::user()->user_type == 'seller') {
                flash(translate('This user already a seller'))->error();
                return back();
            }
        } else {
            return view('auth.'.get_setting('authentication_layout_select').'.reg_verification', compact('type'));
        }
    }

    public function sendRegVerificationCode(Request $request){
        $email = $request->email ?? null;
        $phone = $request->phone != null ? '+'.$request->country_code.$request->phone : null;

        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            if(User::where('email', $email)->first() != null){
                flash(translate('Email already exists.'))->error();
                return back();
            }
        }
        elseif (User::where('phone', $phone)->first() != null) {
            flash(translate('Phone already exists.'))->error();
            return back();
        }

        $verificationCode = rand(100000, 999999);
        $sellerVerification = RegistrationVerificationCode::updateOrCreate(
            ['email' => $email, 'phone' => $phone], 
            ['code' => $verificationCode]
        );
        $success = 1;

        if ($email) {
            try {
                EmailUtility::email_verification_for_registration_seller('email_verification_for_registration_seller', $email, $verificationCode);
            } catch (\Exception $e) {
                $success = 0;
            }
        }
        else {
            if (addon_is_activated('otp_system')){
                $sms_template   = SmsTemplate::where('identifier', 'phone_number_verification')->first();
                $sms_body       = $sms_template->sms_body;
                $sms_body       = str_replace('[[code]]', $verificationCode, $sms_body);
                $sms_body       = str_replace('[[site_name]]', env('APP_NAME'), $sms_body);
                $template_id    = $sms_template->template_id;
                
                (new SendSmsService())->sendSMS($phone, env('APP_NAME'), $sms_body, $template_id);
            }
        }

        if($success){
            return redirect()->route('shop-reg.verify_code', encrypt($sellerVerification->id));
        }
        else {
            flash(translate('Something went wrong!'))->error();
            return back();
        }
    }

    public function regVerifyCode($id){
        // $sellerVerification = $id;
        $sellerVerification = RegistrationVerificationCode::whereId(decrypt($id))->first();
        return view('auth.'.get_setting('authentication_layout_select').'.seller_verify_confirmation', compact('sellerVerification'));
    }

    public function regVerifyCodeConfirmation(Request $request){
        $email = isset($request->email) ? $request->email : null;
        $phone = isset($request->phone) ? $request->phone  : null;

        $sellerVerification = RegistrationVerificationCode::where('code', $request->verification_code);
        $sellerVerification = $request->email != null ? 
                                $sellerVerification->where('email', $email) :
                                $sellerVerification->where('phone', $phone);
        $sellerVerification = $sellerVerification->first();
        if($sellerVerification == null){
            flash(translate('Verification code do not matched'))->error();
            return back();
        }
        else {
            $sellerVerification->is_verified = 1;
            $sellerVerification->save();
                return view('auth.'.get_setting('authentication_layout_select').'.seller_registration', compact('sellerVerification','email','phone'));
        }
    }
}

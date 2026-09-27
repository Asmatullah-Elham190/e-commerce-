<?php

namespace App\Http\Controllers\Auth;

use Cookie;
use Session;
use App\Models\Cart;
use App\Models\User;
use App\Rules\Recaptcha;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Models\BusinessSetting;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Http\Controllers\OTPVerificationController;
use App\Services\FacebookConversionService;
use App\Utility\EmailUtility;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:6|confirmed',
            'g-recaptcha-response' => [
                Rule::when(get_setting('google_recaptcha') == 1 && get_setting('recaptcha_customer_register') == 1 , ['required', new Recaptcha()], ['sometimes'])
            ]
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        if(addon_is_activated('portfolio_system') && get_setting('customer_verification')){
            $data['verification_status'] = 0;
        }
        if (isset($data['email']) && filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $user = User::create([
                'name' => $data['name'] . (isset($data['l_name']) && $data['l_name'] !== '' ? ' ' . $data['l_name'] : ''),
                'email' => $data['email'],
                'phone' => isset($data['phone']) ? '+'.$data['country_code'].preg_replace('/\D+/', '', $data['phone']) : null,
                'password' => Hash::make($data['password']),
                'verification_status' => $data['verification_status'] ?? 1
            ]);
        }
        else {
            if (addon_is_activated('otp_system')){
                $cleanPhone = preg_replace('/\D+/', '', $data['phone']);
                $user = User::create([
                    'name' => $data['name'] . (isset($data['l_name']) && $data['l_name'] !== '' ? ' ' . $data['l_name'] : ''),
                    'phone' => '+'.$data['country_code'].$cleanPhone,
                    'password' => Hash::make($data['password']),
                    'verification_code' => rand(100000, 999999),
                    'verification_status' => $data['verification_status'] ?? 1
                ]);

                if(get_setting('customer_registration_verify') != '1' ){
                    $otpController = new OTPVerificationController;
                    $otpController->send_code($user);
                }

            }
        }

         
        
        if(session('temp_user_id') != null){
            if(auth()->user()->user_type == 'customer'){
                Cart::where('temp_user_id', session('temp_user_id'))
                ->update(
                    [
                        'user_id' => auth()->user()->id,
                        'temp_user_id' => null
                    ]
                );
            }
            else {
                Cart::where('temp_user_id', session('temp_user_id'))->delete();
            }
            Session::forget('temp_user_id');
        }

        if(Cookie::has('referral_code')){
            $referral_code = Cookie::get('referral_code');
            $referred_by_user = User::where('referral_code', $referral_code)->first();
            if($referred_by_user != null){
                $user->referred_by = $referred_by_user->id;
                $user->save();
            }
        }



        return $user;
    }

    public function register(Request $request)
    {
        //dd($request->all());
        if (filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            if(User::where('email', $request->email)->first() != null){
                flash(translate('Email or Phone already exists.'));
                return back();
                
            }
        }
        elseif (User::where('phone', '+'.$request->country_code.$request->phone)->first() != null) {
            flash(translate('Phone already exists.'));
            return back();
        }

        $this->validator($request->all())->validate();

        $user = $this->create($request->all());

        $this->guard()->login($user);

        if($user->email != null){
            if(BusinessSetting::where('type', 'email_verification')->first()->value != 1 || get_setting('customer_registration_verify') === '1'){
                $user->email_verified_at = date('Y-m-d H:m:s');
                $user->save();
                offerUserWelcomeCoupon();
                flash(translate('Registration successful.'))->success();
            }
            else {
                try {
                    EmailUtility::email_verification($user, 'customer');
                    flash(translate('Registration successful. Please verify your email.'))->success();
                } catch (\Throwable $e) {
                    dd($e);
                    $user->delete();
                    flash(translate('Registration failed. Please try again later.'))->error();
                }
            }

            // Account Opening Email to customer
            if ( $user != null && (get_email_template_data('registration_email_to_customer', 'status') == 1)) {
                try {
                    EmailUtility::customer_registration_email('registration_email_to_customer', $user, null);
                } catch (\Exception $e) {}
            }
        }

        if($user->phone != null){
            if(get_setting('email_verification') != 1 || get_setting('customer_registration_verify') === '1'){
                $user->email_verified_at = date('Y-m-d H:m:s');
                $user->save();
                offerUserWelcomeCoupon();
                flash(translate('Registration successful.'))->success();
            }
        }

        // customer Account Opening Email to Admin
        if ( $user != null && (get_email_template_data('customer_reg_email_to_admin', 'status') == 1)) {
            try {
                EmailUtility::customer_registration_email('customer_reg_email_to_admin', $user, null);
            } catch (\Exception $e) {}
        }
                
        // Store GA4 event in session to be pushed on next page load
        if(get_setting('google_analytics') == 1) {
            $ga4Event = [
                'event' => 'sign_up',
                'method' => $request->email ? 'email' : 'phone'
            ];
            session()->flash('ga4_event', $ga4Event);
        }
        
        // Facebook CAPI - CompleteRegistration
        if(get_setting('facebook_pixel_capi') == 1) {
            try {
                $fb = new FacebookConversionService();
                $fb->sendCompleteRegistration($user->id);
            } catch (\Exception $e) {
                \Log::error('Facebook CAPI Registration Error: ' . $e->getMessage());
            }
        }
        
        //  ========== END TRACKING ==========

        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }

    protected function registered(Request $request, $user)
    {
        if ($user->email == null && $user->email_verified_at == null) {
            return redirect()->route('verification');
        }elseif(session('link') != null){
            return redirect(session('link'));
        }else {
            if(addon_is_activated('portfolio_system') && get_setting('customer_verification')){
                return redirect()->route('dashboard');
            }
            return redirect()->route('home');
        }
    }
}

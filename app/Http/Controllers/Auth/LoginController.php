<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use GeneaLabs\LaravelSocialiter\Facades\Socialiter;
use Socialite;
use App\Models\User;
use App\Models\Customer;
use App\Models\Cart;
use App\Services\SocialRevoke;
use App\Utility\EmailUtility;
use Session;
use Illuminate\Http\Request;
use CoreComponentRepository;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use Storage;
use App\Rules\Recaptcha;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    /*protected $redirectTo = '/';*/


    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider($provider)
    {
        if (request()->get('query') == 'mobile_app') {
            request()->session()->put('login_from', 'mobile_app');
        }
        if ($provider == 'apple') {
            return Socialite::driver("sign-in-with-apple")
                ->scopes(["name", "email"])
                ->redirect();
        }
        return Socialite::driver($provider)->redirect();
    }

    public function handleAppleCallback(Request $request)
    {
        try {
            $user = Socialite::driver("sign-in-with-apple")->user();
        } catch (\Exception $e) {
            flash(translate("Something Went wrong. Please try again."))->error();
            return redirect()->route('user.login');
        }
        //check if provider_id exist
        $existingUserByProviderId = User::where('provider_id', $user->id)->first();

        if ($existingUserByProviderId) {
            $existingUserByProviderId->access_token = $user->token;
            $existingUserByProviderId->refresh_token = $user->refreshToken;
            if (!isset($user->user['is_private_email'])) {
                $existingUserByProviderId->email = $user->email;
            }
            $existingUserByProviderId->save();
            //proceed to login
            auth()->login($existingUserByProviderId, true);
        } else {
            //check if email exist
            $existing_or_new_user = User::firstOrNew([
                'email' => $user->email
            ]);
            $existing_or_new_user->provider_id = $user->id;
            $existing_or_new_user->access_token = $user->token;
            $existing_or_new_user->refresh_token = $user->refreshToken;
            $existing_or_new_user->provider = 'apple';
            if (!$existing_or_new_user->exists) {
                $existing_or_new_user->name = 'Apple User';
                if ($user->name) {
                    $existing_or_new_user->name = $user->name;
                }
                $existing_or_new_user->email = $user->email;
                $existing_or_new_user->email_verified_at = date('Y-m-d H:m:s');
            }
            $existing_or_new_user->save();

            auth()->login($existing_or_new_user, true);
        }

        if (session('temp_user_id') != null) {
            Cart::where('user_id', auth()->user()->id)->delete(); // If previous data is available for this user, delete first
            Cart::where('temp_user_id', session('temp_user_id'))
                ->update([
                    'user_id' => auth()->user()->id,
                    'temp_user_id' => null
                ]);

            Session::forget('temp_user_id');
        }

        if (session('link') != null) {
            return redirect(session('link'));
        } else {
            if (auth()->user()->user_type == 'seller') {
                return redirect()->route('seller.dashboard');
            }
            return redirect()->route('dashboard');
        }
    }
    /**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback(Request $request, $provider)
    {
        if (session('login_from') == 'mobile_app') {
            return $this->mobileHandleProviderCallback($request, $provider);
        }
        try {
            if ($provider == 'twitter') {
                $user = Socialite::driver('twitter')->user();
            } else {
                $user = Socialite::driver($provider)->stateless()->user();
            }
        } catch (\Exception $e) {
            flash(translate("Something Went wrong. Please try again."))->error();
            return redirect()->route('user.login');
        }

        //check if provider_id exist
        $existingUserByProviderId = User::where('provider_id', $user->id)->first();

        if ($existingUserByProviderId) {
            $existingUserByProviderId->access_token = $user->token;
            $existingUserByProviderId->save();
            //proceed to login
            auth()->login($existingUserByProviderId, true);
        } else {
            //check if email exist
            $existingUser = User::where('email', '!=', null)->where('email', $user->email)->first();

            if ($existingUser) {
                //update provider_id
                $existing_User = $existingUser;
                $existing_User->provider_id = $user->id;
                $existing_User->provider = $provider;
                $existing_User->access_token = $user->token;
                $existing_User->save();

                //proceed to login
                auth()->login($existing_User, true);
            } else {
                //create a new user
                $newUser = new User;
                $newUser->name = $user->name;
                $newUser->email = $user->email;
                $newUser->email_verified_at = date('Y-m-d Hms');
                $newUser->provider_id = $user->id;
                $newUser->provider = $provider;
                $newUser->access_token = $user->token;
                $newUser->save();
                if ($user->avatar) {
                    if(Str::startsWith($user->avatar, 'http:')) {
                        $user->avatar = Str::replaceFirst('http:', 'https:', $user->avatar);
                    }
                    $newUser->avatar_original = upload_avatar_from_url($user->avatar, $newUser->id);
                    $newUser->save();
                }
                auth()->login($newUser, true);

                // customer Account Opening Email to Admin
                if ((get_email_template_data('customer_reg_email_to_admin', 'status') == 1)) {
                    try {
                        EmailUtility::customer_registration_email('customer_reg_email_to_admin', $newUser, null);
                    } catch (\Exception $e) {}
                }
            }
        }

        if (session('temp_user_id') != null) {
            // Deleting cart data if the user has already cart data.
            Cart::where('user_id', auth()->user()->id)->delete();

            Cart::where('temp_user_id', session('temp_user_id'))
                ->update([
                    'user_id' => auth()->user()->id,
                    'temp_user_id' => null
                ]);

            Session::forget('temp_user_id');
        }

        if (session('link') != null) {
            return redirect(session('link'));
        } else {
            if (auth()->user()->user_type == 'seller') {
                return redirect()->route('seller.dashboard');
            }
            return redirect()->route('dashboard');
        }
    }

    public function mobileHandleProviderCallback($request, $provider)
    {
        try {
            if ($provider == 'twitter') {
                $socialUser = Socialite::driver('twitter')->user();
            } else {
                $socialUser = Socialite::driver($provider)->stateless()->user();
            }
            
            $existingUserByProviderId = User::where('provider_id', $socialUser->id)->first();
            
            if ($existingUserByProviderId) {
                $existingUserByProviderId->access_token = $socialUser->token;
                $existingUserByProviderId->save();
                $user = $existingUserByProviderId;
            } else {
                $existingUser = User::where('email', $socialUser->email)->first();
                
                if ($existingUser) {
                    $existingUser->provider_id = $socialUser->id;
                    $existingUser->provider = $provider;
                    $existingUser->access_token = $socialUser->token;
                    $existingUser->save();
                    $user = $existingUser;
                } else {
                    $user = new User;
                    $user->name = $socialUser->name ?? $socialUser->nickname ?? 'User';
                    $user->email = $socialUser->email ?? $socialUser->nickname . '@twitter.com';
                    $user->email_verified_at = now();
                    $user->provider_id = $socialUser->id;
                    $user->provider = $provider;
                    $user->access_token = $socialUser->token;
                    $user->save();
                    
                    if ($socialUser->avatar) {
                        $avatarUrl = $socialUser->avatar;
                        if(Str::startsWith($avatarUrl, 'http:')) {
                            $avatarUrl = Str::replaceFirst('http:', 'https:', $avatarUrl);
                        }
                        $user->avatar_original = upload_avatar_from_url($avatarUrl, $user->id);
                        $user->save();
                    }
                }
            }
            $userData = [
                'result' => true,
                'message' => 'Authentication successful',
                'access_token' => $user->createToken('MobileApp')->plainTextToken, 
                'token_type' => 'Bearer',
                'user' => [
                    'id' => $user->id,
                    'type' => $user->user_type ?? 'customer',
                    'name' => $user->name,
                    'email' => $user->email,
                    'avatar' => $user->avatar_original,
                    'avatar_original' => $user->avatar_original,
                    'phone' => $user->phone ?? "", 
                    'email_verified' => true
                ]
            ];
            
            $encodedData = base64_encode(json_encode($userData));
            return redirect()->to("appsocialloginscheme://login-success?data=" . $encodedData);
            
        } catch (\Exception $e) {
            \Log::error('Mobile Social Login Error: ' . $e->getMessage());
            $errorData = [
                'result' => false,
                'provider' => $provider,
                'error' => $e->getMessage(),
                'message' => 'Authentication failed. Please try again.'
            ];
            $encodedError = base64_encode(json_encode($errorData));
            return redirect()->to("appsocialloginscheme://login-error?data=" . $encodedError);
        }
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            'email'    => 'required_without:phone',
            'phone'    => 'required_without:email',
            'password' => 'required|string',
              'g-recaptcha-response' => [
                Rule::when(get_setting('google_recaptcha') == 1  && get_setting($request['recaptcha_action']) == 1 , ['required', new Recaptcha()], ['sometimes'])
            ],
        ]);
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        if ($request->get('phone') != null) {
            return ['phone' => "+{$request['country_code']}{$request['phone']}", 'password' => $request->get('password')];
        } elseif ($request->get('email') != null) {
            return $request->only($this->username(), 'password');
        }
    }

    /**
     * Check user's role and redirect user based on their role
     * @return
     */
    public function authenticated()
    {
        if (session('temp_user_id') != null) {
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

        if (auth()->user()->user_type == 'admin' || auth()->user()->user_type == 'staff') {
            CoreComponentRepository::instantiateShopRepository();
            return redirect()->route('admin.dashboard');
        } elseif (auth()->user()->user_type == 'seller') {
            
            if (auth()->user()->shop->registration_approval  == 0) {
                auth()->logout();
                flash(translate("Your seller account is under review. We will notify you once approved."));
                return redirect()->route('home');
            }
            //save the seller login log
            \Log::channel('seller_login')->info('Seller Logged In', [
                'user_id' => auth()->user()->id,
                'email' => auth()->user()->email,
                'time' => now()->toDateTimeString(),
            ]);
            return redirect()->route('seller.dashboard');
        } else {

            if (session('link') != null) {
                return redirect(session('link'));
            } else {
                return redirect()->route('dashboard');
            }
        }
    }

    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        flash(translate('Invalid login credentials'))->error();
        return back();
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        if (auth()->user() != null && (auth()->user()->user_type == 'admin' || auth()->user()->user_type == 'staff')) {
            $redirect_route = 'login';
        } else {
            $redirect_route = 'home';
        }

        //User's Cart Delete
        // if (auth()->user()) {
        //     Cart::where('user_id', auth()->user()->id)->delete();
        // }

        $this->guard()->logout();

        $request->session()->invalidate();

        return $this->loggedOut($request) ?: redirect()->route($redirect_route);
    }

    public function account_deletion(Request $request)
    {

        $redirect_route = 'home';

        if (auth()->user()) {
            Cart::where('user_id', auth()->user()->id)->delete();
        }

        // if (auth()->user()->provider) {
        //     $social_revoke =  new SocialRevoke;
        //     $revoke_output = $social_revoke->apply(auth()->user()->provider);

        //     if ($revoke_output) {
        //     }
        // }

        $auth_user = auth()->user();

        // user images delete from database and file storage
        $uploads = $auth_user->uploads;
        if ($uploads) {
            foreach ($uploads as $upload) {
                if (env('FILESYSTEM_DRIVER') == 's3') {
                    Storage::disk('s3')->delete($upload->file_name);
                    if (file_exists(public_path() . '/' . $upload->file_name)) {
                        unlink(public_path() . '/' . $upload->file_name);
                        $upload->delete();
                    }
                } else {
                    unlink(public_path() . '/' . $upload->file_name);
                    $upload->delete();
                }
            }
        }

        $auth_user->customer_products()->delete();

        User::destroy(auth()->user()->id);

        auth()->guard()->logout();
        $request->session()->invalidate();

        flash(translate("Your account deletion successfully done."))->success();
        return redirect()->route($redirect_route);
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except(['logout', 'account_deletion']);
    }

    public function handle_demo_login()
    {
        return view('frontend.handle_demo_login');
    }
}

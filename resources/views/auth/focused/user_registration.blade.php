@extends('auth.layouts.authentication')

@section('content')
    <!-- aiz-main-wrapper -->
    <div class="aiz-main-wrapper d-flex flex-column justify-content-center bg-white">
        <section class="bg-white overflow-hidden" style="min-height:100vh;">
            <div class="row no-gutters" style="min-height: 100vh;">
                <!-- Left Side Image-->
                <div class="col-xxl-9 col-lg-8">
                    <div class="h-100" style="max-height: 100vh">
                        <img src="{{ uploaded_asset(get_setting('customer_register_page_image')) }}" alt="" class="img-fit h-100">
                    </div>
                </div>
                
                <!-- Right Side -->
                <div class="col-xxl-3 col-lg-4">
                    <div class="d-flex align-items-center right-content">
                        <div class="py-3 py-lg-4 px-3 px-xl-5 flex-grow-1">
                            <!-- Site Icon -->
                            <div class="size-48px mb-3 mx-auto mx-lg-0">
                                <img src="{{ uploaded_asset(get_setting('site_icon')) }}" alt="{{ translate('Site Icon')}}" class="img-fit h-100">
                            </div>
                            <!-- Titles -->
                            <div class="text-center text-lg-left">
                                <h1 class="fs-20 fs-md-24 fw-700 text-primary" style="text-transform: uppercase;">{{ translate('Create an account')}}</h1>
                            </div>
                            <!-- Register form -->
                            <div class="pt-3 pt-lg-4">
                                <div class="">
                                    <form id="reg-form" class="form-default" role="form" action="{{ route('register') }}" method="POST">
                                        @csrf
                                        <!-- Name -->
                                        <div class="form-group">
                                            <label for="name" class="fs-12 fw-700 text-soft-dark">{{  translate('Full Name') }}</label>
                                            <input type="text" class="form-control rounded-0{{ $errors->has('name') ? ' is-invalid' : '' }}" value="{{ old('name') }}" placeholder="{{  translate('Full Name') }}" name="name">
                                            @if ($errors->has('name'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('name') }}</strong>
                                                </span>
                                            @endif
                                        </div>

                                        @if (addon_is_activated('otp_system'))
                                        <div>
                                            <div id="emailOrPhoneDiv">
                                                {{-- Show both fields with the toggle button if neither email nor phone is set --}}
                                                <div class="form-group phone-form-group mb-1 ">
                                                    <label for="phone" class="fs-12 fw-700 text-soft-dark">{{ translate('Phone') }}</label>
                                                    <div class="input-group registration-iti">
                                                        <input type="tel" phone-number id="phone-code" class="form-control rounded-0{{ $errors->has('phone') ? ' is-invalid' : '' }}"
                                                        value="{{ old('phone') }}" placeholder="" name="phone"
                                                        autocomplete="off">
                                                        @if(get_setting('customer_registration_verify') == '1')
                                                        <button class="btn btn-primary" type="button" id="sendOtpPhoneBtn" onclick="sendVerificationCode(this)">
                                                                    {{ translate('Verify') }} 
                                                        </button>
                                                        @endif
                                                    </div>
                                                </div>
                                        
                                                <input type="hidden" id="country_code" name="country_code" value="{{ old('country_code', 'US') }}"> {{-- Default to 'US' --}}
                                        
                                                <div class="form-group email-form-group mb-1 d-none">
                                                    <label for="email" class="fs-12 fw-700 text-soft-dark">{{ translate('Email') }}</label>
                                                    <div class="input-group">
                                                        <input type="email" class="form-control rounded-0 {{ $errors->has('email') ? ' is-invalid' : '' }} "
                                                        value="{{ old('email') }}" placeholder="{{ translate('Email') }}" name="email" id="signinAddonEmail"
                                                        autocomplete="off">
                                                        @if(get_setting('customer_registration_verify') == '1')
                                                        <button class="btn btn-primary ml-2" type="button" id="sendOtpBtn" onclick="sendVerificationCode(this)">
                                                                {{ translate('Verify') }} 
                                                        </button>
                                                        @endif
                                                    </div>
                                                    @if ($errors->has('email'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('email') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                        
                                                <div class="form-group text-right mb-0" id="mail_phone_toggle_btn">
                                                    <button class="btn btn-link p-0 text-primary" type="button" onclick="toggleEmailPhone(this)">
                                                        <i>*{{ translate('Use Email Instead') }}</i>
                                                    </button>
                                                </div>
                                            
                                            </div>
                                            <div class="form-group mb-3 d-none">
                                                <label class="form-label" for="verification_code">{{ translate('Verification Code') }}</label>
                                                <div class="input-group">
                                                    <input type="text"
                                                        class="form-control @error('verification_code') is-invalid @enderror border-right-0"
                                                        name="code" id="verification_code"
                                                        placeholder="{{ translate('Verification Code') }}"
                                                        maxlength="6">
                                                    <span class="btn border border-left-0" id="verifyOtpBtn">
                                                        <i class="las la-lg la-arrow-right"></i> 
                                                    </span>
                                                    @error('otp')
                                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        @else
                                            {{-- If OTP system is disabled, show only the email field --}}
                                            <div class="form-group email-phone-div  email-form-group" id="emailOrPhoneDiv">
                                                <label for="email" class="fs-12 fw-700 text-soft-dark">{{ translate('Email') }}</label>
                                                {{--<input type="email" class="form-control rounded-0{{ $errors->has('email') ? ' is-invalid' : '' }}" 
                                                       value="{{ $email ?? old('email') }}" placeholder="{{ translate('Email') }}" name="email" {{$email  ? 'readonly' : ''}} >--}}

                                                <div class="input-group">
                                                    <input type="email"
                                                        class="form-control rounded-0 {{ $errors->has('email') ? ' is-invalid' : '' }}"
                                                        name="email" id="signinSrEmail"
                                                        placeholder="{{ translate('Email Address') }}">
                                                        @if(get_setting('customer_registration_verify') == '1')
                                                        <button class="btn btn-primary ml-2" type="button" id="sendOtpBtn" onclick="sendVerificationCode()">
                                                            {{ translate('Verify') }} 
                                                        </button>
                                                        @endif
                                                </div>
                                                @if ($errors->has('email'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('email') }}</strong>
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="form-group mb-3 d-none">
                                                <label class="form-label" for="verification_code">{{ translate('Verification Code') }}</label>
                                                <div class="input-group">
                                                    <input type="text"
                                                        class="form-control @error('verification_code') is-invalid @enderror border-right-0"
                                                        name="code" id="verification_code"
                                                        placeholder="{{ translate('Verification Code') }}"
                                                        maxlength="6">
                                                    <span class="btn border border-left-0" id="verifyOtpBtn">
                                                        <i class="las la-lg la-arrow-right"></i> 
                                                    </span>
                                                    @error('otp')
                                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        @endif

                                        <!-- password -->
                                        <div class="form-group mb-0">
                                            <label for="password" class="fs-12 fw-700 text-soft-dark">{{  translate('Password') }}</label>
                                            <div class="position-relative">
                                                <input type="password" class="form-control rounded-0{{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="{{  translate('Password') }}" name="password">
                                                <i class="password-toggle las la-2x la-eye"></i>
                                            </div>
                                            <div class="text-right mt-1">
                                                <span class="fs-12 fw-400 text-gray-dark">{{ translate('Password must contain at least 6 digits') }}</span>
                                            </div>
                                            @if ($errors->has('password'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('password') }}</strong>
                                                </span>
                                            @endif
                                        </div>

                                        <!-- password Confirm -->
                                        <div class="form-group">
                                            <label for="password_confirmation" class="fs-12 fw-700 text-soft-dark">{{  translate('Confirm Password') }}</label>
                                            <div class="position-relative">
                                                <input type="password" class="form-control rounded-0" placeholder="{{  translate('Confirm Password') }}" name="password_confirmation">
                                                <i class="password-toggle las la-2x la-eye"></i>
                                            </div>
                                        </div>

                                        <!-- Recaptcha -->
                                            @if(get_setting('google_recaptcha') == 1 && get_setting('recaptcha_customer_register') == 1)
                                                
                                                @if ($errors->has('g-recaptcha-response'))
                                                    <span class="border invalid-feedback rounded p-2 mb-3 bg-danger text-white" role="alert" style="display: block;">
                                                        <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                                                    </span>
                                                @endif
                                            @endif

                                        <!-- Terms and Conditions -->
                                        <div class="mb-3">
                                            <label class="aiz-checkbox">
                                                <input type="checkbox" name="checkbox_example_1" required>
                                                <span class="">{{ translate('By signing up you agree to our ')}} <a href="{{ route('terms') }}" class="fw-500">{{ translate('terms and conditions.') }}</a></span>
                                                <span class="aiz-square-check"></span>
                                            </label>
                                        </div>

                                        <!-- Submit Button -->
                                        <div class="mb-4 mt-4">
                                            <button type="submit" id="createAccountBtn" class="btn btn-primary btn-block fw-600 rounded-0">{{  translate('Create Account') }}</button>
                                        </div>
                                    </form>
                                    
                                    <!-- Social Login -->
                                    @if(get_setting('google_login') == 1 || get_setting('facebook_login') == 1 || get_setting('twitter_login') == 1 || get_setting('apple_login') == 1)
                                        <div class="text-center mb-3">
                                            <span class="bg-white fs-12 text-gray">{{ translate('Or Join With')}}</span>
                                        </div>
                                        <ul class="list-inline social colored text-center mb-4">
                                            @if (get_setting('facebook_login') == 1)
                                                <li class="list-inline-item">
                                                    <a href="{{ route('social.login', ['provider' => 'facebook']) }}" class="facebook">
                                                        <i class="lab la-facebook-f"></i>
                                                    </a>
                                                </li>
                                            @endif
                                            @if (get_setting('twitter_login') == 1)
                                                <li class="list-inline-item">
                                                    <a href="{{ route('social.login', ['provider' => 'twitter']) }}" class="x-twitter">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="#ffffff" viewBox="0 0 16 16" class="mb-2 pb-1">
                                                            <path d="M12.6.75h2.454l-5.36 6.142L16 15.25h-4.937l-3.867-5.07-4.425 5.07H.316l5.733-6.57L0 
                                                            .75h5.063l3.495 4.633L12.601.75Zm-.86 13.028h1.36L4.323 2.145H2.865z"/>
                                                        </svg>
                                                    </a>
                                                </li>
                                            @endif
                                            @if(get_setting('google_login') == 1)
                                                <li class="list-inline-item">
                                                    <a href="{{ route('social.login', ['provider' => 'google']) }}" class="google">
                                                        <i class="lab la-google"></i>
                                                    </a>
                                                </li>
                                            @endif
                                            @if (get_setting('apple_login') == 1)
                                                <li class="list-inline-item">
                                                    <a href="{{ route('social.login', ['provider' => 'apple']) }}" class="apple">
                                                        <i class="lab la-apple"></i>
                                                    </a>
                                                </li>
                                            @endif
                                        </ul>
                                    @endif
                                </div>

                                <!-- Log In -->
                                <p class="fs-12 text-gray mb-0">
                                    {{ translate('Already have an account?')}}
                                    <a href="{{ route('user.login') }}" class="ml-2 fs-14 fw-700 animate-underline-primary">{{ translate('Log In')}}</a>
                                </p>
                                <!-- Go Back -->
                                <a href="{{ url()->previous() }}" class="mt-3 fs-14 fw-700 d-flex align-items-center text-primary" style="max-width: fit-content;">
                                    <i class="las la-arrow-left fs-20 mr-1"></i>
                                    {{ translate('Back to Previous Page')}}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('script')
    @if(get_setting('google_recaptcha') == 1 && get_setting('recaptcha_customer_register') == 1)
        <script src="https://www.google.com/recaptcha/api.js?render={{ env('CAPTCHA_KEY') }}"></script>
        
        <script type="text/javascript">
                document.getElementById('reg-form').addEventListener('submit', function(e) {
                    e.preventDefault();
                    grecaptcha.ready(function() {
                        grecaptcha.execute(`{{ env('CAPTCHA_KEY') }}`, {action: 'register'}).then(function(token) {
                            var input = document.createElement('input');
                            input.setAttribute('type', 'hidden');
                            input.setAttribute('name', 'g-recaptcha-response');
                            input.setAttribute('value', token);
                            e.target.appendChild(input);
                            e.target.submit();
                        });
                    });
                });
        </script>
    @endif
    @include('auth.verifyEmailOrPhone')

    <script>
        const regVerifyRequired = {{get_setting('customer_registration_verify') ? 'true' : 'false' }};
        //user registerbtn disable
        const createBtn   = $('#createAccountBtn');
        const termsCheckbox = $('input[name="checkbox_example_1"]');
        function toggleCreateBtn() {
            const termsChecked = termsCheckbox.is(':checked');
            const regVerified  = regVerifyRequired ? (verifyBtn && verifyBtn.classList.contains('disabled')) : true;
            let enableBtn = false;
            if (regVerifyRequired) {
                enableBtn = termsChecked && regVerified;
            } else {
                enableBtn = termsChecked;
            }
            createBtn.prop('disabled', !enableBtn);
        }

        document.addEventListener('DOMContentLoaded', function() {
            toggleCreateBtn(); 
            termsCheckbox.on('change', toggleCreateBtn); 
        });
    </script>
@endsection
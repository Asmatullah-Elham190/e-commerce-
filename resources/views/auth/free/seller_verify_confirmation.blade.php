@extends('auth.layouts.authentication')

@section('content')
    @php $isOtpSystemActivated = addon_is_activated('otp_system'); @endphp
    <!-- aiz-main-wrapper -->
    <div class="aiz-main-wrapper d-flex flex-column justify-content-md-center bg-white">
        <section class="bg-white overflow-hidden">
            <div class="row">
                <div class="col-xxl-6 col-xl-9 col-lg-10 col-md-7 mx-auto py-lg-4">
                    <div class="card shadow-none rounded-0 border-0">
                        <div class="row no-gutters">
                            <!-- Left Side Image-->
                            <div class="col-lg-6">
                                    <img src="{{ uploaded_asset(get_setting('seller_register_page_image')) }}" alt="" class="img-fit h-100">
                                </div>
                                    
                                <!-- Right Side -->
                                <div class="col-lg-6 p-4 p-lg-5 d-flex flex-column justify-content-center border right-content" style="height: auto;">
                                    <!-- Site Icon -->
                                    <div class="size-48px mb-3 mx-auto mx-lg-0">
                                        <img src="{{ uploaded_asset(get_setting('site_icon')) }}" alt="{{ translate('Site Icon')}}" class="img-fit h-100">
                                    </div>

                                    <!-- Titles -->
                                    <div class="text-center text-lg-left">
                                        <h1 class="fs-20 fs-md-24 fw-700 text-primary" style="text-transform: uppercase;">
                                            {{ !$isOtpSystemActivated ? translate('Verify Your Email') : translate('Verify Your Email/Phone') }}
                                        </h1>
                                    </div>
                                    <!-- Register form -->
                                    <div class="pt-3 pt-lg-4">
                                        <form id="reg-form" class="form-default" role="form" action="{{ route('shop-reg.verify_code_confirmation') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="seller_verification_id" value="{{ $sellerVerification->id }}">
                                            @if($sellerVerification->email != null)
                                                <div class="form-group">
                                                    <label class="fs-12 fw-700 text-soft-dark">{{  translate('Email') }}</label>
                                                    <input type="text" name="email" class="form-control rounded-0" value="{{ $sellerVerification->email }}" readonly>
                                                </div>
                                            @else
                                                <div class="form-group">
                                                    <label class="fs-12 fw-700 text-soft-dark">{{  translate('Phone') }}</label>
                                                    <input type="text" name="phone" class="form-control rounded-0" value="{{ $sellerVerification->phone }}" readonly>
                                                </div>
                                            @endif

                                            <div class="form-group">
                                                <label class="fs-12 fw-700 text-soft-dark">{{  translate('Verification Code') }}</label>
                                                <input type="number" name="verification_code" class="form-control rounded-0" value="">
                                            </div>

                                            <!-- Submit Button -->
                                            <div class="mb-4 mt-4">
                                                <button type="submit" class="btn btn-primary btn-block fw-600 rounded-0">{{  translate('Submit') }}</button>
                                            </div>
                                        </form>
                                        <!-- Log In -->
                                        <p class="fs-12 text-gray mb-0">
                                            {{ translate('Already have an account?')}}
                                            <a href="{{ route('seller.login') }}" class="ml-2 fs-14 fw-700 animate-underline-primary">{{ translate('Log In')}}</a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Go Back -->
                        <div class="mt-3 mr-4 mr-md-0">
                            <a href="{{ url()->previous() }}" class="ml-auto fs-14 fw-700 d-flex align-items-center text-primary" style="max-width: fit-content;">
                                <i class="las la-arrow-left fs-20 mr-1"></i>
                                {{ translate('Back to Previous Page')}}
                            </a>
                        </div>
                    </div>
                </div>
        </section>
    </div>
@endsection

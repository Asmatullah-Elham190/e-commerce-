@if(get_setting('product_query_activation') == 1)
    <div class="product-queries-container py-20px px-30px border bg-white border-light-gray rounded-2">
        <p class="fs-20 fw-bold text-dark">{{ translate(' Product Queries ') }} ({{ count($detailedProduct->product_queries) }})</p>
        <div class="mb-2 bg-white has-transition">
            <!-- Login & Register -->
            @guest
                <p class="fs-14 fw-400 mb-0 mt-3"><a
                        href="{{ route('user.login') }}">{{ translate('Login') }}</a> 
                        <span class="text-lowercase">{{ translate('or') }}</span>
                         <a class="mr-1"
                        href="{{ route('user.registration') }}">{{ translate('Register ') }}</a>{{ translate(' to submit your questions to seller') }}
                        {{-- href="{{ route('user.registration') }}">{{ translate('Register ') }}</a>{{ translate(' to submit your questions to seller') }} --}}
                </p>
            @endguest

            <!-- Query Submit -->

            
           @auth
                <div class="query product-queries form px-3 py-3 py-sm-2 border border-light-gray rounded-2 mt-3 has-transition">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('product-queries.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product" value="{{ $detailedProduct->id }}">
                        <div class="row align-items-center">
                            <div class="col-12 col-md-9 col-lg-10">
                                <textarea class="form-control border-0 px-0" id="product-queries" rows="3" name="question"
                                    placeholder="Write your question here . . . "></textarea>
                            </div>
                            <div class="col-12 col-sm-6 col-md-3 col-lg-2">
                                <input type="submit" value="Submit"
                                    class="bg-orange text-white hov-opacity-80 has-transition text-center fs-14 fw-bold w-100 py-2 rounded-1 border-0 ">
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Own Queries -->
                @php
                    $own_product_queries = $detailedProduct->product_queries->where('customer_id', Auth::id());
                @endphp
                @if ($own_product_queries->count() > 0)
                <div class="mt-4">
                    <h5 class="fs-16 fw-bold text-dark">{{ translate('My Questions') }}</h5>
                    <div class="d-flex flex-column pt-20px other-question">
                        <!--Single Question-->
                        @foreach ($own_product_queries as $product_query)
                        <div class="d-flex align-items-start single-question">
                            <span class="flex-shrink-0 d-block mt-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="36"
                                    viewBox="0 0 24 36">
                                    <g id="Group_23928" data-name="Group 23928"
                                        transform="translate(-654 -2397)">
                                        <path id="Path_28707" data-name="Path 28707" d="M0,0H24V24H0Z"
                                            transform="translate(654 2397)" fill="#363636" />
                                        <text id="Q" transform="translate(666 2414)" fill="#fff"
                                            font-size="14" font-family="SegoeUI-Bold, Segoe UI"
                                            font-weight="700">
                                            <tspan x="-5.308" y="0">{{ translate('Q') }}</tspan>
                                        </text>
                                        <path id="Path_28708" data-name="Path 28708" d="M0,0H12L0,12Z"
                                            transform="translate(666 2421)" />
                                    </g>
                                </svg>
                            </span>
                            <div>
                                <p class="fs-14 text-dark fw-400 mb-1 p-0">{{ strip_tags($product_query->question) }}</p>
                                <span class="fs-12 text-gray fw-400">{{ $product_query->user->name }}</span>
                            </div>
                        </div>

                        <!--Single Answer-->
                        <div class="d-flex align-items-start single-question">
                            <span class="flex-shrink-0 d-block mt-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="36"
                                    viewBox="0 0 24 36">
                                    <g id="Group_23929" data-name="Group 23929"
                                        transform="translate(-654 -2453)">
                                        <path id="Path_28709" data-name="Path 28709" d="M0,0H24V24H0Z"
                                            transform="translate(654 2453)" fill="#1592e6" />
                                        <text id="A" transform="translate(666 2470)" fill="#fff"
                                            font-size="14" font-family="SegoeUI-Bold, Segoe UI"
                                            font-weight="700">
                                            <tspan x="-4.922" y="0">{{ translate('A') }}</tspan>
                                        </text>
                                        <path id="Path_28710" data-name="Path 28710" d="M0,0H12L0,12Z"
                                            transform="translate(666 2477)" fill="#0266cc" />
                                    </g>
                                </svg>
                            </span>
                            @php

                                if($detailedProduct->added_by == 'seller'){
                                   $product_shop_name= $detailedProduct->user->shop->name;
                                }else{
                                     $product_shop_name= get_setting('site_name');
                                }
                            
                                
                            @endphp
                            <div>
                                <p class="fs-14 text-dark fw-400 mb-1 p-0">
                                    @if($product_query->reply)
                                        {{ strip_tags($product_query->reply) }}
                                    @else
                                        <span class="text-gray">{{ translate('Seller did not respond yet') }}</span>
                                    @endif
                                </p>

                                <span class="fs-12 text-gray fw-400">{{ $product_shop_name }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            @endauth
           

            <div class="mt-4 queries-area">
                 @include('frontend.partials.product_query_pagination')
            </div>
        </div>
    </div>
@endif

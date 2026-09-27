<!-- Top Bar Banner -->
@php
    $top_banner_background_color = get_setting('top_banner_background_color', get_setting('base_color'));
    $top_banner_text_color = get_setting('top_banner_text_color');
    $top_banner_image = get_setting('top_banner_image');
    $top_banner_image_for_tabs = get_setting('top_banner_image_for_tabs');
    $top_banner_image_for_mobile = get_setting('top_banner_image_for_mobile');
    $topBanners = \App\Models\TopBanner::where('status', 1)->orderBy('id','desc')->get();
@endphp 
    @if (count($topBanners) > 0 || $top_banner_image != null)
    <div class="position-relative top-banner removable-session z-1035 d-none" 
         data-key="top-banner" data-value="removed" style="background-color: {{ $top_banner_background_color }}">
        <div class="d-block text-reset h-40px h-lg-60px position-relative overflow-hidden">

            @if($top_banner_image != null)
            <!-- For Large device -->
            <img src="{{ uploaded_asset($top_banner_image)  }}"
                class="d-none d-xl-block img-fit h-100 w-100" alt="{{ translate('top_banner') }}">

            <!-- For Medium device -->
            <img src="{{ uploaded_asset($top_banner_image_for_tabs ?? $top_banner_image)  }}"
                class="d-none d-md-block d-xl-none img-fit h-100 w-100" alt="{{ translate('top_banner') }}">

            <!-- For Small device -->
            <img src="{{ uploaded_asset($top_banner_image_for_mobile ?? $top_banner_image) }}"
                class="d-md-none img-fit h-100 w-100" alt="{{ translate('top_banner') }}">
            @endif

            <!-- Scroll Text -->
            <div class="top-banner-scroll-text position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center">
                <div class="@if (get_setting('show_full_width_top_bar') == 1) layout-container mx-auto px-3 @else container @endif">
                    <div class="overflow-hidden">
                        <div class="top-banner-scroll-inner">
                            @foreach ($topBanners as $banner)
                                <a href="{{ $banner->link ?? '#' }}" style="color: {{$top_banner_text_color}};"
                                    class="{{ $banner->link ? 'has-link' : 'no-link' }}">
                                    {{ $banner->getTranslation('text') }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button class="btn text-white h-100 absolute-top-right set-session" 
            data-key="top-banner" data-value="removed"
            data-toggle="remove-parent" data-parent=".top-banner">
            <i style="color: {{$top_banner_text_color}};" class="la la-close la-2x"></i>
        </button>
    </div>
    @endif
	@include('header.' .get_element_type_by_id(get_setting('header_element')))
<!-- Top Menu Sidebar -->
<div class="aiz-top-menu-sidebar collapse-sidebar-wrap sidebar-xl sidebar-left d-lg-none z-1035">
    <div class="overlay overlay-fixed dark c-pointer" data-toggle="class-toggle" data-target=".aiz-top-menu-sidebar"
        data-same=".hide-top-menu-bar"></div>
    <div class="collapse-sidebar c-scrollbar-light text-left">
        <button type="button" class="btn btn-sm p-4 hide-top-menu-bar" data-toggle="class-toggle"
            data-target=".aiz-top-menu-sidebar">
            <i class="las la-times la-2x text-primary"></i>
        </button>
        @auth
            <span class="d-flex align-items-center nav-user-info pl-4">
                <!-- Image -->
                <span class="size-40px rounded-circle overflow-hidden border border-transparent nav-user-img">
                    @if ($user->avatar_original != null)
                        <img src="{{ $user_avatar }}" class="img-fit h-100" alt="{{ translate('avatar') }}"
                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                    @else
                        <img src="{{ static_asset('assets/img/avatar-place.png') }}" class="image"
                            alt="{{ translate('avatar') }}"
                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                    @endif
                </span>
                <!-- Name -->
                <h4 class="h5 fs-14 fw-700 text-dark ml-2 mb-0">{{ $user->name }}</h4>
            </span>
        @else
            <!--Login & Registration -->
            <span class="d-flex align-items-center nav-user-info pl-4">
                <!-- Image -->
                <span
                    class="size-40px rounded-circle overflow-hidden border d-flex align-items-center justify-content-center nav-user-img">
                    <svg xmlns="http://www.w3.org/2000/svg" width="19.902" height="20.012" viewBox="0 0 19.902 20.012">
                        <path id="fe2df171891038b33e9624c27e96e367"
                            d="M15.71,12.71a6,6,0,1,0-7.42,0,10,10,0,0,0-6.22,8.18,1.006,1.006,0,1,0,2,.22,8,8,0,0,1,15.9,0,1,1,0,0,0,1,.89h.11a1,1,0,0,0,.88-1.1,10,10,0,0,0-6.25-8.19ZM12,12a4,4,0,1,1,4-4A4,4,0,0,1,12,12Z"
                            transform="translate(-2.064 -1.995)" fill="#91919b" />
                    </svg>
                </span>

                <a href="{{ route('user.login') }}"
                    class="text-reset opacity-60 hov-opacity-100 hov-text-primary fs-12 d-inline-block border-right border-soft-light border-width-2 pr-2 ml-3">{{ translate('Login') }}</a>
                <a href="{{ route('user.registration') }}"
                    class="text-reset opacity-60 hov-opacity-100 hov-text-primary fs-12 d-inline-block py-2 pl-2">{{ translate('Registration') }}</a>
            </span>
            
        @endauth
        <hr>
        <ul class="mb-0 pl-3 pb-3 h-100">
            @if (get_setting('header_menu_labels') != null)
                @foreach (json_decode(get_setting('header_menu_labels'), true) as $key => $value)
                    <li class="mr-0">
                        <a href="{{ resolve_internal_menu_url(json_decode(get_setting('header_menu_links'), true)[$key]) }}"
                            class="fs-13 px-3 py-3 w-100 d-inline-block fw-700 text-dark header_menu_links
                                    @if (url()->current() == resolve_internal_menu_url(json_decode(get_setting('header_menu_links'), true)[$key])) active @endif">
                            {{ translate($value) }}
                        </a>
                    </li>
                @endforeach
            @endif
            @auth
                @if (isAdmin())
                    <hr>
                    <li class="mr-0">
                        <a href="{{ route('admin.dashboard') }}"
                            class="fs-13 px-3 py-3 w-100 d-inline-block fw-700 text-dark header_menu_links">
                            {{ translate('My Account') }}
                        </a>
                    </li>
                @else
                    <hr>
                    <li class="mr-0">
                        <a href="{{ route('dashboard') }}" class="fs-13 px-3 py-3 w-100 d-inline-block fw-700 text-dark header_menu_links
                                        {{ areActiveRoutes(['dashboard'], ' active') }}">
                            {{ translate('My Account') }}
                        </a>
                    </li>
                @endif
                @if (isCustomer())
                    <li class="mr-0">
                        <a href="{{ route('customer.all-notifications') }}" class="fs-13 px-3 py-3 w-100 d-inline-block fw-700 text-dark header_menu_links
                                        {{ areActiveRoutes(['customer.all-notifications'], ' active') }}">
                            {{ translate('Notifications') }}
                        </a>
                    </li>
                    <li class="mr-0">
                        <a href="{{ route('wishlists.index') }}" class="fs-13 px-3 py-3 w-100 d-inline-block fw-700 text-dark header_menu_links
                                        {{ areActiveRoutes(['wishlists.index'], ' active') }}">
                            {{ translate('Wishlist') }}
                        </a>
                    </li>
                    <li class="mr-0">
                        <a href="{{ route('compare') }}" class="fs-13 px-3 py-3 w-100 d-inline-block fw-700 text-dark header_menu_links
                                        {{ areActiveRoutes(['compare'], ' active') }}">
                            {{ translate('Compare') }}
                        </a>
                    </li>
                @endif
                <hr>
                <li class="mr-0">
                    <a href="{{ route('logout') }}"
                        class="fs-13 px-3 py-3 w-100 d-inline-block fw-700 text-primary header_menu_links">
                        {{ translate('Logout') }}
                    </a>
                </li>
            @endauth
        </ul>
        <br>
        <br>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="order_details" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div id="order-details-modal-body">

            </div>
        </div>
    </div>
</div>

@section('script')
    <script type="text/javascript">
        function show_order_details(order_id) {
            $('#order-details-modal-body').html(null);

            if (!$('#modal-size').hasClass('modal-lg')) {
                $('#modal-size').addClass('modal-lg');
            }

            $.post('{{ route('orders.details') }}', {
                _token: AIZ.data.csrf,
                order_id: order_id
            }, function (data) {
                $('#order-details-modal-body').html(data);
                $('#order_details').modal();
                $('.c-preloader').hide();
                AIZ.plugins.bootstrapSelect('refresh');
            });
        }
    </script>
@endsection
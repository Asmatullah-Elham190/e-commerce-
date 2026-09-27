@extends('backend.layouts.app')

@section('content')

    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{translate('Coupon Information Adding')}}</h5>
            </div>
            <div class="card-body">
                <form class="form-horizontal" action="{{ route('coupon.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mt-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="form-group row">
                        <label class="col-12 col-from-label" for="name">{{translate('Coupon Type')}}</label>
                        <div class="col-12">
                            <select name="type" id="coupon_type" class="form-control aiz-selectpicker"
                                onchange="coupon_form()" required>
                                <option value="">{{translate('Select One') }}</option>
                                <option value="product_base" @if (old('type') == 'product_base') selected @endif>
                                    {{translate('For Products')}}
                                </option>
                                <option value="cart_base" @if (old('type') == 'cart_base') selected @endif>
                                    {{translate('For Total Orders')}}
                                </option>
                                <option value="welcome_base" @if (old('type') == 'welcome_base') selected @endif>
                                    {{translate('Welcome Coupon')}}
                                </option>
                            </select>
                        </div>
                    </div>

                    <div id="coupon_form">

                    </div>
                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-primary">{{translate('Save')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('modal')
    @include('modals.loading_modal')

    <div id="rightOffcanvas" class="right-offcanvas-lg position-fixed top-0 fullscreen bg-white py-20px z-1045">

        <div class="border-bottom pb-15px px-30px">
            <div class="d-flex align-items-center justify-content-between">
                <h5 class="fs-16 fw-700 m-0">{{ translate('Select Products') }}</h5>
                <button onclick="closeOffcanvas()" class="border-0 bg-transparent pr-0">
                    <i class="las la-times fs-24 text-gray hov-text-blue has-transition"></i>
                </button>
            </div>
        </div>

        <div class="right-offcanvas-body position-absolute h-100 px-30px inventory-offcanvas-body">
            <div class="pb-5px">
                <div class="row gutters-5 mt-3">
                    <div class="col-md-6">
                        <select class="form-control aiz-selectpicker" name="coupon_category"
                            onchange="couponFilterProducts()" data-placeholder="{{ translate('Choose Category') }}"
                            data-live-search="true">
                            <option value="">{{ translate('Choose Category') }}</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->getTranslation('name') }}</option>
                                @foreach($category->childrenCategories as $childCategory)
                                    @include('categories.child_category', ['child_category' => $childCategory])
                                @endforeach
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <input type="text" class="form-control" name="coupon_search_keyword"
                            onkeyup="couponFilterProducts()" placeholder="{{ translate('Search by Product Name') }}">
                    </div>
                </div>

                <div class="mt-3" id="coupon-products-list"></div>
            </div>
        </div>

        <div class="w-100 px-30px position-absolute bottom-0 bg-white right-offcavas-footer pt-20px pb-20px">
            <div class="d-flex justify-content-end footer-btn">
                <button type="button" class="d-block fs-14 fw-700 py-10px mr-2 cancel" onclick="closeRightcanvas()">
                    {{ translate('Cancel') }}
                </button>
                <button type="button" class="d-block fs-14 fw-700 py-10px save" onclick="addSelectedToDiscountTable()">
                    {{ translate('Add Selected') }}
                </button>
            </div>
        </div>
    </div>
    <div id="rightOffcanvasOverlay" class="position-fixed top-0 left-0 h-100 w-100"></div>
@endsection
@section('script')

    <script type="text/javascript">

        function coupon_form() {
            var coupon_type = $('#coupon_type').val();
            $.post('{{ route('coupon.get_coupon_form') }}', {
                _token: '{{ csrf_token() }}',
                coupon_type: coupon_type
            }, function (data) {
                $('#coupon_form').html(data);
                if ($('.aiz-date-range').length) {
                    $('.aiz-date-range').daterangepicker();
                }
                AIZ.plugins.bootstrapSelect('refresh');
            });
        }

        @if($errors->any())
            coupon_form();
        @endif


        const rightOffcanvas = document.getElementById('rightOffcanvas');
        const overlay = document.getElementById('rightOffcanvasOverlay');

        function openCouponCanvas() {
            $('#coupon-products-list').html('');
            $('select[name=coupon_category]').val('').trigger('change');
            $('input[name=coupon_search_keyword]').val('');
            rightOffcanvas.classList.add('active');
            overlay.classList.add('active');
            document.body.classList.add('body-no-scroll');
        }

        function closeCouponCanvas() {
            rightOffcanvas.classList.remove('active');
            overlay.classList.remove('active');
            document.body.classList.remove('body-no-scroll');
        }

        window.openRightcanvas = openCouponCanvas;
        window.closeRightcanvas = closeCouponCanvas;
        window.closeOffcanvas = closeCouponCanvas;

        if (overlay) overlay.addEventListener('click', closeCouponCanvas);
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeCouponCanvas();
        });

        var couponSearchTimer;
        var addedProductIds = [];

        function couponFilterProducts() {
            clearTimeout(couponSearchTimer);
            couponSearchTimer = setTimeout(function () {
                var category = $('select[name=coupon_category]').val();
                var searchKey = $('input[name=coupon_search_keyword]').val();

                $('#coupon-products-list').html(
                    '<div class="footable-loader mt-5"><span class="fooicon fooicon-loader"></span></div>'
                );

                $.post(
                    '{{ route('coupons.product_search') }}',
                    { _token: '{{ csrf_token() }}', category: category, search_key: searchKey },
                    function (data) {
                        $('#coupon-products-list').html(data);
                        
                        addedProductIds.forEach(function (id) {
                            $('.coupon-product-check[data-product-id="' + id + '"]').prop('checked', true);
                        });
                    }
                );
            }, 400);
        }

        function addSelectedToDiscountTable() {
            $('.coupon-product-check:checked').each(function () {
                var id = parseInt($(this).data('product-id'));
                var name = $(this).data('product-name');
                var img = $(this).data('product-img');
                var price = $(this).data('product-price');

                if (addedProductIds.indexOf(id) === -1) {
                    addedProductIds.push(id);
                    $('#selected-coupon-products-body').append(
                        '<tr id="coupon-product-row-' + id + '">' +
                        '<td class="py-2">' +
                        '<div class="d-flex align-items-center">' +
                        '<img class="size-48px img-fit border border-gray-200 overflow-hidden mr-3 flex-shrink-0"' +
                        ' src="' + img + '"' +
                        ' onerror="this.src=\'{{ static_asset('assets/img/placeholder.jpg') }}\'">' +
                        '<span>' + name + '</span>' +
                        '</div>' +
                        '<input type="hidden" name="product_ids[]" value="' + id + '">' +
                        '</td>' +
                        '<td class="py-2" style="vertical-align:middle;white-space:nowrap;">' + price + '</td>' +
                        '<td class="py-2" style="vertical-align:middle;">' +
                        '<button type="button" class="w-35px h-35px rounded-1 bg-danger border-0 text-white"' +
                        ' onclick="removeCouponProduct(' + id + ')">' +
                        '<i class="las la-trash fs-16"></i>' +
                        '</button>' +
                        '</td>' +
                        '</tr>'
                    );
                }
            });

            $('#selected-coupon-products-wrapper').show();
            closeCouponCanvas();
        }

        function removeCouponProduct(id) {
            $('#coupon-product-row-' + id).remove();
            addedProductIds = addedProductIds.filter(function (i) { return i !== id; });
            if (addedProductIds.length === 0) {
                $('#selected-coupon-products-wrapper').hide();
            }
        }

    </script>

@endsection
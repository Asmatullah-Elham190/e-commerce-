@extends('backend.layouts.app')

@section('content')

    <div class="row add-product-page-content">
        <div class="col-lg-10 mx-auto">
            <div class="mb-3">
                <a href="{{ route('flash_deals.index') }}" class="fs-14 fw-500 text-reset hov-text-blue has-transition">
                    <i class="las la-arrow-left fs-14"></i>
                    {{translate('Back to Flash Deals')}}
                </a>
            </div>
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{translate('Flash Deal Information')}}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('flash_deals.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group row">
                                    <label class="col-12 control-label" for="name">{{translate('Title')}}</label>
                                    <div class="col-12">
                                        <input type="text" placeholder="{{translate('Title')}}" id="name" name="title"
                                            class="form-control" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-12 control-label" for="start_date">{{translate('Date')}}</label>
                                    <div class="col-12">
                                        <input type="text" class="form-control aiz-date-range" name="date_range"
                                            placeholder="{{ translate('Select Date') }}" data-time-picker="true"
                                            data-format="DD-MM-Y HH:mm:ss" data-separator=" to " autocomplete="off"
                                            required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group row">
                                    <label class="col-12 control-label" for="signinSrEmail">{{translate('Banner')}}</label>
                                    <div class="col-12">
                                        <div class="img-upload-container">
                                            <div class="input-group file-upload-input border border-dashed border-gray-400 rounded-1 w-120px h-120px d-flex align-items-center justify-content-center"
                                                data-toggle="aizuploader" data-type="image" style="margin-left: -10px;">
                                                <div
                                                    class="form-control p-0 border-0 d-flex align-items-center justify-content-center">
                                                    <img src="{{ static_asset('assets/img/plus-lg.svg') }}"
                                                        class="w-40px h-40px w-md-64px h-md-64px" alt="generate Icon">
                                                </div>
                                                <input type="hidden" name="thumbnail_img" class="selected-files">
                                            </div>
                                            <div class="file-preview box sm">
                                            </div>
                                        </div>
                                        <span
                                            class="small text-muted">{{ translate('This image is shown as cover banner in flash deal details page. Minimum dimensions required: 436px width X 443px height.') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label class="col-12 control-label" for="products">{{translate('Products')}}</label>
                            <div class="col-12">
                                <button type="button"
                                    class="bg-transparent d-block w-100 py-2 px-3 border border-dashed border-gray-400 rounded-1 d-flex align-items-center justify-content-center file-upload-input text-reset hov-text-blue "
                                    onclick='openRightcanvas("")'>
                                    <i class="las la-plus"></i>
                                    Add Product
                                </button>
                            </div>
                        </div>
                        <div class="alert alert-danger">
                            {{ translate('Any product with a discount or included in another flash deal will not be displayed here.') }}
                        </div>
                        <br>
                        <div class="form-group" id="discount_table">
                        </div>
                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-primary">{{translate('Save')}}</button>
                        </div>
                    </form>
                </div>
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
                        <select class="form-control aiz-selectpicker" name="flash_deal_category"
                            onchange="flashDealFilterProducts()" data-placeholder="{{ translate('Choose Category') }}"
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
                    {{-- Seller --}}
                    <div class="col-md-6">
                        <select class="form-control aiz-selectpicker" name="flash_deal_seller"
                            onchange="flashDealFilterProducts()" data-placeholder="{{ translate('Choose Seller') }}"
                            data-live-search="true">
                            <option value="">
                                {{ translate('Choose Seller') }}
                            </option>
                            @foreach($sellers as $seller)
                                <option value="{{ $seller->id }}">{{ $seller->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                {{-- Search --}}
                <div class="row gutters-5 mt-3">
                    <div class="col-md-12">
                        <input type="text" class="form-control" name="flash_deal_search_keyword"
                            onkeyup="flashDealFilterProducts()" placeholder="{{ translate('Search by Product Name') }}">
                    </div>
                </div>

                <div class="mt-3" id="flash-deal-products-list"></div>
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

        const rightOffcanvas = document.getElementById('rightOffcanvas');
        const overlay = document.getElementById('rightOffcanvasOverlay');

        function openRightcanvas() {
            rightOffcanvas.classList.add('active');
            overlay.classList.add('active');
            document.body.classList.add('body-no-scroll');
            $('#flash-deal-products-list').html('');
        }
        function closeRightcanvas() {
            rightOffcanvas.classList.remove('active');
            overlay.classList.remove('active');
            document.body.classList.remove('body-no-scroll');
            $('#flash-deal-products-list').html('');
            $('select[name=flash_deal_category]').val('').trigger('change');
            $('select[name=flash_deal_seller]').val('').trigger('change');
            $('input[name=flash_deal_search_keyword]').val('');
        }
        function closeOffcanvas() { closeRightcanvas(); }

        if (overlay) overlay.addEventListener('click', closeRightcanvas);
        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeRightcanvas(); });

        let flashDealSearchTimer;

        function flashDealFilterProducts() {
            clearTimeout(flashDealSearchTimer);
            flashDealSearchTimer = setTimeout(function () {
                const category = $('select[name=flash_deal_category]').val();
                const seller = $('select[name=flash_deal_seller]').val();
                const searchKey = $('input[name=flash_deal_search_keyword]').val();

                $('#flash-deal-products-list').html(
                    '<div class="footable-loader mt-5"><span class="fooicon fooicon-loader"></span></div>'
                );

                $.post(
                    '{{ route('flash_deals.product_search') }}',
                    {
                        _token: '{{ csrf_token() }}',
                        category: category,
                        seller: seller,
                        search_key: searchKey
                    },
                    function (data) {
                        $('#flash-deal-products-list').html(data);
                        addedProductIds.forEach(function (id) {
                            $('.flash-deal-product-check[data-product-id="' + id + '"]').prop('checked', true);
                        });
                    }
                );
            }, 400);
        }

        let addedProductIds = new Set();

        function addSelectedToDiscountTable() {
            const newIds = [];

            $('.flash-deal-product-check:checked').each(function () {
                const id = $(this).attr('data-product-id');
                if (!addedProductIds.has(id)) {
                    addedProductIds.add(id);
                    newIds.push(id);
                }
            });

            if (newIds.length > 0) {
                $.post(
                    '{{ route('flash_deals.product_discount') }}',
                    { _token: '{{ csrf_token() }}', product_ids: newIds },
                    function (data) {
                        const existing = $('#discount_table table');
                        if (existing.length === 0) {
                            $('#discount_table').html(data);
                        } else {
                            const newRows = $(data).find('tbody tr');
                            existing.find('tbody').append(newRows);
                        }
                        if (typeof AIZ !== 'undefined' && AIZ.plugins.bootstrapSelect) {
                            AIZ.plugins.bootstrapSelect();
                        }
                    }
                );
            }

            closeRightcanvas();
        }

        initFooTable();

        function clearDiscountInput(productId) {
            $('#discount-input-' + productId).val(0);
            saveDiscountAjax(productId);
        }

        function removeDiscountRow(productId) {
            $('#discount-row-' + productId).remove();
            addedProductIds.delete(String(productId));
            if ($('#discount_table tbody tr').length === 0) {
                $('#discount_table').html('');
            }
        }

        function saveDiscountAjax(productId) {
            if (typeof flashDealId === 'undefined' || !flashDealId) return;

            const discount = $('#discount-input-' + productId).val();
            const discountType = $('#discount-type-' + productId).val();

            showSaveMsg(productId, '{{ translate("Saving...") }}');

            $.post(
                '{{ route('flash_deals.update_product_discount') }}',
                {
                    _token: '{{ csrf_token() }}',
                    product_id: productId,
                    discount: discount,
                    discount_type: discountType,
                    flash_deal_id: flashDealId
                },
                function (response) {
                    if (response.success) {
                        showSaveMsg(productId, '✓ {{ translate("Saved") }}', 1500);
                    } else {
                        showSaveMsg(productId, '✗ {{ translate("Failed") }}', 2000);
                    }
                }
            ).fail(function () {
                showSaveMsg(productId, '✗ {{ translate("Failed") }}', 2000);
            });
        }

        function showSaveMsg(productId, text, hideAfter = 0) {
            const $msg = $('#save-msg-' + productId);
            $('#save-msg-text-' + productId).text(text);
            $msg.show();
            if (hideAfter > 0) {
                setTimeout(function () { $msg.hide(); }, hideAfter);
            }
        }

        let discountSaveTimers = {};

        $(document).on('input', '.discount-input', function () {
            const productId = $(this).data('product-id');
            const type      = $('#discount-type-' + productId).val();
            const unitPrice = parseFloat($(this).data('unit-price'));
            let val         = parseFloat($(this).val());

            if (type === 'amount' && val > unitPrice) {
                $(this).val(unitPrice);
                val = unitPrice;
            } else if (type === 'percent' && val > 100) {
                $(this).val(100);
                val = 100;
            }

            clearTimeout(discountSaveTimers[productId]);
            discountSaveTimers[productId] = setTimeout(function () {
                saveDiscountAjax(productId);
            }, 800);
        });

        $(document).on('change', '.discount-type-select', function () {
            const productId = $(this).data('product-id');
            const type      = $(this).val();
            const unitPrice = $('#discount-input-' + productId).data('unit-price');

            if (type === 'amount') {
                $('#discount-input-' + productId).attr('max', unitPrice);
            } else {
                $('#discount-input-' + productId).attr('max', 100);
            }

            saveDiscountAjax(productId);
        });
    </script>
@endsection
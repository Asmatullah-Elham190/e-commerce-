<!-- Rating Card Start -->
<div class="rating-card-wrapper mt-4">
    @foreach ($reviews as $key => $review)
    @php
    $customerName = null;
    $customerAvatar = null;
    if($review->type == "real"){
    $customerName = $review->user != null ? $review->user->name : translate('Use is Not Available');
    $customerAvatar = $review->user != null ? uploaded_asset($review->user->avatar_original) : static_asset('assets/img/placeholder.jpg');
    }
    else{
    $customerName = $review->custom_reviewer_name;
    $customerAvatar = uploaded_asset($review->custom_reviewer_image);
    }
    @endphp
    <!--Single-->
    <div class="rating-card py-4 review-item" data-index="{{ $loop->index }}">
        <div class="row">
            <div class="col-12 col-md-4 col-lg-3">
                <div class="d-flex mb-4 mb-md-0">
                    <div
                        class="w-64px h-64px rounded-1 overflow-hidden d-flex flex-shrink-0 align-items-center justify-content-center">
                        <img class="img-fluid w-100 h-100 object-fit-cover object-position-center" 
                        src="{{ $customerAvatar }}" alt="{{ $customerName }}"
                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                    </div>
                    <div class="pl-20px">
                        <p class="m-0 fs-14 fw-bold text-dark">{{ $customerName }}</p>
                        <span class="fs-12 fw-400 text-gray">{{ date('d-m-Y', strtotime($review->created_at)) }}</span>
                        <div class="">
                            <!-- Review ratting -->
                            <span class="rating d-flex align-items-center">
                                @for ($i = 0; $i < $review->rating; $i++)
                                    <i class="las la-star active"></i>
                                @endfor
                                @for ($i = 0; $i < 5 - $review->rating; $i++)
                                    <i class="las la-star"></i>
                                @endfor
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-8 col-lg-9">
                <span class="fs-14 fw-400 text-dark">{{ $review->comment }}</span>
                <!-- Product Variation -->
                <div class="mt-4 d-flex flex-wrap product-variation-wrapper">
                    <!--Single-->
                    @if($review->photos != null)
                    @foreach (explode(',', $review->photos) as $photo)
                    <a class="product-variation-card w-60px w-lg-90px h-60px h-lg-90px cursor-pointer bg-soft-light border border-light-gray has-transition rounded-1 overflow-hidden d-flex flex-shrink-0 align-items-center justify-content-center"
                        href="javascript:void(0);" onclick="showReviewImageModal('{{ uploaded_asset($photo) }}', '{{ json_encode(array_map('uploaded_asset', explode(',', $review->photos))) }}')">
                        <img class="img-fit h-100 lazyload has-transition"
                            src="{{ static_asset('assets/img/placeholder.jpg') }}"
                            data-src="{{ uploaded_asset($photo) }}"
                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                    </a>
                    @endforeach
                    @endif
                </div>
                <!-- Variation -->
                @php
                $OrderDetail = get_order_details_by_review($review);
                @endphp
                @if ($OrderDetail && $OrderDetail->variation)
                <span class="fs-12 fw-400 text-gray">{{ translate('Variation :') }} {{ $OrderDetail->variation }}</span>
                @endif
            </div>
        </div>
    </div>
    @endforeach
    
</div>
<!-- Rating Card End -->


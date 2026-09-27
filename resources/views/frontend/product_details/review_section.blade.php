<section id="reviewsRatings">
    <div class="reviews-ratings-container py-20px px-30px border bg-white border-light-gray rounded-2">
        <p class="fs-20 fw-bold text-dark">{{ translate('Reviews & Ratings') }}</p>
        <!-- Reviews & Ratings Show -->
        <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
            <div class="d-flex flex-wrap align-items-center">
                <h4 class="my-0 mr-4 fs-48 fw-bold text-dark">{{ number_format($detailedProduct->rating, 1) }}</h4>
                <div class="d-flex align-items-center">
                    <div class="rating d-flex align-items-center mr-2">
                        {{ renderStarRating($detailedProduct->rating) }}
                    </div>
                    @php
                        $total = 0;
                        $total += $detailedProduct->reviews->where('status', 1)->count();
                    @endphp
                    <p class="m-0 fs-14 fw-bold text-dark">{{ translate('Total Review') }} <span>{{ $total }}</span></p>
                </div>
            </div>
            <div class="mt-3 mt-md-0">
                <button type="button"
                    onclick="openReviewOffcanvas('{{ $detailedProduct->id }}', '{{ $order->id ?? ''}}')" 
                    class="py-20px px-30px fs-14 fw-bold text-dark rounded-2 border border border-light-gray hov-text-orange d-flex align-items-center bg-white has-transition">
                    <span>{{ translate('Rate this Product') }}</span>
                    <span>
                        <i class="las la-long-arrow-alt-right fs-20 mt-1"></i>
                    </span>
                </button>
            </div>
        </div>

        @if($detailedProduct->reviews()->where('status', 1)->count() > 0)
        <!-- Reviews & Ratings Show by Filter Start -->
        <div class="d-flex flex-wrap align-items-center justify-content-between mb-2">
            <div>
                <span class="fs-14 fw-400 text-gray">{{ translate('Filter by Star Rating') }}</span>
                <div class="filter-rating-wrapper d-flex align-items-center mt-2">
                    <label data-value="5"
                        class="rating-point rating-point-select w-40px h-40px d-flex align-items-center justify-content-center rounded-1 border border-1 border-gray-300 bg-white cursor-pointer">
                        <input type="radio" name="5" value="5">
                        <div class="d-flex align-items-center">
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                    viewBox="0 0 14 14">
                                    <path id="Path_45203" data-name="Path 45203"
                                        d="M-56.6,55.024c-.5.372-3.694-1.989-4.306-1.994s-3.844,2.3-4.336,1.921.665-4.293.481-4.9-3.279-3.116-3.085-3.724,4.1-.664,4.6-1.037,1.818-4.228,2.43-4.222,1.872,3.882,2.364,4.263,4.4.5,4.587,1.114S-56.806,49.5-57,50.112-56.1,54.651-56.6,55.024Z"
                                        transform="translate(67.851 -41.064)" fill="#292933" />
                                </svg>

                            </span>
                            <span class="fs-14 fw-400 text-dark pl-1 mt-1">5</span>
                        </div>
                    </label>
                    <label data-value="4"
                        class="rating-point rating-point-select w-40px h-40px d-flex align-items-center justify-content-center rounded-1 border border-1 border-gray-300 bg-white cursor-pointer">
                        <input type="radio" name="4" value="4">
                        <div class="d-flex align-items-center">
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                    viewBox="0 0 14 14">
                                    <path id="Path_45203" data-name="Path 45203"
                                        d="M-56.6,55.024c-.5.372-3.694-1.989-4.306-1.994s-3.844,2.3-4.336,1.921.665-4.293.481-4.9-3.279-3.116-3.085-3.724,4.1-.664,4.6-1.037,1.818-4.228,2.43-4.222,1.872,3.882,2.364,4.263,4.4.5,4.587,1.114S-56.806,49.5-57,50.112-56.1,54.651-56.6,55.024Z"
                                        transform="translate(67.851 -41.064)" fill="#292933" />
                                </svg>

                            </span>
                            <span class="fs-14 fw-400 text-dark pl-1 mt-1">4</span>
                        </div>
                    </label>
                    <label data-value="3"
                        class="rating-point rating-point-select w-40px h-40px d-flex align-items-center justify-content-center rounded-1 border border-1 border-gray-300 bg-white cursor-pointer">
                        <input type="radio" name="3" value="3">
                        <div class="d-flex align-items-center">
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                    viewBox="0 0 14 14">
                                    <path id="Path_45203" data-name="Path 45203"
                                        d="M-56.6,55.024c-.5.372-3.694-1.989-4.306-1.994s-3.844,2.3-4.336,1.921.665-4.293.481-4.9-3.279-3.116-3.085-3.724,4.1-.664,4.6-1.037,1.818-4.228,2.43-4.222,1.872,3.882,2.364,4.263,4.4.5,4.587,1.114S-56.806,49.5-57,50.112-56.1,54.651-56.6,55.024Z"
                                        transform="translate(67.851 -41.064)" fill="#292933" />
                                </svg>

                            </span>
                            <span class="fs-14 fw-400 text-dark pl-1 mt-1">3</span>
                        </div>
                    </label>
                    <label data-value="2"
                        class="rating-point rating-point-select w-40px h-40px d-flex align-items-center justify-content-center rounded-1 border border-1 border-gray-300 bg-white cursor-pointer">
                        <input type="radio" name="2" value="2">
                        <div class="d-flex align-items-center">
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                    viewBox="0 0 14 14">
                                    <path id="Path_45203" data-name="Path 45203"
                                        d="M-56.6,55.024c-.5.372-3.694-1.989-4.306-1.994s-3.844,2.3-4.336,1.921.665-4.293.481-4.9-3.279-3.116-3.085-3.724,4.1-.664,4.6-1.037,1.818-4.228,2.43-4.222,1.872,3.882,2.364,4.263,4.4.5,4.587,1.114S-56.806,49.5-57,50.112-56.1,54.651-56.6,55.024Z"
                                        transform="translate(67.851 -41.064)" fill="#292933" />
                                </svg>

                            </span>
                            <span class="fs-14 fw-400 text-dark pl-1 mt-1">2</span>
                        </div>
                    </label>
                    <label data-value="1"
                        class="rating-point rating-point-select w-40px h-40px d-flex align-items-center justify-content-center rounded-1 border border-1 border-gray-300 bg-white cursor-pointer">
                        <input type="radio" name="1" value="1">
                        <div class="d-flex align-items-center">
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                    viewBox="0 0 14 14">
                                    <path id="Path_45203" data-name="Path 45203"
                                        d="M-56.6,55.024c-.5.372-3.694-1.989-4.306-1.994s-3.844,2.3-4.336,1.921.665-4.293.481-4.9-3.279-3.116-3.085-3.724,4.1-.664,4.6-1.037,1.818-4.228,2.43-4.222,1.872,3.882,2.364,4.263,4.4.5,4.587,1.114S-56.806,49.5-57,50.112-56.1,54.651-56.6,55.024Z"
                                        transform="translate(67.851 -41.064)" fill="#292933" />
                                </svg>

                            </span>
                            <span class="fs-14 fw-400 text-dark pl-1 mt-1">1</span>
                        </div>
                    </label>
                </div>
            </div>
            {{-- sort by images btn --}}
            <div class="row">

                <div class="w-100 w-md-auto col-12 col-md-5 mb-2 md-mb-0">
                    <a href="javascript:void(0)" onclick="reviewByImages()" class="btn review-sort-by-images hover-border-dark mr-2 rounded-1 border border-1 border-gray-300 bg-white cursor-pointer"> {{ translate('Sort by Image') }} </a>
                </div>

                <div class="w-100 w-md-auto col-12 col-md-7">
                    <label for="sortBy" class="fs-14 fw-400 text-gray d-block text-md-right">{{ translate('Sort by') }}</label>
                    <div class="custom-select-wrapper w-100">
                        <select class="aiz-selectpicker" id="sortBy" onchange="reviewBySort(this.value)"
                            class="form-control px-15px py-10px fs-14 fw-400 cursor-pointer bg-white text-dark border border-1 border-gray-300 rounded-1">
                            <option value="newest" selected class="cursor-pointer">{{ translate('Newest') }}</option>
                            <option value="oldest" class="cursor-pointer">{{ translate('Oldest') }}</option>
                            <option value="higest" class="cursor-pointer">{{ translate('Highest Rating') }}</option>
                            <option value="lowest" class="cursor-pointer">{{ translate('Lowest Rating') }}</option>
                        </select>
                    </div>
                </div>
            </div>
            
        </div>
        @else
            <div class="text-center">
                <h5 class="fs-16 fw-bold text-gray">{{ translate('No reviews found!') }}</h5>
                <span>
                    <svg xmlns="http://www.w3.org/2000/svg" height="48px" viewBox="0 -960 960 960" width="48px" fill="#e3e3e3"><path d="M626-533q22.5 0 38.25-15.75T680-587q0-22.5-15.75-38.25T626-641q-22.5 0-38.25 15.75T572-587q0 22.5 15.75 38.25T626-533Zm-292 0q22.5 0 38.25-15.75T388-587q0-22.5-15.75-38.25T334-641q-22.5 0-38.25 15.75T280-587q0 22.5 15.75 38.25T334-533Zm146.17 116Q413-417 358.5-379.5T278-280h53q22-42 62.17-65 40.18-23 87.5-23 47.33 0 86.83 23.5T630-280h52q-25-63-79.83-100-54.82-37-122-37ZM480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-400Zm0 340q142.38 0 241.19-98.81Q820-337.63 820-480q0-142.38-98.81-241.19T480-820q-142.37 0-241.19 98.81Q140-622.38 140-480q0 142.37 98.81 241.19Q337.63-140 480-140Z"/></svg>
                </span>
            </div>
        @endif
        <!-- Reviews & Ratings Show by Filter End -->
        {{--@include('frontend.product_details.reviews')--}}
        <div class="reviews-area">

        </div>
        

        
        <!-- See More Button -->
         @if($detailedProduct->reviews()->count() > 3)
         <div id="seeMoreReviews" >
            <div class="d-flex justify-content-end mt-3">
                <button type="button" id="see-more-btn" class="see-more-btn d-flex align-items-center px-30px py-10px fs-14 fw-400 text-blue bg-transparent bg-white border-0 hov-bg-blue has-transition hov-text-white rounded-pill" data-limit="0">
                    <span class="pr-1">{{ translate('See more') }}</span>
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="13.486" height="8" viewBox="0 0 13.486 8">
                            <path id="Path_45208" data-name="Path 45208" d="M260.593-618.15l-6.743-6.743,1.257-1.257,5.486,5.486,5.486-5.486,1.257,1.257Z" transform="translate(-253.85 626.15)" fill="#0080fe" />
                        </svg>
                    </span>
                </button>
            </div>
         </div>
        @endif
    </div>
</section>

<div class="modal fade" id="reviewImageModal" tabindex="-1" role="dialog" aria-labelledby="reviewImageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content ">
            <div class="modal-body">
                <div style="position: relative;">
                    <img id="modalReviewImage" src="" class="img-fluid" style="max-height: 40vh; width:100%; object-fit: contain;" alt="Review Image">

                    <button class="shadow-lg btn btn-circle btn-icon" id="prevImageBtn" style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%);">
                        <i class="las la-arrow-left"></i>
                    </button>
                    <button class="shadow-lg btn btn-circle btn-icon" id="nextImageBtn" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%);">
                        <i class="las la-arrow-right"></i>
                    </button>
                    <button type="button" class="shadow-lg btn btn-circle btn-icon" data-dismiss="modal" style="position: absolute; top:-15px; right: -15px;">x</button>
                </div>
            </div>
        </div>
    </div>
</div>

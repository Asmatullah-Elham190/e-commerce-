<section class="mb-5 pb-3">
    <div class="container">
        @foreach ($categories as $key => $category)
            <div class="mb-4 bg-white rounded-2 border border-gray-300 hov-border-blue has-transition  all-category-card">

                <div class="border-bottom px-4 py-3">
                    <a href="{{ route('products.category', $category->slug) }}" class="text-dark d-flex align-items-center">
                        <div class="size-60px overflow-hidden p-1 border mr-3">
                            <img src="{{ uploaded_asset($category->banner) }}" alt="" class="img-fit h-100">
                        </div>
                        <div class="text-reset fs-16 fs-md-20 fw-700 hov-text-primary">
                            {{ $category->getTranslation('name') }}
                        </div>
                    </a>
                </div>
                @if ($category->childrenCategories->count() > 0)
                <div class="px-4 pb-3">
                    <div class="row gutters-12">
                        <div class="col-xxl-9 col-xl-8 col-lg-8 col-12">
                            <div class="row row-cols-xl-4 row-cols-lg-3 row-cols-md-3 row-cols-sm-2 row-cols-1 gutters-16">
                                @php
                                    $categoryBannerMap = [];
                                    $categoryIds = json_decode(get_setting('main_category_id', null), true) ?? [];
                                    $categoryBanners = json_decode(get_setting('main_category_banner', null), true) ?? [];
                                    foreach ($categoryIds as $index => $id) {
                                        if (!empty($categoryBanners[$index])) {
                                            $categoryBannerMap[$id] = $categoryBanners[$index];
                                        }
                                    }
                                @endphp
                                @foreach ($category->childrenCategories as $key => $child_category)
                                    <div class="col text-left mt-2">
                                        <h6 class="text-dark mt-2 text-truncate">
                                            <a class="text-reset fw-700 fs-14 hov-text-primary"
                                                title="{{ $child_category->getTranslation('name') }}"
                                                href="{{ route('products.category', $child_category->slug) }}">
                                                {{ $child_category->getTranslation('name') }}
                                            </a>
                                        </h6>

                                        <ul
                                            class="mt-3 mb-2 list-unstyled has-transition mh-100">
                                            @foreach ($child_category->childrenCategories as $key => $second_level_category)
                                                <li class="text-dark mb-2">
                                                    <i class="las la-angle-right fs-12 text-reset opacity-70"></i>
                                                    <a class="text-reset fw-400 fs-14 hov-text-primary animate-underline-primary"
                                                        href="{{ route('products.category', $second_level_category->slug) }}">
                                                        {{ $second_level_category->getTranslation('name') }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-xxl-3 col-xl-4 col-lg-4 col-12 mt-3">
                            <a href="{{ route('products.category', $category->slug) }}">
                                <div
                                    class="w-100 h-200px h-lg-400px border rounded-2 hov-scale-img hov-opacity-70 has-transition overflow-hidden">
                                    <img src="{{ uploaded_asset($categoryBannerMap[$category->id] ?? static_asset('assets/img/placeholder.jpg')) }}"
                                        class="img-fit w-100 h-100 has-transition" alt="Fashion Clothes Rack">
                                </div>
                            </a>
                        </div>

                    </div>

                </div>
                @endif
            </div>
        @endforeach
    </div>
</section>
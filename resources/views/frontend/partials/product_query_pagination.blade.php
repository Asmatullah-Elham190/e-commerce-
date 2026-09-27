<h5 class="fs-16 fw-bold text-dark">{{ translate('Other Questions') }}</h5>
<div class="d-flex flex-column pt-20px other-question">
    <!--Single Question-->
    @forelse ($product_queries as $product_query)
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
            <span class="fs-12 text-gray fw-400">{{ $product_query->user->name }} </span>
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

            if($product_query->product->added_by == 'seller'){
                $product_shop_name= $product_query->product->user->shop->name;
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
    @empty
    <div class="d-flex">
        <p class="m-0 fs-14 text-gray mr-2">{{ translate('No queries have been asked to the seller yet') }}</p>
        <span>
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M620-520q25 0 42.5-17.5T680-580q0-25-17.5-42.5T620-640q-25 0-42.5 17.5T560-580q0 25 17.5 42.5T620-520Zm-280 0q25 0 42.5-17.5T400-580q0-25-17.5-42.5T340-640q-25 0-42.5 17.5T280-580q0 25 17.5 42.5T340-520Zm140 100q-68 0-123.5 38.5T276-280h66q22-37 58.5-58.5T480-360q43 0 79.5 21.5T618-280h66q-25-63-80.5-101.5T480-420Zm0 340q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-400Zm0 320q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Z"/></svg>
        </span>
    </div>
    @endforelse

    <!-- Pagination -->
    <div class="aiz-pagination product-queries-pagination py-2 d-flex justify-content-end">
        {{ $product_queries->links() }}
    </div>
</div>
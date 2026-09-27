<div class="card-body">
    <table class="table mb-0" id="aiz-data-table">
        <thead>
            <tr>
                @if (auth()->user()->can('can_set_category_wise_discount'))
                    <th>
                        <div class="form-group">
                            <div class="aiz-checkbox-inline">
                                <label class="aiz-checkbox pt-5px d-block">
                                    <input type="checkbox" class="check-all">
                                    <span class="aiz-square-check"></span>
                                </label>
                            </div>
                        </div>
                    </th>
                @else
                    <th>#</th>
                @endif
                <th class="hide-md text-uppercase fs-10 fs-md-12 fw-700 text-secondary">
                    {{ translate('Icon') }}
                </th>
                <th class="text-uppercase fs-10 fs-md-12 fw-700 text-secondary">
                    {{ translate('Name') }}
                </th>
                <th class="hide-md text-uppercase fs-10 fs-md-12 fw-700 text-secondary">
                    {{ translate('Parent') }}
                </th>
                <th class="hide-md text-uppercase fs-10 fs-md-12 fw-700 text-secondary">
                    {{ translate('Inhouse') }}
                </th>
                <th class="hide-md text-uppercase fs-10 fs-md-12 fw-700 text-secondary">
                    {{ translate('Seller') }}
                </th>
                <th class="hide-sm text-uppercase fs-10 fs-md-12 fw-700 text-secondary">
                    {{ translate('Discount') }} (%)
                </th>
                <th class="hide-sm text-uppercase fs-10 fs-md-12 fw-700 text-secondary">
                    {{ translate('Date Range') }}
                </th>
            </tr>
        </thead>
        <tbody>
            @forelse ($categories as $key => $category)
                @php
                    $allMatch = $category->sellerDiscounts->every(function($discount) use ($category) {
                        return 
                            $discount->discount == $category->discount &&
                            $discount->discount_start_date == $category->discount_start_date &&
                            $discount->discount_end_date == $category->discount_end_date;
                    });
                @endphp
                <tr class="data-row">
                    <td class="align-middle w-100px">
                        <div>
                            <button type="button"
                                class="toggle-plus-minus-btn border-0 bg-blue fs-14 fw-500 text-white p-0 align-items-center justify-content-center">+</button>
                        </div>
                        @if (auth()->user()->can('can_set_category_wise_discount'))
                            <div class="form-group d-inline-block mb-2">
                                <label class="aiz-checkbox">
                                    <input type="checkbox" class="check-one" name="id[]"value="{{ $category->id }}">
                                    <span class="aiz-square-check"></span>
                                </label>
                            </div>
                        @else
                            <div class="form-group d-inline-block">
                                {{ $key + 1 + ($categories->currentPage() - 1) * $categories->perPage() }}</div>
                        @endif
                    </td>
                    <td class="hide-md align-middle" data-label="Icon">
                        <div class="w-100px w-md-100px">
                            @if($category->icon != null)
                                <span class="avatar avatar-square avatar-sm border border-gray-400">
                                    <img src="{{ uploaded_asset($category->icon) }}" alt="{{translate('icon')}}">
                                </span>
                            @else
                                —
                            @endif
                        </div>
                    </td>
                    <td class="align-middle" data-label="Name">
                        <div class="row gutters-5 w-200px w-md-200px pr-4">
                            <div class="col">
                                <span class="text-dark fs-14 fw-400">
                                    {{ $category->getTranslation('name') }}
                                    @if($category->digital == 1)
                                        <img src="{{ static_asset('assets/img/digital_tag.png') }}" alt="{{translate('Digital')}}"
                                            class="ml-2 h-25px" style="cursor: pointer;" title="Digital">
                                    @endif
                                </span>
                            </div>
                        </div>
                    </td>
                    <td class="hide-md align-middle" data-label="Parent">
                        <div class="w-200px w-md-200px">
                            <span
                                class="text-dark fs-14 fw-400">
                                @php
                                    $parent = \App\Models\Category::where('id', $category->parent_id)->first();
                                @endphp
                                @if ($parent != null)
                                    {{ $parent->getTranslation('name') }}
                                @else
                                    —
                                @endif
                            </span>
                        </div>
                    </td>
                    <td class="hide-md align-middle" data-label="Inhouse">
                        <div class="w-100px w-md-100px">
                            <span
                                class="text-dark fs-14 fw-400">
                                {{ $category->products->where('added_by', 'admin')->count()}}
                            </span>
                        </div>
                    </td>
                    <td class="hide-md align-middle" data-label="Seller">
                        <div class="w-100px w-md-100px">
                            <span class="text-dark fs-14 fw-400">
                                {{ $category->products->where('added_by', 'seller')->count() }}
                            </span>
                            <label class="aiz-switch aiz-switch-success mb-0">
                                <input id="seller_product_discount_{{ $category->id }}" type="checkbox"   onchange="trigger_alert_switch(this, '{{ $key }}')"
                                    {{ $allMatch ? 'checked' : '' }}>
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </td>
                    @php
                        $start_date = $category->discount_start_date ? date('d-m-Y H:i:s', $category->discount_start_date) : null;
                        $end_date   = $category->discount_end_date ? date('d-m-Y H:i:s', $category->discount_end_date) : null;
                    @endphp
                    <td class="hide-sm align-middle w-lg-250px pr-3" data-label="Discount">
                        <div class="custom-input-pen-clear-field pl-3 pr-2 border border-2 bg-light border-light rounded-1 has-transition w-100 w-sm-300px mr-1">
                            <div class="d-flex align-items-center justify-between">
                                <div class="flex-grow-1">
                                    <input type="number" value="{{ $category->discount }}" 
                                        data-category-id="{{ $category->id }}"
                                        class="form-control px-0 text-blue fs-12 fw-bold bg-transparent border-0 discount-input"
                                        onkeydown="handleDiscountEnter(event, this)">
                                </div>
                                <div class="text-center">
                                    <button type="button" class="border-0 bg-transparent" onclick="clearField(this, 'discount')">
                                        <svg id="pen-icon" xmlns="http://www.w3.org/2000/svg" width="12"
                                            height="12" viewBox="0 0 12 12">
                                            <path id="_50637d5a60d7f283537860671a4b78a6"
                                                data-name="50637d5a60d7f283537860671a4b78a6"
                                                d="M12.2,6.567l-5.949,5.95a2.49,2.49,0,0,1-1.157.655l-2.282.571a.5.5,0,0,1-.6-.6l.571-2.282A2.49,2.49,0,0,1,3.437,9.7l5.949-5.95Zm1.409-4.226a1.992,1.992,0,0,1,0,2.818l-.705.7L10.09,3.045l.705-.7A1.992,1.992,0,0,1,13.613,2.341Z"
                                                transform="translate(-2.196 -1.758)" fill="#a5a5b8" />
                                        </svg>
                                        <span class="fs-10 fw-400 text-blue">{{translate('Clear')}}</span>
                                    </button>
                                </div>
                            </div>
                            <!-- Tooltips Message -->
                            <div class="input-field-message bg-dark mt-1 position-absolute py-1 px-2 rounded-1">
                                <span class="fs-12 text-white fw-300">{{translate('Type and press enter to save')}}</span>
                            </div>
                        </div>
                    </td>
                    <td class="hide-sm align-middle w-lg-250px pr-3" data-label="Date Range">
                        <div class="custom-input-pen-clear-field pl-3 pr-2 border border-2 bg-light border-light rounded-1 has-transition w-100 w-sm-300px mr-1">
                            <div class="d-flex align-items-center justify-between">
                                <div class="flex-grow-1">
                                    <input type="text" value="{{ $start_date && $end_date ? $start_date . ' to ' . $end_date : '' }}" placeholder="{{translate('Select Date')}}" data-category-id="{{ $category->id }}"
                                        class="form-control aiz-date-range px-0 text-blue fs-12 fw-bold bg-transparent border-0 discount-date-range-input" placeholder="{{translate('Select Date')}}" data-time-picker="true" data-format="DD-MM-Y HH:mm:ss" data-separator=" to " autocomplete="off">
                                </div>
                                <div class="text-center">
                                    <button type="button" class="border-0 bg-transparent" onclick="clearField(this, 'discount-date-range')">
                                        <svg id="pen-icon" xmlns="http://www.w3.org/2000/svg" width="12"
                                            height="12" viewBox="0 0 12 12">
                                            <path id="_50637d5a60d7f283537860671a4b78a6"
                                                data-name="50637d5a60d7f283537860671a4b78a6"
                                                d="M12.2,6.567l-5.949,5.95a2.49,2.49,0,0,1-1.157.655l-2.282.571a.5.5,0,0,1-.6-.6l.571-2.282A2.49,2.49,0,0,1,3.437,9.7l5.949-5.95Zm1.409-4.226a1.992,1.992,0,0,1,0,2.818l-.705.7L10.09,3.045l.705-.7A1.992,1.992,0,0,1,13.613,2.341Z"
                                                transform="translate(-2.196 -1.758)" fill="#a5a5b8" />
                                        </svg>
                                        <span class="fs-10 fw-400 text-blue">{{translate('Clear')}}</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" class="text-center py-5">
                        <div class="w-100">
                            <h5 class="fs-16 fw-bold text-gray">{{ translate('No Data found!') }}</h5>
                            <i class="las la-frown fs-48 text-soft-white"></i>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="aiz-pagination">
        {{ $categories->appends(request()->input())->links() }}
    </div>
</div>
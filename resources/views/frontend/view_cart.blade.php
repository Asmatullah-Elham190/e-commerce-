@extends('frontend.layouts.app')

@section('content')
    <!-- Cart Details -->
    <section class="my-4" id="cart-details">
        @include('frontend.partials.cart.cart_details', ['carts' => $carts])
    </section>
@endsection

@section('script')
    <script type="text/javascript">
        function removeFromCartView(e, key) {
            e.preventDefault();
	    window.dataLayer = window.dataLayer || [];
	    dataLayer.push({
	        event: "remove_from_cart",
	        ecommerce: {
	            items: [{
	                item_id: key
	            }]
	        }
	    });
            removeFromCart(key);
        }

        function updateQuantity(key, element) {
            const qty = parseInt(element.value || 1);
            window.dataLayer = window.dataLayer || [];
            dataLayer.push({
                event: "update_cart_quantity",
                ecommerce: {
                    items: [{
                        item_id: key,
                        quantity: qty
                    }]
                }
            });
        
            $.post('{{ route('cart.updateQuantity') }}', {
                _token: AIZ.data.csrf,
                id: key,
                quantity: element.value
            }, function(data) {
                updateNavCart(data.nav_cart_view, data.cart_count);
                $('#cart-details').html(data.cart_view);
                AIZ.extra.plusMinus();
            });
        }

        // Cart item selection
        $(document).on("change", ".check-all", function() {
            $('.check-one:checkbox').prop('checked', this.checked);
            updateCartStatus();
        });
        $(document).on("change", ".check-seller", function() {
            var value = this.value;
            $('.check-one-'+value+':checkbox').prop('checked', this.checked);
            updateCartStatus();
        });
        $(document).on("change", ".check-one[name='id[]']", function(e) {
            e.preventDefault();
            updateCartStatus();
        });
        function updateCartStatus() {
            $('.aiz-refresh').addClass('active');
            let product_id = [];
            $(".check-one[name='id[]']:checked").each(function() {
                product_id.push($(this).val());
            });

            $.post('{{ route('cart.updateCartStatus') }}', {
                _token: AIZ.data.csrf,
                product_id: product_id
            }, function(data) {
                $('#cart-details').html(data);
                AIZ.extra.plusMinus();
                $('.aiz-refresh').removeClass('active');
            });
            // GA4 - Track selected cart items
            $(".check-one[name='id[]']:checked").each(function() {
                product_id.push($(this).val());
            });
            window.dataLayer = window.dataLayer || [];
            dataLayer.push({
                event: "select_cart_items",
                ecommerce: {
                    items: product_id.map(id => ({ item_id: id }))
                }
            });
        }

        // coupon apply
        $(document).on("click", "#coupon-apply", function() {
            @if (Auth::check())
                @if(Auth::user()->user_type != 'customer')
                    AIZ.plugins.notify('warning', "{{ translate('Please Login as a customer to apply coupon code.') }}");
                    return false;
                @endif

                var data = new FormData($('#apply-coupon-form')[0]);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: "POST",
                    url: "{{ route('checkout.apply_coupon_code') }}",
                    data: data,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(data, textStatus, jqXHR) {
                        AIZ.plugins.notify(data.response_message.response, data.response_message.message);
                        $("#cart_summary").html(data.html);
                    }
                });
            @else
                $('#login_modal').modal('show');
            @endif
        });

        // coupon remove
        $(document).on("click", "#coupon-remove", function() {
            @if (Auth::check() && Auth::user()->user_type == 'customer')
                var data = new FormData($('#remove-coupon-form')[0]);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: "POST",
                    url: "{{ route('checkout.remove_coupon_code') }}",
                    data: data,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(data, textStatus, jqXHR) {
                        $("#cart_summary").html(data);
                    }
                });
            @endif
        });

    </script>
    @php
        // Build GA4 view_cart 
        $items = [];
        $computedTotal = 0.0;
    
        foreach ($carts as $cartItem) {
            $product = get_single_product($cartItem['product_id'] ?? null);
            if (!$product) { continue; }
    
            $categoryname = optional($product->category)->name
                ?? optional($product->categories->first())->name
                ?? 'Uncategorized';
    
            $price    = (float) ($product->unit_price ?? 0);
            $discount = (float) ($product->discount ?? 0);
            $qty      = (int)   ($cartItem['quantity'] ?? $cartItem['qty'] ?? 1);
    
            $items[] = [
                'item_id'        => (string) $product->id,
                'item_name'      => $product->getTranslation('name') ?? $product->name,
                'item_category'  => $categoryname,
                'item_list_name' => 'Related Products',
                'price'          => $price,
                'discount'       => $discount,
                'quantity'       => $qty,
            ];
    
            $computedTotal += max($price - $discount, 0) * $qty;
        }
    
        $cartValue = isset($total) ? (float) $total : $computedTotal;
    
        $ga4ViewCart = [
            'event'     => 'view_cart',
            'ecommerce' => [
                'currency' => "{{ get_system_currency()->code }}",
                'value'    => $cartValue,
                'items'    => $items,
            ],
        ];
    @endphp

    @if (get_setting('google_analytics') == 1)
    <script>
      window.dataLayer = window.dataLayer || [];
      dataLayer.push(@json($ga4ViewCart));
    </script>
    @endif
@endsection

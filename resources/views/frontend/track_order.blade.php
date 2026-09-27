@php
    $layout = 'frontend.layouts.app';

    if (addon_is_activated('portfolio_system')) {
        $user = auth()->user();
        if (!$user || $user->verification_status == 0 || optional($user->shop)->verification_status == 0) {
            $layout = 'frontend.layouts.portfolio_app';
        }
    }
    $order_tracking = json_decode(get_setting('order_tracking'), true);
    $business_info = json_decode(get_setting('business_info'), true);
@endphp

@extends($layout)

@section('content')
    <section class="pt-4 mb-4">
        <div class="col-md-10 col-lg-8 col-xl-6 mx-auto">
            <h1 class="fs-24 fw-bold text-center">{{ translate('Track Order') }}</h1>

            <form action="{{ route('orders.track') }}" method="GET">
                <div class="bg-white border border-1 border-gray-300 rounded-2 p-3 p-md-4">
                    <p class="fs-14 fw-bold mb-1">{{ translate('Check Your Order Status') }}</p>
                    <div class="form-box-content">
                        <div class="form-group d-flex flex-wrap flex-md-nowrap">
                            <input type="text" class="form-control fs-14 fw-400 rounded-1 flex-grow-1 mr-2"
                                placeholder="Tracking Code" name="tracking_code" required>
                            <button type="submit" class="btn btn-primary rounded-1 w-150px mt-2 mt-md-0">
                                {{ translate('Track Order') }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            @if (isset($order) && $order != null)
                @php
                    $shippingAddress = json_decode($order->shipping_address, true);
                    $status = $order->delivery_status;
                    $isPaid = $order->payment_status == 'paid';
                    $isCOD = $order->payment_type == 'cash_on_delivery';
                    $isCancelled = $status == 'cancelled';

                    $statusUpdateTimes = [];
                    if ($order->delivery_status_update_datetime) {
                        $statusUpdateTimes = json_decode($order->delivery_status_update_datetime, true);
                    }

                    $seller = $order->seller_id ? \App\Models\User::find($order->seller_id) : null;
                    if ($seller && $seller->user_type == 'seller') {
                        $fromAddress = get_seller_address($order);
                    } else {
                        $fromAddress =
                            ($business_info['address'] ?? '') .
                            ', ' .
                            ($business_info['state'] ?? '') .
                            ', ' .
                            ($business_info['country'] ?? '');
                    }

                    $toAddress =
                        ($shippingAddress['address'] ?? '') .
                        ', ' .
                        ($shippingAddress['city'] ?? '') .
                        ', ' .
                        ($shippingAddress['country'] ?? '');

                    $shippingName = null;
                    if ($order->shipping_method == 'shiprocket') {
                        $shippingName = 'Shiprocket';
                    } elseif ($order->shipping_method == 'steadfast') {
                        $shippingName = 'Steadfast';
                    } elseif ($order->shipping_method == 'pathao') {
                        $shippingName = 'Pathao';
                    } elseif ($order->shipping_method == 'redx') {
                        $shippingName = 'redx';
                    }

                    $isThirdParty = !is_null($shippingName);

                    $normalSteps = [
                        'pending' => 'Order Placed',
                        'confirmed' => 'Order Confirmed',
                        'picked_up' => 'Order Picked Up',
                        'on_the_way' => 'On The Way',
                        'delivered' => 'Delivered',
                    ];
                    $normalStatusOrder = array_keys($normalSteps);

                    if ($isCancelled) {
                        $lastCompletedIndex = -1;
                        foreach ($normalStatusOrder as $idx => $stepKey) {
                            if (isset($statusUpdateTimes[$stepKey])) {
                                $lastCompletedIndex = $idx;
                            }
                        }
                        $currentIndex = $lastCompletedIndex;
                    } else {
                        $currentIndex = array_search($status, $normalStatusOrder);
                    }

                    function formatDateTime($dateTimeString)
                    {
                        if (!$dateTimeString)
                            return null;
                        try {
                            $timestamp = is_numeric($dateTimeString) ? (int) $dateTimeString : strtotime($dateTimeString);
                            if ($timestamp === false || $timestamp <= 0)
                                return null;
                            return [
                                'date' => date('d M Y', $timestamp),
                                'time' => date('h.i A', $timestamp)
                            ];
                        } catch (Exception $e) {
                            return null;
                        }
                    }
                @endphp

                <div class="border border-1 border-gray-300 rounded-3 mt-4 p-3 p-md-4 bg-white">

                    <div class="bg-soft-blue rounded-pill py-2 px-3 d-flex align-items-center">
                        <div class="w-50px h-50px rounded-circle overflow-hidden mr-3 flex-shrink-0">
                            @if (isset($order_tracking['logo']) && $order_tracking['logo'] != '')
                                <img src="{{ uploaded_asset($order_tracking['logo']) }}" alt="Delivery Icon">
                            @else
                                <img src="{{ static_asset('assets/img/placeholder.jpg') }}" alt="Delivery Icon">
                            @endif
                        </div>
                        <div>
                            <h6 class="fs-15 fw-700 mb-1 text-dark">
                                {{ isset($order_tracking['name']) && $order_tracking['name'] != ''
                ? $order_tracking['name']
                : $business_info['company_name'] ?? 'Express Delivery' }}
                            </h6>
                            <span class="fs-13 text-secondary">
                                {{ isset($order_tracking['moto']) && $order_tracking['moto'] != ''
                ? $order_tracking['moto']
                : 'From Checkout to Doorstep' }}
                            </span>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-start border-bottom border-gray-300 py-4">
                        <div class="address-box">
                            <p class="fs-16 text-dark fw-400 mb-1">{{ translate('From') }}</p>
                            <p class="fs-12 fw-400 m-0 text-gray w-120px">{{ $fromAddress }}</p>
                        </div>
                        <div class="flex-grow-1 mx-3 mx-md-4 mt-1 border-1 border-gray-300 border-dashed"></div>
                        <div class="address-box text-right">
                            <p class="fs-16 text-dark fw-400 mb-1">{{ translate('To') }}</p>
                            <p class="fs-12 fw-400 m-0 text-gray w-120px">{{ $toAddress }}</p>
                        </div>
                    </div>

                    @if ($isThirdParty)
                        <div class="order-tracking-timeline mt-4 d-flex flex-column-reverse">

                            @php
                                $pendingTime = formatDateTime($statusUpdateTimes['pending'] ?? null);
                                $confirmedTime = formatDateTime($statusUpdateTimes['confirmed'] ?? null);
                                $pickedUpTime = formatDateTime($statusUpdateTimes['picked_up'] ?? null);
                                $onTheWayTime = formatDateTime($statusUpdateTimes['on_the_way'] ?? null);
                                $deliveredTime = formatDateTime($statusUpdateTimes['delivered'] ?? null);
                                $cancelledTime = formatDateTime($statusUpdateTimes['cancelled'] ?? null);
                                $paymentTime = formatDateTime($statusUpdateTimes['payment_time'] ?? null);
                            @endphp

                            <div class="tracking-timeline-completed d-flex">
                                <div class="d-flex flex-column align-items-center mr-3">
                                    <div
                                        class="w-25px h-25px position-relative d-flex align-items-center justify-content-center z-2">
                                        <div class="w-15px h-15px rounded-circle bg-success border border-2 border-success"></div>
                                    </div>
                                </div>
                                <div class="pb-4">
                                    <div class="d-flex align-items-center">
                                        <h6 class="fs-14 fw-700 mb-1">{{ translate('Order Placed') }}</h6>
                                        @if ($isCOD)
                                            <span class="badge bg-orange text-white ml-3 fs-10 px-3 rounded-pill"
                                                style="margin-top:-3px">COD</span>
                                        @endif
                                    </div>
                                    <div>
                                        <span class="fs-12 fw-400 text-muted pr-3">{{ date('d M Y', $order->date) }}</span>
                                        <span class="fs-12 fw-400 text-muted">{{ date('h.i A', $order->date) }}</span>
                                    </div>
                                </div>
                            </div>

                            @if ($isPaid && !$isCOD)
                                @php $showPaymentLine = $status != 'pending'; @endphp
                                <div class="tracking-timeline-completed d-flex">
                                    <div class="d-flex flex-column align-items-center mr-3">
                                        <div
                                            class="w-25px h-25px position-relative d-flex align-items-center justify-content-center z-2">
                                            <div class="w-15px h-15px rounded-circle bg-success border border-2 border-success"></div>
                                        </div>
                                        @if ($showPaymentLine)
                                            <div class="connector-line line-solid bg-success"></div>
                                        @endif
                                    </div>
                                    <div class="{{ $showPaymentLine ? 'pb-4' : '' }}">
                                        <h6 class="fs-14 fw-700 mb-1">{{ translate('Payment Successful') }}</h6>
                                        <div>
                                            @if ($paymentTime && $paymentTime['date'])
                                                <span class="fs-12 fw-400 text-muted pr-3">{{ $paymentTime['date'] }}</span>
                                                <span class="fs-12 fw-400 text-muted">{{ $paymentTime['time'] }}</span>
                                            @else
                                                <span class="fs-12 fw-400 text-muted pr-3">{{ date('d M Y', $order->date) }}</span>
                                                <span class="fs-12 fw-400 text-muted">{{ date('h.i A', $order->date) }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @php
                                $isConfirmed = in_array($status, ['confirmed', 'picked_up', 'on_the_way', 'delivered']) || ($isCancelled && isset($statusUpdateTimes['confirmed']));
                                $isConfirmedCurrent = !$isCancelled && $status == 'pending';
                            @endphp
                            <div
                                class="{{ $isConfirmed ? 'tracking-timeline-completed' : ($isConfirmedCurrent ? 'tracking-timeline-running' : '') }} d-flex">
                                <div class="d-flex flex-column align-items-center mr-3">
                                    <div
                                        class="w-25px h-25px position-relative d-flex align-items-center justify-content-center z-2">
                                        @if ($isConfirmed)
                                            <div class="w-15px h-15px rounded-circle bg-success border border-2 border-success"></div>
                                        @elseif($isConfirmedCurrent)
                                            <div
                                                class="order-tracking-pulse-ring position-absolute w-25px h-25px rounded-circle border border-1 border-gray-500">
                                            </div>
                                            <div class="w-15px h-15px rounded-circle bg-gray border border-2 border-gray-500"></div>
                                        @else
                                            <div class="w-15px h-15px rounded-circle bg-gray border border-2 border-gray-300"></div>
                                        @endif
                                    </div>
                                    <div class="connector-line {{ $isConfirmed ? 'line-solid bg-success' : 'line-dashed' }}"></div>
                                </div>
                                <div class="pb-4">
                                    <h6 class="fs-14 fw-700 mb-1">{{ translate('Order Confirmed') }}</h6>
                                    @if ($isConfirmed && $confirmedTime)
                                        <div>
                                            <span class="fs-12 fw-400 text-muted pr-3">{{ $confirmedTime['date'] }}</span>
                                            <span class="fs-12 fw-400 text-muted">{{ $confirmedTime['time'] }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            @php
                                $assignedStatuses = ['picked_up', 'on_the_way', 'delivered'];
                                $isAssigned = in_array($status, $assignedStatuses) || ($isCancelled && isset($statusUpdateTimes['picked_up']));
                                $isAssignedCurrent = !$isCancelled && in_array($status, ['confirmed']);
                            @endphp
                            <div
                                class="{{ $isAssigned ? 'tracking-timeline-completed' : ($isAssignedCurrent ? 'tracking-timeline-running' : '') }} d-flex">
                                <div class="d-flex flex-column align-items-center mr-3">
                                    <div
                                        class="w-25px h-25px position-relative d-flex align-items-center justify-content-center z-2">
                                        @if ($isAssigned)
                                            <div class="w-15px h-15px rounded-circle bg-success border border-2 border-success"></div>
                                        @elseif($isAssignedCurrent)
                                            <div
                                                class="order-tracking-pulse-ring position-absolute w-25px h-25px rounded-circle border border-1 border-gray-500">
                                            </div>
                                            <div class="w-15px h-15px rounded-circle bg-gray border border-2 border-gray-500"></div>
                                        @else
                                            <div class="w-15px h-15px rounded-circle bg-gray border border-2 border-gray-300"></div>
                                        @endif
                                    </div>
                                    <div class="connector-line {{ $isAssigned ? 'line-solid bg-success' : 'line-dashed' }}"></div>
                                </div>
                                <div class="pb-4">
                                    <h6 class="fs-14 fw-700 mb-1">{{ translate('Order Assigned to') }} {{ $shippingName }}</h6>
                                    @if ($isAssigned && $pickedUpTime)
                                        <div>
                                            <span class="fs-12 fw-400 text-muted pr-3">{{ $pickedUpTime['date'] }}</span>
                                            <span class="fs-12 fw-400 text-muted">{{ $pickedUpTime['time'] }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            @php
                                $isDelivered = $status == 'delivered';
                                $isDeliveredRunning = !$isCancelled && $isAssigned && !$isDelivered;
                                $finalLineClass = $isCancelled ? 'line-solid bg-danger' : ($isDelivered ? 'line-solid bg-success' : 'line-dashed');
                            @endphp

                            <div
                                class="{{ $isCancelled ? 'tracking-timeline-cancelled' : ($isDelivered ? 'tracking-timeline-completed' : ($isDeliveredRunning ? 'tracking-timeline-running' : '')) }} d-flex">
                                <div class="d-flex flex-column align-items-center mr-3">
                                    <div
                                        class="w-25px h-25px position-relative d-flex align-items-center justify-content-center z-2">
                                        @if ($isCancelled)
                                            <div class="w-15px h-15px rounded-circle bg-danger border border-2 border-danger"></div>
                                        @elseif ($isDelivered)
                                            <div class="w-15px h-15px rounded-circle bg-success border border-2 border-success"></div>
                                        @elseif($isDeliveredRunning)
                                            <div
                                                class="order-tracking-pulse-ring position-absolute w-25px h-25px rounded-circle border border-1 border-gray-500">
                                            </div>
                                            <div class="w-15px h-15px rounded-circle bg-gray border border-2 border-gray-500"></div>
                                        @else
                                            <div class="w-15px h-15px rounded-circle bg-gray border border-2 border-gray-300"></div>
                                        @endif
                                    </div>
                                    <div class="connector-line {{ $finalLineClass }}"></div>
                                </div>
                                <div class="pb-4">
                                    @if($isCancelled)
                                        <h6 class="fs-14 fw-700 mb-1 text-danger">{{ translate('Order Cancelled') }}</h6>
                                        @if($cancelledTime)
                                            <div>
                                                <span class="fs-12 fw-400 text-muted pr-3">{{ $cancelledTime['date'] }}</span>
                                                <span class="fs-12 fw-400 text-muted">{{ $cancelledTime['time'] }}</span>
                                            </div>
                                        @endif
                                    @else
                                        <h6 class="fs-14 fw-700 mb-1">{{ translate('Delivered') }}</h6>
                                        @if ($isDelivered && $deliveredTime)
                                            <div>
                                                <span class="fs-12 fw-400 text-muted pr-3">{{ $deliveredTime['date'] }}</span>
                                                <span class="fs-12 fw-400 text-muted">{{ $deliveredTime['time'] }}</span>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>

                        </div>


                    @else
                        <div class="order-tracking-timeline mt-4 d-flex flex-column">
                            @php
                                $allSteps = [];
                                if ($isPaid && !$isCOD) {
                                    $allSteps[] = ['key' => 'payment', 'label' => 'Payment Successful'];
                                }

                                foreach ($normalSteps as $stepKey => $stepLabel) {
                                    if ($isCancelled) {
                                        if (isset($statusUpdateTimes[$stepKey])) {
                                            $allSteps[] = ['key' => $stepKey, 'label' => $stepLabel];
                                        }
                                    } else {
                                        $allSteps[] = ['key' => $stepKey, 'label' => $stepLabel];
                                    }
                                    if ($isCOD && $stepKey === 'delivered') {
                                        $allSteps[] = ['key' => 'payment', 'label' => 'Payment Successful'];
                                    }
                                }

                                if ($isCancelled) {
                                    $allSteps[] = ['key' => 'cancelled', 'label' => 'Order Cancelled'];
                                }

                                $reversedSteps = array_reverse($allSteps, true);
                                $totalSteps = count($reversedSteps);
                                $counter = 0;
                            @endphp

                            @foreach ($reversedSteps as $step)
                                @php
                                    $counter++;
                                    $stepKey = $step['key'];
                                    $stepLabel = $step['label'];

                                    $isPaymentStep = ($stepKey == 'payment');
                                    $isCancelledStep = ($stepKey == 'cancelled');
                                    $isLastInUI = ($counter == $totalSteps);
                                    $showLine = !$isLastInUI;

                                    if ($isCancelledStep) {
                                        $isCompleted = true;
                                        $isCurrent = false;
                                        $stepTime = formatDateTime($statusUpdateTimes['cancelled'] ?? null);
                                        $lineClass = 'line-solid bg-danger';

                                    } elseif ($isPaymentStep) {
                                        $isCompleted = $isCOD 
                                        ? isset($statusUpdateTimes['delivered']) 
                                        : ($isPaid && !$isCOD);
                                        $isCurrent = false;
                                        $stepTime = formatDateTime($statusUpdateTimes['payment_time'] ?? null);
                                        if (!$stepTime || !$stepTime['date']) {
                                            $stepTime = ['date' => date('d M Y', $order->date), 'time' => date('h.i A', $order->date)];
                                        }
                                        $lineClass = $isCompleted ? 'line-solid bg-success' : 'line-dashed';

                                    } else {
                                        $stepIndex = array_search($stepKey, $normalStatusOrder);
                                        $isCompleted = ($stepIndex <= $currentIndex);
                                        $isCurrent = !$isCancelled && ($stepIndex == $currentIndex + 1) && !$isCompleted;
                                        $stepTime = formatDateTime($statusUpdateTimes[$stepKey] ?? null);
                                        $lineClass = $isCompleted ? 'line-solid bg-success' : 'line-dashed';
                                    }
                                @endphp

                                <div
                                    class="{{ $isCancelledStep ? 'tracking-timeline-cancelled' : ($isCurrent ? 'tracking-timeline-running' : 'tracking-timeline-completed') }} d-flex">
                                    <div class="d-flex flex-column align-items-center mr-3">
                                        <div
                                            class="w-25px h-25px position-relative d-flex align-items-center justify-content-center z-2">
                                            @if ($isCancelledStep)
                                                <div class="w-15px h-15px rounded-circle bg-danger border border-2 border-danger"></div>
                                            @elseif($isCurrent)
                                                <div
                                                    class="order-tracking-pulse-ring position-absolute w-25px h-25px rounded-circle border border-1 border-gray-500">
                                                </div>
                                                <div class="w-15px h-15px rounded-circle bg-gray border border-2 border-gray-500"></div>
                                            @elseif($isCompleted)
                                                <div class="w-15px h-15px rounded-circle bg-success border border-2 border-success"></div>
                                            @else
                                                <div class="w-15px h-15px rounded-circle bg-gray border border-2 border-gray-300"></div>
                                            @endif
                                        </div>
                                        @if ($showLine)
                                            <div class="connector-line {{ $lineClass }}"></div>
                                        @endif
                                    </div>

                                    <div class="{{ $isLastInUI ? '' : 'pb-4' }}">
                                        <div class="d-flex align-items-center">
                                            <h6 class="fs-14 fw-700 mb-1 {{ $isCancelledStep ? 'text-danger' : '' }}">
                                                {{ translate($stepLabel) }}
                                            </h6>
                                            @if (!$isPaymentStep && !$isCancelledStep && $stepKey == 'pending' && $isCOD)
                                                <span class="badge bg-orange text-white ml-3 fs-10 px-3 rounded-pill"
                                                    style="margin-top:-3px">COD</span>
                                            @endif
                                        </div>

                                        @if ($isCompleted || $isCurrent)
                                            <div>
                                                @if ($stepTime && $stepTime['date'])
                                                    <span class="fs-12 fw-400 text-muted pr-3">{{ $stepTime['date'] }}</span>
                                                    <span class="fs-12 fw-400 text-muted">{{ $stepTime['time'] }}</span>
                                                @elseif(!$isPaymentStep && !$isCancelledStep && $stepKey == 'pending')
                                                    <span class="fs-12 fw-400 text-muted pr-3">{{ date('d M Y', $order->date) }}</span>
                                                    <span class="fs-12 fw-400 text-muted">{{ date('h.i A', $order->date) }}</span>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>

                            @endforeach
                        </div>
                    @endif

                </div>

                @auth
                    <div class="border border-1 border-gray-300 rounded-3 mt-4 p-3 p-md-4 bg-white">
                        <div class="d-flex flex-wrap flex-sm-nowrap align-items-center justify-content-between">
                            <div class="pr-2 pr-sm-3">
                                <h6 class="fs-16 fw-bold mb-3">{{ translate('Order Summary') }}</h6>
                                <div>
                                    <span class="fs-14 fw-400 text-dark">{{ translate('Order ID') }}: </span>
                                    <span class="fs-14 fw-400 text-dark">{{ $order->code }}</span>
                                </div>
                                <div>
                                    <span class="fs-14 fw-400 text-dark">{{ translate('Payment Method') }}: </span>
                                    <span
                                        class="fs-14 fw-400 text-dark">{{ ucfirst(str_replace('_', ' ', $order->payment_type)) }}</span>
                                </div>
                                <div>
                                    <span class="fs-14 fw-400 text-dark">{{ translate('Delivery Type') }}: </span>
                                    <span
                                        class="fs-14 fw-400 text-dark">{{ ucfirst(str_replace('_', ' ', $order->shipping_type)) }}</span>
                                </div>
                                <div class="mt-3">
                                    <span class="fs-14 fw-400 text-dark">{{ $order->orderDetails->count() }}
                                        {{ translate('Products') }}</span>
                                    <div class="mt-1">
                                        <span class="fs-14 fw-400 text-dark">{{ translate('Total Amount') }}: </span>
                                        <span class="fs-14 fw-bold text-dark">{{ single_price($order->grand_total) }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-2 mt-sm-0">
                                <a href="{{ route('invoice.download', $order->id) }}"
                                    class="mb-1 border-0 bg-blue text-white fs-12 fw-600 text-center px-3 py-2 rounded-1 w-160px d-inline-block d-flex align-items-center hov-opacity-80 has-transition">
                                    <i class="las la-download pr-1 fs-16"></i>
                                    <span>{{ translate('Download Invoice') }}</span>
                                </a>
                                <a href="{{ route('purchase_history.details', encrypt($order->id)) }}"
                                    class="border-0 bg-soft-blue text-blue fs-12 fw-600 text-center px-3 py-2 rounded-1 w-160px d-inline-block mt-2 hov-opacity-80 has-transition">
                                    {{ translate('View Order Details') }}
                                </a>
                            </div>
                        </div>

                        @if ($isCOD && !$isPaid)
                            <div class="mt-3">
                                <span class="fs-16 fw-400 text-dark bg-soft-blue rounded-1 text-center px-3 py-3 d-block">
                                    {{ translate('Cash on Delivery: Pay') }} {{ single_price($order->grand_total) }}
                                    {{ translate('when your order arrives') }}
                                </span>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="mt-4">
                        <a href="{{ route('user.login') }}"
                            class="mb-1 border-0 bg-blue text-white fs-12 fw-600 text-center px-3 py-3 rounded-1 w-100 d-block d-flex align-items-center justify-content-center hov-opacity-80 has-transition">
                            <span>{{ translate('Login to view Order Summary') }}</span>
                            <i class="las la-arrow-right fs-16 pl-2"></i>
                        </a>
                    </div>
                @endauth
            @else
                <div class="border border-1 border-gray-300 rounded-3 mt-4 p-3 p-md-4 bg-white">
                    @if ($searched)
                        <p class="fs-14 text-danger text-center mt-2">
                            {{ translate('No order found. Please check the code and try again.') }}
                        </p>
                    @endif
                    <div class="d-flex flex-column align-items-center justify-content-center py-4">
                        <img src="{{ static_asset('assets/img/order-tracking-map.png') }}" class="img-fluid mb-3" alt="Map Img">
                    </div>
                </div>
            @endif

        </div>
    </section>
@endsection
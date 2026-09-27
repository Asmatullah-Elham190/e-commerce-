{{-- all_chat_modal --}}
<div class="border-bottom pb-15px px-20px d-flex align-items-center justify-content-between">
    <h5 class="fs-16 fw-700 text-truncate m-0">{{ translate('Seller Hub') }}</h5>
    <button onclick="closeglobalRightOffcanvas()" class="border-0 bg-transparent p-0">
        <i class="las la-times fs-24 text-gray hov-text-blue has-transition"></i>
    </button>
</div>

<div class="global-right-offcanvas-body w-100 px-0 py-0 d-flex flex-column"
    style="overflow: hidden !important; height: calc(100vh - 60px) !important;">

    <div class="common-sm-nav-tabs-container w-100">
        <ul class="nav nav-tabs flex-nowrap common-sm-nav-tabs px-20px pt-5px" id="common-sm-tabs" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link fs-13 fw-400 text-reset px-0 mr-3 active" id="nav-all-tab" data-toggle="tab"
                    href="#nav-all" role="tab" aria-controls="nav-all" aria-selected="true">{{ translate('All') }}</a>
            </li>

            <li class="nav-item" role="presentation">
                <a class="nav-link fs-13 fw-400 text-reset px-0 mx-3 position-relative" id="nav-notices-tab" data-toggle="tab"
                    href="#nav-notices" role="tab" aria-controls="nav-notices"
                    aria-selected="false">{{ translate('Notices') }}
                    @if ($hasUnseenNotices)
                        <span class="bg-danger rounded-circle position-absolute"
                            style="width: 5px; height: 5px; top: 6px; right: -12px;"></span>
                    @endif
                </a>
            </li>

            <li class="nav-item" role="presentation">
                <a class="nav-link fs-13 fw-400 text-reset px-0 mx-3" id="nav-requests-tab"
                    data-toggle="tab" href="#nav-requests" role="tab" aria-controls="nav-requests"
                    aria-selected="false">
                    {{ translate('Requests') }}
                </a>
            </li>

            <li class="nav-item" role="presentation">
                <a class="nav-link fs-13 fw-400 text-reset px-0 mx-3 position-relative" id="nav-promotions-tab"
                    data-toggle="tab" href="#nav-promotions" role="tab" aria-controls="nav-promotions"
                    aria-selected="false">
                    {{ translate('Promotions') }}
                    @if ($hasUnseenPromotions)
                        <span class="bg-danger rounded-circle position-absolute"
                            style="width: 5px; height: 5px; top: 6px; right: -12px;"></span>
                    @endif
                </a>
            </li>

            <li class="nav-item" role="presentation">
                <a class="nav-link fs-13 fw-400 text-reset px-0 mx-3 position-relative" id="nav-messages-tab" data-toggle="tab"
                    href="#nav-messages" role="tab" aria-controls="nav-messages"
                    aria-selected="false">{{ translate('Messages') }}
                    @if ($hasUnseenMessages)
                        <span class="bg-danger rounded-circle position-absolute"
                            style="width: 5px; height: 5px; top: 6px; right: -12px;"></span>
                    @endif
                </a>
            </li>

            <li class="nav-item mt-1 ml-3 ml-md-auto" role="presentation">
                <a class="nav-link plus-nav-link px-0 w-25px h-25px rounded-circle border-0 bg-soft-blue hov-bg-soft-light has-transition d-flex align-items-center justify-content-center"
                    id="nav-plus-tab" data-toggle="tab" href="#nav-plus" role="tab" aria-controls="nav-plus"
                    aria-selected="false">
                    <svg id="Group_40038" data-name="Group 40038" xmlns="http://www.w3.org/2000/svg" width="12"
                        height="12" viewBox="0 0 12 12">
                        <rect id="Rectangle_24889" data-name="Rectangle 24889" width="12" height="1.5"
                            transform="translate(6.75) rotate(90)" fill="#0b80fd" />
                        <rect id="Rectangle_24891" data-name="Rectangle 24891" width="12" height="1.5"
                            transform="translate(0 5.25)" fill="#0b80fd" />
                    </svg>
                </a>
            </li>
        </ul>
    </div>

    <div class="tab-content flex-grow-1 d-flex flex-column overflow-hidden" id="common-sm-tabsContent">
        <div class="tab-pane fade show active flex-grow-1 h-100" id="nav-all" role="tabpanel"
            aria-labelledby="nav-all-tab">
            <div class="d-flex flex-column justify-content-between h-100">
                <div class="flex-grow-1 c-scrollbar-light" style="overflow-y: auto;">
                    <div class="d-flex flex-column">

                        @forelse ($activities as $activity)
                            @include('seller.chats.partials._activity_item', ['activity' => $activity])
                        @empty
                            <div class="px-20px py-40px text-center mt-4">
                                <span class="fs-13 fw-400 text-gray">{{ translate('No activity found') }}</span>
                            </div>
                        @endforelse

                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade flex-grow-1 h-100" id="nav-notices" role="tabpanel" aria-labelledby="nav-notices-tab">
        </div>

        <div class="tab-pane fade flex-grow-1 h-100" id="nav-requests" role="tabpanel"
            aria-labelledby="nav-requests-tab">
        </div>

        <div class="tab-pane fade flex-grow-1 h-100" id="nav-promotions" role="tabpanel"
            aria-labelledby="nav-promotions-tab">
        </div>

        <div class="tab-pane fade flex-grow-1 h-100" id="nav-messages" role="tabpanel"
            aria-labelledby="nav-messages-tab">
            <div id="chat-single-view" class="h-100">
            </div>
        </div>

        <div class="tab-pane fade flex-grow-1 h-100" id="nav-plus" role="tabpanel" aria-labelledby="nav-plus-tab">

        </div>
    </div>
</div>
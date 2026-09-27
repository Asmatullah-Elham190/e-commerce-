{{-- plus blade  --}}
<div class="d-flex flex-column justify-content-between h-100">
    <div class="flex-grow-1 c-scrollbar-light" style="overflow-y: auto;">
        <div class="px-20px py-20px">
            <h6 class="fs-13 fw-700 text-dark mb-1">{{ translate('New Post Type') }}</h6>
            <span class="fs-11 fw-400 text-gray">{{ translate('Select your desired post type from here') }}</span>
            <!-- Nav Tab Start -->
            <div class="mt-3">
                <ul class="nav nav-tabs post-type-nav-tabs border-0 mb-4" id="postTypeTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link fs-13 fw-500 text-dark mr-2 active border border-1 border-gray-300 rounded-pill"
                            id="plus-message-tab" data-toggle="tab" href="#plus-message" role="tab" aria-controls="plus-message"
                            aria-selected="true">Message</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link fs-13 fw-500 text-dark mx-2 border border-1 border-gray-300 rounded-pill"
                            id="plus-promotion-tab" data-toggle="tab" href="#plus-promotion" role="tab" aria-controls="plus-promotion"
                            aria-selected="false">Promotion</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link fs-13 fw-500 text-dark mx-2 border border-1 border-gray-300 rounded-pill"
                            id="plus-notice-tab" data-toggle="tab" href="#plus-notice" role="tab" aria-controls="plus-notice"
                            aria-selected="false">Notices</a>
                    </li>
                </ul>
                <!-- Tab Content -->
                <div class="tab-content mt-3" id="postTypeTabContent">
                    <!-- Message Tab Pane -->
                    <div class="tab-pane fade show active" id="plus-message" role="tabpanel" aria-labelledby="plus-message-tab">
                        
                    </div>
                    <!-- Promotion Tab Pane -->
                    <div class="tab-pane fade" id="plus-promotion" role="tabpanel" aria-labelledby="plus-promotion-tab">
                        
                    </div>
                    <!-- Notices Tab Pane -->
                    <div class="tab-pane fade" id="plus-notice" role="tabpanel" aria-labelledby="plus-notice-tab">
                        
                    </div>
                </div>
            </div>
            <!-- Nav Tab End -->
        </div>
    </div>
    <!-- Footer Button -->
    <div class="bg-white px-20px py-4 border-top mt-auto">
        <button type="button" id="seller-hub-post-btn"
            class="fs-13 fw-700 text-dark bg-white hov-bg-light has-transition d-block text-center border border-1 border-gray-300 px-3 py-2 w-100 rounded-2">
            {{ translate('Post') }}</button>
    </div>
</div>
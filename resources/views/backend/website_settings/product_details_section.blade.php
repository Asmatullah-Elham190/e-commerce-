@extends('backend.layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-6 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Product Details Section') }}</h5>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('business_settings.update') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="form-group d-flex justify-content-between align-items-center mb-3">
							<input type="hidden" name="types[]" value="enable_product_description_section">
							<div class="d-flex align-items-center">
								<label class="aiz-switch aiz-switch-blue mb-0 pr-2">
									<input type="checkbox" name="enable_product_description_section" value="1"
										{{ (get_setting('enable_product_description_section') )?? 0 == 1 ? 'checked' : '' }}>
									<span></span>
								</label>
								<span class="d-block" style="margin-top: -6px">{{ translate('Enable Product Description Section') }}</span>
							</div>
						</div>

                        <div class="form-group d-flex justify-content-between align-items-center mb-3">
							<input type="hidden" name="types[]" value="enable_product_related_section">
							<div class="d-flex align-items-center">
								<label class="aiz-switch aiz-switch-blue mb-0 pr-2">
									<input type="checkbox" name="enable_product_related_section" value="1"
										{{ (get_setting('enable_product_related_section') )?? 0 == 1 ? 'checked' : '' }}>
									<span></span>
								</label>
								<span class="d-block" style="margin-top: -6px">{{ translate('Enable Related Product Section') }}</span>
							</div>
						</div>

                        <div class="form-group d-flex justify-content-between align-items-center mb-0">
							<input type="hidden" name="types[]" value="enable_ratings_and_review_section">
							<div class="d-flex align-items-center">
								<label class="aiz-switch aiz-switch-blue mb-0 pr-2">
									<input type="checkbox" name="enable_ratings_and_review_section" value="1"
										{{ (get_setting('enable_ratings_and_review_section') )?? 0 == 1 ? 'checked' : '' }}>
									<span></span>
								</label>
								<span class="d-block" style="margin-top: -6px">{{ translate('Enable Ratings & Reviews Section') }}</span>
							</div>
						</div>
						<small class="text-danger">{{ translate("NB: If you disable the 'Rating & Review' section, customers will no longer be able to view or submit ratings and reviews anywhere on your website.") }}</small>

                        <div class="form-group d-flex justify-content-between align-items-center mb-3 mt-3">
							<input type="hidden" name="types[]" value="enable_product_queries_section">
							<div class="d-flex align-items-center">
								<label class="aiz-switch aiz-switch-blue mb-0 pr-2">
									<input type="checkbox" name="enable_product_queries_section" value="1"
										{{ (get_setting('enable_product_queries_section') )?? 0 == 1 ? 'checked' : '' }}>
									<span></span>
								</label>
								<span class="d-block" style="margin-top: -6px">{{ translate('Enable Product Queries Section') }}</span>
							</div>
						</div>

                        <div class="form-group d-flex justify-content-between align-items-center mb-3">
							<input type="hidden" name="types[]" value="enable_frequently_bought_section">
							<div class="d-flex align-items-center">
								<label class="aiz-switch aiz-switch-blue mb-0 pr-2">
									<input type="checkbox" name="enable_frequently_bought_section" value="1"
										{{ (get_setting('enable_frequently_bought_section') )?? 0 == 1 ? 'checked' : '' }}>
									<span></span>
								</label>
								<span class="d-block" style="margin-top: -6px">{{ translate('Enable Frequently Bought Section') }}</span>
							</div>
						</div>

                        <div class="form-group d-flex justify-content-between align-items-center mb-3">
							<input type="hidden" name="types[]" value="enable_more_from_this_seller_section">
							<div class="d-flex align-items-center">
								<label class="aiz-switch aiz-switch-blue mb-0 pr-2">
									<input type="checkbox" name="enable_more_from_this_seller_section" value="1"
										{{ (get_setting('enable_more_from_this_seller_section') )?? 0 == 1 ? 'checked' : '' }}>
									<span></span>
								</label>
								<span class="d-block" style="margin-top: -6px">{{ translate('Enable More from this Seller Section') }}</span>
							</div>
						</div>

                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-primary">{{ translate('Save') }}</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

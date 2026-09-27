@extends('backend.layouts.app')

@section('content')
	<div class="page-content">
		<div class="aiz-titlebar text-left mt-2 pb-2 px-3 px-md-2rem border-bottom border-gray">
			<div class="row align-items-center">
				<div class="col">
					<h1 class="h3">{{ translate('Homepage Settings (Nexa)') }}</h1>
				</div>
			</div>
		</div>

		<div class="d-sm-flex">
			<div class="page-side-nav c-scrollbar-light px-3 py-2">
				<ul class="nav nav-tabs flex-sm-column border-0" role="tablist" aria-orientation="vertical">
					<li class="nav-item">
						<a class="nav-link" id="home-slider-tab" href="#home_slider" data-toggle="tab"
							data-target="#home_slider" type="button" role="tab" aria-controls="home_slider"
							aria-selected="true">
							{{ translate('Home Slider') }}
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="flash-deal-tab" href="#flash_deal" data-toggle="tab"
							data-target="#flash_deal" type="button" role="tab" aria-controls="flash_deal"
							aria-selected="false">
							{{ translate("Flash Deal") }}
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="todays-deal-tab" href="#todays_deal" data-toggle="tab"
							data-target="#todays_deal" type="button" role="tab" aria-controls="todays_deal"
							aria-selected="false">
							{{ translate("Today's Deal") }}
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="featured-categories-tab" href="#featured_categories" data-toggle="tab"
							data-target="#featured_categories" type="button" role="tab" aria-controls="featured_categories"
							aria-selected="false">
							{{ translate('Featured Categories') }}
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="banner-1-tab" href="#banner_1" data-toggle="tab" data-target="#banner_1"
							type="button" role="tab" aria-controls="banner_1" aria-selected="false">
							{{ translate('Banner Level 1') }}
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="featured-products-tab" href="#featured_products" data-toggle="tab" data-target="#featured_products"
							type="button" role="tab" aria-controls="featured_products" aria-selected="false">
							{{ translate('Featured Products') }}
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="best-selling-products-tab" href="#best_selling_products" data-toggle="tab" data-target="#best_selling_products"
							type="button" role="tab" aria-controls="featured_best_selling_products" aria-selected="false">
							{{ translate('Best Selling Products') }}
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="categories-tab" href="#categories" data-toggle="tab" data-target="#categories"
							type="button" role="tab" aria-controls="categories" aria-selected="false">
							{{ translate('Categories') }}
						</a>
					</li>
					@if(addon_is_activated('auction'))
						<li class="nav-item">
							<a class="nav-link" id="auction-tab" href="#auction" data-toggle="tab" data-target="#auction"
								type="button" role="tab" aria-controls="auction" aria-selected="false">
								{{ translate('Auction Products') }}
								@if (env("DEMO_MODE") == "On")
									<span class="badge badge-pill badge-secondary ml-1">{{ translate('Addon') }}</span>
								@endif
							</a>
						</li>
					@endif
					<li class="nav-item">
						<a class="nav-link" id="classifieds-tab" href="#classifieds" data-toggle="tab"
							data-target="#classifieds" type="button" role="tab" aria-controls="classifieds"
							aria-selected="false">
							{{ translate('Classifieds') }}
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="banner-2-tab" href="#banner_2" data-toggle="tab" data-target="#banner_2"
							type="button" role="tab" aria-controls="banner_2" aria-selected="false">
							{{ translate('Banner Level 2') }}
						</a>
					</li>
					@if(addon_is_activated('preorder'))
						<li class="nav-item">
							<a class="nav-link" id="classifieds-tab" href="#newestPreorder" data-toggle="tab"
								data-target="#newestPreorder" type="button" role="tab" aria-controls="newestPreorder"
								aria-selected="false">
								{{ translate('Newest Preorder Products') }}
							</a>
						</li>
					@endif
					<li class="nav-item">
						<a class="nav-link" id="shop-by-seller-tab" href="#shop_by_seller" data-toggle="tab" data-target="#shop_by_seller"
							type="button" role="tab" aria-controls="shop_by_seller" aria-selected="false">
							{{ translate('Shop By Sellers') }}
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="shop-by-brand-tab" href="#shop_by_brand" data-toggle="tab" data-target="#shop_by_brand"
							type="button" role="tab" aria-controls="shop_by_brand" aria-selected="false">
							{{ translate('Shop By Brands') }}
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="banner-3-tab" href="#banner_3" data-toggle="tab" data-target="#banner_3"
							type="button" role="tab" aria-controls="banner_3" aria-selected="false">
							{{ translate('Banner Level 3') }}
						</a>
					</li>
				</ul>
			</div>

			<div class="flex-grow-1 p-sm-3 p-lg-2rem mb-2rem mb-md-0">
				<div class="tab-content">

					<ul class="nav nav-tabs nav-fill language-bar">
						@foreach (get_all_active_language() as $key => $language)
							<li class="nav-item">
								<a class="nav-link text-reset @if ($language->code == $lang) active @endif py-3"
									href="{{route('custom-pages.edit', ['id' => $page->slug, 'lang' => $language->code, 'page' => 'home'])}}">
									<img src="{{ static_asset('assets/img/flags/' . $language->code . '.png') }}" height="11"
										class="mr-1">
									<span>{{ $language->name }}</span>
								</a>
							</li>
						@endforeach
					</ul>

					<div class="tab-pane fade" id="home_slider" role="tabpanel" aria-labelledby="home-slider-tab">
						<form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
							@csrf
							<input type="hidden" name="tab" value="home_slider">
							<input type="hidden" name="types[][{{ $lang }}]" value="home_slider_images">
							<input type="hidden" name="types[][{{ $lang }}]" value="small_home_slider_images">
							<input type="hidden" name="types[][{{ $lang }}]" value="home_slider_links">

							<div class="bg-white p-3 p-sm-2rem">
								<div class="w-100">
									<div class="fs-11 d-flex mb-2rem">
										<div>
											<svg id="_79508b4b8c932dcad9066e2be4ca34f2"
												data-name="79508b4b8c932dcad9066e2be4ca34f2"
												xmlns="http://www.w3.org/2000/svg" width="16" height="16"
												viewBox="0 0 16 16">
												<path id="Path_40683" data-name="Path 40683"
													d="M8,16a8,8,0,1,1,8-8A8.024,8.024,0,0,1,8,16ZM8,1.333A6.667,6.667,0,1,0,14.667,8,6.686,6.686,0,0,0,8,1.333Z"
													fill="#9da3ae" />
												<path id="Path_40684" data-name="Path 40684"
													d="M10.6,15a.926.926,0,0,1-.667-.333c-.333-.467-.067-1.133.667-2.933.133-.267.267-.6.4-.867a.714.714,0,0,1-.933-.067.644.644,0,0,1,0-.933A3.408,3.408,0,0,1,11.929,9a.926.926,0,0,1,.667.333c.333.467.067,1.133-.667,2.933-.133.267-.267.6-.4.867a.714.714,0,0,1,.933.067.644.644,0,0,1,0,.933A3.408,3.408,0,0,1,10.6,15Z"
													transform="translate(-3.262 -3)" fill="#9da3ae" />
												<circle id="Ellipse_813" data-name="Ellipse 813" cx="1" cy="1" r="1"
													transform="translate(8 3.333)" fill="#9da3ae" />
												<path id="Path_40685" data-name="Path 40685"
													d="M12.833,7.167a1.333,1.333,0,1,1,1.333-1.333A1.337,1.337,0,0,1,12.833,7.167Zm0-2a.63.63,0,0,0-.667.667.667.667,0,1,0,1.333,0A.63.63,0,0,0,12.833,5.167Z"
													transform="translate(-3.833 -1.5)" fill="#9da3ae" />
											</svg>
										</div>
										<div class="ml-2 text-gray">
											<div class="mb-2">
												{{ translate('Minimum dimensions required: 1920px width X 320px height.') }}
											</div>
											<div>
												{{ translate('We have limited banner height to maintain UI. We had to crop from both left & right side in view for different devices to make it responsive. Before designing banner keep these points in mind.') }}
											</div>
										</div>
									</div>
									<div class="home-slider-target">
										@php
											$home_slider_images = get_setting('home_slider_images', null, $lang);
											$small_home_slider_images = get_setting('small_home_slider_images', null, $lang);
											$home_slider_links = get_setting('home_slider_links', null, $lang);
										@endphp
										@if ($home_slider_images != null)
											@foreach (json_decode($home_slider_images, true) as $key => $value)
												<div class="p-3 p-md-4 mb-3 mb-md-2rem remove-parent"
													style="border: 1px dashed #e4e5eb;">
													<div class="row gutters-5">
														<div class="col-md">
															<label
																class="col-from-label fs-13 fw-500 mb-2">{{translate('Slider')}}</label>
															<div class="form-group mb-md-0">
																<div class="input-group" data-toggle="aizuploader"
																	data-type="image">
																	<div class="input-group-prepend">
																		<div
																			class="input-group-text bg-soft-secondary font-weight-medium">
																			{{ translate('Browse')}}
																		</div>
																	</div>
																	<div class="form-control file-amount">
																		{{ translate('Choose File') }}
																	</div>
																	<input type="hidden" name="home_slider_images[]"
																		class="selected-files"
																		value="{{ json_decode($home_slider_images, true)[$key] }}">
																</div>
																<div class="file-preview box sm">
																</div>
															</div>
														</div>
														<div class="col-md">
															<label
																class="col-from-label fs-13 fw-500 mb-2">{{translate('Slider')}} ({{translate('Small Device')}})</label>
															<div class="form-group mb-md-0">
																<div class="input-group" data-toggle="aizuploader"
																	data-type="image">
																	<div class="input-group-prepend">
																		<div
																			class="input-group-text bg-soft-secondary font-weight-medium">
																			{{ translate('Browse')}}
																		</div>
																	</div>
																	<div class="form-control file-amount">
																		{{ translate('Choose File') }}
																	</div>
																	<input type="hidden" name="small_home_slider_images[]"
																		class="selected-files"
																		value="{{ json_decode($small_home_slider_images, true)[$key] ?? ''}}">
																</div>
																<div class="file-preview box sm">
																</div>
															</div>
														</div>
														<div class="col-md">
															<label
																class="col-from-label fs-13 fw-500 mb-2">{{translate('Link')}}</label>
															<div class="form-group mb-md-0">
																<input type="text" class="form-control" placeholder="http://"
																	name="home_slider_links[]"
																	value="{{ isset(json_decode($home_slider_links, true)[$key]) ? json_decode($home_slider_links, true)[$key] : '' }}">
															</div>
														</div>
														<div class="col-md-auto">
															<div class="form-group mb-md-0">
																<button type="button"
																	class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger"
																	data-toggle="remove-parent" data-parent=".remove-parent">
																	<i class="las la-times"></i>
																</button>
															</div>
														</div>
													</div>
												</div>
											@endforeach
										@endif
									</div>
									<div class="">
										<button type="button"
											class="btn btn-block border hov-bg-soft-secondary fs-14 rounded-0 d-flex align-items-center justify-content-center"
											style="background: #fcfcfc;" data-toggle="add-more" data-content='
													<div class="p-3 p-md-4 mb-3 mb-md-2rem remove-parent" style="border: 1px dashed #e4e5eb;">
														<div class="row gutters-5">
															<!-- Image -->
															<div class="col-md">
																<label
																	class="col-from-label fs-13 fw-500 mb-2">{{translate('Slider')}}</label>
																<div class="form-group mb-md-0">
																	<div class="input-group" data-toggle="aizuploader" data-type="image">
																		<div class="input-group-prepend">
																			<div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
																		</div>
																		<div class="form-control file-amount">{{ translate('Choose File') }}</div>
																		<input type="hidden" name="home_slider_images[]" class="selected-files" value="">
																	</div>
																	<div class="file-preview box sm">
																	</div>
																</div>
															</div>
															<div class="col-md">
																<label
																	class="col-from-label fs-13 fw-500 mb-2">{{translate('Slider')}} ({{translate('Small Device')}})</label>
																<div class="form-group mb-md-0">
																	<div class="input-group" data-toggle="aizuploader" data-type="image">
																		<div class="input-group-prepend">
																			<div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
																		</div>
																		<div class="form-control file-amount">{{ translate('Choose File') }}</div>
																		<input type="hidden" name="small_home_slider_images[]" class="selected-files" value="">
																	</div>
																	<div class="file-preview box sm">
																	</div>
																</div>
															</div>
															<!-- link -->
															<div class="col-md">
																<label
																	class="col-from-label fs-13 fw-500 mb-2">{{translate('Link')}}</label>
																<div class="form-group mb-md-0">
																	<input type="text" class="form-control" placeholder="http://" name="home_slider_links[]" value="">
																</div>
															</div>
															<!-- remove parent button -->
															<div class="col-md-auto">
																<div class="form-group mb-md-0">
																	<button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger" data-toggle="remove-parent" data-parent=".remove-parent">
																		<i class="las la-times"></i>
																	</button>
																</div>
															</div>
														</div>
													</div>' data-target=".home-slider-target">
											<i class="las la-2x text-success la-plus-circle"></i>
											<span class="ml-2">{{ translate('Add New') }}</span>
										</button>
									</div>
								</div>
								<div class="mt-4 text-right">
									<button type="submit"
										class="btn btn-success w-230px btn-md rounded-2 fs-14 fw-700 shadow-success">{{ translate('Save') }}</button>
								</div>
							</div>
						</form>
					</div>

					<div class="tab-pane fade" id="flash_deal" role="tabpanel" aria-labelledby="flash-deal-tab">
						<form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
							@csrf
							<input type="hidden" name="tab" value="flash_deal">
							<div class="bg-white p-3 p-sm-2rem">
								<div class="row gutters-16">
									<div class="col-lg-12">
										<div class="w-100">

											<div class="form-group mb-2 d-flex justify-content-between align-items-center">
												@php $enable_flash_deal = get_setting('enable_flash_deal') @endphp
												<div class="d-flex align-items-center">
													<label class="aiz-switch aiz-switch-blue mb-0 pr-2">
														<input type="hidden" name="types[]" value="enable_flash_deal">
														<input type="checkbox" name="enable_flash_deal" value="1"
															{{ $enable_flash_deal == 1 ? 'checked' : '' }}>
														<span></span>
													</label>
													<span class="d-block" style="margin-top: -6px">{{ translate('Enable Flash Deal') }}</span>
												</div>
											</div>

										</div>
									</div>
								</div>
								<!-- Save Button -->
								<div class="mt-4 text-right">
									<button type="submit"
										class="btn btn-success w-230px btn-md rounded-2 fs-14 fw-700 shadow-success">{{ translate('Save') }}</button>
								</div>
							</div>
						</form>
					</div>

					<div class="tab-pane fade" id="todays_deal" role="tabpanel" aria-labelledby="todays-deal-tab">
						<form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
							@csrf
							<input type="hidden" name="tab" value="todays_deal">
							<div class="bg-white p-3 p-sm-2rem">
								<div class="row gutters-16">
									<div class="col-lg-12">
										<div class="w-100">
											<div class="form-group mb-2 d-flex justify-content-between align-items-center">
												@php $enable_todays_deal = get_setting('enable_todays_deal') @endphp
												<div class="d-flex align-items-center">
													<label class="aiz-switch aiz-switch-blue mb-0 pr-2">
														<input type="hidden" name="types[]" value="enable_todays_deal">
														<input type="checkbox" name="enable_todays_deal" value="1"
															{{ $enable_todays_deal == 1 ? 'checked' : '' }}>
														<span></span>
													</label>
													<span class="d-block" style="margin-top: -6px">{{ translate('Enable Todays Deal') }}</span>
												</div>
											</div>
										</div>
									</div>
								</div>
								@php
									$todays_deal_title_sub_text = get_setting('todays_deal_title_sub_text', null);
								@endphp
								<div class="form-group">
									<label class="col-from-label">
										{{translate('Title Sub Text')}}
									</label>
									<div class="col-12 pl-0">
										<input type="hidden" name="types[]" value="todays_deal_title_sub_text">
										<input type="text" class="form-control" name="todays_deal_title_sub_text" value="{{ $todays_deal_title_sub_text }}" placeholder="">
									</div>
								</div>
								<!-- Save Button -->
								<div class="mt-4 text-right">
									<button type="submit"
										class="btn btn-success w-230px btn-md rounded-2 fs-14 fw-700 shadow-success">{{ translate('Save') }}</button>
								</div>
							</div>
						</form>
					</div>

					<div class="tab-pane fade" id="featured_categories" role="tabpanel"
						aria-labelledby="featured-categories-tab">
						<form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
							@csrf
							<input type="hidden" name="tab" value="featured_categories">
							<div class="bg-white p-3 p-sm-2rem">
								<div class="row gutters-16">
									<!-- Featured Products Settings -->
									<div class="col-lg-12">
							
										<div class="form-group mb-2 d-flex justify-content-between align-items-center">
											@php $enable_featured_categories = get_setting('enable_featured_categories') @endphp
											<div class="d-flex align-items-center">
												<label class="aiz-switch aiz-switch-blue mb-0 pr-2">
													<input type="hidden" name="types[]" value="enable_featured_categories">
													<input type="checkbox" name="enable_featured_categories" value="1"
														{{ $enable_featured_categories == 1 ? 'checked' : '' }}>
													<span></span>
												</label>
												<span class="d-block" style="margin-top: -6px">{{ translate('Enable Featured Categories') }}</span>
											</div>
										</div>
											
									</div>
								</div>
								@php
									$featured_categories_title_sub_text = get_setting('featured_categories_title_sub_text', null);
								@endphp
								<div class="form-group">
									<label class="col-from-label">
										{{translate('Title Sub Text')}}
									</label>
									<div class="col-12 pl-0">
										<input type="hidden" name="types[]" value="featured_categories_title_sub_text">
										<input type="text" class="form-control" name="featured_categories_title_sub_text" value="{{ $featured_categories_title_sub_text }}" placeholder="">
									</div>
								</div>
								@php
									$categories = \App\Models\Category::where('featured', 1)->get();

									$saved_texts = json_decode(get_setting('featured_category_texts'), true) ?? [];
								@endphp
								<input type="hidden" name="types[]" value="featured_category_texts">
								<table class="table table-bordered aiz-table aiz-border-rl-borderless-table">
									<thead>
										<tr>
											<td width="45%">
												<span class="fs-13 fw-400 text-gray">
													{{ translate('Category') }}
												</span>
											</td>
											<td width="25%">
												<span class="fs-13 fw-400 text-gray">
													{{ translate('Text') }}
												</span>
											</td>
										</tr>
									</thead>
									<tbody>
										@foreach ($categories as $category)
											<tr>
												<td style="vertical-align: middle;">
													<span>{{ $category->name }}</span>
												</td>
												<td style="vertical-align: middle;">
													<div class="custom-input-pen-clear-field pl-3 pr-2 border border-2 bg-light border-light rounded-1 has-transition w-100 w-sm-300px mr-1">
														<div class="d-flex align-items-center justify-between">
															<div class="flex-grow-1">
																<input type="text"
																	name="featured_category_texts[{{ $category->id }}]"
																	value="{{ $saved_texts[$category->id] ?? '' }}" maxlength="60"
																	class="form-control px-0 text-blue fs-12 fw-bold bg-transparent border-0 text-input">
															</div>
														</div>
													</div>
												</td>
											</tr>
										@endforeach
									</tbody>
								</table>
								<!-- Save Button -->
								<div class="mt-4 text-right">
									<button type="submit"
										class="btn btn-success w-230px btn-md rounded-2 fs-14 fw-700 shadow-success">{{ translate('Save') }}</button>
								</div>
							</div>
						</form>
					</div>

					<div class="tab-pane fade" id="banner_1" role="tabpanel" aria-labelledby="banner-1-tab">
						<form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
							@csrf
							<input type="hidden" name="tab" value="banner_1">
							<input type="hidden" name="types[][{{ $lang }}]" value="home_banner1_images">
							<input type="hidden" name="types[][{{ $lang }}]" value="home_banner1_links">
							
							<div class="bg-white p-3 p-sm-2rem">
								<div class="row gutters-16">
									
									<div class="col-lg-12">
										<div class="form-group mb-2 d-flex justify-content-between align-items-center">
											@php $enable_banner_1 = get_setting('enable_banner_1') @endphp
											<div class="d-flex align-items-center">
												<label class="aiz-switch aiz-switch-blue mb-0 pr-2">
													<input type="hidden" name="types[]" value="enable_banner_1">
													<input type="checkbox" name="enable_banner_1" value="1"
														{{ $enable_banner_1 == 1 ? 'checked' : '' }}>
													<span></span>
												</label>
												<span class="d-block" style="margin-top: -6px">{{ translate('Enable Banner 1') }}</span>
											</div>
										</div>
									</div>

								</div>
								<div class="w-100">
									<label
										class="col-from-label fs-13 fw-500 mb-0">{{ translate('Banner & Links (Max 3)') }}</label>
									<div class="small text-muted mb-3">
										{{ translate("Minimum dimensions required: 1280px width X 320px height.") }}
									</div>

									<!-- Images & links -->
									<div class="home-banner1-target">
										@php
											$home_banner1_images = get_setting('home_banner1_images', null, $lang);
											$home_banner1_links = get_setting('home_banner1_links', null, $lang);
										@endphp
										@if ($home_banner1_images != null)
											@foreach (json_decode($home_banner1_images, true) as $key => $value)
												<div class="p-3 p-md-4 mb-3 mb-md-2rem remove-parent"
													style="border: 1px dashed #e4e5eb;">
													<div class="row gutters-5">
														<!-- Image -->
														<div class="col-md-5">
															<div class="form-group mb-md-0">
																<div class="input-group" data-toggle="aizuploader"
																	data-type="image">
																	<div class="input-group-prepend">
																		<div
																			class="input-group-text bg-soft-secondary font-weight-medium">
																			{{ translate('Browse')}}
																		</div>
																	</div>
																	<div class="form-control file-amount">
																		{{ translate('Choose File') }}
																	</div>
																	<input type="hidden" name="home_banner1_images[]"
																		class="selected-files"
																		value="{{ json_decode($home_banner1_images, true)[$key] }}">
																</div>
																<div class="file-preview box sm">
																</div>
															</div>
														</div>
														<!-- link -->
														<div class="col-md">
															<div class="form-group mb-md-0">
																<input type="text" class="form-control" placeholder="http://"
																	name="home_banner1_links[]"
																	value="{{ isset(json_decode($home_banner1_links, true)[$key]) ? json_decode($home_banner1_links, true)[$key] : '' }}">
															</div>
														</div>
														<!-- remove parent button -->
														<div class="col-md-auto">
															<div class="form-group mb-md-0">
																<button type="button"
																	class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger"
																	data-toggle="remove-parent" data-parent=".remove-parent">
																	<i class="las la-times"></i>
																</button>
															</div>
														</div>
													</div>
												</div>
											@endforeach
										@endif
									</div>

									<!-- Add button -->
									<div class="">
										<button type="button"
											class="btn btn-block border hov-bg-soft-secondary fs-14 rounded-0 d-flex align-items-center justify-content-center"
											style="background: #fcfcfc;" data-toggle="add-more" data-content='
													<div class="p-3 p-md-4 mb-3 mb-md-2rem remove-parent" style="border: 1px dashed #e4e5eb;">
														<div class="row gutters-5">
															<!-- Image -->
															<div class="col-md-5">
																<div class="form-group mb-md-0">
																	<div class="input-group" data-toggle="aizuploader" data-type="image">
																		<div class="input-group-prepend">
																			<div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
																		</div>
																		<div class="form-control file-amount">{{ translate('Choose File') }}</div>
																		<input type="hidden" name="home_banner1_images[]" class="selected-files" value="">
																	</div>
																	<div class="file-preview box sm">
																	</div>
																</div>
															</div>
															<!-- link -->
															<div class="col-md">
																<div class="form-group mb-md-0 mb-0">
																	<input type="text" class="form-control" placeholder="http://" name="home_banner1_links[]" value="">
																</div>
															</div>
															<!-- remove parent button -->
															<div class="col-md-auto">
																<div class="form-group mb-md-0">
																	<button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger" data-toggle="remove-parent" data-parent=".remove-parent">
																		<i class="las la-times"></i>
																	</button>
																</div>
															</div>
														</div>
													</div>' data-target=".home-banner1-target">
											<i class="las la-2x text-success la-plus-circle"></i>
											<span class="ml-2">{{ translate('Add New') }}</span>
										</button>
									</div>
								</div>
								<!-- Save Button -->
								<div class="mt-4 text-right">
									<button type="submit"
										class="btn btn-success w-230px btn-md rounded-2 fs-14 fw-700 shadow-success">{{ translate('Save') }}</button>
								</div>
							</div>
						</form>
					</div>

					<div class="tab-pane fade" id="featured_products" role="tabpanel" aria-labelledby="featured-products-tab">
						<form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
							@csrf
							<input type="hidden" name="tab" value="featured_products">
							<div class="bg-white p-3 p-sm-2rem">
								<div class="row gutters-16">

									<div class="col-lg-12">
										<div class="form-group mb-2 d-flex justify-content-between align-items-center">
											@php $enable_featured_products = get_setting('enable_featured_products') @endphp
											<div class="d-flex align-items-center">
												<label class="aiz-switch aiz-switch-blue mb-0 pr-2">
													<input type="hidden" name="types[]" value="enable_featured_products">
													<input type="checkbox" name="enable_featured_products" value="1"
														{{ $enable_featured_products == 1 ? 'checked' : '' }}>
													<span></span>
												</label>
												<span class="d-block" style="margin-top: -6px">{{ translate('Enable Featured Products') }}</span>
											</div>
										</div>
									</div>

								</div>
								@php
									$featured_products_title_sub_text = get_setting('featured_products_title_sub_text', null);
								@endphp
								<div class="form-group">
									<label class="col-from-label">
										{{translate('Title Sub Text')}}
									</label>
									<div class="col-12 pl-0">
										<input type="hidden" name="types[]" value="featured_products_title_sub_text">
										<input type="text" class="form-control" name="featured_products_title_sub_text" value="{{ $featured_products_title_sub_text }}" placeholder="">
									</div>
								</div>
								<!-- Save Button -->
								<div class="mt-4 text-right">
									<button type="submit"
										class="btn btn-success w-230px btn-md rounded-2 fs-14 fw-700 shadow-success">{{ translate('Save') }}</button>
								</div>
							</div>
						</form>
					</div>

					<div class="tab-pane fade" id="best_selling_products" role="tabpanel" aria-labelledby="best-selling-products-tab">
						<form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
							@csrf
							<input type="hidden" name="tab" value="best_selling_products">
							<div class="bg-white p-3 p-sm-2rem">
								<div class="row gutters-16">

									<div class="col-lg-12">
										<div class="form-group mb-2 d-flex justify-content-between align-items-center">
											@php $enable_best_selling_products = get_setting('enable_best_selling_products') @endphp
											<div class="d-flex align-items-center">
												<label class="aiz-switch aiz-switch-blue mb-0 pr-2">
													<input type="hidden" name="types[]" value="enable_best_selling_products">
													<input type="checkbox" name="enable_best_selling_products" value="1"
														{{ $enable_best_selling_products == 1 ? 'checked' : '' }}>
													<span></span>
												</label>
												<span class="d-block" style="margin-top: -6px">{{ translate('Enable Best Selling Products') }}</span>
											</div>
										</div>
									</div>

								</div>
								@php
									$best_selling_products_title_sub_text = get_setting('best_selling_products_title_sub_text', null);
								@endphp
								<div class="form-group">
									<label class="col-from-label">
										{{translate('Title Sub Text')}}
									</label>
									<div class="col-12 pl-0">
										<input type="hidden" name="types[]" value="best_selling_products_title_sub_text">
										<input type="text" class="form-control" name="best_selling_products_title_sub_text" value="{{ $best_selling_products_title_sub_text }}" placeholder="">
									</div>
								</div>
								<!-- Save Button -->
								<div class="mt-4 text-right">
									<button type="submit"
										class="btn btn-success w-230px btn-md rounded-2 fs-14 fw-700 shadow-success">{{ translate('Save') }}</button>
								</div>
							</div>
						</form>
					</div>

					<div class="tab-pane fade" id="categories" role="tabpanel" aria-labelledby="categories-tab">
						<form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
							@csrf
							<input type="hidden" name="tab" value="categories">
							<div class="bg-white p-3 p-sm-2rem">
								<div class="row gutters-16">
									<div class="col-lg-12">
										<div class="form-group mb-2 d-flex justify-content-between align-items-center">
											@php $enable_categories = get_setting('enable_categories') @endphp
											<div class="d-flex align-items-center">
												<label class="aiz-switch aiz-switch-blue mb-0 pr-2">
													<input type="hidden" name="types[]" value="enable_categories">
													<input type="checkbox" name="enable_categories" value="1"
														{{ $enable_categories == 1 ? 'checked' : '' }}>
													<span></span>
												</label>
												<span class="d-block" style="margin-top: -6px">{{ translate('Enable Categories') }}</span>
											</div>
										</div>
									</div>
								</div>
					
								<div class="w-100">
									<label class="col-from-label fs-13 fw-500 mb-3">{{ translate('Categories') }}</label>
					
									@php
										$savedMainCategories = json_decode(get_setting('main_categories'), true) ?? [];
										$savedChildCategories = json_decode(get_setting('child_categories'), true) ?? [];

										$allMainCategories = \App\Models\Category::where('parent_id', 0)
											->with('childrenCategories')
											->get();
									@endphp
									<input type="hidden" name="types[]" value="main_categories">
									<input type="hidden" name="types[]" value="child_categories">
					
									<div class="categories-target" id="categories-target">
										@if (!empty($savedMainCategories))
											@foreach ($savedMainCategories as $key => $mainId)
												@php
													$childIds = $savedChildCategories[$key] ?? [];
												@endphp
												<div class="p-3 p-md-4 mb-3 mb-md-2rem remove-parent category-row" data-index="{{ $key }}" style="border: 1px dashed #e4e5eb;">
													<div class="row gutters-5 align-items-start">
					
														<div class="col">
															<div class="form-group mb-0">
																<label class="text-muted fs-12 mb-1">{{ translate('Main Category') }}</label>
																<select class="form-control aiz-selectpicker main-category-select"
																	name="main_categories[]"
																	data-live-search="true"
																	data-selected="{{ $mainId }}"
																	required>
																	<option value="">{{ translate('Select Main Category') }}</option>
																	@foreach ($allMainCategories as $category)
																		<option value="{{ $category->id }}" {{ $mainId == $category->id ? 'selected' : '' }}>
																			{{ $category->getTranslation('name') }}
																		</option>
																	@endforeach
																</select>
															</div>
														</div>
					
														<div class="col">
															<div class="form-group mb-0">
																<label class="text-muted fs-12 mb-1">{{ translate('Sub Category') }} <span class="text-muted fs-11">({{ translate('Max 2') }})</span></label>
																<select class="form-control aiz-selectpicker child-category-select"
																	name="child_categories[{{ $key }}][]"
																	data-live-search="true"
																	multiple
																	data-max-options="2"
																	data-selected-children="{{ json_encode($childIds) }}">
																	<option value="">{{ translate('Select Sub Category') }}</option>
																	@foreach ($allMainCategories as $category)
																		@if ($mainId == $category->id)
																			@foreach ($category->childrenCategories as $childCategory)
																				<option value="{{ $childCategory->id }}" {{ in_array($childCategory->id, $childIds) ? 'selected' : '' }}>
																					{{ $childCategory->getTranslation('name') }}
																				</option>
																			@endforeach
																		@endif
																	@endforeach
																</select>
															</div>
														</div>
					
														<div class="col-auto" style="padding-top: 26px;">
															<button type="button"
																class="btn btn-icon btn-circle btn-sm btn-soft-danger remove-category-row"
																data-toggle="remove-parent"
																data-parent=".remove-parent">
																<i class="las la-times"></i>
															</button>
														</div>
													</div>
												</div>
											@endforeach
										@endif
									</div>
					
									<div id="add-new-wrapper">
										<button
											type="button"
											id="add-category-btn"
											class="btn btn-block border hov-bg-soft-secondary fs-14 rounded-0 d-flex align-items-center justify-content-center"
											style="background: #fcfcfc;">
											<i class="las la-2x text-success la-plus-circle"></i>
											<span class="ml-2">{{ translate('Add New') }}</span>
										</button>
									</div>
								</div>
					
								<div class="mt-4 text-right">
									<button type="submit" class="btn btn-success w-230px btn-md rounded-2 fs-14 fw-700 shadow-success">{{ translate('Save') }}</button>
								</div>
							</div>
						</form>
					</div>

					@if(addon_is_activated('auction'))
						<!-- Auction Banner -->
						<div class="tab-pane fade" id="auction" role="tabpanel" aria-labelledby="auction-tab">
							<form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
								@csrf
								<input type="hidden" name="tab" value="auction">
								<div class="bg-white p-3 p-sm-2rem">

									<div class="row gutters-16">

										<div class="col-lg-12">
											<div class="form-group mb-2 d-flex justify-content-between align-items-center">
												@php $enable_auction_products = get_setting('enable_auction_products') @endphp
												<div class="d-flex align-items-center">
													<label class="aiz-switch aiz-switch-blue mb-0 pr-2">
														<input type="hidden" name="types[]" value="enable_auction_products">
														<input type="checkbox" name="enable_auction_products" value="1"
															{{ $enable_auction_products == 1 ? 'checked' : '' }}>
														<span></span>
													</label>
													<span class="d-block" style="margin-top: -6px">{{ translate('Enable Auction Products') }}</span>
												</div>
											</div>
										</div>

									</div>
									@php
										$auction_title_sub_text = get_setting('auction_title_sub_text', null);
									@endphp
									<div class="form-group">
										<label class="col-from-label">
											{{translate('Title Sub Text')}}
										</label>
										<div class="col-12 pl-0">
											<input type="hidden" name="types[]" value="auction_title_sub_text">
											<input type="text" class="form-control" name="auction_title_sub_text" value="{{ $auction_title_sub_text }}" placeholder="">
										</div>
									</div>
									<div class="w-100">
										<label
											class="col-from-label fs-13 fw-500 mb-3">{{ translate('Auction Banner') }}</label>
										<!-- Images -->
										<div class="form-group">
											<div class="input-group" data-toggle="aizuploader" data-type="image">
												<div class="input-group-prepend">
													<div class="input-group-text bg-soft-secondary font-weight-medium">
														{{ translate('Browse')}}
													</div>
												</div>
												<div class="form-control file-amount">{{ translate('Choose File') }}</div>
												<input type="hidden" name="types[][{{ $lang }}]" value="auction_banner_image">
												<input type="hidden" name="auction_banner_image" class="selected-files"
													value="{{ get_setting('auction_banner_image', null, $lang) }}">
											</div>
											<div class="file-preview box sm">
											</div>
											<small
												class="text-muted">{{ translate("Minimum dimensions required: 435px width X 400px height.") }}</small>
										</div>
									</div>
									<div class="w-100">
										<label
											class="col-from-label fs-13 fw-500 mb-3">{{ translate('Auction Banner') }} ({{ translate('Small Device') }})</label>
										<!-- Images -->
										<div class="form-group">
											<div class="input-group" data-toggle="aizuploader" data-type="image">
												<div class="input-group-prepend">
													<div class="input-group-text bg-soft-secondary font-weight-medium">
														{{ translate('Browse')}}
													</div>
												</div>
												<div class="form-control file-amount">{{ translate('Choose File') }}</div>
												<input type="hidden" name="types[][{{ $lang }}]" value="auction_banner_image_small">
												<input type="hidden" name="auction_banner_image_small" class="selected-files"
													value="{{ get_setting('auction_banner_image_small', null, $lang) }}">
											</div>
											<div class="file-preview box sm">
											</div>
											<small
												class="text-muted">{{ translate("Maximum dimensions required: 768px width X 200px height.") }}</small>
										</div>
									</div>

									<!-- Save Button -->
									<div class="mt-4 text-right">
										<button type="submit"
											class="btn btn-success w-230px btn-md rounded-2 fs-14 fw-700 shadow-success">{{ translate('Save') }}</button>
									</div>
								</div>
							</form>
						</div>
					@endif
					
					<div class="tab-pane fade" id="classifieds" role="tabpanel" aria-labelledby="classifieds-tab">
						<form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
							@csrf
							<input type="hidden" name="tab" value="classifieds">
							<div class="bg-white p-3 p-sm-2rem">
								<div class="row gutters-16">

									<div class="col-lg-12">
										<div class="form-group mb-2 d-flex justify-content-between align-items-center">
											@php $enable_classified_products = get_setting('enable_classified_products') @endphp
											<div class="d-flex align-items-center">
												<label class="aiz-switch aiz-switch-blue mb-0 pr-2">
													<input type="hidden" name="types[]" value="enable_classified_products">
													<input type="checkbox" name="enable_classified_products" value="1"
														{{ $enable_classified_products == 1 ? 'checked' : '' }}>
													<span></span>
												</label>
												<span class="d-block" style="margin-top: -6px">{{ translate('Enable Classified Products') }}</span>
											</div>
										</div>
									</div>

								</div>
								@php
									$classified_title_sub_text = get_setting('classified_title_sub_text', null);
								@endphp
								<div class="form-group">
									<label class="col-from-label">
										{{translate('Title Sub Text')}}
									</label>
									<div class="col-12 pl-0">
										<input type="hidden" name="types[]" value="classified_title_sub_text">
										<input type="text" class="form-control" name="classified_title_sub_text" value="{{ $classified_title_sub_text }}" placeholder="">
									</div>
								</div>
								<div class="w-100">
									<label
										class="col-from-label fs-13 fw-500 mb-3">{{ translate('Classified Banner') }}</label>
									<!-- Images -->
									<div class="form-group">
										<div class="input-group" data-toggle="aizuploader" data-type="image">
											<div class="input-group-prepend">
												<div class="input-group-text bg-soft-secondary font-weight-medium">
													{{ translate('Browse')}}
												</div>
											</div>
											<div class="form-control file-amount">{{ translate('Choose File') }}</div>
											<input type="hidden" name="types[][{{ $lang }}]" value="classified_banner_image">
											<input type="hidden" name="classified_banner_image" class="selected-files"
												value="{{ get_setting('classified_banner_image', null, $lang) }}">
										</div>
										<div class="file-preview box sm">
										</div>
										<small
											class="text-muted">{{ translate("Minimum dimensions required: 435px width X 400px height.") }}</small>
									</div>
								</div>
								<div class="w-100">
									<label
										class="col-from-label fs-13 fw-500 mb-3">{{ translate('Classified Banner') }} ({{ translate('Small Device') }})</label>
									<!-- Images -->
									<div class="form-group">
										<div class="input-group" data-toggle="aizuploader" data-type="image">
											<div class="input-group-prepend">
												<div class="input-group-text bg-soft-secondary font-weight-medium">
													{{ translate('Browse')}}
												</div>
											</div>
											<div class="form-control file-amount">{{ translate('Choose File') }}</div>
											<input type="hidden" name="types[][{{ $lang }}]" value="classified_banner_image_small">
											<input type="hidden" name="classified_banner_image_small" class="selected-files"
												value="{{ get_setting('classified_banner_image_small', null, $lang) }}">
										</div>
										<div class="file-preview box sm">
										</div>
										<small
											class="text-muted">{{ translate("Maximum dimensions required: 768px width X 200px height.") }}</small>
									</div>
								</div>
								<!-- Save Button -->
								<div class="mt-4 text-right">
									<button type="submit"
										class="btn btn-success w-230px btn-md rounded-2 fs-14 fw-700 shadow-success">{{ translate('Save') }}</button>
								</div>
							</div>
						</form>
					</div>

					<div class="tab-pane fade" id="banner_2" role="tabpanel" aria-labelledby="banner-2-tab">
						<form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
							@csrf
							<input type="hidden" name="tab" value="banner_2">
							<input type="hidden" name="types[][{{ $lang }}]" value="home_banner2_images">
							<input type="hidden" name="types[][{{ $lang }}]" value="home_banner2_links">

							<div class="bg-white p-3 p-sm-2rem">
								<div class="row gutters-16">

									<div class="col-lg-12">
										<div class="form-group mb-2 d-flex justify-content-between align-items-center">
											@php $enable_banner_2 = get_setting('enable_banner_2') @endphp
											<div class="d-flex align-items-center">
												<label class="aiz-switch aiz-switch-blue mb-0 pr-2">
													<input type="hidden" name="types[]" value="enable_banner_2">
													<input type="checkbox" name="enable_banner_2" value="1"
														{{ $enable_banner_2 == 1 ? 'checked' : '' }}>
													<span></span>
												</label>
												<span class="d-block" style="margin-top: -6px">{{ translate('Enable Banner 2') }}</span>
											</div>
										</div>
									</div>

								</div>
								<div class="w-100">
									<label
										class="col-from-label fs-13 fw-500 mb-0">{{ translate('Banner & Links (Max 3)') }}</label>
									<div class="small text-muted mb-0">
										{{ translate("Minimum dimensions required For Large Screen: 640px width X 320px height (If use a single banner).") }}
									</div>
									<!-- Images & links -->
									<div class="home-banner2-target">
										@php
											$home_banner2_images = get_setting('home_banner2_images', null, $lang);
											$home_banner2_links = get_setting('home_banner2_links', null, $lang);
										@endphp
										@if ($home_banner2_images != null)
											@foreach (json_decode($home_banner2_images, true) as $key => $value)
												<div class="p-3 p-md-4 mb-3 mb-md-2rem remove-parent"
													style="border: 1px dashed #e4e5eb;">
													<div class="row gutters-5">
														<!-- Image -->
														<div class="col-md">
															<label
																class="col-from-label fs-13 fw-500 mb-2">{{translate('Banner')}}</label>
															<div class="form-group mb-md-0">
																<div class="input-group" data-toggle="aizuploader"
																	data-type="image">
																	<div class="input-group-prepend">
																		<div
																			class="input-group-text bg-soft-secondary font-weight-medium">
																			{{ translate('Browse')}}
																		</div>
																	</div>
																	<div class="form-control file-amount">
																		{{ translate('Choose File') }}
																	</div>
																	<input type="hidden" name="home_banner2_images[]"
																		class="selected-files"
																		value="{{ json_decode($home_banner2_images, true)[$key] }}">
																</div>
																<div class="file-preview box sm">
																</div>
															</div>
														</div>

														<!-- link -->
														<div class="col-md">
															<label class="col-from-label fs-13 fw-500 mb-2">{{('Links')}}</label>
															<div class="form-group mb-md-0">
																<input type="text" class="form-control" placeholder="http://"
																	name="home_banner2_links[]"
																	value="{{ isset(json_decode($home_banner2_links, true)[$key]) ? json_decode($home_banner2_links, true)[$key] : '' }}">
															</div>
														</div>
														<!-- remove parent button -->
														<div class="col-md-auto">
															<div class="form-group mb-md-0">
																<button type="button"
																	class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger"
																	data-toggle="remove-parent" data-parent=".remove-parent">
																	<i class="las la-times"></i>
																</button>
															</div>
														</div>
													</div>
												</div>
											@endforeach
										@endif
									</div>

									<!-- Add button -->
									<div class="">
										<button type="button"
											class="btn btn-block border hov-bg-soft-secondary fs-14 rounded-0 d-flex align-items-center justify-content-center"
											style="background: #fcfcfc;" data-toggle="add-more" data-content='
													<div class="p-3 p-md-4 mb-3 mb-md-2rem remove-parent" style="border: 1px dashed #e4e5eb;">
														<div class="row gutters-5">
															<!-- Image -->
															<div class="col-md">
																<div class="form-group mb-md-0">
																	<div class="input-group" data-toggle="aizuploader" data-type="image">
																		<div class="input-group-prepend">
																			<div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
																		</div>
																		<div class="form-control file-amount">{{ translate('Choose File') }}</div>
																		<input type="hidden" name="home_banner2_images[]" class="selected-files" value="">
																	</div>
																	<div class="file-preview box sm">
																	</div>
																</div>
															</div>

															<!-- link -->
															<div class="col-md">
																<div class="form-group mb-md-0 mb-0">
																	<input type="text" class="form-control" placeholder="http://" name="home_banner2_links[]" value="">
																</div>
															</div>
															<!-- remove parent button -->
															<div class="col-md-auto">
																<div class="form-group mb-md-0">
																	<button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger" data-toggle="remove-parent" data-parent=".remove-parent">
																		<i class="las la-times"></i>
																	</button>
																</div>
															</div>
														</div>
													</div>' data-target=".home-banner2-target">
											<i class="las la-2x text-success la-plus-circle"></i>
											<span class="ml-2">{{ translate('Add New') }}</span>
										</button>
									</div>
								</div>
								<!-- Save Button -->
								<div class="mt-4 text-right">
									<button type="submit"
										class="btn btn-success w-230px btn-md rounded-2 fs-14 fw-700 shadow-success">{{ translate('Save') }}</button>
								</div>
							</div>
						</form>
					</div>

					<div class="tab-pane fade" id="newestPreorder" role="tabpanel" aria-labelledby="newestPreorder-tab">
						<form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
							@csrf
							<input type="hidden" name="tab" value="newestPreorder">
							<div class="bg-white p-3 p-sm-2rem">
								<div class="row gutters-16">

									<div class="col-lg-12">
										<div class="form-group mb-2 d-flex justify-content-between align-items-center">
											@php $enable_preorder_products = get_setting('enable_preorder_products') @endphp
											<div class="d-flex align-items-center">
												<label class="aiz-switch aiz-switch-blue mb-0 pr-2">
													<input type="hidden" name="types[]" value="enable_preorder_products">
													<input type="checkbox" name="enable_preorder_products" value="1"
														{{ $enable_preorder_products == 1 ? 'checked' : '' }}>
													<span></span>
												</label>
												<span class="d-block" style="margin-top: -6px">{{ translate('Enable Preorder Products') }}</span>
											</div>
										</div>
									</div>

								</div>
								@php
									$preorder_title_sub_text = get_setting('preorder_title_sub_text', null);
								@endphp
								<div class="form-group">
									<label class="col-from-label">
										{{translate('Title Sub Text')}}
									</label>
									<div class="col-12 pl-0">
										<input type="hidden" name="types[]" value="preorder_title_sub_text">
										<input type="text" class="form-control" name="preorder_title_sub_text" value="{{ $preorder_title_sub_text }}" placeholder="">
									</div>
								</div>
								<div class="form-group">
									<label class="col-from-label fs-13 fw-500">{{ translate("Preorder Banner") }}</label>
									<div class="input-group " data-toggle="aizuploader" data-type="image">
										<div class="input-group-prepend">
											<div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}</div>
										</div>
										<div class="form-control file-amount">{{ translate('Choose File') }}</div>
										<input type="hidden" name="types[][{{ $lang }}]"
											value="newest_preorder_banner_image">
										<input type="hidden" name="newest_preorder_banner_image"
											value="{{ get_setting('newest_preorder_banner_image', null, $lang) }}"
											class="selected-files">
									</div>
									<div class="file-preview box"></div>
									<small
											class="text-muted">{{ translate("Minimum dimensions required: 435px width X 400px height.") }}</small>
								</div>
								<div class="form-group">
									<label class="col-from-label fs-13 fw-500">{{ translate("Preorder Banner") }} ({{ translate("Small Device") }})</label>
									<div class="input-group " data-toggle="aizuploader" data-type="image">
										<div class="input-group-prepend">
											<div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}</div>
										</div>
										<div class="form-control file-amount">{{ translate('Choose File') }}</div>
										<input type="hidden" name="types[][{{ $lang }}]"
											value="newest_preorder_banner_image_small">
										<input type="hidden" name="newest_preorder_banner_image_small"
											value="{{ get_setting('newest_preorder_banner_image_small', null, $lang) }}"
											class="selected-files">
									</div>
									<div class="file-preview box"></div>
									<small
											class="text-muted">{{ translate("Maximum dimensions required: 768px width X 200px height.") }}</small>
								</div>
								<!-- Save Button -->
								<div class="mt-4 text-right">
									<button type="submit"
										class="btn btn-success w-230px btn-md rounded-2 fs-14 fw-700 shadow-success">{{ translate('Save') }}</button>
								</div>
							</div>
						</form>
					</div>

					<div class="tab-pane fade" id="shop_by_seller" role="tabpanel" aria-labelledby="shop-by-seller-tab">
						<form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
							@csrf
							<input type="hidden" name="tab" value="shop_by_seller">
							<div class="bg-white p-3 p-sm-2rem">
								<div class="row gutters-16">

									<div class="col-lg-12">
										<div class="form-group mb-2 d-flex justify-content-between align-items-center">
											@php $enable_shop_by_seller = get_setting('enable_shop_by_seller') @endphp
											<div class="d-flex align-items-center">
												<label class="aiz-switch aiz-switch-blue mb-0 pr-2">
													<input type="hidden" name="types[]" value="enable_shop_by_seller">
													<input type="checkbox" name="enable_shop_by_seller" value="1"
														{{ $enable_shop_by_seller == 1 ? 'checked' : '' }}>
													<span></span>
												</label>
												<span class="d-block" style="margin-top: -6px">{{ translate('Enable Shop By Sellers') }}</span>
											</div>
										</div>
									</div>

								</div>
								@php
									$shop_by_seller_title_sub_text = get_setting('shop_by_seller_title_sub_text', null);
								@endphp
								<div class="form-group">
									<label class="col-from-label">
										{{translate('Title Sub Text')}}
									</label>
									<div class="col-12 pl-0">
										<input type="hidden" name="types[]" value="shop_by_seller_title_sub_text">
										<input type="text" class="form-control" name="shop_by_seller_title_sub_text" value="{{ $shop_by_seller_title_sub_text }}" placeholder="">
									</div>
								</div>
								<!-- Save Button -->
								<div class="mt-4 text-right">
									<button type="submit"
										class="btn btn-success w-230px btn-md rounded-2 fs-14 fw-700 shadow-success">{{ translate('Save') }}</button>
								</div>
							</div>
						</form>
					</div>

					<div class="tab-pane fade" id="shop_by_brand" role="tabpanel" aria-labelledby="shop-by-brand-tab">
						<form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
							@csrf
							<input type="hidden" name="tab" value="shop_by_brand">
							<div class="bg-white p-3 p-sm-2rem">
								<div class="row gutters-16">

									<div class="col-lg-12">
										<div class="form-group mb-2 d-flex justify-content-between align-items-center">
											@php $enable_shop_by_brand = get_setting('enable_shop_by_brand') @endphp
											<div class="d-flex align-items-center">
												<label class="aiz-switch aiz-switch-blue mb-0 pr-2">
													<input type="hidden" name="types[]" value="enable_shop_by_brand">
													<input type="checkbox" name="enable_shop_by_brand" value="1"
														{{ $enable_shop_by_brand == 1 ? 'checked' : '' }}>
													<span></span>
												</label>
												<span class="d-block" style="margin-top: -6px">{{ translate('Enable Shop By Brands') }}</span>
											</div>
										</div>
									</div>

								</div>
								@php
									$shop_by_brand_title_sub_text = get_setting('shop_by_brand_title_sub_text', null);
								@endphp
								<div class="form-group">
									<label class="col-from-label">
										{{translate('Title Sub Text')}}
									</label>
									<div class="col-12 pl-0">
										<input type="hidden" name="types[]" value="shop_by_brand_title_sub_text">
										<input type="text" class="form-control" name="shop_by_brand_title_sub_text" value="{{ $shop_by_brand_title_sub_text }}" placeholder="">
									</div>
								</div>
								<!-- Save Button -->
								<div class="mt-4 text-right">
									<button type="submit"
										class="btn btn-success w-230px btn-md rounded-2 fs-14 fw-700 shadow-success">{{ translate('Save') }}</button>
								</div>
							</div>
						</form>
					</div>

					<div class="tab-pane fade" id="banner_3" role="tabpanel" aria-labelledby="banner-3-tab">
						<form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
							@csrf
							<input type="hidden" name="tab" value="banner_3">
							<input type="hidden" name="types[][{{ $lang }}]" value="home_banner3_images">
							<input type="hidden" name="types[][{{ $lang }}]" value="small_home_banner3_images">
							<input type="hidden" name="types[][{{ $lang }}]" value="home_banner3_links">

							<div class="bg-white p-3 p-sm-2rem">
								<div class="row gutters-16">

									<div class="col-lg-12">
										<div class="form-group mb-2 d-flex justify-content-between align-items-center">
											@php $enable_banner_3 = get_setting('enable_banner_3') @endphp
											<div class="d-flex align-items-center">
												<label class="aiz-switch aiz-switch-blue mb-0 pr-2">
													<input type="hidden" name="types[]" value="enable_banner_3">
													<input type="checkbox" name="enable_banner_3" value="1"
														{{ $enable_banner_3 == 1 ? 'checked' : '' }}>
													<span></span>
												</label>
												<span class="d-block" style="margin-top: -6px">{{ translate('Enable Banner 3') }}</span>
											</div>
										</div>
									</div>

								</div>
								<div class="w-100">
									<label
										class="col-from-label fs-13 fw-500 mb-0">{{ translate('Banner & Links (Max 3)') }}</label>
									<div class="small text-muted mb-0">
										{{ translate("Minimum dimensions required For Large Screen: 1920px width X 320px height (If use a single banner).") }}
									</div>
									<!-- Images & links -->
									<div class="home-banner3-target">
										@php
											$home_banner3_images = get_setting('home_banner3_images', null, $lang);
											$small_home_banner3_images = get_setting('small_home_banner3_images', null, $lang);
											$home_banner3_links = get_setting('home_banner3_links', null, $lang);
										@endphp
										@if ($home_banner3_images != null)
											@foreach (json_decode($home_banner3_images, true) as $key => $value)
												<div class="p-3 p-md-4 mb-3 mb-md-2rem remove-parent"
													style="border: 1px dashed #e4e5eb;">
													<div class="row gutters-5">
														<!-- Image -->
														<div class="col-md">
															<label
																class="col-from-label fs-13 fw-500 mb-2">{{translate('Banner')}}</label>
															<div class="form-group mb-md-0">
																<div class="input-group" data-toggle="aizuploader"
																	data-type="image">
																	<div class="input-group-prepend">
																		<div
																			class="input-group-text bg-soft-secondary font-weight-medium">
																			{{ translate('Browse')}}
																		</div>
																	</div>
																	<div class="form-control file-amount">
																		{{ translate('Choose File') }}
																	</div>
																	<input type="hidden" name="home_banner3_images[]"
																		class="selected-files"
																		value="{{ json_decode($home_banner3_images, true)[$key] }}">
																</div>
																<div class="file-preview box sm">
																</div>
															</div>
														</div>
														<div class="col-md">
															<label
																class="col-from-label fs-13 fw-500 mb-2">{{translate('Banner')}} ({{translate('Small Device')}})</label>
															<div class="form-group mb-md-0">
																<div class="input-group" data-toggle="aizuploader"
																	data-type="image">
																	<div class="input-group-prepend">
																		<div
																			class="input-group-text bg-soft-secondary font-weight-medium">
																			{{ translate('Browse')}}
																		</div>
																	</div>
																	<div class="form-control file-amount">
																		{{ translate('Choose File') }}
																	</div>
																	<input type="hidden" name="small_home_banner3_images[]"
																		class="selected-files"
																		value="{{ json_decode($small_home_banner3_images, true)[$key] ?? ''}}">
																</div>
																<div class="file-preview box sm">
																</div>
															</div>
														</div>

														<!-- link -->
														<div class="col-md">
															<label class="col-from-label fs-13 fw-500 mb-2">{{('Links')}}</label>
															<div class="form-group mb-md-0">
																<input type="text" class="form-control" placeholder="http://"
																	name="home_banner3_links[]"
																	value="{{ isset(json_decode($home_banner3_links, true)[$key]) ? json_decode($home_banner3_links, true)[$key] : '' }}">
															</div>
														</div>
														<!-- remove parent button -->
														<div class="col-md-auto">
															<div class="form-group mb-md-0">
																<button type="button"
																	class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger"
																	data-toggle="remove-parent" data-parent=".remove-parent">
																	<i class="las la-times"></i>
																</button>
															</div>
														</div>
													</div>
												</div>
											@endforeach
										@endif
									</div>

									<!-- Add button -->
									<div class="">
										<button type="button"
											class="btn btn-block border hov-bg-soft-secondary fs-14 rounded-0 d-flex align-items-center justify-content-center"
											style="background: #fcfcfc;" data-toggle="add-more" data-content='
													<div class="p-3 p-md-4 mb-3 mb-md-2rem remove-parent" style="border: 1px dashed #e4e5eb;">
														<div class="row gutters-5">
															<!-- Image -->
															<div class="col-md">
																<label
																class="col-from-label fs-13 fw-500 mb-2">{{translate('Banner')}}</label>
																<div class="form-group mb-md-0">
																	<div class="input-group" data-toggle="aizuploader" data-type="image">
																		<div class="input-group-prepend">
																			<div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
																		</div>
																		<div class="form-control file-amount">{{ translate('Choose File') }}</div>
																		<input type="hidden" name="home_banner3_images[]" class="selected-files" value="">
																	</div>
																	<div class="file-preview box sm">
																	</div>
																</div>
															</div>
															<!-- Image -->
															<div class="col-md">
																<label
																class="col-from-label fs-13 fw-500 mb-2">{{translate('Banner')}} ({{translate('Small Device')}})</label>
																<div class="form-group mb-md-0">
																	<div class="input-group" data-toggle="aizuploader" data-type="image">
																		<div class="input-group-prepend">
																			<div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
																		</div>
																		<div class="form-control file-amount">{{ translate('Choose File') }}</div>
																		<input type="hidden" name="small_home_banner3_images[]" class="selected-files" value="">
																	</div>
																	<div class="file-preview box sm">
																	</div>
																</div>
															</div>

															<!-- link -->
															<div class="col-md">
																<label class="col-from-label fs-13 fw-500 mb-2">{{('Links')}}</label>
																<div class="form-group mb-md-0 mb-0">
																	<input type="text" class="form-control" placeholder="http://" name="home_banner3_links[]" value="">
																</div>
															</div>
															<!-- remove parent button -->
															<div class="col-md-auto">
																<div class="form-group mb-md-0">
																	<button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger" data-toggle="remove-parent" data-parent=".remove-parent">
																		<i class="las la-times"></i>
																	</button>
																</div>
															</div>
														</div>
													</div>' data-target=".home-banner3-target">
											<i class="las la-2x text-success la-plus-circle"></i>
											<span class="ml-2">{{ translate('Add New') }}</span>
										</button>
									</div>
								</div>
								<!-- Save Button -->
								<div class="mt-4 text-right">
									<button type="submit"
										class="btn btn-success w-230px btn-md rounded-2 fs-14 fw-700 shadow-success">{{ translate('Save') }}</button>
								</div>
							</div>
						</form>
					</div>

				</div>
			</div>
		</div>
	</div>

@endsection

@section('script')
	<script>
		$(document).ready(function () {
			AIZ.plugins.bootstrapSelect('refresh');

			var hash = document.location.hash;
			if (hash) {
				$('.nav-tabs a[href="' + hash + '"]').tab('show');
			} else {
				$('.nav-tabs a[href="#home_slider"]').tab('show');
			}
			$('.nav-tabs a').on('shown.bs.tab', function (e) {
				window.location.hash = e.target.hash;
			});

			var MAX_MAIN_CATEGORIES = 4;
			var rowIndex = {{ count($savedMainCategories) }};

			function getRowCount() {
				return $('#categories-target .category-row').length;
			}

			function updateAddButtonVisibility() {
				if (getRowCount() >= MAX_MAIN_CATEGORIES) {
					$('#add-new-wrapper').hide();
				} else {
					$('#add-new-wrapper').show();
				}
			}

			function loadChildCategories($select, parentId, selectedIds) {
				var $row = $select.closest('.category-row');
				var $childSelect = $row.find('.child-category-select');
				var currentName = $childSelect.attr('name');

				try { $childSelect.selectpicker('destroy'); } catch(e) {}

				if (!parentId) {
					$childSelect.html('<option value="">{{ translate("Select Sub Category") }}</option>');
					$childSelect.attr('name', currentName);
					try { $childSelect.selectpicker({maxOptions: 2}); } catch(e) {}
					AIZ.plugins.bootstrapSelect('refresh');
					return;
				}

				$.ajax({
					url: '{{ route("categories.children") }}',
					type: 'GET',
					data: { parent_id: parentId },
					success: function (data) {
						var options = '<option value="">{{ translate("Select Sub Category") }}</option>';
						$.each(data, function (i, child) {
							var sel = (selectedIds && selectedIds.map(Number).indexOf(child.id) !== -1) ? 'selected' : '';
							options += '<option value="' + child.id + '" ' + sel + '>' + child.name + '</option>';
						});

						$childSelect.html(options);
						$childSelect.attr('name', currentName);

						try { $childSelect.selectpicker({maxOptions: 2}); } catch(e) {}
						
						setTimeout(function() {
							AIZ.plugins.bootstrapSelect('refresh');
							if (selectedIds && selectedIds.length > 0) {
								$childSelect.selectpicker('val', selectedIds.map(String));
								$childSelect.selectpicker('refresh');
							}
						}, 100);
					},
					error: function () {
						$childSelect.html('<option value="">{{ translate("Error loading") }}</option>');
						$childSelect.attr('name', currentName);
						try { $childSelect.selectpicker({maxOptions: 2}); } catch(e) {}
					}
				});
			}

			$(document).on('changed.bs.select', '.main-category-select', function () {
				var $this = $(this);

				$this.selectpicker('refresh');

				loadChildCategories($this, $this.val(), []);
			});

			$('#add-category-btn').on('click', function () {
				if (getRowCount() >= MAX_MAIN_CATEGORIES) return;

				var idx = rowIndex++;
				var mainOptions = '<option value="">{{ translate("Select Main Category") }}</option>';
				@foreach ($allMainCategories as $category)
					mainOptions += '<option value="{{ $category->id }}">{{ $category->getTranslation('name') }}</option>';
				@endforeach

				var html = `
					<div class="p-3 p-md-4 mb-3 mb-md-2rem remove-parent category-row" data-index="${idx}" style="border: 1px dashed #e4e5eb;">
						<div class="row gutters-5 align-items-start">
							<div class="col">
								<div class="form-group mb-0">
									<label class="text-muted fs-12 mb-1">{{ translate('Main Category') }}</label>
									<select class="form-control aiz-selectpicker main-category-select"
										name="main_categories[]"
										data-live-search="true"
										required>
										${mainOptions}
									</select>
								</div>
							</div>
							<div class="col">
								<div class="form-group mb-0">
									<label class="text-muted fs-12 mb-1">{{ translate('Sub Category') }} <span class="text-muted fs-11">({{ translate('Max 2') }})</span></label>
									<select class="form-control aiz-selectpicker child-category-select"
										name="child_categories[${idx}][]"
										data-live-search="true"
										multiple
										data-max-options="2">
										<option value="">{{ translate('Select Sub Category') }}</option>
									</select>
								</div>
							</div>
							<div class="col-auto" style="padding-top: 26px;">
								<button type="button" class="btn btn-icon btn-circle btn-sm btn-soft-danger remove-category-row">
									<i class="las la-times"></i>
								</button>
							</div>
						</div>
					</div>`;

				$('#categories-target').append(html);

				var $newRow = $('#categories-target .category-row:last');
				$newRow.find('.main-category-select').selectpicker('render');
				$newRow.find('.main-category-select').selectpicker('refresh');

				$newRow.find('.child-category-select').selectpicker({
					maxOptions: 2
				});

				$newRow.find('.child-category-select').selectpicker('refresh');

				updateAddButtonVisibility();
			});

			$(document).on('click', '.remove-category-row', function () {
				$(this).closest('.category-row').remove();
				setTimeout(function() {
					$('#categories-target .category-row').each(function() {
						var $row = $(this);
						var $mainSelect = $row.find('.main-category-select');
						var $childSelect = $row.find('.child-category-select');
						var parentId = $mainSelect.val();
						var selectedIds = $childSelect.data('selected-children') || [];
						if (parentId && selectedIds.length > 0) {
							loadChildCategories($mainSelect, parentId, selectedIds);
						}
					});
				}, 300);
				updateAddButtonVisibility();
			});

			$('#categories-target .category-row').each(function() {
				var $childSelect = $(this).find('.child-category-select');
				var selectedIds = $childSelect.data('selected-children') || [];

				$childSelect.selectpicker({ maxOptions: 2 });

				if (selectedIds && selectedIds.length > 0) {
					$childSelect.selectpicker('val', selectedIds.map(String));
					$childSelect.selectpicker('refresh');
				}
			});

			updateAddButtonVisibility();
		});
	</script>
@endsection
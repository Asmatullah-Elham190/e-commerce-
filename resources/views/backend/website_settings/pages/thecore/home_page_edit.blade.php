@extends('backend.layouts.app')

@section('content')
	<div class="page-content">
		<div class="aiz-titlebar text-left mt-2 pb-2 px-3 px-md-2rem border-bottom border-gray">
			<div class="row align-items-center">
				<div class="col">
					<h1 class="h3">{{ translate('Homepage Settings (Thecore)') }}</h1>
				</div>
			</div>
		</div>

		<div class="d-sm-flex">
			<!-- page side nav -->
			<div class="page-side-nav c-scrollbar-light px-3 py-2">
				<ul class="nav nav-tabs flex-sm-column border-0" role="tablist" aria-orientation="vertical">
					<!-- Home Slider -->
					<li class="nav-item">
						<a class="nav-link" id="home-slider-tab" href="#home_slider"
							data-toggle="tab" data-target="#home_slider" type="button" role="tab" aria-controls="home_slider" aria-selected="true">
							{{ translate('Home Slider') }}
						</a>
					</li>
					
					<!-- Flash Deals -->
					<li class="nav-item">
						<a class="nav-link" id="flash-deals-tab" href="#flash_deals"
							data-toggle="tab" data-target="#flash_deals" type="button" role="tab" aria-controls="flash_deals" aria-selected="false">
							{{ translate('Flash Deals') }}
						</a>
					</li>
					<!-- Hot Categories -->
					<li class="nav-item">
						<a class="nav-link" id="hot-categories-tab" href="#hot_categories"
							data-toggle="tab" data-target="#hot_categories" type="button" role="tab" aria-controls="hot_categories" aria-selected="false">
							{{ translate('Hot Categories') }}
						</a>
					</li>
					<!-- Featured Products -->
					<li class="nav-item">
						<a class="nav-link" id="featured-tab" href="#featured"
							data-toggle="tab" data-target="#featured" type="button" role="tab" aria-controls="featured" aria-selected="false">
							{{ translate('Featured Products') }}
						</a>
					</li>
					<!-- Featured Categories -->
					<li class="nav-item">
						<a class="nav-link" id="featured-categories-tab" href="#featured_categories"
							data-toggle="tab" data-target="#featured_categories" type="button" role="tab" aria-controls="featured_categories" aria-selected="false">
							{{ translate('Featured Categories') }}
						</a>
					</li>
					<!-- Best Selling -->
					<li class="nav-item">
						<a class="nav-link" id="best-selling-tab" href="#best_selling"
							data-toggle="tab" data-target="#best_selling" type="button" role="tab" aria-controls="best_selling" aria-selected="false">
							{{ translate('Best Selling Products') }}
						</a>
					</li>
					<!-- Today's Deal -->
					<li class="nav-item">
						<a class="nav-link" id="todays-deal-tab" href="#todays_deal"
							data-toggle="tab" data-target="#todays_deal" type="button" role="tab" aria-controls="todays_deal" aria-selected="false">
							{{ translate("Today's Deal") }}
						</a>
					</li>
					<!-- Banner Level 1 -->
					<li class="nav-item">
						<a class="nav-link" id="banner-1-tab" href="#banner_1"
							data-toggle="tab" data-target="#banner_1" type="button" role="tab" aria-controls="banner_1" aria-selected="false">
							{{ translate('Banner Level 1') }}
						</a>
					</li>

					@if(addon_is_activated('auction'))
					<!-- Auction Products -->
					<li class="nav-item">
						<a class="nav-link" id="auction-tab" href="#auction"
							data-toggle="tab" data-target="#auction" type="button" role="tab" aria-controls="auction" aria-selected="false">
							{{ translate('Auction Products') }}
							@if (env("DEMO_MODE") == "On")
							<span class="badge badge-pill badge-secondary ml-1">{{ translate('Addon') }}</span>
							@endif
						</a>
					</li>
					@endif
					
					<!-- Banner Level 2 -->
					<li class="nav-item">
						<a class="nav-link" id="banner-2-tab" href="#banner_2"
							data-toggle="tab" data-target="#banner_2" type="button" role="tab" aria-controls="banner_2" aria-selected="false">
							{{ translate('Banner Level 2') }}
						</a>
					</li>

					<!-- Banner Level 3 -->
					{{--<li class="nav-item">
						<a class="nav-link" id="banner-3-tab" href="#banner_3"
							data-toggle="tab" data-target="#banner_3" type="button" role="tab" aria-controls="banner_3" aria-selected="false">
							{{ translate('Banner Level 3') }}
						</a>
					</li> --}}
					

					<!-- Digital Products -->
					{{-- <li class="nav-item">
						<a class="nav-link" aria-current="page" href="#profile"
							data-toggle="tab" data-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">
							{{ translate('Digital Products') }}
						</a>
					</li> --}}
					<!-- Classifieds -->
					<li class="nav-item">
						<a class="nav-link" id="classifiedss-tab" href="#classifieds"
							data-toggle="tab" data-target="#classifieds" type="button" role="tab" aria-controls="classifieds" aria-selected="false">
							{{ translate('Classifieds') }}
						</a>
					</li>
					@if(addon_is_activated('preorder'))
					<!-- Newest Preorder Products -->
					<li class="nav-item">
						<a class="nav-link" id="classifiedss-tab" href="#newestPreorder"
							data-toggle="tab" data-target="#newestPreorder" type="button" role="tab" aria-controls="newestPreorder" aria-selected="false">
							{{ translate('Newest Preorder Products') }}
						</a>
					</li>
					@endif
					<!-- Top Sellers -->
					{{-- <li class="nav-item">
						<a class="nav-link" aria-current="page" href="#profile"
							data-toggle="tab" data-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">
							{{ translate('Top Sellers') }}
						</a>
					</li> --}}
					<!-- Top Brands -->
					{{-- <li class="nav-item">
						<a class="nav-link" id="brands-tab" href="#brands"
							data-toggle="tab" data-target="#brands" type="button" role="tab" aria-controls="brands" aria-selected="false">
							{{ translate('Top Brands') }}
						</a>
					</li> --}}
					
				</ul>
			</div>

			<!-- tab content -->
			<div class="flex-grow-1 p-sm-3 p-lg-2rem mb-2rem mb-md-0">
				<div class="tab-content">

					<!-- Language Bar -->
					<ul class="nav nav-tabs nav-fill language-bar">
						@foreach (get_all_active_language() as $key => $language)
							<li class="nav-item">
								<a class="nav-link text-reset @if ($language->code == $lang) active @endif py-3"
									href="{{route('custom-pages.edit', ['id'=>$page->slug, 'lang'=>$language->code, 'page'=>'home'] )}}">
									<img src="{{ static_asset('assets/img/flags/' . $language->code . '.png') }}"
										height="11" class="mr-1">
									<span>{{ $language->name }}</span>
								</a>
							</li>
						@endforeach
					</ul>

					<!-- Home Slider -->
					<div class="tab-pane fade" id="home_slider" role="tabpanel" aria-labelledby="home-slider-tab">
						<form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
							@csrf
							<input type="hidden" name="tab" value="home_slider">
							<input type="hidden" name="types[][{{ $lang }}]" value="home_slider_images">
							<input type="hidden" name="types[][{{ $lang }}]" value="home_slider_links">

							<div class="bg-white p-3 p-sm-2rem">
								<div class="w-100">
									<!-- Information -->
									<div class="fs-11 d-flex mb-2rem">
										<div>
											<svg id="_79508b4b8c932dcad9066e2be4ca34f2" data-name="79508b4b8c932dcad9066e2be4ca34f2" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
												<path id="Path_40683" data-name="Path 40683" d="M8,16a8,8,0,1,1,8-8A8.024,8.024,0,0,1,8,16ZM8,1.333A6.667,6.667,0,1,0,14.667,8,6.686,6.686,0,0,0,8,1.333Z" fill="#9da3ae"/>
												<path id="Path_40684" data-name="Path 40684" d="M10.6,15a.926.926,0,0,1-.667-.333c-.333-.467-.067-1.133.667-2.933.133-.267.267-.6.4-.867a.714.714,0,0,1-.933-.067.644.644,0,0,1,0-.933A3.408,3.408,0,0,1,11.929,9a.926.926,0,0,1,.667.333c.333.467.067,1.133-.667,2.933-.133.267-.267.6-.4.867a.714.714,0,0,1,.933.067.644.644,0,0,1,0,.933A3.408,3.408,0,0,1,10.6,15Z" transform="translate(-3.262 -3)" fill="#9da3ae"/>
												<circle id="Ellipse_813" data-name="Ellipse 813" cx="1" cy="1" r="1" transform="translate(8 3.333)" fill="#9da3ae"/>
												<path id="Path_40685" data-name="Path 40685" d="M12.833,7.167a1.333,1.333,0,1,1,1.333-1.333A1.337,1.337,0,0,1,12.833,7.167Zm0-2a.63.63,0,0,0-.667.667.667.667,0,1,0,1.333,0A.63.63,0,0,0,12.833,5.167Z" transform="translate(-3.833 -1.5)" fill="#9da3ae"/>
											</svg>
										</div>
										<div class="ml-2 text-gray">
											<div class="mb-2">{{ translate('Minimum dimensions required: 554px width X 516px height.') }}</div>
											<div>{{ translate('We have limited banner height to maintain UI. We had to crop from both left & right side in view for different devices to make it responsive. Before designing banner keep these points in mind.') }}</div>
										</div>
									</div>

									

									<!-- Images & links -->
									<div class="home-slider-target">
										@php
											$home_slider_images = get_setting('home_slider_images', null, $lang);
											$home_slider_links = get_setting('home_slider_links', null, $lang);
										@endphp
										@if ($home_slider_images != null)
											@foreach (json_decode($home_slider_images, true) as $key => $value)
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
																	<input type="hidden" name="home_slider_images[]" class="selected-files" value="{{ json_decode($home_slider_images, true)[$key] }}">
																</div>
																<div class="file-preview box sm">
																</div>
															</div>
														</div>
														<!-- link -->
														<div class="col-md">
															<div class="form-group mb-md-0">
																<input type="text" class="form-control" placeholder="http://" name="home_slider_links[]" value="{{ isset(json_decode($home_slider_links, true)[$key]) ? json_decode($home_slider_links, true)[$key] : '' }}">
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
												</div>
											@endforeach
										@endif
									</div>

									<!-- Add button -->
									<div class="">
										<button
											type="button"
											class="btn btn-block border hov-bg-soft-secondary fs-14 rounded-0 d-flex align-items-center justify-content-center" style="background: #fcfcfc;"
											data-toggle="add-more"
											data-content='
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
																<input type="hidden" name="home_slider_images[]" class="selected-files" value="">
															</div>
															<div class="file-preview box sm">
															</div>
														</div>
													</div>
													<!-- link -->
													<div class="col-md">
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
											</div>'
											data-target=".home-slider-target">
											<i class="las la-2x text-success la-plus-circle"></i>
											<span class="ml-2">{{ translate('Add New') }}</span>
										</button>
									</div>

									<div class="row">
										<div class="col-md-12 mt-2">
											<div class="form-group">
												<label class="col-from-label fs-13 fw-500">{{ translate(' Background color') }}</label>
												<div class="input-group">
													@php $hero_bg_color = get_setting('hero_bg_color'); @endphp
													<input type="hidden" name="types[]" value="hero_bg_color">
													<input type="text" class="form-control aiz-color-input" placeholder="#000000" name="hero_bg_color" value="{{ $hero_bg_color }}">
													<div class="input-group-append">
														<span class="input-group-text p-0">
															<input class="aiz-color-picker border-0 size-40px" type="color" value="{{ $hero_bg_color }}">
														</span>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<!-- Save Button -->
								<div class="mt-4 text-right">
									<button type="submit" class="btn btn-success w-230px btn-md rounded-2 fs-14 fw-700 shadow-success">{{ translate('Save') }}</button>
								</div>
							</div>
						</form>
					</div>

					<div class="tab-pane fade" id="flash_deals" role="tabpanel" aria-labelledby="flash-deals-tab">
						<form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
							@csrf
							<input type="hidden" name="tab" value="flash_deals">
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
									<button type="submit" class="btn btn-success w-230px btn-md rounded-2 fs-14 fw-700 shadow-success">{{ translate('Save') }}</button>
								</div>
							</div>
						</form>
					</div>

					<!-- Featured Products -->
					<div class="tab-pane fade" id="featured" role="tabpanel" aria-labelledby="featured-tab">
						<form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
							@csrf
							<input type="hidden" name="tab" value="featured">
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
								<div class="row gutters-16">
									<!-- Featured Products Settings -->
									<div class="col-lg-12">
											<!-- Background color -->
											<div class="form-group">
												<label class="col-from-label fs-13 fw-500">{{ translate('Background color') }}</label>
												<div class="input-group mb-3">
													<input type="hidden" name="types[]" value="featured_section_bg_color">
													<input type="text" class="form-control aiz-color-input" placeholder="#000000" name="featured_section_bg_color" value="{{ get_setting('featured_section_bg_color') }}">
													<div class="input-group-append">
														<span class="input-group-text p-0">
															<input class="aiz-color-picker border-0 size-40px" type="color" value="{{ get_setting('featured_section_bg_color') }}">
														</span>
													</div>
												</div>
											</div>	
										
									</div>
								</div>
								<!-- Save Button -->
								<div class="mt-4 text-right">
									<button type="submit" class="btn btn-success w-230px btn-md rounded-2 fs-14 fw-700 shadow-success">{{ translate('Save') }}</button>
								</div>
							</div>
						</form>
					</div>

					<!-- Hot Categories -->
					<div class="tab-pane fade" id="hot_categories" role="tabpanel" aria-labelledby="hot-categories-tab">
						<form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
							@csrf
							<input type="hidden" name="tab" value="hot_categories">
							<div class="bg-white p-3 p-sm-2rem">
								<div class="row gutters-16">
									<div class="col-lg-12">
										<div class="w-100">

											<div class="form-group mb-2 d-flex justify-content-between align-items-center">
												@php $enable_hot_categories = get_setting('enable_hot_categories') @endphp
												<div class="d-flex align-items-center">
													<label class="aiz-switch aiz-switch-blue mb-0 pr-2">
														<input type="hidden" name="types[]" value="enable_hot_categories">
														<input type="checkbox" name="enable_hot_categories" value="1"
															{{ $enable_hot_categories == 1 ? 'checked' : '' }}>
														<span></span>
													</label>
													<span class="d-block" style="margin-top: -6px">{{ translate('Enable Hot Categories') }}</span>
												</div>
											</div>

										</div>
									</div>
								</div>
								<div class="row gutters-16">
									<!-- Featured Products Settings -->
									<div class="col-lg-6">
										<div class="p-4 border" style="background: #fcfcfc;">
											<p class="fs-14 fw-500 mb-3">{{ translate("To set Hot Categories on the homepage, first enable them from the Category Listing page. Only the enabled categories will appear in the Hot Categories section.") }}
												<br>{{ translate("Set Hot categories") }}<a href="{{ route('categories.index') }}"> {{ translate('Here') }}</a> 
											</p>
										</div>
									</div>
								</div>
								<!-- Save Button -->
								<div class="mt-4 text-right">
									<button type="submit" class="btn btn-success w-230px btn-md rounded-2 fs-14 fw-700 shadow-success">{{ translate('Save') }}</button>
								</div>
							</div>
						</form>
					</div>

					<!-- Featured Categories -->
					<div class="tab-pane fade" id="featured_categories" role="tabpanel" aria-labelledby="featured-categories-tab">
						<form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
							@csrf
							<input type="hidden" name="tab" value="featured_categories">
							<div class="bg-white p-3 p-sm-2rem">
								<div class="row gutters-16">
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
								<div class="row gutters-16">
									<!-- Featured Products Settings -->
									<div class="col-lg-12">
										
											<!-- Background color -->
											<div class="form-group">
												<label class="col-from-label fs-13 fw-500">{{ translate('Background color') }}</label>
												<div class="input-group mb-3">
													<input type="hidden" name="types[]" value="featured_category_section_bg_color">
													<input type="text" class="form-control aiz-color-input" placeholder="#000000" name="featured_category_section_bg_color" value="{{ get_setting('featured_category_section_bg_color') }}">
													<div class="input-group-append">
														<span class="input-group-text p-0">
															<input class="aiz-color-picker border-0 size-40px" type="color" value="{{ get_setting('featured_category_section_bg_color') }}">
														</span>
													</div>
												</div>
											</div>
											
											<!-- Use Outline -->
											<div class="form-group d-inline-block mb-1">
												<label class="aiz-checkbox">
													<input type="hidden" name="types[]" value="featured_category_section_outline">
													<input type="checkbox" class="check-one" name="featured_category_section_outline" value="1" @if(get_setting('featured_category_section_outline') == 1) checked @endif>
													<span class="fs-13 fw-400">{{ translate('Use Outline') }}</span>
													<span class="aiz-square-check"></span>
												</label>
											</div>
											<!-- Outline color -->
											<div class="form-group">
												<label class="col-from-label fs-13 fw-500">{{ translate('Outline color') }}</label>
												<div class="input-group mb-3">
													<input type="hidden" name="types[]" value="featured_category_section_outline_color">
													<input type="text" class="form-control aiz-color-input" placeholder="#000000" name="featured_category_section_outline_color" value="{{ get_setting('featured_category_section_outline_color') }}">
													<div class="input-group-append">
														<span class="input-group-text p-0">
															<input class="aiz-color-picker border-0 size-40px" type="color" value="{{ get_setting('featured_category_section_outline_color') }}">
														</span>
													</div>
												</div>
											</div>

											<!-- Product Button Color -->
											<div class="form-group">
												<label class="col-from-label fs-13 fw-500">{{ translate('Button color') }}</label>
												<div class="input-group">
													@php $featured_category_btn_color = get_setting('featured_category_btn_color'); @endphp
													<input type="hidden" name="types[]" value="featured_category_btn_color">
													<input type="text" class="form-control aiz-color-input" placeholder="#000000" name="featured_category_btn_color" value="{{ $featured_category_btn_color }}">
													<div class="input-group-append">
														<span class="input-group-text p-0">
															<input class="aiz-color-picker border-0 size-40px" type="color" value="{{ $featured_category_btn_color }}">
														</span>
													</div>
												</div>
											</div>
											<!-- btn text color -->
											<div class="form-group">
												<label class="col-from-label fs-13 fw-500">{{ translate('Button text color') }}</label>
												<div class="input-group mb-3">
													<input type="hidden" name="types[]" value="featured_category_section_btn_text_color">
													<input type="text" class="form-control aiz-color-input" placeholder="#000000" name="featured_category_section_btn_text_color" value="{{ get_setting('featured_category_section_btn_text_color') }}">
													<div class="input-group-append">
														<span class="input-group-text p-0">
															<input class="aiz-color-picker border-0 size-40px" type="color" value="{{ get_setting('featured_section_bg_color') }}">
														</span>
													</div>
												</div>
											</div>
										
									</div>
								</div>
								<!-- Save Button -->
								<div class="mt-4 text-right">
									<button type="submit" class="btn btn-success w-230px btn-md rounded-2 fs-14 fw-700 shadow-success">{{ translate('Save') }}</button>
								</div>
							</div>
						</form>
					</div>

					<!-- Best Selling Products -->
					<div class="tab-pane fade" id="best_selling" role="tabpanel" aria-labelledby="best-selling-tab">
						<form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
							@csrf
							<input type="hidden" name="tab" value="best_selling">
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
								<div class="row gutters-16">
									<!-- Best Selling Products Settings -->
									<div class="col-lg-12">
										
											<!-- Background color -->
											<div class="form-group">
												<label class="col-from-label fs-13 fw-500">{{ translate('Background color') }}</label>
												<div class="input-group mb-3">
													<input type="hidden" name="types[]" value="best_selling_section_bg_color">
													<input type="text" class="form-control aiz-color-input" placeholder="#000000" name="best_selling_section_bg_color" value="{{ get_setting('best_selling_section_bg_color') }}">
													<div class="input-group-append">
														<span class="input-group-text p-0">
															<input class="aiz-color-picker border-0 size-40px" type="color" value="{{ get_setting('best_selling_section_bg_color') }}">
														</span>
													</div>
												</div>
											</div>
										
									</div>
								</div>
								<!-- Save Button -->
								<div class="mt-4 text-right">
									<button type="submit" class="btn btn-success w-230px btn-md rounded-2 fs-14 fw-700 shadow-success">{{ translate('Save') }}</button>
								</div>
							</div>
						</form>
					</div>

					<!-- Today's Deal -->
					<div class="tab-pane fade" id="todays_deal" role="tabpanel" aria-labelledby="todays-deal-tab">
						<form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
							@csrf
							<input type="hidden" name="tab" value="todays_deal">
							<div class="bg-white p-3 p-sm-2rem">
								<div class="row gutters-16 mb-2">
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
													<span class="d-block" style="margin-top: -6px">{{ translate("Enable Today's Deal") }}</span>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row gutters-16">
									<!-- Todays Deal Settings -->
									<div class="col-lg-12">
										<div class="w-100">
											

											<!-- Products background color -->
											<div class="form-group">
												<label class="col-from-label fs-13 fw-500">{{ translate('Products background color') }}</label>
												<div class="input-group">
													@php $todays_deal_bg_color =  get_setting('todays_deal_bg_color') @endphp
													<input type="hidden" name="types[]" value="todays_deal_bg_color">
													<input type="text" class="form-control aiz-color-input" placeholder="#000000" name="todays_deal_bg_color" value="{{ $todays_deal_bg_color }}">
													<div class="input-group-append">
														<span class="input-group-text p-0">
															<input class="aiz-color-picker border-0 size-40px" type="color" value="{{ $todays_deal_bg_color }}">
														</span>
													</div>
												</div>
											</div>

											
										</div>
									</div>
								</div>
								<!-- Save Button -->
								<div class="mt-4 text-right">
									<button type="submit" class="btn btn-success w-230px btn-md rounded-2 fs-14 fw-700 shadow-success">{{ translate('Save') }}</button>
								</div>
							</div>
						</form>
					</div>

					<!-- Banner Level 1 -->
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
									<label class="col-from-label fs-13 fw-500 mb-0">{{ translate('Banner & Links (Max 3)') }}</label>
                                    <div class="small text-muted mb-3">{{ translate("Minimum dimensions required: 436px width X 240px height.") }}</div>

									<!-- Images & links -->
									<div class="home-banner1-target">
										@php
											$home_banner1_images = get_setting('home_banner1_images', null, $lang);
											$home_banner1_links = get_setting('home_banner1_links', null, $lang);
										@endphp
										@if ($home_banner1_images != null)
											@foreach (json_decode($home_banner1_images, true) as $key => $value)
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
																	<input type="hidden" name="home_banner1_images[]" class="selected-files" value="{{ json_decode($home_banner1_images, true)[$key] }}">
																</div>
																<div class="file-preview box sm">
																</div>
															</div>
														</div>
														<!-- link -->
														<div class="col-md">
															<div class="form-group mb-md-0">
																<input type="text" class="form-control" placeholder="http://" name="home_banner1_links[]" value="{{ isset(json_decode($home_banner1_links, true)[$key]) ? json_decode($home_banner1_links, true)[$key] : '' }}">
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
												</div>
											@endforeach
										@endif
									</div>

									<!-- Add button -->
									<div class="">
										<button
											type="button"
											class="btn btn-block border hov-bg-soft-secondary fs-14 rounded-0 d-flex align-items-center justify-content-center" style="background: #fcfcfc;"
											data-toggle="add-more"
											data-content='
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
											</div>'
											data-target=".home-banner1-target">
											<i class="las la-2x text-success la-plus-circle"></i>
											<span class="ml-2">{{ translate('Add New') }}</span>
										</button>
									</div>
								</div>
								<!-- Save Button -->
								<div class="mt-4 text-right">
									<button type="submit" class="btn btn-success w-230px btn-md rounded-2 fs-14 fw-700 shadow-success">{{ translate('Save') }}</button>
								</div>
							</div>
						</form>
					</div>

					<!-- Banner Level 2 -->
					<div class="tab-pane fade" id="banner_2" role="tabpanel" aria-labelledby="banner-2-tab">
						<form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
							@csrf
							<input type="hidden" name="tab" value="banner_2">
							<input type="hidden" name="types[][{{ $lang }}]" value="home_banner2_images">
							<input type="hidden" name="types[][{{ $lang }}]" value="home_banner2_sm_images">
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
									<label class="col-from-label fs-13 fw-500 mb-0">{{ translate('Banner & Links (Max 3)') }}</label>
                                    <div class="small text-muted mb-0">{{ translate("Minimum dimensions required For Large Screen: 1370px width X 420px height (If use a single banner).") }}</div>
									<div class="small text-muted mb-3">{{ translate("Minimum dimensions required For Small Screen: 436px width X 443px height. (If use a single banner).") }}</div>
									<!-- Images & links -->
									<div class="home-banner2-target">
										@php
											$home_banner2_images = get_setting('home_banner2_images', null, $lang);
											$home_banner2_sm_images = get_setting('home_banner2_sm_images', null, $lang);
											$home_banner2_links = get_setting('home_banner2_links', null, $lang);
										@endphp
										@if ($home_banner2_images != null)
											@foreach (json_decode($home_banner2_images, true) as $key => $value)
												<div class="p-3 p-md-4 mb-3 mb-md-2rem remove-parent" style="border: 1px dashed #e4e5eb;">
													<div class="row gutters-5">
														<!-- Image -->
														<div class="col-md-4">
															<label class="col-from-label fs-13 fw-500 mb-2">{{translate('Banner')}}</label>
															<div class="form-group mb-md-0">
																<div class="input-group" data-toggle="aizuploader" data-type="image">
																	<div class="input-group-prepend">
																		<div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
																	</div>
																	<div class="form-control file-amount">{{ translate('Choose File') }}</div>
																	<input type="hidden" name="home_banner2_images[]" class="selected-files" value="{{ json_decode($home_banner2_images, true)[$key] }}">
																</div>
																<div class="file-preview box sm">
																</div>
															</div>
														</div>

														<!-- Image -->
														<div class="col-md-4">
															<label class="col-from-label fs-13 fw-500 mb-2">{{translate('Bnnner for Small device')}}</label>
															<div class="form-group mb-md-0">
																<div class="input-group" data-toggle="aizuploader" data-type="image">
																	<div class="input-group-prepend">
																		<div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
																	</div>
																	<div class="form-control file-amount">{{ translate('Choose File') }}</div>
																	<input type="hidden" name="home_banner2_sm_images[]" class="selected-files" value="{{ json_decode($home_banner2_sm_images, true)[$key] ?? ''}}">
																</div>
																<div class="file-preview box sm">
																</div>
															</div>
														</div>
														<!-- link -->
														<div class="col-md">
															<label class="col-from-label fs-13 fw-500 mb-2">{{('Links')}}</label>
															<div class="form-group mb-md-0">
																<input type="text" class="form-control" placeholder="http://" name="home_banner2_links[]" value="{{ isset(json_decode($home_banner2_links, true)[$key]) ? json_decode($home_banner2_links, true)[$key] : '' }}">
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
												</div>
											@endforeach
										@endif
									</div>

									<!-- Add button -->
									<div class="">
										<button
											type="button"
											class="btn btn-block border hov-bg-soft-secondary fs-14 rounded-0 d-flex align-items-center justify-content-center" style="background: #fcfcfc;"
											data-toggle="add-more"
											data-content='
											<div class="p-3 p-md-4 mb-3 mb-md-2rem remove-parent" style="border: 1px dashed #e4e5eb;">
												<div class="row gutters-5">
													<!-- Image -->
													<div class="col-md-4">
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

													<div class="col-md-4">
														<div class="form-group mb-md-0">
															<div class="input-group" data-toggle="aizuploader" data-type="image">
																<div class="input-group-prepend">
																	<div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
																</div>
																<div class="form-control file-amount">{{ translate('Choose File') }}</div>
																<input type="hidden" name="home_banner2_sm_images[]" class="selected-files" value="">
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
											</div>'
											data-target=".home-banner2-target">
											<i class="las la-2x text-success la-plus-circle"></i>
											<span class="ml-2">{{ translate('Add New') }}</span>
										</button>
									</div>
								</div>
								<!-- Save Button -->
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
								<div class="w-100">
									<label class="col-from-label fs-13 fw-500 mb-3">{{ translate('Auction Banner') }}</label>
									<!-- Images -->
									<div class="form-group">
										<div class="input-group" data-toggle="aizuploader" data-type="image">
											<div class="input-group-prepend">
												<div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
											</div>
											<div class="form-control file-amount">{{ translate('Choose File') }}</div>
											<input type="hidden" name="types[][{{ $lang }}]" value="auction_banner_image">
											<input type="hidden" name="auction_banner_image" class="selected-files" value="{{ get_setting('auction_banner_image', null, $lang) }}">
										</div>
										<div class="file-preview box sm">
										</div>
                                        <small class="text-muted">{{ translate("Minimum dimensions required: 435px width X 400px height.") }}</small>
									</div>
								</div>

								<div class="row w-100">
									<!-- Product Background Color -->
									<div class="col-md-6">
										<div class="form-group">
											<label class="col-from-label fs-13 fw-500">{{ translate('Products background color') }}</label>
											<div class="input-group">
												@php $auction_product_bg_color = get_setting('auction_product_bg_color'); @endphp
												<input type="hidden" name="types[]" value="auction_product_bg_color">
												<input type="text" class="form-control aiz-color-input" placeholder="#000000" name="auction_product_bg_color" value="{{ $auction_product_bg_color }}">
												<div class="input-group-append">
													<span class="input-group-text p-0">
														<input class="aiz-color-picker border-0 size-40px" type="color" value="{{ $auction_product_bg_color }}">
													</span>
												</div>
											</div>
										</div>
									</div>

									<!-- Product Button Color -->
									<div class="col-md-6">
										<div class="form-group">
											<label class="col-from-label fs-13 fw-500">{{ translate('Products button color') }}</label>
											<div class="input-group">
												@php $auction_product_btn_color = get_setting('auction_product_btn_color'); @endphp
												<input type="hidden" name="types[]" value="auction_product_btn_color">
												<input type="text" class="form-control aiz-color-input" placeholder="#000000" name="auction_product_btn_color" value="{{ $auction_product_btn_color }}">
												<div class="input-group-append">
													<span class="input-group-text p-0">
														<input class="aiz-color-picker border-0 size-40px" type="color" value="{{ $auction_product_btn_color }}">
													</span>
												</div>
											</div>
										</div>
									</div>
								</div>

								<!-- Save Button -->
								<div class="mt-4 text-right">
									<button type="submit" class="btn btn-success w-230px btn-md rounded-2 fs-14 fw-700 shadow-success">{{ translate('Save') }}</button>
								</div>
							</div>
						</form>
					</div>
					@endif



					<!-- newestPreorder -->
					<div class="tab-pane fade" id="newestPreorder" role="tabpanel" aria-labelledby="newestPreorder-tab">
						<form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
							@csrf
							<input type="hidden" name="tab" value="newestPreorder">
							<div class="bg-white p-3 p-sm-2rem">
								<div class="row gutters-16">
									
									<div class="col-lg-12">
										<div class="form-group mb-2 d-flex justify-content-between align-items-center">
											@php $enable_newest_preorder_products = get_setting('enable_newest_preorder_products') @endphp
											<div class="d-flex align-items-center">
												<label class="aiz-switch aiz-switch-blue mb-0 pr-2">
													<input type="hidden" name="types[]" value="enable_newest_preorder_products">
													<input type="checkbox" name="enable_newest_preorder_products" value="1"
														{{ $enable_newest_preorder_products == 1 ? 'checked' : '' }}>
													<span></span>
												</label>
												<span class="d-block" style="margin-top: -6px">{{ translate('Enable Newest Preorder Products Section') }}</span>
											</div>
										</div>
									</div>

								</div>
								<div class="form-group">
									<label class="col-from-label fs-13 fw-500">{{ translate("Banner") }}</label>
									<div class="input-group " data-toggle="aizuploader" data-type="image">
										<div class="input-group-prepend">
											<div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}</div>
										</div>
										<div class="form-control file-amount">{{ translate('Choose File') }}</div>
										<input type="hidden" name="types[][{{ $lang }}]" value="newest_preorder_banner_image">
										<input type="hidden" name="newest_preorder_banner_image" value="{{ get_setting('newest_preorder_banner_image', null, $lang) }}" class="selected-files">
									</div>
									<div class="file-preview box"></div>
								</div>
								<!-- Save Button -->
								<div class="mt-4 text-right">
									<button type="submit" class="btn btn-success w-230px btn-md rounded-2 fs-14 fw-700 shadow-success">{{ translate('Save') }}</button>
								</div>
							</div>
						</form>
					</div>


					<!-- Classifieds -->
					<div class="tab-pane fade" id="classifieds" role="tabpanel" aria-labelledby="classifieds-tab">
						<form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
							@csrf
							<input type="hidden" name="tab" value="classifieds">
							<div class="bg-white p-3 p-sm-2rem">
								<div class="row gutters-16">
									
									<div class="col-lg-12">
										<div class="form-group mb-2 d-flex justify-content-between align-items-center">
											@php $enable_classified_products_sections = get_setting('enable_classified_products_sections') @endphp
											<div class="d-flex align-items-center">
												<label class="aiz-switch aiz-switch-blue mb-0 pr-2">
													<input type="hidden" name="types[]" value="enable_classified_products_sections">
													<input type="checkbox" name="enable_classified_products_sections" value="1"
														{{ $enable_classified_products_sections == 1 ? 'checked' : '' }}>
													<span></span>
												</label>
												<span class="d-block" style="margin-top: -6px">{{ translate('Enable Classified Products Section') }}</span>
											</div>
										</div>
									</div>

								</div>
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label class="col-from-label fs-13 fw-500">{{ translate('Products Background color') }}</label>
											<div class="input-group">
												@php $classified_bg_color = get_setting('classified_bg_color'); @endphp
												<input type="hidden" name="types[]" value="classified_bg_color">
												<input type="text" class="form-control aiz-color-input" placeholder="#000000" name="classified_bg_color" value="{{ $classified_bg_color }}">
												<div class="input-group-append">
													<span class="input-group-text p-0">
														<input class="aiz-color-picker border-0 size-40px" type="color" value="{{ $classified_bg_color }}">
													</span>
												</div>
											</div>
										</div>
									</div>

								</div>
								<!-- Save Button -->
								<div class="mt-4 text-right">
									<button type="submit" class="btn btn-success w-230px btn-md rounded-2 fs-14 fw-700 shadow-success">{{ translate('Save') }}</button>
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
    <script type="text/javascript">
		$(document).ready(function(){
		    AIZ.plugins.bootstrapSelect('refresh');
		});
    </script>
	<script>
		$(document).ready(function(){
			var hash = document.location.hash;
			if (hash) {
				$('.nav-tabs a[href="'+hash+'"]').tab('show');
			}else{
				$('.nav-tabs a[href="#home_slider"]').tab('show');
			}

			// Change hash for page-reload
			$('.nav-tabs a').on('shown.bs.tab', function (e) {
				window.location.hash = e.target.hash;
			});
		});
	</script>
@endsection

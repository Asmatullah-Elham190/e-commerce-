@extends('backend.layouts.app')

@section('content')


    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="mb-3">
                <h6 class="fw-600 mb-2">{{ translate('Banners & Sliders') }}</h6>
                <a href="{{ route('website.dashboard') }}" class="fs-14 fw-500 text-reset hov-text-blue has-transition">
                    <i class="las la-arrow-left fs-14"></i>
                    {{translate('Back to Design Studio Home')}}
                </a>
            </div>
            <div class="card">
                <div class="card-header">
                    <h6 class="fw-600 mb-0">{{ translate('Flash Deal') }}</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('business_settings.update') }}" method="POST">
                        @csrf

                        <!-- Flash Deal Page Banner - Large -->
                        <div class="form-group mb-4">
                            <label class="col-from-label">{{ translate('Flash Deal Page Banner - Large') }}</label>
                            <div class="add-product-page-content">
                                <div class="img-upload-container">
                                    <div class="input-group file-upload-input border border-dashed border-gray-400 rounded-1 w-120px h-120px d-flex align-items-center justify-content-center"
                                        data-toggle="aizuploader" data-type="image" data-multiple="false">
                                        <div
                                            class="form-control p-0 border-0 d-flex align-items-center justify-content-center">
                                            <img src="{{ static_asset('assets/img/plus-lg.svg') }}"
                                                class="w-40px h-40px w-md-64px h-md-64px" alt="generate Icon">
                                        </div>
                                        <input type="hidden" name="types[]" value="flash_deal_banner">
                                        <input type="hidden" name="flash_deal_banner" class="selected-files"
                                            value="{{ get_setting('flash_deal_banner') }}">
                                    </div>
                                    <div class="file-preview box sm"></div>
                                </div>
                            </div>

                            <small
                                class="text-muted">{{ translate('Will be shown in large device. Minimum dimensions required: 1370px width X 242px height.') }}</small>

                        </div>
                        <!-- Flash Deal Page Banner - Small -->
                        <div class="form-group mb-4">
                            <label class="col-from-label">{{ translate('Flash Deal Page Banner - Small') }}</label>
                            <div class="add-product-page-content">
                                <div class="img-upload-container">
                                    <div class="input-group file-upload-input border border-dashed border-gray-400 rounded-1 w-120px h-120px d-flex align-items-center justify-content-center"
                                        data-toggle="aizuploader" data-type="image" data-multiple="false">
                                        <div
                                            class="form-control p-0 border-0 d-flex align-items-center justify-content-center">
                                            <img src="{{ static_asset('assets/img/plus-lg.svg') }}"
                                                class="w-40px h-40px w-md-64px h-md-64px" alt="generate Icon">
                                        </div>
                                        <input type="hidden" name="types[]" value="flash_deal_banner_small">
                                        <input type="hidden" name="flash_deal_banner_small" class="selected-files"
                                            value="{{ get_setting('flash_deal_banner_small') }}">
                                    </div>
                                    <div class="file-preview box sm"></div>
                                </div>
                            </div>
                            <small
                                class="text-muted">{{ translate('Will be shown in small device. Minimum dimensions required: 400px width X 184px height.') }}</small>
                        </div>
                        <!-- Update Button -->
                        <div class="mt-4 text-right">
                            <button type="submit"
                                class="btn btn-success w-230px btn-md rounded-2 fs-14 fw-700 shadow-success">{{ translate('Update') }}</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h6 class="fw-600 mb-0">{{ translate('Megamenu') }}</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('business_settings.update') }}" method="POST">
                        @csrf
                        <div class="form-group mb-4">
                            <label class="col-from-label">{{ translate('Megamenu Banner 1') }}</label>
                            <div class="add-product-page-content">
                                <div class="img-upload-container">
                                    <div class="input-group file-upload-input border border-dashed border-gray-400 rounded-1 w-120px h-120px d-flex align-items-center justify-content-center"
                                        data-toggle="aizuploader" data-type="image" data-multiple="false">
                                        <div
                                            class="form-control p-0 border-0 d-flex align-items-center justify-content-center">
                                            <img src="{{ static_asset('assets/img/plus-lg.svg') }}"
                                                class="w-40px h-40px w-md-64px h-md-64px" alt="generate Icon">
                                        </div>
                                        <input type="hidden" name="types[]" value="megamenu_banner1">
                                        <input type="hidden" name="megamenu_banner1" class="selected-files"
                                            value="{{ get_setting('megamenu_banner1') }}">
                                    </div>
                                    <div class="file-preview box sm"></div>
                                </div>
                                <small
                                    class="text-muted">{{ translate('Minimum dimensions required: 600px width X 160px height.') }}</small>
                            </div>

                        </div>
                        <div class="form-group mb-4">
                            <label class="col-from-label">{{ translate('Megamenu Banner 2') }}</label>
                            <div class="add-product-page-content">
                                <div class="img-upload-container">
                                    <div class="input-group file-upload-input border border-dashed border-gray-400 rounded-1 w-120px h-120px d-flex align-items-center justify-content-center"
                                        data-toggle="aizuploader" data-type="image" data-multiple="false">
                                        <div
                                            class="form-control p-0 border-0 d-flex align-items-center justify-content-center">
                                            <img src="{{ static_asset('assets/img/plus-lg.svg') }}"
                                                class="w-40px h-40px w-md-64px h-md-64px" alt="generate Icon">
                                        </div>
                                        <input type="hidden" name="types[]" value="megamenu_banner2">
                                        <input type="hidden" name="megamenu_banner2" class="selected-files"
                                            value="{{ get_setting('megamenu_banner2') }}">
                                    </div>
                                    <div class="file-preview box sm"></div>
                                </div>
                                <small
                                    class="text-muted">{{ translate('Minimum dimensions required: 600px width X 160px height.') }}</small>
                            </div>
                        </div>
                        <div class="mt-4 text-right">
                            <button type="submit"
                                class="btn btn-success w-230px btn-md rounded-2 fs-14 fw-700 shadow-success">{{ translate('Update') }}</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-header align-items-start flex-column">
                    <h6 class="fw-600 mb-0">{{ translate('Category') }}</h6>
                    <small class="text-muted">({{ translate('Minimum dimensions required: 312px width X 400px height') }})</small>
                </div>
                <div class="card-body">
                    <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
						@csrf
						<input type="hidden" name="types[]" value="main_category_id">
						<input type="hidden" name="types[]" value="main_category_banner">
						
						<div class="bg-white">
							<div class="w-100">
								<!-- Images & links -->
								<div class="home-banner1-target">
									@php
										$main_category_id = get_setting('main_category_id', null);
										$main_category_banner = get_setting('main_category_banner', null);
										$allMainCategories = \App\Models\Category::where('parent_id', 0)
											->with('childrenCategories')
											->get();
									@endphp
									@if ($main_category_id != null)
										@foreach (json_decode($main_category_id, true) as $key => $value)
											<div class="p-3 p-md-4 mb-3 mb-md-2rem remove-parent"
												style="border: 1px dashed #e4e5eb;">
												<div class="row gutters-5">
                                                    <!-- link -->
													<div class="col-md">
                                                        <label class="col-from-label">{{ translate('Main Category') }}</label>
														<div class="form-group mb-md-0">
															<select class="form-control aiz-selectpicker"
																name="main_category_id[]"
																data-live-search="true"
																data-selected="{{ $value }}"
																required>
																<option value="">{{ translate('Select Main Category') }}</option>
																@foreach ($allMainCategories as $category)
																	<option value="{{ $category->id }}" {{ $value == $category->id ? 'selected' : '' }}>
																		{{ $category->getTranslation('name') }}
																	</option>
																@endforeach
															</select>
														</div>
													</div>
													<!-- Image -->
													<div class="col-md-5">
                                                        <label class="col-from-label">{{ translate('Banner') }}</label>
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
																<input type="hidden" name="main_category_banner[]"
																	class="selected-files"
																	value="{{ json_decode($main_category_banner, true)[$key] }}">
															</div>
															<div class="file-preview box sm">
															</div>
														</div>
													</div>
													<!-- remove parent button -->
													<div class="col-md-auto">
														<div class="form-group mb-md-0 mt-4">
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
                                                        <!-- link -->
														<div class="col-md">
                                                            <label class="col-from-label">{{ translate('Main Category') }}</label>
															<div class="form-group mb-md-0 mb-0">
																<select class="form-control aiz-selectpicker"
                                                                    name="main_category_id[]"
                                                                    data-live-search="true"
                                                                    required>
                                                                    <option value="">{{ translate('Select Main Category') }}</option>
                                                                    @foreach ($allMainCategories as $category)
                                                                        <option value="{{ $category->id }}">
                                                                            {{ $category->getTranslation('name') }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
															</div>
														</div>
														<!-- Image -->
														<div class="col-md-5">
                                                            <label class="col-from-label">{{ translate('Banner') }}</label>
															<div class="form-group mb-md-0">
																<div class="input-group" data-toggle="aizuploader" data-type="image">
																	<div class="input-group-prepend">
																		<div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
																	</div>
																	<div class="form-control file-amount">{{ translate('Choose File') }}</div>
																	<input type="hidden" name="main_category_banner[]" class="selected-files" value="">
																</div>
																<div class="file-preview box sm">
																</div>
															</div>
														</div>
														<!-- remove parent button -->
														<div class="col-md-auto">
															<div class="form-group mb-md-0 mt-4">
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
									class="btn btn-success w-230px btn-md rounded-2 fs-14 fw-700 shadow-success">{{ translate('Update') }}</button>
							</div>
						</div>
					</form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script type="text/javascript">
        $('select[name="image_watermark_type"]').on('change', function () {
            let val = $(this).val();
            if (val == 'image') {
                $('#watermark_image').removeClass('d-none');
                $('#watermark_text').addClass('d-none');
            } else {
                $('#watermark_text').removeClass('d-none');
                $('#watermark_image').addClass('d-none');
            }
        });
    </script>
@endsection
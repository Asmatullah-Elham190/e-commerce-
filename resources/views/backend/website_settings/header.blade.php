@extends('backend.layouts.app')

@section('content')

	<div class="aiz-titlebar text-left mt-2 mb-3">
		<div class="row align-items-center">
			<div class="col">
				<h1 class="h3">{{ translate('Selected Header') }}</h1>
			</div>
		</div>
	</div>

	@include('header.' .get_element_type_by_id(get_setting('header_element')))
	<br>

	<div class="row">
		<div class="col-md-8 mx-auto">
			<div class="card">
				<div class="card-header">
					<h6 class="mb-0">{{ translate('Header Setting') }}</h6>
				</div>

				<div class="card-body">
					<form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
						@csrf
						<!-- Header Logo -->
						<div class="form-group row">
							<label class="col-md-3 col-from-label">{{ translate('Header Logo') }}</label>
							<div class="col-md-8">
								<div class=" input-group " data-toggle="aizuploader" data-type="image">
									<div class="input-group-prepend">
										<div class="input-group-text bg-soft-secondary font-weight-medium">
											{{ translate('Browse') }}
										</div>
									</div>
									<div class="form-control file-amount">{{ translate('Choose File') }}</div>
									<input type="hidden" name="types[]" value="header_logo">
									<input type="hidden" name="header_logo" class="selected-files"
										value="{{ get_setting('header_logo') }}">
								</div>
								<div class="file-preview"></div>
								<small
									class="text-muted">{{ translate("Minimum dimensions required: 244px width X 40px height.") }}</small>
							</div>
						</div>
						<!-- Full Width Header -->
						<div class="form-group row">
							<label class="col-md-3 col-from-label">{{translate('Show Full Width Header')}}</label>
							<div class="col-md-8">
								<label class="aiz-switch aiz-switch-success mb-0">
									<input type="hidden" name="types[]" value="show_full_width_header">
									<input type="checkbox" name="show_full_width_header"
										@if(get_setting('show_full_width_header') == 'on') checked @endif>
									<span></span>
								</label>
							</div>
						</div>
						<!-- Show Language Switcher -->
						<div class="form-group row">
							<label class="col-md-3 col-from-label">{{translate('Show Language Switcher?')}}</label>
							<div class="col-md-8">
								<label class="aiz-switch aiz-switch-success mb-0">
									<input type="hidden" name="types[]" value="show_language_switcher">
									<input type="checkbox" name="show_language_switcher"
										@if(get_setting('show_language_switcher') == 'on') checked @endif>
									<span></span>
								</label>
							</div>
						</div>
						<!-- Show Currency Switcher -->
						<div class="form-group row">
							<label class="col-md-3 col-from-label">{{translate('Show Currency Switcher?')}}</label>
							<div class="col-md-8">
								<label class="aiz-switch aiz-switch-success mb-0">
									<input type="hidden" name="types[]" value="show_currency_switcher">
									<input type="checkbox" name="show_currency_switcher"
										@if(get_setting('show_currency_switcher') == 'on') checked @endif>
									<span></span>
								</label>
							</div>
						</div>
						<!-- Enable stikcy header -->
						<div class="form-group row">
							<label class="col-md-3 col-from-label">{{translate('Enable stikcy header?')}}</label>
							<div class="col-md-8">
								<label class="aiz-switch aiz-switch-success mb-0">
									<input type="hidden" name="types[]" value="header_stikcy">
									<input type="checkbox" name="header_stikcy" @if(get_setting('header_stikcy') == 'on')
									checked @endif>
									<span></span>
								</label>
							</div>
						</div>

						<div class="border-top pt-3">
							@foreach($element_type->element_styles as $style)

							<div class="form-group row">
								<label class="col-md-3 col-from-label">{{ translate($style->name) }}</label>
								<div class="col-md-8">
									<div class="input-group">
										<input type="hidden" name="types[]" value="{{ $style->name }}">
										<input type="text" class="form-control aiz-color-input" placeholder="#000000"
											name="{{ $style->name }}" value="{{ get_setting($style->name) }}">
										<div class="input-group-append">
											<span class="input-group-text p-0">
												<input data-target="{{ $style->name }}"
													class="aiz-color-picker border-0 size-40px" type="color"
													value="{{ get_setting($style->name) }}">
											</span>
										</div>
									</div>
								</div>
							</div>
							@endforeach
							<!-- Help line number -->
							<div class="form-group row">
								<label class="col-md-3 col-from-label">{{translate('Help line number')}}</label>
								<div class="col-md-8">
									<div class="form-group">
										<input type="hidden" name="types[]" value="helpline_number">
										<input type="text" class="form-control"
											placeholder="{{ translate('Help line number') }}" name="helpline_number"
											value="{{ get_setting('helpline_number') }}">
									</div>
								</div>
							</div>
							<div class="border-top pt-3">
								<!-- Header Nav Menus -->
								<label class="">{{translate('Header Nav Menu')}}</label>
								<div class="header-nav-menu">
									<input type="hidden" name="types[]" value="header_menu_labels">
									<input type="hidden" name="types[]" value="header_menu_links">
									@if (get_setting('header_menu_labels') != null)
										@foreach (json_decode(get_setting('header_menu_labels'), true) as $key => $value)
											<div class="row gutters-5">
												<div class="col-4">
													<div class="form-group">
														<input type="text" class="form-control" placeholder="{{translate('Label')}}"
															name="header_menu_labels[]" value="{{ $value }}">
													</div>
												</div>
												<div class="col">
													<div class="form-group">
														<input type="text" class="form-control"
															placeholder="{{ translate('Link with') }} http:// {{ translate('or') }} https://"
															name="header_menu_links[]"
															value="{{ json_decode(App\Models\BusinessSetting::where('type', 'header_menu_links')->first()->value, true)[$key] }}">
													</div>
												</div>
												<div class="col-auto">
													<button type="button"
														class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger"
														data-toggle="remove-parent" data-parent=".row">
														<i class="las la-times"></i>
													</button>
												</div>
											</div>
										@endforeach
									@endif
								</div>
								<button type="button" class="btn btn-soft-secondary btn-sm" data-toggle="add-more"
									data-content='<div class="row gutters-5">
										<div class="col-4">
											<div class="form-group">
												<input type="text" class="form-control" placeholder="{{translate('Label')}}" name="header_menu_labels[]">
											</div>
										</div>
										<div class="col">
											<div class="form-group">
												<input type="text" class="form-control" placeholder="{{ translate('Link with') }} http:// {{ translate('or') }} https://" name="header_menu_links[]">
											</div>
										</div>
										<div class="col-auto">
											<button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger" data-toggle="remove-parent" data-parent=".row">
											<i class="las la-times"></i>
										</button>
											</div>
										</div>' data-target=".header-nav-menu">
									{{ translate('Add New') }}
								</button>
							</div>
							<br>
							<!-- Update Button -->
							<div class="mt-4 text-right">
								<button type="submit"
									class="btn btn-success w-230px btn-md rounded-2 fs-14 fw-700 shadow-success">{{ translate('Update') }}</button>
							</div>
					</form>
				</div>
			</div>
		</div>
	</div>

@endsection

{{-- modal --}}
@section('modal')
	<div class="image-show-overlay" id="image-show-overlay">
		<div class="d-flex justify-content-end my-3 mr-3">
			<button type="button" class="btn text-white d-flex align-items-center justify-content-center"><i
					class="las la-2x la-times"></i></button>
		</div>
		<div class="overlay-img">
			<img src="{{ static_asset('assets/img/authentication_pages/boxed.png') }}" class="w-100" alt="img-show">
		</div>
	</div>
@endsection

@section('script')
	{{-- Language,currency, stikcy header visibility --}}
	<script>
		$(document).ready(function () {
			function toggleVisibility(inputName, targetClass, toggleClass = null) {
				const isChecked = $(`input[name="${inputName}"]`).is(':checked');

				if (toggleClass) {
					if (isChecked) {
						$(`.${targetClass}`).addClass(toggleClass);
					} else {
						$(`.${targetClass}`).removeClass(toggleClass);
					}
				} else {
					if (isChecked) {
						$(`.${targetClass}`).removeClass('d-none');
					} else {
						$(`.${targetClass}`).addClass('d-none');
					}
				}
			}

			function updateUI() {
				toggleVisibility('show_language_switcher', 'lang-visibility');
				toggleVisibility('show_currency_switcher', 'currency-visibility');
				toggleVisibility('header_stikcy', 'stikcy-header-visibility', 'sticky-top');
			}

			updateUI();

			$('input[name="show_language_switcher"], input[name="show_currency_switcher"], input[name="header_stikcy"]').on('change', function () {
				updateUI();
			});
		});
	</script>

	{{-- top_header_bg_color --}}
	<script>
		$(document).ready(function () {
			function updateTopHeaderBgColor() {
				const newColor = $('input[name="top_header_bg_color"]').val();
				if (newColor) {
					$('.top-background-color-visibility').css('background-color', newColor);
				}
			}

			$('input[name="top_header_bg_color"]').on('input keyup change', function (e) {
				e.preventDefault();
				updateTopHeaderBgColor();
			});

			$('.aiz-color-picker').on('input change', function (e) {
				e.preventDefault();
				const color = $(this).val();
				const target = $(this).data('target');
				$('input[name="' + target + '"]').val(color).trigger('change');
			});

			updateTopHeaderBgColor();
		});
	</script>

	{{-- middle_header_bg_color --}}
	<script>
		$(document).ready(function () {
			function updateMiddleHeaderBgColor() {
				const newColor = $('input[name="middle_header_bg_color"]').val();
				if (newColor) {
					$('.middle-background-color-visibility').css('background-color', newColor);
				}
			}

			$('input[name="middle_header_bg_color"]').on('input keyup change', function (e) {
				e.preventDefault();
				updateMiddleHeaderBgColor();
			});

			$('.aiz-color-picker').on('input change', function (e) {
				e.preventDefault();
				const color = $(this).val();
				const target = $(this).data('target');
				$('input[name="' + target + '"]').val(color).trigger('change');
			});

			updateMiddleHeaderBgColor();
		});
	</script>

	{{-- bottom_header_bg_color --}}
	<script>
		$(document).ready(function () {
			function updateBottomHeaderBgColor() {
				const newColor = $('input[name="bottom_header_bg_color"]').val();
				if (newColor) {
					$('.bottom-background-color-visibility').css('background-color', newColor);
				}
			}

			$('input[name="bottom_header_bg_color"]').on('input keyup change', function (e) {
				e.preventDefault();
				updateBottomHeaderBgColor();
			});

			$('.aiz-color-picker').on('input change', function (e) {
				e.preventDefault();
				const color = $(this).val();
				const target = $(this).data('target');
				$('input[name="' + target + '"]').val(color).trigger('change');
			});

			updateBottomHeaderBgColor();
		});
	</script>

	{{-- top_header_text_color --}}
	<script>
		$(document).ready(function () {
			function updateTopHeaderTextColor(name, cssProp, selector) {
				const newColor = $('input[name="' + name + '"]').val();
				if (newColor) {
					$(selector).css(cssProp, newColor);
				}
			}
			$('input[name="top_header_text_color"]').on('input change', function () {
				updateTopHeaderTextColor('top_header_text_color', 'color', '.top-text-color-visibility');
			});

			$('.aiz-color-picker').on('input change', function () {
				const color = $(this).val();
				const target = $(this).data('target');
				$('input[name="' + target + '"]').val(color).trigger('change');
			});

			updateTopHeaderTextColor('top_header_text_color', 'color', '.top-text-color-visibility');
		});

	</script>

	{{-- middle_header_text_color --}}
	<script>
		$(document).ready(function () {
			function updateMiddleHeaderTextColor(name, cssProp, selector) {
				const newColor = $('input[name="' + name + '"]').val();
				if (newColor) {
					$(selector).css(cssProp, newColor);
				}
			}
			$('input[name="middle_header_text_color"]').on('input change', function () {
				updateMiddleHeaderTextColor('middle_header_text_color', 'color', '.middle-text-color-visibility');
			});

			$('.aiz-color-picker').on('input change', function () {
				const color = $(this).val();
				const target = $(this).data('target');
				$('input[name="' + target + '"]').val(color).trigger('change');
			});

			updateMiddleHeaderTextColor('middle_header_text_color', 'color', '.middle-text-color-visibility');
		});
	</script>

	{{-- bottom_header_text_color --}}
	<script>
		$(document).ready(function () {
			function updateBottomHeaderTextColor(name, cssProp, selector) {
				const newColor = $('input[name="' + name + '"]').val();
				if (newColor) {
					$(selector).css(cssProp, newColor);
				}
			}
			$('input[name="bottom_header_text_color"]').on('input change', function () {
				updateBottomHeaderTextColor('bottom_header_text_color', 'color', '.bottom-text-color-visibility');
			});

			$('.aiz-color-picker').on('input change', function () {
				const color = $(this).val();
				const target = $(this).data('target');
				$('input[name="' + target + '"]').val(color).trigger('change');
			});

			updateBottomHeaderTextColor('bottom_header_text_color', 'color', '.bottom-text-color-visibility');
		});
	</script>

	<script>
		$(document).ready(function () {
			let previousFileId = null;

			setInterval(function () {
				let fileId = $('.selected-files[name="header_logo"]').val();

				if (fileId && fileId !== previousFileId) {
					previousFileId = fileId;

					$.ajax({
						url: 'get-upload-file-name',
						method: 'POST',
						data: {
							_token: '{{ csrf_token() }}',
							id: fileId
						},
						success: function (res) {
							if (res.success) {
								// Full image path with domain + public
								let imagePath = '{{ url('public') }}/' + res.file_name;

								// Set to preview image
								$('#header-logo-preview').attr('src', imagePath);

								console.log("Live Image Path:", imagePath);
							}
							else {
								alert(res.message);
							}
						},
						error: function () {
							alert("Something went wrong.");
						}
					});
				}
			}, 500);
		});
	</script>

	<script>
		$(document).ready(function () {
			const helplineContainer = $('#admin-helpline-preview .helpline-container');
			const previewElement = $('#admin-helpline-preview');

			function updateHelplineNumber() {
				const newNumber = $('input[name="helpline_number"]').val().trim();

				if (newNumber === '') {
					previewElement.hide();
				} else {
					previewElement.show();
					$('.helpline-number-preview').text(newNumber);
				}
			}

			$('input[name="helpline_number"]').on('input keyup change', function () {
				updateHelplineNumber();
			});

			updateHelplineNumber();
		});
	</script>

@endsection
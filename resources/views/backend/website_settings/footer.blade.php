@extends('backend.layouts.app')

@section('content')

<div class="col-md-10 mx-auto">
    <div class="aiz-titlebar text-left mt-2 mb-3">
    	<div class="row align-items-center">
    		<div class="col">
    			<h1 class="h3">{{ translate('Website Footer') }}</h1>
    		</div>
    	</div>
    </div>

	<!-- Language -->
    <ul class="nav nav-tabs nav-fill language-bar">
        @foreach (get_all_active_language() as $key => $language)
            <li class="nav-item">
                <a class="nav-link text-reset @if ($language->code == $lang) active @endif py-3" href="{{ route('website.footer', ['lang'=> $language->code] ) }}">
                    <img src="{{ static_asset('assets/img/flags/'.$language->code.'.png') }}" height="11" class="mr-1">
                    <span>{{$language->name}}</span>
                </a>
            </li>
        @endforeach
    </ul>

	<!-- Footer Widget -->
    <div class="card">
    	<div class="row gutters-10">
			<!-- Sub Footer Design -->
            <div class="col-lg-12">
    			<div class="card-header">
    				<h6 class="mb-0">{{ translate('Sub Footer') }}</h6>
    			</div>
    			<div class="card-body">
                    <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
						<div class="form-group d-flex justify-content-between align-items-center">
							<input type="hidden" name="types[][{{ $lang }}]" value="show_full_width_sub_footer">
							<div class="d-flex align-items-center">
								<label class="aiz-switch aiz-switch-blue mb-0 pr-2">
									<input type="checkbox" name="show_full_width_sub_footer" value="1"
										{{ (get_setting('show_full_width_sub_footer') )?? 0 == 1 ? 'checked' : '' }}>
									<span></span>
								</label>
								<span class="d-block" style="margin-top: -6px">{{ translate('Show Full Width Sub Footer') }}</span>
							</div>
						</div>
						<div class="form-group d-flex justify-content-between align-items-center">
							<input type="hidden" name="types[][{{ $lang }}]" value="enable_sub_footer_section">
							<div class="d-flex align-items-center">
								<label class="aiz-switch aiz-switch-blue mb-0 pr-2">
									<input type="checkbox" name="enable_sub_footer_section" value="1"
										{{ (get_setting('enable_sub_footer_section') )?? 0 == 1 ? 'checked' : '' }}>
									<span></span>
								</label>
								<span class="d-block" style="margin-top: -6px">{{ translate('Enable Sub Footer') }}</span>
							</div>
						</div>
						<!-- Background Color -->
    					<div class="form-group">
    						<label>{{ translate('Background Color') }}</label>
    						<input type="hidden" name="types[]" value="sub_footer_bg_color">
							<div class="input-group">
								<input type="text" class="form-control aiz-color-input" name="sub_footer_bg_color" value="{{ get_setting('sub_footer_bg_color') }}" 
									placeholder="Ex: #e1e1e1">
								<div class="input-group-append">
									<span class="input-group-text p-0">
										<input class="aiz-color-picker border-0 size-40px" type="color" value="{{get_setting('sub_footer_bg_color') }}">
									</span>
								</div>
							</div>
    					</div>
						<!-- Text Color -->
    		            <div class="form-group">
    						<label>{{ translate('Text Color') }}</label>
    						<input type="hidden" name="types[]" value="sub_footer_text_color">
    						<div class="d-flex align-items-center">
								<!-- Light Option -->
								<label class="aiz-megabox d-block bg-white mb-0 mr-3" style="flex: 1;">
									<input type="radio" name="sub_footer_text_color" value="white" @checked(get_setting('sub_footer_text_color') == 'white')>
									<span class="d-flex align-items-center aiz-megabox-elem rounded-0"
										style="padding: 0.75rem 1.2rem;">
										<span class="aiz-rounded-check flex-shrink-0 mt-1"></span>
										<span class="flex-grow-1 pl-3 fw-600">{{ translate('Light') }}</span>
									</span>
								</label>
								<!-- Dark Option -->
								<label class="aiz-megabox d-block bg-white mb-0" style="flex: 1;">
									<input type="radio" name="sub_footer_text_color" value="black" @checked(get_setting('sub_footer_text_color') == 'black')>
									<span class="d-flex align-items-center aiz-megabox-elem rounded-0"
										style="padding: 0.75rem 1.2rem;">
										<span class="aiz-rounded-check flex-shrink-0 mt-1"></span>
										<span class="flex-grow-1 pl-3 fw-600">{{ translate('Dark') }}</span>
									</span>
								</label>
							</div>
    					</div>
						<!-- Title -->
    					<div class="form-group">
    						<label>{{ translate('Title') }} ({{ translate('Translatable') }})</label>
    						<input type="hidden" name="types[][{{ $lang }}]" value="footer_title">
    						<input type="text" class="form-control" placeholder="Footer title" name="footer_title" value="{{ get_setting('footer_title',null,$lang) }}">
    					</div>
						<!-- About description -->
    		            <div class="form-group">
    						<label>{{ translate('Footer description') }} ({{ translate('Translatable') }})</label>
    						<input type="hidden" name="types[][{{ $lang }}]" value="footer_description">
    						<textarea class="form-control" name="footer_description" rows="6" placeholder="Type.." >{{ get_setting('footer_description',null,$lang); }}</textarea>
    					</div>
						<!-- Update Button -->
						<div class="mt-4 text-right">
							<button type="submit" class="btn btn-success w-230px btn-md rounded-2 fs-14 fw-700 shadow-success">{{ translate('Update') }}</button>
						</div>
    				</form>
    			</div>
    		</div>
    	</div>
    </div>

	<!-- Footer Widget -->
    <div class="card">
    	<div class="row gutters-10">
			<!-- Policy Section Design -->
            <div class="col-lg-12">
    			<div class="card-header">
    				<h6 class="mb-0">{{ translate('Policy Section') }}</h6>
    			</div>
    			<div class="card-body">
                    <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
						<div class="form-group d-flex justify-content-between align-items-center">
							<input type="hidden" name="types[][{{ $lang }}]" value="show_full_width_policy_section">
							<div class="d-flex align-items-center">
								<label class="aiz-switch aiz-switch-blue mb-0 pr-2">
									<input type="checkbox" name="show_full_width_policy_section" value="1"
										{{ (get_setting('show_full_width_policy_section') )?? 0 == 1 ? 'checked' : '' }}>
									<span></span>
								</label>
								<span class="d-block" style="margin-top: -6px">{{ translate('Show Full Width Policy Section') }}</span>
							</div>
						</div>
						<div class="form-group d-flex justify-content-between align-items-center">
							<input type="hidden" name="types[][{{ $lang }}]" value="enable_footer_policy_section">
							<div class="d-flex align-items-center">
								<label class="aiz-switch aiz-switch-blue mb-0 pr-2">
									<input type="checkbox" name="enable_footer_policy_section" value="1"
										{{ (get_setting('enable_footer_policy_section') )?? 0 == 1 ? 'checked' : '' }}>
									<span></span>
								</label>
								<span class="d-block" style="margin-top: -6px">{{ translate('Enable Policy Section') }}</span>
							</div>
						</div>
						<!-- Background Color -->
    					<div class="form-group">
    						<label>{{ translate('Background Color') }}</label>
    						<input type="hidden" name="types[]" value="policy_section_bg_color">
							<div class="input-group">
								<input type="text" class="form-control aiz-color-input" name="policy_section_bg_color" value="{{ get_setting('policy_section_bg_color') }}" 
									placeholder="Ex: #e1e1e1">
								<div class="input-group-append">
									<span class="input-group-text p-0">
										<input class="aiz-color-picker border-0 size-40px" type="color" value="{{get_setting('policy_section_bg_color') }}">
									</span>
								</div>
							</div>
    					</div>
						<!-- Text Color -->
    		            <div class="form-group">
    						<label>{{ translate('Text Color') }}</label>
    						<input type="hidden" name="types[]" value="policy_section_text_color">
    						<div class="d-flex align-items-center">
								<!-- Light Option -->
								<label class="aiz-megabox d-block bg-white mb-0 mr-3" style="flex: 1;">
									<input type="radio" name="policy_section_text_color" value="white" @checked(get_setting('policy_section_text_color') == 'white')>
									<span class="d-flex align-items-center aiz-megabox-elem rounded-0"
										style="padding: 0.75rem 1.2rem;">
										<span class="aiz-rounded-check flex-shrink-0 mt-1"></span>
										<span class="flex-grow-1 pl-3 fw-600">{{ translate('Light') }}</span>
									</span>
								</label>
								<!-- Dark Option -->
								<label class="aiz-megabox d-block bg-white mb-0" style="flex: 1;">
									<input type="radio" name="policy_section_text_color" value="black" @checked(get_setting('policy_section_text_color') == 'black')>
									<span class="d-flex align-items-center aiz-megabox-elem rounded-0"
										style="padding: 0.75rem 1.2rem;">
										<span class="aiz-rounded-check flex-shrink-0 mt-1"></span>
										<span class="flex-grow-1 pl-3 fw-600">{{ translate('Dark') }}</span>
									</span>
								</label>
							</div>
    					</div>
						<!-- Update Button -->
						<div class="mt-4 text-right">
							<button type="submit" class="btn btn-success w-230px btn-md rounded-2 fs-14 fw-700 shadow-success">{{ translate('Update') }}</button>
						</div>
    				</form>
    			</div>
    		</div>
    	</div>
    </div>

	<!-- Footer Widget -->
    <div class="card">
    	<div class="row gutters-10">
			<!-- Footer Design -->
            <div class="col-lg-12">
    			<div class="card-header">
    				<h6 class="mb-0">{{ translate('Footer Design') }}</h6>
    			</div>
    			<div class="card-body">
                    <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
						<div class="form-group d-flex justify-content-between align-items-center">
							<input type="hidden" name="types[][{{ $lang }}]" value="show_full_width_footer">
							<div class="d-flex align-items-center">
								<label class="aiz-switch aiz-switch-blue mb-0 pr-2">
									<input type="checkbox" name="show_full_width_footer" value="1"
										{{ (get_setting('show_full_width_footer') )?? 0 == 1 ? 'checked' : '' }}>
									<span></span>
								</label>
								<span class="d-block" style="margin-top: -6px">{{ translate('Show Full Width Footer') }}</span>
							</div>
						</div>
						<!-- Background Color -->
    					<div class="form-group">
    						<label>{{ translate('Background Color') }}</label>
    						<input type="hidden" name="types[]" value="footer_bg_color">
							<div class="input-group">
								<input type="text" class="form-control aiz-color-input" name="footer_bg_color" value="{{ get_setting('footer_bg_color') }}" 
									placeholder="Ex: #e1e1e1">
								<div class="input-group-append">
									<span class="input-group-text p-0">
										<input class="aiz-color-picker border-0 size-40px" type="color" value="{{get_setting('footer_bg_color') }}">
									</span>
								</div>
							</div>
    					</div>
						<!-- Text Color -->
    		            <div class="form-group">
    						<label>{{ translate('Text Color') }}</label>
    						<input type="hidden" name="types[]" value="footer_text_color">
    						<div class="d-flex align-items-center">
								<!-- Light Option -->
								<label class="aiz-megabox d-block bg-white mb-0 mr-3" style="flex: 1;">
									<input type="radio" name="footer_text_color" value="white" @checked(get_setting('footer_text_color') == 'white' || get_setting('footer_text_color') == '#ffffff')>
									<span class="d-flex align-items-center aiz-megabox-elem rounded-0"
										style="padding: 0.75rem 1.2rem;">
										<span class="aiz-rounded-check flex-shrink-0 mt-1"></span>
										<span class="flex-grow-1 pl-3 fw-600">{{ translate('Light') }}</span>
									</span>
								</label>
								<!-- Dark Option -->
								<label class="aiz-megabox d-block bg-white mb-0" style="flex: 1;">
									<input type="radio" name="footer_text_color" value="black" @checked(get_setting('footer_text_color') == 'black' || get_setting('footer_text_color') == '#000000')>
									<span class="d-flex align-items-center aiz-megabox-elem rounded-0"
										style="padding: 0.75rem 1.2rem;">
										<span class="aiz-rounded-check flex-shrink-0 mt-1"></span>
										<span class="flex-grow-1 pl-3 fw-600">{{ translate('Dark') }}</span>
									</span>
								</label>
							</div>
    					</div>
						<!-- Update Button -->
						<div class="mt-4 text-right">
							<button type="submit" class="btn btn-success w-230px btn-md rounded-2 fs-14 fw-700 shadow-success">{{ translate('Update') }}</button>
						</div>
    				</form>
    			</div>
    		</div>
    	</div>
    </div>

	<!-- Footer Widget -->
    <div class="card">
    	<div class="row gutters-10">
			<!-- About Widget -->
    		<div class="col-lg-12">
    			<div class="card-header">
    				<h6 class="mb-0">{{ translate('About Widget') }}</h6>
    			</div>
    			<div class="card-body">
    				<form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
    					@csrf
						<!-- Footer Logo -->
    					<div class="form-group">
    		                <label class="form-label" for="signinSrEmail">{{ translate('Footer Logo') }}</label>
    		                <div class="input-group " data-toggle="aizuploader" data-type="image">
    		                    <div class="input-group-prepend">
    		                        <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse') }}</div>
    		                    </div>
    		                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
    							<input type="hidden" name="types[]" value="footer_logo">
    		                    <input type="hidden" name="footer_logo" class="selected-files" value="{{ get_setting('footer_logo') }}">
    		                </div>
    						<div class="file-preview"></div>
                            <small class="text-muted">{{ translate("Minimum dimensions required: 275px width X 44px height.") }}</small>
    		            </div>
						<!-- About description -->
    		            <div class="form-group">
    						<label>{{ translate('About description') }} ({{ translate('Translatable') }})</label>
    						<input type="hidden" name="types[][{{ $lang }}]" value="about_us_description">
    						<textarea class="form-control" name="about_us_description" placeholder="Type.." row="3" maxlength="240">{!! get_setting('about_us_description',null,$lang); !!}</textarea>
    					</div>
						<!-- Update Button -->
						<div class="mt-4 text-right">
							<button type="submit" class="btn btn-success w-230px btn-md rounded-2 fs-14 fw-700 shadow-success">{{ translate('Update') }}</button>
						</div>
    				</form>
    			</div>
    		</div>
    	</div>
    </div>

	<!-- Footer Widget -->
    <div class="card">
    	<div class="row gutters-10">
			<!-- About Widget -->
    		<div class="col-lg-12">
    			<div class="card-header">
    				<h6 class="mb-0">{{ translate('Social Link Widget') }}</h6>
    			</div>
    			<div class="card-body">
    				<form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
    					@csrf
						<div class="form-group d-flex justify-content-between align-items-center">
							<input type="hidden" name="types[]" value="show_social_links">
							<div class="d-flex align-items-center">
								<label class="aiz-switch aiz-switch-blue mb-0 pr-2">
									<input type="checkbox" name="show_social_links" value="1" 
									{{ (get_setting('show_social_links') )?? 0 == 1 ? 'checked' : '' }}@if( get_setting('show_social_links') == 'on') checked @endif>
									<span></span>
								</label>
								<span class="d-block" style="margin-top: -6px">{{ translate('Enable Social Links?') }}</span>
							</div>
						</div>
						<div class="form-group">
							<label>{{ translate('Social Links') }}</label>
							<!-- Facebook Link -->
							<div class="input-group form-group">
								<div class="input-group-prepend">
									<span class="input-group-text"><i class="lab la-facebook-f"></i></span>
								</div>
								<input type="hidden" name="types[]" value="facebook_link">
								<input type="text" class="form-control" placeholder="http://" name="facebook_link" value="{{ get_setting('facebook_link')}}">
							</div>
							<!-- Twitter Link -->
							<div class="input-group form-group">
								<div class="input-group-prepend">
									<span class="input-group-text">
										<svg xmlns="http://www.w3.org/2000/svg" width="9" height="10" fill="#5d6161" viewBox="0 0 16 16" class="ml-1">
											<path d="M12.6.75h2.454l-5.36 6.142L16 15.25h-4.937l-3.867-5.07-4.425 5.07H.316l5.733-6.57L0 
											.75h5.063l3.495 4.633L12.601.75Zm-.86 13.028h1.36L4.323 2.145H2.865z"/>
										</svg>
									</span>
								</div>
								<input type="hidden" name="types[]" value="twitter_link">
								<input type="text" class="form-control" placeholder="http://" name="twitter_link" value="{{ get_setting('twitter_link')}}">
							</div>
							<!-- Instagram Link -->
							<div class="input-group form-group">
								<div class="input-group-prepend">
									<span class="input-group-text"><i class="lab la-instagram"></i></span>
								</div>
								<input type="hidden" name="types[]" value="instagram_link">
								<input type="text" class="form-control" placeholder="http://" name="instagram_link" value="{{ get_setting('instagram_link')}}">
							</div>
							<!-- Youtube Link -->
							<div class="input-group form-group">
								<div class="input-group-prepend">
									<span class="input-group-text"><i class="lab la-youtube"></i></span>
								</div>
								<input type="hidden" name="types[]" value="youtube_link">
								<input type="text" class="form-control" placeholder="http://" name="youtube_link" value="{{ get_setting('youtube_link')}}">
							</div>
							<!-- Linkedin Link -->
							<div class="input-group form-group">
								<div class="input-group-prepend">
									<span class="input-group-text"><i class="lab la-linkedin-in"></i></span>
								</div>
								<input type="hidden" name="types[]" value="linkedin_link">
								<input type="text" class="form-control" placeholder="http://" name="linkedin_link" value="{{ get_setting('linkedin_link')}}">
							</div>
						</div>
						<!-- Update Button -->
						<div class="mt-4 text-right">
							<button type="submit" class="btn btn-success w-230px btn-md rounded-2 fs-14 fw-700 shadow-success">{{ translate('Update') }}</button>
						</div>
    				</form>
    			</div>
    		</div>
    	</div>
    </div>

	<!-- Footer Widget -->
    <div class="card">
    	<div class="row gutters-10">
			<!-- Contact Info Widget -->
    		<div class="col-lg-12">
    			<div class="card-header">
    				<h6 class="mb-0">{{ translate('Contact Info Widget') }}</h6>
    			</div>
    			<div class="card-body">
                    <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
    					@csrf
						<!-- Contact address -->
                        <div class="form-group">
    						<label>{{ translate('Contact address') }} ({{ translate('Translatable') }})</label>
    						<input type="hidden" name="types[][{{ $lang }}]" value="contact_address">
    						<input type="text" class="form-control" placeholder="{{ translate('Address') }}" name="contact_address" value="{{ get_setting('contact_address',null,$lang) }}">
    					</div>
						<!-- Contact phone -->
                        <div class="form-group">
    						<label>{{ translate('Contact phone') }}</label>
    						<input type="hidden" name="types[]" value="contact_phone">
    						<input type="text" class="form-control" placeholder="{{ translate('Phone') }}" name="contact_phone" value="{{ get_setting('contact_phone') }}">
    					</div>
						<!-- Contact email -->
                        <div class="form-group">
    						<label>{{ translate('Contact email') }}</label>
    						<input type="hidden" name="types[]" value="contact_email">
    						<input type="text" class="form-control" placeholder="{{ translate('Email') }}" name="contact_email" value="{{ get_setting('contact_email') }}">
    					</div>
						<!-- Update Button -->
						<div class="mt-4 text-right">
							<button type="submit" class="btn btn-success w-230px btn-md rounded-2 fs-14 fw-700 shadow-success">{{ translate('Update') }}</button>
						</div>
    				</form>
    			</div>
    		</div>
    	</div>
    </div>

	<!-- Footer Widget -->
    <div class="card">
    	<div class="row gutters-10">
			<!-- Link Widget One -->
            <div class="col-lg-12">
    			<div class="card-header">
    				<h6 class="mb-0">{{ translate('Quick Links') }}</h6>
    			</div>
    			<div class="card-body">
                    <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
						<!-- Links -->
    		            <div class="form-group">
    						<label>{{ translate('Links') }} - ({{ translate('Translatable') }} {{ translate('Label') }})</label>
    						<div class="w3-links-target">
    							<input type="hidden" name="types[][{{ $lang }}]" value="widget_one_labels">
    							<input type="hidden" name="types[]" value="widget_one_links">
    							@if (get_setting('widget_one_labels',null,$lang) != null)
    								@foreach (json_decode(get_setting('widget_one_labels',null,$lang), true) as $key => $value)
                                        @php
											$widget_one_links = '';
											if(isset(json_decode(get_setting('widget_one_links'), true)[$key])) {
												$widget_one_links = json_decode(get_setting('widget_one_links'), true)[$key];
											}
										@endphp
    									<div class="row gutters-5">
    										<div class="col-4">
    											<div class="form-group">
    												<input type="text" class="form-control" placeholder="{{ translate('Label') }}" name="widget_one_labels[]" value="{{ $value }}">
    											</div>
    										</div>
    										<div class="col">
    											<div class="form-group">
    												<input type="text" class="form-control" placeholder="http://" name="widget_one_links[]" value="{{ $widget_one_links }}">
    											</div>
    										</div>
    										<div class="col-auto">
    											<button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger" data-toggle="remove-parent" data-parent=".row">
    												<i class="las la-times"></i>
    											</button>
    										</div>
    									</div>
    								@endforeach
    							@endif
    						</div>
    						<button
    							type="button"
    							class="btn btn-soft-secondary btn-sm"
    							data-toggle="add-more"
    							data-content='<div class="row gutters-5">
    								<div class="col-4">
    									<div class="form-group">
    										<input type="text" class="form-control" placeholder="{{translate('Label')}}" name="widget_one_labels[]">
    									</div>
    								</div>
    								<div class="col">
    									<div class="form-group">
    										<input type="text" class="form-control" placeholder="http://" name="widget_one_links[]">
    									</div>
    								</div>
    								<div class="col-auto">
    									<button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger" data-toggle="remove-parent" data-parent=".row">
    										<i class="las la-times"></i>
    									</button>
    								</div>
    							</div>'
    							data-target=".w3-links-target">
    							{{ translate('Add New') }}
    						</button>
    					</div>
						<!-- Update Button -->
						<div class="mt-4 text-right">
							<button type="submit" class="btn btn-success w-230px btn-md rounded-2 fs-14 fw-700 shadow-success">{{ translate('Update') }}</button>
						</div>
    				</form>
    			</div>
    		</div>
    	</div>
    </div>

	<!-- Footer Bottom -->
    <div class="card">
		<div class="row gutters-10">
			<div class="col-lg-12">
				<!-- Copyright Widget -->
				<div class="card-header">
					<h6 class="mb-0">{{ translate('App Links') }}</h6>
				</div>
				<div class="card-body">
					<form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
						@csrf
						<div class="form-group d-flex justify-content-between align-items-center">
							<input type="hidden" name="types[]" value="enable_play_store_link">
							<div class="d-flex align-items-center">
								<label class="aiz-switch aiz-switch-blue mb-0 pr-2">
									<input type="checkbox" name="enable_play_store_link" value="1" 
									{{ (get_setting('enable_play_store_link') )?? 0 == 1 ? 'checked' : '' }}@if( get_setting('enable_play_store_link') == 'on') checked @endif>
									<span></span>
								</label>
								<span class="d-block" style="margin-top: -6px">{{ translate('Enable Play Store Link') }}</span>
							</div>
						</div>
						<!-- Play Store Link -->
						<div class="form-group">
							<label>{{ translate('Play Store Link') }} ({{ translate('Customer App') }})</label>
							<input type="hidden" name="types[]" value="play_store_link">
							<input type="text" class="form-control" placeholder="http://" name="play_store_link" value="{{ get_setting('play_store_link') }}">
						</div>
						<div class="form-group d-flex justify-content-between align-items-center">
							<input type="hidden" name="types[]" value="enable_app_store_link">
							<div class="d-flex align-items-center">
								<label class="aiz-switch aiz-switch-blue mb-0 pr-2">
									<input type="checkbox" name="enable_app_store_link" value="1" 
									{{ (get_setting('enable_app_store_link') )?? 0 == 1 ? 'checked' : '' }}@if( get_setting('enable_app_store_link') == 'on') checked @endif>
									<span></span>
								</label>
								<span class="d-block" style="margin-top: -6px">{{ translate('Enable App Store Link') }}</span>
							</div>
						</div>
						<!-- App Store Link -->
						<div class="form-group">
							<label>{{ translate('App Store Link') }} ({{ translate('Customer App') }})</label>
							<input type="hidden" name="types[]" value="app_store_link">
							<input type="text" class="form-control" placeholder="http://" name="app_store_link" value="{{ get_setting('app_store_link') }}">
						</div>
						<!-- Download App Link -->
						@if ((get_setting('vendor_system_activation') == 1) || addon_is_activated('delivery_boy'))
							<!-- Seller App Link -->
							@if (get_setting('vendor_system_activation') == 1)
								<div class="form-group d-flex justify-content-between align-items-center">
									<input type="hidden" name="types[]" value="enable_seller_app_link">
									<div class="d-flex align-items-center">
										<label class="aiz-switch aiz-switch-blue mb-0 pr-2">
											<input type="checkbox" name="enable_seller_app_link" value="1" 
											{{ (get_setting('enable_seller_app_link') )?? 0 == 1 ? 'checked' : '' }}@if( get_setting('enable_seller_app_link') == 'on') checked @endif>
											<span></span>
										</label>
										<span class="d-block" style="margin-top: -6px">{{ translate('Enable Seller App Link') }}</span>
									</div>
								</div>
								<div class="form-group">
									<label>{{ translate('Seller App Link') }}</label>
									<div class="input-group form-group">
										<input type="hidden" name="types[]" value="seller_app_link">
										<input type="text" class="form-control" placeholder="http://" name="seller_app_link" value="{{ get_setting('seller_app_link')}}">
									</div>
								</div>
							@endif
							<!-- Delivery Boy App Link -->
							@if (addon_is_activated('delivery_boy'))
								<div class="form-group d-flex justify-content-between align-items-center">
									<input type="hidden" name="types[]" value="enable_delivery_app_link">
									<div class="d-flex align-items-center">
										<label class="aiz-switch aiz-switch-blue mb-0 pr-2">
											<input type="checkbox" name="enable_delivery_app_link" value="1" 
											{{ (get_setting('enable_delivery_app_link') )?? 0 == 1 ? 'checked' : '' }}@if( get_setting('enable_delivery_app_link') == 'on') checked @endif>
											<span></span>
										</label>
										<span class="d-block" style="margin-top: -6px">{{ translate('Enable Delivery Boy App Link') }}</span>
									</div>
								</div>
								<div class="form-group">
									<label>{{ translate('Delivery Boy App Link') }}</label>
									<div class="input-group form-group">
										<input type="hidden" name="types[]" value="delivery_boy_app_link">
										<input type="text" class="form-control" placeholder="http://" name="delivery_boy_app_link" value="{{ get_setting('delivery_boy_app_link')}}">
									</div>
								</div>
							@endif
						@endif
						<!-- Update Button -->
						<div class="mt-4 text-right">
							<button type="submit" class="btn btn-success w-230px btn-md rounded-2 fs-14 fw-700 shadow-success">{{ translate('Update') }}</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<!-- Footer Bottom -->
    <div class="card">
		<div class="row gutters-10">
			<div class="col-lg-12">
				<div class="card-header">
					<h6 class="mb-0">{{ translate('Copyright Widget ') }}</h6>
				</div>
				<div class="card-body">
					<form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
						@csrf
							<div class="form-group">
								<label>{{ translate('Copyright Text') }} ({{ translate('Translatable') }})</label>
								<input type="hidden" name="types[][{{ $lang }}]" value="frontend_copyright_text">
								<textarea class="form-control" name="frontend_copyright_text" placeholder="Type.." row="1" maxlength="60">{!! get_setting('frontend_copyright_text',null,$lang) !!}</textarea>
							</div>
							<div class="card-header p-0">
								<h6 class="mb-0">{{ translate('Payment Methods') }}</h6>
							</div>
							<div class="form-group mt-3">
								<label>{{ translate('Payment Methods') }}</label>
								<div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="true">
									<div class="input-group-prepend">
										<div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
									</div>
									<div class="form-control file-amount">{{ translate('Choose File') }}</div>
									<input type="hidden" name="types[]" value="payment_method_images">
									<input type="hidden" name="payment_method_images" class="selected-files" value="{{ get_setting('payment_method_images')}}">
								</div>
								<div class="file-preview box sm"></div>
								<small class="text-muted">{{ translate("Minimum dimensions required: 144px width X 20px height.") }}</small>
							</div>
							<!-- Update Button -->
							<div class="mt-4 text-right">
								<button type="submit" class="btn btn-success w-230px btn-md rounded-2 fs-14 fw-700 shadow-success">{{ translate('Update') }}</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
@endsection

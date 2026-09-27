@extends('backend.layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Top Bar Settings') }}</h5>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('business_settings.update') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="form-group d-flex justify-content-between align-items-center mb-0">
							<input type="hidden" name="types[]" value="show_full_width_top_bar">
							<div class="d-flex align-items-center">
								<label class="aiz-switch aiz-switch-blue mb-0 pr-2">
									<input type="checkbox" name="show_full_width_top_bar" value="1"
										{{ (get_setting('show_full_width_top_bar') )?? 0 == 1 ? 'checked' : '' }}>
									<span></span>
								</label>
								<span class="d-block" style="margin-top: -6px">{{ translate('Show Full Width Top Bar') }}</span>
							</div>
						</div>

                        <div class="form-group">
                            <label class="col-from-label fs-13" for="image_for_mobile">
                            </label>
                            <div class="row pl-3">
                                <svg id="_79508b4b8c932dcad9066e2be4ca34f2" data-name="79508b4b8c932dcad9066e2be4ca34f2" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                    <path id="Path_40683" data-name="Path 40683" d="M8,16a8,8,0,1,1,8-8A8.024,8.024,0,0,1,8,16ZM8,1.333A6.667,6.667,0,1,0,14.667,8,6.686,6.686,0,0,0,8,1.333Z" fill="#9da3ae"/>
                                    <path id="Path_40684" data-name="Path 40684" d="M10.6,15a.926.926,0,0,1-.667-.333c-.333-.467-.067-1.133.667-2.933.133-.267.267-.6.4-.867a.714.714,0,0,1-.933-.067.644.644,0,0,1,0-.933A3.408,3.408,0,0,1,11.929,9a.926.926,0,0,1,.667.333c.333.467.067,1.133-.667,2.933-.133.267-.267.6-.4.867a.714.714,0,0,1,.933.067.644.644,0,0,1,0,.933A3.408,3.408,0,0,1,10.6,15Z" transform="translate(-3.262 -3)" fill="#9da3ae"/>
                                    <circle id="Ellipse_813" data-name="Ellipse 813" cx="1" cy="1" r="1" transform="translate(8 3.333)" fill="#9da3ae"/>
                                    <path id="Path_40685" data-name="Path 40685" d="M12.833,7.167a1.333,1.333,0,1,1,1.333-1.333A1.337,1.337,0,0,1,12.833,7.167Zm0-2a.63.63,0,0,0-.667.667.667.667,0,1,0,1.333,0A.63.63,0,0,0,12.833,5.167Z" transform="translate(-3.833 -1.5)" fill="#9da3ae"/>
                                </svg>
                            
                                <div class="ml-2 text-gray">
                                    <div class="mb-2">{{ translate('Minimum dimensions required: 1920px width X 40px height.') }}</div>
                                    <div>{{ translate('We have limited bar height to maintain UI. We had to crop from both top & bottom in view for different devices to make it responsive. Before designing bar keep these points in mind.') }}</div>
                                </div>
                            </div>
						</div>

                        <div class="form-group">
							<label class="col-from-label fs-13">{{ translate('Select Background Color') }}</label>
							<div class="input-group">
                                <input type="hidden" name="types[]" value="top_banner_background_color">
								<input type="text" class="form-control aiz-color-input" placeholder="Ex: #e1e1e1"
									name="top_banner_background_color" value="{{ get_setting('top_banner_background_color') }}">
								<div class="input-group-append">
									<span class="input-group-text p-0">
										<input data-target="top_banner_background_color"
											class="aiz-color-picker border-0 size-40px" type="color"
											value="{{ get_setting('top_banner_background_color') }}">
									</span>
								</div>
							</div>
						</div>

                        <div class="form-group">
                            <label class="col-from-label fs-13">
                                {{translate('Select Text Color')}}
                            </label>
                            <div class="d-flex align-items-center">
                                <input type="hidden" name="types[]" value="top_banner_text_color">
                                <label class="aiz-megabox d-block bg-white mb-0 mr-3" style="flex: 1;">
                                    <input type="radio" name="top_banner_text_color" value="white" @if(get_setting('top_banner_text_color') == 'white') checked @endif>
                                    <span class="d-flex align-items-center aiz-megabox-elem rounded-0"
                                        style="padding: 0.75rem 1.2rem;">
                                        <span class="aiz-rounded-check flex-shrink-0 mt-1"></span>
                                        <span class="flex-grow-1 pl-3 fw-600">{{ translate('Light') }}</span>
                                    </span>                                
                                </label>
                                <label class="aiz-megabox d-block bg-white mb-0" style="flex: 1;">
                                    <input type="radio" name="top_banner_text_color" value="dark" @if(get_setting('top_banner_text_color') == 'dark') checked @endif>
                                    <span class="d-flex align-items-center aiz-megabox-elem rounded-0"
                                        style="padding: 0.75rem 1.2rem;">
                                        <span class="aiz-rounded-check flex-shrink-0 mt-1"></span>
                                        <span class="flex-grow-1 pl-3 fw-600">{{ translate('Dark') }}</span>
                                    </span>
                                </label>
                            </div>
                        </div>

                        <!-- Image -->
                        <div class="form-group">
                            <label class="col-from-label fs-13" for="image">
                                {{ translate('Image') }}<br>
                                <span class="fs-12 text-secondary fw-400">{{ translate('(2560px or 1920px X S/M/Lpx)') }}</span>
                            </label>
                            <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">
                                        {{ translate('Browse')}}
                                    </div>
                                </div>
                                <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                <input type="hidden" name="types[]" value="top_banner_image">
                                <input type="hidden" name="top_banner_image" class="selected-files"
                                    value="{{ get_setting('top_banner_image') }}">
                            </div>
                            <div class="file-preview box sm">
                            </div>
                        </div>

                        <!-- Image for tabs -->
                        <div class="form-group">
                            <label class="col-from-label fs-13" for="image_for_tabs">
                                {{ translate('Image for Tabs') }}<br>
                                <span class="fs-12 text-secondary fw-400">{{ translate('(1024px X S/M/Lpx)') }}</span>
                            </label>
                            <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">
                                        {{ translate('Browse')}}
                                    </div>
                                </div>
                                <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                <input type="hidden" name="types[]" value="top_banner_image_for_tabs">
                                <input type="hidden" name="top_banner_image_for_tabs" class="selected-files"
                                value="{{ get_setting('top_banner_image_for_tabs') }}">
                            </div>
                            <div class="file-preview box sm">
                            </div>
                        </div>

                        <!-- Image for mobile -->
                        <div class="form-group">
                            <label class="col-from-label fs-13" for="image_for_mobile">
                                {{ translate('Image for Mobile') }}<br>
                                <span class="fs-12 text-secondary fw-400">{{ translate('(440px X S/M/Lpx)') }}</span>
                            </label>
                            <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                                </div>
                                <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                <input type="hidden" name="types[]" value="top_banner_image_for_mobile">
                                <input type="hidden" name="top_banner_image_for_mobile" class="selected-files"
                                value="{{ get_setting('top_banner_image_for_mobile') }}">
                            </div>
                            <div class="file-preview box sm">
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

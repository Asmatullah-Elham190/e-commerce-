@extends('backend.layouts.app')

@section('content')
	<div class="row">
		<div class="col-lg-8 col-xxl-6 mx-auto">
			<div class="card">
				<div class="card-header d-block d-md-flex">
					<h3 class="h6 mb-0">{{ translate('Update your system') }}</h3>
					<span>{{ translate('Current verion') }}: {{ get_setting('current_version') }}</span>
				</div>
				<div class="card-body">
					<div class="alert alert-info mb-5">
						<ul class="mb-0">
							<li class="">
								{{ translate('Make sure your server has matched with all requirements.') }}
								<a href="{{route('system_server')}}">{{ translate('Check Here') }}</a>
							</li>
							<li class="">{{ translate('Download latest version from codecanyon.') }}</li>
							<li class="">{{ translate('Extract downloaded zip. You will find updates.zip file in those extraced files.') }}</li>
							<li class="">{{ translate('Upload that zip file here and click update now.') }}</li>
							<li class="">{{ translate('If you are using any addon make sure to update those addons after updating.') }}</li>
							<li class="">{{ translate('Please turn off maintenance mode before updating.') }}</li>
							<li class="font-weight-bold">{{ translate('You can autometically update from previous 10 (ten) version.') }}</li>
						</ul>
					</div>
					<form action="{{ route('final_update') }}" method="post" enctype="multipart/form-data">
						@csrf
						<div class="row gutters-5">
							<div class="col-md">
        						<div class="input-group " data-toggle="aizuploader" data-type="archive">
        							<div class="input-group-prepend">
        								<div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}</div>
        							</div>
        							<div class="form-control file-amount">{{ translate('Choose File') }}</div>
        							<input type="hidden" name="update_zip" value="" class="selected-files">
        						</div>
        						<div class="file-preview box"></div>
							</div>
						</div>

						<div class="row gutters-5 mt-3">
							<div class="col-md">
		                        <div class="form-group">
		                            <label for="purchase_code" class="fs-12 fw-700" style="color: #666;">Purchase Code. <a class="fs-12 fw-500" href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code" target="_blank" class="text-blue hov-text-primary"><i>Where to get purchase code?</i></a></label>
		                            <input type="text" class="form-control rounded-2 border" style="height: 36px !important;" id="purchase_code" name="purchase_code" placeholder="**** **** **** ****" required="">
		                        </div>
								
	                        </div>
	                    </div>
						<div class="row gutters-5">
							<div class="col-md">
		                        <div class="form-group">
		                            <label for="system_key" class="fs-12 fw-700" style="color: #666;">System Key. <span class="fs-12 fw-500">If you have don't have System key, <a href="https://activeitzone.com/activation" target="_blank" class="text-blue hov-text-primary"><i>Click Here</i></a></span> </label>
		                            <input type="text" class="form-control rounded-2 border" style="height: 36px !important;" id="system_key" name="system_key" placeholder="***************************" required>
		                        </div>
	                        </div>
	                    </div>
						<div class="row gutters-5">
							<div class="col-md-auto">
								<button type="submit" class="btn btn-primary btn-block">{{ translate('Update Now') }}</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
@endsection

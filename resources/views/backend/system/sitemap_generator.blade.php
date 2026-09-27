@extends('backend.layouts.app')

@section('content')
	<div class="row">
		<div class="col-lg-8 col-xxl-8 mx-auto">
			<div class="card">
				<div class="card-header d-block d-md-flex">
					<h3 class="h6 mb-0">{{ translate('Sitemap Generator') }}</h3>
					<span>{{ translate('Current verion') }}: {{ get_setting('current_version') }}</span>
				</div>
				<div class="card-body">
					<form action="{{ route('generate_sitemap') }}" method="post">
						@csrf
						<!-- Submit button -->
						<div class="d-flex justify-content-end mt-4">
							<button type="submit" class="btn btn-install mt-3">
								<i class="las la-2x la-download mr-3"></i>
								{{ translate('Generate and Download') }}
							</button>
						</div>
					</form>
                    <hr>
                    <div>
                        <p><i class="las la-1x la-history mr-1"></i>{{ translate('Sitemap Generation History') }}</p>
                        <table class="table table-default text-center">
                            <thead>
                                <tr>
                                  <th scope="col">#</th>
                                  <th>{{ translate('File Name') }}</th>
                                  <th>{{ translate('File Size') }}</th>
                                  <th>{{ translate('Last Modified') }}</th>
                                  <th>{{ translate('Mime Type') }}</th>
                                  <th>{{ translate('Sitemap URL') }}</th>
                                  <th colspan="2">{{ translate('Actions') }}</th>
                                </tr>
                            </thead>
                        @if($file_info != null)
                            @foreach($file_info as $key => $info)
                            <tbody>
                                <tr>
                                    <th scope="row" class="align-middle">{{ $key+1 }}</th>
                                    <td class="align-middle">{{ $info['file_name'] }}</td>
                                    <td class="align-middle">{{ $info['file_size'] }}</td>
                                    <td class="align-middle">{{ $info['last_modified'] }}</td>
                                    <td class="align-middle">{{ $info['mime_type'] }}</td>
                                    <td class="align-middle">{{ $info['url'] }}</td>
                                    <td class="align-middle">
                                        <form action="{{ route('delete_sitemap') }}" method="post">
                                            @csrf
                                            <input type="hidden" name="file_name" value="{{ $info['file_name'] }}">
                                            <button class="btn btn-default btn-xs" type="submit" onclick="return confirm('Are you sure?')" title="{{ translate('Delete File') }}"><i class="las la-2x la-trash-alt"></i></button>
                                        </form>
                                    </td>
                                    <td class="align-middle">
                                        <form action="{{ route('download_old_sitemap') }}" method="post">
                                            @csrf
                                            <input type="hidden" name="file_name" value="{{ $info['file_name'] }}">
                                            <button class="btn btn-default btn-xs" type="submit" title="{{ translate('Download File') }}"><i class="las la-2x la-cloud-download-alt"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            </tbody>
                            @endforeach
                        @endif
                          </table>
                    </div>
				</div>
			</div>
		</div>
	</div>
@endsection

@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="row align-items-center">
		<div class="col-md-6">
			<h1 class="h3">{{translate('All Classifies Packages')}}</h1>
		</div>
        @can('add_classified_package')
            <div class="col-md-6 text-md-right">
                <a href="{{ route('customer_packages.create') }}" class="btn btn-circle btn-info">
                    <span>{{translate('Add New Package')}}</span>
                </a>
            </div>
        @endcan
	</div>
</div>

<div class="row">
    @foreach ($customer_packages as $key => $customer_package)
        <div class="col-xxl-3 col-lg-4 col-md-6 col-sm-12">
            <div class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4 overflow-hidden hov-card-blue">
                <div class="card-body text-center">
                    <div class="w-150px h-150px border border-1 border-gray-200 overflow-hidden mx-auto rounded-circle p-3 bg-soft-blue">
                        <img alt="{{ translate('Package Logo')}}" src="{{ uploaded_asset($customer_package->logo) }}" class="img-fit">
                    </div>
                    <div class="text-center mt-4 mb-3">
                        <span class="fs-16 fw-600 text-blue bg-soft-blue px-3 py-2 rounded-pill">{{$customer_package->getTranslation('name')}}</span>
                    </div>
                    <p class="fs-24 fs-md-30 fw-600 text-center opacity-80 mb-2">{{single_price($customer_package->amount)}}</p>
                    <div class="d-flex align-items-center py-2 border-top border-gray-200">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#007bff"><path d="M460-171.46v-297.08L200-619.08v283.23q0 6.16 3.08 11.54 3.07 5.39 9.23 9.23L460-171.46Zm40 0 247.69-143.62q6.16-3.84 9.23-9.23 3.08-5.38 3.08-11.54v-283.23L500-468.54v297.08Zm-52.31 38.92L192.31-279.69q-15.16-8.69-23.73-23.62-8.58-14.92-8.58-32.31v-288.76q0-17.39 8.58-32.31 8.57-14.93 23.73-23.62l255.38-147.15q15.16-8.69 32.31-8.69 17.15 0 32.31 8.69l255.38 147.15q15.16 8.69 23.73 23.62 8.58 14.92 8.58 32.31v288.76q0 17.39-8.58 32.31-8.57 14.93-23.73 23.62L512.31-132.54q-15.16 8.69-32.31 8.69-17.15 0-32.31-8.69ZM628.46-589 737-651.46 492.31-793.08q-6.16-3.84-12.31-3.84t-12.31 3.84l-95.69 55L628.46-589ZM480-502.92l108-62.7-257-148.53-108 62.69 257 148.54Z"/></svg>
                        <p class="fs-15 mb-0 ml-2">{{translate('Product Upload') }}:
                            <span class="text-bold">{{$customer_package->product_upload}}</span>
                        </p>
                    </div>
                    <div class="mar-top mt-4 d-flex align-items-center" style="gap: 8px;">
                        @can('edit_classified_package')
                            <a href="{{route('customer_packages.edit', ['id'=>$customer_package->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" class="btn btn-sm btn-info d-flex justify-content-center text-center w-100 rounded-2">
                                <i class="las la-edit fs-18"></i> 
                                <span class="ml-1">{{translate('Edit')}}</span>
                            </a>
                        @endcan
                        @can('delete_classified_package')
                            <a href="#" data-href="{{route('customer_packages.destroy', $customer_package->id)}}" class="btn btn-sm btn-danger confirm-delete d-flex justify-content-center text-center w-100 rounded-2">
                                <i class="las la-trash-alt fs-18"></i> 
                                <span class="ml-1">{{translate('Delete')}}</span>
                            </a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection

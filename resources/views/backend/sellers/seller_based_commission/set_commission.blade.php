@extends('backend.layouts.app')

@section('content')

@php
    $route = Route::currentRouteName() == 'sellers.index' ? 'all_seller_route' : 'seller_rating_followers';
@endphp

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3 ml-2">{{translate('Set Seller Based Commission')}}</h1>
        </div>
    </div>

    @if(get_setting('seller_commission_type') != 'seller_based' || get_setting('vendor_commission_activation') != 1)
        <div class="alert alert-info mt-2 text-center">
            @if(get_setting('seller_commission_type') != 'seller_based')
              <p class="font-weight-bold text-danger">{{ translate('Commission Type is not Seller Based, set commission type ') }} <a href="{{ route('business_settings.vendor_commission') }}">{{ translate('Here') }}</a></p>
            @endif
            @if (get_setting('vendor_commission_activation') != 1)
              <p class="font-weight-bold text-danger">{{ translate(' Seller Commission is not Activated, Active ') }} <a href="{{ route('business_settings.vendor_commission') }}">{{ translate('Here') }}</a></p>
           @endif
        </div>
    @endif

</div>

<div class="card">
       

        <div class="card-header row gutters-5">
            <div class="col">
                <h5 class="mb-md-0 h6">{{ translate('Sellers') }}</h5>
            </div>
           
              
            <div class="col-md-3">
            <form class="" id="sort_sellers" action="" method="GET">
                <div class="form-group mb-0">
                  <input type="text" class="form-control" id="search" name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="{{ translate('Type name or email or mobile number & Enter') }}">
                </div>
            </form>
            </div>
        </div>

        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                <tr>
                    <th>#</th>
                    <th>{{translate('Name')}}</th>
                    <th data-breakpoints="lg">{{translate('Phone')}}</th>
                    <th data-breakpoints="lg">{{translate('Email Address')}}</th>
                    <th data-breakpoints="lg">{{ translate('Num. of Products') }}</th>
                    <th data-breakpoints="lg">{{translate('Email Verification')}}</th>
                    <th data-breakpoints="lg">{{ translate('Status') }}</th>
                    <th data-breakpoints="lg">{{ translate('Commission') }}</th>
                    <th width="10%">{{translate('Options')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($shops as $key => $shop)
                    <tr>
                        <td>{{ ($key+1) + ($shops->currentPage() - 1)*$shops->perPage() }}</td>
                        <td>
                            <div class="row gutters-5  mw-100 align-items-center">
                                <div class="col-auto">
                                    <img src="{{ uploaded_asset($shop->logo) }}" class="size-40px img-fit" alt="Image" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                </div>
                                <div class="col">
                                    <span class="text-truncate-2">{{ $shop->name }}</span>
                                </div>
                            </div>
                        </td>
                        <td>{{$shop->user->phone}}</td>
                        <td>{{$shop->user->email}}</td>
                       
                            
                         <td>{{ $shop->user->products->count() }}</td>
                           
                       
                        <td>
                             @if($shop->user->email_verified_at != null)
                                 <span class="badge badge-inline badge-success">{{translate('Verified')}}</span>
                             @else
                                 <span class="badge badge-inline badge-warning">{{translate('Unverified')}}</span>
                             @endif
                        </td>
                        <td>
                            @if($shop->user->banned)
                                <span class="badge badge-inline badge-danger">{{ translate('Ban') }}</span>
                            @else
                                <span class="badge badge-inline badge-success">{{ translate('Regular') }}</span>
                            @endif
                        </td>
                        
                        
                        <td>
                            <div class="input-group">
                                <input type="number" class="form-control" id="commission_{{ $shop->id }}" step="0.01" value="{{$shop->commission_percentage}}" min="0" placeholder="{{translate('Commission')}}"
                                    style="border-radius: 8px 0 0 8px;">
                                <div class="input-group-prepend">
                                    <span class="input-group-text border-left-0" id="inputGroupPrepend" style="border-radius: 0 8px 8px 0;">%</span>
                                </div>
                            </div>
                        </td>
                        <td class="text-right">
                            <div class="form-group mb-0 text-right">
                                <button type="button" onclick="trigger_alert({{ $shop->id }})" class="btn btn-primary btn-sm rounded-2 w-120px">{{translate('Set')}}</button>
                            </div>
                        </td>
                        
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="aiz-pagination">
              {{ $shops->appends(request()->input())->links() }}
            </div>
        </div>
</div>

@endsection

@section('modal')
    <!-- confirm Modal -->
    <div id="confirm-modal" class="modal fade">
        <div class="modal-dialog modal-md modal-dialog-centered" style="max-width: 540px;">
            <div class="modal-content p-2rem">
                <div class="modal-body text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="72" height="64" viewBox="0 0 72 64">
                        <g id="Octicons" transform="translate(-0.14 -1.02)">
                          <g id="alert" transform="translate(0.14 1.02)">
                            <path id="Shape" d="M40.159,3.309a4.623,4.623,0,0,0-7.981,0L.759,58.153a4.54,4.54,0,0,0,0,4.578A4.718,4.718,0,0,0,4.75,65.02H67.587a4.476,4.476,0,0,0,3.945-2.289,4.773,4.773,0,0,0,.046-4.578Zm.6,52.555H31.582V46.708h9.173Zm0-13.734H31.582V23.818h9.173Z" transform="translate(-0.14 -1.02)" fill="#ffc700" fill-rule="evenodd"/>
                          </g>
                        </g>
                    </svg>
                    <p class="mt-3 mb-3 fs-16 fw-700">{{translate('Are you sure you want to set this Seller Based Commission?')}}</p>
                    <div>
                        <button type="button" class="btn btn-light rounded-2 mt-2 fs-13 fw-700 w-150px" data-dismiss="modal">{{ translate('Cancel') }}</button>
                        <a href="javascript:void(0)" id="trigger_btn" data-value="" class="btn btn-warning rounded-2 mt-2 fs-13 fw-700 w-250px" onclick="setCommission()">{{translate('Confirm')}}</a>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- /.modal -->
@endsection

@section('script')
<script type="text/javascript">

$(document).ready(function() {
    setTimeout(() => {
        AIZ.plugins.dateRange();
    }, "2000");
});

function trigger_alert(shopID){
    $('#trigger_btn').attr('data-value', shopID);
    $('#confirm-modal').modal('show');
}

function setCommission(){
    if('{{env('DEMO_MODE')}}' == 'On'){
        AIZ.plugins.notify('info', '{{ translate('Data can not change in demo mode.') }}');
        $('#confirm-modal').modal('hide');
        return;
    }

    $('#confirm-modal').modal('hide');
    var seller_id = $('#trigger_btn').attr('data-value');
    var commission =  $("#commission_" + seller_id).val();
    if(commission < 0) {
        AIZ.plugins.notify('danger', '{{ translate('Commission can not be less than 0') }}');
    }
    else{
        $.post('{{ route('set_seller_commission') }}', {
            _token:'{{ csrf_token() }}',
            seller_id:seller_id,
            commission_percentage:commission,
        }, function(data) {
            if(data == 1){
                AIZ.plugins.notify('success', '{{ translate('Seller Based Commission Set Successfully') }}');
            }
            location.reload();
        }).fail(function() {
            AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
        });
    }
}

$(document).ready(function(){
     var commission_type = "{{ get_setting('seller_commission_type')}}";
     var commission_status = @json(get_setting('vendor_commission_activation'));
     if (commission_type == "seller_based" && commission_status==1){
        $('button, .input-group input').prop('disabled', false);
     } else {
         $('button, .input-group input').prop('disabled', true);
    }
});
</script>
@endsection

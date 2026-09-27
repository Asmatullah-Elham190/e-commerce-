<div class="card seller-profile-documents">

    <div class="row gutters-5">
     

        @if (json_decode($shop->verification_info) && is_array(json_decode($shop->verification_info)))
        @foreach (json_decode($shop->verification_info) as $key => $info)
        @if ($info->type == 'file')
        @php
        $file_path = $info->value;
        $file_name = basename($file_path);
        $extension = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
        $is_image = in_array($extension, ['jpg', 'jpeg', 'png', 'webp', 'gif']);
        @endphp

        <div class="col-auto w-140px w-lg-220px">
            <div class="aiz-file-box">

                <div class="dropdown-file">
                    <a class="dropdown-link" data-toggle="dropdown">
                        <i class="la la-ellipsis-v"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="javascript:void(0)" class="dropdown-item" onclick="showFileInModal('{{ my_asset($file_path) }}')">
                            <span>{{translate('View')}}</span>
                        </a>
                        <a href="javascript:void(0)" class="dropdown-item" onclick="printFile('{{ my_asset($file_path) }}')">
                            <span>{{translate('Print')}}</span>
                        </a>
                        <a href="javascript:void(0)" class="dropdown-item confirm-delete" data-href="{{ route('seller.verification.file.delete', ['index' => $key, 'shop_id' => $shop->id, 'file_path' => $file_path]) }}">
                            <span>{{translate('Delete')}}</span>
                        </a>
                    </div>
                </div>
                <div class="card card-file aiz-uploader-select c-default" title="{{ $file_name }}">
                    @if ($is_image)
                    <div class="card-file-thumb">
                        <img src="{{ my_asset($file_path) }}" class="img-fit">
                    </div>
                    @elseif ($extension == 'pdf')
                    <div class="card-file-thumb d-flex align-items-center justify-content-center" style="height: 120px; background: #f5f5f5;">
                        <i class="las la-file-pdf" style="font-size: 48px; color: red;"></i>
                    </div>
                    @else
                    <div class="card-file-thumb d-flex align-items-center justify-content-center" style="height: 120px; background: #f5f5f5;">
                        <i class="las la-file" style="font-size: 48px; color: #666;"></i>
                    </div>
                    @endif

                    <div class="card-body">
                        <h6 class="d-flex">
                            <span class="text-truncate title">{{ $info->label ?? $file_name }}</span>
                            <span class="ext">.{{ $extension }}</span>
                        </h6>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @endforeach
        @endif

    </div>
</div>
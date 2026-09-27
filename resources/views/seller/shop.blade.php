@extends('seller.layouts.app')

@section('panel_content')

    <div class="col-md-10 mx-auto">
        <div class="aiz-titlebar mt-2 mb-2">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="h3">{{ translate('Shop Settings')}}
                        <span class="ml-3 fs-13">(<a href="{{ route('shop.visit', $shop->slug) }}"
                                class="btn btn-link btn-sm px-0" target="_blank">
                                {{ translate('Visit Shop')}}
                                <i class="las la-arrow-right"></i></a>)
                        </span>
                    </h1>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{ translate('Basic Info') }}</h5>
            </div>
            <div class="card-body">
                <form class="" action="{{ route('seller.shop.update') }}" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="shop_id" value="{{ $shop->id }}">
                    @csrf
                    <div class="">
                        <label class="col-form-label">{{ translate('Shop Name') }}<span
                                class="text-danger text-danger">*</span></label>
                        <input type="text" class="form-control mb-3" placeholder="{{ translate('Shop Name')}}" name="name"
                            value="{{ $shop->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="col-form-label">{{ translate('Shop Logo') }}</label>
                        <div class="input-group" data-toggle="aizuploader" data-type="image">
                            <div class="input-group-prepend">
                                <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}
                                </div>
                            </div>
                            <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                            <input type="hidden" name="logo" value="{{ $shop->logo }}" class="selected-files">
                        </div>
                        <div class="file-preview box sm">
                        </div>
                    </div>
                    <div class="">
                        <label>{{ translate('Shop Phone') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control mb-3" placeholder="{{ translate('Phone')}}" name="phone"
                            value="{{ $shop->phone }}" required>
                    </div>
                    <div class="">
                        <label class="col-form-label">{{ translate('Shop Address') }} <span
                                class="text-danger text-danger">*</span></label>
                        <input type="text" class="form-control mb-3" placeholder="{{ translate('Address')}}" name="address"
                            value="{{ $shop->address }}" required>
                    </div>
                    @if (get_setting('shipping_type') == 'seller_wise_shipping')
                        <div class="">
                            <label>{{ translate('Shipping Cost')}} <span class="text-danger">*</span></label>
                            <input type="number" lang="en" min="0" class="form-control mb-3"
                                placeholder="{{ translate('Shipping Cost')}}" name="shipping_cost"
                                value="{{ $shop->shipping_cost }}" required>
                        </div>
                    @endif
                    <div class="">
                        <label class="col-form-label">{{ translate('Meta Title') }}<span
                                class="text-danger text-danger">*</span></label>
                        <input type="text" class="form-control mb-3" placeholder="{{ translate('Meta Title')}}"
                            name="meta_title" value="{{ $shop->meta_title }}" required>
                    </div>
                    <div class="">
                        <label class="col-form-label">{{ translate('Meta Description') }}<span
                                class="text-danger text-danger">*</span></label>
                        <textarea name="meta_description" rows="3" class="form-control mb-3"
                            required>{{ $shop->meta_description }}</textarea>
                    </div>
                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-sm btn-primary">{{translate('Save')}}</button>
                    </div>
                </form>
            </div>
        </div>

        @php
            $business_info = json_decode($shop->business_info, true);
        @endphp
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{ translate('Business Settings') }}</h5>
            </div>

            <div class="card-body">
                <form action="{{ route('seller.shop.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="shop_id" value="{{ $shop->id }}">
                    @if (addon_is_activated('gst_system'))

                        <div class="">
                            <label>{{ translate('GSTIN Number') }}<span class="text-danger">*</span></label>
                            <input type="text" class="form-control mb-3" name="gstin_number"
                                placeholder="{{ translate('GSTIN Number') }}" value="{{ $business_info['gstin'] ?? '' }}"
                                required @if($shop->gst_verification) disabled @endif>
                        </div>

                        <div class="">
                            <label>{{ translate('GSTIN Certificate') }}<span class="text-danger">*</span></label>
                            <div class="row align-items-center">
                                <div class="col-md-10">
                                    <div class="custom-file">
                                        <label class="custom-file-label">
                                            <input type="file" data-preview="#gst_preview"
                                                class="custom-file-input preview-input" name="gstin_certificate"
                                                id="gstin_certificate" accept=".jpg,.jpeg,.png,.bmp,application/pdf" required
                                                @if($shop->gst_verification) disabled @endif>
                                            <span class="custom-file-name">{{ translate('Choose file') }}</span>
                                        </label>
                                    </div>
                                    <div id="gst_preview" class="mt-2"></div>
                                </div>
                                <div class="col-md-2 mb-3">
                                    @if (isset($business_info['certificate']))
                                        <div class="text-center">
                                            <a onclick="showFileInModal('{{ my_asset($business_info['gstin_certificate']) }}')"
                                                class="btn btn-sm btn-info text-white">{{ translate('View Current Certificate') }}</a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                    @endif

                    <div class="">
                        <label>{{ translate('VAT / TIN / BIN Number') }}<span class="text-danger">*</span></label>
                        <input type="text" class="form-control mb-3" name="certificate_number"
                            placeholder="{{ translate('VAT / TIN / BIN Number') }}"
                            value="{{ $business_info['certificate_number'] ?? '' }}" required>
                    </div>

                    <div class="">
                        <label>{{ translate('Reg Certificate / Trade License / Sale Tax Permit ') }}<span
                                class="text-danger">*</span></label>
                        <div class="row align-items-center">
                            <div class="col-md-10">
                                <div class="custom-file">
                                    <label class="custom-file-label">
                                        <input type="file" class="custom-file-input preview-input"
                                            data-preview="#certificate_preview" name="certificate" id="certificate"
                                            accept=".jpg,.jpeg,.png,.bmp,application/pdf">
                                        <span class="custom-file-name">{{ translate('Choose file') }}</span>
                                    </label>
                                </div>
                                <div id="certificate_preview" class="mt-2"></div>
                            </div>
                            <div class="col-md-2 mb-3">
                                @if (isset($business_info['certificate']))
                                    <div class="text-center">
                                        <a onclick="showFileInModal('{{ my_asset($business_info['certificate']) }}')"
                                            class="btn btn-sm btn-info text-white">{{ translate('View Current Certificate') }}</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if (get_active_countries()->count() > 1)
                        <div class="">
                            <label>{{ translate('Country')}} <span class="text-danger">*</span></label>
                            <div class="mb-3">
                                <select class="form-control aiz-selectpicker" data-live-search="true"
                                    data-placeholder="{{ translate('Select your country') }}" name="country_id" required>
                                    <option value="">{{ translate('Select your country') }}</option>
                                    @foreach (\App\Models\Country::where('status', 1)->get() as $key => $country)
                                        <option value="{{ $country->id }}" @if(isset($business_info['country']) && $business_info['country'] == $country->name) selected @endif>{{ $country->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @elseif(get_active_countries()->count() == 1)
                        <input type="hidden" name="country_id" value="{{ get_active_countries()->first()->id }}">
                    @endif

                    <div class="">
                        <label>{{ translate('State')}} <span class="text-danger">*</span></label>
                        <select class="form-control mb-3 aiz-selectpicker" data-live-search="true" name="state_id"
                            required></select>
                    </div>

                    <div class="form-group mt-3 mb-0 text-right">
                        <button type="submit" class="btn btn-sm btn-primary">
                            {{ translate('Save') }}
                        </button>
                    </div>

                </form>
            </div>
        </div>

        @if (addon_is_activated('delivery_boy'))
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Delivery Boy Pickup Point') }}</h5>
                </div>
                <div class="card-body">
                    <form class="" action="{{ route('seller.shop.update') }}" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="shop_id" value="{{ $shop->id }}">
                        @csrf

                        @if (get_setting('google_map') == 1)
                            <div class="mb-3">
                                <input id="searchInput" class="controls" type="text"
                                    placeholder="{{translate('Enter a location')}}">
                                <div id="map"></div>
                                <ul id="geoData">
                                    <li style="display: none;">{{ translate('Full Address') }}: <span id="location"></span></li>
                                    <li style="display: none;">{{ translate('Postal Code') }}: <span id="postal_code"></span></li>
                                    <li style="display: none;">{{ translate('Country') }}: <span id="country"></span></li>
                                    <li style="display: none;">{{ translate('Latitude') }}: <span id="lat"></span></li>
                                    <li style="display: none;">{{ translate('Longitude') }}: <span id="lon"></span></li>
                                </ul>
                            </div>
                            <div class="">
                                <label for="exampleInputuname">{{ translate('Longitude') }}</label>
                                <input type="text" class="form-control mb-3" id="longitude" name="delivery_pickup_longitude"
                                    readonly="" value="{{ $shop->delivery_pickup_longitude }}">
                            </div>
                            <div class="">
                                <label for="exampleInputuname">{{ translate('Latitude') }}</label>
                                <input type="text" class="form-control mb-3" id="latitude" name="delivery_pickup_latitude"
                                    readonly="" value="{{ $shop->delivery_pickup_latitude }}">
                            </div>
                        @else
                            <div class="">
                                <label for="exampleInputuname">{{ translate('Longitude') }}</label>
                                <input type="text" class="form-control mb-3" id="longitude" name="delivery_pickup_longitude"
                                    value="{{ $shop->delivery_pickup_longitude }}">
                            </div>

                            <div class="">
                                <label for="exampleInputuname">{{ translate('Latitude') }}</label>
                                <input type="text" class="form-control mb-3" id="latitude" name="delivery_pickup_latitude"
                                    value="{{ $shop->delivery_pickup_latitude }}">
                            </div>
                        @endif

                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-sm btn-primary">{{translate('Save')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{ translate('Social Media Link') }}</h5>
            </div>
            <div class="card-body">
                <form class="" action="{{ route('seller.shop.update') }}" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="shop_id" value="{{ $shop->id }}">
                    @csrf
                    <div class="form-box-content p-3">
                        <div class="mb-3">
                            <label class="col-form-label">{{ translate('Facebook') }}</label>
                            <input type="text" class="form-control" placeholder="{{ translate('Facebook')}}" name="facebook"
                                value="{{ $shop->facebook }}">
                            <small class="text-muted">{{ translate('Insert link with https ') }}</small>
                        </div>
                        <div class="mb-3">
                            <label class="col-form-label">{{ translate('Instagram') }}</label>
                            <input type="text" class="form-control" placeholder="{{ translate('Instagram')}}"
                                name="instagram" value="{{ $shop->instagram }}">
                            <small class="text-muted">{{ translate('Insert link with https ') }}</small>
                        </div>
                        <div class="mb-3">
                            <label class="col-form-label">{{ translate('Twitter') }}</label>
                            <input type="text" class="form-control" placeholder="{{ translate('Twitter')}}" name="twitter"
                                value="{{ $shop->twitter }}">
                            <small class="text-muted">{{ translate('Insert link with https ') }}</small>
                        </div>
                        <div class="mb-3">
                            <label class="col-form-label">{{ translate('Google') }}</label>
                            <input type="text" class="form-control" placeholder="{{ translate('Google')}}" name="google"
                                value="{{ $shop->google }}">
                            <small class="text-muted">{{ translate('Insert link with https ') }}</small>
                        </div>
                        <div class="mb-3">
                            <label class="col-form-label">{{ translate('Youtube') }}</label>
                            <input type="text" class="form-control" placeholder="{{ translate('Youtube')}}" name="youtube"
                                value="{{ $shop->youtube }}">
                            <small class="text-muted">{{ translate('Insert link with https ') }}</small>
                        </div>
                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-sm btn-primary">{{translate('Save')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('modal')
    <div class="modal fade" id="filePreviewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{translate('File Preview')}}</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body" style="min-height: 500px;">
                    <div id="filePreviewContainer" class="text-center"></div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')

    @if (addon_is_activated('delivery_boy') && get_setting('google_map') == 1)

        <script>
            function initialize(id_format = '') {
                let default_longtitude = '';
                let default_latitude = '';
                @if (get_setting('google_map_longtitude') != '' && get_setting('google_map_longtitude') != '')
                    default_longtitude = {{ get_setting('google_map_longtitude') }};
                    default_latitude = {{ get_setting('google_map_latitude') }};
                @endif

                            var lat = -33.8688;
                var long = 151.2195;

                if (document.getElementById('latitude').value != '' &&
                    document.getElementById('longitude').value != '') {
                    lat = parseFloat(document.getElementById('latitude').value);
                    long = parseFloat(document.getElementById('longitude').value);
                } else if (default_longtitude != '' &&
                    default_latitude != '') {
                    lat = default_latitude;
                    long = default_longtitude;
                }


                var map = new google.maps.Map(document.getElementById('map'), {
                    center: {
                        lat: lat,
                        lng: long
                    },
                    zoom: 13
                });

                var myLatlng = new google.maps.LatLng(lat, long);

                var input = document.getElementById(id_format + 'searchInput');

                map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

                var autocomplete = new google.maps.places.Autocomplete(input);

                autocomplete.bindTo('bounds', map);

                var infowindow = new google.maps.InfoWindow();
                var marker = new google.maps.Marker({
                    map: map,
                    position: myLatlng,
                    anchorPoint: new google.maps.Point(0, -29),
                    draggable: true,
                });

                map.addListener('click', function (event) {
                    marker.setPosition(event.latLng);
                    document.getElementById(id_format + 'latitude').value = event.latLng.lat();
                    document.getElementById(id_format + 'longitude').value = event.latLng.lng();
                    infowindow.setContent('Latitude: ' + event.latLng.lat() + '<br>Longitude: ' + event.latLng.lng());
                    infowindow.open(map, marker);
                });

                google.maps.event.addListener(marker, 'dragend', function (event) {
                    document.getElementById(id_format + 'latitude').value = event.latLng.lat();
                    document.getElementById(id_format + 'longitude').value = event.latLng.lng();
                    infowindow.setContent('Latitude: ' + event.latLng.lat() + '<br>Longitude: ' + event.latLng.lng());
                    infowindow.open(map, marker);
                });

                autocomplete.addListener('place_changed', function () {
                    infowindow.close();
                    marker.setVisible(false);
                    var place = autocomplete.getPlace();

                    if (!place.geometry) {
                        window.alert("Autocomplete's returned place contains no geometry");
                        return;
                    }

                    if (place.geometry.viewport) {
                        map.fitBounds(place.geometry.viewport);
                    } else {
                        map.setCenter(place.geometry.location);
                        map.setZoom(17);
                    }

                    marker.setPosition(place.geometry.location);
                    marker.setVisible(true);

                    var address = '';
                    if (place.address_components) {
                        address = [
                            (place.address_components[0] && place.address_components[0].short_name || ''),
                            (place.address_components[1] && place.address_components[1].short_name || ''),
                            (place.address_components[2] && place.address_components[2].short_name || '')
                        ].join(' ');
                    }

                    infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
                    infowindow.open(map, marker);

                    for (var i = 0; i < place.address_components.length; i++) {
                        if (place.address_components[i].types[0] == 'postal_code') {
                            document.getElementById('postal_code').innerHTML = place.address_components[i].long_name;
                        }
                        if (place.address_components[i].types[0] == 'country') {
                            document.getElementById('country').innerHTML = place.address_components[i].long_name;
                        }
                    }
                    document.getElementById('location').innerHTML = place.formatted_address;
                    document.getElementById(id_format + 'latitude').value = place.geometry.location.lat();
                    document.getElementById(id_format + 'longitude').value = place.geometry.location.lng();
                });

            }
        </script>

        <script
            src="https://maps.googleapis.com/maps/api/js?key={{ env('MAP_API_KEY') }}&libraries=places&language=en&callback=initialize"
            async defer></script>

    @endif

    <script>
        $(document).on('change', '[name=country_id]', function () {
            var country_id = $(this).val();
            get_states(country_id);
        });

        $(document).ready(function () {
            var country_id = $('[name=country_id]').val();
            get_states(country_id);
        });

        function get_states(country_id) {
            var savedStateName = "{{ optional(json_decode($shop->business_info))->state ?? '' }}";
            var stateSelect = $('[name="state_id"]');
            $('[name="state"]').html("");
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('seller.get-state')}}",
                type: 'POST',
                data: {
                    country_id: country_id
                },
                success: function (response) {
                    var obj = JSON.parse(response);
                    if (obj != '') {
                        $('[name="state_id"]').html(obj);
                        if (savedStateName) {
                            stateSelect.find('option').each(function () {
                                if ($(this).text().trim() === savedStateName.trim()) {
                                    $(this).prop('selected', true);
                                }
                            });
                        }
                        AIZ.plugins.bootstrapSelect('refresh');
                    }
                }
            });
        }

        function showFileInModal(fileUrl) {
            const ext = fileUrl.split('.').pop().toLowerCase();
            const container = document.getElementById('filePreviewContainer');
            container.innerHTML = '';

            if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(ext)) {
                const img = document.createElement('img');
                img.src = fileUrl;
                img.style.maxWidth = '100%';
                img.style.maxHeight = '600px';
                container.appendChild(img);
            } else if (ext === 'pdf') {
                const iframe = document.createElement('iframe');
                iframe.src = fileUrl;
                iframe.style.width = '100%';
                iframe.style.height = '600px';
                iframe.frameBorder = 0;
                container.appendChild(iframe);
            } else {
                container.innerHTML = '<p class="text-danger">Unsupported file format.</p>';
            }

            $('#filePreviewModal').modal('show');
        }

        $(document).on('change', '.preview-input', function () {
            let input = this;
            let previewBox = $($(this).data('preview'));
            let fileName = input.files[0]?.name || '';

            $(this).next('.custom-file-label').html(fileName);

            previewBox.html('');

            if (input.files && input.files[0]) {
                let file = input.files[0];
                let fileType = file.type;
                if (fileType.startsWith('image/')) {
                    let reader = new FileReader();
                    reader.onload = function (e) {
                        previewBox.html(
                            '<img src="' + e.target.result + '" class="preview-img img-fluid h-100px w-100-px">'
                        );
                    };
                    reader.readAsDataURL(file);
                }
                else if (fileType === 'application/pdf') {
                    previewBox.html(`
                            <div class="pdf-preview d-flex align-items-end justify-content-center border rounded text-center" 
                                style="width:100px; height:100px; background-color:#f8f9fa; position:relative; font-size:40px; color:#e74c3c;">
                                <i class="las la-file-pdf" style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%); opacity:0.2; font-size:50px;"></i>
                                <small class="text-truncate-1 fs-10" style="position:absolute; bottom:5px; left:0;" title="${fileName}">${fileName}</small>
                            </div>
                        `);
                }
            }
        });

    </script>


    @if(get_active_countries()->count() == 1)
        <script>
            $(document).ready(function () {
                get_states(@json(get_active_countries()[0]->id))
            });
        </script>
    @endif

@endsection
@extends('backend.layouts.app')

@section('content')


<div class="row ">
    <div class="col-md-10 mx-auto">
        <div class="aiz-titlebar text-left mt-2 mb-3">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="h3 ml-2">{{translate('Add And Edit Product With AI (Gemini)')}}</h1>
                </div>
            </div>

            @if(get_setting('ai_activation') != 1)
                <div class="alert alert-info text-center ">
                    <p class="font-weight-bold text-danger m-0">{{ translate('Ai Feature is not Activated, Active ') }} <a href="{{ route('ai-config') }}">{{ translate('Here') }}</a></p>
                </div>
            @endif

        </div>

        <div class="row gutters-16">
            <!-- Total Customer -->
            <div class="col-sm-6">
                <a href="{{route('products.create')}}" class="dashboard-box h-220px mb-2rem overflow-hidden d-flex align-items-center" style="background: rgba(32, 122, 252, 0.1);">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <div>
                            <h3 class="fs-20 fw-600 text-dark mb-0">{{translate('Add Product')}}</h3>
                            <p class="text-dark fs-14 mt-1">{{translate('Let AI create product information automatically to speed up product listing')}}</p>
                        </div>
                        <div class="mt-2">
                            <svg xmlns="http://www.w3.org/2000/svg" height="84px" viewBox="0 -960 960 960" width="84px" fill="#000000"><path d="M469-469.5H252v-22h217v-217h22v217h217v22H491v217h-22v-217Z"/></svg>                      
                        </div>
                    </div>
                </a>
            </div>
            <!-- Total Products -->
            <div class="col-sm-6">
                <a href="{{route('products.admin')}}" class="dashboard-box h-220px mb-2rem overflow-hidden d-flex align-items-center" style="background: rgba(238, 77, 93, 0.1);">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <div>
                            <h3 class="fs-20 fw-600 text-dark mb-0">{{translate('Edit Product')}}</h3>
                            <p class="text-dark fs-14 mt-1">{{translate('Enhance product information and make it more engaging with AI')}}</p>
                        </div>
                        <div class="mt-2">
                            <svg xmlns="http://www.w3.org/2000/svg" height="72px" viewBox="0 -960 960 960" width="72px" fill="#000000"><path d="M225.76-172q-22 0-37.88-15.88Q172-203.76 172-225.76v-508.48q0-22 15.88-37.88Q203.76-788 226-788h343l-22 22H226q-12 0-22 10t-10 22v508q0 12 10 22t22 10h508q12 0 22-10t10-22v-327.5l22-22V-226q0 22.24-15.88 38.12Q756.24-172 734.24-172H225.76ZM480-480Zm-67.5 67.5V-497l359.53-360.52q2.47-1.98 6.64-3.48 4.16-1.5 9.35-1.5 4.01 0 7.94 1.25 3.92 1.25 7.54 3.75L852-810q4.61 3.46 6.55 8.73Q860.5-796 860.5-791t-1.72 9.37q-1.71 4.37-4.76 7.11L492-412.5h-79.5Zm426-377.5-49-53.5 49 53.5Zm-404 355.5h49l282.5-283-24.04-25L714.5-768l-280 279.5v54Zm307.46-308L714.5-768l27.46 25.5 24.04 25-24.04-25Z"/></svg>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div> 
</div>
@endsection
@extends('layouts.admin.app')

@section('title',translate('messages.app_settings'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title text-capitalize">
                <div class="card-header-icon d-inline-flex mr-2 img">
                    <img src="{{asset('/public/assets/admin/img/app.png')}}" class="mw-26px" alt="public">
                </div>
                <span>
                    {{translate('messages.app_settings')}}
                </span>
            </h1>
        </div>
        <!-- End Page Header -->

        @php($app_minimum_version_android=\App\Models\BusinessSetting::where(['key'=>'app_minimum_version_android'])->first())
        @php($app_minimum_version_android=$app_minimum_version_android?$app_minimum_version_android->value:null)

        @php($app_url_android=\App\Models\BusinessSetting::where(['key'=>'app_url_android'])->first())
        @php($app_url_android=$app_url_android?$app_url_android->value:null)

        @php($app_minimum_version_ios=\App\Models\BusinessSetting::where(['key'=>'app_minimum_version_ios'])->first())
        @php($app_minimum_version_ios=$app_minimum_version_ios?$app_minimum_version_ios->value:null)

        @php($app_url_ios=\App\Models\BusinessSetting::where(['key'=>'app_url_ios'])->first())
        @php($app_url_ios=$app_url_ios?$app_url_ios->value:null)

        @php($popular_food=\App\Models\BusinessSetting::where(['key'=>'popular_food'])->first())
        @php($popular_food=$popular_food?$popular_food->value:null)

        @php($popular_restaurant=\App\Models\BusinessSetting::where(['key'=>'popular_restaurant'])->first())
        @php($popular_restaurant=$popular_restaurant?$popular_restaurant->value:null)

        @php($new_restaurant=\App\Models\BusinessSetting::where(['key'=>'new_restaurant'])->first())
        @php($new_restaurant=$new_restaurant?$new_restaurant->value:null)

        @php($most_reviewed_foods=\App\Models\BusinessSetting::where(['key'=>'most_reviewed_foods'])->first())
        @php($most_reviewed_foods=$most_reviewed_foods?$most_reviewed_foods->value:null)

        <div class="card mb-3">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6 col-sm-6 col-12">
                        <div class="form-group m-0">
                            <label class="toggle-switch toggle-switch-sm d-flex justify-content-between border rounded px-3 px-xl-4 form-control" for="popular_food">
                                <span class="pr-2">{{translate('messages.popular_foods')}}:</span>
                                <input type="checkbox" class="toggle-switch-input" onclick="location.href='{{route('admin.business-settings.toggle-settings',['popular_food',$popular_food?0:1, 'popular_food'])}}'" id="popular_food" {{$popular_food?'checked':''}}>
                                <span class="toggle-switch-label">
                                    <span class="toggle-switch-indicator"></span>
                                </span>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-12">
                        <div class="form-group m-0">
                            <label class="toggle-switch toggle-switch-sm d-flex justify-content-between border rounded px-3 px-xl-4 form-control" for="popular_restaurant">
                                <span class="pr-2">{{translate('messages.popular_restaurants')}}:</span>
                                <input type="checkbox" name="popular_restaurant" class="toggle-switch-input" onclick="location.href='{{route('admin.business-settings.toggle-settings',['popular_restaurant',$popular_restaurant?0:1, 'popular_restaurant'])}}'" id="popular_restaurant" {{$popular_restaurant?'checked':''}}>
                                <span class="toggle-switch-label">
                                    <span class="toggle-switch-indicator"></span>
                                </span>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-12">
                        <div class="form-group m-0">
                            <label class="toggle-switch toggle-switch-sm d-flex justify-content-between border rounded px-3 px-xl-4 form-control" for="new_restaurant">
                                <span class="pr-2 text-capitalize">{{translate('messages.new_restaurants')}}:</span>
                                <input type="checkbox" class="toggle-switch-input" onclick="location.href='{{route('admin.business-settings.toggle-settings',['new_restaurant',$new_restaurant?0:1, 'new_restaurant'])}}'" id="new_restaurant" {{$new_restaurant?'checked':''}}>
                                <span class="toggle-switch-label">
                                    <span class="toggle-switch-indicator"></span>
                                </span>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-12">
                        <div class="form-group m-0">
                            <label class="toggle-switch toggle-switch-sm d-flex justify-content-between border rounded px-3 px-xl-4 form-control" for="most_reviewed_foods">
                                <span class="pr-2 text-capitalize">{{translate('messages.most_reviewed_foods')}}:</span>
                                <input type="checkbox" class="toggle-switch-input" onclick="location.href='{{route('admin.business-settings.toggle-settings',['most_reviewed_foods',$most_reviewed_foods?0:1, 'most_reviewed_foods'])}}'" id="most_reviewed_foods" {{$most_reviewed_foods?'checked':''}}>
                                <span class="toggle-switch-label">
                                    <span class="toggle-switch-indicator"></span>
                                </span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row gx-2 gx-lg-3">
                    <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                        <form action="{{route('admin.business-settings.app-settings')}}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label  class="form-label text-capitalize">{{translate('messages.app_minimum_version')}} ({{translate('messages.android')}})</label>
                                        <input type="number" placeholder="{{translate('messages.app_minimum_version')}}" class="form-control h--45px" name="app_minimum_version_android"
                                            value="{{env('APP_MODE')!='demo'?$app_minimum_version_android??'':''}}">
                                    </div>
                                    <div class="form-group m-0">
                                        <label class="form-label text-capitalize">{{translate('messages.app_url')}} ({{translate('messages.android')}})</label>
                                        <input type="text" placeholder="{{translate('messages.app_url')}}" class="form-control h--45px" name="app_url_android"
                                            value="{{env('APP_MODE')!='demo'?$app_url_android??'':''}}">
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label  class="form-label text-capitalize">{{translate('messages.app_minimum_version')}} ({{translate('messages.ios')}})</label>
                                        <input type="number" placeholder="{{translate('messages.app_minimum_version')}}" class="form-control h--45px" name="app_minimum_version_ios"
                                            value="{{env('APP_MODE')!='demo'?$app_minimum_version_ios??'':''}}">
                                    </div>
                                    <div class="form-group m-0">
                                        <label class="form-label text-capitalize">{{translate('messages.app_url')}} ({{translate('messages.ios')}})</label>
                                        <input type="text" placeholder="{{translate('messages.app_url')}}" class="form-control h--45px" name="app_url_ios"
                                            value="{{env('APP_MODE')!='demo'?$app_url_ios??'':''}}">
                                    </div>
                                </div>
                            </div>
                            <div class="btn--container justify-content-end mt-3">
                                <button class="btn btn--reset" type="reset">{{translate('messages.reset')}}</button>
                                <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}" onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}" class="btn btn--primary mb-2">{{translate('messages.submit')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection

@push('script_2')

@endpush

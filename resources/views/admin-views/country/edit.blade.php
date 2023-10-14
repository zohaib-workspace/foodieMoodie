@extends('layouts.admin.app')

@section('title',translate('Update Country'))


@section('content')

    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title text-capitalize"><i class="tio-edit"></i> {{translate('messages.Country')}} {{translate('messages.update')}}</h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
            <form action="{{route('admin.country.update', $country->id)}}" method="post"  class="shadow--card">
                    @csrf
                    <div class="row">
                
                        <div class="col-md-6 col-xl-7 zone-setup">
                            <div class="form-group mb-3">
                                <label class="input-label"
                                       for="exampleFormControlInput1">{{translate('messages.country')}} {{translate('messages.name')}}</label>
                                <input id="zone_name" type="text" name="name" class="form-control" placeholder="{{translate('messages.new_zone')}}" value="{{$country->name}}" required>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="input-label">
                                            {{translate('messages.sort name')}} 
                                        </label>
                                        <input id="sortname" name="sortname" type="text" class="form-control h--45px" placeholder="{{ translate('messages.Ex : PK') }} " value="{{$country->sortname}}" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="input-label">
                                            {{translate('messages.phonecode')}}
                                        </label>
                                        <input id="phonecode" name="phonecode" type="number" class="form-control h--45px" placeholder="{{ translate('messages.Ex :') }} 92" value="{{$country->phonecode}}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4 col-12">
                            <div class="form-group">
                                    <label class="input-label" for="choice_types">{{ translate('messages.Time Zone') }}<span
                                                class="input-label-secondary"
                                                title="{{ translate('messages.select_timezone') }}"><img
                                                    src="{{ asset('/public/assets/admin/img/info-circle.svg') }}"
                                                    alt="{{ translate('messages.select_timezone') }}"></span></label>
                                        <select name="timezone_id" id="choice_zone" 
                                            data-placeholder="{{ translate('messages.select') }} {{ translate('messages.Time zone') }}"
                                            class="form-control h--45px js-select2-custom">
                                            @foreach (\App\Models\Timezone::all() as $t_z)
                                                    <option value="{{ $t_z->id }}"
                                                        {{ $country->timezone_id == $t_z->id ? 'selected' : '' }}>
                                                        {{ $t_z->timezone }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            <div class="btn--container mt-3 justify-content-end">
                                <button id="reset_btn" type="reset" class="btn btn--reset">{{translate('messages.reset')}}</button>
                                <button type="submit" class="btn btn--primary">{{translate('messages.update')}}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- End Table -->
        </div>
    </div>

@endsection

@push('script_2')
<script>
  
    $('#reset_btn').click(function(){
        // $('#zone_name').val('');
        // $('#coordinates').val('');
        // $('#min_delivery_charge').val('');
        // $('#delivery_charge_per_km').val('');
        location.reload(true);
    })
</script>
@endpush
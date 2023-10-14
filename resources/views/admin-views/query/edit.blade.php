@extends('layouts.admin.app')
@section('title',translate('Update query'))
@section('content')

    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title text-capitalize"><i class="tio-edit"></i> {{translate('messages.query')}} {{translate('messages.update')}}</h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
            <form action="{{route('admin.query.update', $query->id)}}" method="post"  class="shadow--card">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 col-xl-7 zone-setup">
                            <div class="form-group mb-3">
                                <label class="input-label"
                                       for="exampleFormControlInput1">{{translate('messages.query')}} {{translate('messages.name')}}</label>
                                <input  type="text" name="name" class="form-control" placeholder="{{translate('messages.new_query')}}" value="{{$query->name}}" required>
                            </div>
                             <div class="form-group">
                            <label class="input-label" for="choice_types">{{ translate('messages.Query Role') }}<span class="input-label-secondary" title="{{ translate('messages.select_role') }}"><img src="{{ asset('/public/assets/admin/img/info-circle.svg') }}" alt="{{ translate('messages.select_role') }}"></span></label>
                            <select name="role" id="choice_country" data-placeholder="{{ translate('messages.select') }} {{ translate('messages.query role') }}" class="form-control h--45px js-select2-custom">
                                <option value="rider" {{ $query->role == 'rider' ? 'selected' : '' }}>
                                    Rider
                                </option>
                                <option value="user" {{ $query->role == 'user' ? 'selected' : '' }}>
                                    User
                                </option>
                                <option value="all" {{ $query->role == 'all' ? 'selected' : '' }}>
                                    All
                                </option>
                            </select>
                        </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="input-label">
                                            {{translate('messages.Description')}} 
                                        </label>
                                        <input name="description" type="text" class="form-control h--45px" placeholder="{{ translate('messages.Enter Description') }} " value="{{$query->description}}" required>
                                    </div>
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
        location.reload(true);
    })
</script>
@endpush
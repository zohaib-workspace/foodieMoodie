@extends('layouts.admin.app')

@section('title', 'Shift Update')

@push('css_or_js')
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title">Update Shift</h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.business-settings.timezone-update', [$shift['id']]) }}" method="post"
                            id="shift_form" enctype="multipart/form-data">
                            @csrf
                            @method('put')

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="exampleFormControlInput1">{{ translate('messages.start_time') }}</label>
                                        <input type="time" name="start_time" value={{ $shift['start_time'] }}
                                            class="form-control"
                                            placeholder="{{ translate('messages.starting_time_of_shift') }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="input-label" for="title">{{ translate('messages.zone') }}</label>
                                        <select name="zone_id" id="zone" class="form-control js-select2-custom"
                                            onchange="getRequest('{{ url('/') }}/admin/food/get-foods?zone_id='+this.value,'choice_item')">
                                            <option disabled selected value="">
                                                ---{{ translate('messages.select') }}---</option>
                                            @php($zones = \App\Models\Zone::active()->get())
                                            @foreach ($zones as $zone)
                                                @if (isset(auth('admin')->user()->zone_id))
                                                    @if (auth('admin')->user()->zone_id == $zone->id)
                                                        <option value="{{ $zone->id }}" selected>{{ $zone->name }}
                                                        </option>
                                                    @endif
                                                @else
                                                    @if ($shift['zone_id'] == $zone['id'])
                                                        <option value="{{ $zone['id'] }}" selected>{{ $zone['name'] }}
                                                        </option>
                                                    @else
                                                        <option value="{{ $zone['id'] }}">{{ $zone['name'] }}</option>
                                                    @endif
                                                @endif
                                            @endforeach
                                        </select>
                                        
                                    </div>
                                    {{-- <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('messages.banner')}} {{translate('messages.type')}}</label>
                                <select name="banner_type" id="banner_type" class="form-control" onchange="banner_type_change(this.value)">
                                    <option value="restaurant_wise">{{translate('messages.business')}} {{translate('messages.wise')}}</option>
                                    <option value="item_wise">{{translate('messages.food')}} {{translate('messages.wise')}}</option>
                                </select>
                            </div> --}}
                            
                                <div class="form-group mb-2">
                                    <label class="form-label d-block">{{ translate('description') }}</label>
                                    <textarea placeholder="{{translate('Description')}} {{translate('of')}} {{translate('shift')}}"  class="form-control" name="description">{{$shift['description']}}</textarea>
                                </div>

                                </div>
                                <div class="col-md-6">
                                    {{-- <div class="form-group" id="restaurant_wise">
                                <label class="input-label" for="exampleFormControlSelect1">{{translate('messages.restaurant')}}<span
                                        class="input-label-secondary"></span></label>
                                <select name="restaurant_id" class="js-data-example-ajax form-control"  title="Select Business">
                                    <option selected disabled>{{ translate('Select') }}</option>
                                </select>
                            </div>
                            <div class="form-group" id="item_wise">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('messages.shift')}} {{translate('messages.date')}}</label>
                                <select name="item_id" id="choice_item" class="form-control js-select2-custom" placeholder="{{translate('messages.select_food')}}">
                                    <option selected disabled>{{ translate('Select Restaurant') }}</option>
                                </select> --}}
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="exampleFormControlInput1">{{ translate('messages.end_time') }}</label>
                                        <input type="time" name="end_time" value={{ $shift['end_time'] }}
                                            class="form-control"
                                            placeholder="{{ translate('messages.ending_time_of_shift') }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="exampleFormControlInput1">{{ translate('messages.shift_date') }}</label>
                                        <input type="date" id="date_from" name="shift_date"
                                            value={{ $shift['shift_date'] }} class="form-control"
                                            placeholder="{{ translate('messages.ending_time_of_shift') }}" required>
                                    </div>
                                </div>
                            </div>



                            <div class="btn--container justify-content-end">

                                <button type="submit" class="btn btn--primary">{{ translate('messages.Update') }}</button>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('script_2')
    <script>
        $('#shift_form').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{ route('admin.delivery-man.shift-update', [$shift['id']]) }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    if (data.errors) {
                        for (var i = 0; i < data.errors.length; i++) {
                            toastr.error(data.errors[i].message, {
                                CloseButton: true,
                                ProgressBar: true
                            });
                        }
                    } else {
                        toastr.success('{{ translate('messages.shift_updated_successfully') }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                        setTimeout(function() {
                            location.href =
                                '{{ route('admin.delivery-man.shifts') }}';
                        }, 2000);
                    }
                }
            });
        });
    </script>
@endpush

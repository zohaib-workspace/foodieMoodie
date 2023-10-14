@extends('layouts.admin.app')

@section('title', 'Update Service')

@push('css_or_js')
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title">{{ translate('messages.Update_Service') }}</h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <form action="{{ route('admin.business-settings.service-update', [$service['id']]) }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label class="form-label d-block">{{ translate('Name') }}</label>
                                <input type="text" placeholder="{{ translate('messages.Car Wash') }}"
                                    class="form-control" name="name" value="{{ $service['name'] }}">
                            </div>
                            <div class="form-group mb-2">
                                <label class="form-label d-block">{{ translate('WA-Number') }}</label>
                                <input type="text" placeholder="+923056860156" class="form-control" name="wa_number"
                                    value="{{ $service['wa_number'] }}">
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
                                            @if ($service['zone_id'] == $zone['id'])
                                                <option value="{{ $zone['id'] }}" selected>{{ $zone['name'] }}
                                                </option>
                                            @else
                                                <option value="{{ $zone['id'] }}">{{ $zone['name'] }}</option>
                                            @endif
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            {{-- <div class="form-group mb-2">
                                <label class="form-label d-block">Zone</label>
                                <input type="text" placeholder="+923056860156" class="form-control"
                                    name="zone_id" value="{{$service['zone_id']}}">
                            </div> --}}
                            <div class="form-group mb-2">
                                <label class="form-label d-block">Description</label>
                                <textarea type="text"
                                    placeholder="{{ translate('Description') }} {{ translate('of') }} {{ translate('service') }}"
                                    class="form-control" name="description" value="{{ $service['description'] }}">{{ $service['description'] }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="h-100 d-flex flex-column justify-content-center">
                                <div class="form-group mt-auto">
                                    <label class="d-block text-center">{{ translate('messages.Service') }}
                                        {{ translate('messages.image') }} <small class="text-danger">* (
                                            {{ translate('messages.ratio') }} 1000x300 )</small></label>
                                </div>
                                <div class="form-group mt-auto">
                                    <center>
                                        <img class="initial-2" id="viewer"
                                            src="{{ asset('storage/app/public/service') }}/{{ $service['image'] }}"
                                            onerror="this.src='{{ asset('public/assets/admin/img/900x400/img1.jpg') }}'"
                                            alt="campaign image" />
                                    </center>
                                </div>
                                <div class="form-group mt-auto">
                                    <div class="custom-file">
                                        <input type="file" name="image" id="customFileEg1" class="custom-file-input"
                                            accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" required>
                                        <label class="custom-file-label"
                                            for="customFileEg1">{{ translate('messages.choose') }}
                                            {{ translate('messages.file') }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>




                    {{-- <div class="form-group mb-2">
                        <label class="form-label d-block">Image</label>
                        <input type="file" class="form-control-file" id="exampleFormControlFile1" 
                            name="image" value="{{$service['image']}}">
                    </div> --}}



                    {{-- <div class="form-group mb-2">
                        <label class="form-label d-block">User</label>
                        <input type="text" placeholder="+923056860156" class="form-control"
                            name="user_id" value="{{$service['user_id']}}">
                    </div> --}}



                    <div class="btn--container justify-content-end">
                        {{-- <button type="reset" class="btn btn--reset">Reset</button> --}}
                        <button type="submit" class="btn btn--primary">Save</button>
                    </div>

                </form>
            </div>



            <hr>

        </div>
    </div>
@endsection

@push('script_2')
    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#viewer').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        $("#customFileEg1").change(function() {
            readURL(this);
        });

        $('#reset_btn').click(function() {
            $('#zone').val(null).trigger('change');
            $('#choice_item').val(null).trigger('change');
            $('#viewer').attr('src', '{{ asset('public/assets/admin/img/900x400/img1.jpg') }}');
        })
    </script>
@endpush

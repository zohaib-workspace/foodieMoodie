@extends('layouts.admin.app')

@section('title', translate('messages.shifts'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-12 mb-2 mb-sm-0">
                    <h1 class="page-header-title"><i class="tio-add-circle-outlined"></i> {{ translate('messages.Create') }}
                        {{ translate('messages.new') }} {{ translate('messages.Shift') }}</h1>

                </div>
                {{-- <a href="{{route('admin.delivery-man.add')}}" class="btn btn-primary pull-right"><i
                                class="tio-add-circle"></i> {{translate('messages.add')}} {{translate('messages.deliveryman')}}</a>

                @if (!isset(auth('admin')->user()->zone_id))
                <div class="col-sm-auto min-250">
                    <select name="zone_id" class="form-control js-select2-custom"
                            onchange="set_zone_filter('{{route('admin.delivery-man.list')}}', this.value)">
                        <option value="all">All Zones</option>
                        @foreach (\App\Models\Zone::orderBy('name')->get() as $z)
                            <option
                                value="{{$z['id']}}" {{isset($zone) && $zone->id == $z['id']?'selected':''}}>
                                {{$z['name']}}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif --}}
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.delivery-man.shift-add') }}" method="post" id="shift_form">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="exampleFormControlInput1">{{ translate('messages.start_time') }}</label>
                                        <input type="time" name="start_time" class="form-control"
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
                                                    <option value="{{ $zone['id'] }}">{{ $zone['name'] }}</option>
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
                                        <textarea placeholder="{{translate('Description')}} {{translate('of')}} {{translate('shift')}}" class="form-control" name="description" required></textarea>
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
                                        <input type="time" name="end_time" class="form-control"
                                            placeholder="{{ translate('messages.ending_time_of_shift') }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="exampleFormControlInput1">{{ translate('messages.shift_date') }}</label>
                                        <input type="date" id="date_from" name="shift_date" class="form-control"
                                            placeholder="{{ translate('messages.ending_time_of_shift') }}" required>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="col-md-6">
                                    <div class="h-100 d-flex flex-column justify-content-center">
                                        <div class="form-group mt-auto">
                                            <label class="d-block text-center">{{translate('messages.campaign')}} {{translate('messages.image')}} <small class="text-danger">* ( {{translate('messages.ratio')}} 1000x300 )</small></label>
                                        </div>
                                        <div class="form-group mt-auto">
                                            <center>
                                                <img class="initial-2" id="viewer"
                                                    src="{{asset('public/assets/admin/img/900x400/img1.jpg')}}" alt="campaign image"/>
                                            </center>
                                        </div>
                                        <div class="form-group mt-auto">
                                            <div class="custom-file">
                                                <input type="file" name="image" id="customFileEg1" class="custom-file-input"
                                                    accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" required>
                                                <label class="custom-file-label" for="customFileEg1">{{translate('messages.choose')}} {{translate('messages.file')}}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}
                    </div>
                    
                    <div class="btn--container justify-content-end">
                        <button id="reset_btn" type="reset"
                            class="btn btn--reset">{{ translate('messages.reset') }}</button>
                        <button type="submit" class="btn btn--primary">{{ translate('messages.submit') }}</button>
                    </div>
                </div>
                    </form>
                    
                </div>
            </div>

        </div>
        <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
            <!-- Card -->
            <div class="card">
                <!-- Header -->
                <div class="card-header py-2">

                    <h1 class="page-header-title text-capitalize">
                        <div class="card-header-icon d-inline-flex mr-2 img">
                            <img src="{{ asset('/public/assets/admin/img/delivery-man.png') }}" alt="public">
                        </div>
                        <span>
                            {{ translate('Shifts List') }}
                        </span>
                        <span class="badge badge-soft-dark ml-2" id="itemCount">{{$shifts->count()}}</span>
                    </h1>
                    <form id="search-form">
                        @csrf
                        <!-- Search -->
                        <div class="input--group input-group input-group-merge input-group-flush">
                            <input id="datatableSearch" type="search" name="search" class="form-control" placeholder="{{ translate('Ex : Search by title ...') }}" aria-label="{{translate('messages.search_here')}}">
                            <button type="submit" class="btn btn--secondary">
                                <i class="tio-search"></i>
                            </button>
                        </div>
                        <!-- End Search -->
                    </form>
                </div>
                <!-- End Header -->

                <!-- Table -->
                <div class="table-responsive datatable-custom fz--14px">
                    <table id="columnSearchDatatable"
                        class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                        data-hs-datatables-options='{
                                 "order": [],
                                 "orderCellsTop": true,
                                 "paging":false
                               }'>
                        <thead class="thead-light">
                            <tr>
                                <th class="text-capitalize">{{ translate('messages.sl') }}</th>
                                <th class="text-capitalize w-20p">{{ translate('messages.rider_name') }}</th>
                                <th class="text-capitalize">{{ translate('messages.Start_Time') }}</th>
                                <th class="text-capitalize">{{ translate('messages.End_Time') }}</th>
                                <th class="text-capitalize">{{ translate('messages.zone') }}</th>
                                <th class="text-capitalize text-center">{{ translate('Shift Date') }}</th>
                                <th class="text-capitalize">{{ translate('messages.Status') }}</th>
                                <th class="text-capitalize text-center w-110px">{{ translate('messages.action') }}</th>
                            </tr>
                        </thead>

                        <tbody id="set-rows">

                            @foreach ($shifts as $key => $shift)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        @if ($shift->rider)
                                            <a class="table-rest-info"
                                                href="{{ route('admin.delivery-man.preview', [$shift['id']]) }}">
                                                <img onerror="this.src='{{ asset('public/assets/admin/img/160x160/img1.jpg') }}'"
                                                    src="{{ asset('storage/app/public/delivery-man') }}/{{ $shift['image'] }}"
                                                    alt="{{ $shift->rider['f_name'] }} {{ $shift->rider['l_name'] }}">
                                                <div class="info">
                                                    <h5 class="text-hover-primary mb-0">
                                                        {{ $shift->rider['f_name'] . ' ' . $shift->rider['l_name'] }}</h5>
                                                    <span class="d-block text-body">
                                                        <!-- Rating -->
                                                        <span class="rating">
                                                            <i class="tio-star"></i>
                                                            {{ count($shift->rider->rating) > 0 ? number_format($shift->rider->rating[0]->average, 1, '.', ' ') : 0 }}
                                                        </span>
                                                        <!-- Rating -->
                                                    </span>
                                                </div>
                                            </a>
                                        @else
                                            <span class="table-rest-info">
                                                <img src={{ asset('public/assets/admin/img/160x160/img1.jpg') }}
                                                    alt="">
                                                <div class="info">
                                                    <h5 class="text-hover-primary mb-0">
                                                        No Rider Assigned</h5>

                                                </div>
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        {{ $shift['start_time'] }}
                                    </td>
                                    <td>
                                        {{ $shift['end_time'] }}
                                        {{-- <span class="d-block font-size-sm">{{$banner['image']}}</span> --}}
                                    </td>
                                    <td>
                                        {{ $shift->zone['name'] }}
                                    </td>

                                    <td>
                                        {{ $shift['shift_date'] }}
                                    </td>

                                    <td>


                                        {{-- <label class="toggle-switch toggle-switch-sm"
                                            for="statusCheckbox{{ $shift->id }}">
                                            <input type="checkbox"
                                                onclick="location.href='{{ route('admin.delivery-man.status', [$shift['id'], $shift->status ? 0 : 1]) }}'"
                                                class="toggle-switch-input" id="statusCheckbox{{ $shift->id }}"
                                                {{ $shift->status ? 'checked' : '' }}>
                                            <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label> --}}
                                        

                                        <form action="{{ route('admin.delivery-man.status', $shift->id) }}">
                                            <select name="status" id="priority"
                                                class=" form-control form--control-select {{ $shift->status == 'Started' ? 'text--title' : '' }} {{ $shift->status == 'Active' ? 'text--info' : '' }} {{ $shift->status == 'Ended' ? 'text--success' : '' }} "
                                                onchange="this.form.submit()">
                                                <option class="text--title" value="Started"
                                                    {{ $shift->status == 'Started' ? 'selected' : '' }}>
                                                    {{ translate('messages.Started') }}</option>
                                                <option class="text--info" value="Active"
                                                    {{ $shift->status == 'Active' ? 'selected' : '' }}>
                                                    {{ translate('messages.Active') }}</option>
                                                <option class="text--success" value="Ended"
                                                    {{ $shift->status == 'Ended' ? 'selected' : '' }}>
                                                    {{ translate('messages.Ended') }}</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td>
                                        <div class="btn--container justify-content-center">

                                            <!-- Button trigger modal -->
                                            <a class="btn btn-sm btn--primary btn-outline-primary action-btn" href="{{route('admin.delivery-man.shift-update',[$shift['id']])}}"title="{{translate('messages.edit')}} {{translate('messages.banner')}}"><i class="tio-edit"></i>
                                            </a>
                                            <a class="btn btn-sm btn--primary btn-outline-primary action-btn" data-bs-toggle="modal"
                                            data-bs-target="#exampleModal{{ $shift['id'] }}"><i class="tio-invisible"></i>
                                            </a>
                                            <a class="btn btn-sm btn--danger btn-outline-danger action-btn" href="javascript:" onclick="form_alert('banner-{{$shift['id']}}','{{translate('Want to delete this shift')}}')" title="{{translate('messages.delete')}} {{translate('messages.shift')}}"><i class="tio-delete-outlined"></i>
                                            </a>
                                            <form action="{{route('admin.delivery-man.shift-delete',[$shift['id']])}}"
                                                        method="post" id="banner-{{$shift['id']}}">
                                                    @csrf @method('delete')
                                            </form>
                                            {{-- <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#exampleModal{{ $shift['id'] }}">
                                                <i class="tio-invisible"></i>
                                            </button> --}}

                                            <!-- Modal -->
                                            <div class="modal fade" id="exampleModal{{ $shift['id'] }}" tabindex="-1"
                                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            @if ($shift->rider)
                                                                <a class="table-rest-info"
                                                                    href="{{ route('admin.delivery-man.preview', [$shift['id']]) }}">
                                                                    <img onerror="this.src='{{ asset('public/assets/admin/img/160x160/img1.jpg') }}'"
                                                                        src="{{ asset('storage/app/public/delivery-man') }}/{{ $shift['image'] }}"
                                                                        alt="{{ $shift->rider['f_name'] }} {{ $shift->rider['l_name'] }}">
                                                                    <div class="info">
                                                                        <h5 class="text-hover-primary mb-0">
                                                                            {{ $shift->rider['f_name'] . ' ' . $shift->rider['l_name'] }}
                                                                        </h5>
                                                                        <span class="d-block text-body">
                                                                            <!-- Rating -->
                                                                            <span class="rating">
                                                                                <i class="tio-star"></i>
                                                                                {{ count($shift->rider->rating) > 0 ? number_format($shift->rider->rating[0]->average, 1, '.', ' ') : 0 }}
                                                                            </span>
                                                                            <!-- Rating -->
                                                                        </span>
                                                                    </div>
                                                                </a>
                                                            @else
                                                                <span class="table-rest-info">
                                                                    <img src={{ asset('public/assets/admin/img/160x160/img1.jpg') }}
                                                                        alt="">
                                                                    <div class="info">
                                                                        <h5 class="text-hover-primary mb-0">
                                                                            No Rider Assigned</h5>

                                                                    </div>
                                                                </span>
                                                            @endif

                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="container">
                                                                <div class="row">
                                                                    <div class="col">
                                                                        <p class="h4">Start Time:</p>
                                                                    </div>
                                                                    <div class="col">
                                                                        <p class="h4">{{ $shift['start_time'] }}</p>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col">
                                                                        <p class="h4">End Time:</p>
                                                                    </div>
                                                                    <div class="col">
                                                                        <p class="h4">{{ $shift['end_time'] }}</p>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col">
                                                                        <p class="h4">Zone:</p>
                                                                    </div>
                                                                    <div class="col">
                                                                        <p class="h4">{{ $shift->zone['name'] }}</p>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col">
                                                                        <p class="h4">Shift Date:</p>
                                                                    </div>
                                                                    <div class="col">
                                                                        <p class="h4">{{ $shift['shift_date'] }}</p>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col">
                                                                        <p class="h4">Status:</p>
                                                                    </div>
                                                                    <div class="col">
                                                                        <p
                                                                            class="h4 {{ $shift->status == 'Started' ? 'badge badge-info' : '' }} {{ $shift->status == 'Active' ? 'badge badge-primary' : '' }} {{ $shift->status == 'Ended' ? 'badge badge-success' : '' }}">
                                                                            {{ $shift['status'] }}
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="container">

                                                                <p class="h2">Description</p>
                                                                {{ $shift['description'] }}
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Close</button>
                                                            {{-- <button type="button" class="btn btn-primary">Save
                                                                    changes</button> --}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- <form action="{{ route('admin.delivery-man.delete', [$shift['id']]) }}"
                                                method="post" id="delivery-man-{{ $shift['id'] }}">
                                                @csrf @method('delete')
                                            </form> --}}
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $shifts->links() }}

                    {{-- @if (count($delivery_men) === 0)
                        <div class="empty--data">
                            <img src="{{asset('/public/assets/admin/img/empty.png')}}" alt="public">
                            <h5>
                                {{translate('no_data_found')}}
                            </h5>
                        </div>
                        @endif --}}
                    <div class="page-area px-4 pb-3">
                        <div class="d-flex align-items-center justify-content-end">
                            {{-- <div>
                                    1-15 of 380
                                </div> --}}
                            {{-- <div>
                                        {!! $delivery_men->links() !!}
                                </div> --}}
                        </div>
                    </div>
                </div>
                <!-- End Table -->
            </div>
            <!-- End Card -->
        </div>
    </div>
    </div>

@endsection

@push('script_2')
    <script>
        $(document).on('ready', function() {
            // INITIALIZATION OF DATATABLES
            // =======================================================
            var datatable = $.HSCore.components.HSDatatables.init($('#columnSearchDatatable'));

            $('#column1_search').on('keyup', function() {
                datatable
                    .columns(1)
                    .search(this.value)
                    .draw();
            });

            $('#column2_search').on('keyup', function() {
                datatable
                    .columns(2)
                    .search(this.value)
                    .draw();
            });

            $('#column3_search').on('keyup', function() {
                datatable
                    .columns(3)
                    .search(this.value)
                    .draw();
            });

            $('#column4_search').on('keyup', function() {
                datatable
                    .columns(4)
                    .search(this.value)
                    .draw();
            });


            // INITIALIZATION OF SELECT2
            // =======================================================
            $('.js-select2-custom').each(function() {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });
        });
    </script>

    <script>
        $("#date_from").on("change", function() {
            $('#date_to').attr('min', $(this).val());
        });

        $("#date_to").on("change", function() {
            $('#date_from').attr('max', $(this).val());
        });
        $(document).ready(function() {
            $('#date_from').attr('min', (new Date()).toISOString().split('T')[0]);
            $('#date_to').attr('min', (new Date()).toISOString().split('T')[0]);
        });
        $('#search-form').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{ route('admin.delivery-man.shift-search') }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#loading').show();
                },
                success: function(data) {
                    $('#set-rows').html(data.view);
                    $('#itemCount').html(data.count);
                    $('.page-area').hide();
                },
                complete: function() {
                    $('#loading').hide();
                },
            });
        });

        $('#shift_form').on('submit', function (e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{route('admin.delivery-man.shift-add')}}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    if (data.errors) {
                        for (var i = 0; i < data.errors.length; i++) {
                            toastr.error(data.errors[i].message, {
                                CloseButton: true,
                                ProgressBar: true
                            });
                        }
                    } else {
                        toastr.success('{{ translate('Shift added successfully!') }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                        setTimeout(function () {
                            location.href = '{{route('admin.delivery-man.shifts')}}';
                        }, 2000);
                    }
                }
            });
        });
    </script>
@endpush

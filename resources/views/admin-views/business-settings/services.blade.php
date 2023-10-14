@extends('layouts.admin.app')

@section('title', 'Services')

@push('css_or_js')
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title"><i class="tio-add-circle-outlined"></i> {{ translate('messages.add') }}
                        {{ translate('messages.new') }} {{ translate('messages.service') }}</h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <div class="card">
                    <div class="card-body">

                        <form action="{{ route('admin.business-settings.services-add') }}" method="post" id="service_form"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group mb-2">
                                        <label class="form-label d-block">{{ translate('Name') }}</label>
                                        <input type="text" placeholder="{{ translate('messages.Car Wash') }}"
                                            class="form-control" name="name">
                                    </div>


                                    <div class="form-group mb-2">
                                        <label class="form-label d-block">{{ translate('WA-Number') }}</label>
                                        <input type="text" placeholder="+923056860156" class="form-control"
                                            name="wa_number">
                                    </div>
                                    {{-- <div class="form-group mb-2">
                                        <label class="form-label d-block">{{ translate('User') }}</label>
                                        <input type="text" placeholder="" class="form-control"
                                            name="user_id">
                                    </div> --}}
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
                                    <div class="form-group mb-2">
                                        <label class="form-label d-block">{{ translate('description') }}</label>
                                        <textarea placeholder="{{translate('Description')}} {{translate('of')}}" class="form-control" name="description"></textarea>
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
                                                    src="{{ asset('public/assets/admin/img/900x400/img1.jpg') }}"
                                                    alt="campaign image" />
                                            </center>
                                        </div>
                                        <div class="form-group mt-auto">
                                            <div class="custom-file">
                                                <input type="file" name="image" id="customFileEg1"
                                                    class="custom-file-input"
                                                    accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" required>
                                                <label class="custom-file-label"
                                                    for="customFileEg1">{{ translate('messages.choose') }}
                                                    {{ translate('messages.file') }}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>



                            <div class="btn--container justify-content-end">
                                <button type="reset" class="btn btn--reset">{{ translate('reset') }}</button>
                                <button type="submit" class="btn btn--primary">{{ translate('save') }}</button>
                            </div>

                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">{{ translate('messages.service') }} {{ translate('messages.list') }}<span class="badge badge-soft-dark ml-2" id="itemCount">{{$services->count()}}</span></h5>
                        <form id="search-form">
                            @csrf
                            <!-- Search -->
                            <div class="input--group input-group input-group-merge input-group-flush">
                                <input id="datatableSearch" type="search" name="search" class="form-control"
                                    placeholder="{{ translate('Ex : Search by title ...') }}"
                                    aria-label="{{ translate('messages.search_here') }}">
                                <button type="submit" class="btn btn--secondary">
                                    <i class="tio-search"></i>
                                </button>
                            </div>
                            <!-- End Search -->
                        </form>
                    </div>
                    <div class="table-responsive datatable-custom">
                        <table id="columnSearchDatatable"
                            class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                            data-hs-datatables-options='{
                                         "order": [],
                                         "orderCellsTop": true
                                       }'>
                            <thead class="thead-light">
                                <tr>
                                    <th>#{{ translate('messages.sl') }}</th>
                                    <th class="w-30p">{{ translate('Name') }}</th>
                                    <th class="w-25p">{{ translate('status') }}</th>
                                    <th>Details</th>

                                    <th>Action</th>
                                    <th></th>
                                </tr>

                            </thead>

                            <tbody id="set-rows">
                                @foreach ($services as $key => $service)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        
                                        <td>
                                            <span class="media align-items-center">
                                                <img class="avatar avatar-lg mr-3 avatar--3-1"
                                                    src="{{ asset('storage/app/public/service')}}/{{ $service['image'] }}"
                                                    
                                                    onerror="this.src='{{ asset('public/assets/admin/img/900x400/img1.jpg') }}'"
                                                    alt="{{ $service->name }} image">
                                                <div class="media-body">
                                                    <h5 class="text-hover-primary mb-0">
                                                        {{ Str::limit($service['name'], 25, '...') }}</h5>
                                                </div>
                                            </span>
                                            {{-- <span class="d-block font-size-sm text-body">
                                                {{ $service['name'] }}
                                            </span> --}}
                                        </td>
                                        <td>
                                            <label class="toggle-switch toggle-switch-sm"
                                                for="statusCheckbox{{ $service->id }}">
                                                <input type="checkbox"
                                                    onclick="location.href='{{ route('admin.business-settings.status', [$service['id'], $service->status ? 0 : 1]) }}'"
                                                    class="toggle-switch-input" id="statusCheckbox{{ $service->id }}"
                                                    {{ $service->status ? 'checked' : '' }}>
                                                <span class="toggle-switch-label">
                                                    <span class="toggle-switch-indicator"></span>
                                                </span>
                                            </label>
                                        </td>
                                        <td>
                                            <!-- Button trigger modal -->

                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#exampleModal{{ $service['id'] }}">
                                                <i class="tio-invisible"></i>
                                            </button>

                                            <!-- Modal -->
                                            <div class="modal fade" id="exampleModal{{ $service['id'] }}" tabindex="-1"
                                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            {{-- <a class="table-rest-info" href="">
                                                                <img onerror="this.src='{{ asset('public/assets/admin/img/160x160/img1.jpg') }}'"
                                                                    src="}" alt="">
                                                                <div class="info">
                                                                    <h5 class="text-hover-primary mb-0">
                                                                        {{ $service['name'] }}
                                                                    </h5>
                                                                    <span class="d-block text-body">
                                                                        <!-- Rating -->
                                                                         <span class="rating">
                                                                            <i class="tio-star"></i>
                                                                            {{ count($dm->rider->rating) > 0 ? number_format($dm->rider->rating[0]->average, 1, '.', ' ') : 0 }}
                                                                        </span> 
                                                                        <!-- Rating -->
                                                                    </span>
                                                                </div>
                                                            </a> --}}
                                                            {{ $service['name'] }}

                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="container">
                                                                {{-- <div class="row">
                                                                    <div class="col">
                                                                        <p class="h4">Start Time:</p>
                                                                    </div>
                                                                    <div class="col">
                                                                        <p class="h4">{{ $dm['start_time'] }}</p>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col">
                                                                        <p class="h4">End Time:</p>
                                                                    </div>
                                                                    <div class="col">
                                                                        <p class="h4">{{ $dm['end_time'] }}</p>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col">
                                                                        <p class="h4">Zone:</p>
                                                                    </div>
                                                                    <div class="col">
                                                                        <p class="h4">{{ $dm->zone['name'] }}</p>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col">
                                                                        <p class="h4">Shift Date:</p>
                                                                    </div>
                                                                    <div class="col">
                                                                        <p class="h4">{{ $dm['shift_date'] }}</p>
                                                                    </div>
                                                                </div> --}}
                                                                <div class="row">
                                                                    <div class="col">
                                                                        <div class="row">
                                                                            <div class="col">
                                                                                <p class="h4">Status:</p>
                                                                            </div>
                                                                            <div class="col">
                                                                                <p
                                                                                    class="h4 {{ $service['status'] == '1' ? 'badge badge-success' : 'badge badge-danger' }}">
                                                                                    {{ $service['status'] == '1' ? 'Active' : 'Offline' }}
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col">
                                                                                <p class="h4">Zone:</p>
                                                                            </div>
                                                                            <div class="col">
                                                                                <p class="h4 ">
                                                                                    {{ $service['zone_id'] }}</p>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col">
                                                                                <p class="h4">WA No:</p>
                                                                            </div>
                                                                            <div class="col">
                                                                                <p class="h4 ">
                                                                                    {{ $service['wa_number'] }}</p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col">
                                                                        <span class="media align-items-center">
                                                                            <img class="avatar avatar-lg mr-3 avatar--3-1"
                                                                                src="{{ asset('storage/app/public/service') }}/{{ $service['image'] }}"
                                                                                onerror="this.src='{{ asset('public/assets/admin/img/900x400/img1.jpg') }}'"
                                                                                alt="{{ $service->name }} image">
                                                                            {{-- <div class="media-body">
                                                                                <h5 class="text-hover-primary mb-0">
                                                                                    {{ Str::limit($service['name'], 25, '...') }}</h5>
                                                                            </div> --}}
                                                                        </span>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                            <div class="container">

                                                                <p class="h2">{{ translate('description') }}</p>
                                                                {{ $service['description'] }}

                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">{{ translate('close') }}</button>
                                                            {{-- <button type="button" class="btn btn-primary">Save
                                                                changes</button> --}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </td>

                                        <td>
                                            <!-- Dropdown -->
                                            <div class="dropdown">
                                                <button class="btn btn-secondary dropdown-toggle" type="button"
                                                    id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                    <i class="tio-settings"></i>
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                                                    <a class="dropdown-item"
                                                        href="{{ route('admin.business-settings.service-update', [$service['id']]) }}">
                                                        {{ translate('edit') }}
                                                    </a>

                                                    <a class="dropdown-item" href="javascript:"
                                                        onclick="$('#service-{{ $service['id'] }}').submit()">{{ translate('delete') }}</a>
                                                    <form
                                                        action="{{ route('admin.business-settings.service-delete', [$service['id']]) }}"
                                                        method="post" id="service-{{ $service['id'] }}">
                                                        @csrf @method('delete')
                                                    </form>

                                                </div>
                                            </div>
                                            <!-- End Dropdown -->

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
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


            // INITIALIZATION OF SELECT2
            // =======================================================
            $('.js-select2-custom').each(function() {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });

            // $('#service_form').on('submit', function(e) {
            //     e.preventDefault();
            //     var formData = new FormData(this);
            //     $.ajaxSetup({
            //         headers: {
            //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //         }
            //     });
            //     $.post({
            //         url: '{{ route('admin.business-settings.services-add') }}',
            //         data: formData,
            //         cache: false,
            //         contentType: false,
            //         processData: false,
            //         success: function(data) {
            //             if (data.errors) {
            //                 for (var i = 0; i < data.errors.length; i++) {
            //                     toastr.error(data.errors[i].message, {
            //                         CloseButton: true,
            //                         ProgressBar: true
            //                     });
            //                 }
            //             } else {
            //                 toastr.success(
            //                     '{{ translate('Banner uploaded successfully!') }}', {
            //                         CloseButton: true,
            //                         ProgressBar: true
            //                     });
            //                 setTimeout(function() {
            //                     location.href = '{{ route('admin.banner.add-new') }}';
            //                 }, 2000);
            //             }
            //         }
            //     });
            // });
        });

        function getRequest(route, id) {
            $.get({
                url: route,
                dataType: 'json',
                success: function(data) {
                    $('#' + id).empty().append(data.options);
                },
            });
        }
    </script>
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

        $('#search-form').on('submit', function (e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{route('admin.business-settings.service-search')}}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    $('#set-rows').html(data.view);
                    $('#itemCount').html(data.count);
                    $('.page-area').hide();
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        });
    </script>
@endpush

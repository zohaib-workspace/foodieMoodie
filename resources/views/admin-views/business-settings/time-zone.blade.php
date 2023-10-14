@extends('layouts.admin.app')

@section('title', 'Time Zone')

@push('css_or_js')
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title"><i class="tio-add-circle-outlined"></i>{{ translate('messages.add') }} {{ translate('messages.Time') }}{{ translate('messages.zone') }}</h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.business-settings.timezone-add') }}" method="post" id="timezone_form"
                            enctype="multipart/form-data">
                            @csrf

                            <div class="form-group mb-2">
                                <label class="form-label d-block">{{ translate('messages.time') }} {{ translate('messages.zone') }}</label>
                                <input type="text" placeholder="{{ translate('messages.Ex :') }} Bangladesh"
                                    class="form-control" name="timezone">
                            </div>

                            <div class="form-group mb-2">
                                <label class="form-label d-block">{{ translate('messages.GMT') }} {{ translate('messages.Time') }}</label>
                                <input type="text" placeholder="{{ translate('messages.Ex :') }} USD"
                                    class="form-control" name="gmt_time">
                            </div>

                            {{-- <div class="form-group mb-2">
                        <label class="form-label d-block">Status</label>
                        <input type="text" placeholder="{{ translate('messages.Ex :') }} $" class="form-control"
                            name="status">
                    </div> --}}
                            <div class="btn--container justify-content-end">
                                <button type="reset" class="btn btn--reset">{{ translate('messages.reset') }}</button>
                                <button type="submit" class="btn btn--primary">{{ translate('messages.save') }}</button>
                            </div>

                        </form>
                    </div>
                </div>

            </div>



            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">{{translate('messages.Time_Zones')}} {{translate('messages.list')}}<span class="badge badge-soft-dark ml-2" id="itemCount">{{$timezones->count()}}</span></h5>
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

                    <div class="table-responsive datatable-custom">
                        <table id="columnSearchDatatable"
                            class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                            data-hs-datatables-options='{
                                         "order": [],
                                         "search": "#datatableSearch",
                                         "orderCellsTop": true
                                       }'>
                            <thead class="thead-light">
                                <tr>
                                    <th>#{{ translate('messages.sl') }}</th>
                                    <th class="w-30p">{{ translate('messages.Time_Zone') }}</th>
                                    <th class="w-25p">{{ translate('messages.GMT_Time') }}</th>
                                    <th>{{ translate('messages.status') }}</th>
        
                                    <th>{{ translate('messages.Action') }}</th>
                                    <th></th>
                                </tr>
                                
                            </thead>
        
                            <tbody id="set-rows">
                                @foreach ($timezones as $key => $tz)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            <span class="d-block font-size-sm text-body">
                                                {{ $tz['timezone'] }}
                                            </span>
                                        </td>
                                        <td>
                                            {{ $tz['gmt_time'] }}
                                        </td>
                                        <td>
                                            {{ $tz['status'] }}
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
                                                    {{--@if ($tz['timezone'] != 'Asia/Karachi')--}}
                                                        <a class="dropdown-item"
                                                            href="{{ route('admin.business-settings.timezone-update', [$tz['id']]) }}">Edit</a>
                                                        <a class="dropdown-item" href="javascript:"
                                                            onclick="$('#currency-{{ $tz['id'] }}').submit()">Delete</a>
                                                        <form
                                                            action="{{ route('admin.business-settings.timezone-delete', [$tz['id']]) }}"
                                                            method="post" id="currency-{{ $tz['id'] }}">
                                                            @csrf @method('delete')
                                                        </form>
                                                    {{--@else
                                                        <a class="dropdown-item" href="javascript:">
                                                            Default
                                                        </a>
                                                    @endif--}}
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
            <!-- Table -->
            
            
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
        });

        $('#timezone_form').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{ route('admin.business-settings.timezone-add') }}',
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
                        toastr.success('{{ translate('TimeZone added successfully!') }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                        setTimeout(function() {
                            location.href =
                                '{{ route('admin.business-settings.timezone-add') }}';
                        }, 2000);
                    }
                }
            });
        });
        $('#search-form').on('submit', function (e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{route('admin.business-settings.search')}}',
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

@extends('layouts.admin.app')

@section('title', translate('Order Report Center'))


@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title"><i class="tio-add-circle-outlined"></i> {{ translate('messages.Order') }}
                        {{ translate('messages.Reports') }} </h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">

            {{-- <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <div class="card">
                    <div class="card-body">
                        <form action="{{route('admin.banner.store')}}" method="post" id="banner_form">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="input-label" for="exampleFormControlInput1">{{translate('messages.title')}}</label>
                                        <input type="text" name="title" class="form-control" placeholder="{{translate('messages.new_banner')}}" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="input-label" for="title">{{translate('messages.zone')}}</label>
                                        <select name="zone_id" id="zone" class="form-control js-select2-custom" onchange="getRequest('{{url('/')}}/admin/food/get-foods?zone_id='+this.value,'choice_item')">
                                            <option disabled selected value="">---{{translate('messages.select')}}---</option>
                                            @php($zones=\App\Models\Zone::active()->get())
                                            @foreach ($zones as $zone)
                                                @if (isset(auth('admin')->user()->zone_id))
                                                    @if (auth('admin')->user()->zone_id == $zone->id)
                                                        <option value="{{$zone->id}}" selected>{{$zone->name}}</option>
                                                    @endif
                                                @else
                                                    <option value="{{$zone['id']}}">{{$zone['name']}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="input-label" for="exampleFormControlInput1">{{translate('messages.banner')}} {{translate('messages.type')}}</label>
                                        <select name="banner_type" id="banner_type" class="form-control" onchange="banner_type_change(this.value)">
                                            <option value="restaurant_wise">{{translate('messages.business')}} {{translate('messages.wise')}}</option>
                                            <option value="item_wise">{{translate('messages.food')}} {{translate('messages.wise')}}</option>
                                        </select>
                                    </div>
                                    <div class="form-group" id="restaurant_wise">
                                        <label class="input-label" for="exampleFormControlSelect1">{{translate('messages.business')}}<span
                                                class="input-label-secondary"></span></label>
                                        <select name="restaurant_id" class="js-data-example-ajax form-control"  title="Select Business">
                                            <option selected disabled>{{ translate('Select') }}</option>
                                        </select>
                                    </div>
                                    <div class="form-group" id="item_wise">
                                        <label class="input-label" for="exampleFormControlInput1">{{translate('messages.select')}} {{translate('messages.food')}}</label>
                                        <select name="item_id" id="choice_item" class="form-control js-select2-custom" placeholder="{{translate('messages.select_food')}}">
                                            <option selected disabled>{{ translate('Select Restaurant') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
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
                                </div>
                            </div>
                            <div class="btn--container justify-content-end">
                                <button id="reset_btn" type="reset" class="btn btn--reset">{{translate('messages.reset')}}</button>
                                <button type="submit" class="btn btn--primary">{{translate('messages.submit')}}</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div> --}}

            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">{{ translate('messages.order') }} {{ translate('messages.Reports') }}<span
                                class="badge badge-soft-dark ml-2" id="itemCount">{{ $orderReports->count() }}</span></h5>
                        {{-- <form id="search-form">
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
                        </form> --}}
                    </div>
                    <!-- Table -->
                    <div class="table-responsive datatable-custom">
                        <table id="columnSearchDatatable"
                            class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                            data-hs-datatables-options='{
                                "order": [],
                                "orderCellsTop": true,
                                "search": "#datatableSearch",
                                "entries": "#datatableEntries",
                                "isResponsive": false,
                                "isShowPaging": false,
                                "paging": false,
                               }'>
                            <thead class="thead-light ">
                                <tr>
                                    <th>{{ translate('messages.sl') }}</th>
                                    <th>{{ translate('messages.order_id') }}</th>
                                    <th>{{ translate('messages.complain') }}</th>
                                    <th class="text-center">{{ translate('messages.status') }}</th>
                                    <th class="text-center">{{ translate('messages.action') }}</th>
                                </tr>
                            </thead>

                            <tbody id="set-rows">
                                @foreach ($orderReports as $key => $orderRepo)
                                    <tr>
                                        <td>
                                            {{ $key + $orderReports->firstItem() }}

                                        </td>
                                        <td>
                                            <a href="{{route('admin.order.details',['id'=>$orderRepo['order_id']])}}">#{{$orderRepo['order_id']}}</a></h5>
                                        </td>
                                        <td>{{ translate('messages.' . $orderRepo['complain']) }}</td>
                                        <td class="text-capitalize text-center">
                                            <span
                                                class=" badge 
                                                    {{ $orderRepo['status'] == 'completed' ? 'badge-info' : ($orderRepo['status'] == 'pending' ? 'badge-warning' : ($orderRepo['status'] == 'rejected' ? 'badge-danger' : '')) }}
                                                    ">
                                                {{ $orderRepo['status'] }}
                                            </span>
                    </div>
                    </td>
                    <td>
                        <div class="btn--container justify-content-center" data-toggle="modal"
                            data-target="#exampleModal{{ $orderRepo['id'] }}">
                            <a class="ml-2 btn btn-sm btn--warning btn-outline-warning action-btn">
                                <i class="tio-invisible"></i>
                            </a>


                        </div>
                        <!-- Button trigger modal -->
                        {{-- <button type="button" class="btn btn-primary 
                            " data-toggle="modal" data-target="#exampleModal">
                            
                        </button> --}}

                        <!-- Modal -->
                        <div class="modal fade" id="exampleModal{{ $orderRepo['id'] }}" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">{{ translate('messages.Order_Id') }}
                                            <a href="{{route('admin.order.details',['id'=>$orderRepo['order_id']])}}">#{{$orderRepo['order_id']}}</a></h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('admin.report.admin-response', $orderRepo->id) }}" method="post" id="banner_for">
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="input-label"
                                                            for="exampleFormControlInput1">{{ translate('messages.title') }}</label>
                                                        <textarea name="response" class="form-control" placeholder="{{ translate('messages.Response_to_the_Report') }}"></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="input-label"
                                                            for="exampleFormControlInput1">{{ translate('messages.status') }}</label>
                                                    <select name="status" id="priority1"
                                                        class=" form-control form--control-select {{ $orderRepo->status == 'pending' ? 'text--title' : '' }} {{ $orderRepo->status == 'completed' ? 'text--info' : '' }} {{ $orderRepo->status == 'rejected' ? 'text--danger' : '' }} "
                                                        >
                                                        <option class="text--title" value="pending"
                                                            {{ $orderRepo->status == 'pending' ? 'selected' : '' }}>
                                                            {{ translate('messages.Pending') }}</option>
                                                        <option class="text--info" value="completed"
                                                            {{ $orderRepo->status == 'completed' ? 'selected' : '' }}>
                                                            {{ translate('messages.Completed') }}</option>
                                                        <option class="text--danger" value="rejected"
                                                            {{ $orderRepo->status == 'rejected' ? 'selected' : '' }}>
                                                            {{ translate('messages.Rejected') }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="btn--container justify-content-end">
                                                {{-- <button id="reset_btn" type="reset"
                                                    class="btn btn--reset">{{ translate('messages.reset') }}</button> --}}
                                                <button type="submit"
                                                    class="btn btn--primary">{{ translate('messages.submit') }}</button>
                                            </div>
                                        </form>
                                    </div>
                                    {{-- <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-primary">Save changes</button>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                    </td>
                    </tr>
                    @endforeach
                    </tbody>
                    </table>
                    @if (count($orderReports) === 0)
                        <div class="empty--data">
                            <img src="{{ asset('/public/assets/admin/img/empty.png') }}" alt="public">
                            <h5>
                                {{ translate('no_data_found') }}
                            </h5>
                        </div>
                    @endif
                    <div class="page-area px-4 pb-3">
                        <div class="d-flex align-items-center justify-content-end">
                            {{-- <div>
                                    1-15 of 380
                                </div> --}}
                            <div>
                                {!! $orderReports->links() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Table -->
    </div>
    </div>

@endsection

@push('script_2')
    <script>
        function getRequest(route, id) {
            $.get({
                url: route,
                dataType: 'json',
                success: function(data) {
                    $('#' + id).empty().append(data.options);
                },
            });
        }

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
    </script>
    <script>
        $(document).on('ready', function() {
            var zone_id = [];
            var select_control = $('#banner_type, #restaurant_wise select, #item_wise select');
            $('#zone').on('change', function() {
                if ($(this).val()) {
                    zone_id = $(this).val();
                } else {
                    zone_id = [];
                }
                if ($('#zone').val() == undefined) {
                    select_control.attr('disabled', '')
                } else {
                    select_control.removeAttr('disabled')
                }
            });
            if ($('#zone').val() == undefined) {
                select_control.attr('disabled', '')
            } else {
                select_control.removeAttr('disabled')
            }

            $('.js-data-example-ajax').select2({
                ajax: {
                    url: '{{ url('/') }}/admin/vendor/get-restaurants',
                    data: function(params) {
                        return {
                            q: params.term, // search term
                            zone_ids: [zone_id],
                            page: params.page
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    },
                    __port: function(params, success, failure) {
                        var $request = $.ajax(params);

                        $request.then(success);
                        $request.fail(failure);

                        return $request;
                    }
                }
            });
            // INITIALIZATION OF DATATABLES
            // =======================================================
            var datatable = $.HSCore.components.HSDatatables.init($('#columnSearchDatatable'), {
                select: {
                    style: 'multi',
                    classMap: {
                        checkAll: '#datatableCheckAll',
                        counter: '#datatableCounter',
                        counterInfo: '#datatableCounterInfo'
                    }
                },
                language: {
                    zeroRecords: '<div class="text-center p-4">' +
                        '<img class="w-7rem mb-3" src="{{ asset('public/assets/admin/svg/illustrations/sorry.svg') }}" alt="Image Description">' +
                        '<p class="mb-0">{{ translate('No data to show') }}</p>' +
                        '</div>'
                }
            });

            $('#datatableSearch').on('mouseup', function(e) {
                var $input = $(this),
                    oldValue = $input.val();

                if (oldValue == "") return;

                setTimeout(function() {
                    var newValue = $input.val();

                    if (newValue == "") {
                        // Gotcha
                        datatable.search('').draw();
                    }
                }, 1);
            });

            // INITIALIZATION OF SELECT2
            // =======================================================
            $('.js-select2-custom').each(function() {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });
        });
        $('#item_wise').hide();

        function banner_type_change(order_type) {
            if (order_type == 'item_wise') {
                $('#restaurant_wise').hide();
                $('#item_wise').show();
            } else if (order_type == 'restaurant_wise') {
                $('#restaurant_wise').show();
                $('#item_wise').hide();
            } else {
                $('#item_wise').hide();
                $('#restaurant_wise').hide();
            }
        }

        $('#banner_form').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{ route('admin.banner.store') }}',
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
                        toastr.success('{{ translate('Banner uploaded successfully!') }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                        setTimeout(function() {
                            location.href = '{{ route('admin.banner.add-new') }}';
                        }, 2000);
                    }
                }
            });
        });
    </script>
    <script>
        $('#search-form').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{ route('admin.banner.search') }}',
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
    </script>
    <script>
        $('#reset_btn').click(function() {
            $('#zone').val(null).trigger('change');
            $('#choice_item').val(null).trigger('change');
            $('#viewer').attr('src', '{{ asset('public/assets/admin/img/900x400/img1.jpg') }}');
        })
    </script>
@endpush

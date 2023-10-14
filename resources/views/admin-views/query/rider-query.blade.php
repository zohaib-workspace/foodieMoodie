@extends('layouts.admin.app')

@section('title', translate('Rider Support Center'))


@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title"><i class="tio-add-circle-outlined"></i> {{ translate('messages.Rider') }}
                        {{ translate('messages.Queries') }} </h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">

       
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">{{ translate('messages.Query') }} {{ translate('messages.Reports') }}<span
                                class="badge badge-soft-dark ml-2" id="itemCount">{{ $riderReports->count() }}</span></h5>
                       
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
                                    <th>{{ translate('messages.Rider Name') }}</th>
                                    <th>{{ translate('messages.query') }}</th>
                                    <th>{{ translate('messages.query_description') }}</th>
                                    <th class="text-center">{{ translate('messages.status') }}</th>
                                    <th class="text-center">{{ translate('messages.action') }}</th>
                                </tr>
                            </thead>

                            <tbody id="set-rows">
                                @foreach ($riderReports as $key => $orderRepo)
                                    <tr>
                                        <td>
                                            {{ $key + $riderReports->firstItem() }}

                                        </td>
                                        <td>
                                            {{$orderRepo['rider']['f_name']}} {{$orderRepo['rider']['l_name']}}
                                        </td>
                                        <td>{{ translate('messages.' . $orderRepo['name']) }}</td>
                                        <td><span style="width: 1px;">{{ translate('messages.' . $orderRepo['description']) }}</span></td>

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
                                <i class="tio-edit"></i>
                            </a>


                        </div>
                     
                        <!-- Modal -->
                        <div class="modal fade" id="exampleModal{{ $orderRepo['id'] }}" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">{{ translate('messages.Rider Query') }}
                            </br>{{$orderRepo['rider']['f_name']}} {{$orderRepo['rider']['l_name']}}</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                   
                                    <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-6"> 
                                      <h6> {{ translate('messages.Query') }}</h6>
                                                {{ translate('messages.' . $orderRepo['name']) }}
                                                 

                                        </div>
                                        <div class="col-md-6"> 
                                       <h6>{{ translate('messages.Description') }}</h6>
             <p>  {{ translate('messages.' . $orderRepo['description']) }}</p>
                                                    </div>

                                        </div>
                                        <form action="{{ route('admin.userquires.admin-response-rider', $orderRepo->id) }}" method="post" id="banner_for">
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="input-label"
                                                            for="exampleFormControlInput1">{{ translate('messages.response') }}</label>
                                                        <textarea name="response" class="form-control" placeholder="{{ translate('messages.Response_to_the_Report') }}" >{{ translate('messages.' . $orderRepo['response']) }}</textarea>
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
                    @if (count($riderReports) === 0)
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
                                {!! $riderReports->links() !!}
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

@extends('layouts.admin.app')
@section('title',translate('messages.Businesss Slider List'))
@push('css_or_js')
@endpush
@section('content')

    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title"><i class="tio-notice"></i> {{translate('messages.business')}} {{translate('messages.slider')}} <span class="badge badge-soft-dark ml-2" id="itemCount">{{$business->total()}}</span></h1>
                </div>

                <div class="col-sm-auto">
                    <a class="btn btn--primary" href="{{route('admin.business_slider.add-new')}}">
                        <i class="tio-add"></i> {{translate('messages.add')}} {{translate('messages.new')}} {{translate('messages.Business slider')}}
                    </a>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <!-- Card -->
                <div class="card">
                    <div class="card-header py-2 border-0">
                        <div class="search--button-wrapper">
                            <h5 class="card-title"></h5>
                            <form id="search-form">
                                @csrf
                                <!-- Search -->
                                <div class="input--group input-group input-group-merge input-group-flush">
                                    <input id="datatableSearch" type="search" name="search" class="form-control" placeholder="{{ translate('Ex: Search by title...') }}" aria-label="Search here">
                                    <button type="submit" class="btn btn--secondary"><i class="tio-search"></i></button>
                                </div>
                                <!-- End Search -->
                            </form>
                        </div>
                    </div>
                    <!-- Table -->
                    <div class="table-responsive datatable-custom">
                        <table id="columnSearchDatatable"
                               class="font-size-sm table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                               data-hs-datatables-options='{
                                 "order": [],
                                 "orderCellsTop": true,
                                 "paging":false
                               }'>
                            <thead class="thead-light">
                            <tr>
                                <th>{{ translate('messages.sl') }}</th>
                                <th>{{translate('messages.image')}}</th>
                                <th >{{translate('messages.title')}}</th>
                                <th >{{translate('messages.Description')}}</th>
                                <th>{{translate('messages.status')}}</th>
                                <th class="text-center">{{translate('messages.action')}}</th>
                            </tr>
                            </thead>

                            <tbody id="set-rows">
                            @foreach($business as $key=>$slider)
                                <tr>
                                    <td>{{$key+$business->firstItem()}}</td>
                                    <td> <img onerror="this.src='{{asset('public/assets/admin/img/160x160/img1.jpg')}}'"
                                                    src="{{asset('storage/app/public/campaign')}}/{{$slider['image']}}" style="width:70px"></td>
                                    <td>
                                        <span class="d-block text-body">{{Str::limit($slider['title'],25, '...')}}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="d-block text-body">{{str::limit($slider['description'], 25,'...')}}
                                        </span>
                                    </td>
                                    <td>
                                        <label class="toggle-switch toggle-switch-sm" for="statusCheckbox{{$slider->id}}">
                                            <input type="checkbox" name="status" onclick="location.href='{{route('admin.business_slider.status',[$slider['id'],$slider->status?0:1])}}'" class="toggle-switch-input" id="statusCheckbox{{$slider->id}}" {{$slider->status?'checked':''}}>
                                            <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                            </span> 
                                        </label>
                                    </td>
                                    <td>
                                        <div class="btn--container justify-content-center">
                                            <a class="btn btn-sm btn--primary btn-outline-primary action-btn"
                                                href="{{route('admin.business_slider.edit',[$slider['id']])}}" title="{{translate('messages.edit')}} {{translate('messages.business_slider')}}"><i class="tio-edit"></i>
                                            </a>
                                            <a class="btn btn-sm btn--danger btn-outline-danger action-btn" href="javascript:"
                                                onclick="form_alert('slider-{{$slider['id']}}','{{translate('messages.Want_to_delete_this_item')}}')" title="{{translate('messages.delete')}} {{translate('messages.business_slider')}}"><i class="tio-delete-outlined"></i>
                                            </a>
                                            <form action="{{route('admin.business_slider.delete',[$slider['id']])}}"
                                                        method="post" id="slider-{{$slider['id']}}">
                                                @csrf @method('delete')
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        @if(count($business) === 0)
                        <div class="empty--data">
                            <img src="{{asset('/public/assets/admin/img/empty.png')}}" alt="public">
                            <h5>
                                {{translate('no_data_found')}}
                            </h5>
                        </div>
                        @endif
                        <div class="page-area px-4 pb-3">
                            <div class="d-flex align-items-center justify-content-end">
                                <div>
                                    {!! $business->links() !!}
                                </div>
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
        $(document).on('ready', function () {
            // INITIALIZATION OF DATATABLES
            // =======================================================
            var datatable = $.HSCore.components.HSDatatables.init($('#columnSearchDatatable'));

            $('#column1_search').on('keyup', function () {
                datatable
                    .search(this.value)
                    .draw();
            });

            $('#column2_search').on('keyup', function () {
                datatable
                    .columns(2)
                    .search(this.value)
                    .draw();
            });

            $('#column3_search').on('change', function () {
                datatable
                    .columns(3)
                    .search(this.value)
                    .draw();
            });

            $('#column4_search').on('keyup', function () {
                datatable
                    .columns(4)
                    .search(this.value)
                    .draw();
            });


            // INITIALIZATION OF SELECT2
            // =======================================================
            $('.js-select2-custom').each(function () {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });
        });
    </script>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">        <script src="{{ asset('public/assets/admin') }}/js/summernote.js"></script>

    <script>
        $('#search-form').on('submit', function (e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{route('admin.business_slider.search')}}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    console.log(data);
                    $('.page-area').hide();
                    $('#set-rows').html(data.view);
                    $('#itemCount').html(data.count);
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        });
    </script>
@endpush

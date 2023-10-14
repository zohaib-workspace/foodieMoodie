@extends('layouts.admin.app')
@section('title',translate('Add new Sub Query'))

@section('content')
<div class="content container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-sm mb-2 mb-sm-0">
                <h1 class="page-header-title">
                    <i class="tio-free-transform"></i>{{translate('messages.Sub Query')}} {{translate('messages.setup')}}
                </h1>
            </div>
        </div>
    </div>
    <div class="row gx-2 gx-lg-3">
        <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
            <form action="{{route('admin.query.sub_store')}}" method="post" class="shadow--card">
                @csrf
                <div class="row justify-content-between">
                    <div class="col-md-6 col-xl-7 sub_query-setup">
                        <div class="pl-xl-5 pl-xxl-0">
                        <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group mb-3">
                                    <label class="input-label" for="choice_business">{{ translate('messages.Top Query Name') }}
                                        <span data-toggle="tooltip" data-placement="right"
                                        class="input-label-secondary"><img
                                            src="{{ asset('/public/assets/admin/img/info-circle.svg') }}"
                                            alt="{{ translate('messages.restaurant_lat_lng_warning') }}"></span>
                                        </label>                               
                                        <select name="parent_id" id="choice_query" required class="form-control h--45px js-select2-custom"
                                    data-placeholder="{{ translate('messages.select') }} {{ translate('messages.Top Query Name') }}"> 
                                    <option value="" selected disabled>{{ translate('messages.select') }}
                                        {{ translate('messages.Top Query Name') }}</option>                               
                                    @foreach (\App\Models\Query::where('parent_id','0')->where('status' , '1')->get() as $c_name)                                     
                                            <option value="{{ $c_name->id }}">{{ $c_name->name }}</option>                               
                                    @endforeach
                                </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group mb-3">
                                        <label class="input-label">
                                            {{translate('messages.Sub Query Name')}}
                                        </label>
                                        <input  name="name" type="text" class="form-control h--45px" placeholder="{{ translate('messages.Ex : Sub Query Name') }} " required>
                                    </div>
                                </div>
                            </div>
  <div class="col-sm-6">
<div class="form-group mb-3">
                                    <label class="input-label" for="choice_rider">{{ translate('messages.Query Role') }}
                                        <span data-toggle="tooltip" data-placement="right"
                                        class="input-label-secondary"><img
                                            src="{{ asset('/public/assets/admin/img/info-circle.svg') }}"
                                            alt="{{ translate('messages.query_lat_lng_warning') }}"></span>
                                        </label>                               
                                        <select name="role" id="choice_role" required class="form-control h--45px js-select2-custom"
                                    data-placeholder="{{ translate('messages.select') }} {{ translate('messages.Query Role') }}"> 
                                    <option value="" selected disabled>{{ translate('messages.select') }}
                                        {{ translate('messages.Query Role') }}</option>                               
                                      <option value="rider">Rider</option> 
                                      <option value="user">User</option>               
                                      <option value="all">All</option>
                                </select>
                            </div></div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group mb-3">
                                        <label class="input-label">
                                            {{translate('messages.Description Name')}}
                                        </label>
                                        <input id="description" name="description" type="text" class="form-control h--45px" placeholder="{{ translate('messages.Sub Query Description :') }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="btn--container mt-3 justify-content-end">
                                <button id="reset_btn" type="button" class="btn btn--reset">{{translate('messages.reset')}}</button>
                                <button type="submit" class="btn btn--primary">{{translate('messages.submit')}}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="col-sm-12 col-lg-12 mb-3 my-lg-2">
                <div class="card">
                    <div class="card-header py-2 flex-wrap border-0 align-items-center">
                        <div class="search--button-wrapper">
                            <h5 class="card-title">{{translate('messages.Sub Query')}} {{translate('messages.list')}}<span class="badge badge-soft-dark ml-2" id="itemCount">{{$sub_query_1->count()}}</span></h5>
                            <form action="javascript:" id="search-form" class="my-2 mr-sm-2 mr-xl-4 ml-sm-auto flex-grow-1 flex-grow-sm-0">
                                            <!-- Search -->
                                @csrf
                                <div class="input--group input-group input-group-merge input-group-flush">
                                    <input id="datatableSearch_" type="search" name="search" class="form-control"
                                            placeholder="{{ translate('messages.Search_by_name') }}" aria-label="{{translate('messages.search')}}" required>
                                    <button type="submit" class="btn btn--secondary">
                                        <i class="tio-search"></i>
                                    </button>
                                </div>
                                <!-- End Search -->
                            </form>
    <div class="table-responsive datatable-custom">     
                        <table id="columnSearchDatatable"
                               class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                               data-hs-datatables-options='{
                                 "order": [],
                                 "orderCellsTop": true,
                                 "paging":false
                               }'>
                            <thead class="thead-light">
                            <tr>
                            <th class="text-center">{{translate('messages.sl')}}</th>
                                <th class="text-center">{{translate('messages.Sub Query')}} {{translate('messages.id')}}</th>
                                <th class="pl-5">{{translate('messages. Name')}}</th>
                                 <th class="pl-5">{{translate('messages.role')}}</th>
                                <th class="pl-5">{{translate('messages.Description')}}</th>
                                <th >{{translate('messages.status')}}</th>
                                <th class="w-40px">{{translate('messages.action')}}</th>
                            </tr>
                            </thead>

                            <tbody id="set-rows">
                            @foreach($sub_query_1 as $key=>$sub_query)
                                <tr>
                       <td>{{$key+1}}</td>
                                    <td class="text-center">
                                        <span class="move-left">
                                            {{$sub_query['id']}}
                                        </span>
                                    </td>
                                    <td class="pl-5">
                                        <span class="d-block font-size-sm text-body">
                                            {{$sub_query['name']}}
                                        </span>
                                    </td>
                                     <td class="pl-5">
                                    <span class="d-block font-size-sm text-body">
                                        {{$sub_query['role']}}
                                    </span>
                                </td>
                                    <td class="pl-5">
                                        <span class="d-block font-size-sm text-body">
                                            {{$sub_query['description']}}
                                        </span>
                                    </td>
                                    <td>
                                    <td>
                                    <label class="toggle-switch toggle-switch-sm" for="stocksCheckbox{{$sub_query->id}}">
                                        <input type="checkbox" onclick="status_form_alert('status-{{$sub_query['id']}}','{{ translate('Are You Sure') }}', event)" class="toggle-switch-input" id="stocksCheckbox{{$sub_query->id}}" {{$sub_query->status?'checked':''}}>
                                        <span class="toggle-switch-label">
                                            <span class="toggle-switch-indicator"></span>
                                        </span>
                                    </label>
                                    <form action="{{route('admin.query.status',[$sub_query['id'],$sub_query->status?0:1])}}" method="get" id="status-{{$sub_query['id']}}">
                                    </form>
                                </td>
                                    <td>
                                    <div class="btn--container justify-content-center">
                                            <a class="btn btn-sm btn--primary btn-outline-primary action-btn"
                                                href="{{route('admin.query.sub_edit',[$sub_query['id']])}}" title="{{translate('messages.edit')}} {{translate('messages.sub Query')}}"><i class="tio-edit"></i>
                                            </a>
                                            <a class="btn btn-sm btn--warning btn-outline-warning action-btn" href="javascript:"
                                            onclick="form_alert('query-{{$sub_query['id']}}','Want to delete this sub query ?')" title="{{translate('messages.delete')}} {{translate('messages.sub_query')}}"><i class="tio-delete-outlined"></i>
                                            </a>
                                            <form action="{{route('admin.query.delete',[$sub_query['id']])}}" method="post" id="query-{{$sub_query['id']}}">
                                                @csrf @method('delete')
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            </table>
                    </div>
                </div>
            </div>
            <!-- End Table -->
        </div>
    </div>          
            @endsection

            @push('script_2')
            <script>
        function status_form_alert(id, message, e) {
            e.preventDefault();
            Swal.fire({
                title: "{{translate('messages.are_you_sure')}}",
                text: message,
                type: 'warning',
                showCloseButton: true,
                showCancelButton: true,
                cancelButtonColor: 'var(--secondary-clr)',
                confirmButtonColor: 'var(--primary-clr)',
                cancelButtonText: 'Cancel',
                confirmButtonText: 'Yes',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $('#'+id).submit()
                }
            })
        }
</script>
<script>
        $(document).on('ready', function () {       
            var datatable = $.HSCore.components.HSDatatables.init($('#columnSearchDatatable'));
            $('#column1_search').on('keyup', function () {
                datatable
                    .columns(1)
                    .search(this.value)
                    .draw();
            });
            $('#column3_search').on('change', function () {
                datatable
                    .columns(2)
                    .search(this.value)
                    .draw();
            });
            $('.js-select2-custom').each(function () {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });
        });
    </script>
            <script>
                
        $('#search-form').on('submit', function () {
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{route('admin.query.search')}}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    $('#set-rows').html(data.view);
                    $('#itemCount').html(data.total);
                    $('.page-area').hide();
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        });
    </script>
        <script>
        $('#reset_btn').click(function(){
            $('#choice_query').val(null);
            $('#name').val(null);
            $('#description').val(null);
        })
    </script>
@endpush
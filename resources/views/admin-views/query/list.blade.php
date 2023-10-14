@extends('layouts.admin.app')
@section('title',translate('Add New Top Query'))

@section('content')

<div class="content container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-sm mb-2 mb-sm-0">
                <h1 class="page-header-title">
                    <i class="tio-free-transform"></i>{{translate('messages.Top Query')}} {{translate('messages.setup')}}
                </h1>
            </div>
            <div class="col-sm-auto">
                    <a class="btn btn--primary" href="{{route('admin.query.add_sub')}}">
                        <i class="tio-add"></i> {{translate('messages.add')}} {{translate('messages.new')}} {{translate('messages.Sub Query')}}
                    </a>
                </div>
        </div>
    </div>
    <div class="row gx-2 gx-lg-3">
        <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
            <form action="{{route('admin.query.store')}}" method="post" class="shadow--card">
                @csrf
                <div class="row justify-content-between">
                    <div class="col-md-6 col-xl-7 query-setup">
                        <div class="pl-xl-5 pl-xxl-0">
                            <div class="form-group mb-3">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('messages.query')}} {{translate('messages.name')}}</label>
                                <input id="name" type="text" name="name" class="form-control h--45px" placeholder="{{ translate('messages.Top :') }} {{ translate('Query 4') }}" value="{{old('name')}}" required>
                            </div>
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
                                        {{ translate('messages.query Role') }}</option>                               
                                      <option value="rider">Rider</option> 
                                      <option value="user">User</option>                               
                                      <option value="all">All</option>                                            
                                   
                                </select>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group mb-6">
                                        <label class="input-label">
                                            {{translate('messages.Description')}}
                                        </label>
                                        <input id="description" name="description" type="text" class="form-control h--45px" placeholder="{{ translate('messages.Enter Description :') }} " required>
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
        <div class="col-sm-12 col-lg-12 mb-3 my-lg-2">
            <div class="card">
                <div class="card-header py-2 flex-wrap border-0 align-items-center">
                    <div class="search--button-wrapper">
                        <h5 class="card-title">{{translate('messages.query')}} {{translate('messages.list')}}<span class="badge badge-soft-dark ml-2" id="itemCount">{{$query->count()}}</span></h5>
                        <form action="javascript:" id="search-form" class="my-2 mr-sm-2 mr-xl-4 ml-sm-auto flex-grow-1 flex-grow-sm-0">
                            <!-- Search -->
                            @csrf
                            <div class="input--group input-group input-group-merge input-group-flush">
                                <input id="datatableSearch_" type="search" name="search" class="form-control" placeholder="{{ translate('messages.Search_by_name') }}" aria-label="{{translate('messages.search')}}" required>
                                <button type="submit" class="btn btn--secondary">
                                    <i class="tio-search"></i>
                                </button>
                            </div>
                            <!-- End Search -->
                        </form>
                        <!-- Unfold -->
                    </div>
                </div>
                <div class="table-responsive datatable-custom">
                    <table id="columnSearchDatatable" class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table" data-hs-datatables-options='{
                                 "order": [],
                                 "orderCellsTop": true,
                                 "paging":false
                               }'>
                        <thead class="thead-light">
                            <tr>
                                <th class="text-center">{{translate('messages.sl')}}</th>
                                <th class="text-center">{{translate('messages.query')}} {{translate('messages.id')}}</th>
                                <th class="pl-5">{{translate('messages.name')}}</th>
                                 <th class="pl-5">{{translate('messages.role')}}</th>
                                <th class="pl-5">{{translate('messages.Description')}}</th>
                                <th>{{translate('messages.status')}}</th>
                                <th class="w-40px">{{translate('messages.action')}}</th>
                            </tr>
                        </thead>

                        <tbody id="set-rows">
                            @foreach($query as $key=>$query)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td class="text-center">
                                    <span class="move-left">
                                        {{$query->id}}
                                    </span>
                                </td>
                                <td class="pl-5">
                                    <span class="d-block font-size-sm text-body">
                                        {{$query['name']}}
                                    </span>
                                </td>
                                 <td class="pl-5">
                                    <span class="d-block font-size-sm text-body">
                                        {{$query['role']}}
                                    </span>
                                </td>
                                <td class="pl-5">
                                    <span class="d-block font-size-sm text-body">
                                        {{$query['description']}}
                                    </span>
                                </td>
                                <td>
                                    <label class="toggle-switch toggle-switch-sm" for="stocksCheckbox{{$query->id}}">
                                        <input type="checkbox" onclick="status_form_alert('status-{{$query['id']}}','{{ translate('Are You Sure') }}', event)" class="toggle-switch-input" id="stocksCheckbox{{$query->id}}" {{$query->status?'checked':''}}>
                                        <span class="toggle-switch-label">
                                            <span class="toggle-switch-indicator"></span>
                                        </span>
                                    </label>
                                    <form action="{{route('admin.query.status',[$query['id'],$query->status?0:1])}}" method="get" id="status-{{$query['id']}}">
                                    </form>
                                </td>
                                <td>
                                    <div class="btn--container justify-content-center">
                                        <a class="btn btn-sm btn--primary btn-outline-primary action-btn" href="{{route('admin.query.edit',[$query['id']])}}" title="{{translate('messages.edit')}} {{translate('messages.query')}}"><i class="tio-edit"></i>
                                        </a>
                                        <a class="btn btn-sm btn--warning btn-outline-warning action-btn" href="javascript:" onclick="form_alert('query-{{$query['id']}}','Want to delete this query ?')" title="{{translate('messages.delete')}} {{translate('messages.query')}}"><i class="tio-delete-outlined"></i>
                                        </a>
                                        <form action="{{route('admin.query.delete',[$query['id']])}}" method="post" id="query-{{$query['id']}}">
                                            @csrf @method('delete')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="page-area px-4 pb-3">
                        <div class="d-flex align-items-center justify-content-end">

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
    function status_form_alert(id, message, e) {
        e.preventDefault();
        Swal.fire({
            title: "{{translate('messages.')}}",
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
                $('#' + id).submit()
            }
        })
    }
</script>
<script>
    $(document).on('ready', function() {
        var datatable = $.HSCore.components.HSDatatables.init($('#columnSearchDatatable'));
        $('#column1_search').on('keyup', function() {
            datatable
                .columns(1)
                .search(this.value)
                .draw();
        });
        $('#column3_search').on('change', function() {
            datatable
                .columns(2)
                .search(this.value)
                .draw();
        });
        $('.js-select2-custom').each(function() {
            var select2 = $.HSCore.components.HSSelect2.init($(this));
        });
    });
</script>
<script>
    $('#search-form').on('submit', function() {
        var formData = new FormData(this);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.post({
            url : '{{route('admin.query.search')}}',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function() {
                $('#loading').show();
            },
            success: function(data) {
                $('#set-rows').html(data.view);
                $('#itemCount').html(data.total);
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
        $('#name').val(null);
        $('#description').val(null);
    })
</script>
@endpush
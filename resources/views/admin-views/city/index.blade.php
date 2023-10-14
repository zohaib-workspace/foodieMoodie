@extends('layouts.admin.app')
@section('title',translate('Add new city'))

@section('content')
<div class="content container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-sm mb-2 mb-sm-0">
                <h1 class="page-header-title">
                    <i class="tio-free-transform"></i>{{translate('messages.city')}} {{translate('messages.setup')}}
                </h1>
            </div>
        </div>
    </div>
    <div class="row gx-2 gx-lg-3">
        <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
            <form action="{{route('admin.city.store')}}" method="post" class="shadow--card">
                @csrf
                <div class="row justify-content-between">
                    <div class="col-md-6 col-xl-7 city-setup">
                        <div class="pl-xl-5 pl-xxl-0">
                        <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group mb-3">
                                    <label class="input-label" for="choice_business">{{ translate('messages.Country Name') }}
                                        <span data-toggle="tooltip" data-placement="right"
                                        class="input-label-secondary"><img
                                            src="{{ asset('/public/assets/admin/img/info-circle.svg') }}"
                                            alt="{{ translate('messages.restaurant_lat_lng_warning') }}"></span>
                                        </label>                               
                                        <select name="country_id" id="choice_country" required class="form-control h--45px js-select2-custom"
                                    data-placeholder="{{ translate('messages.select') }} {{ translate('messages.Country Name') }}"> 
                                    <option value="" selected disabled>{{ translate('messages.select') }}
                                        {{ translate('messages.Country Name') }}</option>                               
                                    @foreach (\App\Models\Country::all() as $c_name)                                     
                                            <option value="{{ $c_name->id }}">{{ $c_name->name }}</option>                               
                                    @endforeach
                                </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group mb-3">
                                        <label class="input-label">
                                            {{translate('messages.Provence')}}
                                        </label>
                                        <input id="provence" name="provence" type="text" class="form-control h--45px" placeholder="{{ translate('messages.Ex : Punjab') }} " required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group mb-3">
                                        <label class="input-label">
                                            {{translate('messages.City Name')}}
                                        </label>
                                        <input id="name" name="name" type="text" class="form-control h--45px" placeholder="{{ translate('messages.Ex :') }} Lahore" required>
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
                            <h5 class="card-title">{{translate('messages.city')}} {{translate('messages.list')}}<span class="badge badge-soft-dark ml-2" id="itemCount">{{$city->count()}}</span></h5>
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
                                <th class="text-center">{{translate('messages.city')}} {{translate('messages.id')}}</th>
                                <th class="pl-5">{{translate('messages.Country')}}</th>
                                <th class="pl-5">{{translate('messages.City Name')}}</th>
                                <th class="pl-5">{{translate('messages.provence')}}</th>
                                <th >{{translate('messages.status')}}</th>
                                <th class="w-40px">{{translate('messages.action')}}</th>
                            </tr>
                            </thead>

                            <tbody id="set-rows">
                            @foreach($city as $key=>$city)
                                <tr>
                                <td>{{$key+1}}</td>

                                    <td class="text-center">
                                        <span class="move-left">
                                            {{$city->id}}
                                        </span>
                                    </td>
                                    <td class="pl-5">
                                        <span class="d-block font-size-sm text-body">
                                        {{$city['country']['name']}}
                                        </span>
                                    </td>
                                    <td class="pl-5">
                                        <span class="d-block font-size-sm text-body">
                                            {{$city['name']}}
                                        </span>
                                    </td>
                                    <td class="pl-5">
                                        <span class="d-block font-size-sm text-body">
                                            {{$city['provence']}}
                                        </span>
                                    </td>
                                    <td>
                                    <label class="toggle-switch toggle-switch-sm" for="stocksCheckbox{{$city->id}}">
                                            <input type="checkbox" onclick="status_form_alert('status-{{$city['id']}}', '{{ translate('Are You Sure') }}', event)" class="toggle-switch-input" id="stocksCheckbox{{$city->id}}" {{$city->status?'checked':''}}>
                                            <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                        <form action="{{route('admin.city.status',[$city['id'],$city->status?0:1])}}" method="get" id="status-{{$city['id']}}">
                                        </form>
                                    </td>
                                    <td>
                                    <div class="btn--container justify-content-center">
                                            <a class="btn btn-sm btn--primary btn-outline-primary action-btn"
                                                href="{{route('admin.city.edit',[$city['id']])}}" title="{{translate('messages.edit')}} {{translate('messages.city')}}"><i class="tio-edit"></i>
                                            </a>
                                            <a class="btn btn-sm btn--warning btn-outline-warning action-btn" href="javascript:"
                                            onclick="form_alert('city-{{$city['id']}}','Want to delete this city ?')" title="{{translate('messages.delete')}} {{translate('messages.city')}}"><i class="tio-delete-outlined"></i>
                                            </a>
                                            <form action="{{route('admin.city.delete',[$city['id']])}}" method="post" id="city-{{$city['id']}}">
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
                url: '{{route('admin.city.search')}}',
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
            $('#choice_country').val(null);
            $('#provence').val(null);
            $('#name').val(null);
        })
    </script>
@endpush
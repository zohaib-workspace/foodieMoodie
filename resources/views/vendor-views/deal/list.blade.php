@extends('layouts.vendor.app')

@section('title',translate('messages.Deals List'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="mb-2 mb-sm-0">
                <h1 class="page-header-title"><i class="tio-filter-list"></i> {{translate('messages.Deals')}} {{translate('messages.list')}}<span class="badge badge-soft-dark ml-2" id="itemCount">{{$deals->total()}}</span></h1>
            </div>
            <div class="my-2">
                <div class="row g-2 align-items-center justify-content-end">
                    @if ($toggle_veg_non_veg)
                    <!-- Veg/NonVeg filter -->
                    <div class="col-auto">
                        <!--<select name="category_id" onchange="set_filter('{{url()->full()}}',this.value, 'type')" data-placeholder="{{translate('messages.all')}}" class="form-control w-100 max-sm-12">-->
                        <!--    <option value="all" {{$type=='all'?'selected':''}}>{{translate('messages.all')}}</option>-->
                        <!--    <option value="veg" {{$type=='veg'?'selected':''}}>{{translate('messages.veg')}}</option>-->
                        <!--    <option value="non_veg" {{$type=='non_veg'?'selected':''}}>{{translate('messages.non_veg')}}</option>-->
                        <!--</select>-->
                    </div>
                    <!-- End Veg/NonVeg filter -->
                    @endif
                    <div class="col-auto">
                        <a href="{{route('vendor.deal.add-new')}}" class="btn max-sm-12 btn--primary w-100"><i
                                    class="tio-add-circle"></i> {{translate('messages.add')}} {{translate('messages.new')}} {{translate('messages.Deal')}}</a>
                    </div>

                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <!-- Card -->
        <div class="card">
            <!-- Header -->
            <div class="card-header border-0">
                <div class="search--button-wrapper search--button-area justify-content-end">
                    <form id="search-form">
                        @csrf
                        <!-- Search -->
                        <div class="input-group input--group">
                            <input id="datatableSearch" type="search" name="search" class="form-control" placeholder="{{ translate('messages.Ex : Search Deal Name') }}">
                            <button type="submit" class="btn btn--secondary">
                                <i class="tio-search"></i>
                            </button>
                        </div>
                    <!-- End Search -->
                    </form>
                    

                    <!-- Unfold -->
                    <div class="hs-unfold">
                        <a class="js-hs-unfold-invoker btn btn-white" href="javascript:;"
                            data-hs-unfold-options='{
                            "target": "#showHideDropdown",
                            "type": "css-animation"
                            }'>
                            <i class="tio-table mr-1"></i> {{ translate('Columns') }}
                        </a>

                        <div id="showHideDropdown" class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-right dropdown-card">
                            <div class="card card-sm">
                                <div class="card-body">
                                    {{--<div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="mr-2">#</span>
                                        <!-- Checkbox Switch -->
                                        <label class="toggle-switch toggle-switch-sm" for="toggleColumn_index">
                                            <input type="checkbox" class="toggle-switch-input" id="toggleColumn_index" checked>
                                            <span class="toggle-switch-label">
                                            <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                    <!-- End Checkbox Switch -->
                                    </div>--}}
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="mr-2">{{ translate('Name') }}</span>
                                        <!-- Checkbox Switch -->
                                        <label class="toggle-switch toggle-switch-sm" for="toggleColumn_name">
                                            <input type="checkbox" class="toggle-switch-input" id="toggleColumn_name" checked>
                                            <span class="toggle-switch-label">
                                            <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                    <!-- End Checkbox Switch -->
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="mr-2">{{ translate('Items Count') }}</span>

                                        <!-- Checkbox Switch -->
                                        <label class="toggle-switch toggle-switch-sm" for="toggleColumn_type">
                                            <input type="checkbox" class="toggle-switch-input" id="toggleColumn_type" checked>
                                            <span class="toggle-switch-label">
                                            <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                    <!-- End Checkbox Switch -->
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="mr-2">{{ translate('Status') }}</span>

                                        <!-- Checkbox Switch -->
                                        <label class="toggle-switch toggle-switch-sm" for="toggleColumn_status">
                                            <input type="checkbox" class="toggle-switch-input" id="toggleColumn_status" checked>
                                            <span class="toggle-switch-label">
                                            <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                        <!-- End Checkbox Switch -->
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="mr-2">{{ translate('Price') }}</span>

                                        <!-- Checkbox Switch -->
                                        <label class="toggle-switch toggle-switch-sm" for="toggleColumn_price">
                                            <input type="checkbox" class="toggle-switch-input" id="toggleColumn_price" checked>
                                            <span class="toggle-switch-label">
                                            <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                        <!-- End Checkbox Switch -->
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="mr-2">{{ translate('Action') }}</span>

                                        <!-- Checkbox Switch -->
                                        <label class="toggle-switch toggle-switch-sm" for="toggleColumn_action">
                                            <input type="checkbox" class="toggle-switch-input" id="toggleColumn_action" checked>
                                            <span class="toggle-switch-label">
                                            <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                        <!-- End Checkbox Switch -->
                                    </div>
                            </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Unfold -->
                </div>
            </div>
            <!-- End Header -->

            <!-- Table -->
            <div class="table-responsive datatable-custom">
                <table id="datatable" class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                    data-hs-datatables-options='{
                        "columnDefs": [{
                            "targets": [],
                            "width": "5%",
                            "orderable": false
                        }],
                        "order": [],
                        "info": {
                        "totalQty": "#datatableWithPaginationInfoTotalQty"
                        },

                        "entries": "#datatableEntries",
                        "isResponsive": false,
                        "isShowPaging": false,
                            "paging":false
                    }'>
                    <thead class="thead-light">
                    <tr>
                        <th>{{ translate('messages.sl') }}</th>
                        <th class="w-20p">{{translate('messages.name')}}</th>
                        <th class="w-20p">{{translate('Items Count')}}</th>
                        <th class="text-center">{{translate('messages.price')}}</th>
                        <th class="text-center">{{translate('messages.status')}}</th>
                        <th class="text-center">{{translate('messages.action')}}</th>
                    </tr>
                    </thead>

                    <tbody id="set-rows">
                    @foreach($deals as $key=>$deal)
                        <tr>
                            <td>{{$key+$deals->firstItem()}}</td>
                            <td>
                                <a class="media align-items-center" href="{{route('vendor.deal.view',[$deal['id']])}}">
                                    <img class="avatar avatar-lg mr-3" src="{{asset('storage/app/public/product')}}/{{$deal['image']}}"
                                            onerror="this.src='{{asset('/public/assets/admin/img/100x100/food-default-image.png')}}'" alt="{{$deal->title}} image">
                                    <div class="media-body">
                                        <h5 class="text-hover-primary mb-0">{{Str::limit($deal['title'],20,'...')}}</h5>
                                    </div>
                                </a>
                            </td>
                            <td>
                            {{$deal->number_of_items}}
                            </td>
                            <td>
                                <div class="text-right mx-auto mw-36px">
                                <!-- Static Symbol -->
            {{ \App\CentralLogics\Helpers::currency_symbol() }}
            <!-- Static Symbol -->
                {{($deal['price'])}}
                                </div>
                            </td>
                            <td>
                                <div class="d-flex">
                                    <div class="mx-auto">
                                        <label class="toggle-switch toggle-switch-sm mr-2" for="stocksCheckbox{{$deal->id}}">
                                            <input type="checkbox" onclick="location.href='{{route('vendor.deal.status',[$deal['id'],$deal->status=='Active'?0:1])}}'"class="toggle-switch-input" id="stocksCheckbox{{$deal->id}}" {{$deal->status=='Active'?'checked':''}}>
                                            <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="btn--container justify-content-center">
                                    <a class="btn action-btn btn--primary btn-outline-primary"
                                        href="{{route('vendor.deal.edit',[$deal['id']])}}" title="{{translate('messages.edit')}} {{translate('messages.deal')}}"><i class="tio-edit"></i>
                                    </a>
                                    <a class="btn action-btn btn--danger btn-outline-danger" href="javascript:"
                                        onclick="form_alert('deal-{{$deal['id']}}','{{ translate('Want to delete this deal ?') }}')" title="{{translate('messages.delete')}} {{translate('messages.deal')}}"><i class="tio-delete-outlined"></i>
                                    </a>
                                    <form action="{{route('vendor.deal.delete',[$deal['id']])}}"
                                            method="post" id="deal-{{$deal['id']}}">
                                        @csrf @method('delete')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                @if(count($deals) === 0)
                <div class="empty--data">
                    <img src="{{asset('/public/assets/admin/img/empty.png')}}" alt="public">
                    <h5>
                        {{translate('no_data_found')}}
                    </h5>
                </div>
                @endif
                <div class="page-area">
                    <table>
                        <tfoot class="border-top">
                        {!! $deals->links() !!}
                        </tfoot>
                    </table>
                </div>
            </div>
            <!-- End Table -->
        </div>
        <!-- End Card -->
    </div>

@endsection

@push('script_2')
    <script>
        $(document).on('ready', function () {
            // INITIALIZATION OF DATATABLES
            // =======================================================
            var datatable = $.HSCore.components.HSDatatables.init($('#datatable'), {
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
                '<img class="mb-3 w-7rem" src="{{asset('public/assets/admin/svg/illustrations/sorry.svg')}}" alt="Image Description">' +
                '<p class="mb-0">{{ translate('No data to show') }}</p>' +
                '</div>'
          }
        });

        $('#datatableSearch').on('mouseup', function (e) {
          var $input = $(this),
            oldValue = $input.val();

          if (oldValue == "") return;

          setTimeout(function(){
            var newValue = $input.val();

            if (newValue == ""){
              // Gotcha
              datatable.search('').draw();
            }
          }, 1);
        });

        $('#toggleColumn_index').change(function (e) {
          datatable.columns(0).visible(e.target.checked)
        })
        $('#toggleColumn_name').change(function (e) {
          datatable.columns(1).visible(e.target.checked)
        })

        $('#toggleColumn_type').change(function (e) {
          datatable.columns(2).visible(e.target.checked)
        })

        $('#toggleColumn_status').change(function (e) {
          datatable.columns(4).visible(e.target.checked)
        })
        $('#toggleColumn_price').change(function (e) {
          datatable.columns(3).visible(e.target.checked)
        })
        $('#toggleColumn_action').change(function (e) {
          datatable.columns(5).visible(e.target.checked)
        })
            // INITIALIZATION OF SELECT2
            // =======================================================
            $('.js-select2-custom').each(function () {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });
        });

        $('#category').select2({
            ajax: {
                url: '{{route("vendor.category.get-all")}}',
                data: function (params) {
                    return {
                        q: params.term, // search term
                        all:true,
                        page: params.page
                    };
                },
                processResults: function (data) {
                    return {
                    results: data
                    };
                },
                __port: function (params, success, failure) {
                    var $request = $.ajax(params);

                    $request.then(success);
                    $request.fail(failure);

                    return $request;
                }
            }
        });
    </script>

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
                url: '{{route('vendor.deal.search')}}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    $('#set-rows').html(data.view);
                    $('.page-area').hide();
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        });
    </script>
@endpush

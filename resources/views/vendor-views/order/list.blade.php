@extends('layouts.vendor.app')

@section('title',translate('messages.Order List'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<?php
// dd(str_replace('_',' ',$status))
?>
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header pt-0 pb-2">
            <div class="d-flex flex-wrap justify-content-between">
                <h2 class="page-header-title align-items-center text-capitalize py-2 mr-2">
                    <div class="card-header-icon d-inline-flex mr-2 img">
                        @if(str_replace('_',' ',$status) == 'All')
                            <img class="mw-24px" src="{{asset('/public/assets/admin/img/resturant-panel/page-title/order.png')}}" alt="public">
                        @elseif(str_replace('_',' ',$status) == 'Pending')
                            <img class="mw-24px" src="{{asset('/public/assets/admin/img/resturant-panel/page-title/pending.png')}}" alt="public">
                        @elseif(str_replace('_',' ',$status) == 'Confirmed')
                            <img class="mw-24px" src="{{asset('/public/assets/admin/img/resturant-panel/page-title/confirm.png')}}" alt="public">
                        @elseif(str_replace('_',' ',$status) == 'Cooking')
                            <img class="mw-24px" src="{{asset('/public/assets/admin/img/resturant-panel/page-title/cooking.png')}}" alt="public">
                        @elseif(str_replace('_',' ',$status) == 'Ready for delivery')
                            <img class="mw-24px" src="{{asset('/public/assets/admin/img/resturant-panel/page-title/ready.png')}}" alt="public">
                        @elseif(str_replace('_',' ',$status) == 'Food on the way')
                            <img class="mw-24px" src="{{asset('/public/assets/admin/img/resturant-panel/page-title/ready.png')}}" alt="public">
                        @elseif(str_replace('_',' ',$status) == 'Delivered')
                            <img class="mw-24px" src="{{asset('/public/assets/admin/img/resturant-panel/page-title/ready.png')}}" alt="public">
                        @elseif(str_replace('_',' ',$status) == 'Refunded')
                            <img class="mw-24px" src="{{asset('/public/assets/admin/img/resturant-panel/page-title/order.png')}}" alt="public">
                        @elseif(str_replace('_',' ',$status) == 'Scheduled')
                            <img class="mw-24px" src="{{asset('/public/assets/admin/img/resturant-panel/page-title/order.png')}}" alt="public">
                        @endif
                    </div>
                    <span>
                        {{str_replace('_',' ',$status)}} {{translate('messages.orders')}} <span class="badge badge-soft-dark ml-2">{{$orders->total()}}</span>
                    </span>
                </h2>
            </div>
        </div>
        <!-- End Page Header -->


        <!-- End Page Header -->

        <!-- Card -->
        <div class="card">
            <!-- Header -->
            <div class="card-header py-2">
                <div class="search--button-wrapper justify-content-end max-sm-flex-100">
                    <form action="javascript:" id="search-form">
                        @csrf
                        <!-- Search -->
                        <div class="input-group input--group">
                            <input id="datatableSearch_" type="search" name="search" class="form-control"
                                    placeholder="{{ translate('Ex : Search by Order Id') }}" aria-label="{{translate('messages.search')}}" required>
                            <button type="submit" class="btn btn--secondary">
                                <i class="tio-search"></i>
                            </button>
                        </div>
                        <!-- End Search -->
                    </form>

                    <div class="d-sm-flex justify-content-sm-end align-items-sm-center m-0">

                        <!-- Unfold -->
                        <div class="hs-unfold mr-2">
                            <a class="js-hs-unfold-invoker btn btn-sm btn-white dropdown-toggle" href="javascript:;"
                                data-hs-unfold-options='{
                                    "target": "#usersExportDropdown",
                                    "type": "css-animation"
                                }'>
                                <i class="tio-download-to mr-1"></i> {{translate('messages.export')}}
                            </a>

                            <div id="usersExportDropdown"
                                    class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-sm-right">
                                <span class="dropdown-header">{{translate('messages.options')}}</span>
                                <a id="export-copy" class="dropdown-item" href="javascript:;">
                                    <img class="avatar avatar-xss avatar-4by3 mr-2"
                                            src="{{asset('public/assets/admin')}}/svg/illustrations/copy.svg"
                                            alt="Image Description">
                                    {{translate('messages.copy')}}
                                </a>
                                <a id="export-print" class="dropdown-item" href="javascript:;">
                                    <img class="avatar avatar-xss avatar-4by3 mr-2"
                                            src="{{asset('public/assets/admin')}}/svg/illustrations/print.svg"
                                            alt="Image Description">
                                    {{translate('messages.print')}}
                                </a>
                                <div class="dropdown-divider"></div>
                                <span
                                    class="dropdown-header">{{translate('messages.download')}} {{translate('messages.options')}}</span>
                                <a id="export-excel" class="dropdown-item" href="javascript:;">
                                    <img class="avatar avatar-xss avatar-4by3 mr-2"
                                            src="{{asset('public/assets/admin')}}/svg/components/excel.svg"
                                            alt="Image Description">
                                    {{translate('messages.excel')}}
                                </a>
                                <a id="export-csv" class="dropdown-item" href="javascript:;">
                                    <img class="avatar avatar-xss avatar-4by3 mr-2"
                                            src="{{asset('public/assets/admin')}}/svg/components/placeholder-csv-format.svg"
                                            alt="Image Description">
                                    .{{translate('messages.csv')}}
                                </a>
                                <a id="export-pdf" class="dropdown-item" href="javascript:;">
                                    <img class="avatar avatar-xss avatar-4by3 mr-2"
                                            src="{{asset('public/assets/admin')}}/svg/components/pdf.svg"
                                            alt="Image Description">
                                    {{translate('messages.pdf')}}
                                </a>
                            </div>
                        </div>
                        <!-- End Unfold -->

                        <!-- Unfold -->
                        <div class="hs-unfold">
                            <a class="js-hs-unfold-invoker btn btn-sm btn-white" href="javascript:;"
                                data-hs-unfold-options='{
                                    "target": "#showHideDropdown",
                                    "type": "css-animation"
                                }'>
                                <i class="tio-table mr-1"></i> {{translate('messages.column')}} <span
                                    class="badge badge-soft-dark rounded-circle ml-1"></span>
                            </a>

                            <div id="showHideDropdown"
                                    class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-right dropdown-card">
                                <div class="card card-sm">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <span class="mr-2">
                                                {{translate('messages.Order ID')}}

                                            </span>

                                            <!-- Checkbox Switch -->
                                            <label class="toggle-switch toggle-switch-sm" for="toggleColumn_order">
                                                <input type="checkbox" class="toggle-switch-input"
                                                        id="toggleColumn_order" checked>
                                                <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                                </span>
                                            </label>
                                            <!-- End Checkbox Switch -->
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <span class="mr-2">{{translate('messages.date')}}</span>

                                            <!-- Checkbox Switch -->
                                            <label class="toggle-switch toggle-switch-sm" for="toggleColumn_date">
                                                <input type="checkbox" class="toggle-switch-input"
                                                        id="toggleColumn_date" checked>
                                                <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                                </span>
                                            </label>
                                            <!-- End Checkbox Switch -->
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <span class="mr-2">{{translate('messages.customer')}}</span>

                                            <!-- Checkbox Switch -->
                                            <label class="toggle-switch toggle-switch-sm"
                                                    for="toggleColumn_customer">
                                                <input type="checkbox" class="toggle-switch-input"
                                                        id="toggleColumn_customer" checked>
                                                <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                                </span>
                                            </label>
                                            <!-- End Checkbox Switch -->
                                        </div>


                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <span class="mr-2">{{translate('messages.total')}}</span>

                                            <!-- Checkbox Switch -->
                                            <label class="toggle-switch toggle-switch-sm" for="toggleColumn_total">
                                                <input type="checkbox" class="toggle-switch-input"
                                                        id="toggleColumn_total" checked>
                                                <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                                </span>
                                            </label>
                                            <!-- End Checkbox Switch -->
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <span class="mr-2">{{translate('messages.order')}} {{translate('messages.status')}}</span>

                                            <!-- Checkbox Switch -->
                                            <label class="toggle-switch toggle-switch-sm" for="toggleColumn_order_status">
                                                <input type="checkbox" class="toggle-switch-input"
                                                        id="toggleColumn_order_status" checked>
                                                <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                                </span>
                                            </label>
                                            <!-- End Checkbox Switch -->
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="mr-2">{{translate('messages.actions')}}</span>

                                            <!-- Checkbox Switch -->
                                            <label class="toggle-switch toggle-switch-sm"
                                                    for="toggleColumn_actions">
                                                <input type="checkbox" class="toggle-switch-input"
                                                        id="toggleColumn_actions" checked>
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
            </div>
            <!-- End Header -->

            <!-- Table -->
            <div class="table-responsive datatable-custom">
                <table id="datatable"
                       class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                       data-hs-datatables-options='{
                                 "order": [],
                                 "orderCellsTop": true,
                                 "paging":false
                               }'>
                    <thead class="thead-light">
                    <tr>
                        <th class="w-60px">
                            {{ translate('messages.sl') }}
                        </th>
                        <th class="w-90px table-column-pl-0">{{translate('messages.Order ID')}}</th>
                        <th class="w-140px">{{translate('messages.order')}} {{translate('messages.date')}}</th>
                        <th class="w-140px">{{translate('messages.customer_information')}}</th>
                        <th class="w-100px">{{translate('messages.total')}} {{translate('messages.amount')}}</th>
                        <th class="w-100px text-center">{{translate('messages.order')}} {{translate('messages.status')}}</th>
                        <th class="w-100px text-center">{{translate('messages.actions')}}</th>
                    </tr>
                    </thead>

                    <tbody id="set-rows">
                    @foreach($orders as $key=>$order)
                        <tr class="status-{{$order['order_status']}} class-all">
                            <td class="">
                                
                                {{$key+$orders->firstItem()}}
                            </td>
                            <td class="table-column-pl-0">
                                <a href="{{route('vendor.order.details',['id'=>$order['id']])}}" class="text-hover">{{$order['id']}}</a>
                            </td>
                            <td>
                                <span class="d-block">
                                    {{date('d M Y',strtotime($order['created_at']))}}
                                </span>
                                <span class="d-block text-uppercase">
                                    {{date(config('timeformat'),strtotime($order['created_at']))}}
                                </span>
                            </td>
                            <td>
                                @if($order->customer)
                                    <a class="text-body text-capitalize"
                                       href="{{route('vendor.order.details',['id'=>$order['id']])}}">
                                       <span class="d-block font-semibold">
                                            {{$order->customer['f_name'].' '.$order->customer['l_name']}}
                                       </span>
                                       <span class="d-block">
                                            {{$order->customer['phone']}}
                                       </span>
                                    </a>
                                @else
                                    <label
                                        class="badge badge-danger">{{translate('messages.invalid')}} {{translate('messages.customer')}} {{translate('messages.data')}}</label>
                                @endif
                            </td>
                            <td>


                                <div class="text-right mw-85px">
                                    <div>
                                        {{\App\CentralLogics\Helpers::format_currency($order['order_amount'], $order['restaurant_id'])}}
                                    </div>
                                    @if($order->payment_status=='paid')
                                    <strong class="text-success">
                                        {{translate('messages.paid')}}
                                    </strong>
                                    @else
                                        <strong class="text-danger">
                                            {{translate('messages.unpaid')}}
                                        </strong>
                                    @endif
                                </div>

                            </td>
                            <td class="text-capitalize text-center">
                                @if($order['order_status']=='pending')
                                    <span class="badge badge-soft-info mb-1">
                                        {{translate('messages.pending')}}
                                    </span>
                                @elseif($order['order_status']=='confirmed')
                                    <span class="badge badge-soft-info mb-1">
                                      {{translate('messages.confirmed')}}
                                    </span>
                                @elseif($order['order_status']=='processing')
                                    <span class="badge badge-soft-warning mb-1">
                                      {{translate('messages.processing')}}
                                    </span>
                                @elseif($order['order_status']=='picked_up')
                                    <span class="badge badge-soft-warning mb-1">
                                      {{translate('messages.out_for_delivery')}}
                                    </span>
                                @elseif($order['order_status']=='delivered')
                                    <span class="badge badge-soft-success mb-1">
                                      {{translate('messages.delivered')}}
                                    </span>
                                @else
                                    <span class="badge badge-soft-danger mb-1">
                                        {{translate(str_replace('_',' ',$order['order_status']))}}
                                    </span>
                                @endif

                                <div class="text-capitalze opacity-7">
                                    @if($order['order_type']=='take_away')
                                        <span>
                                            {{translate('messages.take_away')}}
                                        </span>
                                        @else
                                        <span>
                                            {{translate('messages.delivery')}}
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                 {{--@php($chatId = $order->delivery_man['id'] . $order['id'] . $order->restaurant['id'])--}}
                                <div class="btn--container justify-content-center">
                                    
                                    <button class="btn action-btn btn--warning btn-outline-warning  orderChatButton" 
                                        {{$order->delivery_man == null ? 'disabled' : ""}}
                                        id="{{ $order->delivery_man != null ? $order['id'] : 'noDeliveryMan' }}"
                                        image="{{$order->delivery_man!= null?  $order->delivery_man['image'] : ''}}"

                                            onclick="openChat(this.id)"><i class="tio-chat"></i></button>
                                    
                                    <a class="btn action-btn btn--warning btn-outline-warning" href="{{route('vendor.order.details',['id'=>$order['id']])}}"><i class="tio-visible-outlined"></i></a>
                                    <a class="btn action-btn btn--primary btn-outline-primary" target="_blank" href="{{route('vendor.order.generate-invoice',[$order['id']])}}"><i class="tio-print"></i></a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            @if(count($orders) === 0)
            <div class="empty--data">
                <img src="{{asset('/public/assets/admin/img/empty.png')}}" alt="public">
                <h5>
                    {{translate('no_data_found')}}
                </h5>
            </div>
            @endif
            <!-- End Table -->
            
            <div id="mySidenav" class="sidenav">
                

                    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
                    <div class="card card-bordered">
                        {{-- <div class="card-header">
                                <h4 class="card-title"><strong>Close</strong></h4>
                                <a class="btn btn-xs btn-secondary" href="#" data-abc="true">Let's Chat
                                    App</a>
                                </div> --}}
                        <div class="ps-container ps-theme-default ps-active-y" id="chat-content"
                            style="overflow-y: scroll !important; ;">


                            <div class="ps-scrollbar-x-rail" style="left: 0px; bottom: 0px;">
                                <div class="ps-scrollbar-x" tabindex="0" style="left: 0px; width: 0px;">
                                </div>
                            </div>
                            <div class="ps-scrollbar-y-rail" style="top: 0px; height: 0px; right: 2px;">
                                <div class="ps-scrollbar-y" tabindex="0" style="top: 0px; height: 2px;">
                                </div>
                            </div>
                        </div>

                        <div class="publisher bt-1 border-light">
                            <div class="avatar avatar-sm avatar-circle">
                                <img class="avatar-img"
                                    onerror="this.src='{{ asset('public/assets/admin/img/160x160/img1.jpg') }}'"
                                    src="{{ asset('storage/app/public/vendor') }}/{{ \App\CentralLogics\Helpers::get_loggedin_user()->image }}"
                                    alt="Image Description">
                                <span class="avatar-status avatar-sm-status avatar-status-success"></span>
                            </div>
                            <input class="publisher-input" type="text" id="message" name="message"
                                placeholder="Write something">


                            <span class="publisher-btn file-group">
                                {{-- <i class="tio-link"></i>
                                    <input type="file"> --}}
                            </span>
                            {{-- <a class="publisher-btn" href="#" data-abc="true"><i class="tio-slightly-smilling"></i></a> --}}
                            <a class="publisher-btn text-info" id="chatSubmit" href="#" data-abc="true"><i
                                    class="tio tio-send"></i></a>
                        </div>

                    </div>

                
            </div>

            <!-- Footer -->
            <div class="card-footer">
                <!-- Pagination -->
                <div class="row justify-content-center justify-content-sm-between align-items-sm-center">
                    {{--<div class="col-sm mb-2 mb-sm-0">
                        <div class="d-flex justify-content-center justify-content-sm-start align-items-center">
                            <span class="mr-2">Showing:</span>

                            <!-- Select -->
                            <select id="datatableEntries" class="js-select2-custom"
                                    data-hs-select2-options='{
                                    "minimumResultsForSearch": "Infinity",
                                    "customClass": "custom-select custom-select-sm custom-select-borderless",
                                    "dropdownAutoWidth": true,
                                    "width": true
                                  }'>
                                <option value="25" selected>25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                                <option value="200">200</option>
                            </select>
                            <!-- End Select -->

                            <span class="text-secondary mr-2">of</span>

                            <!-- Pagination Quantity -->
                            <span id="datatableWithPaginationInfoTotalQty"></span>
                        </div>
                    </div>--}}

                    <div class="col-sm-auto">
                        <div class="d-flex justify-content-center justify-content-sm-end">
                            <!-- Pagination -->
                            {!! $orders->links() !!}
                            {{--<nav id="datatablePagination" aria-label="Activity pagination"></nav>--}}
                        </div>
                    </div>
                </div>
                <!-- End Pagination -->
            </div>
            <!-- End Footer -->
        </div>
        <!-- End Card -->
    </div>
    @php($logedInUser = \App\CentralLogics\Helpers::get_loggedin_user()->id)
@endsection

@push('script_2')
    <script>
        $(document).on('ready', function () {
            // INITIALIZATION OF NAV SCROLLER
            // =======================================================
            $('.js-nav-scroller').each(function () {
                new HsNavScroller($(this)).init()
            });

            // INITIALIZATION OF SELECT2
            // =======================================================
            $('.js-select2-custom').each(function () {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });


            // INITIALIZATION OF DATATABLES
            // =======================================================
            var datatable = $.HSCore.components.HSDatatables.init($('#datatable'), {
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'copy',
                        className: 'd-none'
                    },
                    {
                        extend: 'excel',
                        className: 'd-none'
                    },
                    {
                        extend: 'csv',
                        className: 'd-none'
                    },
                    {
                        extend: 'pdf',
                        className: 'd-none'
                    },
                    {
                        extend: 'print',
                        className: 'd-none'
                    },
                ],
                select: {
                    style: 'multi',
                    selector: 'td:first-child input[type="checkbox"]',
                    classMap: {
                        checkAll: '#datatableCheckAll',
                        counter: '#datatableCounter',
                        counterInfo: '#datatableCounterInfo'
                    }
                },
                language: {
                    zeroRecords: '<div class="text-center p-4">' +
                        '<img class="mb-3 w-7rem" src="{{asset('public/assets/admin')}}/svg/illustrations/sorry.svg" alt="Image Description">' +
                        '<p class="mb-0">{{ translate('No data to show') }}</p>' +
                        '</div>'
                }
            });

            $('#export-copy').click(function () {
                datatable.button('.buttons-copy').trigger()
            });

            $('#export-excel').click(function () {
                datatable.button('.buttons-excel').trigger()
            });

            $('#export-csv').click(function () {
                datatable.button('.buttons-csv').trigger()
            });

            $('#export-pdf').click(function () {
                datatable.button('.buttons-pdf').trigger()
            });

            $('#export-print').click(function () {
                datatable.button('.buttons-print').trigger()
            });

            $('#toggleColumn_order').change(function (e) {
                datatable.columns(1).visible(e.target.checked)
            })

            $('#toggleColumn_date').change(function (e) {
                datatable.columns(2).visible(e.target.checked)
            })

            $('#toggleColumn_customer').change(function (e) {
                datatable.columns(3).visible(e.target.checked)
            })

            $('#toggleColumn_order_status').change(function (e) {
                datatable.columns(5).visible(e.target.checked)
            })


            $('#toggleColumn_total').change(function (e) {
                datatable.columns(4).visible(e.target.checked)
            })

            $('#toggleColumn_actions').change(function (e) {
                datatable.columns(6).visible(e.target.checked)
            })


            // INITIALIZATION OF TAGIFY
            // =======================================================
            $('.js-tagify').each(function () {
                var tagify = $.HSCore.components.HSTagify.init($(this));
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
                url: '{{route('vendor.order.search')}}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    $('#set-rows').html(data.view);
                    $('.card-footer').hide();
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        });
    </script>
    
    
     {{-- firebase script start --}}
    <script type="module">
    
        let id = null;
        let image = null;
        // Import the functions you need from the SDKs you need
        import { initializeApp } from "https://www.gstatic.com/firebasejs/9.18.0/firebase-app.js";
        import { getAnalytics } from "https://www.gstatic.com/firebasejs/9.18.0/firebase-analytics.js";
        import { getDatabase, set, ref, push, child, onValue, onChildAdded } from "https://www.gstatic.com/firebasejs/9.18.0/firebase-database.js";
        // TODO: Add SDKs for Firebase products that you want to use
        // https://firebase.google.com/docs/web/setup#available-libraries
        $(document).on('click',".orderChatButton",function(){
            
            
            // document.getElementById("mySidenav").style.height = 100%;
            id = $(this).attr('id');
            image = $(this).attr('image');
            if(id != 'noDeliveryMan'){
                
                loadChat(id, image);
                var mobileSize = window.matchMedia("(max-width: 767px)").matches;
                var chatBoxSize = mobileSize ? "100%" : "500px";
                document.getElementById("mySidenav").style.width = chatBoxSize;
            }else{
                toastr.error('{{ translate('Rider not assigned yet!') }}', {
                                CloseButton: true,
                                ProgressBar: true
                            });
            }
        })
        function openNav() {
            document.getElementById("mySidenav").style.width = "500px";
        }
        const firebaseConfig = {
            apiKey: "AIzaSyCxpNldfezGvEqQLhPg-ky9iflPOEd4H_E",
            authDomain: "absher-apps.firebaseapp.com",
            databaseURL: "https://absher-apps-default-rtdb.firebaseio.com",
            projectId: "absher-apps",
            storageBucket: "absher-apps.appspot.com",
            messagingSenderId: "565172443161",
            appId: "1:565172443161:web:c5caf83b9a5a73a7373288",
            measurementId: "G-8GTY41RHE4"
            };
      
        // Initialize Firebase
        const app = initializeApp(firebaseConfig);
        // const analytics = getAnalytics(app);
        const database = getDatabase(app);
        
        $('#chatSubmit').click((e)=>{
            e.preventDefault();
            var message = document.getElementById('message').value;
            if(message != ''){
            let pathToChat = 'orders/' + id + '/chats/rider_vendor';
            const _id = push(child(ref(database),pathToChat)).key;
            set(ref(database, pathToChat+"/"+_id),{
                creater_id: "vendor_"+"{{\App\CentralLogics\Helpers::get_loggedin_user()->id}}",
                from: "web",
                message: message,
                timestamp: Date.now(),
                type: "text"
            });
            document.getElementById('message').value = '';

            // alert('message sent successfully');

            }
            
        });
        $('#message').keypress(function(e){
            if(e.keyCode == 13){ // Check if "Enter" key was pressed
                e.preventDefault();
                $('#chatSubmit').click(); // Trigger click event on the chatSubmit button
            }
        });

        function loadChat(chatId , image){
            
            let pathToChat = 'orders/' + chatId + '/chats/rider_vendor';
            const newMsg =  ref(database, pathToChat);  
            const chatContainer = document.getElementById('chat-content');
            onChildAdded(newMsg, (data)=>{
                var creater_id = "vendor_"+"{{$logedInUser}}";
                const timestamp = data.val().timestamp;
                const date = new Date(timestamp);
                const time = date.toLocaleTimeString();
                if (data.val().creater_id != creater_id) {
                    console.log('')
                    var divData = '<div class="media media-chat">\n' +
                        '                      <div class="avatar avatar-sm avatar-circle">'+
                        '                      <img class="avatar-img"\n' +
                        '                         src="{{ asset('storage/app/public/profile/') }}/'+image+'"' +
                        '                         alt="..."> \n' +
                        '                      </div>'+
                        '                    <div class="media-body">\n' +
                        '                        <p>' + data.val().message + '</p>\n' +
                        '                        <p class="meta"><time datetime="2018">'+time+'</time></p>\n' +
                        '                    </div>\n' +
                        '                </div>\n';
                } else {
                    var divData = '<div class="media media-chat media-chat-reverse">\n' +
                        '                <div class="media-body">\n' +
                        '                    <p>' + data.val().message + '</p>\n' +
                        '                        <p class="meta"><time datetime="2018">'+time+'</time></p>\n' +
                        '                </div>\n' +
                        '            </div>\n';
                }
                var d1 = document.getElementById('chat-content');
                
                var newDiv = document.createElement('div');
                newDiv.innerHTML = divData;
                d1.appendChild(newDiv.firstChild);
                d1.scrollTop = d1.scrollHeight;
            });
            
            // onValue(newMsg, function(data) {
            //     console.log(data.val().message);

            //     if (data.val().name != name) {
                    

            //         var divData = '<div class="media media-chat">\n' +
            //             '                      <img class="avatar"\n' +
            //             '                         src="https://img.icons8.com/color/36/000000/administrator-male.png"\n' +
            //             '                         alt="..."> \n' +
            //             '                    <div class="media-body">\n' +
            //             '                        <p>' + data.val().message + '</p>\n' +
            //             '                        <p class="meta"><time datetime="2018">23:58</time></p>\n' +
            //             '                    </div>\n' +
            //             '                </div>\n';
            //     } else {
            //         var divData = '<div class="media media-chat media-chat-reverse">\n' +
            //             '                <div class="media-body">\n' +
            //             '                    <p>' + data.val().message + '</p>\n' +
            //             '                        <p class="meta"><time datetime="2018">23:58</time></p>\n' +
            //             '                </div>\n' +
            //             '            </div>\n';
            //     }
            //     var d1 = document.getElementById('chat-content');
                
            //     var newDiv = document.createElement('div');
            //     newDiv.innerHTML = divData;
            //     d1.appendChild(newDiv.firstChild);
            //     d1.scrollTop = d1.scrollHeight;
            // });   
        
        }
        
    </script>
    {{-- firebase script end --}}
    <script>
        function openChat() {
            // document.getElementById("mySidenav").style.width = "500px";
        }

        function closeNav() {
            document.getElementById("mySidenav").style.width = "0";
            var parentDiv = document.getElementById("chat-content");
            // Remove all child elements
            // parentDiv.innerHTML = "";
            const sidenav = document.getElementById("chat-content");
            while (sidenav.firstChild) {
                sidenav.removeChild(sidenav.firstChild);
            }
        }



        function handlebuttonclick(chatId) {
            get_chat_messages(chatId, openChat)
        }

        // import {
        //     get_chat_messages
        // } from './script.js';
        // import {
        //     openNav
        // } from './script.js';


        // 
    </script>
@endpush

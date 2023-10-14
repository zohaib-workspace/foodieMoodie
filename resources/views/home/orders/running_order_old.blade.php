@extends('layouts.home.
app')
@section('title', ' home')
@Section('main_content')
    <main>
        <!-- /secondary_nav -->

        <div class="bg_gray mt-5">
            <div class="container margin_detail">
                <div class="row" id="orders">
                    <div class="col-lg-12 list_menu">
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="pills-running-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-running" type="button" role="tab" aria-controls="pills-home"
                                    aria-selected="true">Current Orders</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pills-completed-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-completed" type="button" role="tab"
                                    aria-controls="pills-completed" aria-selected="false">Past Orders </button>
                            </li>
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-running" role="tabpanel"
                                aria-labelledby="pills-running-tab">

                                <div class="table_wrapper">
                                    <table class="table  cart-list table-borderless menu-gallery">
                                        <thead>
                                            <tr>
                                                <th>Order #</th>
                                                <th>Item</th>
                                                <th>Created At</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="CurrentOrders">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pills-completed" role="tabpanel"
                                aria-labelledby="pills-completed-tab">

                                <div class="table_wrapper">
                                    <table class="table  cart-list table-borderless menu-gallery">
                                        <thead>
                                            <tr>
                                                <th>Order #</th>
                                                <th>Item</th>
                                                <th>Created At</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="pastOrders">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-7">
                    <button class="btn btn-primary go_back_btn" id="">Go Back</button>
                </div>

                <div id="order_detail">


                </div>
            </div>
        </div>

    </main>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('.go_back_btn').hide();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Authorization': "Bearer {{ Session::get('token') }}"
                }
            });
            var data = {
                // "token": "{{ csrf_token() }}",
                'offset': 0,
                'limit': 10,
            }

            function currentOrderFunc() {
                // console.log('data inside func', data);
                $.ajax({
                    type: "get",
                    url: "{{ url('api/v1/customer/order/running-orders') }}",
                    data: data,
                    success: function(data) {
                        // console.log(data);
                        // console.log(data.response.orders);
                        var current_orders = '';
                        $.each(data.response.orders, function(index, order) {
                            current_orders += `
                          <tr>
                                <td>${order.id}</td>
                                <td class="d-md-flex align-items-center">
                                    <figure>
                                        {{-- onerror="this.src='{{ asset('placeholder.png') }}'" --}}
                                        {{-- {{ asset('storage/app/public/product/' . $product['image']) }} --}}
                                        <a href="#" title="Photo title" data-effect="mfp-zoom-in">
                                            <img  src="{{ asset('storage/app/public/restaurant/') }}/${order.restaurant.logo}"
                                                data-src="{{ asset('storage/app/public/restaurant/') }}/${order.restaurant.logo}"
                                                alt="thumb" class="lazy rounded-circle"
                                                onerror="this.src='{{ asset('placeholder.png') }}'"></a>
                                    </figure>
                                    <div class="flex-md-column">
                                     <h4>${order.restaurant.name}</h4> 
                                     <p>Rs. ${order.order_amount}</p>
                                        <p>
                                            {{-- {{ $product['description'] }} --}}
                                        </p>
                                    </div>
                                </td>
                                <td> ${order.created_at}</td>
                                <td> ${order.order_status}</td>
                                <td><button class="btn text-info" data-order-id="${order.id}" ><i class="fa-regular fa-eye"></i></button></td>

                            </tr>`;
                        });
                        $('#CurrentOrders').empty();
                        $('#CurrentOrders').append(current_orders);
                    },
                    error: function(error) {
                        console.error(error);
                    }
                });

            }
            currentOrderFunc();

            $('#pills-completed-tab').click(function() {
                completed_orders();
            });

            function completed_orders() {
                $.ajax({
                    type: "get",
                    url: "{{ url('api/v1/customer/order/completed-orders') }}",
                    data: data,
                    success: function(data) {
                        // console.log(data);
                        // console.log(data.response.orders);
                        var past_orders = '';

                        $.each(data.response.orders, function(index, past_order) {
                            past_orders += `
                      <tr>
                            <td>${past_order.id}</td>
                            <td class="d-md-flex align-items-center">
                                <figure>
                                    {{-- onerror="this.src='{{ asset('placeholder.png') }}'" --}}
                                    {{-- {{ asset('storage/app/public/product/' . $product['image']) }} --}}
                                    <a href="#" title="Photo title" data-effect="mfp-zoom-in">
                                        <img src="{{ asset('storage/app/public/restaurant/') }}/${past_order.restaurant.logo}"
                                            data-src="{{ asset('storage/app/public/restaurant/') }}/${past_order.restaurant.logo}"
                                            alt="thumb" class="lazy rounded-circle"
                                            onerror="this.src='{{ asset('placeholder.png') }}'"></a>
                                </figure>
                                <div class="flex-md-column">
                                 <h4>${past_order.restaurant.name}</h4> 
                                 <p>Rs. ${past_order.order_amount}</p>
                                    <p>
                                        {{-- {{ $product['description'] }} --}}
                                    </p>
                                </div>
                            </td>
                            <td> ${past_order.created_at}</td>
                            <td> ${past_order.order_status}</td>
                            <td><button class="btn text-info" data-order-id="${past_order.id}" ><i class="fa-regular fa-eye"></i></button></td>


                        </tr>`;
                        });
                        $('#pastOrders').empty();
                        $('#pastOrders').append(past_orders);
                    },
                    error: function(error) {
                        console.error(error);
                    }
                });
            }


            // order detail

            $(document).on('click', '[data-order-id]', function() {
                function orderDetailApiFunction() {


                    // 'this' refers to the clicked element
                    var order_id = $(this).data('order-id');
                    // console.log('Clicked ID:', order_id);
                    $.ajax({
                        type: "get",
                        url: `{{ url('api/v1/customer/order/details') }}?order_id=${order_id}`,
                        dataType: "json",
                        success: function(response) {
                            // console.log(response);
                            var order_detail = `
                        <div class="row ">

                            <div class="col-7 mt-2">
                               Order Id ${response.response.order.id}
                            </div> `;
                            if (response.response.order.processing_time) {
                                order_detail += ` <div class="col-5 mt-2 d-flex justify-content-end">
                                    <button class="btn btn-warning"> ${response.response.order.processing_time} mins</button>
                                </div>`;

                            }
                            order_detail += `
                            <div class="col-md-6 card">
                                <h1 class="text-warning"> Delivery Address</h1>
                                <p>${response.response.order.delivery_address.address}</p>
                            </div>
                            <div class="col-md-6 card  ">`;
                            if (response.response.order.delivery_man) {
                                order_detail += `<h6>Delivery man not available</h6>
                            <h6 class="my-auto text-danger">${response.response.order.delivery_man.f_name} ${response.response.order.delivery_man.l_name}</h6>
                            <h6>${response.response.order.delivery_man.phone}</h6>`;


                            } else {

                                order_detail += `<b>Delivery man not available</b>`;
                            }
                            order_detail += ` </div>
                        </div>
                        <div class="row p-2">
                            <div class="col-6 d-flex align-items-center">
                                <img src="{{ asset('storage/app/public/restaurant/') }}/${response.response.order.restaurant.logo}" onerror="this.src={{ asset('placeholder.png') }}" alt="" class="rounded-circle" width="100" height="100">
                                <h4 class="px-2">${response.response.order.restaurant.name}</h4>
                            </div>
                            <div class="col-6 flex-column align-items-center my-auto">
                                <i class="fa-solid fa-phone text-warning fs-5"></i>
                                <br>
                                <i class="fa-regular fa-message text-warning fs-5 my-2"></i>
                            </div>
                        </div>
                        <div class="row mt-2 py-2 rounded" style="background:#f6f1d3;">`;

                            // console.log(response.response.order);

                            $.each(response.response.order.details, function(i, d) {
                                // console.log('detail first ',d); 
                                order_detail += `
                                <div class="col-6 d-flex align-items-center my-1">
                                    <img src="{{ asset('storage/app/public/product') }}/${d.food_details.image}" onerror="this.src={{ asset('placeholder.png') }}" alt="" class="rounded" width="100" height="100">
                                    <div class="px-2">
                                        <h6>Rs. ${d.price+d.total_add_on_price}</h6>
                                        <h6> ${d.food_details.name}</h6>
                                        <h6>${d.food_details.variation.type}</h6>
                                    </div>
                                </div>

                                <div class="col-6 m-auto">
                                    <h6>Quantity</h6>
                                    <div class="px-2">
                                        <h6> ${d.quantity}</h6>
                                    </div>
                                </div>`;
                            });

                            order_detail += `
                        <hr class="my-1">
                        <div class="col-6 mt-3">
                            <h6>Delivery Charges</h6>
                        </div>
                        <div class="col-6 mt-3">
                            <h6 class="text-warning">Rs. ${response.response.order.delivery_charge}</h6>
                        </div>
                        <div class="col-6 mt-3">
                            <h6>Tax Amount</h6>
                        </div>
                        <div class="col-6 mt-3">
                            <h6 class="text-warning">Rs. ${response.response.order.total_tax_amount}</h6>
                        </div>
                        <div class="col-6 mt-3">
                            <h6>Cart Total</h6>
                        </div>
                        <div class="col-6 mt-3">
                            <h6 class="text-warning">Rs. ${response.response.order.order_amount}</h6>
                        </div>

                        <div class="bg-warning col-2 py-2 rounded-end rounded-end-3 text-white">
                            <i class="fa-solid fa-wallet px-1"></i>Delivery
                        </div>
                        
                    </div>`;
                            if (response.response.order.order_status == 'pending') {
                                order_detail += `
                            <div class="row">
                                <div class="col-12 m-auto">
                                    <button class="btn btn-danger" data-order-id-2="${response.response.order.id}" id="cancel_order_btn">Cancel Order</button>
                                </div>
                            </div>
                            `;
                            }
                            order_detail += `
                            <div class="row">
                                <div class="col-12 m-auto">
                                    <button class="btn btn-danger" data-report-order-id="${response.response.order.id}" id="report_order_btn">Report Order</button>
                                </div>
                            </div>
                            `;
                            $('#orders').hide();
                            $('#order_detail').empty();
                            $('#order_detail').show();
                            $('.go_back_btn').show();
                            $('#order_detail').append(order_detail);

                        },

                        error: function(erros) {
                            console.error(erros);
                        }
                    });
                }
                orderDetailApiFunction();
            });

            $(".go_back_btn").click(function() {
                currentOrderFunc();
                completed_orders();
                $('#orders').show();
                $('#order_detail').hide();
                $('.go_back_btn').hide();

            });
            $(document).on('click', '[data-order-id-2]', function() {
                // e.preventDefault();
                var order_id = $(this).data('order-id-2');
                var data = {
                    'order_id': order_id,
                }
                // var order_id='100035';
                // console.log('selected id= ', order_id);
                let isCancel = confirm('Are you sure you want to cancel this order?');
                // alert(isCancel);
                if (isCancel) {
                    $.ajax({
                        type: "post",
                        url: "{{ url('api/v1/customer/order/cancel') }}",
                        data: data,
                        dataType: "json",
                        success: function(response) {
                            orderDetailApiFunction();
                            console.log(response);
                            alert(response.message)
                            // currentOrderFunc();

                        },
                        error: function(error) {
                            console.log(error);
                            alert(error.message);
                        }
                    });
                }

            });

        });
    </script>
@endsection

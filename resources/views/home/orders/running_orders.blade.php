@extends('layouts.home.app')
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

                                <div class="table_wrapper table-responsive">
                                    <table class="table  cart-list table-borderless menu-gallery">
                                        <thead>
                                            <tr>
                                                <th>Order #</th>
                                                <th>Restaurant</th>
                                                <th>Date</th>
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

                                <div class="table_wrapper table-responsive">
                                    <table class="table  cart-list table-borderless menu-gallery">
                                        <thead>
                                            <tr>
                                                <th>Order #</th>
                                                <th>Restaurant</th>
                                                <th>Date</th>
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
                    <button class="btn btn-success go_back_btn" id="">Go Back</button>
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

            function capitalizeFirstLetter(string) {
                return string.charAt(0).toUpperCase() + string.slice(1);
            }

            function formatCreatedAt(timestamp) {
                var createdAtDate = new Date(timestamp);
                var formattedCreatedAt = createdAtDate.toLocaleString('en-US', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: 'numeric',
                    minute: 'numeric',
                    // second: 'numeric',
                    hour12: true
                });
                return formattedCreatedAt;
            }

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
                            console.log('--'+order.id);

                        
                          
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
                                <td> ${formatCreatedAt(order.created_at)}</td>
                                <td> ${capitalizeFirstLetter(order.order_status)}</td>
                                <td><a class="btn text-success" href="{{ url('user/order/detail/')}}/${order.id}" ><i class="fa-regular fa-eye"></i></a></td>
                                
                                </tr>`;
                            });
                        $('#CurrentOrders').empty();
                        $('#CurrentOrders').append(current_orders);
                    },
                    error: function(error) {
                        console.error(error);
                    }
                    // <td><button class="btn text-success" data-order-id="${order.id}" ><i class="fa-regular fa-eye"></i></button></td>
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
                            <td> ${formatCreatedAt(past_order.created_at)}</td>
                            <td> ${capitalizeFirstLetter(past_order.order_status)}</td>
                            <td><button class="btn text-success" data-order-id="${past_order.id}" ><i class="fa-regular fa-eye"></i></button></td>


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
                // 'this' refers to the clicked element

                var order_id = $(this).data('order-id');
                getOrderDetails(order_id);

                // console.log('Clicked ID:', order_id);
            });

            function getOrderDetails(order_id) {
                $.ajax({
                    type: "get",
                    url: `{{ url('api/v1/customer/order/details') }}?order_id=${order_id}`,
                    dataType: "json",
                    success: function(response) {


                        var order_detail = `
                            <div class="row" >
                                <div class="col-lg-8 list_menu">
                                    <section id="section-1">
                                        <h4> Order Id ${response.response.order.id}</h4>
                                        <div class="table_wrapper table-responsive">
                                            <table class="table table-borderless cart-list menu-gallery">
                                                <thead>
                                                    <tr>
                                                        <th> Image</th>
                                                        <th> Name</th>
                                                        <th> price</th>
                                                        <th> variation</th>
                                                        <th>Add ons</th>
                                                    </tr>
                                                </thead>
                                                <tbody>`;
                        $.each(response.response.order.details, function(i, d) {
                            console.log(d);
                            order_detail += `
                                                        <tr>
                                                            <td class="">
                                                                <img <img src="{{ asset('storage/app/public/product') }}/${d.food_details.image}" onerror="this.src={{ asset('placeholder.png') }}" width="50" height="50" class="rounded" alt="">
                                                            </td>
                                                            <td>${d.food_details.name}</td>
                                                            <td>Rs ${d.price+d.total_add_on_price}</td>
    
                                                            <td>${d.variation[0].type}</td>
                                                            <td>`;
                            $.each(d.add_ons, function(j, add_on) {
                                console.log(add_on);
                                order_detail += `${add_on.name}, `;
                            });
                            order_detail += `
                                                            </td>
                                                        </tr>`;
                        });

                        order_detail += `
                                                </tbody>
                                            </table>
                                        </div>
                                    </section>
    
                                </div>
    
                                <div class="col-lg-4">
                                    <div class="box_order border">
                                        <div class="head d-flex justify-content-between align-items-center">
                                            <h3 class="mb-0">Order Summary</h3>
                                            <a href="#0" class="close_panel_mobile"><i class="icon_close"></i></a>
                                        </div>
                                        <div class="main" id="">
                                            
                                            <ul class="list-unstyled">
                                                <h5>Restaurant</h5>
                                                <li class="d-flex align-items-center mb-3">
                                                    <img src="{{ asset('storage/app/public/restaurant/') }}/${response.response.order.restaurant.logo}" onerror="this.src={{ asset('placeholder.png') }}" width="40" height="40" class="rounded-circle" alt="">
                                                    <b class="px-1 fs-5">${response.response.order.restaurant.name}</b>
                                                    <span class="ms-auto">
                                                        <button class="btn p-0 m-0"><i class="fa-solid fa-phone text-warning fs-5"></i></button>
                                                        <br>
                                                        <button class="btn p-0 m-0"><i class="fa-regular fa-message text-warning fs-5 my-2"></i></button>
                                                    </span>
                                                </li>
                                                
                                                <h5>Rider   </h5>`;
                        if (response.response.order.delivery_man) {
                            order_detail += `<li class="d-flex justify-content-between mb-3">
                                                    <p>${response.response.order.delivery_man.f_name} ${response.response.order.delivery_man.l_name} <br> ${response.response.order.delivery_man.phone}</p>
                                                    <span class="">
                                                        <button class="btn p-0 m-0"><i class="fa-solid fa-phone text-warning fs-5"></i></button>
                                                        <br>
                                                        <button class="btn p-0 m-0"><i class="fa-regular fa-message text-warning fs-5 my-2"></i></button>
                                                    </span>
                                                </li>`;
                        } else {
                            order_detail += `<li>Delivery man not available</li>`;
                        }
                        order_detail += `
                                                <li>Delivery charges <span>Rs. ${response.response.order.delivery_charge}</span></li>
                                                <li>Tax Amount <span>Rs. ${response.response.order.total_tax_amount}</span></li>
                                                <li>Cart Total <span>Rs. ${response.response.order.order_amount}</span></li>
                                            </ul>
                                            {{-- <ul class="list-unstyled">
                                                <li class="total">Subtotal<span id="sub_total">$0</span></li>
                                            </ul> --}}
                                            `;
                                                if (response.response.order.order_status == 'pending') {
                                                    order_detail += `
                                                        <div class="btn_1_mobile">
                                                            <button href="#" class="btn_1 gradient full-width mb-5" data-order-id-2="${response.response.order.id}" id="cancel_order_btn">Cancel Order</button>
                                                        </div>`;
                                                }
                                        if (response.response.order.order_status == 'canceled') {
                                            order_detail += `
                                                <div class="btn_1_mobile">
                                                    <button  class="btn_1 gradient full-width mb-1" data-bs-toggle="modal" data-bs-target="#staticBackdrop" >Report Order</button>
                                                </div>
                                                
                                                <!-- Button trigger modal -->
    
    
                                                <!-- Modal -->
                                                <div class="modal fade" style="z-index:50000;" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="staticBackdropLabel">Order # ${response.response.order.id}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <label>Write your complain here</label>
                                                                <textarea class="form-control" id="report_input" rows="2" placeholder="Write your complain here"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <button type="button" class="btn btn_1 gradient " data-report-order-id="${response.response.order.id}" id="report_order_btn">Submit</button>
                                                    </div>
                                                    </div>
                                                </div>
                                                </div>
                                                `;
                        }
                        order_detail += `
                                        </div>
                                    </div>
                                </div>
                                
                            </div>`;
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
                    'user_id': order_id,
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
                            console.log(response);
                            alert(response.message);
                            getOrderDetails(order_id);
                            // currentOrderFunc();

                        },
                        error: function(error) {
                            console.log(error);
                            alert(error.message);
                        }
                    });
                }

            });
            $(document).on('click', '[data-report-order-id]', function() {
                // e.preventDefault();
                var order_id = $(this).data('report-order-id');
                var complain = $('#report_input').val();
                if (complain == '') {
                    return alert('Please write proper complain to send');
                }
                if (complain.length < 10) {
                    return alert('Please explain proper');

                }
                var data = {
                    'order_id': order_id,
                    'user_id': `{{ auth()->id() }}`,
                    'complain': complain,
                }
                $.ajax({
                    type: "post",
                    url: "{{ url('api/v1/report/send_report') }}",
                    data: data,
                    dataType: "json",
                    success: function(response) {
                        console.log(response);
                        alert(response.message);
                        $(".modal").modal("hide");
                        getOrderDetails(order_id);
                        // currentOrderFunc();

                    },
                    error: function(error) {
                        console.log(error);
                        alert(error.message);
                    }
                });


            });

        });
    </script>
@endsection

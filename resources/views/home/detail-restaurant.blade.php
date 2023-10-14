@extends('layouts.home.app')
@section('title', ' home')
@Section('main_content')
    <main>

        <div class="hero_in detail_page background-image"
            data-background="url({{ asset('storage/restaurant/cover/' . $restaurent['response']['cover_photo']) }})">
            <div class="wrapper opacity-mask" data-opacity-mask="rgba(0, 0, 0, 0.5)">
                <div class="container">
                    <div class="main_info">
                        <div class="row">
                            <div class="col-xl-4 col-lg-5 col-md-6">
                                <div class="head">
                                    <div class="score">
                                        <img src='{{ asset('storage/restaurant/' . $restaurent['response']['logo']) }}'
                                            height="50px" width="50px"
                                            onerror="this.src='{{ asset('placeholder.png') }}'">
                                    </div>
                                </div>
                                <h1>{{ $restaurent['response']['name'] }}</h1>
                                {{ $restaurent['response']['address'] }} - <a
                                    href="https://www.google.com/maps?q={{ $restaurent['response']['latitude'] }},{{ $restaurent['response']['longitude'] }}"
                                    target="blank">Get directions</a>
                            </div>
                            <div class="col-xl-8 col-lg-7 col-md-6 position-relative">
                                <div class="buttons clearfix">
                                    <span class="magnific-gallery">
                                        {{-- <a href="{{ asset('public/home_assets/img/detail_1.jpg')}}" class="btn_hero" title="Photo title" data-effect="mfp-zoom-in"><i class="icon_image"></i>View photos</a> --}}
                                        <a href="{{ asset('public/home_assets/img/detail_2.jpg') }}" title="Photo title"
                                            data-effect="mfp-zoom-in"></a>
                                        <a href="{{ asset('public/home_assets/img/detail_3.jpg') }}" title="Photo title"
                                            data-effect="mfp-zoom-in"></a>
                                    </span>
                                    {{-- <a href="#0" class="btn_hero wishlist"><i class="icon_heart"></i>Wishlist</a> --}}
                                </div>
                            </div>
                        </div>
                        <!-- /row -->
                    </div>
                    <!-- /main_info -->
                </div>
            </div>
        </div>
        <!--/hero_in-->

        {{-- <nav class="secondary_nav sticky_horizontal">
            <div class="container">
                <ul id="secondary_nav">
                    <li><a href="#section-1">Starters</a></li>
                    <li><a href="#section-2">Main Courses</a></li>
                    <li><a href="#section-3">Desserts</a></li>
                    <li><a href="#section-4">Drinks</a></li>
                    <li><a href="#section-5"><i class="icon_chat_alt"></i>Reviews</a></li>
                </ul>
            </div>
            <span></span>
        </nav> --}}
        <!-- /secondary_nav -->

        <div class="bg_gray">
            <div class="container margin_detail">
                <div class="row" id="product_cart_detail">
                    <div class="col-lg-8 list_menu">
                        @php
                            $categories = $categories['response']['data'];
                        @endphp
                        @if (!empty($categories))
                            @foreach ($categories as $c => $category)
                                @if (!empty($category['category_products']))
                                    <section id="section-1">
                                        <h4> {{ $category['name'] }}</h4>
                                        <div class="table_wrapper">
                                            <table class="table table-borderless cart-list menu-gallery">
                                                <thead>
                                                    <tr>
                                                        <th>
                                                            Item
                                                        </th>
                                                        <th>
                                                            Price
                                                        </th>
                                                        <th>
                                                            Order
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if (!empty($category['category_products']))
                                                        @foreach ($category['category_products'] as $p => $product)
                                                            <tr>
                                                                <td class="d-md-flex align-items-center">
                                                                    <figure>
                                                                        {{-- onerror="this.src='{{ asset('placeholder.png') }}'" --}}
                                                                        {{-- {{ asset('storage/app/public/product/' . $product['image']) }} --}}
                                                                        <a href="#" title="Photo title"
                                                                            data-effect="mfp-zoom-in">
                                                                            <img src="{{ asset('storage/app/public/product/' . $product['image']) }}"
                                                                                data-src="{{ asset('storage/app/public/product/' . $product['image']) }}"
                                                                                alt="thumb" class="lazy"
                                                                                onerror="this.src='{{ asset('placeholder.png') }}'"></a>
                                                                    </figure>
                                                                    <div class="flex-md-column">
                                                                        <h4>{{ $product['name'] }}</h4>
                                                                        <p>
                                                                            {{ $product['description'] }}
                                                                        </p>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <strong>{{ $product['price'] }}</strong>
                                                                </td>
                                                                <td class="options">
                                                                    <div class="dropdown dropdown-options">
                                                                        <a href="#" class="dropdown-toggle"
                                                                            onclick="get_item_detail({{ $product['id'] }})"
                                                                            data-bs-toggle="dropdown"
                                                                            aria-expanded="true"><i
                                                                                class="icon_plus_alt2"></i></a>
                                                                        <div class="dropdown-menu"
                                                                            id="drop_down_{{ $product['id'] }}">

                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </section>
                                @endif
                            @endforeach
                        @endif

                    </div>
                    <!-- /col -->

                    <div class="col-lg-4" id="sidebar_fixed">
                        <div class="box_order mobile_fixed">
                            <div class="head">
                                <h3>Order Summary</h3>
                                <a href="#0" class="close_panel_mobile"><i class="icon_close"></i></a>
                            </div>
                            <!-- /head -->
                            <div class="main" id="order_summary">

                                {{-- <ul class="clearfix">
                                    <li><a href="#0">1x Enchiladas</a><span>$11</span></li>
                                    <li><a href="#0">2x Burrito</a><span>$14</span></li>
                                    <li><a href="#0">1x Chicken</a><span>$18</span></li>
                                    <li><a href="#0">2x Corona Beer</a><span>$9</span></li>
                                    <li><a href="#0">2x Cheese Cake</a><span>$11</span></li>
                                </ul> --}}
                                <ul class="clearfix" id="total_records_parent">
                                    <li class="total">Subtotal<span id="sub_total">$0</span></li>
                                    {{-- <li>Delivery fee<span>$0</span></li> --}}
                                    {{-- <li class="total">Total<span id="total">$0</span></li> --}}
                                </ul>

                                {{-- <div class="dropdown day">
                                    <a href="#" data-bs-toggle="dropdown">Day <span id="selected_day"></span></a>
                                    <div class="dropdown-menu">
                                        <div class="dropdown-menu-content">
                                            <h4>Which day delivered?</h4>
                                            <div class="radio_select chose_day">
                                                <ul>
                                                    <li>
                                                        <input type="radio" id="day_1" name="day" value="Today">
                                                        <label for="day_1">Today<em>-40%</em></label>
                                                    </li>
                                                    <li>
                                                        <input type="radio" id="day_2" name="day"
                                                            value="Tomorrow">
                                                        <label for="day_2">Tomorrow<em>-40%</em></label>
                                                    </li>
                                                </ul>
                                            </div>
                                            <!-- /people_select -->
                                        </div>
                                    </div>
                                </div>
                                <!-- /dropdown -->
                                <div class="dropdown time">
                                    <a href="#" data-bs-toggle="dropdown">Time <span id="selected_time"></span></a>
                                    <div class="dropdown-menu">
                                        <div class="dropdown-menu-content">
                                            <h4>Lunch</h4>
                                            <div class="radio_select add_bottom_15">
                                                <ul>
                                                    <li>
                                                        <input type="radio" id="time_1" name="time"
                                                            value="12.00am">
                                                        <label for="time_1">12.00<em>-40%</em></label>
                                                    </li>
                                                    <li>
                                                        <input type="radio" id="time_2" name="time"
                                                            value="08.30pm">
                                                        <label for="time_2">12.30<em>-40%</em></label>
                                                    </li>
                                                    <li>
                                                        <input type="radio" id="time_3" name="time"
                                                            value="09.00pm">
                                                        <label for="time_3">1.00<em>-40%</em></label>
                                                    </li>
                                                    <li>
                                                        <input type="radio" id="time_4" name="time"
                                                            value="09.30pm">
                                                        <label for="time_4">1.30<em>-40%</em></label>
                                                    </li>
                                                </ul>
                                            </div>
                                            <!-- /time_select -->
                                            <h4>Dinner</h4>
                                            <div class="radio_select">
                                                <ul>
                                                    <li>
                                                        <input type="radio" id="time_5" name="time"
                                                            value="08.00pm">
                                                        <label for="time_1">20.00<em>-40%</em></label>
                                                    </li>
                                                    <li>
                                                        <input type="radio" id="time_6" name="time"
                                                            value="08.30pm">
                                                        <label for="time_2">20.30<em>-40%</em></label>
                                                    </li>
                                                    <li>
                                                        <input type="radio" id="time_7" name="time"
                                                            value="09.00pm">
                                                        <label for="time_3">21.00<em>-40%</em></label>
                                                    </li>
                                                    <li>
                                                        <input type="radio" id="time_8" name="time"
                                                            value="09.30pm">
                                                        <label for="time_4">21.30<em>-40%</em></label>
                                                    </li>
                                                </ul>
                                            </div>
                                            <!-- /time_select -->
                                        </div>
                                    </div>
                                </div> --}}
                                <!-- /dropdown -->
                                <div class="btn_1_mobile">
                                    <button href="#" class="btn_1 gradient full-width mb_5" id="check_out_btn">Check
                                        out</button>
                                    <div class="text-center"><small>No money charged on this steps</small></div>
                                </div>
                            </div>
                        </div>
                        <!-- /box_order -->
                        <div class="btn_reserve_fixed"><a href="#0" class="btn_1 gradient full-width">View
                                Basket</a></div>
                    </div>
                </div>
                <!-- /row -->
                {{-- checkout detail page show --}}
                <div class="row" id="check_out_detail">
                    <div class="col-md-12 my-2">
                        <button class="btn btn-primary" id="go_back_to_products">Go back!</button>
                    </div>

                    <div class="col-md-8 my-2">
                        <div class="table_wrapper">
                            <table class="table table-borderless cart-list menu-gallery">
                                <thead>
                                    <tr>
                                        <th>
                                            Item
                                        </th>
                                        <th>
                                            Price
                                        </th>
                                        <th>
                                            Remove
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="second_cart_product_show">

                                </tbody>
                            </table>
                        </div>
                    </div>
                    {{-- <div class="col-md-4 my-2">
                        <div class="card border">
                            <div class="card-header bg-secondary text-center">
                                Place Order
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6"><b>Cart Total</b></div>
                                    <div class="col-6" id="total_second_cart">Rs. 0</div>
                                    <div class="col-6"><b>Delivery Charges</b></div>
                                    <div class="col-6">Rs. 0</div>
                                    <div class="col-6"><b>Total Amount</b></div>
                                    <div class="col-6" id="total_amount_second_cart">Rs. 0</div>
                                </div>
                            </div>

                            <div class=" m-2">
                                <div class="col-sm-4">
                                    <label class="container_radio">Delivery
                                        <input type="radio" value="option1" name="opt_order" checked>
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="col-sm-4">
                                    <label class="container_radio">Take away
                                        <input type="radio" value="option1" name="opt_order">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="col-sm-4">
                                    <label class="container_radio">Dine in
                                        <input type="radio" value="option1" name="opt_order">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                            </div>
                        </div>

                    </div> --}}

                    <div class="col-md-4 my-2">
                        <div class="box_order ">
                            <div class="head">
                                <h3>Place Order</h3>
                                <a href="#0" class="close_panel_mobile"><i class="icon_close"></i></a>
                            </div>
                            <!-- /head -->
                            <div class="main">

                                <ul class="clearfix" id="total_records_parent">
                                    <li class="total">Cart Total<span id="total_second_cart">Rs. 0</span></li>
                                    <li class="total">Delivery Charges<span id="delivery_charges_show">Rs. 0</span></li>
                                    <li class="total">Total Amount<span id="total_amount_second_cart">Rs. 0</span></li>

                                </ul>
                                <div class="row opt_order">
                                    <h6>Choose Delivery Method</h6>
                                    <div class="col-12">
                                        <label class="container_radio">Delivery
                                            <input type="radio" value="delivery" name="delivery_method">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="col-12">
                                        <label class="container_radio">Take away
                                            <input type="radio" value="take_away" name="delivery_method">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="col-12">
                                        <label class="container_radio">Dine In
                                            <input type="radio" value="dine_in" name="delivery_method">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="row">
                                    <h6>Choose Payment Method</h6>
                                    <div class="col-12">
                                        <label class="container_radio">Cash On Delivery
                                            <input type="radio" value="cash_on_delivery" name="payment">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="col-12">
                                        <label class="container_radio">Online payment
                                            <input type="radio" value="digital_payment" name="payment">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                </div>



                                <button class="btn_1 gradient full-width mb_5" id="place_order">Place Order</button>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
            <!-- /container -->
        </div>
        <!-- /bg_gray -->

        <div class="container margin_30_20">
            <div class="row">
                <div class="col-lg-8 list_menu">
                    <section id="section-5">
                        <h4>Reviews</h4>
                        <div class="row add_bottom_30 d-flex align-items-center reviews">
                            <div class="col-md-3">
                                <div id="review_summary">
                                    <strong>8.5</strong>
                                    <em>Superb</em>
                                    <small>Based on 4 reviews</small>
                                </div>
                            </div>
                            <div class="col-md-9 reviews_sum_details">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>Food Quality</h6>
                                        <div class="row">
                                            <div class="col-xl-10 col-lg-9 col-9">
                                                <div class="progress">
                                                    <div class="progress-bar" role="progressbar" style="width: 90%"
                                                        aria-valuenow="90" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                            <div class="col-xl-2 col-lg-3 col-3"><strong>9.0</strong></div>
                                        </div>
                                        <!-- /row -->
                                        <h6>Service</h6>
                                        <div class="row">
                                            <div class="col-xl-10 col-lg-9 col-9">
                                                <div class="progress">
                                                    <div class="progress-bar" role="progressbar" style="width: 95%"
                                                        aria-valuenow="95" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                            <div class="col-xl-2 col-lg-3 col-3"><strong>9.5</strong></div>
                                        </div>
                                        <!-- /row -->
                                    </div>
                                    <div class="col-md-6">
                                        <h6>Punctuality</h6>
                                        <div class="row">
                                            <div class="col-xl-10 col-lg-9 col-9">
                                                <div class="progress">
                                                    <div class="progress-bar" role="progressbar" style="width: 60%"
                                                        aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                            <div class="col-xl-2 col-lg-3 col-3"><strong>6.0</strong></div>
                                        </div>
                                        <!-- /row -->
                                        <h6>Price</h6>
                                        <div class="row">
                                            <div class="col-xl-10 col-lg-9 col-9">
                                                <div class="progress">
                                                    <div class="progress-bar" role="progressbar" style="width: 60%"
                                                        aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                            <div class="col-xl-2 col-lg-3 col-3"><strong>6.0</strong></div>
                                        </div>
                                        <!-- /row -->
                                    </div>
                                </div>
                                <!-- /row -->
                            </div>
                        </div>
                        <!-- /row -->
                        <div id="reviews">
                            <div class="review_card">
                                <div class="row">
                                    <div class="col-md-2 user_info">
                                        <figure><img src="{{ asset('public/home_assets/img/avatar4.jpg') }}"
                                                alt=""></figure>
                                        <h5>Lukas</h5>
                                    </div>
                                    <div class="col-md-10 review_content">
                                        <div class="clearfix add_bottom_15">
                                            <span class="rating">8.5<small>/10</small> <strong>Rating
                                                    average</strong></span>
                                            <em>Published 54 minutes ago</em>
                                        </div>
                                        <h4>"Great Location!!"</h4>
                                        <p>Eos tollit ancillae ea, lorem consulatu qui ne, eu eros eirmod scaevola sea. Et
                                            nec tantas accusamus salutatus, sit commodo veritus te, erat legere fabulas has
                                            ut. Rebum laudem cum ea, ius essent fuisset ut. Viderer petentium cu his. Tollit
                                            molestie suscipiantur his et.</p>
                                        <ul>
                                            <li><a href="#0"><i class="icon_like"></i><span>Useful</span></a></li>
                                            <li><a href="#0"><i class="icon_dislike"></i><span>Not useful</span></a>
                                            </li>
                                            <li><a href="#0"><i class="arrow_back"></i> <span>Reply</span></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <!-- /row -->
                            </div>
                            <!-- /review_card -->
                            <div class="review_card">
                                <div class="row">
                                    <div class="col-md-2 user_info">
                                        <figure><img src="{{ asset('public/home_assets/img/avatar1.jpg') }}"
                                                alt=""></figure>
                                        <h5>Marika</h5>
                                    </div>
                                    <div class="col-md-10 review_content">
                                        <div class="clearfix add_bottom_15">
                                            <span class="rating">9.0<small>/10</small> <strong>Rating
                                                    average</strong></span>
                                            <em>Published 11 Oct. 2019</em>
                                        </div>
                                        <h4>"Really great dinner!!"</h4>
                                        <p>Eos tollit ancillae ea, lorem consulatu qui ne, eu eros eirmod scaevola sea. Et
                                            nec tantas accusamus salutatus, sit commodo veritus te, erat legere fabulas has
                                            ut. Rebum laudem cum ea, ius essent fuisset ut. Viderer petentium cu his. Tollit
                                            molestie suscipiantur his et.</p>
                                        <ul>
                                            <li><a href="#0"><i class="icon_like"></i><span>Useful</span></a></li>
                                            <li><a href="#0"><i class="icon_dislike"></i><span>Not useful</span></a>
                                            </li>
                                            <li><a href="#0"><i class="arrow_back"></i> <span>Reply</span></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <!-- /row -->
                                <div class="row reply">
                                    <div class="col-md-2 user_info">
                                        <figure><img src="{{ asset('public/home_assets/img/avatar.jpg') }}"
                                                alt=""></figure>
                                    </div>
                                    <div class="col-md-10">
                                        <div class="review_content">
                                            <strong>Reply from Foogra</strong>
                                            <em>Published 3 minutes ago</em>
                                            <p><br>Hi Monika,<br><br>Eos tollit ancillae ea, lorem consulatu qui ne, eu eros
                                                eirmod scaevola sea. Et nec tantas accusamus salutatus, sit commodo veritus
                                                te, erat legere fabulas has ut. Rebum laudem cum ea, ius essent fuisset ut.
                                                Viderer petentium cu his. Tollit molestie suscipiantur his et.<br><br>Thanks
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <!-- /reply -->
                            </div>
                            <!-- /review_card -->
                        </div>
                        <!-- /reviews -->
                        <div class="text-end"><a href="leave-review.html" class="btn_1 gradient">Leave a Review</a></div>
                    </section>
                    <!-- /section -->
                </div>
            </div>
        </div>
        <!-- /container -->
    </main>
@endsection

@section('scripts')
    <script>
        function get_item_detail(id) {
            // console.clear();
            $('#drop_down_' + id).show();
            // console.log(id);
            $.ajax({
                type: "get",
                url: "{{ url('api/v1/products/details/') }}" + "/" + id,
                // data: {'id':id}, 
                success: function(response_data) {
                    console.log(response_data);


                    var dropdownHtml = `
                            <h5>Select a variation</h5>
                            <ul class="clearfix">`;

                    //execute loop here
                    $.each(response_data.response.variations, function(index, item) {
                        // console.log(item);
                        $('input[name="myRadio"]').first().prop('checked', true);
                        dropdownHtml += `
                        <li>
                            <label class="container_radio">${item.type}<small> +$ ${item.price}</small>
                                <input type="radio" checked class="variations" name="vari"   data-price="${item.price}" data-type="${item.type}" >
                                <span class="checkmark"></span>
                            </label>
                            
                        </li>`;
                    });
                    // loop end

                    dropdownHtml += `  </ul>
                    <h5>Add ons</h5>
                    <ul class="clearfix">  `;
                    $.each(response_data.response.add_ons, function(index, item) {
                        // console.log(item);

                        dropdownHtml += `
                    <li>
                        <label class="container_check"> ${item.name} <small>+$${item.price}</small>
                            <input type="checkbox" id="add_ons_${response_data.response.id}" class="add_ons" value="${item.name}" data-id="${item.id}" data-price="${item.price}" data-name="${item.name}">
                            <span class="checkmark"></span>                            
                        </label>
                    </li>`;
                    });

                    dropdownHtml +=
                        `</ul>
                    <a href="#0" class="btn_1" onclick="add_to_cart_btn(${response_data.response.id},${response_data.response.price},'${response_data.response.name}',${response_data.response.restaurant_id},'${response_data.response.image}')">Add to cart</a> `;

                    var drop_down_id = "#drop_down_" + id;
                    $(drop_down_id).empty();
                    $(drop_down_id).append(dropdownHtml);
                },
                error: function(error) {
                    console.error(error);
                }
            });
        }
        // Define an empty cart as an object
        var cart = {};

        // Function to add items to the cart
        function add_to_cart_btn(id, product_price, product_name, restaurant_id, image) {

            // console.log('product price is' , product_price);


            // Clear console (optional)
            // console.clear();
            // Show checkboxes
            $('#drop_down_' + id).show();

            // Collect selected addons
            var selectedAddOns = $('.add_ons:checked').map(function() {
                return {
                    id: $(this).data('id'),
                    name: $(this).data('name'),
                    price: parseFloat($(this).data('price'))
                };
            }).get();

            // Collect selected variations (radio buttons)
            var selectedVariations = {
                // id: $('.variations:checked').data('id'),
                type: $('.variations:checked').data('type'),
                price: parseFloat($('.variations:checked').data('price'))
            };
            if (selectedVariations.type === undefined) {
                selectedVariations = '';
            }

            // Create an object with selected values
            var selected_value = {
                'product_id': id,
                'restaurant_id': restaurant_id,
                'product_name': product_name,
                'product_price': product_price,
                'image': image,
                'variations': selectedVariations,
                'add_ons': selectedAddOns,
            }

            // If the item ID is not in the cart, create an empty array for it
            if (!cart[id]) {
                cart[id] = [];
            }

            // Add the selected item to the cart
            cart[id].push(selected_value);

            // Hide checkboxes and clear selections
            $('#drop_down_' + id).hide();
            $('.add_ons:checked').prop('checked', false);
            $('.variations:checked').prop('checked', false);



            // Show order summary
            orderShow(id);
        }

        // Function to display order summary
        // Initialize sub_total variable            
        var sub_total = 0;

        function orderShow(id) {
            // console.log('cart to show ', cart);
            var orderSummary = `<ul class="clearfix" id="order_record">`;
            var variation_price = 0;
            $.each(cart[id], function(index, item) {
                // console.log(item);
                // to avoid duplication in UI
                if ($('#' + id + index).length > 0) {
                    // Element with ID already exists, no action needed
                } else {
                    var add_ons_price = 0; // Reset add_ons_price for each item

                    $.each(item.add_ons, function(keinexy, add_on) {
                        isNaN(add_on.price) ? add_ons_price += 0 : add_ons_price += add_on.price;
                    });
                    total_price = 0;

                    variation_price = isNaN(item.variations.price) ? 0 : item.variations.price;

                    total_price = parseFloat(add_ons_price) + parseFloat(variation_price);
                    // total_price = parseFloat(add_ons_price) + parseFloat(variation_price) + parseFloat(item.product_price);
                    // Update sub_total and construct order summary HTML
                    sub_total += total_price;

                    orderSummary +=
                        `<li onclick="removeFromCart(${id},${index},${total_price})" id="${id}${index}"><a href="#0" >1x  ${item.product_name}</a><span>$${total_price}</span></li>`;
                }
            });

            orderSummary += `</ul>`;

            // Update sub_total and total elements in the UI
            $('#sub_total').text("Rs." + sub_total);
            $('#total_amount_second_cart').text("Rs." + sub_total);
            $('#total_second_cart').text("Rs." + sub_total);
            // $('#total').text("$" + sub_total);

            // Prepend order summary to the designated element
            $("#order_summary").prepend(orderSummary);


            // second cart show



            var cart_products;
            // if ($('#second_cart_' + id + i).length > 0) {
            //     // Element with ID already exists, no action needed
            //    }else{
            $.each(cart[id], function(i, item) {
                if ($('#second_cart_' + id + i).length > 0) {
                    // Element with ID already exists, no action needed
                } else {
                    cart_products += `
                    <tr id="second_cart_${item.product_id}${i}">
                        <td class="d-md-flex align-items-center">
                            <figure>
                                <a href="#" title="Photo title" data-effect="mfp-zoom-in">
                                    <img src="{{ asset('storage/app/public/product') }}/${item.image}"
                                        data-src="{{ asset('storage/app/public/product') }}/${item.image}"
                                        alt="thumb" class="lazy"
                                        onerror="this.src='{{ asset('placeholder.png') }}'"></a>
                            </figure>
                            <div class="flex-md-column">
                                <h4>${item.product_name}</h4>`;

                    if (item.variations.type != undefined) {
                        cart_products += `
                        <p>
                            variant ${item.variations.type}
                        </p>`;
                    }

                    cart_products += `
                        </div>
                    </td>
                    <td>
                        <strong>${total_price}</strong>
                    </td>
                    <td class="options">
                        <i onclick="removeFromCart(${item.product_id},${i},${total_price})" class="icon_minus_alt2"></i>
                    </td>
                </tr>`;
                    $('#second_cart_product_show').prepend(cart_products);
                }
                // delivery_method_change();
            });

            // $('#second_cart_product_show').empty();
            // }
        }

        function removeFromCart(id, index, total_price) {
            // if (cart[id][index]) {
            // console.log('cart[id][index] ', cart[id][index]);
            cart[id].splice(index, 1);
            sub_total = sub_total - total_price;
            $('#' + id + index).remove();
            $('#second_cart_' + id + index).remove();
            $('#sub_total').text("Rs." + sub_total);
            $('#total_amount_second_cart').text("Rs." + sub_total);
            $('#total_second_cart').text("Rs." + sub_total);
            // $('#total').text("Rs." + sub_total);
            // }
            // updateUI(id,sub_total);
            console.log('remove from cart ');
            console.log(cart);
        }



        // show second things
        $('#check_out_detail').hide();

        $('#check_out_btn').click(function() {
            // console.log(cart[id]);
            var user = `{{ Auth()->check() }}`;
            if (!user) {
                location.href = `{{ route('user.login') }}`;
                alert('Login First')
                return;
            }
            if (Object.keys(cart).length !== 0) {
                $('#product_cart_detail').hide();
                $('#check_out_detail').show();
            } else {
                alert('first add to cart products');
            }

            // var allItems = [];
            // console.log('cart before for loop', cart);
            // for (var id in cart) {
            //     if (cart.hasOwnProperty(id)) {
            //         allItems = allItems.concat(cart[id]);
            //     }
            // }

            // var cart_products = "";
            // var varations = 0;
            // console.log('all items ', allItems);
            // allItems.forEach(function(item, index) {
            //     console.log(item);
            //     var add_ons_price = 0;
            //     item.add_ons.forEach(function(add_on) {
            //         isNaN(add_on.price) ? add_ons_price += 0 : add_ons_price += add_on.price;
            //     })
            //     variation = isNaN(item.variations.price) ? 0 : item.variations.price;
            //     var product_total = variation + add_ons_price + item.product_price;

            //     cart_products += `
        // <tr id="second_cart_${item.product_id}${index}">
        //     <td class="d-md-flex align-items-center">
        //         <figure>
        //             <a href="#" title="Photo title" data-effect="mfp-zoom-in">
        //                 <img src="https://foodie.junaidali.tk/storage/app/public/product/${item.image}"
        //                     data-src="https://foodie.junaidali.tk/storage/app/public/product/${item.image}"
        //                     alt="thumb" class="lazy"
        //                     onerror="this.src='{{ asset('placeholder.png') }}'"></a>
        //         </figure>
        //         <div class="flex-md-column">
        //             <h4>${item.product_name}</h4>`;
            //     if (item.variations.type != undefined) {
            //         cart_products += `
        //             <p>
        //                 variant ${item.variations.type}
        //             </p>`;
            //     }

            //     cart_products += `
        //         </div>
        //     </td>
        //     <td>
        //         <strong>${product_total}</strong>
        //     </td>
        //     <td class="options">
        //          <i onclick="removeFromCart(${item.product_id},${index},${product_total})" class="icon_minus_alt2"></i>
        //     </td>
        // </tr>`;
            // });

            // $('#second_cart_product_show').empty();
            // $('#second_cart_product_show').append(cart_products);
        });


        // function showCartInDetail() {
        //     var allItems = [];

        //     for (var id in cart) {
        //         if (cart.hasOwnProperty(id)) {
        //             allItems = allItems.concat(cart[id]);
        //         }
        //     }

        //     allItems.forEach(function(item) {
        //         // console.log(item);
        //         console.log(item.product_name, item.product_price);

        //         // Add your code here to display the item in the cart
        //     });
        // }


        $('#go_back_to_products').click(function() {
            $('#check_out_detail').hide();
            $('#product_cart_detail').show();
            var selectedValue = $('input[name=delivery_method]:checked').val();
            if (selectedValue == 'delivery') {
                $("'input[name=delivery_method]:checked'").prop('checked', false);
            }
        });
        // function updateUI(id,sub_total) {
        //     // sub_total = sub_total - total_price; // Deduct removed item's total_price
        //     // alert(sub_total);
        //     // alert(cart[id]);
        //     // console.log(sub_total);

        //     // Clear the previous order summary
        //     $("#order_record").empty();

        //     console.log(cart);
        //      orderSummary = `<ul class="clearfix" id="order_record">`;
        //      variation_price = 0;
        //     $.each(cart[id], function(index, item) {
        //         // to avoid duplication in UI
        //         if ($('#' + id + index).length > 0) {
        //             // Element with ID already exists, no action needed
        //         } else {
        //              add_ons_price = 0; // Reset add_ons_price for each item

        //             $.each(item.add_ons, function(keinexy, add_on) {
        //                 isNaN(add_on.price) ? add_ons_price += 0 : add_ons_price += add_on.price;
        //             });
        //             console.log('show order add ons pric   >  > ', add_ons_price);
        //             total_price = 0;

        //             console.log('show order iem price variation    >  > ', item.variations.price);

        //             variation_price = isNaN(item.variations.price) ? 0 : item.variations.price;
        //             console.log('show order variation price   >  > ', variation_price);

        //             total_price = parseFloat(add_ons_price) + parseFloat(variation_price) + parseFloat(item
        //                 .product_price);
        //             // Update sub_total and construct order summary HTML
        //             sub_total = sub_total;

        //             orderSummary +=
        //                 `<li onclick="removeFromCart(${id},${index},${total_price})" id="${id}${index}"><a href="#0" >1x  ${item.product_name}</a><span>$${total_price}</span></li>`;
        //         }
        //     });

        //     orderSummary += `</ul>`;


        //     // Update sub_total and total elements in the UI
        //     $('#sub_total').text("$" + sub_total);
        //     $('#total').text("$" + sub_total);

        //     // Prepend order summary to the designated element
        //     $("#order_summary").prepend(orderSummary);
        // }

        $(document).ready(function() {
            // $('input[name=delivery_method]:checked').trigger('change');

            $('input[name=delivery_method]').change(function() {
                var selectedValue = $('input[name=delivery_method]:checked').val();
                var delivery_charges_total = 0;
                if (selectedValue == 'delivery') {

                    if ("geolocation" in navigator) {
                        var navi = navigator.geolocation.getCurrentPosition(function(position) {
                            var user_latitude = position.coords.latitude;
                            var user_longitude = position.coords.longitude;
                            // alert( latitude + ", " + longitude);
                            var restaurant_lat = `{{ $restaurent['response']['latitude'] }}`;
                            var restaurant_long = `{{ $restaurent['response']['longitude'] }}`;
                            var distance = calculateDistance(restaurant_lat, restaurant_long,
                                user_latitude, user_longitude);
                            console.log('Distance: ' + distance + ' km');
                            var restaurant_id = `{{ $restaurent['response']['id'] }}`;
                            var data = {
                                'business_id': `{{ $restaurent['response']['id'] }}`,
                                'distance': distance,
                            }

                            $.ajax({
                                type: "post",
                                url: "{{ url('api/v1/order_shipping_charges') }}",
                                data: data,
                                // dataType: "dataType",
                                success: function(response) {
                                    console.log(response);
                                    console.log(response.response.charges)
                                    delivery_charges_total += parseFloat(response
                                        .response.charges);

                                    var total = delivery_charges_total + parseFloat(
                                        sub_total);
                                    $('#delivery_charges_show').text('Rs. ' +
                                        response.response.charges);
                                    console.log('total', total);
                                    $('#total_amount_second_cart').text("Rs." +
                                        total);
                                },
                                error: function(error) {
                                    console.error(error);
                                }

                            });
                        });
                    } else {
                        alert("Geolocation is not available. Allow location");
                    }
                } else {
                    $('#delivery_charges_show').text('Rs. 0');
                    console.log(delivery_charges_total);
                    console.log(typeof(delivery_charges_total));
                    console.log(sub_total);
                    $('#total_amount_second_cart').text("Rs." + sub_total);
                }
            });


        });

        function calculateDistance(lat1, lon1, lat2, lon2) {
            var R = 6371; // Radius of the Earth in kilometers
            var dLat = (lat2 - lat1) * (Math.PI / 180);
            var dLon = (lon2 - lon1) * (Math.PI / 180);
            var a =
                Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                Math.cos(lat1 * (Math.PI / 180)) * Math.cos(lat2 * (Math.PI / 180)) *
                Math.sin(dLon / 2) * Math.sin(dLon / 2);
            var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            var distance = R * c; // Distance in kilometers

            return distance;
        }
        $('#place_order').click(function() {
            var selectedDeliveryMethod = $('input[name=delivery_method]:checked').val();
            if (!$('input[name="delivery_method"]').is(':checked')) {
                alert('Please select delivery Method');
                return;
            }
            var selectedPayment = $('input[name=payment]:checked').val();
            if (!$('input[name="payment"]').is(':checked')) {
                alert('Please select Payment Method');
                return;
            }
            if ("geolocation" in navigator) {
                var allItems = [];

                for (var id in cart) {
                    if (cart.hasOwnProperty(id)) {
                        allItems = allItems.concat(cart[id]);
                    }
                }
                var newArray = [];
                
                allItems.forEach(function(item) {
                    console.log(item.add_ons);
                    var add_on_ids = [];
                    var add_on_qty = [];
                    $.each(item.add_ons, function(indexInArray, ad_on) {
                        console.log(ad_on);
                        var id = ad_on.id;
                        add_on_ids.push(id);
                        add_on_qty.push(1);
                    });

                    var variation = '';
                    if (item.variations.type != undefined) {
                        variation = item.variations;
                    }

                    var item = {
                        "food_id": item.product_id,
                        "variation": [variation],
                        "add_on_ids": add_on_ids,
                        "add_on_qtys": add_on_qty,
                        "quantity": "1",
                        "variant": item.variations.type
                    };
                    newArray.push(item);
                });

                var navi = navigator.geolocation.getCurrentPosition(function(position) {
                    var user_latitude = position.coords.latitude;
                    var user_longitude = position.coords.longitude;
                    // alert( latitude + ", " + longitude);
                    var restaurant_lat = `{{ $restaurent['response']['latitude'] }}`;
                    var restaurant_long = `{{ $restaurent['response']['longitude'] }}`;
                    var distance = calculateDistance(restaurant_lat, restaurant_long,
                        user_latitude, user_longitude);
                    console.log('Distance: ' + distance + ' km');

                    async function calculateOrderAmount() {
                        var order_amount = sub_total;

                        if (selectedDeliveryMethod == 'delivery') {
                            var data = {
                                'business_id': `{{ $restaurent['response']['id'] }}`,
                                'distance': distance,
                            };

                            try {
                                var response = await $.ajax({
                                    type: "post",
                                    url: "{{ url('api/v1/order_shipping_charges') }}",
                                    data: data,
                                });

                                console.log(response);
                                console.log(response.response.charges);
                                order_amount = order_amount + parseFloat(response.response.charges);

                                return order_amount; // Return the updated order_amount
                            } catch (error) {
                                console.error(error);
                                return null; // Handle error case
                            }
                        }

                        return order_amount; // Return the original order_amount if not in 'delivery' method
                    }

                    calculateOrderAmount().then(finalAmount => {
                        console.log("Final order_amount:", finalAmount);

                        var data = {
                            'order_amount': finalAmount,
                            'payment_method': selectedPayment,
                            'address': 'Gulraiz Town Multan Punjab',
                            'order_type': selectedDeliveryMethod,
                            'restaurant_id': `{{ $restaurent['response']['id'] }}`,
                            'distance': distance,
                            'latitude': user_latitude,
                            'longitude': user_longitude,
                            'tax': `{{ $restaurent['response']['tax'] }}`,
                            'cart': newArray,
                            'deals':[],
                        }
                        console.log(data);
                        // return 0;
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                'Authorization': "Bearer {{ Session::get('token') }}",
                            }
                        });
                        $.ajax({
                            type: "post",
                            url: "{{ url('api/v1/customer/order/place') }}",
                            data: data,
                            dataType: 'json',
                            success: function(response) {
                                console.log(response);
                                alert(response.message);
                                // Refresh the page
                                location.reload();
                            },
                            error: function(errors) {
                                console.error(errors);
                                console.log(errors.responseJSON.response.errors[0].message);
                                alert(errors.responseJSON.response.errors[0].message);
                            }
                        });
                    });
                });

                console.log('cart in placeorder ', cart);
                console.log('sub total in placeorder  ', sub_total);
            } else {
                alert('Geolocation is not available. Allow location');
            }
        });
    </script>
@endsection

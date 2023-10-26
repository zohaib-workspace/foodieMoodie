@extends('layouts.home.app')
@section('title', ' home')
@Section('main_content')

    <main>
        <style>
            @media (max-width: 767.98px) {
                .border-sm-start-none {
                    border-left: none !important;
                }
            }
        </style>

        <div class="hero_in detail_page background-image"
            data-background="url({{ asset('storage/app/public/restaurant/cover/' . $restaurent['response']['cover_photo']) }})">
            <div class="wrapper opacity-mask" data-opacity-mask="rgba(0, 0, 0, 0.5)">
                <div class="container">
                    <div class="main_info">
                        <div class="row">
                            <div class="col-xl-4 col-lg-5 col-md-6">
                                <div class="head">
                                    <div class="score">
                                        <img src='{{ asset('storage/app/public/restaurant/' . $restaurent['response']['logo']) }}'
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
                        {{-- start deals --}}
                        {{-- <div class="row "> --}}
                        <h4>Special Deals</h4>
                        @forelse($restaurent['response']['special_deals'] as $deals)
                            <div class="col-md-12">
                                <div class="card shadow-0 border rounded-3">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12 col-lg-3 col-xl-3 mb-4 mb-lg-0">
                                                <div class="bg-image hover-zoom ripple rounded ripple-surface">
                                                    <img src="{{ asset('storage/app/public/product/' . $deals['image']) }}"
                                                        class="rounded" width="100" height="100" />
                                                    <a href="#!">
                                                        <div class="hover-overlay">
                                                            <div class="mask"
                                                                style="background-color: rgba(253, 253, 253, 0.15);"></div>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-lg-6 col-xl-6">
                                                <h5>{{ $deals['title'] }}</h5>

                                                {{-- <div class="mt-1 mb-0 text-muted small">
                                            <span>100% cotton</span>
                                            <span class="text-primary"> • </span>
                                            <span>Light weight</span>
                                            <span class="text-primary"> • </span>
                                            <span>Best finish<br /></span>
                                        </div>
                                        <div class="mb-2 text-muted small">
                                            <span>Unique design</span>
                                            <span class="text-primary"> • </span>
                                            <span>For men</span>
                                            <span class="text-primary"> • </span>
                                            <span>Casual<br /></span>
                                        </div> --}}
                                                <p class=" my-1 mb-md-0">
                                                    {{ $deals['description'] }}
                                                </p>
                                            </div>
                                            <div class="col-md-6 col-lg-3 col-xl-3 border-sm-start-none border-start">
                                                <div class="d-flex flex-row align-items-center mb-1">
                                                    <h4 class="mb-1 me-1">Rs. {{ $deals['price'] }}</h4>
                                                </div>
                                                {{-- <h6 class="text-success">Free shipping</h6> --}}
                                                <div class="d-flex flex-column mt-4">
                                                    <button class="btn btn-teal btn-sm" type="button"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#dealsModel{{ $deals['id'] }}">Add to
                                                        Cart</button>
                                                    {{-- <button class="btn btn-outline-primary btn-sm mt-2" type="button">
                                                Add to Cart
                                            </button> --}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- start deals modal --}}
                            <div class="modal fade" style="z-index:50000;" id="dealsModel{{ $deals['id'] }}"
                                data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                                aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="staticBackdropLabel">Deal Comment</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-12">
                                                    <label for="comment{{ $deals['id'] }}">Comment</label>
                                                    <textarea class="form-control" id="comment{{ $deals['id'] }}" rows="4" style="height:80%;"
                                                        placeholder="Write your comment here"></textarea>
                                                </div>
                                                <div class="col-md-4 col-7 mt-2">
                                                    <label for="quantity{{ $deals['id'] }}">Quantity</label>
                                                    <div class="input-group ">
                                                        <span class="input-group-btn">
                                                            <button type="button"
                                                                class="btn btn-secondary rounded-circle  btn-number"
                                                                data-type="minus" data-field="quantity{{ $deals['id'] }}">
                                                                <span class="fa fa-minus"></span>
                                                            </button>
                                                        </span>
                                                        <input type="number" name="quantity{{ $deals['id'] }}"
                                                            class="form-control border-none input-number quantity"
                                                            style="border:none;box-shadow:none;padding-left:29px"
                                                            value="1" min="1" max="10">
                                                        <span class="input-group-btn p-0">
                                                            <button type="button"
                                                                class="btn btn-teal btn-number rounded-circle"
                                                                data-type="plus" data-field="quantity{{ $deals['id'] }}">
                                                                <span class="fa fa-plus"></span>
                                                            </button>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Close</button>
                                            <button type="button" class="btn btn-orange"
                                                onclick="add_to_deals({{ $deals['id'] }},'{{ $deals['title'] }}','{{ $deals['image'] }}',{{ $deals['price'] }});">Add
                                                to Cart</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- end deals modal --}}
                        @empty
                            <div class="col-md-12 text-center">Deals not found</div>
                        @endforelse


                        {{-- </div> --}}
                        {{-- /end deals --}}

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
                                    <li class="total">Subtotal<span id="sub_total">Rs. 0</span></li>
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
                                    <button href="#" class="btn_1 gradient full-width mb_5"
                                        id="check_out_btn">Check
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
                <div class="row my-3" id="check_out_detail">
                    <div class="col-md-12">
                        <button class="btn btn-teal" id="go_back_to_products">Go back!</button>

                    </div>
                    {{-- <div class="col-md-3">lkasdj dk</div> --}}

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
                                    <li class="total">Discount<span id="coupon_discount">Rs. 0</span></li>
                                    <li class="total">Total Amount<span id="total_amount_second_cart">Rs. 0</span></li>

                                </ul>
                                <ul class="clearfix" id="">
                                    <li class="h6"><i class="fa-solid fa-location-dot"></i> Delivery Address<span
                                            id=""> <button type="button" id="update_address_btn"
                                                class="btn text-info m-0 p-0"><u>Update Address</u></button></span></li>
                                    <li class="" id="selected_address">{{ session()->get('searched_name') ?? '' }}
                                    </li>
                                    <li id="update_address">
                                        <div class="input-group">
                                            <input type="text" id="autocomplete" class="form-control"
                                                placeholder="Enter address" aria-label="location"
                                                aria-describedby="updateLocation">
                                            <button class="btn btn-orange" type="button"
                                                id="updateLocation">Update</button>
                                            <button class="btn" id="close_btn"><i
                                                    class="fa-solid fa-xmark"></i></button>
                                        </div>

                                    </li>

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
                                <div class="border-top">
                                    <div class="col-12 my-1 text-orange">
                                        <i class="fa-solid fa-gift fs-6 "></i>
                                        <button class="btn px-0 text-orange border-white" id="apply_a_voucher">Apply a
                                            voucher</button>
                                        <div class="input-group " id="voucher_section">
                                            <input type="text" id="voucher_code_input" class="form-control"
                                                placeholder="Enter Coupon" aria-label="location"
                                                aria-describedby="apply-voucher">
                                            <button class="btn btn-teal" type="button"
                                                id="submit-voucher">Apply</button>
                                            <button class="btn" id="close_voucher_btn"><i
                                                    class="fa-solid fa-xmark"></i></button>
                                        </div>
                                    </div>

                                </div>



                                <button class="btn_1 gradient full-width mb_5" id="place_order">Place Order</button>
                                <div id="success-message" class="alert alert-success" style="display: none;"></div>
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
                        {{-- <div class="row add_bottom_30 d-flex align-items-center reviews">
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
                        </div> --}}
                        <!-- /row -->
                        <div id="reviews">
                            @foreach ($reviews as $key => $review)
                                <div class="review_card">
                                    <div class="row">
                                        <div class="col-md-2 user_info">
                                            <figure><img src="{{ asset('public/home_assets/img/avatar4.jpg') }}"
                                                    alt=""></figure>
                                            <h5>{{ $review->user->f_name ?? '' }} {{ $review->user->l_name ?? '' }}</h5>
                                        </div>
                                        <div class="col-md-10 review_content">
                                            <div class="clearfix add_bottom_15">
                                                <span class="rating">{{ $review->rating }}<small>/5</small> <strong>Rating
                                                    </strong></span>
                                                <em>Published {{ $review->created_at->diffForHumans() }} </em>
                                            </div>
                                            {{-- <h4>"Great Location!!"</h4> --}}
                                            <p>{{ $review->comment }}</p>
                                            {{-- <ul>
                                        <li><a href="#0"><i class="icon_like"></i><span>Useful</span></a></li>
                                        <li><a href="#0"><i class="icon_dislike"></i><span>Not useful</span></a>
                                        </li>
                                        <li><a href="#0"><i class="arrow_back"></i> <span>Reply</span></a></li>
                                    </ul> --}}
                                        </div>
                                    </div>
                                    <!-- /row -->
                                </div>
                            @endforeach
                            <!-- /review_card -->
                            {{-- <div class="review_card">
                            <div class="row">
                                <div class="col-md-2 user_info">
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
                                    <figure><img src="{{ asset('public/home_assets/img/avatar.jpg') }}" alt=""></figure>
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
                        </div> --}}
                            <!-- /review_card -->
                        </div>
                        <!-- /reviews -->
                        {{-- <div class="text-end"><a href="leave-review.html" class="btn_1 gradient">Leave a Review</a></div> --}}
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
        function displaySuccessMessage(message) {

            const successMessage = document.getElementById('success-message');
            if (message == '') {
                successMessage.style.display = 'none';
            } else {
                successMessage.textContent = message;
                successMessage.style.display = 'block';
            }
        }

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
                    if(response_data.response.variations)
                    {
                        $.each(response_data.response.variations, function(index, item) {
                            // console.log(item);
                            $('input[name="myRadio"]').first().prop('checked', true);
                            dropdownHtml += `
                            <li>
                                <label class="container_radio">${item.type}<small> +Rs ${item.price}</small>
                                    <input type="radio" checked class="variations" name="vari"   data-price="${item.price}" data-type="${item.type}" >
                                    <span class="checkmark"></span>
                                </label>
                                
                            </li>`;
                        });

                    }else{
                        dropdownHtml+='Variation not available.';
                    }
                    // loop end

                    dropdownHtml += `  </ul>
                    <h5>Add ons</h5>
                    <ul class="clearfix">  `;
                    if(response_data.response.add_ons)
                    {
                    $.each(response_data.response.add_ons, function(index, item) {
                        // console.log(item);

                        dropdownHtml += `
                    <li>
                        <label class="container_check"> ${item.name} <small>+Rs ${item.price}</small>
                            <input type="checkbox" id="add_ons_${response_data.response.id}" class="add_ons" value="${item.name}" data-id="${item.id}" data-price="${item.price}" data-name="${item.name}">
                            <span class="checkmark"></span>                            
                        </label>
                    </li>`;
                    });
                    }
                    if(response_data.response.variations)
                    {

                    
                    dropdownHtml +=
                        `</ul>
                    <a href="#0" class="btn_1" onclick="add_to_cart_btn(${response_data.response.id},${response_data.response.price},'${response_data.response.name}',${response_data.response.restaurant_id},'${response_data.response.image}')">Add to cart</a> `;
                    }
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
        var coupon_discount = 0;
        var delivery_charges_total = 0;

        function orderShow(id) {
            console.log('cart to show ', cart);
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
                        `<li  id="${id}${index}"><a href="javascript:;" onclick="removeFromCart(${id},${index},${total_price},'cart')" ></a>
                            1x  ${item.product_name}
                            <br>
                            <small>variation: <div class="badge bg-teal">${item.variations.type}</div> add ons: `;
                    $.each(item.add_ons, function(keinexy, add_on) {
                        orderSummary += `<div class="badge bg-orange px-1">${add_on.name} </div>`;
                    });

                    orderSummary += `</small> <span>Rs.${total_price}</span>
                        </li>`;
                }
            });

            orderSummary += `</ul>`;

            // Update sub_total and total elements in the UI
            total = sub_total+delivery_charges_total-coupon_discount;
            console.log(coupon_discount);
            $('#sub_total').text("Rs." + sub_total);
            $('#total_amount_second_cart').text("Rs." + total);
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
                            variant <small class="badge bg-teal">${item.variations.type}</small>
                        </p>
                        `;
                    }
                    if (item.add_ons) {

                        cart_products += ` <p> Add on : `;

                        $.each(item.add_ons, function(indexInArray, add_on) {
                            cart_products += `<small class="badge bg-orange">${add_on.name}</small>`;
                        });
                        cart_products += '</p>';

                    }
                    cart_products += `
                        </div>
                    </td>
                    <td>
                        <strong>${total_price}</strong>
                    </td>
                    <td class="options">
                        <i onclick="removeFromCart(${item.product_id},${i},${total_price},'cart')" class="icon_minus_alt2"></i>
                    </td>
                </tr>`;
                    $('#second_cart_product_show').prepend(cart_products);
                }
                // delivery_method_change();
            });

            // $('#second_cart_product_show').empty();
            // }
        }

        //start deals add to cart
        var deals = {};

        function add_to_deals(deal_id, deal_title, image, price) {
            var quantity = $('.quantity').val();
            var comment = $(`#comment${deal_id}`).val();
            var total_price = price * quantity;
            selected_value = {
                "deal_id": deal_id,
                "title": deal_title,
                "image": image,
                "quantity": quantity,
                "total_price": price,
                "double_price": total_price,
                "comment": comment,
                "tax_amount": 0,
                "required_products": [],
                "optional_products": []
            }
            $('.modal').modal('hide');
            $('.quantity').val(1);
            $(`#comment${deal_id}`).val('');
            // If the deals ID is not in the deals, create an empty array for it
            if (!deals[deal_id]) {
                deals[deal_id] = [];
            }

            // Add the selected item to the deals
            deals[deal_id].push(selected_value);
            console.log(deals);
            dealsOrderShow(deal_id);
        }

        function dealsOrderShow(id) {
            console.log('deals  to show ', deals);
            var orderSummary = `<ul class="clearfix" id="order_record">`;
            var variation_price = 0;
            $.each(deals[id], function(index, item) {
                // console.log(item);
                // to avoid duplication in UI
                if ($('#deal' + id + index).length > 0) {
                    // Element with ID already exists, no action needed
                } else {
                    // Update sub_total and construct order summary HTML
                    sub_total += item.double_price;

                    orderSummary +=
                        `<li onclick="removeFromCart(${id},${index},${item.double_price},'deals')" id="deal${id}${index}"><a href="javascript:;" ></a>${item.quantity}x   ${item.title}<span>$${item.double_price}</span></li>`;
                }
            });

            orderSummary += `</ul>`;

            // Update sub_total and total elements in the UI
            $('#sub_total').text("Rs." + sub_total);
            var total= sub_total-parseFloat(coupon_discount)+parseFloat(delivery_charges_total)
            $('#total_amount_second_cart').text("Rs." +total);
            $('#total_second_cart').text("Rs." +sub_total);
            // $('#total').text("$" + sub_total);

            // Prepend order summary to the designated element
            $("#order_summary").prepend(orderSummary);


            // second cart show



            var cart_products;
            // if ($('#second_cart_' + id + i).length > 0) {
            //     // Element with ID already exists, no action needed
            //    }else{
            $.each(deals[id], function(i, item) {
                if ($('#second_deal_' + id + i).length > 0) {
                    // Element with ID already exists, no action needed
                } else {
                    cart_products += `
                    <tr id="second_deal_${item.deal_id}${i}">
                        <td class="d-md-flex align-items-center">
                            <figure>
                                <a href="#" title="Photo title" data-effect="mfp-zoom-in">
                                    <img src="{{ asset('storage/app/public/product') }}/${item.image}"
                                        data-src="{{ asset('storage/app/public/product') }}/${item.image}"
                                        alt="thumb" class="lazy"
                                        onerror="this.src='{{ asset('placeholder.png') }}'"></a>
                            </figure>
                            <div class="flex-md-column">
                                <h4>${item.title}</h4>`;



                    cart_products += `
                        </div>
                    </td>
                    <td>
                        <strong>${item.double_price}</strong>
                    </td>
                    <td class="options">
                    
                        <i onclick="removeFromCart(${item.deal_id},${i},${item.double_price},'deals')" class="icon_minus_alt2"></i>
                    </td>
                </tr>`;
                    $('#second_cart_product_show').prepend(cart_products);
                }
            });
        }
        //end deals add to cart

        function removeFromCart(id, index, total_price, isDealOrCart = '') {
            // if (cart[id][index]) {
            // console.log('cart[id][index] ', cart[id][index]);
            if (isDealOrCart == 'deals') {
                //return console.log(deal);
                deals[id].splice(index, 1);
                $('#deal' + id + index).remove();
                $('#second_deal_' + id + index).remove();
                console.log('remove from deals ');
            } else {

                cart[id].splice(index, 1);
                $('#' + id + index).remove();
                $('#second_cart_' + id + index).remove();
                console.log('remove from cart ');
            }
            //return console.log(error);
            sub_total = sub_total - total_price;
            $('#sub_total').text("Rs." + sub_total);
            var total=sub_total+delivery_charges_total-coupon_discount
            $('#total_amount_second_cart').text("Rs." + total);
            $('#total_second_cart').text("Rs." + sub_total);
            // $('#total').text("Rs." + sub_total);
            // }
            // updateUI(id,sub_total);
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
            if (Object.keys(cart).length !== 0 || Object.keys(deals).length !== 0) {
                $('#product_cart_detail').hide();
                $('#check_out_detail').show();
            } else {
                alert('Cart is empty!');
            }


        });


        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                    'content'),
                'Authorization': "Bearer {{ Session::get('token') }}",
                contentType: "application/json",
                'ZoneId': `[{{ session()->get('s_zone_id') }}]`,
            }
        });
        var s_lat = `{{ session()->get('lat') }}`;
        var s_lng = `{{ session()->get('lng') }}`;
        var s_zone_id = `{{ session()->get('s_zone_id') }}`;
        $(document).ready(function() {
            // $('input[name=delivery_method]:checked').trigger('change');

            $('input[name=delivery_method]').change(function() {
                var selectedValue = $('input[name=delivery_method]:checked').val();
                 delivery_charges_total = 0;
                if (selectedValue == 'delivery') {

                    if ("geolocation" in navigator) {
                        var navi = navigator.geolocation.getCurrentPosition(function(position) {
                            var user_latitude = position.coords.latitude;
                            var user_longitude = position.coords.longitude;
                            // alert( latitude + ", " + longitude);
                            var restaurant_lat = `{{ $restaurent['response']['latitude'] }}`;
                            var restaurant_long = `{{ $restaurent['response']['longitude'] }}`;
                            if (s_lat && s_lng) {
                                var distance = calculateDistance(restaurant_lat, restaurant_long,
                                    s_lat, s_lng);

                            } else {
                                var distance = calculateDistance(restaurant_lat, restaurant_long,
                                    s_lat ?? user_latitude, s_lng ?? user_longitude);

                            }
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
                                    // console.log(response);
                                    // console.log(response.response.charges)
                                    delivery_charges_total += parseFloat(response.response.charges);
                                    console.log('coupon code inside delivery charges' + coupon_discount);
                                    var total = delivery_charges_total + parseFloat(sub_total)-parseFloat(coupon_discount);
                                    $('#delivery_charges_show').text('Rs. ' + response
                                        .response.charges);
                                    console.log('total', total);
                                    $('#total_amount_second_cart').text("Rs." + total);
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
                    // console.log(typeof(delivery_charges_total));
                    console.log(sub_total);
                    console.log('coupon code '+coupon_discount);
                    var total=sub_total-coupon_discount;
                    $('#total_amount_second_cart').text("Rs." + total);
                }
            });


            // start plus minus qty
            $('.btn-number').click(function(e) {
                e.preventDefault();

                fieldName = $(this).attr('data-field');
                type = $(this).attr('data-type');
                var input = $("input[name='" + fieldName + "']");
                var currentVal = parseInt(input.val());

                if (!isNaN(currentVal)) {
                    if (type == 'minus') {
                        if (currentVal > input.attr('min')) {
                            input.val(currentVal - 1).change();
                        }
                        if (parseInt(input.val()) == input.attr('min')) {
                            $(this).attr('disabled', true);
                        }
                    } else if (type == 'plus') {
                        if (currentVal < input.attr('max')) {
                            input.val(currentVal + 1).change();
                        }
                        if (parseInt(input.val()) == input.attr('max')) {
                            $(this).attr('disabled', true);
                        }
                    }
                } else {
                    input.val(0);
                }
            });

            $('.input-number').focusin(function() {
                $(this).data('oldValue', $(this).val());
            });

            $('.input-number').change(function() {

                minValue = parseInt($(this).attr('min'));
                maxValue = parseInt($(this).attr('max'));
                valueCurrent = parseInt($(this).val());

                name = $(this).attr('name');
                if (valueCurrent >= minValue) {
                    $(".btn-number[data-type='minus'][data-field='" + name + "']").removeAttr('disabled')
                } else {
                    alert('Sorry, the minimum value was reached');
                    $(this).val($(this).data('oldValue'));
                }
                if (valueCurrent <= maxValue) {
                    $(".btn-number[data-type='plus'][data-field='" + name + "']").removeAttr('disabled')
                } else {
                    alert('Sorry, the maximum value was reached');
                    $(this).val($(this).data('oldValue'));
                }
            });
            // end plus minus qty

            $('#update_address_btn').click(function() {
                $('#selected_address').hide();
                $('#update_address').show();
            });
            $('#apply_a_voucher').click(function() {
                $('#voucher_section').show();
            });
            $('#voucher_section').hide();
            $('#close_voucher_btn').click(function() {
                $('#voucher_section').hide();
            });
            $('#update_address').hide();

            $('#updateLocation').click(function() {
                $('#selected_address').show();
                $('#update_address').hide();
                $('#selected_address').text($('#autocomplete').val());
                $.ajax({
                    type: "get",
                    url: "{{ url('user/session-get') }}",
                    dataType: "json",
                    success: function(response) {
                        console.log(response);
                        s_lat = response.response.lat;
                        s_lng = response.response.lng;
                        s_zone_id = response.response.s_zone_id;
                        if (s_zone_id != {{ $restaurent['response']['zone_id'] }}) {
                            return alert(
                                'Change your address this restaurant is not delivering in your selected area.'
                            );
                        }

                    },
                    error: function(error) {
                        console.log(error);
                    }
                });

            });
            $('#close_btn').click(function() {
                $('#selected_address').show();
                $('#update_address').hide();

            });
            $('#go_back_to_products').click(function() {
                $('#check_out_detail').hide();
                $('#product_cart_detail').show();

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
        var coupon_code = '';
        $('#place_order').click(function() {
            if (s_zone_id != {{ $restaurent['response']['zone_id'] }}) {
                return alert('Change your address this restaurant is not delivering in your selected area.');
            }
            // console.log(deals);
            // return alert(s_lat);
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
                var allDealsItem = [];
                for (var id in deals) {
                    if (deals.hasOwnProperty(id)) {
                        allDealsItem = allDealsItem.concat(deals[id]);
                    }
                }
                var newArray = [];

                var add_on_ids = [];
                var add_on_qty = [];
                allItems.forEach(function(item) {
                    console.log(item.add_ons);
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
                        "food_id": item.product_id || '',
                        "variation": [variation] || '',
                        "add_on_ids": add_on_ids || [],
                        "add_on_qtys": add_on_qty || [],
                        "quantity": "1",
                        "variant": item.variations.type || ''
                    };
                    newArray.push(item);
                });

                var navi = navigator.geolocation.getCurrentPosition(function(position) {
                    var user_latitude = position.coords.latitude;
                    var user_longitude = position.coords.longitude;
                    // alert( latitude + ", " + longitude);
                    var restaurant_lat = `{{ $restaurent['response']['latitude'] }}`;
                    var restaurant_long = `{{ $restaurent['response']['longitude'] }}`;
                    // var distance = calculateDistance(restaurant_lat, restaurant_long, s_lat ?? user_latitude,s_lng ?? user_longitude);
                    if (s_lat && s_lng) {
                        var distance = calculateDistance(restaurant_lat, restaurant_long, s_lat, s_lng);

                    } else {
                        var distance = calculateDistance(restaurant_lat, restaurant_long,
                            user_latitude, user_longitude);

                    }
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
                            'order_amount': finalAmount-coupon_discount,
                            'payment_method': selectedPayment,
                            'address': 'Gulraiz Town Multan Punjab',
                            'order_type': selectedDeliveryMethod,
                            'restaurant_id': `{{ $restaurent['response']['id'] }}`,
                            'distance': distance,
                            'latitude': s_lat ?? user_latitude,
                            'longitude': s_lng ?? user_longitude,
                            'tax': `{{ $restaurent['response']['tax'] }}`,
                            'cart': newArray,
                            'deals': allDealsItem,
                            'coupon_code': coupon_code,
                        }
                       return console.log(data);
                        // return 0;

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
                                //  console.log('data after fails', data); 
                                console.error(errors);
                                console.log(errors.responseJSON.response.errors[0]
                                    .message);
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
        $('#submit-voucher').click(function(e) {
            e.preventDefault();
            var voucher_input = $('#voucher_code_input').val();
            if (voucher_input == '') {
                return alert('please enter coupon before submit');
            }
            data = {
                'code': voucher_input,
                'restaurant_id': `{{ $restaurent['response']['id'] }}`,
            }

            $.ajax({
                type: "post",
                url: "{{ url('api/v1/coupon/apply') }}",
                data: data,
                dataType: "json",
                success: function(response) {
                    console.log(response);
                    if (coupon_code!=response.response.code.code) {
                        
                        if (response.response.code.discount_type == 'amount') {
                            coupon_discount = response.response.code.discount;

                            $('#coupon_discount').text('Rs. ' + coupon_discount);
                            var total=sub_total-coupon_discount+ delivery_charges_total;
                            $('#total_amount_second_cart').text("Rs." + total);
                            // $('#sub_total').text("Rs." + total);
                            
                        }else if(response.response.code.discount_type == 'percentage')
                        {
                            
                            var discountRate = response.response.code.discount/100; // 10% discount
                            var discountedPrice = sub_total * (discountRate);
                            $('#coupon_discount').text('Rs. ' + discountedPrice.toFixed(2));
                            var total=sub_total-discountedPrice+ delivery_charges_total;
                            $('#total_amount_second_cart').text("Rs." + total);

                            // $('#discountedPrice').text(discountedPrice.);
                        }
                    }
                    coupon_code = response.response.code.code;
                    displaySuccessMessage('Appllied coupon:  ' + coupon_code);
                    // alert('')
                },
                error: function(error) {
                    console.log(error);
                    coupon_code = '';
                    displaySuccessMessage('');
                    coupon_discount = 0;
                    $('#coupon_discount').text('Rs.  0');
                    var total=sub_total+delivery_charges_total;
                    $('#total_amount_second_cart').text("Rs." + total);
                    $('#sub_total').text("Rs." + sub_total);

                }
            });

        });
    </script>
@endsection

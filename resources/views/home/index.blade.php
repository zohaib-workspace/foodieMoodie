@extends('layouts.home.app')
@section('title', ' home')
@section('main_content')

    <main>
        <div class="hero_single version_1">
            <div class="opacity-mask">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-7 col-lg-8">




                            <h1>Delivery or Takeaway Food</h1>
                            <p>The best restaurants at the best price</p>
                            <form>
                                <div class="row g-0 custom-search-input">
                                    <div class="col-lg-10">
                                        <div class="form-group">
                                            <input class="form-control no_border_r" type="text" id="autocomplete"
                                                placeholder="Address, neighborhood...">
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <button class="btn_1 gradient" type="submit">Search</button>
                                    </div>
                                </div>
                                <!-- /row -->
                                <div class="search_trends">
                                    <h5>Trending:</h5>
                                    <ul>
                                        <li><a href="#">Sushi</a></li>
                                        <li><a href="#">Burger</a></li>
                                        <li><a href="#">Chinese</a></li>
                                        <li><a href="#">Pizza</a></li>
                                    </ul>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- /row -->
                </div>
            </div>
            <div class="wave hero"></div>
        </div>
        <!-- /hero_single -->

        <div class="container margin_30_60">
            <div class="main_title center">
                <span><em></em></span>
                <h2>Popular Categories</h2>
                <p>OUR MOST POPULAR CATEGORIES </p>
            </div>
            <!-- /main_title -->

            <div class="owl-carousel owl-theme categories_carousel">
                <?php
        				if(!empty($categories)){foreach($categories as $data):
        					?>
                {{-- onclick="redirectToRestaurant({{ $data['id'] }});" --}}
                <div class="item_version_2">
                    <a href="{{ route('user.home.restaurants') }}">
                        <figure>
                            <!--<span>98</span>-->
                            <img  src="{{ asset('storage/app/public/category') }}/{{ $data['image'] }}"
                                data-src="{{ asset('storage/app/public/category') }}/{{ $data['image'] }}" alt=""
                                class="owl-lazy" width="350" style="height:167px;"
                                onerror="this.src='{{ asset('/home_assets/img/cat_listing_placeholder.png') }}'">
                            <div class="info">
                                <h3>{{ $data['name'] }}</h3>
                                <!--<small>Avg price $40</small>-->
                                {{-- <p>{{ asset('storage/app/public/category') }}/{{ $data['image'] }}</p> --}}
                            </div>
                        </figure>
                    </a>
                </div>
                <?php endforeach; }?>


            </div>
            <!-- /carousel -->
        </div>
        <!-- /container -->
        <div class="bg_gray">
            <div class="container margin_60_40">
                <div class="row add_bottom_25">
                    <div class="main_title">
                        <span><em></em></span>
                        <h2>Top Rated Restaurants</h2>
                        <p>OUR TOP RATED RESTAURANT.</p>
                        <a href="{{route('user.home.restaurants')}}">View All &rarr;</a>
                    </div>
                    @foreach ($restaurants as $restaurant)
                        <div class="col-lg-6">
                            <div class="list_home">
                                <ul>
                                    <li>
                                        <a href="{{ route('user.restaurent_details', $restaurant->id) }}">
                                            <figure>
                                                <img src="{{ asset('storage/app/public/restaurant/cover') }}/{{ $restaurant['cover_photo'] }}"
                                                    data-src="{{ asset('storage/app/public/restaurant/cover') }}/{{ $restaurant['cover_photo'] }}"
                                                    alt="" class="lazy" width="350"
                                                    onerror="this.src='{{ asset('/home_assets/img/cat_listing_placeholder.png') }}'"
                                                    height="118">
                                            </figure>
                                            <div class="score"><strong>0/5</strong></div>
                                            <!--<em>{{ $restaurant['cuisine'] }}</em>-->
                                            <h3>{{ $restaurant['name'] }}</h3>
                                            <small>{{ $restaurant['address'] }}</small>
                                            <ul>
                                                <li><span class="ribbon off">{{ $restaurant['delivery_time'] }}</span>
                                                </li>
                                                <!--<li>Average price ${{ $restaurant['average_price'] }}</li>-->
                                            </ul>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- <div class="bg_gray">
            <div class="container margin_60_40">
                <div class="main_title">
                    <span><em></em></span>
                    <h2>Top Rated Restaurants</h2>
                    <p>OUR TOP RATED RESTAURANT.</p>
                    <a href="#">View All &rarr;</a>
                </div>
                <div class="row add_bottom_25">
                    @if (!empty($restaurants))
                        @for ($i = 0; $i < count($restaurants); $i += 2)
                            <div class="col-lg-6">
                                <div class="list_home">
                                    <ul>
                                        <li>
                                            <a
                                                href="{{ route('user.restaurent_details', ['id' => $restaurants[$i]['id']]) }}">
                                                <figure>
                                                    <img src="{{ asset('storage/app/public/restaurant/cover') }}/{{ $restaurants[$i]['cover_photo'] }}"
                                                        data-src="{{ asset('storage/app/public/restaurant/cover') }}/{{ $restaurants[$i]['cover_photo'] }}"
                                                        alt="" class="lazy" width="350"
                                                        onerror="this.src='{{ asset('/home_assets/img/cat_listing_placeholder.png') }}'"
                                                        height="118">
                                                </figure>
                                                <div class="score"><strong>0/5</strong></div>
                                                <!--<em>{{ $restaurants[$i]['cuisine'] }}</em>-->
                                                <h3>{{ $restaurants[$i]['name'] }}</h3>
                                                <small>{{ $restaurants[$i]['address'] }}</small>
                                                <ul>
                                                    <li><span
                                                            class="ribbon off">{{ $restaurants[$i]['delivery_time'] }}</span>
                                                    </li>
                                                    <!--<li>Average price ${{ $restaurants[$i]['average_price'] }}</li>-->
                                                </ul>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            @if ($i + 1 < count($restaurants))
                                <div class="col-lg-6">
                                    <div class="list_home">
                                        <ul>
                                            <li>
                                                <a href="#">
                                                    <figure>
                                                        <img src="{{ asset('storage/app/public/restaurant/cover') }}/{{ $restaurants[$i + 1]['cover_photo'] }}"
                                                            data-src="{{ asset('storage/app/public/restaurant/cover') }}/{{ $restaurants[$i + 1]['cover_photo'] }}"
                                                            alt="" class="lazy" width="350"
                                                            onerror="this.src='{{ asset('/home_assets/img/cat_listing_placeholder.png') }}'"
                                                            height="118">
                                                    </figure>
                                                    <div class="score"><strong>0/5</strong></div>

                                                    <h3>{{ $restaurants[$i + 1]['name'] }}</h3>
                                                    <small>{{ $restaurants[$i + 1]['address'] }}</small>
                                                    <ul>
                                                        <li><span
                                                                class="ribbon off">{{ $restaurants[$i + 1]['delivery_time'] }}</span>
                                                        </li>
                                                        <!--<li>Average price ${{ $restaurants[$i + 1]['average_price'] }}</li>-->
                                                    </ul>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            @endif
                        @endfor
                    @endif
                  
                </div>

                <!-- /row -->
                <div class="banner lazy" data-bg="url({{ asset('public/home_assets/img/banner_bg_desktop.jpg') }})">
                    <div class="wrapper d-flex align-items-center opacity-mask" data-opacity-mask="rgba(0, 0, 0, 0.3)">
                        <div>
                            <small>FooYes Delivery</small>
                            <h3>We Deliver to your Office</h3>
                            <p>Enjoy a tasty food in minutes!</p>
                            <a href="#" class="btn_1 gradient">Start Now!</a>
                        </div>
                    </div>
                    <!-- /wrapper -->
                </div>
                <!-- /banner -->
            </div>
        </div> --}}
            <!-- /bg_gray -->




            <div class="container">
                <div class="main_title">
                    <span><em></em></span>
                    <h2>Popular Food</h2>
                    {{-- <p>OUR TOP RATED RESTAURANT.</p> --}}
                    {{-- <a href="#">View All &rarr;</a> --}}
                </div>
                <div id="popularFood">

                </div>
            </div>






            <div class="shape_element_2">
                <div class="container margin_60_0">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="box_how">
                                        <figure><img
                                                src="{{ asset('/home_assets/img/lazy-placeholder-100-100-white.png') }}"
                                                data-src="{{ asset('/home_assets/img/how_1.svg') }}" alt=""
                                                width="150" height="167" class="lazy"></figure>
                                        <h3>Easly Order</h3>
                                        <p>Faucibus ante, in porttitor tellus blandit et. Phasellus tincidunt metus lectus
                                            sollicitudin.</p>
                                    </div>
                                    <div class="box_how">
                                        <figure><img
                                                src="{{ asset('/home_assets/img/lazy-placeholder-100-100-white.png') }}"
                                                data-src="{{ asset('/home_assets/img/how_2.svg') }}" alt=""
                                                width="130" height="145" class="lazy"></figure>
                                        <h3>Quick Delivery</h3>
                                        <p>Maecenas pulvinar, risus in facilisis dignissim, quam nisi hendrerit nulla, id
                                            vestibulum.</p>
                                    </div>
                                </div>
                                <div class="col-lg-6 align-self-center">
                                    <div class="box_how">
                                        <figure><img
                                                src="{{ asset('/home_assets/img/lazy-placeholder-100-100-white.png') }}"
                                                data-src="{{ asset('/home_assets/img/how_3.svg') }}" alt=""
                                                width="150" height="132" class="lazy"></figure>
                                        <h3>Enjoy Food</h3>
                                        <p>Morbi convallis bibendum urna ut viverra. Maecenas quis consequat libero, a
                                            feugiat
                                            eros.</p>
                                    </div>
                                </div>
                            </div>
                            <p class="text-center mt-3 d-block d-lg-none"><a href="#0"
                                    class="btn_1 medium gradient pulse_bt mt-2">Register Now!</a></p>
                        </div>
                        <div class="col-lg-5 offset-lg-1 align-self-center">
                            <div class="intro_txt">
                                <div class="main_title">
                                    <span><em></em></span>
                                    <h2>Start Ordering Now</h2>
                                </div>
                                <p class="lead">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed imperdiet
                                    libero
                                    id nisi euismod, sed porta est consectetur deserunt.</p>
                                <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat
                                    nulla
                                    pariatur.</p>
                                <p><a href="#" class="btn_1 medium gradient pulse_bt mt-2">Register</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /shape_element_2 -->

    </main>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // console.log('jq is ready to use');
            $.ajax({
                type: "get",
                url: "{{ url('api/v1/products/popular') }}",
                headers: {
                    'zoneId': JSON.stringify([1]),
                },
                success: function(data) {
                    console.log(data.response.products);
                    var popular_product = `<div class="owl-carousel owl-theme categories_carousel" >`;
                        var route='';
                    $.each(data.response.products, function(indexInArray, product) {
                        route='user.restaurent_details',product.restaurant_id;
                        popular_product += `<div class="item_version_2 px-2">
                            <a href="{{ url('user/restaurent_details/')}}/${product.restaurant_id}">
                                <figure>
                                    <!--<span>98</span>-->
                                    <!--{{ asset('storage/app/public/category') }}-->
                                    <img src="{{ asset('storage/app/public/category') }}/${product.image}"
                                        data-src="{{ asset('storage/app/public/category') }}/${product.image}"
                                        alt="" class="owl-lazy" width="350" height="240"
                                        onerror="this.src='{{ asset('/home_assets/img/cat_listing_placeholder.png') }}'">
                                    <div class="info">
                                        <h3>${product.name}</h3>
                                        <!--<small>Avg price $40</small>-->
                                    </div>
                                </figure>
                            </a>
                            <!-- <p>other description is here</p>-->
                        </div>`;
                    });

                    popular_product += `</div>`; // Move this line here to close the Owl Carousel

                    $('#popularFood').append(popular_product);
                    $('#popularFood .owl-carousel').owlCarousel({
                    // Add your Owl Carousel options here
                    items: 3,
                    loop: true,
                    autoplay: true,       // Enable autoplay
                    autoplayTimeout: 3000,
                    // ... other options
                });
                },
                error: function(error) {
                    console.error(error);
                }
            });

        });
    </script>
@endsection

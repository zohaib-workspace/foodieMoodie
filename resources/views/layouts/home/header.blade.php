<style>
    .logout-link {
  background-image: linear-gradient(to right, #ffcda2, #fff);
  border-radius:25px;
  color: white;
  display: inline-block;
  padding: 5px 10px;
  text-decoration: none;
  transition: color 0.3s; /* Add a smooth transition effect */
}

.logout-link:hover {
  color: black;
}

    </style>

<header class="header black_nav clearfix element_to_stick">
        <div class="container">
            <div id="logo">
                <a href="{{route('user.home')}}">
                    <img src="{{ asset('/home_assets/img/logo.png')}}" width="162" height="60" alt="">
                </a>
            </div>
            <div class="layer"></div><!-- Opacity Mask Menu Mobile -->

            <!-- /top_menu -->
            <a href="#" class="open_close">
                <i class="icon_menu"></i><span>Menu</span>
            </a>
           <nav class="main-menu">
                <div id="header_menu">
                    <a href="#" class="open_close">
                        <i class="icon_close"></i><span>Menu</span>
                    </a>
                    <a href="#"><img src="{{ asset('public/home_assets/img/logo.png')}}" width="162" height="35" alt=""></a>
                </div>
                <ul>
                    <li class="submenu">
                        <a href="{{ route('user.home') }}" class="show-submenu">Home</a>
                    </li>
                    <li class="submenu">
                        <a href="#" class="show-submenu">Order</a>
                        <ul>

                            <li><a href="{{ route('user.home.running_orders') }}">All Orders</a></li>
                            {{-- <li><a href="{{ route('user.order') }}">Order</a></li>
                            <li><a href="{{ route('user.confirm-order') }}">Confirm Order</a></li> --}}
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="{{route('user.home.restaurants')}}" class="show-submenu">Restaurants</a>
                        {{-- <ul>

                            <li><a href="{{ route('user.list-map') }}">List With Map</a></li>
                            <li><a href="{{ route('user.detail-raustaurent') }}">Detail Page 2</a></li>
                        </ul> --}}
                    </li>

                    <li><a href="{{ route('user.contact') }}">Contact Us</a></li>
                    <li><a href="{{ route('user.help') }}">Help & Faq</a></li>
                    @php
                    $isLoggedIn = session('loginId'); // Assuming you have a session variable indicating the user's login status
                @endphp

                @if ($isLoggedIn)
                <ul id="top_menu" class="drop_user">
                  <li>
                      <div class="dropdown user clearfix">
                          <a href="header-user-logged.html#" data-bs-toggle="dropdown">
                              <figure><img src="{{ asset('public/home_assets/img/avatar1.jpg')}}" alt=""></figure><span>Jhon Doe</span>
                          </a>
                          <div class="dropdown-menu">
                              <div class="dropdown-menu-content">
                                  <ul>
                                      <li><a href="header-user-logged.html#0"><i class="icon_cog"></i>Dashboard</a></li>
                                      <li><a href="header-user-logged.html#0"><i class="icon_document"></i>Bookings</a></li>
                                      <li><a href="header-user-logged.html#0"><i class="icon_heart"></i>Wish List</a></li>
                                      <li><a href="{{ route('user.logout') }}"><i class="icon_key"></i>Log out</a></li>
                                  </ul>
                              </div>
                          </div>
                      </div>
                      <!-- /dropdown -->
                  </li>
</ul>
                    <a href="{{ route('user.logout') }}" class="logout-link"
                        style="display: inline-block; padding: 5px 10px;"><b>Logout</b></a>
                @else
                    <li><a href="{{ route('user.login') }}">Sign In</a></li>
                    <li><a href="{{ route('user.register') }}">Sign Up</a></li>
                @endif

                </ul>
            </nav>
        </div>
    </header>
    <!-- /header -->

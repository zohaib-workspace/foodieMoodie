@extends('layouts.home.app')
@section('title', ' home')
@Section('main_content')
    <br><br>
    <main class="bg_gray">
        <div class="container margin_60_40">
            <div class="row" >
                <div class="col-lg-8 list_menu">
                    <section id="section-1">
                        <h4> Order Id 345678</h4>
                        <div class="table_wrapper">
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
                                <tbody>
                                    <tr>
                                        <td class="">
                                    <img src="{{asset('placeholder.png')}}" width="50" height="50" class="rounded" alt="">

                                        </td>
                                        <td>product name</td>
                                        <td>Rs 500</td>

                                        <td> small</td>
                                        <td>Add ons</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </section>

                </div>
                <!-- /col -->

                <div class="col-lg-4">
                    <div class="box_order border">
                        <div class="head d-flex justify-content-between align-items-center">
                            <h3 class="mb-0">Order Summary</h3>
                            <a href="#0" class="close_panel_mobile"><i class="icon_close"></i></a>
                        </div>
                        <div class="main" id="order_summary">
                            <ul class="list-unstyled">
                                <h5>Restaurant</h5>
                                <li class="d-flex align-items-center mb-3">
                                    <img src="{{asset('placeholder.png')}}" width="40" height="40" class="rounded-circle" alt="">
                                    <b class="px-1 fs-5">KFC</b>
                                    <span class="ms-auto">
                                        <button class="btn p-0 m-0"><i class="fa-solid fa-phone text-warning fs-5"></i></button>
                                        <br>
                                        <button class="btn p-0 m-0"><i class="fa-regular fa-message text-warning fs-5 my-2"></i></button>
                                    </span>
                                </li>
                                
                                <h5>Rider   </h5>
                                <li class="d-flex justify-content-between mb-3">
                                    <p>Rider name <br> 92345678567</p>
                                    <span class="">
                                        <button class="btn p-0 m-0"><i class="fa-solid fa-phone text-warning fs-5"></i></button>
                                        <br>
                                        <button class="btn p-0 m-0"><i class="fa-regular fa-message text-warning fs-5 my-2"></i></button>
                                    </span>
                                </li>
                                <li>Delivery charges <span>Rs. 1</span></li>
                                <li>Tax Amount <span>Rs. 1</span></li>
                                <li>Cart Total <span>Rs. 1</span></li>
                            </ul>
                            {{-- <ul class="list-unstyled">
                                <li class="total">Subtotal<span id="sub_total">$0</span></li>
                            </ul> --}}
                            <div class="btn_1_mobile">
                                <button href="#" class="btn_1 gradient full-width mb-5">Cancel Order</button>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </main>
@endsection

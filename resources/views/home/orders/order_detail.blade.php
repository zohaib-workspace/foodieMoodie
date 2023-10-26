@extends('layouts.home.app')
@section('title', ' home')
@Section('main_content')
    @push('cdns')
        <link rel="shortcut icon" href="">
        <!-- Font -->
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&amp;display=swap" rel="stylesheet">
        <!-- CSS Implementing Plugins -->
        <link rel="stylesheet" href="{{ asset('public/assets/admin') }}/css/vendor.min.css">
        <link rel="stylesheet" href="{{ asset('public/assets/admin') }}/vendor/icon-set/style.css">
        <!-- CSS Front Template -->
        <link rel="stylesheet" href="{{ asset('public/assets/admin') }}/css/bootstrap.min.css">
        <link rel="stylesheet" href="{{ asset('public/assets/admin') }}/css/theme.minc619.css?v=1.0">
        <link rel="stylesheet" href="{{ asset('public/assets/admin') }}/css/style.css">
        <!-- Provider Panel Update CSS -->
        <link rel="stylesheet" href="{{ asset('public/assets/admin') }}/css/vendor.css">


        <script src="{{ asset('public/assets/admin') }}/vendor/hs-navbar-vertical-aside/hs-navbar-vertical-aside-mini-cache.js">
        </script>
        <link rel="stylesheet" href="{{ asset('public/assets/admin') }}/css/toastr.css">
    @endpush
    @php
        $max_processing_time = explode('-', $order['restaurant']['delivery_time'])[0];
    @endphp
    <style>
        .sidenav {
            height: 100%;
            width: 0;
            position: fixed;
            z-index: 1000000;
            top: 0;
            right: 0;
            background-color: #ffffff;
            overflow-x: hidden;
            transition: 0.5s;
            padding-top: 60px;
            overflow: hidden;
            box-shadow: -10px 10px 20px 0px rgba(0, 0, 0, 0.64);
            -webkit-box-shadow: -10px 10px 20px 0px rgba(0, 0, 0, 0.64);
            -moz-box-shadow: -10px 10px 20px 0px rgba(0, 0, 0, 0.64);
        }

        .sidenav .closebtn {

            position: absolute;
            top: 0;
            right: 25px;
            font-size: 36px;
            margin-left: 50px;
        }

        @media screen and (max-height: 450px) {
            .sidenav {
                padding-top: 15px;
            }

            .sidenav a {
                font-size: 18px;
            }
        }
    </style>
    <style>
        .chatCont {
            /* position: fixed;
                                    right: 0; */
        }

        .card-bordered {
            border: 1px solid #ebebeb;
        }

        .card {
            border: 0;
            border-radius: 0px;
            margin-bottom: 30px;
            -webkit-box-shadow: 0 2px 3px rgba(0, 0, 0, 0.03);
            box-shadow: 0 2px 3px rgba(0, 0, 0, 0.03);
            -webkit-transition: .5s;
            transition: .5s;
            position: relative;
            height: 100%;

        }

        .padding {
            padding: 3rem !important
        }

        /* body {
                                    background-color: #f9f9fa
                                } */

        .card-header:first-child {
            border-radius: calc(.25rem - 1px) calc(.25rem - 1px) 0 0;
        }


        .card-header {
            display: -webkit-box;
            display: flex;
            -webkit-box-pack: justify;
            justify-content: space-between;
            -webkit-box-align: center;
            align-items: center;
            padding: 15px 20px;
            background-color: transparent;
            border-bottom: 1px solid rgba(77, 82, 89, 0.07);
        }

        .card-header .card-title {
            padding: 0;
            border: none;
        }

        h4.card-title {
            font-size: 17px;
        }

        .card-header>*:last-child {
            margin-right: 0;
        }

        .card-header>* {
            margin-left: 8px;
            margin-right: 8px;
        }

        .btn-secondary {
            color: #4d5259 !important;
            background-color: #e4e7ea;
            border-color: #e4e7ea;
            color: #fff;
        }

        .btn-xs {
            font-size: 11px;
            padding: 2px 8px;
            line-height: 18px;
        }

        .btn-xs:hover {
            color: #fff !important;
        }




        .card-titlew {
            font-family: Roboto, sans-serif;
            font-weight: 300;
            line-height: 1.5;
            margin-bottom: 0;
            padding: 15px 20px;
            /*This line was causing problem in the header of the page now i have changed the its class name*/
            border-bottom: 1px solid rgba(77, 82, 89, 0.07);
        }


        .ps-container {
            position: relative;
        }

        .ps-container {
            -ms-touch-action: auto;
            touch-action: auto;
            overflow: hidden !important;
            -ms-overflow-style: none;
            height: 90%;
        }

        .media-chat {
            padding-right: 64px;
            margin-bottom: 0;
        }

        .media {
            padding: 16px 12px;
            -webkit-transition: background-color .2s linear;
            transition: background-color .2s linear;
        }

        /* .media .avatar {
                                    flex-shrink: 0;
                                } */

        /* .avatar {
                                    position: relative;
                                    display: inline-block;
                                    width: 36px;
                                    height: 36px;
                                    line-height: 36px;
                                    text-align: center;
                                    border-radius: 100%;
                                    background-color: #f5f6f7;
                                    color: #8b95a5;
                                    text-transform: uppercase;
                                } */

        .media-chat .media-body {
            -webkit-box-flex: initial;
            flex: initial;
            display: table;
        }

        .media-body {
            min-width: 0;

        }

        .media-chat .media-body p {
            position: relative;
            padding: 6px 8px;
            margin: 4px 0;
            background-color: #f5f6f7;
            border-radius: 3px;
            font-weight: 100;
            color: #9b9b9b;
        }

        .media>* {
            margin: 0 8px;
        }

        .media-chat .media-body p.meta {
            background-color: rgb(255, 255, 255) !important;
            padding: 0;
            opacity: .8;
        }

        .media-meta-day {
            -webkit-box-pack: justify;
            justify-content: space-between;
            -webkit-box-align: center;
            align-items: center;
            margin-bottom: 0;
            color: #8b95a5;
            opacity: .8;
            font-weight: 400;
        }

        .media {
            padding: 16px 12px;
            -webkit-transition: background-color .2s linear;
            transition: background-color .2s linear;
        }

        .media-meta-day::before {
            margin-right: 16px;
        }

        .media-meta-day::before,
        .media-meta-day::after {
            content: '';
            -webkit-box-flex: 1;
            flex: 1 1;
            border-top: 1px solid #ebebeb;
        }

        .media-meta-day::after {
            content: '';
            -webkit-box-flex: 1;
            flex: 1 1;
            border-top: 1px solid #ebebeb;
        }

        .media-meta-day::after {
            margin-left: 16px;
        }

        .media-chat.media-chat-reverse {
            padding-right: 12px;
            padding-left: 64px;
            -webkit-box-orient: horizontal;
            -webkit-box-direction: reverse;
            flex-direction: row-reverse;
        }

        .media-chat {
            padding-right: 64px;
            margin-bottom: 0;
        }

        .media {
            padding: 2px 12px;
            -webkit-transition: background-color .2s linear;
            transition: background-color .2s linear;
        }

        .media-chat.media-chat-reverse .media-body p {
            float: right;
            clear: right;
            /*background-color: #48b0f7;*/
            background-color: #bc37de;
            color: #fff;
        }

        .media-chat.media-chat-reverse .media-body p time {

            color: #9B9B9B;
            font-size: 10px;
            line-height: 0%;
        }

        .media-chat .media-body p {
            position: relative;
            padding: 6px 8px;
            margin: 4px 0;
            background-color: #f5f6f7;
            border-radius: 3px;
        }

        .media-body p:nth-child(2) {
            display: inline-block;
        }

        .border-light {
            border-color: #f1f2f3 !important;
        }

        .bt-1 {
            border-top: 1px solid #ebebeb !important;
        }

        .publisher {
            position: relative;
            bottom: 0;
            display: -webkit-box;
            display: flex;
            -webkit-box-align: center;
            align-items: center;
            padding: 12px 20px;
            background-color: #f9fafb;
        }

        .publisher>*:first-child {
            margin-left: 0;
        }

        .publisher>* {
            margin: 0 8px;
        }

        .publisher-input {
            -webkit-box-flex: 1;
            flex-grow: 1;
            border: none;
            outline: none !important;
            background-color: transparent;
        }

        button,
        input,
        optgroup,
        select,
        textarea {
            font-family: Roboto, sans-serif;
            font-weight: 300;
        }

        .publisher-btn {
            background-color: transparent;
            border: none;
            color: #8b95a5;
            font-size: 16px;
            cursor: pointer;
            overflow: -moz-hidden-unscrollable;
            -webkit-transition: .2s linear;
            transition: .2s linear;
        }

        .file-group {
            position: relative;
            overflow: hidden;
        }

        .publisher-btn {
            background-color: transparent;
            border: none;
            color: #cac7c7;
            font-size: 16px;
            cursor: pointer;
            overflow: -moz-hidden-unscrollable;
            -webkit-transition: .2s linear;
            transition: .2s linear;
        }

        .file-group input[type="file"] {
            position: absolute;
            opacity: 0;
            z-index: -1;
            width: 20px;
        }

        .text-info {
            color: #48b0f7;
        }
    </style>
    <style>
        body {
            background-color: #eee;
        }

        div.stars {

            width: 270px;

            display: inline-block;

        }



        input.star {
            display: none;
        }

        label.star {

            float: right;
            padding-left: 10px;
            padding-right: 10px;
            font-size: 27px;
            color: teal;
            transition: all .2s;

        }


        input.star:checked~label.star:before {
            content: '\f005';
            color: #FD4;
            transition: all .25s;

        }



        input.star-5:checked~label.star:before {

            color: #FE7;

            text-shadow: 0 0 20px #952;

        }



        input.star-1:checked~label.star:before {
            color: #F62;
        }



        label.star:hover {
            transform: rotate(-15deg) scale(1.3);
        }



        label.star:before {

            content: '\f006';

            font-family: FontAwesome;

        }
    </style>
    <main class="bg_gray mt-4">

        <div class="container margin_60_40">
        @section('title', translate('messages.Order Details'))

        {{-- @section('content') --}}
        <?php $campaign_order = isset($order->details[0]->campaign) ? true : false; ?>
        <div class="content container-fluid item-box-page">

            <div class="page-header d-print-none">

                <h1 class="page-header-title text-capitalize">
                    <div class="card-header-icon d-inline-flex mr-2 img">
                        <img src="{{ asset('public/assets/admin/img/orders.png') }}" alt="public">
                    </div>
                    <span>
                        {{ translate('messages.Order Details') }}
                    </span>
                    {{-- <div class="d-flex ml-auto">
                        <a class="btn btn-icon btn-sm btn-soft-primary rounded-circle mr-1" href="{{ route('vendor.order.details', [$order['id'] - 1]) }}" data-toggle="tooltip" data-placement="top" title="{{ translate('Previous order') }}">
                    <i class="tio-chevron-left m-0"></i>
                    </a>
                    <a class="btn btn-icon btn-sm btn-soft-primary rounded-circle" href="{{ route('vendor.order.details', [$order['id'] + 1]) }}" data-toggle="tooltip" data-placement="top" title="{{ translate('Next order') }}">
                        <i class="tio-chevron-right m-0"></i>
                    </a>
            </div> --}}
                </h1>
            </div>


            <div class="row g-1" id="printableArea">
                <div class="col-lg-8 order-print-area-left">
                    <!-- Card -->
                    <div class="card mb-3 mb-lg-5">
                        <!-- Header -->
                        <div class="card-header border-0 align-items-start flex-wrap">
                            <div class="order-invoice-left">
                                <h1 class="page-header-title mt-2">
                                    <span>
                                        {{ translate('messages.order') }} #{{ $order['id'] }}
                                    </span>

                                    {{-- <span class="badge badge-soft-success text-capitalize my-2 ml-2">
                                    POS
                                </span> --}}
                                    @if ($order->edited)
                                        <span class="badge badge-soft-danger text-capitalize px-2 ml-2">
                                            {{ translate('messages.edited') }}
                                        </span>
                                    @endif
                                    <a class="btn btn--primary m-2 print--btn d-sm-none ml-auto"
                                        href="{{ route('user.generate.invoice', [$order['id']]) }}">
                                        <i class="tio-print mr-1"></i>
                                    </a>
                                </h1>
                                <span class="mt-2 d-block">
                                    <i class="tio-date-range"></i>
                                    {{ date('d M Y ' . config('timeformat'), strtotime($order['created_at'])) }}
                                </span>
                                @if ($order['order_status'] == 'pending')
                                    <button class="btn btn-sm btn-orange mt-2"
                                        onclick="cancelOrder({{ $order['id'] }});">Cancel Order</button>
                                @endif
                                @if ($order['order_status'] == 'canceled')
                                    <button class="btn btn-sm btn-orange mt-2" data-bs-toggle="modal"
                                        data-bs-target="#staticBackdrop">Report Order</button>
                                @endif

                                @if ($order->schedule_at && $order->scheduled)
                                    <span class="text-capitalize d-block mt-1">
                                        {{ translate('messages.scheduled_at') }}
                                        : <label
                                            class="fz-10px badge badge-soft-primary">{{ date('d M Y ' . config('timeformat'), strtotime($order['schedule_at'])) }}</label>
                                    </span>
                                @endif
                                @if ($campaign_order)
                                    <span class="badge mt-2 badge-soft-primary">
                                        {{ translate('messages.campaign_order') }}
                                    </span>
                                @endif
                                @if ($order['order_note'])
                                    <h6>
                                        {{ translate('messages.order') }} {{ translate('messages.note') }} :
                                        {{ $order['order_note'] }}
                                    </h6>
                                @endif
                            </div>
                            <div class="order-invoice-right">
                                {{-- <div class="d-none d-sm-flex flex-wrap ml-auto align-items-center justify-content-end m-n-5rem">
                                    <a class="btn btn--primary m-2 print--btn" href="{{ route('vendor.order.generate-invoice', [$order['id']]) }}">
                            <i class="tio-print mr-1"></i> {{ translate('messages.print') }}
                            {{ translate('messages.invoice') }}
                            </a>

                        </div> --}}
                                <div class="text-right mt-3 order-invoice-right-contents text-capitalize">
                                    <h6>
                                        <span>{{ translate('Status') }} :</span>
                                        @if ($order['order_status'] == 'pending')
                                            <span class="badge badge-soft-info ml-2 ml-sm-3">
                                                {{ translate('messages.pending') }}
                                            </span>
                                        @elseif($order['order_status'] == 'confirmed')
                                            <span class="badge badge-soft-info ml-2 ml-sm-3">
                                                {{ translate('messages.confirmed') }}
                                            </span>
                                        @elseif($order['order_status'] == 'processing')
                                            <span class="badge badge-soft-warning ml-2 ml-sm-3">
                                                {{ translate('messages.cooking') }}
                                            </span>
                                        @elseif($order['order_status'] == 'picked_up')
                                            <span class="badge badge-soft-warning ml-2 ml-sm-3">
                                                {{ translate('messages.out_for_delivery') }}
                                            </span>
                                        @elseif($order['order_status'] == 'delivered')
                                            <span class="badge badge-soft-success ml-2 ml-sm-3">
                                                {{ translate('messages.delivered') }}
                                            </span>
                                        @else
                                            <span class="badge badge-soft-danger ml-2 ml-sm-3">
                                                {{ translate(str_replace('_', ' ', $order['order_status'])) }}
                                            </span>
                                        @endif
                                    </h6>
                                    <h6>
                                        <span>
                                            {{ translate('messages.payment') }} {{ translate('messages.method') }}
                                            :</span>
                                        <strong>
                                            {{ translate(str_replace('_', ' ', $order['payment_method'])) }}</strong>
                                    </h6>
                                    <h6>
                                        <span>{{ translate('Order Type') }} :</span>
                                        <strong
                                            class="text--title">{{ translate(str_replace('_', ' ', $order['order_type'])) }}</strong>
                                    </h6>
                                    <h6>
                                        <span>{{ translate('Payment Status') }} :</span>
                                        @if ($order['payment_status'] == 'paid')
                                            <strong class="text-success">
                                                {{ translate('messages.paid') }}
                                            </strong>
                                        @else
                                            <strong class="text-danger">
                                                {{ translate('messages.unpaid') }}
                                            </strong>
                                        @endif
                                    </h6>
                                </div>
                            </div>
                        </div>
                        <!-- End Header -->

                        <!-- Body -->
                        <div class="card-body p-0">
                            <?php
                            $total_addon_price = 0;
                            $restaurant_discount_amount = 0;
                            $product_price = 0;
                            $deal_price = 0;
                            $total_addon_price = 0;
                            ?>
                            <div class="table-responsive">
                                <table
                                    class="table table-borderless table-thead-bordered table-nowrap card-table dataTable no-footer mb-0">
                                    @if(!blank($order->details))
                                    <thead class="thead-light">
                                        <tr>
                                            <th>{{ translate('Item Details') }}</th>
                                            <th>{{ translate('Addons') }}</th>
                                            <th class="text-right">{{ translate('Price') }}</th>
                                        </tr>
                                    </thead>
                                    @endif
                                    <tbody>
                                        @foreach ($order->details as $key => $detail)
                                            @if (isset($detail->food_id))
                                                @php($detail->food = json_decode($detail->food_details, true))
                                                <tr>
                                                    <td>
                                                        <div class="media">
                                                            <a class="avatar mr-3 cursor-pointer initial-80"
                                                                href="{{ route('vendor.food.view', $detail->food['id']) }}">
                                                                <img class="img-fluid rounded initial-80"
                                                                    src="{{ asset('storage/app/public/product') }}/{{ $detail->food['image'] }}"
                                                                    onerror="this.src='{{ asset('public/assets/admin/img/100x100/1.png') }}'"
                                                                    alt="Image Description">
                                                            </a>
                                                            <div class="media-body">
                                                                <div>
                                                                    <strong>
                                                                        {{ Str::limit($detail->food['name'], 25, '...') }}</strong><br>

                                                                    @if (count(json_decode($detail['variation'], true)) > 0)
                                                                        @if (!is_string($detail['variation']))
                                                                            @foreach (json_decode($detail['variation'], true)[0] as $key1 => $variation)
                                                                                <span
                                                                                    class="font-size-sm text-body text-capitalize">
                                                                                    <span>{{ $key1 }} : </span>
                                                                                    <span
                                                                                        class="font-weight-bold">{{ Str::limit($variation, 20, '...') }}</span>
                                                                                </span>
                                                                            @endforeach
                                                                        @endif
                                                                    @endif



                                                                    <div>
                                                                        <strong>{{ translate('messages.Price') }}:</strong>
                                                                        {{ \App\CentralLogics\Helpers::format_currency($detail['price'], $order['restaurant_id']) }}
                                                                    </div>
                                                                    <div>
                                                                        <strong>{{ translate('messages.Qty') }}:</strong>
                                                                        {{ $detail['quantity'] }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        @foreach (json_decode($detail['add_ons'], true) as $key2 => $addon)
                                                            <div class="font-size-sm text-body">
                                                                <span>{{ Str::limit($addon['name'], 25, '...') }} :
                                                                </span>
                                                                <span class="font-weight-bold">
                                                                    {{ $addon['quantity'] }} x
                                                                    {{ \App\CentralLogics\Helpers::format_currency($addon['price'], $order['restaurant_id']) }}
                                                                </span>
                                                            </div>
                                                            @php($total_addon_price += $addon['price'] * $addon['quantity'])
                                                        @endforeach
                                                    </td>
                                                    <td>
                                                        <div class="text-right">
                                                            @php($amount = $detail['price'] * $detail['quantity'])
                                                            <h5>
                                                                {{ \App\CentralLogics\Helpers::format_currency($amount, $order['restaurant_id']) }}
                                                            </h5>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @php($product_price += $amount)
                                                @php($restaurant_discount_amount += $detail['discount_on_food'] * $detail['quantity'])
                                            @elseif(isset($detail->item_campaign_id))
                                                @php($detail->campaign = json_decode($detail->food_details, true))
                                                <tr>
                                                    <td>
                                                        <div class="media">
                                                            <div class="avatar avatar-xl mr-3">
                                                                <img class="img-fluid rounded initial-80"
                                                                    src="{{ asset('storage/app/public/campaign') }}/{{ $detail->campaign['image'] }}"
                                                                    onerror="this.src='{{ asset('public/assets/admin/img/100x100/1.png') }}'"
                                                                    alt="Image Description">
                                                            </div>
                                                            <div class="media-body">
                                                                <div>
                                                                    <strong>
                                                                        {{ Str::limit($detail->campaign['name'], 25, '...') }}</strong><br>
                                                                    @if (count(json_decode($detail['variation'], true)) > 0)
                                                                        @foreach (json_decode($detail['variation'], true)[0] as $key1 => $variation)
                                                                            <div class="font-size-sm text-body">
                                                                                <span>{{ $key1 }} : </span>
                                                                                <span
                                                                                    class="font-weight-bold">{{ Str::limit($variation, 25, '...') }}</span>
                                                                            </div>
                                                                        @endforeach
                                                                    @endif
                                                                    <div>
                                                                        <strong>{{ translate('messages.Price') }} :
                                                                        </strong>
                                                                        <span>{{ \App\CentralLogics\Helpers::format_currency($detail['price'], $order['restaurant_id']) }}</span>
                                                                    </div>
                                                                    <div>
                                                                        <strong>{{ translate('messages.Qty') }} :
                                                                        </strong>
                                                                        <span>
                                                                            {{ $detail['quantity'] }}
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        @foreach (json_decode($detail['add_ons'], true) as $key2 => $addon)
                                                            <div class="font-size-sm text-body">
                                                                <span>{{ Str::limit($addon['name'], 20, '...') }} :
                                                                </span>
                                                                <span class="font-weight-bold">
                                                                    {{ $addon['quantity'] }} x
                                                                    {{ \App\CentralLogics\Helpers::format_currency($addon['price'], $order['restaurant_id']) }}
                                                                </span>
                                                            </div>
                                                            @php($total_addon_price += $addon['price'] * $addon['quantity'])
                                                        @endforeach
                                                    </td>
                                                    <td>
                                                        @php($amount = $detail['price'] * $detail['quantity'])
                                                        <h5 class="text-right">
                                                            {{ \App\CentralLogics\Helpers::format_currency($amount, $order['restaurant_id']) }}
                                                        </h5>
                                                    </td>
                                                </tr>
                                                @php($product_price += $amount)
                                                @php($restaurant_discount_amount += $detail['discount_on_food'] * $detail['quantity'])
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>


                            <div class="table-responsive">
                                <table
                                class="table table-borderless table-thead-bordered table-nowrap card-table dataTable no-footer mb-0">
                                @if(!blank($order->deals))
                                    <thead class="thead-light">
                                        <tr>
                                            <th>{{ translate('Deals Details') }}</th>
                                            <th class="text-right">{{ translate('Price') }}</th>
                                        </tr>
                                    </thead>
                                    @endif
                                    <tbody>
                                        @foreach ($order->deals as $key => $detail)
                                            @if (isset($detail->deal_data))
                                                @php($detail->food = json_decode($detail->deal_data, true))
                                                <tr>
                                                    <td>
                                                        <div class="media">
                                                            <a class="avatar mr-3 cursor-pointer initial-80"
                                                                href="{{ route('vendor.food.view', $detail->food['id']) }}">
                                                                <img class="img-fluid rounded initial-80"
                                                                    src="{{ asset('storage/app/public/product') }}/{{ $detail->food['image'] }}"
                                                                    onerror="this.src='{{ asset('public/assets/admin/img/100x100/1.png') }}'"
                                                                    alt="Image Description">
                                                            </a>
                                                            <div class="media-body">
                                                                <div>
                                                                    <strong>
                                                                        {{ Str::limit($detail->food['title'], 25, '...') }}</strong><br>




                                                                    <div>
                                                                        <strong>{{ translate('messages.Price') }}
                                                                            :</strong>
                                                                        {{ \App\CentralLogics\Helpers::format_currency($detail['price'], $order['restaurant_id']) }}
                                                                    </div>
                                                                    <div>
                                                                        <strong>{{ translate('messages.Qty') }}
                                                                            :</strong> {{ $detail['quantity'] }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="text-right">
                                                            @php($amount = $detail['price'] * $detail['quantity'])
                                                            <h5>
                                                                {{ \App\CentralLogics\Helpers::format_currency($amount, $order['restaurant_id']) }}
                                                            </h5>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @php($deal_price += $amount)
                                                @php($restaurant_discount_amount += $detail['discount_on_food'] ?? 0 * $detail['quantity'])
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>



                            <?php
                            
                            $coupon_discount_amount = $order['coupon_discount_amount'];
                            
                            $total_price = $product_price + $deal_price + $total_addon_price - $restaurant_discount_amount - $coupon_discount_amount;
                            
                            $total_tax_amount = $order['total_tax_amount'];
                            
                            $restaurant_discount_amount = $order['restaurant_discount_amount'];
                            
                            ?>
                            <div class="px-4">
                                <div class="row justify-content-md-end mb-3">
                                    <div class="col-md-9 col-lg-8">
                                        <dl class="row text-sm-right">
                                            <dt class="col-sm-6">{{ translate('messages.items') }}
                                                {{ translate('messages.price') }}:
                                            </dt>
                                            <dd class="col-sm-6">
                                                {{ \App\CentralLogics\Helpers::format_currency($product_price, $order['restaurant_id']) }}
                                            </dd>
                                            <dt class="col-sm-6">{{ translate('messages.addon') }}
                                                {{ translate('messages.cost') }}:
                                            </dt>
                                            <dd class="col-sm-6">
                                                {{ \App\CentralLogics\Helpers::format_currency($total_addon_price, $order['restaurant_id']) }}
                                                <hr>
                                            </dd>

                                            <dt class="col-sm-6">{{ translate('Deals') }} {{ translate('Price') }}:
                                            </dt>
                                            <dd class="col-sm-6">
                                                {{ \App\CentralLogics\Helpers::format_currency($deal_price, $order['restaurant_id']) }}
                                                <hr>
                                            </dd>

                                            <dt class="col-sm-6">{{ translate('messages.subtotal') }}:</dt>
                                            <dd class="col-sm-6">
                                                {{ \App\CentralLogics\Helpers::format_currency($product_price + $total_addon_price + $deal_price, $order['restaurant_id']) }}
                                            </dd>
                                            <dt class="col-sm-6">{{ translate('messages.discount') }}:</dt>
                                            <dd class="col-sm-6">
                                                -
                                                {{ \App\CentralLogics\Helpers::format_currency($restaurant_discount_amount, $order['restaurant_id']) }}
                                            </dd>
                                            <dt class="col-sm-6">{{ translate('messages.coupon') }}
                                                {{ translate('messages.discount') }}:
                                            </dt>
                                            <dd class="col-sm-6">
                                                -
                                                {{ \App\CentralLogics\Helpers::format_currency($coupon_discount_amount, $order['restaurant_id']) }}
                                            </dd>
                                            <dt class="col-sm-6">{{ translate('messages.vat/tax') }}:</dt>
                                            <dd class="col-sm-6">
                                                +
                                                {{ \App\CentralLogics\Helpers::format_currency($total_tax_amount, $order['restaurant_id']) }}
                                            </dd>
                                            <dt class="col-sm-6">{{ translate('messages.delivery_man_tips') }}

                                            </dt>
                                            <dd class="col-sm-6">
                                                @php($dm_tips = $order['dm_tips'])
                                                +
                                                {{ \App\CentralLogics\Helpers::format_currency($dm_tips, $order['restaurant_id']) }}

                                            </dd>
                                            <dt class="col-sm-6">{{ translate('messages.delivery') }}
                                                {{ translate('messages.fee') }}:
                                            </dt>
                                            <dd class="col-sm-6">
                                                @php($del_c = $order['delivery_charge'])
                                                +
                                                {{ \App\CentralLogics\Helpers::format_currency($del_c, $order['restaurant_id']) }}
                                                <hr>
                                            </dd>

                                            <dt class="col-sm-6">{{ translate('messages.total') }}:</dt>
                                            <dd class="col-sm-6">
                                                {{ \App\CentralLogics\Helpers::format_currency($product_price + $del_c + $total_tax_amount + $total_addon_price + $deal_price + $dm_tips - $coupon_discount_amount - $restaurant_discount_amount, $order['restaurant_id']) }}
                                            </dd>
                                        </dl>
                                        <!-- End Row -->
                                    </div>
                                </div>
                            </div>
                            <!-- End Row -->
                        </div>
                        <!-- End Body -->
                    </div>
                    <!-- End Card -->

                    

                </div>

                <div class="col-lg-4 order-print-area-right">
                    <!-- Card -->
                    @if ($order['order_status'] != 'delivered')
                        <div class="card mb-2" style="height: auto">
                            <!-- Header -->
                            <div class="card-header border-0 py-0">
                                <h4 class="card-header-title border-bottom py-3 m-0  w-100 text-center">
                                    {{ translate('Delivery Man') }}</h4>
                            </div>
                            <!-- End Header -->

                            <!-- Body -->

                            <div class="card-body" style="height: auto">
                               
                                @if ($order['order_type'] == 'delivery')
                                    @if ($order->delivery_man)
                                        <h5 class="card-title mb-3">
                                            <span class="card-header-icon">
                                                <i class="tio-user"></i>
                                            </span>
                                            <span>
                                                {{ translate('Delivery Man Information') }}
                                            </span>
                                        </h5>
                                        <div class="media align-items-center deco-none customer--information-single"
                                            href="javascript:">
                                            <div class="avatar avatar-circle">
                                                <img class="avatar-img  initial-81"
                                                    onerror="this.src='{{ asset('public/assets/admin/img/160x160/img3.png') }}'"
                                                    src="{{ asset('storage/app/public/delivery-man/' . $order->delivery_man->image) }}"
                                                    alt="Image Description">
                                            </div>
                                            <div class="media-body">
                                                <span
                                                    class="fz--14px text--title font-semibold text-hover-primary d-block">
                                                    {{ $order->delivery_man['f_name'] . ' ' . $order->delivery_man['l_name'] }}
                                                </span>
                                                <span class="d-block">
                                                    <strong class="text--title font-semibold">
                                                        {{ $order->delivery_man->orders_count }}
                                                    </strong>
                                                    {{ translate('messages.orders') }}
                                                </span>
                                                <span class="d-block">
                                                    <a class="text--title font-semibold"
                                                        href="tel:{{ $order->delivery_man['phone'] }}">
                                                        <strong>
                                                            {{ $order->delivery_man['email'] }}
                                                        </strong>
                                                    </a>
                                                </span>
                                                <span class="d-block">
                                                    <strong class="text--title font-semibold">
                                                    </strong>
                                                    {{ $order->delivery_man['phone'] }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="media justify-content-center align-items-center">
                                            <a class="btn btn--warning m-2 print--btn w-100 mb-3 customerChatButton "
                                                id="{{ $order->delivery_man != null ? $order['id'] : 'noDeliveryMan' }}"
                                                image="{{ $order->delivery_man != null && $order->customer->image }}"
                                                addressee="deliveryMan" onclick="openCha(this.id)">
                                                <i class="tio-chat mr-1"></i> {{ translate('messages.chat') }}
                                                {{ translate('messages.with') }} {{ translate('messages.rider') }}
                                            </a>
                                        </div>

                                        @if ($order['order_type'] != 'take_away')
                                            <hr>
                                            @php($address = $order->dm_last_location)
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h5>{{ translate('messages.last') }}
                                                    {{ translate('messages.location') }}</h5>
                                            </div>
                                            @if (isset($address))
                                                <span class="d-block">
                                                    <a target="_blank"
                                                        href="http://maps.google.com/maps?z=12&t=m&q=loc:{{ $address['latitude'] }}+{{ $address['longitude'] }}">
                                                        <i class="tio-poi"></i> {{ $address['location'] }}<br>
                                                    </a>
                                                </span>
                                            @elseif(!isset($order['delivery_man_id']))
                                                <span class="d-block text-lowercase qcont">
                                                    {{ translate('messages.location') . ' ' . translate('messages.not_found') }}
                                                </span>
                                            @endif
                                        @endif
                                        <div class="media justify-content-center align-items-center">
                                            <a class="btn btn--warning m-2 print--btn w-100 mb-3 customerChatButton "
                                                id="{{ $order['id'] }}"
                                                image="{{ $order->delivery_man != null && $order->customer->image }}"
                                                addressee="deliveryMan" onclick="openCha(this.id)">
                                                <i class="tio-chat mr-1"></i> {{ translate('messages.chat') }}
                                                {{ translate('messages.with') }} {{ translate('messages.rider') }}
                                            </a>
                                        </div>
                                    @else
                                        <div class="py-3 w-100 text-center mt-3">
                                            <span class="d-block text-capitalize qcont">
                                                <i class="tio-security-warning"></i>
                                                {{ translate('messages.deliveryman') . ' ' . translate('messages.assigned') }}
                                            </span>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    @endif
                    <div class="card" style="height: auto">
                        <div class="card-body" style="height: auto">
                            <h5 class="card-title mb-3">
                                <span class="card-header-icon">
                                    <i class="tio-user"></i>
                                </span>
                                <span>
                                    {{ translate('messages.vendor') }} {{ translate('messages.info') }}
                                </span>
                            </h5>
                            @if ($order->restaurant)
                                <div class="media align-items-center deco-none customer--information-single"
                                    href="javascript:">
                                    <div class="avatar avatar-circle">
                                        <img class="avatar-img  initial-81"
                                            onerror="this.src='{{ asset('public/assets/admin/img/resturant-panel/customer.png') }}'"
                                            src="{{ asset('storage/app/public/restaurant/' . $order->restaurant->logo) }}"
                                            alt="Image Description">
                                    </div>
                                    <div class="media-body">
                                        <span class="fz--14px text--title font-semibold text-hover-primary d-block">
                                            {{ $order->restaurant['name'] }}
                                        </span>
                                        {{-- <span class="d-block">
                                    <strong class="text--title font-semibold">
                                        {{ $order->customer->orders_count }}
                        </strong>
                        {{ translate('Orders') }}
                        </span> --}}
                                        <span class="d-block">
                                            <a class="text--title font-semibold"
                                                href="tel:{{ $order->restaurant['phone'] }}">
                                                <strong>
                                                    {{ $order->restaurant['phone'] }}
                                                </strong>
                                            </a>
                                        </span>
                                        <span class="d-block">
                                            <strong class="text--title font-semibold">
                                            </strong>
                                            {{ $order->restaurant['email'] }}
                                        </span>
                                    </div>
                                </div>
                                <div class="media justify-content-center align-items-center">
                                    <a class="btn btn-teal m-2 print--btn w-100 mb-3 customerChatButton "
                                        id="{{ $order['id'] }}"
                                        image="{{ $order->delivery_man != null && $order->customer->image }}"
                                        addressee="customer" onclick="openChat(this.id)">
                                        <i class="tio-chat mr-1"></i> {{ translate('messages.chat') }}
                                        {{ translate('messages.with') }} {{ translate('messages.vendor') }}
                                    </a>
                                </div>
                            @else
                                {{ translate('messages.customer_not_found') }}
                            @endif
                        </div>
                    </div>
                    @if ($order->delivery_address)
                        <div class="card mt-2" style="height: auto">
                            <div class="card-body" style="height: auto">
                                @php($address = json_decode($order->delivery_address, true))
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title">
                                        <span class="card-header-icon">
                                            <i class="tio-user"></i>
                                        </span>
                                        <span>
                                            {{ translate('messages.delivery') }} {{ translate('messages.info') }}
                                        </span>
                                    </h5>
                                    {{-- @if (isset($address))
                                    <a class="link" data-toggle="modal" data-target="#shipping-address-modal"
                                        href="javascript:"><i class="tio-edit"></i></a>
                                @endif --}}
                                </div>
                                @if (isset($address))
                                    <span class="delivery--information-single mt-3">
                                        <span class="name">{{ translate('messages.name') }}:</span>
                                        <span class="info">{{ $address['contact_person_name'] }}</span>
                                        <span class="name">{{ translate('messages.contact') }}:</span>
                                        <a class="info" href="tel:{{ $address['contact_person_number'] }}">
                                            {{ $address['contact_person_number'] }}
                                        </a>
                                        <span class="name">{{ translate('Road') }}:</span>
                                        <span
                                            class="info">{{ isset($address['road']) ? $address['road'] : '' }}</span>
                                        <span class="name">{{ translate('House') }}:</span>
                                        <span
                                            class="info">{{ isset($address['house']) ? $address['house'] : '' }}</span>
                                        <span class="name">{{ translate('Floor') }}:</span>
                                        <span
                                            class="info">{{ isset($address['floor']) ? $address['floor'] : '' }}</span>
                                        <span class="mt-2 d-flex w-100">
                                            <span><i class="tio-poi text--title"></i></span>
                                            @if ($order['order_type'] != 'take_away' && isset($address['address']))
                                                @if (isset($address['latitude']) && isset($address['longitude']))
                                                    <a target="_blank" class="info pl-2"
                                                        href="http://maps.google.com/maps?z=12&t=m&q=loc:{{ $address['latitude'] }}+{{ $address['longitude'] }}">
                                                        {{ $address['address'] }}
                                                    </a>
                                                @else
                                                    <span class="info pl-2">
                                                        {{ $address['address'] }}
                                                    </span>
                                                @endif
                                            @endif
                                        </span>
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endif
                    @if ($order['order_status'] == 'delivered')
                        <div class="card mt-2" style="height: auto">
                            <div class="card-body" style="height: auto">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title">
                                        <span class="card-header-icon">
                                            <i class="tio-user"></i>
                                        </span>
                                        <span>
                                            Review
                                        </span>
                                    </h5>
                                    {{-- @if (isset($address))
                                    <a class="link" data-toggle="modal" data-target="#shipping-address-modal"
                                        href="javascript:"><i class="tio-edit"></i></a>
                                @endif --}}
                                </div>

                                @php($review = App\Models\OrderReview::where('order_id', $order['id'])->first())
                                @if (!$review)
                                    <div class="col-md-12">
                                        <form action="{{ route('user.reviews') }}" method="post">
                                            @csrf
                                            <input type="hidden" name="restaurant_id"
                                                value="{{ $order['restaurant_id'] }}" />
                                            <input type="hidden" name="order_id" value="{{ $order['id'] }}" />


                                            <label class="form-label">Rating</label>
                                            <div class="stars ">
                                                <input class="star star-5" id="star-5" type="radio"
                                                    name="rating" value="5" />

                                                <label class="star star-5" for="star-5"></label>

                                                <input class="star star-4" id="star-4" type="radio"
                                                    name="rating" value="4" />

                                                <label class="star star-4" for="star-4"></label>

                                                <input class="star star-3" id="star-3" type="radio"
                                                    name="rating" value="3" />

                                                <label class="star star-3" for="star-3"></label>

                                                <input class="star star-2" id="star-2" type="radio"
                                                    name="rating" value="2" />

                                                <label class="star star-2" for="star-2"></label>

                                                <input class="star star-1" id="star-1" type="radio"
                                                    name="rating" value="1" />

                                                <label class="star star-1" for="star-1"></label>

                                            </div>
                                            <div class="mb-3">
                                                <label for="comment" class="form-label">Comment</label>
                                                <textarea class="form-control" id="comment" name="comment" rows="2" required></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label for="liked" class="form-label">Liked Category</label>
                                                <select class="form-select" name="liked_category" required>
                                                    <option value="">Select a category</option>
                                                    <option value="Temperature">Temperature</option>
                                                    <option value="Ingredients">Ingredients</option>
                                                    <option value="Taste">Taste</option>
                                                    <option value="Packaging">Packaging</option>
                                                </select>
                                            </div>
                                            <button type="submit" class="btn btn-orange">Submit</button>
                                        </form>
                                    </div>
                                @else
                                    <div class="row my-2">
                                        <div class="col-md-6">Rating</div>
                                        <div class="col-md-6 d-flex justify-content-center">
                                            {{ $review['rating'] ?? '0' }}&nbsp;
                                            <div class="stars">
                                                <input class="star star-2 " checked type="radio" />
                                                <label class="star star-2" for="star-5"
                                                    style="padding:0; float:left;font-size:14px;"></label>

                                            </div>
                                        </div>
                                        <div class="col-md-6">Liked Category</div>
                                        <div class="col-md-6">{{ $review['liked_category'] ?? '' }}</div>
                                        <div class="col-md-6">Comment</div>
                                        <div class="col-md-12">{{ $review['comment'] ?? '' }}</div>
                                    </div>
                                @endif

                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <!-- End Row -->
        </div>

        <!-- Modal -->
        <div id="shipping-address-modal" class="modal fade" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalTopCoverTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <!-- Header -->
                    <div class="modal-top-cover bg-dark text-center">
                        <figure class="position-absolute right-0 bottom-0 left-0 mb-n-1">
                            <svg preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px"
                                viewBox="0 0 1920 100.1">
                                <path fill="#fff" d="M0,0c0,0,934.4,93.4,1920,0v100.1H0L0,0z" />
                            </svg>
                        </figure>

                        <div class="modal-close">
                            <button type="button" class="btn btn-icon btn-sm btn-ghost-light" data-dismiss="modal"
                                aria-label="Close">
                                <svg width="16" height="16" viewBox="0 0 18 18"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill="currentColor"
                                        d="M11.5,9.5l5-5c0.2-0.2,0.2-0.6-0.1-0.9l-1-1c-0.3-0.3-0.7-0.3-0.9-0.1l-5,5l-5-5C4.3,2.3,3.9,2.4,3.6,2.6l-1,1 C2.4,3.9,2.3,4.3,2.5,4.5l5,5l-5,5c-0.2,0.2-0.2,0.6,0.1,0.9l1,1c0.3,0.3,0.7,0.3,0.9,0.1l5-5l5,5c0.2,0.2,0.6,0.2,0.9-0.1l1-1 c0.3-0.3,0.3-0.7,0.1-0.9L11.5,9.5z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <!-- End Header -->

                    <div class="modal-top-cover-icon">
                        <span class="icon icon-lg icon-light icon-circle icon-centered shadow-soft">
                            <i class="tio-location-search"></i>
                        </span>
                    </div>

                    @php($address = \App\Models\CustomerAddress::find($order['delivery_address_id']))
                    @if (isset($address))
                        <form action="{{ route('vendor.order.update-shipping', [$order['delivery_address_id']]) }}"
                            method="post">
                            @csrf
                            <div class="modal-body">
                                <div class="row mb-3">
                                    <label for="requiredLabel"
                                        class="col-md-2 col-form-label input-label text-md-right">
                                        {{ translate('messages.type') }}
                                    </label>
                                    <div class="col-md-10 js-form-message">
                                        <input type="text" class="form-control h--45px" name="address_type"
                                            value="{{ $address['address_type'] }}" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="requiredLabel"
                                        class="col-md-2 col-form-label input-label text-md-right">
                                        {{ translate('messages.contact') }}
                                    </label>
                                    <div class="col-md-10 js-form-message">
                                        <input type="text" class="form-control h--45px"
                                            name="contact_person_number"
                                            value="{{ $address['contact_person_number'] }}" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="requiredLabel"
                                        class="col-md-2 col-form-label input-label text-md-right">
                                        {{ translate('messages.name') }}
                                    </label>
                                    <div class="col-md-10 js-form-message">
                                        <input type="text" class="form-control h--45px" name="contact_person_name"
                                            value="{{ $address['contact_person_name'] }}" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="requiredLabel"
                                        class="col-md-2 col-form-label input-label text-md-right">
                                        {{ translate('messages.address') }}
                                    </label>
                                    <div class="col-md-10 js-form-message">
                                        <input type="text" class="form-control h--45px" name="address"
                                            value="{{ $address['address'] }}" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="requiredLabel"
                                        class="col-md-2 col-form-label input-label text-md-right">
                                        {{ translate('messages.latitude') }}
                                    </label>
                                    <div class="col-md-4 js-form-message">
                                        <input type="text" class="form-control h--45px" name="latitude"
                                            value="{{ $address['latitude'] }}" required>
                                    </div>
                                    <label for="requiredLabel"
                                        class="col-md-2 col-form-label input-label text-md-right">
                                        {{ translate('messages.longitude') }}
                                    </label>
                                    <div class="col-md-4 js-form-message">
                                        <input type="text" class="form-control h--45px" name="longitude"
                                            value="{{ $address['longitude'] }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn--reset"
                                    data-dismiss="modal">{{ translate('messages.close') }}</button>
                                <button type="submit" class="btn btn--primary">{{ translate('messages.save') }}
                                    {{ translate('messages.changes') }}</button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
        <!-- End Modal -->
        <div id="mySidenav" class="sidenav">


            <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
            <div class="card card-bordered">
                {{-- <div class="card-header">
                    <h4 class="card-title"><strong>Close</strong></h4>
                    <a class="btn btn-xs btn-secondary" href="#" data-abc="true">Let's Chat
                        App</a>
                    </div> --}}
                <div class="ps-container ps-theme-default ps-active-y" id="chat-content"
                    style="overflow-y: scroll !important;  !important;">


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
                            src="{{ asset('storage/app/public/vendor') }}/{{ \App\CentralLogics\Helpers::get_loggedin_for_user()->image }}"
                            alt="Image Description">
                        <span class="avatar-status avatar-sm-status avatar-status-success"></span>
                    </div>
                    <input class="publisher-input" type="text" id="message_2" name="message"
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
        @php($logedInUser = \App\CentralLogics\Helpers::get_loggedin_for_user()->id)
        <!-- End Content -->


        {{-- @endsection --}}

    </div>

    {{-- report order modal --}}
    @if ($order['order_status'] == 'canceled')
        <div class="modal fade" style="z-index:10000000;" id="staticBackdrop" data-bs-backdrop="static"
            data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Order # {{ $order['id'] }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <label>Complain</label>
                                <textarea class="form-control" id="report_input" rows="2" placeholder="Write your complain here"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-teal"
                            onclick="reportOrder({{ $order['id'] }})">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</main>
@endsection
@section('scripts')
<script>
    function cancelOrder(order_id) {
        // e.preventDefault();
        var data = {
            'order_id': order_id,
            'user_id': `{{ Auth()->id() }}`,
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
                    location.reload();

                },
                error: function(error) {
                    console.log(error);
                    alert(error.message);
                }
            });
        }

    }

    function reportOrder(order_id) {
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
                // currentOrderFunc();
                location.reload();

            },
            error: function(error) {
                console.log(error);
                alert(error.message);
            }
        });
    }
</script>
<script>
    function last_location_view() {
        toastr.warning(
            '{{ translate('
                            Only available when order is out
                            for delivery!') }}', {
                CloseButton: true,
                ProgressBar: true
            });
    }
</script>
{{-- firebase script start --}}
<script type="module">
    let id = null;
    let image = null;
    let addressee = null;
    let pathToChat = null;
    // Import the functions you need from the SDKs you need
    import {
        initializeApp
    } from "https://www.gstatic.com/firebasejs/9.18.0/firebase-app.js";
    import {
        getAnalytics
    } from "https://www.gstatic.com/firebasejs/9.18.0/firebase-analytics.js";
    import {
        getDatabase,
        set,
        ref,
        push,
        child,
        onValue,
        onChildAdded
    } from "https://www.gstatic.com/firebasejs/9.18.0/firebase-database.js";
    // TODO: Add SDKs for Firebase products that you want to use
    // https://firebase.google.com/docs/web/setup#available-libraries
    $(document).on('click', ".customerChatButton", function() {


        // document.getElementById("mySidenav").style.height = 100%;
        id = $(this).attr('id');
        image = $(this).attr('image');
        addressee = $(this).attr('addressee');
        if (id != 'noDeliveryMan') {
            var mobileSize = window.matchMedia("(max-width: 767px)").matches;
            var chatBoxSize = mobileSize ? "100%" : "500px";
            document.getElementById("mySidenav").style.width = chatBoxSize;
            console.log('before calling load function');
            loadChat(id, image, addressee);
            console.log('after calling load function');

        } else {

        }

        console.log(id);
    })
    console.log(id);

    function openNav() {
        document.getElementById("mySidenav").style.width = "500px";
    }
    const firebaseConfig = {
        apiKey: "AIzaSyAMTrRfTfDolYVWDQnL8FgSvpB0Ry5NZdI",
        authDomain: "foodie-moodie-196a1.firebaseapp.com",
        databaseURL: "https://foodie-moodie-196a1-default-rtdb.firebaseio.com",
        projectId: "foodie-moodie-196a1",
        storageBucket: "foodie-moodie-196a1.appspot.com",
        messagingSenderId: "721693246846",
        appId: "1:721693246846:web:3fecd5eab8e702e2c81f2f"
    };
    // Initialize Firebase
    const app = initializeApp(firebaseConfig);
    // const analytics = getAnalytics(app);
    const database = getDatabase(app);

    $('#chatSubmit').click((e) => {
        e.preventDefault();
        //var message = document.getElementById('message').value;
        var message = document.getElementById('message_2').value;
        if (message != '') {

            // var message = document.getElementById('message').value;
            // let pathToChat = 'chat_rooms/' + id + '/chats';

            // let pathToChat = 'orders/' + id + '/chats/customer_vendor';
            if (addressee == 'deliveryMan') {
                pathToChat = 'orders/' + id + '/chats/customer_vendor';
            } else {
                pathToChat = 'orders/' + id + '/chats/rider_vendor';
            }
            const _id = push(child(ref(database), pathToChat)).key;
            set(ref(database, pathToChat + "/" + _id), {

                creater_id: "vendor_" +
                    "{{ \App\CentralLogics\Helpers::get_loggedin_for_user()->id }}",
                from: "web",
                message: message,
                timestamp: Date.now(),
                type: "text"
            });
            document.getElementById('message_2').value = '';
        }
    });
    $('#message').keypress(function(e) {
        if (e.keyCode == 13) { // Check if "Enter" key was pressed
            e.preventDefault();
            $('#chatSubmit').click(); // Trigger click event on the chatSubmit button
        }
    });

    function loadChat(chatId, image) {
        console.log('inside load function');

        // let pathToChat = 'chat_rooms/' + chatId + '/chats';
        if (addressee == 'deliveryMan') {
            pathToChat = 'orders/' + chatId + '/chats/customer_vendor';
        } else {
            pathToChat = 'orders/' + chatId + '/chats/rider_vendor';
        }
        const newMsg = ref(database, pathToChat);
        const chatContainer = document.getElementById('chat-content');
        onChildAdded(newMsg, (data) => {
            console.log('inside onchildadded function');

            var creater_id = "vendor_" + "{{ Auth()->id() }}";

            const timestamp = data.val().timestamp;
            const date = new Date(timestamp);
            const time = date.toLocaleTimeString();
            if (data.val().creater_id != creater_id) {
                console.log('')
                var divData = '<div class="media media-chat">\n' +
                    '                      <div class="avatar avatar-sm avatar-circle">' +
                    '                      <img class="avatar-img"\n' +
                    '                         src="https://img.icons8.com/color/36/000000/administrator-male.png"\n' +
                    '                         alt="..."> \n' +
                    '                      </div>' +
                    '                    <div class="media-body">\n' +
                    '                    <p>' + data.val().message + '</p>\n' +
                    '                        <p class="meta"><time datetime="2023-04-04T11:58:00">' + time +
                    '</time></p>\n' +
                    '                </div>\n' +
                    '                </div>\n';
            } else {
                var divData = '<div class="media media-chat media-chat-reverse">\n' +
                    '                <div class="media-body">\n' +
                    '                    <p>' + data.val().message + '</p>\n' +
                    '                        <p class="meta"><time datetime="2023-04-04T11:58:00">' + time +
                    '</time></p>\n' +
                    '                </div>\n' +
                    '            </div>\n';
            }
            var d1 = document.getElementById('chat-content');

            var newDiv = document.createElement('div');
            newDiv.innerHTML = divData;
            d1.appendChild(newDiv.firstChild);
            d1.scrollTop = d1.scrollHeight;
        });
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
        document.getElementById('message_2').value = '';
    }
    // document.addEventListener('click', function(event) {
    //     var sidenav = document.getElementById("mySidenav");
    //     var sideNavWidth = sidenav.offsetWidth;
    //     var chatBtn = document.getElementsByClassName("orderChatButton");
    //     var clickTarget = event.target;
    //     if(clickTarget != sidenav && clickTarget != chatBtn && sideNavWidth != "0" ){
    //         closeNav();
    //     }
    // });



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
@endsection

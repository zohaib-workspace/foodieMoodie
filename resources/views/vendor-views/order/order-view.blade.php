@php
$max_processing_time = explode('-', $order['restaurant']['delivery_time'])[0];
@endphp
@extends('layouts.vendor.app')

@section('title', translate('messages.Order Details'))

@section('content')
    <?php $campaign_order = isset($order->details[0]->campaign) ? true : false; ?>
    <div class="content container-fluid item-box-page">

    <div class="page-header d-print-none">

            <h1 class="page-header-title text-capitalize">
                <div class="card-header-icon d-inline-flex mr-2 img">
                    <img src="{{asset('public/assets/admin/img/orders.png')}}" alt="public">
                </div>
                <span>
                    {{ translate('messages.Order Details') }}
                </span>
                <div class="d-flex ml-auto">
                    <a class="btn btn-icon btn-sm btn-soft-primary rounded-circle mr-1"
                        href="{{ route('vendor.order.details', [$order['id'] - 1]) }}" data-toggle="tooltip"
                        data-placement="top" title="{{ translate('Previous order') }}">
                        <i class="tio-chevron-left m-0"></i>
                    </a>
                    <a class="btn btn-icon btn-sm btn-soft-primary rounded-circle"
                        href="{{ route('vendor.order.details', [$order['id'] + 1]) }}" data-toggle="tooltip"
                        data-placement="top" title="{{ translate('Next order') }}">
                        <i class="tio-chevron-right m-0"></i>
                    </a>
                </div>
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
                                <a class="btn btn--primary m-2 print--btn d-sm-none ml-auto" href="{{ route('vendor.order.generate-invoice', [$order['id']]) }}">
                                    <i class="tio-print mr-1"></i>
                                </a>
                            </h1>
                            <span class="mt-2 d-block">
                                <i class="tio-date-range"></i>
                                {{ date('d M Y ' . config('timeformat'), strtotime($order['created_at'])) }}
                            </span>
                            @if ($order->schedule_at && $order->scheduled)
                                <span class="text-capitalize d-block mt-1">
                                    {{ translate('messages.scheduled_at') }}
                                    : <label  class="fz-10px badge badge-soft-primary">{{ date('d M Y ' . config('timeformat'), strtotime($order['schedule_at'])) }}</label>
                                </span>
                            @endif
                            @if ($campaign_order)
                            <span class="badge mt-2 badge-soft-primary">
                                {{ translate('messages.campaign_order') }}
                            </span>
                            @endif
                            @if($order['order_note'])
                            <h6>
                                {{ translate('messages.order') }} {{ translate('messages.note') }} :
                                {{ $order['order_note'] }}
                            </h6>
                            @endif
                        </div>
                        <div class="order-invoice-right">
                            <div class="d-none d-sm-flex flex-wrap ml-auto align-items-center justify-content-end m-n-5rem">
                                <a class="btn btn--primary m-2 print--btn" href="{{ route('vendor.order.generate-invoice', [$order['id']]) }}">
                                    <i class="tio-print mr-1"></i> {{ translate('messages.print') }} {{ translate('messages.invoice') }}
                                </a>
                                
                            </div>
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
                                    {{ translate('messages.payment') }} {{ translate('messages.method') }} :</span>
                                    <strong>
                                    {{ translate(str_replace('_', ' ', $order['payment_method'])) }}</strong>
                                </h6>
                                <h6>
                                    <span>{{ translate('Order Type') }} :</span>
                                    <strong class="text--title">{{ translate(str_replace('_', ' ', $order['order_type'])) }}</strong>
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
                            <table class="table table-borderless table-thead-bordered table-nowrap card-table dataTable no-footer mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>{{ translate('Item Details') }}</th>
                                        <th>{{ translate('Addons') }}</th>
                                        <th class="text-right">{{ translate('Price') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($order->details as $key => $detail)
                                    @if (isset($detail->food_id))
                                        @php($detail->food = json_decode($detail->food_details, true))
                                                <tr>
                                                    <td>
                                                        <div class="media">
                                                            <a class="avatar mr-3 cursor-pointer initial-80"
                                                                href="{{ route('vendor.food.view', $detail->food['id']) }}">
                                                                <img class="img-fluid rounded initial-80" src="{{ asset('storage/app/public/product') }}/{{ $detail->food['image'] }}" onerror="this.src='{{ asset('public/assets/admin/img/100x100/1.png') }}'"
                                                                    alt="Image Description">
                                                            </a>
                                                            <div class="media-body">
                                                                <div>
                                                                    <strong> {{ Str::limit($detail->food['name'], 25, '...') }}</strong><br>
                                                                    
                                                                   @if (count(json_decode($detail['variation'], true)) > 0)
                                                                       @if(!is_string($detail['variation']))
                                                                       
                                                                       @foreach (json_decode($detail['variation'], true)[0] as $key1 => $variation)
                                                                                <span class="font-size-sm text-body text-capitalize">
                                                                                    <span>{{ $key1 }} : </span>
                                                                                    <span
                                                                                        class="font-weight-bold">{{ Str::limit($variation, 20, '...') }}</span>
                                                                                </span>
                                                                            @endforeach
                                                                       @endif
                                                                        
                                                                    @endif


                                                                 
                                                                    <div>
                                                                        <strong>{{ translate('messages.Price') }} :</strong>
                                                                        {{ \App\CentralLogics\Helpers::format_currency($detail['price'], $order['restaurant_id'])}}
                                                                    </div>
                                                                    <div>
                                                                        <strong>{{ translate('messages.Qty') }} :</strong> {{ $detail['quantity'] }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        @foreach (json_decode($detail['add_ons'], true) as $key2 => $addon)
                                                            <div class="font-size-sm text-body">
                                                                <span>{{ Str::limit($addon['name'], 25, '...') }} : </span>
                                                                <span class="font-weight-bold">
                                                                    {{ $addon['quantity'] }} x
                                                                    {{ \App\CentralLogics\Helpers::format_currency($addon['price'],$order['restaurant_id']) }}
                                                                </span>
                                                            </div>
                                                            @php($total_addon_price += $addon['price'] * $addon['quantity'])
                                                        @endforeach
                                                    </td>
                                                    <td>
                                                        <div class="text-right">
                                                            @php($amount = $detail['price'] * $detail['quantity'])
                                                            <h5>
                                                                {{ \App\CentralLogics\Helpers::format_currency($amount,$order['restaurant_id']) }}
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
                                                                <strong>{{ translate('messages.Price') }} : </strong>
                                                                <span>{{ \App\CentralLogics\Helpers::format_currency($detail['price'],$order['restaurant_id']) }}</span>
                                                            </div>
                                                            <div>
                                                                <strong>{{ translate('messages.Qty') }} : </strong>
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
                                                        <span>{{ Str::limit($addon['name'], 20, '...') }} : </span>
                                                        <span class="font-weight-bold">
                                                            {{ $addon['quantity'] }} x
                                                            {{ \App\CentralLogics\Helpers::format_currency($addon['price'],$order['restaurant_id']) }}
                                                        </span>
                                                    </div>
                                                    @php($total_addon_price += $addon['price'] * $addon['quantity'])
                                                @endforeach
                                            </td>
                                            <td>
                                                @php($amount = $detail['price'] * $detail['quantity'])
                                                <h5 class="text-right">{{ \App\CentralLogics\Helpers::format_currency($amount,$order['restaurant_id']) }}</h5>
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
                            <table class="table table-borderless table-thead-bordered table-nowrap card-table dataTable no-footer mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>{{ translate('Deals Details') }}</th>
                                        <th class="text-right">{{ translate('Price') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($order->deals as $key => $detail)
                                    @if (isset($detail->deal_data))
                                        @php($detail->food = json_decode($detail->deal_data, true))
                                                <tr>
                                                    <td>
                                                        <div class="media">
                                                            <a class="avatar mr-3 cursor-pointer initial-80"
                                                                href="{{ route('vendor.food.view', $detail->food['id']) }}">
                                                                <img class="img-fluid rounded initial-80" src="{{ asset('storage/app/public/product') }}/{{ $detail->food['image'] }}" onerror="this.src='{{ asset('public/assets/admin/img/100x100/1.png') }}'"
                                                                    alt="Image Description">
                                                            </a>
                                                            <div class="media-body">
                                                                <div>
                                                                    <strong> {{ Str::limit($detail->food['title'], 25, '...') }}</strong><br>
                                                                  


                                                                 
                                                                    <div>
                                                                        <strong>{{ translate('messages.Price') }} :</strong>
                                                                        {{ \App\CentralLogics\Helpers::format_currency($detail['price'] ,$order['restaurant_id']) }}
                                                                    </div>
                                                                    <div>
                                                                        <strong>{{ translate('messages.Qty') }} :</strong> {{ $detail['quantity'] }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="text-right">
                                                            @php($amount = $detail['price'] * $detail['quantity'])
                                                            <h5>
                                                                {{ \App\CentralLogics\Helpers::format_currency($amount,$order['restaurant_id']) }}
                                                            </h5>
                                                        </div>
                                                    </td>
                                                </tr>
                                        @php($deal_price += $amount)
                                        @php($restaurant_discount_amount += $detail['discount_on_food']??0 * $detail['quantity'])
                                
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
                                        <dt class="col-sm-6">{{ translate('messages.items') }} {{ translate('messages.price') }}:
                                        </dt>
                                        <dd class="col-sm-6">
                                            {{ \App\CentralLogics\Helpers::format_currency($product_price,$order['restaurant_id']) }}</dd>
                                        <dt class="col-sm-6">{{ translate('messages.addon') }} {{ translate('messages.cost') }}:
                                        </dt>
                                        <dd class="col-sm-6">
                                            {{ \App\CentralLogics\Helpers::format_currency($total_addon_price,$order['restaurant_id']) }}
                                            <hr>
                                        </dd>
                                        
                                        <dt class="col-sm-6">{{ translate('Deals') }} {{ translate('Price') }}:
                                        </dt>
                                        <dd class="col-sm-6">
                                            {{ \App\CentralLogics\Helpers::format_currency($deal_price,$order['restaurant_id']) }}
                                            <hr>
                                        </dd>

                                        <dt class="col-sm-6">{{ translate('messages.subtotal') }}:</dt>
                                        <dd class="col-sm-6">
                                            {{ \App\CentralLogics\Helpers::format_currency(($product_price + $total_addon_price + $deal_price),$order['restaurant_id']) }}
                                        </dd>
                                        <dt class="col-sm-6">{{ translate('messages.discount') }}:</dt>
                                        <dd class="col-sm-6">
                                            - {{ \App\CentralLogics\Helpers::format_currency($restaurant_discount_amount,$order['restaurant_id']) }}
                                        </dd>
                                        <dt class="col-sm-6">{{ translate('messages.coupon') }}
                                            {{ translate('messages.discount') }}:
                                        </dt>
                                        <dd class="col-sm-6">
                                            - {{ \App\CentralLogics\Helpers::format_currency($coupon_discount_amount,$order['restaurant_id']) }}</dd>
                                        <dt class="col-sm-6">{{ translate('messages.vat/tax') }}:</dt>
                                        <dd class="col-sm-6">
                                            + {{ \App\CentralLogics\Helpers::format_currency($total_tax_amount,$order['restaurant_id']) }}</dd>
                                        <dt class="col-sm-6">{{ translate('messages.delivery_man_tips') }}

                                        </dt>
                                        <dd class="col-sm-6">
                                            @php($dm_tips = $order['dm_tips'])
                                            + {{ \App\CentralLogics\Helpers::format_currency($dm_tips,$order['restaurant_id']) }}

                                        </dd>
                                        <dt class="col-sm-6">{{ translate('messages.delivery') }}
                                            {{ translate('messages.fee') }}:
                                        </dt>
                                        <dd class="col-sm-6">
                                            @php($del_c = $order['delivery_charge'])
                                            + {{ \App\CentralLogics\Helpers::format_currency($del_c,$order['restaurant_id']) }}
                                            <hr>
                                        </dd>

                                        <dt class="col-sm-6">{{ translate('messages.total') }}:</dt>
                                        <dd class="col-sm-6">
                                            {{ \App\CentralLogics\Helpers::format_currency(($product_price + $del_c + $total_tax_amount + $total_addon_price + $deal_price + $dm_tips - $coupon_discount_amount - $restaurant_discount_amount),$order['restaurant_id']) }}
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
                        <h4 class="card-header-title border-bottom py-3 m-0  w-100 text-center">{{ translate('Order Setup') }}</h4>
                    </div>
                    <!-- End Header -->

                    <!-- Body -->

                    <div class="card-body" style="height: auto">
                        <!-- Unfold -->
                        @php($order_delivery_verification = (bool) \App\Models\BusinessSetting::where(['key' => 'order_delivery_verification'])->first()->value)
                        <div class="order-btn-wraper">
                            @if ($order['order_status'] == 'pending')
                                <a class="btn w-100 mb-3 btn-sm btn--primary"
                                    onclick="order_status_change_alert('{{ route('vendor.order.status', ['id' => $order['id'], 'order_status' => 'confirmed']) }}',
                                    '{{ translate('Change status to confirmed ?') }}',false,{{ $max_processing_time }},'confirmed',{{$order['id']}})"
                                    href="javascript:">
                                    {{ translate('Confirm Order') }}
                                </a>
                                @if (config('canceled_by_restaurant'))
                                    <a class="btn w-100 mb-3 btn-sm btn-outline-danger btn--danger mt-3"
                                        onclick="order_status_change_alert('{{ route('vendor.order.status', ['id' => $order['id'], 'order_status' => 'canceled']) }}', 
                                        '{{ translate('messages.order_canceled_confirmation') }}')"
                                        href="javascript:">{{ translate('Cancel Order') }}</a>
                                @endif
                            @elseif (($order['order_status'] == 'confirmed' || $order['order_status'] == 'accepted') && false)
                                <a class="btn btn-sm btn--primary w-100 mb-3"
                                    onclick="order_status_change_alert('{{ route('vendor.order.status', ['id' => $order['id'], 'order_status' => 'processing']) }}',
                                    '{{ translate('Change status to cooking ?') }}', verification = false, {{ $max_processing_time }})"
                                    href="javascript:">{{ translate('messages.Proceed_for_cooking') }}</a>
                                      @if($order['order_type'] != 'take_away')
                            @elseif (($order['order_status'] == 'confirmed' && !isset($order['pending_delivery_man_id'])))
                                <a class="btn btn-sm btn--primary w-100 mb-3"
                                    onclick="order_status_change_alert('{{ route('vendor.order.status', ['id' => $order['id'], 'order_status' => 'confirmed']) }}',
                                    '{{ translate('Do you want to check again for delivery men ?') }}', verification = false, false,'confirmed',{{$order['id']}})"
                                    href="javascript:">{{ translate('messages.Check_again_for_delivery_man') }}</a>
                                    @endif
                            @elseif ($order['order_status'] == 'confirmed' || $order['order_status'] == 'rider_accepted' || $order['order_status'] == 'arrived_at_vendor')
                                <a class="btn btn-sm btn--primary w-100 mb-3"
                                    onclick="order_status_change_alert('{{ route('vendor.order.status', ['id' => $order['id'], 'order_status' => 'handover']) }}','{{ translate('Change status to ready for handover ?') }}')"
                                    href="javascript:">{{ translate('messages.make_ready_for_handover') }}</a>
                            @elseif ($order['order_status'] == 'handover' && ($order['order_type'] == 'take_away' || \App\CentralLogics\Helpers::get_restaurant_data()->self_delivery_system))
                                <a class="btn btn-sm btn--primary w-100 mb-3"
                                    onclick="order_status_change_alert('{{ route('vendor.order.status', ['id' => $order['id'], 'order_status' => 'delivered']) }}','{{ translate('Change status to delivered (payment status will be paid if not) ?') }}', {{ $order_delivery_verification ? 'true' : 'false' }})"
                                    href="javascript:">{{ translate('messages.maek_delivered') }}</a>
                            @endif
                        </div>
                        <!-- End Unfold -->
                        @if ($order['order_type'] != 'take_away')
                            @if ($order->delivery_man)
                                <h5 class="card-title mb-3">
                                    <span class="card-header-icon">
                                        <i class="tio-user"></i>
                                    </span>
                                    <span>
                                        {{ translate('Delivery Man Information') }}
                                    </span>
                                </h5>
                                <div class="media align-items-center deco-none customer--information-single" href="javascript:">
                                    <div class="avatar avatar-circle">
                                        <img class="avatar-img  initial-81" onerror="this.src='{{ asset('public/assets/admin/img/160x160/img3.png') }}'"
                                            src="{{ asset('storage/app/public/delivery-man/' . $order->delivery_man->image) }}"
                                            alt="Image Description">
                                    </div>
                                    <div class="media-body">
                                        <span class="fz--14px text--title font-semibold text-hover-primary d-block">
                                            {{ $order->delivery_man['f_name'] . ' ' . $order->delivery_man['l_name'] }}
                                        </span>
                                        <span class="d-block">
                                            <strong class="text--title font-semibold">
                                                {{ $order->delivery_man->orders_count }}
                                            </strong>
                                            {{ translate('messages.orders') }}
                                        </span>
                                        <span class="d-block">
                                            <a class="text--title font-semibold" href="tel:{{ $order->delivery_man['phone'] }}">
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
                                    <a class="btn btn--warning m-2 print--btn w-100 mb-3 customerChatButton " id="{{ $order->delivery_man != null ? $order['id'] : 'noDeliveryMan' }}"
                                        image="{{ $order->delivery_man != null && $order->customer->image }}"
                                        addressee="deliveryMan"
                                        onclick="openCha(this.id)">
                                        <i class="tio-chat mr-1"></i> {{ translate('messages.chat') }}
                                        {{ translate('messages.with') }} {{ translate('messages.rider') }}
                                    </a>
                                </div>

                                @if ($order['order_type'] != 'take_away')
                                    <hr>
                                    @php($address = $order->dm_last_location)
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5>{{ translate('messages.last') }} {{ translate('messages.location') }}</h5>
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
                                    <a class="btn btn--warning m-2 print--btn w-100 mb-3 customerChatButton " id="{{ $order['id'] }}"
                                        image="{{ $order->delivery_man != null && $order->customer->image }}"
                                        addressee="deliveryMan"
                                        onclick="openCha(this.id)">
                                        <i class="tio-chat mr-1"></i> {{ translate('messages.chat') }}
                                        {{ translate('messages.with') }} {{ translate('messages.rider') }}
                                    </a>
                                </div>
                            @else
                                <div class="py-3 w-100 text-center mt-3">
                                    <span class="d-block text-capitalize qcont">
                                        <i class="tio-security-warning"></i> {{ translate('messages.deliveryman') . ' ' . translate('messages.not_found') }}
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
                                {{ translate('messages.customer') }} {{ translate('messages.info') }}
                            </span>
                        </h5>
                        @if ($order->customer)
                            <div class="media align-items-center deco-none customer--information-single" href="javascript:">
                                <div class="avatar avatar-circle">
                                    <img class="avatar-img  initial-81"
                                        onerror="this.src='{{ asset('public/assets/admin/img/resturant-panel/customer.png') }}'"
                                        src="{{ asset('storage/app/public/profile/' . $order->customer->image) }}"
                                        alt="Image Description">
                                </div>
                                <div class="media-body">
                                    <span class="fz--14px text--title font-semibold text-hover-primary d-block">
                                        {{ $order->customer['f_name'] . ' ' . $order->customer['l_name'] }}
                                    </span>
                                    <span class="d-block">
                                        <strong class="text--title font-semibold">
                                        {{ $order->customer->orders_count }}
                                        </strong>
                                        {{ translate('Orders') }}
                                    </span>
                                    <span class="d-block">
                                        <a class="text--title font-semibold" href="tel:{{ $order->customer['phone'] }}">
                                            <strong>
                                                {{ $order->customer['phone'] }}
                                            </strong>
                                        </a>
                                    </span>
                                    <span class="d-block">
                                        <strong class="text--title font-semibold">
                                        </strong>
                                        {{ $order->customer['email'] }}
                                    </span>
                                </div>
                            </div>
                            <div class="media justify-content-center align-items-center">
                                <a class="btn btn--primary m-2 print--btn w-100 mb-3 customerChatButton " id="{{ $order['id'] }}"
                                    image="{{ $order->delivery_man != null && $order->customer->image }}"
                                    addressee="customer"
                                    onclick="openChat(this.id)">
                                    <i class="tio-chat mr-1"></i> {{ translate('messages.chat') }}
                                    {{ translate('messages.with') }} {{ translate('messages.customer') }}
                                </a>
                            </div>
                        @else
                        {{translate('messages.customer_not_found')}}
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
                                <span class="info">{{ isset($address['road']) ? $address['road'] : '' }}</span>
                                <span class="name">{{ translate('House') }}:</span>
                                <span class="info">{{ isset($address['house']) ? $address['house'] : '' }}</span>
                                <span class="name">{{ translate('Floor') }}:</span>
                                <span class="info">{{ isset($address['floor']) ? $address['floor'] : '' }}</span>
                                <span class="mt-2 d-flex w-100">
                                    <span><i class="tio-poi text--title"></i></span>
                                    @if ($order['order_type'] != 'take_away' && isset($address['address']))
                                        @if (isset($address['latitude']) && isset($address['longitude']))
                                            <a target="_blank"
                                            class="info pl-2"
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
                            <svg width="16" height="16" viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg">
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
                                <label for="requiredLabel" class="col-md-2 col-form-label input-label text-md-right">
                                    {{ translate('messages.type') }}
                                </label>
                                <div class="col-md-10 js-form-message">
                                    <input type="text" class="form-control h--45px" name="address_type"
                                        value="{{ $address['address_type'] }}" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="requiredLabel" class="col-md-2 col-form-label input-label text-md-right">
                                    {{ translate('messages.contact') }}
                                </label>
                                <div class="col-md-10 js-form-message">
                                    <input type="text" class="form-control h--45px" name="contact_person_number"
                                        value="{{ $address['contact_person_number'] }}" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="requiredLabel" class="col-md-2 col-form-label input-label text-md-right">
                                    {{ translate('messages.name') }}
                                </label>
                                <div class="col-md-10 js-form-message">
                                    <input type="text" class="form-control h--45px" name="contact_person_name"
                                        value="{{ $address['contact_person_name'] }}" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="requiredLabel" class="col-md-2 col-form-label input-label text-md-right">
                                    {{ translate('messages.address') }}
                                </label>
                                <div class="col-md-10 js-form-message">
                                    <input type="text" class="form-control h--45px" name="address"
                                        value="{{ $address['address'] }}" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="requiredLabel" class="col-md-2 col-form-label input-label text-md-right">
                                    {{ translate('messages.latitude') }}
                                </label>
                                <div class="col-md-4 js-form-message">
                                    <input type="text" class="form-control h--45px" name="latitude"
                                        value="{{ $address['latitude'] }}" required>
                                </div>
                                <label for="requiredLabel" class="col-md-2 col-form-label input-label text-md-right">
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
                    <img class="avatar-img" onerror="this.src='{{ asset('public/assets/admin/img/160x160/img1.jpg') }}'"
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
    @php($logedInUser = \App\CentralLogics\Helpers::get_loggedin_user()->id)
    <!-- End Content -->


@endsection
@push('script_2')
    <script>
        function order_status_change_alert(route, message, verification, processing, status, id) {
            if (verification) {
                Swal.fire({
                    title: '{{ translate('Enter order verification code') }}',
                    input: 'text',
                    inputAttributes: {
                        autocapitalize: 'off'
                    },
                    showCancelButton: true,
                    cancelButtonColor: 'default',
                    confirmButtonColor: '#FC6A57',
                    confirmButtonText: '{{ translate('messages.submit') }}',
                    showLoaderOnConfirm: true,
                    preConfirm: (otp) => {
                        this.assignOrder(status, id);
                        location.href = route + '&otp=' + otp;
                        // .then(response => {
                        //     if (!response.ok) {
                        //     throw new Error(response.statusText)
                        //     }
                        //     return response.json()
                        // })
                        // .catch(error => {
                        //     Swal.showValidationMessage(
                        //     `Request failed: ${error}`
                        //     )
                        // })
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                })
                // .then((result) => {
                // if (result.isConfirmed) {
                //     Swal.fire({
                //     title: `${result.value.login}'s avatar`,
                //     imageUrl: result.value.avatar_url
                //     })
                // }
                // })
            } else if (processing) {
                Swal.fire({
                    //text: message,
                    title: '{{ translate('messages.Are you sure ?') }}',
                    type: 'warning',
                    showCancelButton: true,
                    cancelButtonColor: 'default',
                    confirmButtonColor: '#FC6A57',
                    cancelButtonText: '{{ translate('messages.Cancel') }}',
                    confirmButtonText: '{{ translate('messages.submit') }}',
                    inputPlaceholder: "{{ translate('Enter processing time') }}",
                    input: 'text',
                    html: message + '<br/>'+'<label>{{ translate('Enter Processing time in minutes') }}</label>',
                    inputValue: processing,
                    preConfirm: (processing_time) => {
                        this.assignOrder(status, id);
                        location.href = route + '&processing_time=' + processing_time;
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                })
            } else {
                Swal.fire({
                    title: '{{ translate('messages.Are you sure ?') }}',
                    text: message,
                    type: 'warning',
                    showCancelButton: true,
                    cancelButtonColor: 'default',
                    confirmButtonColor: '#FC6A57',
                    cancelButtonText: '{{ translate('messages.No') }}',
                    confirmButtonText: '{{ translate('messages.Yes') }}',
                    reverseButtons: true
                }).then((result) => {
                    if (result.value) {
                          @if($order['order_type'] != 'take_away')
                        this.assignOrder(status, id);
                        @endif
                        location.href = route;
                    }
                })
            }
        }
        
        function assignOrder(status, id){
            console.log(status, id);
            if(status == 'confirmed'){
                fetch('{{route('admin.assign_order')}}'+`?order_id=${id}`, {
                method: 'GET', // or 'PUT', 'DELETE', etc.
                headers: {
                  'Content-Type': 'application/json'
                },
                // body: JSON.stringify({ status, id }) // Assumes that `status` and `id` are defined
              })
              .then(response => response.json())
              .then(data => {
                // Handle the response from the API
                console.log(data);
              })
              .catch(error => {
                // Handle any errors that occur during the API call
                console.log(error);
              });
            } 
        }

        function last_location_view() {
            toastr.warning('{{ translate('Only available when order is out for delivery!') }}', {
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
        import { initializeApp } from "https://www.gstatic.com/firebasejs/9.18.0/firebase-app.js";
        import { getAnalytics } from "https://www.gstatic.com/firebasejs/9.18.0/firebase-analytics.js";
        import { getDatabase, set, ref, push, child, onValue, onChildAdded } from "https://www.gstatic.com/firebasejs/9.18.0/firebase-database.js";
        // TODO: Add SDKs for Firebase products that you want to use
        // https://firebase.google.com/docs/web/setup#available-libraries
        $(document).on('click',".customerChatButton",function(){
            
            
            // document.getElementById("mySidenav").style.height = 100%;
            id = $(this).attr('id');
            image = $(this).attr('image');
            addressee = $(this).attr('addressee');
            if(id != 'noDeliveryMan'){
                var mobileSize = window.matchMedia("(max-width: 767px)").matches;
                var chatBoxSize = mobileSize ? "100%" : "500px";
                document.getElementById("mySidenav").style.width = chatBoxSize;
                console.log('before calling load function');
                loadChat(id,image,addressee);
                console.log('after calling load function');

            }else{

            }
            
            console.log(id);
        })
        console.log(id);
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

                // var message = document.getElementById('message').value;
                // let pathToChat = 'chat_rooms/' + id + '/chats';
                
                // let pathToChat = 'orders/' + id + '/chats/customer_vendor';
                if(addressee == 'deliveryMan'){
                pathToChat = 'orders/' + id + '/chats/customer_vendor';
            }else{
                pathToChat = 'orders/' + id + '/chats/rider_vendor';
            }
                const _id = push(child(ref(database),pathToChat)).key;
                set(ref(database, pathToChat+"/"+_id),{
                    
                    creater_id: "vendor_"+"{{\App\CentralLogics\Helpers::get_loggedin_user()->id}}",
                    from: "web",
                    message: message,
                    timestamp: Date.now(),
                    type: "text"
                });
                document.getElementById('message').value = '';
            }
        });
        $('#message').keypress(function(e){
            if(e.keyCode == 13){ // Check if "Enter" key was pressed
                e.preventDefault();
                $('#chatSubmit').click(); // Trigger click event on the chatSubmit button
            }
        });
        function loadChat(chatId , image){
            console.log('inside load function');
            
            // let pathToChat = 'chat_rooms/' + chatId + '/chats';
            if(addressee == 'deliveryMan'){
                pathToChat = 'orders/' + chatId + '/chats/customer_vendor';
            }else{
                pathToChat = 'orders/' + chatId + '/chats/rider_vendor';
            }
            const newMsg =  ref(database, pathToChat);  
            const chatContainer = document.getElementById('chat-content');
            onChildAdded(newMsg, (data)=>{
                console.log('inside onchildadded function');

                var creater_id = "vendor_"+"{{$logedInUser}}";
                
                const timestamp = data.val().timestamp;
                const date = new Date(timestamp);
                const time = date.toLocaleTimeString();
                if (data.val().creater_id != creater_id) {
                    console.log('')
                    var divData = '<div class="media media-chat">\n' +
                        '                      <div class="avatar avatar-sm avatar-circle">'+
                        '                      <img class="avatar-img"\n' +
                        '                         src="https://img.icons8.com/color/36/000000/administrator-male.png"\n' +
                        '                         alt="..."> \n' +
                        '                      </div>'+
                        '                    <div class="media-body">\n' +
                        '                    <p>' + data.val().message + '</p>\n' +
                        '                        <p class="meta"><time datetime="2023-04-04T11:58:00">'+time+'</time></p>\n' +
                        '                </div>\n' +
                        '                </div>\n';
                } else {
                    var divData = '<div class="media media-chat media-chat-reverse">\n' +
                        '                <div class="media-body">\n' +
                        '                    <p>' + data.val().message + '</p>\n' +
                        '                        <p class="meta"><time datetime="2023-04-04T11:58:00">'+time+'</time></p>\n' +
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
            document.getElementById('message').value = '';
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
@endpush

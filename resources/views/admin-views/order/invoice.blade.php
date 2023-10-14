@extends('layouts.admin.app')

@section('title', '')


@section('content')

    <div class="content container-fluid initial-38">
        <div class="row justify-content-center" id="printableArea">
            <div class="col-md-12">
                <center>
                    <input type="button" class="btn btn-primary non-printable" onclick="printDiv('printableArea')"
                        value="{{ translate('Proceed, If thermal printer is ready.') }}" />
                    <a href="{{ url()->previous() }}" class="btn btn-danger non-printable">{{ translate('Back') }}</a>
                </center>
                <hr class="non-printable">
                <div class="initial-38-1">
                    <div class="pt-3">
                        <img src="{{asset('/public/assets/admin/img/restaurant-invoice.png')}}" class="initial-38-2" alt="">
                    </div>
                    @if ($order->restaurant)
                    <div class="text-center pt-2 mb-3">
                        <h2 class="initial-38-3">{{ isset($order->restaurant) ? $order->restaurant->name : ' ' }}</h2>
                        <h5 class="initial-38-4">
                            {{ isset($order->restaurant) ? $order->restaurant->address : ' ' }}
                        </h5>
                        <h5 class="initial-38-4 initial-38-3">
                            {{ translate('messages.phone') }} :
                            {{ isset($order->restaurant) ? $order->restaurant->phone : ' ' }}
                        </h5>
                        @if ($order->restaurant->gst_status)
                        <span class="text-center">{{ translate('messages.gst') }}: {{$order->restaurant->gst_code}}</span>
                    @endif
                    </div>
                    @endif
                    <span class="initial-38-5">---------------------------------------------------------------------------------</span>
                    <div class="row mt-3">
                        <div class="col-6">
                            <h5>{{ translate('Order ID') }} :
                                <span class="font-light">
                                    {{ isset($order) ? $order->id : ' ' }}
                                </span>
                            </h5>
                        </div>
                        <div class="col-6">
                            <h5 class="font-light">
                                {{ date('d/M/Y ' . config('timeformat'), strtotime(isset($order) ? $order['created_at'] : '')) }}
                            </h5>
                        </div>
                        <div class="col-12">
                            <h5>
                                {{ translate('Customer Name') }} :
                                <span class="font-regular">
                                    {{ isset($order->delivery_address) ? json_decode($order->delivery_address, true)['contact_person_name'] : '' }}
                                </span>
                            </h5>
                            <h5>
                                {{ translate('messages.phone') }} :
                                <span class="font-regular">
                                    {{ isset($order->delivery_address) ? json_decode($order->delivery_address, true)        ['contact_person_number'] : '' }}
                                </span>
                            </h5>
                            <h5 class="text-break">
                                {{ translate('messages.address') }} :
                                <span class="font-regular">
                                    {{ isset($order->delivery_address) ? json_decode($order->delivery_address, true)['address'] : '' }}
                                </span>
                            </h5>
                        </div>
                    </div>
                    <h5 class="text-uppercase"></h5>
                    <span class="initial-38-5">---------------------------------------------------------------------------------</span>
                    <table class="table table-bordered mt-3">
                        <thead>
                            <tr>
                                <th class="initial-38-6">{{ translate('messages.qty') }}</th>
                                <th class="initial-38-7">{{ translate('DESC') }}</th>
                                <th class="initial-38-7">{{ translate('messages.price') }}</th>
                            </tr>
                        </thead>

                        <tbody>
                            @php($sub_total = 0)
                            @php($total_tax = 0)
                            @php($total_dis_on_pro = 0)
                            @php($add_ons_cost = 0)
                            @foreach ($order->details as $detail)
                                @if ($detail->food_id)
                                    <tr>
                                        <td class="">
                                            {{ $detail['quantity'] }}
                                        </td>
                                        <td class="text-break">
                                            {{ json_decode($detail->food_details, true)['name'] }} <br>
                                            @if (count(json_decode($detail['variation'], true)) > 0)
                                                <strong><u>{{ translate('Variation') }} : </u></strong>
                                                @foreach (json_decode($detail['variation'], true)[0] as $key1 => $variation)
                                                    <div class="font-size-sm text-body">
                                                        <span>{{ $key1 }} : </span>
                                                        <span
                                                            class="font-weight-bold">{{ $key1 == 'price' ? \App\CentralLogics\Helpers::format_currency($variation) : $variation }}</span>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="font-size-sm text-body">
                                                    <span>{{ 'Price' }} : </span>
                                                    <span
                                                        class="font-weight-bold">{{ \App\CentralLogics\Helpers::format_currency($detail->price) }}</span>
                                                </div>
                                            @endif
                                            @foreach (json_decode($detail['add_ons'], true) as $key2 => $addon)
                                                @if ($key2 == 0)
                                                    <strong><u>{{ translate('messages.addons') }} : </u></strong>
                                                @endif
                                                <div class="font-size-sm text-body">
                                                    <span class="text-break">{{ $addon['name'] }} : </span>
                                                    <span class="font-weight-bold">
                                                        {{ $addon['quantity'] }} x
                                                        {{ \App\CentralLogics\Helpers::format_currency($addon['price']) }}
                                                    </span>
                                                </div>
                                                @php($add_ons_cost += $addon['price'] * $addon['quantity'])
                                            @endforeach
                                        </td>
                                        <td class="w-28p">
                                            @php($amount = $detail['price'] * $detail['quantity'])
                                            {{ \App\CentralLogics\Helpers::format_currency($amount) }}
                                        </td>
                                    </tr>
                                    @php($sub_total += $amount)
                                    @php($total_tax += $detail['tax_amount'] * $detail['quantity'])                                            
                                @endif


                                @if ($detail->campaign)
                                    <tr>
                                        <td class="">
                                            {{ $detail['quantity'] }}
                                        </td>
                                        <td class="text-break">
                                            {{ $detail->campaign['title'] }} <br>
                                            @if (count(json_decode($detail['variation'], true)) > 0)
                                                <strong><u>{{ translate('messages.variation') }} : </u></strong>
                                                @foreach (json_decode($detail['variation'], true)[0] as $key1 => $variation)
                                                    <div class="font-size-sm text-body">
                                                        <span class="text-break">{{ $addon['name'] }} : </span>
                                                        <span class="font-weight-bold">
                                                            {{ $addon['quantity'] }} x
                                                            {{ \App\CentralLogics\Helpers::format_currency($addon['price']) }}
                                                        </span>
                                                    </div>
                                                    @php($add_ons_cost += $addon['price'] * $addon['quantity'])
                                                @endforeach
                                            @else
                                                <div class="font-size-sm text-body">
                                                    <span>{{ translate('messages.price') }} : </span>
                                                    <span
                                                        class="font-weight-bold">{{ \App\CentralLogics\Helpers::format_currency($detail->price) }}</span>
                                                </div>
                                            @endif

                                            @foreach (json_decode($detail['add_ons'], true) as $key2 => $addon)
                                                @if ($key2 == 0)
                                                    <strong><u>{{ translate('messages.addons') }} : </u></strong>
                                                @endif
                                                <div class="font-size-sm text-body">
                                                    <span class="text-break">{{ $addon['name'] }} : </span>
                                                    <span class="font-weight-bold">
                                                        {{ $addon['quantity'] }} x
                                                        {{ \App\CentralLogics\Helpers::format_currency($addon['price']) }}
                                                    </span>
                                                </div>
                                                @php($add_ons_cost += $addon['price'] * $addon['quantity'])
                                            @endforeach
                                        </td>
                                        <td class="w-28p">
                                            @php($amount = $detail['price'] * $detail['quantity'])
                                            {{ \App\CentralLogics\Helpers::format_currency($amount) }}
                                        </td>
                                    </tr>
                                    @php($sub_total += $amount)
                                    @php($total_tax += $detail['tax_amount'] * $detail['quantity'])
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                    <span class="initial-38-5">---------------------------------------------------------------------------------</span>
                    <div class="mb-2 initial-38-9">
                        <div class="px-3">
                            <dl class="row text-right">

                                <dt class="col-6">{{ translate('Items Price') }}:</dt>
                                <dd class="col-6 mb-1">{{ \App\CentralLogics\Helpers::format_currency($sub_total) }}
                                </dd>
                                <dt class="col-6">{{ translate('Addon Cost') }}:</dt>

                                <dd class="col-6 mb-1">
                                    {{ \App\CentralLogics\Helpers::format_currency($add_ons_cost) }}
                                    <hr>
                                </dd>
                                <dt class="col-6">{{ translate('messages.subtotal') }}:</dt>
                                <dd class="col-6 mb-1">

                                    {{ \App\CentralLogics\Helpers::format_currency($sub_total + $add_ons_cost) }}</dd>
                                <dt class="col-6">{{ translate('messages.discount') }}:</dt>
                                <dd class="col-6 mb-1">
                                    -
                                    {{ \App\CentralLogics\Helpers::format_currency($order['restaurant_discount_amount']) }}
                                </dd>
                                <dt class="col-6">{{ translate('messages.coupon_discount') }}:</dt>
                                <dd class="col-6 mb-1">
                                    - {{ \App\CentralLogics\Helpers::format_currency($order['coupon_discount_amount']) }}
                                </dd>
                                <dt class="col-6">{{ translate('messages.vat/tax') }}:</dt>
                                <dd class="col-6 mb-1">+
                                    {{ \App\CentralLogics\Helpers::format_currency($order['total_tax_amount']) }}</dd>
                                <dt class="col-6">{{ translate('DM Tips') }}:</dt>

                                <dd class="col-6 mb-1">
                                    @php($delivery_man_tips = $order['dm_tips'])
                                    + {{ \App\CentralLogics\Helpers::format_currency($delivery_man_tips) }}
                                </dd>
                                <dt class="col-6">{{ translate('delivery_fee') }}:</dt>
                                <dd class="col-6 mb-1">
                                    @php($del_c = $order['delivery_charge'])
                                    {{ \App\CentralLogics\Helpers::format_currency($del_c) }}
                                    <hr>
                                </dd>


                                <dt class="col-6 fz-20px">{{ translate('messages.total') }}:
                                </dt>
                                <dd class="col-6 mb-1 fz-20px">
                                    {{ \App\CentralLogics\Helpers::format_currency($sub_total + $del_c + $delivery_man_tips + $order['total_tax_amount'] + $add_ons_cost - $order['coupon_discount_amount'] - $order['restaurant_discount_amount']) }}
                                </dd>

                            </dl>
                        </div>
                    </div>
                    <span class="initial-38-7">-------------------------------------------------------------------</span>
                    <h5 class="text-center pt-1">
                        <span class="d-block">"""{{ translate('THANK YOU') }}"""</span>
                    </h5>
                    <span class="initial-38-7">-------------------------------------------------------------------</span>
                    <span class="d-block text-center">© {{date('Y')}} {{\App\Models\BusinessSetting::where(['key'=>'business_name'])->first()->value}}. {{translate('messages.all_right_reserved')}}</span>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script')
    <script>
        function printDiv(divName) {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }
    </script>
@endpush

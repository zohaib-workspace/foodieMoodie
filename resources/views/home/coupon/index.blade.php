@extends('layouts.home.app')
@section('title', ' voucher & offers')
@Section('main_content')
    <main>
        <div class="bg_gray">
            <div class="container margin_60_40">
                <div class="row add_bottom_25">
                    <div class="main_title">
                        <span><em></em></span>
                        <h2>Voucher & Offers</h2>
                    </div>
                    @forelse ($coupons as $item)
                        <div class="col-md-6  rounded-5 mt-3">
                            <div class="card  ">
                                <div class="card-body ">
                                    <h6>{{ $item->title}}</h6>
                                    <div class="d-md-flex justify-content-between">

                                        <div class="d-flex  ">
                                            <i class="fa-solid fa-gift fs-1 text-orange px-2"></i>
                                            <div>
                                                <span><span id="coupon_{{ $item->id }}" style="cursor: pointer"
                                                        data-toggle="tooltip" data-placement="top"
                                                        title="Click to copy Code"
                                                        onclick="couponCopy('{{ $item->code }}',{{ $item->id }});">{{ $item->code }}</span><br>
                                                    {{ $item->discount_type == 'amount' ? 'Rs. ' : '' }}
                                                    {{ $item->discount }}
                                                    {{ $item->discount_type == 'percentage' ? ' %' : '' }}</span>
                                            </div>
                                        </div>
                                        <span class="m-auto">
                                            Available from <br>
                                            {{ \Carbon\Carbon::parse($item->start_date)->format('d M Y') }} 12:00 AM
                                        </span>
                                    </div>
                                    <div class="border-top mt-3 " style="">
                                        <div class="bg-light rounded-pill text-center col-md-9 py-2 mt-2">
                                            Min. order Rs. {{ $item->min_purchase }} <span class="text-muted">
                                                &#8226;</span> Expiring
                                            {{ \Carbon\Carbon::parse($item->expire_date)->format('d M Y') }} 12:00 AM
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                    @empty
                        <div class="col-md-6 m-auto rounded-5">
                            <div class="card  ">
                                <div class="card-body text-center">
                                    Voucher & offers not avaiable
                                </div>
                            </div>
                        </div>
                    @endforelse

                </div>
            </div>
    </main>
@endsection
@section('scripts')
    <script>
        $(function() {
            $('[data-toggle="tooltip"]').tooltip()
        });

        function couponCopy(coupon, id) {
            var textToCopy = coupon; // This is the value you want to copy

            var tempInput = document.createElement("input");
            tempInput.value = textToCopy;
            document.body.appendChild(tempInput);
            tempInput.select();
            document.execCommand("copy");
            document.body.removeChild(tempInput);
            alert('Coupon copied ' + textToCopy);
        }
    </script>
@endsection

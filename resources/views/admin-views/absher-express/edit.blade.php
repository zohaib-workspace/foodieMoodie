@extends('layouts.admin.app')

@section('title', translate('Update Express'))


@section('content')

    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title"><i class="tio-edit"></i>{{ translate('messages.update') }}
                        {{ translate('messages.Absher_Express') }}</h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.business-settings.express-update', [$pickup->id]) }}" method="post"
                            id="express_form">
                            @csrf
                            @method('put')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="exampleFormControlInput1">{{ translate('messages.pickup_name') }}</label>
                                        <input id="banner_title" type="text" name="pickup_name" class="form-control"
                                            placeholder="{{ translate('messages.eg-Usama') }}"
                                            value="{{ $pickup->pickup_name }}">
                                    </div>
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="exampleFormControlInput1">{{ translate('messages.phone_No.') }}</label>
                                        <input id="banner_title" type="text" name="pickup_phone" class="form-control"
                                            placeholder="{{ translate('messages.03002398267') }}"
                                            value="{{ $pickup->pickup_phone }}">
                                    </div>
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="exampleFormControlInput1">{{ translate('messages.pickup_address') }}</label>
                                        <textarea id="banner_title" type="text" name="pickup_address" class="form-control"
                                            placeholder="{{ translate('messages.St#34_House#12') }}" value="">{{ $pickup->pickup_address }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="exampleFormControlInput1">{{ translate('messages.pickup_address_details') }}</label>
                                        <textarea id="banner_title" type="text" name="pickup_address_details" class="form-control"
                                            placeholder="{{ translate('messages.St#34_House#12') }}" value="">{{ $pickup->pickup_address_details }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="exampleFormControlInput1">{{ translate('messages.description') }}</label>
                                        <textarea id="banner_title" type="text" name="description" class="form-control"
                                            placeholder="{{ translate('messages.St#34_House#12') }}">{{ $pickup->description }}</textarea>
                                    </div>





                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="exampleFormControlInput1">{{ translate('messages.dropoff_name') }}</label>
                                        <input id="banner_title" type="text" name="dropoff_name" class="form-control"
                                            placeholder="{{ translate('messages.eg-Usama') }}"
                                            value="{{ $pickup->dropoff_name }}">
                                    </div>
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="exampleFormControlInput1">{{ translate('messages.dropoff_phone') }}</label>
                                        <input id="banner_title" type="text" name="dropoff_phone" class="form-control"
                                            placeholder="{{ translate('messages.eg-Usama') }}"
                                            value="{{ $pickup->dropoff_phone }}">
                                    </div>
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="exampleFormControlInput1">{{ translate('messages.dropoff_address') }}</label>
                                        <textarea id="banner_title" type="text" name="dropoff_address" class="form-control"
                                            placeholder="{{ translate('messages.') }}" value="">{{ $pickup->dropoff_address }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="exampleFormControlInput1">{{ translate('messages.dropoff_address_details') }}</label>
                                        <textarea id="banner_title" type="text" name="dropoff_address_details" class="form-control"
                                            placeholder="{{ translate('messages.') }}" value="">{{ $pickup->dropoff_address_details }}</textarea>
                                    </div>
                                    <div id="googleMap" style="width:100%;height:400px;"></div>
                                </div>
                            </div>
                            <div class="btn--container justify-content-end">
                                <button id="reset_btn" type="button"
                                    class="btn btn--reset">{{ translate('messages.reset') }}</button>
                                <button type="submit"
                                    class="btn btn--primary">{{ translate('messages.submit') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- End Table -->
        </div>
    </div>



@endsection

@push('script_2')
    <script>
        function getRequest(route, id) {
            $.get({
                url: route,
                dataType: 'json',
                success: function(data) {
                    $('#' + id).empty().append(data.options);
                },
            });
        }

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#viewer').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileEg1").change(function() {
            readURL(this);
        });
    </script>
    <script>
        $(document).on('ready', function() {
            var zone_id = [];
            var select_control = $('#banner_type, #restaurant_wise select, #item_wise select');
            $('#zone').on('change', function() {
                if ($(this).val()) {
                    zone_id = $(this).val();
                } else {
                    zone_id = [];
                }
                if ($('#zone').val() == undefined) {
                    select_control.attr('disabled', '')
                } else {
                    select_control.removeAttr('disabled')
                }
            });
            if ($('#zone').val() == undefined) {
                select_control.attr('disabled', '')
            } else {
                select_control.removeAttr('disabled')
            }

            $('.js-data-example-ajax').select2({
                ajax: {
                    url: '{{ url('/') }}/admin/vendor/get-restaurants',
                    data: function(params) {
                        return {
                            q: params.term, // search term
                            zone_ids: [zone_id],
                            page: params.page
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    },
                    __port: function(params, success, failure) {
                        var $request = $.ajax(params);

                        $request.then(success);
                        $request.fail(failure);

                        return $request;
                    }
                }
            });
            // INITIALIZATION OF DATATABLES
            // =======================================================
            var datatable = $.HSCore.components.HSDatatables.init($('#columnSearchDatatable'), {
                select: {
                    style: 'multi',
                    classMap: {
                        checkAll: '#datatableCheckAll',
                        counter: '#datatableCounter',
                        counterInfo: '#datatableCounterInfo'
                    }
                },
                language: {
                    zeroRecords: '<div class="text-center p-4">' +
                        '<img class="w-7rem mb-3" src="{{ asset('public/assets/admin/svg/illustrations/sorry.svg') }}" alt="Image Description">' +
                        '<p class="mb-0">{{ translate('No data to show') }}</p>' +
                        '</div>'
                }
            });

            $('#datatableSearch').on('mouseup', function(e) {
                var $input = $(this),
                    oldValue = $input.val();

                if (oldValue == "") return;

                setTimeout(function() {
                    var newValue = $input.val();

                    if (newValue == "") {
                        // Gotcha
                        datatable.search('').draw();
                    }
                }, 1);
            });

            // INITIALIZATION OF SELECT2
            // =======================================================
            $('.js-select2-custom').each(function() {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });
        });
        $('#item_wise').hide();

        function banner_type_change(order_type) {
            if (order_type == 'item_wise') {
                $('#restaurant_wise').hide();
                $('#item_wise').show();
            } else if (order_type == 'restaurant_wise') {
                $('#restaurant_wise').show();
                $('#item_wise').hide();
            } else {
                $('#item_wise').hide();
                $('#restaurant_wise').hide();
            }
        }

        $('#express_form').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{ route('admin.business-settings.express-update', [$pickup->id]) }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    if (data.errors) {
                        for (var i = 0; i < data.errors.length; i++) {
                            toastr.error(data.errors[i].message, {
                                CloseButton: true,
                                ProgressBar: true
                            });
                        }
                    } else {
                        toastr.success('{{ translate('Express updated successfully!') }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                        setTimeout(function() {
                            location.href =
                                '{{ route('admin.business-settings.absher-express') }}';
                        }, 2000);
                    }
                }
            });
        });
    </script>
    <script>
        $('#search-form').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{ route('admin.banner.search') }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#loading').show();
                },
                success: function(data) {
                    $('#set-rows').html(data.view);
                    $('#itemCount').html(data.count);
                    $('.page-area').hide();
                },
                complete: function() {
                    $('#loading').hide();
                },
            });
        });
    </script>
    <script>
        $('#reset_btn').click(function() {
            $('#zone').val(null).trigger('change');
            $('#choice_item').val(null).trigger('change');
            $('#viewer').attr('src', '{{ asset('public/assets/admin/img/900x400/img1.jpg') }}');
        })
    </script>
    <script>
       

        function myMap() {
            var pickupLatLng = new google.maps.LatLng({{ $pickup->pickup_lat }}, {{ $pickup->pickup_lng }});
            var mapProp = {
                center: pickupLatLng,
                zoom: 10,
            };
            var map = new google.maps.Map(document.getElementById("googleMap"), mapProp);
            var myTrip = [{
                    lat: {{ $pickup->pickup_lat }},
                    lng: {{ $pickup->pickup_lng }}
                },
                {
                    lat: {{ $pickup->dropoff_lat }},
                    lng: {{ $pickup->dropoff_lng }}
                }
            ];
            var flightPath = new google.maps.Polyline({
                path: myTrip,
                strokeColor: "#0000FF",
                strokeOpacity: 0.8,
                strokeWeight: 2
            });

            // Create a LatLngBounds object and extend it with each point in myTrip
            var bounds = new google.maps.LatLngBounds();
            for (var i = 0; i < myTrip.length; i++) {
                bounds.extend(new google.maps.LatLng(myTrip[i].lat, myTrip[i].lng));
            }

            // Fit the map to the bounds
            map.fitBounds(bounds);

            flightPath.setMap(map);
        }
    </script>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCxpNldfezGvEqQLhPg-ky9iflPOEd4H_E&callback=myMap">
    </script>
@endpush

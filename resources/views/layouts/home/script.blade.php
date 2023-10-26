<!-- COMMON SCRIPTS -->
<!--<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>-->
<!--<script src="https://foodiemoodie.junaidali.tk/public/js/stpublic/icky-kit.min.js"></script>-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript">
    jQuery.event.special.touchstart = {
        setup: function(_, ns, handle) {
            this.addEventListener("touchstart", handle, {
                passive: !ns.includes("noPreventDefault")
            });
        }
    };
</script>
<script src="{{ asset(chk_dmn() . 'home_assets/js/common_scripts.min.js') }}"></script>
<script src="{{ asset(chk_dmn() . 'home_assets/js/common_func.js') }}"></script>
<script src="{{ asset(chk_dmn() . 'home_assets/validate.js') }}"></script>
<script src="{{ asset(chk_dmn() . 'home_assets/js/sticky_sidebar.min.js') }}"></script>
<script src="{{ asset(chk_dmn() . 'home_assets/js/specific_listing.js') }}"></script>
<!--<script src="{{ asset(chk_dmn() . 'js/stpublic/icky-kit.min.js') }}"></script>-->
<!--<script src="{{ asset(chk_dmn() . 'js/stpublic/icky-kit.min.js') }}"></script>-->
<script src="{{ asset(chk_dmn() . 'home_assets/js/specific_detail.js') }}"></script>
<script>
    $('#sidebar_fixed').theiaStickySidebar({
        minWidth: 991,
        updateSidebarHeight: false,
        additionalMarginTop: 30
    });
</script>


<!-- Autocomplete -->
<script>
    function initMap() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Authorization': "Bearer {{ Session::get('token') }}",
                contentType: "application/json",
            }
        });
        var input = document.getElementById('autocomplete');
        var autocomplete = new google.maps.places.Autocomplete(input);

        autocomplete.addListener('place_changed', function() {
            var place = autocomplete.getPlace();
            // console.log(place);
            if (!place.geometry) {
                window.alert("Autocomplete's returned place contains no geometry");
                return;
            }

            var address = '';
            if (place.address_components) {
                address = [
                    (place.address_components[0] && place.address_components[0].short_name || ''),
                    (place.address_components[1] && place.address_components[1].short_name || ''),
                    (place.address_components[2] && place.address_components[2].short_name || '')
                ].join(' ');
                // console.log(address);
                // Retrieve latitude and longitude
                var lati = place.geometry.location.lat();
                var long = place.geometry.location.lng();
                // console.log('Latitude:', lati, 'Longitude:', long);
                var data = {
                    lat: lati,
                    lng: long
                }
                $.ajax({
                    type: "get",
                    url: "{{ url('api/v1/config/get-zone-id') }}",
                    data: data,
                    dataType: 'json',
                    success: function(response) {
                        console.log(response);
                        var data = {
                            zone_id: response.response.zone_id,
                            lat: lati,
                            lng: long,
                            searched_name: input.value,
                        }

                        $.ajax({
                            type: "post",
                            url: "{{ url('user/session-store') }}",
                            data: data,
                            dataType: 'json',
                            success: function(response) {
                                console.log(response);



                            },
                            error: function(error) {
                                console.error(error);
                            }

                        });


                    },
                    error: function(error) {
                        // console.error(error);
                        alert(error.responseJSON.message);
                    }

                });
            }
        });
    }
</script>
{{-- <script async defer  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBH-1noGUoStq-5nLCbxHLhAPHN1kPrW2k&libraries=places&callback=initMap"></script> --}}
<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>

<script defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAiWxQfh0rigfLPFqru0WtGWvrICURlxqM&libraries=places&callback=initMap">
</script>

<script>
    function get_location() {
        if ("geolocation" in navigator) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var latitude = position.coords.latitude;
                var longitude = position.coords.longitude;
                alert('latitude and longtitide is ' + latitude + ", " + longitude);
            });
        } else {
            $("#locationData").html("Geolocation is not available.");
        }

    }


    $(document).ready(function() {
        $('#loading').hide();
    });
</script>
@yield('scripts')
</body>

</html>

@extends('layouts.vendor.app')
@section('title', translate('Messages'))
@section('content')

    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title">{{ translate('messages.Notification') }} {{ translate('messages.list') }}</h1>
        </div>
        <!-- End Page Header -->

        <div class="row g-3">
            <div class="col-lg-10 col-md-4">
                <!-- Card -->
                <div class="card">
               
                    <div class="card-header border-0">
                        <div class="input-group input---group">
                            <div class="input-group-prepend border-right-0">
                                <span class="input-group-text border-right-0" id="basic-addon1"><i class="tio-search"></i></span>
                            </div>
                            <input type="text" class="form-control border-left-0 pl-1" id="serach" placeholder="{{ translate('messages.search') }}" aria-label="Username"
                                aria-describedby="basic-addon1" autocomplete="off">
                        </div>
                    </div>
                    <!-- Body -->
                    <div class="card-body p-0 initial-19" id="conversation-list">
                        <div class="border-bottom"></div>

@foreach($notify as $noti)

    <div
        class="chat-user-info d-flex border-bottom p-3 align-items">
        <div class="chat-user-info-img d-none d-md-block">
        <div class="chat-user-info-img d-none d-md-block">
            <img class="avatar-img" src="{{ asset('storage/app/public/profile/2023-02-28-63fdea48cf47d.png')}}"  alt="Image Description">
            </div>
        </div>
        @if($noti['read_by_vendor'] == 0)
        <div class="chat-user-info-content" >
            <h5 class="mb-0 d-flex justify-content-between">
                <span class=" mr-3"> 
            {{$noti['data']['title']}} 
            <a href="{{route('vendor.notification.read_status',['order_id' => $noti['data']['order_id'],'id' => $noti['id']])}}" >{{$noti['data']['order_id'] }} </a>
            {{$noti['data']['description']}}
            </span>
            </h5>
        </div>
    
        @elseif($noti['read_by_vendor'] == 1)
        <div class="chat-user-info-content" style=" opacity: 0.5;" >
            <h5 class="mb-0 d-flex justify-content-between">
                <span class=" mr-3"> 
            {{$noti['data']['title']}} 
            <a href="{{route('vendor.notification.read_status',['order_id' => $noti['data']['order_id'],'id' => $noti['id']])}}" >{{$noti['data']['order_id'] }} </a>
                {{$noti['data']['description']}}
            </span>
            </h5>
        </div>
        @endif
    </div>
@endforeach


                    </div>
                    <!-- End Body -->
                </div>
                <!-- End Card -->
            </div>
           
        </div>
        <!-- End Row -->
    </div>

@endsection

@push('script_2')
<script src="{{ asset('public/assets/admin/js/spartan-multi-image-picker.js') }}"></script>
    <script>
        function viewConvs(url, id_to_active, conv_id, sender_id) {
            $('.customer-list').removeClass('conv-active');
            $('#' + id_to_active).addClass('conv-active');
            let new_url= "{{ route('vendor.message.list') }}" + '?conversation=' + conv_id+ '&user=' + sender_id;
            $.get({
                url: url,
                success: function(data) {
                    window.history.pushState('', 'New Page Title', new_url);
                    $('#view-conversation').html(data.view);
                    converationList();
                }
            });

        }

        var page = 1;
        $('#conversation-list').scroll(function() {
            if ($('#conversation-list').scrollTop() + $('#conversation-list').height() >= $('#conversation-list')
                .height()) {
                page++;
                loadMoreData(page);
            }
        });

        function loadMoreData(page) {
            $.ajax({
                    url: "{{ route('vendor.notification.list') }}" + '?page=' + page,
                    type: "get",
                    beforeSend: function() {

                    }
                })
                .done(function(data) {
                    if (data.html == " ") {
                        return;
                    }
                    $("#conversation-list").append(data.html);
                })
                .fail(function(jqXHR, ajaxOptions, thrownError) {
                    alert('server not responding...');
                });
        }

        function fetch_data(page, query) {
            $.ajax({
                url: "{{ route('vendor.notification.list') }}" + '?page=' + page + "&key=" + query,
                success: function(data) {
                    $('#conversation-list').empty();
                    $("#conversation-list").append(data.html);
                }
            })
        }

        $(document).on('keyup', '#serach', function() {
            var query = $('#serach').val();
            fetch_data(page, query);
        });
    </script>

<script>
    $('#read_by_vendor').on('click', function() {
        var formData = new FormData(this);
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.post({
            url : '{{route('vendor.notification.read_status')}}',
            data: formData,
           
            
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function() {
                $('#loading').show();
            },
            success: function(data) {
                $('#set-rows').html(data.view);
                $('#itemCount').html(data.total);
                $('.page-area').hide();
            },
            complete: function() {
                $('#loading').hide();
            },
        });
    });
</script>
@endpush

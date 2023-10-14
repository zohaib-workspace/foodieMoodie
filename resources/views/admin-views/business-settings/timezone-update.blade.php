@extends('layouts.admin.app')

@section('title','Timezone Update')

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title">Update Currency</h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <div class="card">
                    <div class="card-body">
                       <form action="{{route('admin.business-settings.timezone-update',[$tz['id']])}}" method="post" id="timezone_form"
                      enctype="multipart/form-data">
                    @csrf
                    @method('put')

                    <div class="form-group mb-2">
                        <label class="form-label d-block">Time Zone</label>
                        <input type="text" placeholder="{{ translate('messages.Ex :') }} Bangladesh" value="{{$tz['timezone']}}" class="form-control" name="timezone">
                    </div>

                    <div class="form-group mb-2">
                        <label class="form-label d-block">GMT Time</label>
                        <input type="text" placeholder="{{ translate('messages.Ex :') }} USD" value="{{$tz['gmt_time']}}" class="form-control" name="gmt_time">
                    </div>

                    {{-- <div class="form-group mb-2">
                        <label class="form-label d-block">Status</label>
                        <input type="text" placeholder="{{ translate('messages.Ex :') }} $" value="{{$tz['status']}}" class="form-control" name="status">
                    </div> --}}

                    

                    <button type="submit" class="btn btn-primary mb-2">Update</button>

                </form> 
                    </div>
                </div>
                
            </div>
        </div>
    </div>
@endsection

@push('script_2')
<script>
    
    $(document).ready(function() {
            $('#timezone_form').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.post({
                    url: '{{ route('admin.business-settings.timezone-update', [$tz['id']]) }}',
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
                            toastr.success(
                                '{{ translate('messages.timezone_updated_successfully') }}', {
                                    CloseButton: true,
                                    ProgressBar: true
                                });
                            setTimeout(function() {
                                location.href =
                                    '{{ route('admin.business-settings.timezone-add') }}';
                            }, 2000);
                        }
                    }
                });
            });

        });
</script>

@endpush

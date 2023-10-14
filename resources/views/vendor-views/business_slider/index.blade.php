@extends('layouts.vendor.app')
@section('title',translate('Add new Business Slider'))

@section('content')
<div class="content container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-sm mb-2 mb-sm-0">
                <h1 class="page-header-title">
                    <i class="tio-free-transform"></i>{{translate('messages.Business Slider')}} {{translate('messages.setup')}}
                </h1>
            </div>
        </div>
    </div>
    <div class="row gx-2 gx-lg-3">
        <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
            <form action="{{route('vendor.business_slider.store')}}" method="post" class="shadow--card"  enctype="multipart/form-data">
                @csrf
                <div class="row justify-content-between">
                    <div class="col-md-6 col-xl-7 business_slider-setup">
                        <div class="pl-xl-5 pl-xxl-0">

                        <div class="mb-1">
                        <div class="form-group">
                            <label class="input-label" for="exampleFormControlInput1">{{translate('messages.title')}} ({{translate('en')}})</label>
                            <input type="text" name="title" id="title" class="form-control h--45px" placeholder="{{ translate('messages.Ex :') }} {{translate('messages.new_food')}}" required>
                        </div>
                        <div class="form-group mb-0">
                            <label class="input-label" for="exampleFormControlInput1">{{translate('messages.short')}} {{translate('messages.description')}}</label>
                            <textarea type="text" name="description" id="description" class="form-control ckeditor"></textarea>
                        </div>
                    </div>
</br>
                        <div class="col-sm-6">
                            <div class="form-group m-0 h-100 d-flex flex-column">
                                <label class="d-block text-center mb-0">
                                    {{translate('messages.Slider')}} {{translate('messages.image')}}
                                    <small class="text-danger">* ( {{translate('messages.ratio')}} 300x100 )</small>
                                </label>
                                <center class="mt-auto mb-auto">
                                    <img class="initial-12" id="viewer"
                                         src="{{asset('public/assets/admin/img/900x400/img1.jpg')}}" alt="Business Slider image"/>
                                </center>
</br>
                                <div class="form-group">
                                    <div class="custom-file">
                                        <input type="file" name="image" id="customFileEg1" class="custom-file-input"
                                            accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" >
                                        <label class="custom-file-label" for="customFileEg1">{{translate('messages.choose')}} {{translate('messages.file')}}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    
                               
                            <div class="btn--container mt-3 justify-content-end">
                                <button id="reset_btn" type="button" class="btn btn--reset">{{translate('messages.reset')}}</button>
                                <button type="submit" class="btn btn--primary">{{translate('messages.submit')}}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
           
            @endsection

            @push('script_2')
            <script>
    function getRequest(route, id) {
        $.get({
            url: route,
            dataType: 'json',
            success: function (data) {
                $('#' + id).empty().append(data.options);
            },
        });
    }
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#viewer').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#customFileEg1").change(function () {
        readURL(this);
    });
</script>

        <script>
        $('#reset_btn').click(function(){
            $('#title').val(null);
            $('#description').val(null);
            $('#choice_item').val(null).trigger('change');
            $('#viewer').attr('src','{{asset('public/assets/admin/img/900x400/img1.jpg')}}');        })
    </script>
@endpush
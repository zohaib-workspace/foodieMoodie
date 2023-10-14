@extends('layouts.admin.app')
@section('title',translate('messages.Update Terms And Con'))
@push('css_or_js')
@endpush
@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title"><i class="tio-edit"></i> {{translate('messages.update')}} {{translate('messages.Terms And Conditions')}}</h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="card">
            <div class="card-body">
                <form action="{{route('admin.terms_and_conditions.update',[$terms_and_condition['id']])}}" method="post" id=campaign-form
                      enctype="multipart/form-data">
                      @csrf
                  
                    <div id="form">
                        <div class="form-group">
                            <label class="input-label" for="exampleFormControlInput1">{{translate('messages.title')}} ({{translate('en')}})</label>
                            <input type="text" name="title" class="form-control" placeholder="{{translate('messages.new_title')}}" value="{{$terms_and_condition['title']}}"  required>
                        </div>
                        <div class="form-group">
                            <label class="input-label" for="exampleFormControlInput1">{{translate('messages.short')}} {{translate('messages.description')}}</label>
                            <textarea type="text" name="description" id="summernote" class="form-control ckeditor min-height-154px" >{!! $terms_and_condition['description'] !!}</textarea>
                        </div>
                    </div>
                   
                        <div class="col-lg-6">
                            <div class="form-group m-0 h-100 d-flex flex-column">
                                <label class="d-block text-center mb-3">
                                    {{translate('messages.About')}} {{translate('messages.image')}} <small class="text-danger">* ( {{translate('messages.ratio')}} 900x300 )</small>
                                </label>
                                <center class="mt-auto mb-auto">
                                    <img class="initial-11" id="viewer"
                                         src="{{asset('storage/app/public/terms_and_con')}}/{{$terms_and_condition->image}}"
                                         onerror="this.src='{{asset('public/assets/admin/img/900x400/img1.png')}}'"
                                         alt="About Us image"/>
                                </center>
                                <div class="form-group">
                                    <div class="custom-file mt-3">
                                        <input type="file" name="image" id="customFileEg1" class="custom-file-input"
                                                accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                        <label class="custom-file-label" for="customFileEg1">{{translate('messages.choose')}} {{translate('messages.file')}}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="btn--container justify-content-end">
                        <button type="reset" id="reset_btn" class="btn btn--reset">{{translate('messages.reset')}}</button>
                        <button type="submit" class="btn btn--primary">{{translate('messages.update')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('script_2')
    <script>
        $("#date_from").on("change", function () {
            $('#date_to').attr('min',$(this).val());
        });

        $("#date_to").on("change", function () {
            $('#date_from').attr('max',$(this).val());
        });
       
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

        function show_item(type) {
            if (type === 'product') {
                $("#type-product").show();
                $("#type-category").hide();
            } else {
                $("#type-product").hide();
                $("#type-category").show();
            }
        }

        $('#campaign-form').on('submit', function (e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{route('admin.terms_and_conditions.update',[$terms_and_condition['id']])}}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    if (data.errors) {
                        for (var i = 0; i < data.errors.length; i++) {
                            toastr.error(data.errors[i].message, {
                                CloseButton: true,
                                ProgressBar: true
                            });
                        }
                    } else {
                        toastr.success('{{ translate('terms_and_condition updated successfully!') }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                        setTimeout(function () {
                            location.href = '{{route('admin.terms_and_conditions.list',)}}';
                        }, 2000);
                    }
                }
            });
        });
    </script>
    <script>
        $(".lang_link").click(function(e){
            e.preventDefault();
            $(".lang_link").removeClass('active');
            $(".lang_form").addClass('d-none');
            $(this).addClass('active');

            let form_id = this.id;
            let lang = form_id.substring(0, form_id.length - 5);
            console.log(lang);
            $("#"+lang+"-form").removeClass('d-none');
            if(lang == 'en')
            {
                $("#from_part_2").removeClass('d-none');
            }
            else
            {
                $("#from_part_2").addClass('d-none');
            }
        })
    </script>
        <script>
            $('#reset_btn').click(function(){
                $('#viewer').attr('src','{{asset('storage/app/public/about_us')}}/{{$terms_and_condition->image}}');
            })

        </script>
        <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
        <script src="{{ asset('public/assets/admin') }}/js/summernote.js"></script>

  <script>
      $('#summernote').summernote({
        placeholder: 'Hello Bootstrap 5',
        tabsize: 2,
        height: 500
      });
    </script>
@endpush

@extends('layouts.admin.app')

@section('title', translate('Add new food'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('public/assets/admin/css/tags-input.min.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title"><i class="tio-add-circle-outlined"></i> {{ translate('messages.add') }}
                        {{ translate('messages.new') }} {{ translate('messages.Terms And Conditions') }}</h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <form action="javascript:" method="post" id="food_form" enctype="multipart/form-data">
                    @csrf
                    @php($language = \App\Models\BusinessSetting::where('key', 'language')->first())
                    @php($language = $language->value ?? null)
                    @php($default_lang = 'bn')
                    <div class="row g-2">
                        @if($language)
                            @php($default_lang = json_decode($language)[0])
                            <div class="col-lg-12">
                                <ul class="nav nav-tabs mb-4">
                                    @foreach (json_decode($language) as $lang)
                                        <li class="nav-item">
                                            <a class="nav-link lang_link {{ $lang == $default_lang ? 'active' : '' }}" href="#"
                                                id="{{ $lang }}-link">{{ \App\CentralLogics\Helpers::get_language_name($lang) . '(' . strtoupper($lang) . ')' }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">
                                        <span class="card-header-icon">
                                            <i class="tio-fastfood"></i>
                                        </span>
                                        <span>{{ translate(' Terms And Conditions') }}</span>
                                    </h5>
                                </div>
                                    <div class="card-body">
                                        <div id="{{ $default_lang }}-form">
                                            <div class="form-group">
                                                <label class="input-label" for="exampleFormControlInput1">{{ translate('messages.name') }}
                                                    (EN)</label>
                                                <input type="text" name="title" class="form-control"
                                                    placeholder="{{ translate('messages.Terms And Conditions') }}" required>
                                            </div>
                                            <div class="form-group mb-0">
                                                <label class="input-label" for="exampleFormControlInput1">{{ translate('messages.short') }}
                                                    {{ translate('messages.description') }}</label>
                                                <textarea type="text" name="description"  id="summernote" class="form-control ckeditor min-height-154px"></textarea>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="card h-10">
                                <div class="card-header">
                                    <h5 class="card-title">
                                        <span class="card-header-icon"><i class="tio-image"></i></span>
                                        <span>{{ translate('About Us Image') }} <small class="text-danger">({{ translate('messages.Ratio 200x200') }})</small></span>
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="form-group mb-0 h-100 d-flex flex-column">
                                        <center id="image-viewer-section" class="my-auto">
                                            <img class="initial-52" id="viewer"
                                                src="{{ asset('/public/assets/admin/img/100x100/food-default-image.png') }}" alt="terms and conditions image" />
                                        </center>
                                        <div class="custom-file mt-3">
                                            <input type="file" name="image" id="customFileEg1" class="custom-file-input"
                                                accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                            <label class="custom-file-label" for="customFileEg1">{{ translate('messages.choose') }}
                                                {{ translate('messages.file') }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                      
                        <div class="col-lg-12">
                            <div class="btn--container justify-content-end">
                                <button type="reset" id="reset_btn" class="btn btn--reset">{{ translate('messages.reset') }}</button>
                                <button type="submit" class="btn btn--primary">{{ translate('messages.submit') }}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection


@push('script_2')
    <script>
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
            $('#image-viewer-section').show(1000);
        });
    </script>

    <script src="{{ asset('public/assets/admin') }}/js/tags-input.min.js"></script>

    <script>
        $('#food_form').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{ route('admin.terms_and_conditions.store') }}',
                data: $('#food_form').serialize(),
                data: formData,
               
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#loading').show();
                },
                success: function(data) {
                    $('#loading').hide();
                    if (data.errors) {
                        for (var i = 0; i < data.errors.length; i++) {
                            toastr.error(data.errors[i].message, {
                                CloseButton: true,
                                ProgressBar: true
                            });
                        }
                    } else {
                        toastr.success('{{ translate('messages.Terms And_Conditions_added_successfully') }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                        setTimeout(function() {
                            location.href =
                                '{{ \Request::server('HTTP_REFERER') ?? route('admin.terms_and_conditions.list') }}';
                        }, 2000);
                    }
                }
            });
        });
    </script>
            <script>
                $('#reset_btn').click(function(){
                  
                    $('#viewer').attr('src','{{asset('public/assets/admin/img/900x400/img1.jpg')}}');
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

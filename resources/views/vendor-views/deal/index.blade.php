@extends('layouts.vendor.app')

@section('title',translate('Create New Deal'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{asset('public/assets/admin/css/tags-input.min.css')}}" rel="stylesheet">
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title"><i class="tio-add-circle-outlined"></i> {{translate('create')}} {{translate('messages.new')}} {{translate('deal')}}</h1>
        </div>
        <!-- End Page Header -->
        <form action="javascript:" method="post" id="food_form"
                enctype="multipart/form-data">
            @csrf
            @php($language=\App\Models\BusinessSetting::where('key','language')->first())
            @php($language = $language->value ?? null)
            @php($default_lang = 'bn')
            <div class="row g-2">
            @if($language)
                @php($default_lang = json_decode($language)[0])
                <div class="col-lg-12">
                    <ul class="nav nav-tabs mb-4 border-0">
                        @foreach(json_decode($language) as $lang)
                            <li class="nav-item">
                                <a class="nav-link lang_link {{$lang == $default_lang? 'active':''}}" href="#" id="{{$lang}}-link">{{\App\CentralLogics\Helpers::get_language_name($lang).'('.strtoupper($lang).')'}}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <span class="card-header-icon">
                                <i class="tio-fastfood"></i>
                            </span>
                            <span>{{ translate('Deal Info') }}</span>
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($language)
                            @foreach(json_decode($language) as $lang)
                                <div class="{{$lang != $default_lang ? 'd-none':''}} lang_form" id="{{$lang}}-form">
                                    <div class="form-group mb-0">
                                        <label class="form-label" for="{{$lang}}_name">{{translate('messages.name')}} ({{strtoupper($lang)}})</label>
                                        <input type="text" {{$lang == $default_lang? 'required':''}} name="name[]" id="{{$lang}}_name" class="form-control h--45px" placeholder="{{translate('Ex : New Food')}}" oninvalid="document.getElementById('en-link').click()">
                                    </div>
                                    
                                   <div class="form-group mb-0 ">
                                    <label class="form-label" for="exampleFormControlInput1">{{translate('messages.price')}}</label>
                                    <input type="number" min="0" max="100000" step="0.01" value="1" name="price" class="form-control h--45px"
                                            placeholder="{{ translate('Ex : 100') }}" required>
                                </div>
                                    
                                    <input type="hidden" name="lang[]" value="{{$lang}}">
                                    <div class="form-group pt-4 mb-0">
                                        <label class="form-label" for="exampleFormControlInput1">{{translate('messages.short')}} {{translate('messages.description')}} ({{strtoupper($lang)}})</label>
                                        <textarea type="text" name="description[]" class="form-control ckeditor min-height-154px" placeholder="{{ translate('Ex : Description') }}"></textarea>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div id="{{$default_lang}}-form">
                                <div class="form-group mb-0">
                                    <label class="form-label" for="exampleFormControlInput1">{{translate('messages.name')}} (EN)</label>
                                    <input type="text" name="name[]" class="form-control h--45px" placeholder="{{translate('Ex : Sepcial Deal')}}" required>
                                </div>
                                <div class="form-group mb-0 ">
                                    <label class="form-label" for="exampleFormControlInput1">{{translate('messages.price')}}</label>
                                    <input type="number" min="0" max="100000" step="0.01" value="1" name="price" class="form-control h--45px"
                                            placeholder="{{ translate('Ex : 100') }}" required>
                                </div>
                                <input type="hidden" name="lang[]" value="en">
                                <div class="form-group pt-4 mb-0">
                                    <label class="form-label" for="exampleFormControlInput1">{{translate('messages.short')}} {{translate('messages.description')}}</label>
                                    <textarea type="text" name="description[]" class="form-contro ckeditor min-height-154px" placeholder="{{ translate('Ex : Description') }}"></textarea>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title">
                            <span class="card-header-icon"><i class="tio-image"></i></span>
                            <span>{{ translate('Deal Image') }} <small class="text-danger">({{ translate('messages.Ratio 200x200') }})</small></span>
                        </h5>
                    </div>
                    <div class="card-body d-flex flex-column">

                        <center id="image-viewer-section" class="my-auto">
                            <img class="initial-88" id="viewer" src="{{asset('/public/assets/admin/img/100x100/food-default-image.png')}}" alt="banner image"/>
                        </center>

                        <div class="custom-file mt-3">
                            <input type="file" name="image" id="customFileEg1" class="custom-file-input"
                                    accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                            <label class="custom-file-label" for="customFileEg1">{{translate('messages.choose')}} {{translate('messages.file')}}</label>
                        </div>
                    </div>
                </div>
            </div>
            
            
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <span class="card-header-icon">
                                <i class="tio-dashboard-outlined"></i>
                            </span>
                            <span> {{ translate('Food Details') }}</span>
                        </h5>
                    </div>
                    <div class="card-body">

                        <div class="row g-2">
                            <div class="col-md-4 col-sm-6">
                                <div class="form-group mb-0">
                                    <label class="form-label" for="exampleFormControlSelect1">{{translate('Items')}}<span
                                            class="input-label-secondary">*</span></label>
                                            
                                    <select name="item_id" id="item_id" class="form-control h--45px js-select2-custom"
                                            onchange="getRequest('{{url('/')}}/vendor-panel/deal/get_variants?parent_id='+this.value,'variant_type')">
                                        <option value="" selected disabled>---{{translate('messages.Select Item')}}---</option>
                                        @foreach($food as $item)
                                            <option value="{{$item['id']}}">{{$item['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <div class="form-group mb-0">
                                    <label class="form-label" for="exampleFormControlSelect1">{{translate('Variant')}}<span
                                            class="input-label-secondary"></span></label>
                                    <select name="variant_type" id="variant_type"
                                            class="form-control h--45px js-select2-custom"
                                            >
                                                <option value="" selected disabled>---{{translate('messages.Select Variant')}}---</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-4 col-sm-6">
                              
                    <button id="add_item" type='button' class="btn btn--primary" style="margin-top: 30px">{{translate('Add Item')}}</button>
                            </div>
                            
                        </div>
                        
                        <div class="col-md-12">
                                <div class="added_products"  id="added_products">
                                </div>
                            </div>
                        
                        
                    </div>
                </div>
            </div>

            <div class="col-lg-12">
                <div class="btn--container justify-content-end mt-2">
                    <button type="reset" id="reset_btn" class="btn btn--reset">{{translate('messages.reset')}}</button>
                    <button type="submit" class="btn btn--primary">{{translate('messages.submit')}}</button>
                </div>
            </div>
            </div>
        </form>
    </div>

@endsection

@push('script')

@endpush

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
            $('#image-viewer-section').show(1000)
        });
    </script>

    <script>
        $(document).on('ready', function () {
            // INITIALIZATION OF SELECT2
            // =======================================================
            $('.js-select2-custom').each(function () {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });
             $(document).on('click', '.delete-product', function() {
            $(this).closest('.row').remove();
        });
        });
    </script>


    <script src="{{asset('public/assets/admin')}}/js/tags-input.min.js"></script>

    <script>
    $('#add_item').on('click', function () {
    
        let selected_item_id;
        let selected_item;
        $.each($("#item_id option:selected"), function () {
                selected_item_id = $(this).val();
                selected_item = $(this).text();
                console.log('button is clicked ', selected_item_id, selected_item);
            });
            if(!selected_item_id)return;
            
            $.each($("#variant_type option:selected"), function () {
                add_product_in_deal(selected_item_id, selected_item, $(this).text());
            });
        
        $('#item_id').prop('selectedIndex', 0);
        // Remove data from the select element
        $('#variant_type').empty();
        
        });
        
        function addVariant(){
            console.log('in add variant');
        }
        
    
    function add_product_in_deal(id, name, variant) {
        console.log('added item is ', id);
            let n = name;
            $('#added_products').append('<div class="row pt-4"><div class="col-md-3 pt-3"><input type="text" '+
            'class="form-control h--45px" name="items['+id+
            '][name][]" value="' + name + ' (' + variant + ')' + 
            '" placeholder="{{translate('messages.choice_title')}}" readonly>' + 
            '<input type="hidden" class="form-control h--45px" name="items['+id+
            '][prods_ids][]" value="' + 0 + '">' + 
            
            '</div>'+
            '<div class="col-md-6 pt-3">'+
            '<input type="text" class="form-control h--45px" name="items[' + id + '][options][][]" placeholder="{{translate('Add Choices')}}" data-role="tagsinput"></div>' + 
            '<div class="col-md-3 pt-3">'+
               '<button type="button" class="btn btn-danger delete-product">Delete</button></div></div>'
            
            );
            $("input[data-role=tagsinput], select[multiple][data-role=tagsinput]").tagsinput();
        }
        
    
        $('#choice_attributes').on('change', function () {
            $('#customer_choice_options').html(null);
            $.each($("#choice_attributes option:selected"), function () {
                add_more_customer_choice_option($(this).val(), $(this).text());
            });
        });

        function add_more_customer_choice_option(i, name) {
            let n = name;
            $('#customer_choice_options').append('<div class="row"><div class="col-md-3"><input type="hidden" name="choice_no[]" value="' + i + '"><input type="text" class="form-control h--45px" name="choice[]" value="' + n + '" placeholder="{{translate('messages.choice_title')}}" readonly></div><div class="col-md-9"><input type="text" class="form-control h--45px" name="choice_options_' + i + '[]" placeholder="{{translate('messages.enter_choice_values')}}" data-role="tagsinput" onchange="combination_update()"></div></div>');
            $("input[data-role=tagsinput], select[multiple][data-role=tagsinput]").tagsinput();
        }

        function combination_update() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST",
                url: '{{route('vendor.food.variant-combination')}}',
                data: $('#food_form').serialize(),
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    $('#loading').hide();
                    $('#variant_combination').html(data.view);
                    if (data.length > 1) {
                        $('#quantity').hide();
                    } else {
                        $('#quantity').show();
                    }
                }
            });
        }
    </script>


    <script>
        $('#food_form').on('submit', function () {
            var formData = new FormData(this);
            
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{route('vendor.deal.store')}}',
                data: $('#food_form').serialize(),
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    $('#loading').hide();
                    console.log('Added data: ', data);
                    
                    if (data.errors) {
                        for (var i = 0; i < data.errors.length; i++) {
                            toastr.error(data.errors[i].message, {
                                CloseButton: true,
                                ProgressBar: true
                            });
                        }
                    } else {
                        toastr.success('{{translate('messages.product_added_successfully')}}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                        // return;
                        setTimeout(function () {
                            location.href = '{{route('vendor.deal.list')}}';
                        }, 2000);
                    }
                },
               error: function (xhr, status, error) {
        // Handle exception/error case
        $('#loading').hide();
                    console.log('Added data: ', status, error, xhr);
        toastr.error('An error occurred. Please try again later.', {
            CloseButton: true,
            ProgressBar: true
        });
        console.error(xhr, status, error);
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
            if(lang == '{{$default_lang}}')
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
                        $('#restaurant_id').val(null).trigger('change');
                        $('#item_id').val(null).trigger('change');
                        $('#sub-categories').val(null).trigger('change');
                        $('#veg').val(0).trigger('change');
                        $('#add_on').val(null).trigger('change');
                        $('#choice_attributes').val(null).trigger('change');
                        $('#customer_choice_options').val(null).trigger('change');
                        $('#variant_combination').empty().trigger('change');
                        $('#viewer').attr('src','{{asset('/public/assets/admin/img/100x100/food-default-image.png')}}');
                    })
                </script>
@endpush



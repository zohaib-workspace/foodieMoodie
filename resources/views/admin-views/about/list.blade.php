@extends('layouts.admin.app')
@section('title',translate('messages.Businesss Slider List'))
@push('css_or_js')
@endpush
@section('content')

<main>
<div class="main-body-div">
            <!-- Top Start -->
            <section class="top-start min-h-100px">
                <div class="container">
                    <div class="row">
                        <div class="col-12 mt-2 text-center">
                            <h1>{{translate('messages.About Us')}}</h1>
                           
                        </div>
                       <div style="padding-left:1200px">
                       <a class="btn btn-sm btn--primary btn-outline-primary action-btn"
                                                href="{{route('admin.about.edit',[$about['id']])}}" title="{{translate('messages.edit')}} {{translate('messages.about')}}"><i class="tio-edit"></i>
                                            </a>
                       </div>
                        <div class="col-12">
                   
<?php echo $about['description'] ?>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Top End -->
        </div>
</main>

  
@endsection

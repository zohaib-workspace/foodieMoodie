@extends('layouts.admin.app')
@section('title',translate('messages.Terms And Conditions'))
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
                            <h1>{{translate('messages.Terms And Conditions')}}</h1>
                           
                        </div>
                       <div style="padding-left:1200px">
                       <a class="btn btn-sm btn--primary btn-outline-primary action-btn"  
                                                href="{{route('admin.terms_and_conditions.edit',[$terms_and_condition['id']])}}" title="{{translate('messages.edit')}} {{translate('messages.privacy_policy')}}"><i class="tio-edit"></i>
                                            </a>
                       </div>
                        <div class="col-12">
                   
<!-- <span>{{$terms_and_condition['description']}}</span> -->
<?php echo $terms_and_condition['description'] ?>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Top End -->
        </div>
</main>

@endsection
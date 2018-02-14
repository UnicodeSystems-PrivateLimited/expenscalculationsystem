@extends('layouts.base')
@section ('title')
Password recovery
@stop
@section('content')
    <div class="content_section no-padding col-sm-12">
        <div class="header-title no-padding col-sm-12 text-center">
                <h3><span>Request received</span></h3>
        </div>
        <div class="from-lgn  formgroup text-center no-padding col-sm-12">
            <div class="form_wrapper signup">
                <div class="row row-wrapper">
                    <div class="success-msg text-center col-sm-12">
                            <p class="success-log text-center col-sm-12">We sent you the information to recover your password. Please check your inbox.</p>
                            <p class="text-center col-sm-12"><a href="{!! URL::route('login') !!}"><i class="fa fa-arrow-left"></i> Back to login</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
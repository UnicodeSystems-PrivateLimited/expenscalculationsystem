@extends('layouts.base')
@section('title')
User login
@stop
@section('content')

<div class="content_section no-padding col-sm-12">
            <div class="header-title no-padding col-sm-12 text-center">
                <h3><span>Congratulations, you successfully registered to {!! Config::get('acl_base.app_name') !!}</span></h3>
            </div>
    <div class="from-lgn  formgroup text-center no-padding col-sm-12">
        <div class="form_wrapper signup">
            <div class="row row-wrapper">  
                <div class="success-msg col-sm-12 text-center">
                    <p class="notice">You have been registered successfully.
                        After your account has been activated, you can login to VaTax Cloud using the {!! link_to('/','Following link') !!}</p>
                </div>
            </div>    
        </div>    
    </div>
</div>
@stop
@extends('laravel-authentication-acl::client.layouts.base-fullscreen')
@section ('title')
Registration request received
@stop
@section('content')

<div class="content_section no-padding col-sm-12">

            <div class="header-title no-padding col-sm-12 text-center">
                <h3><span>Request received</span></h3>
            </div>
    <div class="from-lgn formgroup text-center no-padding col-sm-12">
        <div class="form_wrapper signup">
            <div class="row row-wrapper">

                <div class="success-msg text-center col-sm-12">

                    <p class="lead notice">You account has been created. However, before you can use it you need to confirm your email address.<br/>
                        We sent you a confirmation email, please check your inbox.</p>
                </div>

            </div>
        </div>
     </div>    
    </div>  
</div>
     
@stop
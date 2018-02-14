@extends('layouts.base', ['type' => session()->get('type'), 'group' => session()->get('group'), 'loginStatus' => session()->get('loggedin')])
@section ('title')
    Password recovery
@stop
@section('content')
    <!-- <div class="header "> -->
        <!-- {{ HTML::image('resources/assets/images/logo.png') }} -->
    <!-- </div> -->

<div class="content_section no-padding col-sm-12">
        <div class="header-title no-padding col-sm-12 text-center">
                <h3><span>Password Changed {{ HTML::image('resources/assets/images/hand.png') }}</span></h3>
        </div>
    <div class="from-lgn  formgroup text-center no-padding col-sm-12">
        <div class="form_wrapper signup">
            <div class="row row-wrapper">
                    <div class="success-msg text-center col-sm-12 ">
                        <p class="success-log">Your Password has been updated Successfully.</p>
                        <p><a href="{!! URL::route('account.logout') !!}"> Login Again</a></p>
                    </div>
            </div>
        </div>
    </div>
</div>

@stop



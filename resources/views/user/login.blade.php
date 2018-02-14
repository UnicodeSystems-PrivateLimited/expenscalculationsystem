@extends('layouts.base')
@section('title')
    User login
@stop
@section('content')

<div class="content_section no-padding col-sm-12">
      
        <div class="header-title no-padding col-sm-12 text-center">
        <h3><span>Login to {!! Config::get('acl_base.app_name') !!}</span></h3>
        </div>
        {!! Form::open(array('url' => URL::route("account.login.post"), 'method' => 'post','class' => 'layout-column layout-align-center-center') ) !!}
            <div class="from-lgn formgroup  text-center no-padding col-sm-12">
                <div class="form_wrapper login">
                    <?php $message = Session::get('message'); ?>
                    <div class="msgalert-section row">
                        @if( isset($message) )
                            <div class="alert alert-success">{!! $message !!}</div>
                        @endif
                        @if($errors && ! $errors->isEmpty() )
                        <div class="alert alert-danger">
                            @foreach($errors->all() as $error)
                            <p> {!! $error !!}</p>
                            @endforeach
                        </div>
                        @endif
                    </div>

                    <div class="row row-wrapper">
                        <div class="input-group emailid-login  col-sm-12">
                            <label class="col-sm-12">Email :</label>
                            <div class="input-value col-sm-12 no-padding">
                                {!! Form::email('email', '', ['id' => 'email', 'class' => 'form-control with-primary-addon', 'placeholder' => 'Email address', 'required', 'autocomplete' => 'off']) !!}        
                            </div>
                        </div>

                        <div class="input-group password-login col-sm-12">
                            <label class="col-sm-12 ">Password :</label>
                            <div class="input-value col-sm-12 no-padding">
                                {!! Form::password('password', ['id' => 'password', 'class' => 'form-control with-primary-addon', 'placeholder' => 'Password', 'required', 'autocomplete' => 'off']) !!}
                            </div>
                        </div>

                    
                    <div class="input-group submit  col-sm-12">
                        <div class="input-value value-submit">
                            <button type="submit" class="login">Log In <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></button>
                        </div>
                    </div> 
                </div>
                <div class="text-center create-and-login col-sm-12 "> 
                {!! link_to_route('account.create','Create Account') !!} {!! link_to_route('recovery-password','Forgot password?') !!}
                </div>
            </div>
        {!! Form::close() !!} 
       
</div>

@stop




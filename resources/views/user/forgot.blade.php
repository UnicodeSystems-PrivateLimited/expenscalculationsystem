@extends('layouts.base')
@section ('title')
    Password recovery
@stop
@section('content')
<div class="content_section no-padding col-sm-12">
    <div class="header-title no-padding row text-center">
        <h3><span>Password recovery</span></h3>
    </div>
        
    {!! Form::open(array('url' => URL::route("account.reminder"), 'method' => 'post','class' => 'layout-column layout-align-center-center row') ) !!}
        <div class="from-lgn formgroup  text-center no-padding col-sm-12">
            <div class="form_wrapper login">  
                <div class="msgalert-section row">
                    @if($errors && ! $errors->isEmpty() )
                    <div class="alert alert-danger">
                    @foreach($errors->all() as $error)
                    <p>{{$error}}</p>
                    @endforeach
                    </div>
                    @endif
                </div>

                <div class="row row-wrapper">
                    <div class="input-group row">  
                        <p class="notification">Enter your email address and password reset link will be emailed to you</p>
                        <div class="col-sm-12 col-xs-12 forgot-email">
                           <label class="col-sm-12">Email :</label>
                           <div class="input-value col-sm-12 no-padding">
                            {!! Form::email('email', '', ['id' => 'email', 'class' => 'form-control with-primary-addon', 'placeholder' => 'Your account email', 'required', 'autocomplete' => 'off']) !!}               
                           </div>
                        </div>
                    </div>   

                    <div class="input-group submit col-sm-12">
                        <div class="input-value value-submit text-center">
                            <button type="submit" name="submit">Recover <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></button>
                        </div>
                    </div>
                </div><!--row-wrapper-->
            </div> <!--form_wrapper-->
        </div><!--formgroup-->
    {!! Form::close() !!}    
        <div class="text-center create-and-login back-to-home col-sm-12"> 
            <a href="{!! URL::route('login') !!}"><i class="fa fa-arrow-circle-o-left"></i> Back to login</a>
        </div>
</div>
@stop



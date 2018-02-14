@extends('layouts.base')
@section ('title')
    Password recovery
@stop
@section('content')

<div class="container">
    <div class="content_section no-padding col-sm-12">
    <!-- <div class="header "> -->
        <!--<img src="../../assets/img/logo.png"/>-->
        <!-- {{ HTML::image('resources/assets/images/logo.png') }}
    </div> -->
 
        
          {!! Form::open(array('url' => URL::route("user.forget-password"), 'method' => 'post','class' => 'layout-column layout-align-center-center') ) !!}
            <div class="header-title no-padding col-sm-12 d-flex justify-content-center">
                <h3>Enter New Password
                    <!--<img src="../../assets/img/hand.png"/> -->
                {{ HTML::image('resources/assets/images/hand.png') }}
                </h3>
            </div>

            @if($errors && ! $errors->isEmpty() )
            @foreach($errors->all() as $error)
            <div class="alert alert-danger">{{$error}}</div>
            @endforeach
            @endif

        <div class="from-lgn form_wrapper text-center no-padding col-sm-12">

            <div class="input-labelvalue reset-pass d-flex flex-row align-items-center">

                <label  class="col-4 d-flex justify-content-end">Password :</label>

                <div class="input-value col-8 d-flex justify-content-start no-padding">
                    <input id="password" class="form-control with-primary-addon" placeholder="New password" required="required" autocomplete="off" name="password" type="password" value="">
                    <input type="hidden" name="_token" value="<?php echo $_REQUEST['_token']?>"/>
                    <input type="hidden" name="_id" value="<?php echo $_REQUEST['_id']?>"/>
                </div>

            </div>

            <div class="input-labelvalue submit d-flex justify-content-center col-sm-12">

                <div class="input-value value-submit">
                    <input  value="Save" type="submit" />
                </div>

            </div>

        </div>
           {!! Form::close() !!}
       
         <!--<a href="{!! URL::route('login') !!}"><i class="fa fa-arrow-left"></i> Back to login</a>-->
 
    </div>
</div>
@stop



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
 
        
            <div class="header-title no-padding col-sm-12 text-center">
                <h3><span>Enter New Password</span></h3>
            </div>
           
{!! Form::open(array('url' => URL::route("account.checkForgetPassword"), 'method' => 'post','class' => 'layout-column layout-align-center-center') ) !!}

          

<div class="from-lgn formgroup  text-center no-padding col-sm-12">
    <div class="form_wrapper login">
    @if($errors && ! $errors->isEmpty() )
        <div class="msgalert-section row">
            @foreach($errors->all() as $error)
            <div class="alert alert-danger">{{$error}}</div>
            @endforeach
        </div>    
    @endif
       <div class="row row-wrapper">

       <input type="hidden" name="_token" value=" <?php echo $_REQUEST['token']?>"/>   
       
            <div class="reset-pass input-group col-sm-12">

                <label  class="col-sm-12">Password</label>

                <div class="input-value col-sm-12 col-xs-12 no-padding">
                    <input id="password" class="form-control with-primary-addon" placeholder="New password" required="required" autocomplete="off" name="password" type="password" value="">
                </div>
                

            </div>
            <div class="reset-pass input-group col-sm-12">
                <label  class="col-sm-12">Confirm Password</label>
                <div class="input-value col-sm-12 col-xs-12 no-padding">
                    <input id="password_confirmation" class="form-control with-primary-addon" placeholder="Confirm New password" required="required" autocomplete="off" name="password_confirmation" type="password" value="">
                </div>
            </div>
            <div class="input-group col-sm-12 submit ">

                <div class="input-value value-submit">
                    <!-- <input  value="Save" type="submit" /> -->
                    <button type="submit" class="login">Save <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></button>

                </div>

            </div>

        </div><!--row-wrapper-->
    </div><!--form_wrapper-->
</div><!--formgroup-->

           {!! Form::close() !!}
       
         <!--<a href="{!! URL::route('login') !!}"><i class="fa fa-arrow-left"></i> Back to login</a>-->
 
    </div>
</div>
@stop


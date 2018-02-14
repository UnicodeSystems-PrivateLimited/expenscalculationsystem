@extends('layouts.base', ['type' => session()->get('type'), 'group' => session()->get('group'), 'loginStatus' => session()->get('loggedin')])
@section ('title')
    Password recovery
@stop
@section('content')

<div class="content_section no-padding col-sm-12">
    <div class="header-title no-padding row text-center">
        <h3><span>Enter New Password</span></h3>
    </div>
    {!! Form::open(array('url' => URL::route("user.checkpassword"), 'method' => 'post','class' => 'layout-column layout-align-center-center') ) !!}
        <div class="from-lgn formgroup  text-center no-padding col-sm-12">
            <div class="form_wrapper login">
                <div class="msgalert-section row">
                    @if($errors && ! $errors->isEmpty() )
                    @foreach($errors->all() as $error)
                    <div class="alert alert-danger">{{$error}}</div>
                    @endforeach
                    @endif
                </div>       
                <div class="row row-wrapper">
                    <div class="input-group col-sm-12 old-pass">
                        <label class="col-sm-12">Old Password :</label>
                        <div class="input-value col-sm-12 no-padding">
                            {!! Form::password('old_password', ['class' => 'form-control', 'id' =>'password1', 'placeholder' => 'Old password', 'required']) !!}    
                        </div>
                    </div>

                    <div class="input-group col-sm-12 new-pass">
                        <label class="col-sm-12">New Password :</label>
                        <div class="input-value col-sm-12">
                            {!! Form::password('password', ['id' => 'password', 'class' => 'form-control', 'placeholder' => 'New Password', 'required', 'autocomplete' => 'off']) !!}      
                        </div>
                    </div>
                    
                    <div class="input-group col-sm-12 confm-pass">
                        <label class="col-sm-12">Confirm Password :</label>
                        <div class="input-value col-sm-12">
                            {!! Form::password('password_confirmation', ['class' => 'form-control', 'id' =>'password2', 'placeholder' => 'Confirm password', 'required']) !!}    
                        </div>
                    </div>

                    <div class="input-group submit col-sm-12">
                        <div class="input-value value-submit">
                            <button type="submit" class="login">Update <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></button>
                        </div>
                    </div>
                </div>
            </div><!--form_wrapper-->
        </div> <!--formgroup-->  
    {!! Form::close() !!}
</div>
@stop



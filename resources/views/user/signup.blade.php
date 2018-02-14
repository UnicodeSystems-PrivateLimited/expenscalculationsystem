@extends('layouts.base')
@section('title')
User Sign Up
@stop
@section('content')

    <?php
    $url = resource_path("json/countries.json");
    $content = file_get_contents($url);
    $countries = json_decode($content, true);
    ?>

<div class="content_section no-padding col-sm-12">
    <!-- <div class="header "> -->
        <!-- {{ HTML::image('resources/assets/images/logo.png') }} -->
    <!-- </div> -->

    <div class="header-title no-padding row text-center">
        <h3><span>Create Account {!! Config::get('acl_base.app_name') !!}</span></h3>
    </div>
   
            {!! Form::open(["route" => 'account.signup.process', "method" => "POST", "id" => "user_signup"]) !!}
            {{-- Field hidden to fix chrome and safari autocomplete bug --}}
            {!! Form::password('__to_hide_password_autocomplete', ['class' => 'hidden']) !!}
<div class="from-lgn  formgroup text-center no-padding col-sm-12">
  <div class="form_wrapper signup">

  <div class="msgalert-section row">
        <?php $message = Session::get('message'); ?>
        @if( isset($message) )
        <div class="alert alert-success">
            <p>{!! $message !!}</p>
        </div>
        @endif
       
        @if($errors && ! $errors->isEmpty() )
        <div class="alert alert-danger">
            <ul>
            @foreach($errors->all() as $error)
            <li>{!! $error !!}</li>         
            @endforeach
            </ul>
        </div>
        @endif
       
    </div>

    <div class="row row-wrapper">    
    <div class="input-group row">
        <div class="fname-signup col-xs-12 col-sm-6">

            <label class="col-sm-12">First Name :</label>

            <div class="input-value col-sm-12 no-padding">
            {!! Form::text('first_name', '', ['id' => 'first_name', 'class' => 'form-control', 'placeholder' => 'First Name', 'required', 'autocomplete' => 'off']) !!}
            <!--<span class="text-danger">{!! $errors->first('first_name') !!}</span>-->    
            </div>
        </div>

        <div class="lname-signup col-xs-12 col-sm-6">

            <label class="col-sm-12">Last Name :</label>

            <div class="input-value col-sm-12 no-padding">
                {!! Form::text('last_name', '', ['id' => 'last_name', 'class' => 'form-control', 'placeholder' => 'Last Name', 'required', 'autocomplete' => 'off']) !!}           
                <!--<span class="text-danger">{!! $errors->first('last_name') !!}</span>-->            
            </div>
        </div>
    </div>

    <div class="input-group row">

        <div class="col-sm-6 col-xs-12 email-signup ">

            <label class="col-sm-12">Email :</label>

            <div class="input-value col-sm-12 no-padding">
            {!! Form::email('email', '', ['id' => 'email', 'class' => 'form-control', 'placeholder' => 'Email address', 'required', 'autocomplete' => 'off']) !!}          
            <!--<span class="text-danger">{!! $errors->first('email') !!}</span>-->          
            </div>

        </div>
        


        <div class="col-sm-6 col-xs-12 phone-signup">

            <label class="col-sm-12">Phone :</label>

            <div class="input-value col-sm-12 no-padding">
                {!! Form::text('phone', '', ['id' => 'phone', 'class' => 'form-control', 'placeholder' => 'Phone', 'required', 'autocomplete' => 'off']) !!}           
                <!--<span class="text-danger">{!! $errors->first('phone') !!}</span>-->            
            </div>

        </div>
    </div> 
    

    <div class="input-group row cmny-cunrty-add">   
    <div class="col-sm-6 col-xs-12">
        <div class="col-sm-12 col-xs-12 cmnyname-signup padding-reomve">

        
            <label class="col-sm-12">Company Name :</label>

            <div class="input-value col-sm-12 no-padding">
                {!! Form::text('company_name', '', ['id' => 'company_name', 'class' => 'form-control', 'placeholder' => 'Company Name', 'required', 'autocomplete' => 'off']) !!}           
                <!--<span class="text-danger">{!! $errors->first('company_name') !!}</span>-->            
            </div>

        </div>    
           
        <div class="col-sm-12 col-xs-12 cmnyname-signup country-signup  padding-reomve">

            <label class="col-sm-12">Country :</label>

            <div class="input-value col-sm-12 no-padding">
                <select name="Country">
                    <option value="">Country</option>
                    <?php
                    foreach($countries as $key => $value) {
                    ?>
                    <option value="<?= $value ?>"
                            title="<?= htmlspecialchars($value) ?>"><?= htmlspecialchars($value) ?></option>
                    <?php
                    }
                    ?>
                </select>

            {{--{!! Form::text('country', '', ['id' => 'country', 'class' => 'form-control', 'placeholder' => 'Country', 'autocomplete' => 'off']) !!}--}}
                <!--<span class="text-danger">{!! $errors->first('company_name') !!}</span>-->            
            </div>

        </div>    
       
       </div>

        
        <div class=" row adress-row col-sm-6 col-xs-12">   

        <div class="col-sm-12 col-xs-12 adress-signup">

            <label class="col-sm-12">Address:</label>

            <div class="input-value col-sm-12 no-padding">
                <!-- {!! Form::text('address', '', ['id' => 'address', 'class' => 'form-control', 'placeholder' => 'Address', 'autocomplete' => 'off']) !!}            -->
                <!--<span class="text-danger">{!! $errors->first('company_name') !!}</span>-->     
                <textarea name="address" id="address"  rows="2" placeholder="Address.."></textarea>
            </div>

        </div>

        
    </div> 


     </div>

    
    
                
                    <!-- <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                            {!! Form::text('last_name', '', ['id' => 'last_name', 'class' => 'form-control', 'placeholder' => 'Last Name', 'required', 'autocomplete' => 'off']) !!}
                        </div>
                        <span class="text-danger">{!! $errors->first('last_name') !!}</span>
                    </div> -->
            
      

            <!-- <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                    {!! Form::email('email', '', ['id' => 'email', 'class' => 'form-control', 'placeholder' => 'Email address', 'required', 'autocomplete' => 'off']) !!}
                </div>
                <span class="text-danger">{!! $errors->first('email') !!}</span>
            </div> -->
    <div class="input-group row">        
        

        <div class="col-sm-6 col-xs-12 pass-signup">
            <label class="col-sm-12">Password :</label>
            <div class="input-value col-sm-12 no-padding">
            {!! Form::password('password', ['id' => 'password1', 'class' => 'form-control', 'placeholder' => 'Password', 'required', 'autocomplete' => 'off']) !!}      
            <!--<span class="text-danger">{!! $errors->first('password') !!}</span>-->          
            </div>
        </div>
   
        <div class="col-sm-6 col-xs-12 pass-signup">

            <label class="col-sm-12">Confirm Password :</label>

            <div class="input-value col-sm-12 no-padding">
            {!! Form::password('password_confirmation', ['class' => 'form-control', 'id' =>'password2', 'placeholder' => 'Confirm password', 'required']) !!}    
            <!--<span class="text-danger">{!! $errors->first('password') !!}</span>-->          
            </div>

        </div>
    </div>
        <div class="input-group col-sm-12 row cmny-cunrty-add">
            <div class="col-sm-12 col-xs-12">
                <div class="col-sm-12 col-xs-12 cmnyname-signup country-signup  padding-reomve">
                <label class="col-sm-12">Company Owner :</label>

                <div class="input-value col-sm-12 no-padding">
                    <select name="is_owner">
                        <option value="1" title="Yes">Yes</option>
                        <option value="0" title="No">No</option>
                    </select>
                </div>
            </div>   
        </div> 

        </div>
        <div class="input-group col-sm-12 pass-signup ">

             <div id="pass-info"></div>

        </div>
        <div class="input-group col-sm-12 submit">

            <div class="input-value value-submit">
                <!-- <input  type="submit" value="Register" /> -->
                <button type="submit" class="login">Register <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></button>

            </div>

        </div>
            <!-- <div class="row"> -->

                <!-- <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                            {!! Form::password('password', ['id' => 'password1', 'class' => 'form-control', 'placeholder' => 'Password', 'required', 'autocomplete' => 'off']) !!}
                        </div>
                        <span class="text-danger">{!! $errors->first('password') !!}</span>
                    </div>
                </div> -->

                <!-- <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                            {!! Form::password('password_confirmation', ['class' => 'form-control', 'id' =>'password2', 'placeholder' => 'Confirm password', 'required']) !!}
                        </div>
                    </div>
                </div> -->

                <!-- <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <div id="pass-info"></div>
                    </div>
                </div> -->

            <!-- </div> -->

            <!-- <input type="submit" value="Register" class="btn btn-info btn-block"> -->
        </div> 
     </div>
    </div><!--formgroup-->
 </form>

            <div class="text-center create-and-login  col-sm-12"> 
                    {!! link_to_route('login','Already have an account? Login here') !!}
            </div>
       
   
</div><!--content_section-->


@stop
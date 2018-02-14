@extends('layouts.base', ['type' => session()->get('type'), 'group' => session()->get('group'), 'loginStatus' => session()->get('loggedin')])
@section('title')
    Concur Login
@stop
@section('content')

  <div class="content_section no-padding col-sm-12">
    <div class="header-title no-padding col-sm-12 text-center">
        <h3><span>Concur</span></h3>
    </div>
    
<div class="form-and-info-center">
    <div class="from-lgn  formgroup  text-center no-padding col-sm-12">

                    <div class="msgalert-section row">
                        @if (session('error'))
                        <div class="alert alert-danger">
                            <p>{{ session('error') }}</p>
                            </div>   
                        @endif
                        @if (session('message'))
                        <div class="alert alert-success">
                            <p id="message">{{ session('message') }}</p>
                        </div>  
                        @endif
                    </div><!--msgalert-section-->

   <div class="row row-wrapper form-with-step">

      <div class="concur-login form_wrapper col-sm-6 col-xs-12">


        <form action="{{route('concur-login-action')}}" method="POST">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <div class="input-group row">
            <div class="username col-xs-12 col-sm-12">

                <label class="col-sm-12">User Name :</label>

                <div class="input-value col-sm-12 no-padding">
                    @if($userEmail)
                    <input id="username" required type="text" name="username" class="readonly" value="{!! $userEmail !!}" readonly>
                    @else
                    <input id="username" required type="text" name="username" value="{{ old('username') }}">
                    @endif
                </div>

            </div>
        </div>
        <div class="input-group row">
            <div class="pass col-sm-12">

                <label class="col-sm-12">Password :</label>

                <div class="input-value col-sm-12 no-padding">
                    <input id="password" required type="password" name="password" value="{{ old('password') }}">
                </div>

            </div>
        </div>

        <div class="input-group row">
            <div class="client col-sm-12">

                <label class="col-sm-12">Client Id :</label>

                <div class="input-value col-sm-12 no-padding">
                    <input id="client_id" required type="text" name="client_id" value="{{ old('client_id') }}">
                </div>

            </div>
        </div>    

        <div class="input-group row">
            <div class="secret col-sm-12">

                <label class="col-sm-12">Client Secret :</label>

                <div class="input-value col-sm-12 no-padding">
                    <input id="client_secret" required type="text" name="client_secret" value="{{ old('client_secret') }}">
                </div>

            </div>
        </div>
        <div class="input-group row">
            <div class="date-time col-sm-5 from">
                <label class="col-sm-12">From:</label> 
                 <div class="input-value col-sm-12 no-rpadding">
                    <input id="fromdate" type="date" name="fromdate" required value="{{ old('fromdate') }}">
                 </div>
            </div>
            <div class="date-time col-sm-5 to">
                <label class="col-sm-12">To:</label>
                <div class="input-value col-sm-12 no-lpadding">
                    <input id="todate" type="date" name="todate" required value="{{ old('todate') }}">
                </div>
            </div>
        </div>    
            <div class="input-group col-sm-12 submit">

                <div class="input-value value-submit text-left">
                    <!-- <input type="submit" name="submit" value=" Submit "/> -->
                    <button type="submit" name="submit">Submit <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></button>
                </div>

            </div>

            <!-- @if (session('error'))
                <p id="error">{{ session('error') }}</p>
            @endif
            @if (session('message'))
                <p id="message">{{ session('message') }}</p>
            @endif -->
        </form>
        
        </div>

        <div class="info_area col-sm-6  col-xs-12 text-left">
            <h3 class="text-left">Steps For Generating Client ID & Secret</h3>

            <div class="instruction">
                <p><strong>Step 1 :</strong> <span>Go to <a href="https://developer.concur.com/apps/#/" target="_blank" a>https://developer.concur.com/apps/#/</a>
                    and login same Concur Credentials.</span> </p>
                <p><strong>Step 2 :</strong> <span>Now click on "create an app" and fill all the fields.</p>
                <p><strong>Step 3 :</strong> <span>Click on UPDATE after filling all the fields to generate Client ID and Client
                    Secret.</span></p>
                <p><strong>Step 4 :</strong> <span>Copy and paste the id and secret from there to the above form.</span></p>
            </div>

            <div class="concur-import-log">
            <div class="header-title no-padding col-sm-12 text-center">
                    <h3><span>Concur Import Log</span></h3>
                </div>
            <form action="{{route('concur-date-log')}}" method="GET">
            <div class="input-group col-sm-12 submit">
          <div class="input-value value-submit text-center">
                    <!-- <input type="submit" name="submit" value=" Submit "/> -->
                    <button type="log" name="log">Date Log <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></button>
                </div>
                </div>
              </form>

            </div>
        </div><!--info_area-->
     </div>
    </div><!-- formgroup-->
</div>  
</div><!--content_section-->

@stop
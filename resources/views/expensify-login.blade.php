@extends('layouts.base', ['type' => session()->get('type'), 'group' => session()->get('group'), 'loginStatus' => session()->get('loggedin')])
@section('title')
    Expensify login
@stop
@section('content')
<div class="content_section no-padding col-sm-12">
        <div class="header-title no-padding col-sm-12 text-center">
            <h3><span>Expensify Login</span></h3>
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
            <form action="{{route('expensify-login-action')}}" method="POST">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div class="input-group row">
                <div class="partner_email  col-sm-12">

                    <label class="col-sm-12" for="partner_email">Partner Email :</label>
            
                    <div class="input-value col-sm-12 no-padding">
                        @if($userEmail)
                            <input type="email" name="partner_email" class="readonly" required value="{!! $userEmail !!}" readonly>
                        @else
                            <input type="email" name="partner_email" required value="{{ old('partner_email') }}" >
                        @endif
                    </div>

                </div>
            </div>

            <div class="input-group row">
                <div class="partner-user_id  col-sm-12">

                    <label class="col-sm-12" for="partner_user_id">Partner User ID :</label>

                    <div class="input-value col-sm-12 no-padding">
                        @if($userEmail)
                            <input type="text" name="partner_user_id" class="readonly" required value="{!! $PartnerId !!}" readonly>
                        @else
                            <input type="text" name="partner_user_id" required value="{{ old('partner_user_id') }}">
                        @endif
                    </div>

                </div>
            </div>

            <div class="input-group row">
                <div class="user_secret col-sm-12">

                    <label class="col-sm-12" for="partner_user_secret">Partner User Secret :</label>

                    <div class="input-value col-sm-12 no-padding">
                        <input type="text" name="partner_user_secret" required
                            value="{{ old('partner_user_secret') }}">
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

                <div class="input-group submit col-sm-12">

                    <div class="input-value value-submit text-left">
                        <!-- <input type="submit" value="Submit"> -->
                        <button type="submit" name="submit">Submit <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></button>
                    </div>

                </div>

               
            </form>

          </div>

        <div class="info_area col-sm-6  col-xs-12 text-left">

            <h3>Steps For Generating Partner User ID & Partner User Secret</h3>

            <div class="instruction">
                <p><strong>Step 1 :</strong> <span>Go to <a href="https://www.expensify.com/signin" target="_blank" a>https://www.expensify.com/signin</a>
                    and login same Expensify Credentials. </span></p>
                <p><strong>Step 2 :</strong> <span>Go to <a href="https://www.expensify.com/tools/integrations/" target="_blank" a>https://www.expensify.com/tools/integrations/</a>.</p>
                <p><strong>Step 3 :</strong> <span>Click on <b>CLICK HERE</b> to generate Partner User ID and Partner
                    User Secret.</span></p>
                <p><strong>Step 4 :</strong> <span>Copy and paste the id and secret from there to the above form.</span></p>
            </div>
            
             <div class="concur-import-log">
            <div class="header-title no-padding col-sm-12 text-center">
                    <h3><span>Expensify Import Log</span></h3>
                </div>
            <form action="{{route('expensify-date-log')}}" method="GET">
            <div class="input-group col-sm-12 submit">
          <div class="input-value value-submit text-center">
                    <!-- <input type="submit" name="submit" value=" Submit "/> -->
                    <button type="log" name="log">Date Log <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></button>
                </div>
                </div>
              </form>

            </div>

        </div>


      </div>              
    </div><!--formgroup-->
  </div>
</div><!--content_section-->

@stop
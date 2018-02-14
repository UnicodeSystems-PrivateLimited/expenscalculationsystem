@extends('layouts.base', ['type' => session()->get('type'), 'group' => session()->get('group'), 'loginStatus' => session()->get('loggedin')])
@section('title')
    Currency
@stop
@section('content')
<div class="content_section no-padding col-sm-12">
 <a href="{{ URL::previous() }}" class="go_back" title="Go back"><i class="fa fa-arrow-circle-left" aria-hidden="true"></i></a>    
        <div class="header-title no-padding col-sm-12 text-center">
            <h3><span>Add Currency</span></h3>
        </div>
       
    <div class="from-lgn  formgroup form-with-step text-center no-padding col-sm-12">
        <div class="concur-login form_wrapper">
        
            <div class="msgalert-section row">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
          <div class="row row-wrapper">
            <form action="{{route('currency-add')}}" method="POST">
                @if (isset($message))
                    <p id="message">{{ $message }}</p>
                @endif
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div class="input-group row">
                <div class="user_id col-xs-12 col-sm-12">
                    <label class="col-sm-12" for="currency_code">Currency Code :</label>
                    <div class="input-value col-sm-12 no-padding">
                        <input type="text" name="currency_code" required value="{{ old('currency_code') }}" >
                    </div>
                </div>
            </div>


            <div class="input-group row">
                <div class="excahange_rate col-xs-12 col-sm-12">
                    <label class="col-sm-12" for="exchange_rate">Exchange Rate :</label>
                    <div class="input-value col-sm-12 no-padding">
                        <input type="text" name="exchange_rate" required value="{{ old('exchange_rate') }}">
                    </div>
                </div>
            </div>

            <div class="input-group row input-group-radio">
                <div class="col-xs-12 col-sm-12 status">
                    <label class="col-sm-12" for="status">Status :</label>
                    <div class="input-value col-sm-12">
                        <label class="col-sm-6"><input type="radio" name="status" value=1 checked> Active</label>
                        <label class="col-sm-6"><input type="radio" name="status" value=0> Inactive</label>
                    </div>
                </div>
            </div>

                <div class="input-group submit col-sm-12">
                    <div class="input-value value-submit">
                        <button type="submit" name="submit">Submit <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></button>
                    </div>
                </div>
        </form>
    </div><!--row-wrapper-->

     </div><!--form_wrapper-->
   </div> <!--formgroup-->     

</div>
 
@stop
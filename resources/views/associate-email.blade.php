@extends('layouts.base', ['type' => session()->get('type'), 'group' => session()->get('group'), 'loginStatus' => session()->get('loggedin')])
@section('title')
    Associate Email
@stop
@section('content')
    <div class="content_section no-padding col-sm-12">
        <div class="header-title no-padding col-sm-12 text-center">
            <h3><span>Associate Email</span></h3>
        </div>
         <a href="{{ URL::previous() }}" class="go_back" title="Go back"><i class="fa fa-arrow-circle-left" aria-hidden="true"></i></a>
        <div class="col-sm-12">
           
            <form action="{{route('user.associate.email.action')}}" method="POST" class="row">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="user_id" value="{!! $user_id !!}">

            <div class="from-lgn formgroup  text-center no-padding col-sm-12">
                <div class="form_wrapper login">
                    
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

                 <div class="row row-wrapper">
                    <label class="col-sm-12" for="partner_email">Associated Email :</label>
                    
                    <div class="input-group col-sm-12 no-padding select-group">
                        <select name="associated_email" required>
                            <option value="">--Select email--</option>
                            @foreach($emails as $email)
                            <option value="{!! $email->id !!}">{!! $email->username !!}</option>
                            @endforeach
                        </select>
                    </div>
                

                    <div class="input-group submit col-sm-12">

                        <div class="input-value value-submit text-center">
                            <button type="submit" name="submit">Submit <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></button>
                        </div>

                    </div>

                </div>

                </div><!--form_wrapper-->
            </div><!--formgroup-->

              
            </form>
        
        </div>
   </div>
@stop
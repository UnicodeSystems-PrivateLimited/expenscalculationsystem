@extends('layouts.base', ['type' => session()->get('type'), 'group' => session()->get('group'), 'loginStatus' => session()->get('loggedin')])
@section('title')
    Contact Us
@stop
@section('content')
 <div class="content_section no-padding col-sm-12">
        <div class="header-title no-padding col-sm-12 text-center">
        <h3><span>Contact Us</span></h3>
        </div>
        
   <div class="row">
         <div class="from-lgn  formgroup text-center no-padding col-sm-12">
           <div class="form_wrapper signup">

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
                @if (isset($message))
                <div class="alert alert-success"><p id="message">{{ $message }}</p></div>
                @endif
            </div>
            <div class="row row-wrapper">  
             <form method="POST" enctype="multipart/form-data">    
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="input-group row">
                    <div class="col-sm-6 col-xs-12 fromemail">
                        <label class="col-sm-12 ">Name:</label>
                        <div class="input-value col-sm-12 no-padding">
                            <input id="name" required type="text" name="name"
                                value="{{ $name }}">
                        </div>
                    </div>

                    <div class="col-sm-6 col-xs-12 fromemail">
                        <label class="col-sm-12">Email:</label>
                        <div class="input-value col-sm-12 no-padding">
                            <input id="fromemail" required type="email" name="from_email"
                                value="{{ $email }}">
                        </div>
                    </div>

                </div>

                <div class="input-group row">

                    <div class="col-sm-6 col-xs-12 subject">
                        <label class="col-sm-12">Company Name :</label>
                        <div class="input-value col-sm-12">
                            <input id="subject" required type="text" name="company_name"
                                value="{{ $company_name }}">
                        </div>
                    </div>

                    <div class="col-sm-6  col-xs-12 subject ">
                        <label class="col-sm-12">Phone Number :</label>
                        <div class="input-value col-sm-12 no-padding">
                            <input id="subject" required type="text" name="phone_number"
                                value="{{ $phone }}">
                        </div>
                    </div>
                </div>
                
                <div class="input-group row">
                    <div class="subject  col-sm-12 col-xs-12">
                        <label class="col-sm-12">Subject :</label>
                        <div class="input-value col-sm-12 no-padding">
                            <input id="subject" required type="text" name="subject"
                                value="{{ old('subject') }}">
                        </div>
                    </div>
                </div>    

                <div class="input-group row adress-row">
                    <div class="subject  col-sm-12 col-xs-12 text-message adress-signup">
                        <label class="col-sm-12">Message :</label>
                        <div class="input-value col-sm-12 no-padding">
                            <textarea id="message"
                                    name="message">{{ old('message') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="input-group submit col-xs-12 col-sm-12">
                    <div class="input-value value-submit">
                        <!-- <input type="submit" name="submit" value="Submit"/> -->
                        <button type="submit" class="login">Submit <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></button>

                    </div>
                </div>
                </form> 
              </div><!--row-wrapper-->
              </div><!--form_wrapper--> 
          </div> <!--formgroup-->   
       
    </div>
</div>

@stop
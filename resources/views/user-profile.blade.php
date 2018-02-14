@extends('layouts.base', ['type' => session()->get('type'), 'group' => session()->get('group'), 'loginStatus' => session()->get('loggedin')])
@section('title')
Edit Profile
@stop
@section('content')
<div class="content_section no-padding col-sm-12">
    <div class="header-title no-padding col-sm-12 text-center">
        <h3><span>Edit Profile</span></h3>
    </div>
    <div class="row">
      
    <div class="from-lgn formgroup  text-center no-padding col-sm-12">
      <div class="form_wrapper width-800">  
        <div class="row row-wrapper">
            <form action="{{route('user-profile-edit')}}" method="POST">  
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="input-group row">
                    <div class="col-sm-12  col-xs-12 username">
                        <label class="col-sm-12">Email :</label>

                        <div class="input-value col-sm-12 no-padding">
                            @foreach($clientProfile as $client)
                            <input id="email" required type="text" name="email" class="readonly" value="{!! $client->email !!}" readonly>
                        </div>
                    </div>
                </div>

                <div class="input-group row">

                    <div class="col-sm-6 Fname col-xs-12">
                        <label class="col-sm-12">First Name :</label>

                        <div class="input-value col-sm-12 no-padding">
                        <input id="first_name" required type="text" name="first_name" value="{!! $client->first_name !!}" >
                        </div>
                    </div>

                    <div class="col-sm-6 Lname col-xs-12">

                    <label class="col-sm-12">Last Name :</label>

                    <div class="input-value col-sm-12 no-padding">
                        <input id="last_name"  type="text" name="last_name"  value="{!! $client->last_name !!}" >
                    </div>

                </div>

                </div>

                <div class="input-group row">
                <div class="col-sm-6 Phone col-xs-12">
                    <label class="col-sm-12">Phone :</label>

                    <div class="input-value col-sm-12 no-padding">
                        <input id="phone"  type="text" name="phone"  value="{!! $client->phone !!}" >
                    </div>

                </div>
                
                <div class="col-sm-6 Cmnyname col-xs-12">

                    <label class="col-sm-12">Company Name :</label>

                    <div class="input-value col-sm-12 no-padding">
                        <input id="company_name"  type="text" name="company_name"  value="{!! $client->company_name !!}" >
                    </div>

                </div>
            </div>  

            <div class="input-group row adress-row">
                <div class="col-sm-12 Add adress-signup col-xs-12">
                    <label class="col-sm-12">Address :</label>

                    <div class="input-value col-sm-12 no-padding">
                        <!-- <input id="address" required type="text" name="address"  value="{!! $client->address !!}" > -->
                        <textarea id="address"  type="text" name="address">{!! $client->address !!}</textarea>
                    </div>

                </div>
            </div>
                <div class="input-group col-sm-12 submit">

                    <div class="input-value value-submit">
                        <!-- <input type="submit" name="submit" value=" Submit "/> -->
                        <button type="submit" name="submit">Update <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></button>
                    </div>

                </div>

                @endforeach
                </form>

           </div>
        </div><!--form_wrapper-->
      </div> <!--formgroup-->   
    </div><!--row-->
 
</div>

@stop
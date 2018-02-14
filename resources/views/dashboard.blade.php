@extends('layouts.base', ['type' => $type, 'group' => $group, 'loginStatus' => session()->get('loggedin')])
@section('title')
Vatax Cloud
@stop
@section('content')

<div class="content_section no-padding col-sm-12">
    <div class="no-padding row text-center">
        <h3><span>Welcome <?php echo ucwords(session()->get('first_name'));?></span></h3>
        <h3><span> <?php echo ucwords(session()->get('company_name'));?></span></h3>
    </div>
    <div class="row text_row">
        @if($group == 4 && $type == null )
            <p class="text">Please select the way you would like to share your travel and expense details. Click on Concur, Expensify or send us your extracted Excel report and Invoices via the encrypted drag and drop option below. If you have any questions, <a href="{{Route('contactUs')}}">contact us.</a></p>
        @endif
    </div> 
        <div class="indexdetails concur_expensify  col-sm-12">
        <div class="msgalert-section row">
                @if($errors && ! $errors->isEmpty() )
                    @foreach($errors->all() as $error)
                    <div class="alert alert-danger">{{$error}}</div>
                    @endforeach
                    @endif
        </div>   
            <div class="dashboard row">

                <div class="col-sm-12 dashboard_center">
            @if($type != 3 || $type == null)
             @if($group != 1)
                @if($type == 1 || $type == null)
                <div class="img_with_title  text-center col-sm-3">

                    <a href="{{route('concur-login')}}" class="">
                        <span class="logo_img">
                            <img src="{{ URL::asset('resources/assets/images/concur.png') }}" align="middle">
                        </span>
                        <span class="index-title">Concur Login</span>
                    </a>

                </div>
                @endif

                @if($type == 2 || $type == null)
                <div class="img_with_title text-center col-sm-3">

                    <a href="{{route('expensify-login')}}" class="">
                        <span class="logo_img">
                            <img src="{{ URL::asset('resources/assets/images/expensify.png') }}" align="middle">
                        </span>
                        <span class="index-title">Expensify Login</span>
                    </a>

                </div>
                @endif
                @endif
            @endif
            
            @if($group == 1)
            <div class="img_with_title text-center col-sm-3">

                <a href="{{route('excel-import')}}" class="">
                    <span class="logo_img">
                        <img src="{{ URL::asset('resources/assets/images/excel.png') }}" align="middle">
                    </span>
                    <span class="index-title">Excel Import</span>
                </a>

            </div>
            @endif
            
            @if($group != 1)
            <div class="img_with_title col-sm-3 text-center">

                <a href="{{route('send-email')}}" class="">
                    <span class="logo_img">
                        <img src="{{ URL::asset('resources/assets/images/excel.png') }}" align="middle">
                    </span>
                    <span class="index-title">Drag & Drop Files</span>
                </a>

            </div>
            
        </div><!--dashboard_center-->

        <div class="col-sm-12 dashboard_center dashboard_center-other">
            <div class="img_with_title  text-center col-sm-3">
            <div class="display_table">
                    <a href="{{route('user-profile')}}" class="">
                        <span class="index-title">Profile</span>
                    </a>
                    </div>
                </div>
                
                <div class="img_with_title text-center col-sm-3">
                <div class="display_table">
                    <a href="{{route('vat-potiential')}}" class="">
                        <span class="index-title">Vat Potential Report</span>
                    </a>
                    </div>
                </div>
                                        
                        
            <div class="img_with_title col-sm-3 text-center">
                <div class="display_table">
                    <a href="{{route('submission-analysis')}}" class="">
                        <span class="index-title">Submission Analysis Report</span>
                    </a>
                </div>    
            </div>
            <div class="img_with_title col-sm-3 text-center">
                <div class="display_table">
                    <a href="{{route('contactUs')}}" class="">
                        <span class="index-title">Contact Support</span>
                    </a>
                </div>
            </div>

        </div><!--dashboard_center-->
        @endif

        </div><!--dashboard-->
</div><!--concur_expensify--> 
        
    </div>



@stop
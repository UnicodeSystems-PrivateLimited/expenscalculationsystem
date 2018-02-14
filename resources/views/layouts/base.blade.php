<!DOCTYPE html>
<html>
    <head>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>@yield('title')</title>
        <!-- <link rel="stylesheet" href="{{ URL::asset('resources/assets/css/style.css') }}"> -->
         <link rel="stylesheet" href="{{ URL::asset('resources/assets/bootstrap/css/bootstrap.min.css') }}">
         <link rel="stylesheet" href="{{ URL::asset('resources/assets/css/styles.css') }}">
        <link rel="stylesheet" href="{{ URL::asset('resources/assets/css/font-awesome.min.css') }}">
        <script src="{{ URL::asset('resources/assets/js/jquery.min.js') }}"></script>
        <script type="text/javascript" src="{{ URL::asset('resources/assets/js/bootstrap.min.js') }}" async></script>
        <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
        <script type="text/javascript" src="https://cdn.ywxi.net/js/1.js" async></script>
    </head>
    <body>

    <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
          <div class="logoarea col-sm-12 ">
             <a class="navbar-brand text-center" href="{{route('dashboard')}}"><img src="{{ URL::asset('resources/assets/images/vataxcloud-logo.png') }}" style="max-width: 176px;"/></a>
        </div>
        <div class="navbar-header col-sm-12">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
         
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-center">
            <li><a href="{{route('dashboard')}}">Home</a></li>
            
            @if (isset($loginStatus) && $loginStatus)
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Account <span class="caret"></span></a>
              
              <ul class="dropdown-menu">
                <li><a href="{{route('user-profile')}}">Edit Profile</a></li>
                <li><a href="{{route('user.reset-password')}}">Change Password</a></li>
              </ul>
            </li>
          
            @if($group == 4)
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Reports <span class="caret"></span></a>
              
              <ul class="dropdown-menu">
                <li><a href="{{route('vat-potiential')}}">Vat Potential</a></li>
                <li><a href="{{route('submission-analysis')}}">Submission Analysis</a></li>
              </ul>
            </li>
            @endif 
              @if($group == 1)
              <li><a href="{{route('user-details')}}">User Details</a></li>
              @endif
            @endif 
            <li><a href="https://www.globalvatax.com/" target="_blank" class="global_vatax">About Us</a></li>
            <li><a href="{{route('contactUs')}}">Contact Us</a></li>
            
            @if (isset($loginStatus) && $loginStatus) 
            @if($group == 1)
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Manage <span class="caret"></span></a>
              
              <ul class="dropdown-menu">
               <li><a href="{{route('contact-request-list')}}">Contact Request</a></li>
                <li><a href="{{route('currency-list')}}">Exchange Rate</a></li>
                <li><a href="{{route('country-vat-list')}}">Country VAT</a></li>
                <li><a href="{{route('currency-vat-list')}}">Currency VAT</a></li>
              </ul>
            </li>
            @endif
            @endif
          </ul>
        </div><!--/.nav-collapse -->
      </div>
      @if (isset($loginStatus) && $loginStatus)
        <a href="{{route('account.logout')}}"  class="logout-btn">Logout <i class="fa fa-sign-out" aria-hidden="true"></i></a> 
      @endif
    </nav>
   
<div class="page-content col-sm-12">
    @if(Request::is('submission-analysis/*')||Request::is('vat-potential/*')||Request::is('submission-analysis')||Request::is('vat-potential'))
        <div class="container fullwidthcontainer">
            @else
            <div class="container">
                @endif
      @yield('content')
        </div> 
         
       
    </div> 
</div>
<footer class="footer">
      <div class="container text-center">
      <span>Copyright © 2017 Vatax Cloud</span>
      </div>
    </footer>

      

    </body>
</html>




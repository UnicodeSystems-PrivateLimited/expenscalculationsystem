@extends('layouts.base', ['type' => session()->get('type'), 'group' => session()->get('group'), 'loginStatus' => session()->get('loggedin')])
@section('title')
Contact Request
@stop
@section('content')
<div class="container table-container">
    <div class="content_section contactlist-page no-padding col-sm-12">
    <div class="header-title no-padding col-sm-12 text-center">
        <h3><span>Contact Request</span></h3>
        </div>
        

         <div class="search-and-pagination row">  
	     <form action="{{route('contact-search')}}" method="POST" class="input_search col-sm-6 col-xs-12">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <div class="seach-submit col-xs-12"><input id="code"  type="text" placeholder="Search.." name="code" value="{{ Session::pull('code') }}">
          <!-- <input  type="submit" name="submit" value="Search">  -->
          <button type="submit" name="submit"><i class="fa fa-search" aria-hidden="true"></i></button></div>
	    </form> 
         
        </div>
     
        <div class="table-area table-responsive col-sm-12">
            
           
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th class="">Name</th>
                        <th class="">Email</th>
                        <th class="">Phone</th>
                        <th class="">Send On</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    @if(! $contactRequestList->isEmpty() )
                        @foreach($contactRequestList as $contact)
                        <tr>
                            <td class="">{!! $contact->name !!}</td>
                            <td title="{!! $contact->from_email !!}" class="cursor-pointor">{!! $contact->from_email !!}</td>
                            <td class="">{!! $contact->phone_number !!}</td>
                            <td class="">{!! date('d, M Y', strtotime($contact->created_at)) !!}</td>
                            <td class="cursor-pointor text-center"><a href="{{route('contact-request-details', ['id' => $contact->id])}}"><i class="fa fa-eye" aria-hidden="true"></i></a></td>
                        </tr>
                        @endforeach
                    @else
                        <tr align="center">
                            <td colspan="5"><span class="text-warning"><h5>No results found.</h5></span></td>
                        </tr>
                    @endif
                </tbody>
            </table>
            

        </div><!--table-area-->
        
        <div class="paginator text-right col-sm-12 col-xs-12 contact-request-paginator">
             {{ $contactRequestList->appends(Request::only('code'))->links() }}
        </div><!--paginator-->
    </div>
</div>

@stop
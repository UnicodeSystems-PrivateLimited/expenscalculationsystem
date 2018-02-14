@extends('layouts.base', ['type' => session()->get('type'), 'group' => session()->get('group'), 'loginStatus' => session()->get('loggedin')])
@section('title')
Concur Import Log
@stop
@section('content')
<div class="container table-container">
    <div class="content_section contactlist-page no-padding col-sm-12">
    <div class="header-title no-padding col-sm-12 text-center">
        <h3><span>Concur Import Log</span></h3>
        </div>
        

         <div class="search-and-pagination row">  
	     <form action="{{route('concur-log-details-search')}}" method="POST" class="input_search col-sm-6 col-xs-12">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <div class="seach-submit col-xs-12"><input id="code"  type="text" placeholder="Search.." name="code" value="{{ Session::pull('code') }}">
          <!-- <input  type="submit" name="submit" value="Search">  -->
          <button type="submit" name="submit"><i class="fa fa-search" aria-hidden="true"></i></button></div>
	    </form> 
         <div class="paginator text-right col-sm-6 col-xs-12">
                {{ $concurLogList->links() }}
            </div>
        </div>
     
        <div class="table-area table-responsive col-sm-12">
            
           
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th class="">First Name</th>
                        <th class="">Last Name</th>
                        <th class="">Phone</th>
                        <th class="">Email</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    @if(! $concurLogList->isEmpty() )
                        @foreach($concurLogList as $concurLogList)
                        <tr>
                            <td class="">{!! $concurLogList->first_name !!}</td>
                            <td class="">{!! $concurLogList->last_name !!}</td>
                            <td class="">{!! $concurLogList->phone !!}</td>
                            <td title="{!! $concurLogList->email !!}" class="cursor-pointor">{!! $concurLogList->email !!}</td>
                            <td class="cursor-pointor text-center"><a href="{{route('user-concur-log', ['id' => $concurLogList->user_id])}}"><i class="fa fa-eye" aria-hidden="true"></i></a></td>
                        </tr>
                        @endforeach
                    @else
                        <tr align="center">
                            <td colspan="5"><span class="text-warning"><h5>No results found.</h5></span></td>
                        </tr>
                    @endif
                </tbody>
            </table>
            

        </div>
    </div>
</div>

@stop
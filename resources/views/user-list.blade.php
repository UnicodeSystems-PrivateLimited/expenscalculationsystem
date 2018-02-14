@extends('layouts.base', ['type' => session()->get('type'), 'group' => session()->get('group'), 'loginStatus' => session()->get('loggedin')])
@section('title')
User Details
@stop
@section('content')

    <div class="content_section no-padding col-sm-12">
    <div class="header-title no-padding col-sm-12 text-center">
        <h3><span>User Details</span></h3>
    </div>
    <div class="search-and-pagination row">   
	     <form action="{{route('user-search')}}" method="POST">
              <input type="hidden" name="_token" value="{{ csrf_token() }}" class="input_search col-sm-12 col-xs-12">
              <div class="seach-submit col-xs-12"><input id="code"  type="text" name="code" placeholder="Search.." value="{{ Session::pull('code') }}">
	      <!-- <input  type="submit" name="submit" value="Search">  -->
          <button type="submit" name="submit"><i class="fa fa-search" aria-hidden="true"></i></button></div>
        </form>
        </div> 
        <div class="table-area table-responsive col-sm-12">
            
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Email</th>
                        <th class="">First name</th>
                        <th class="">Last name</th>
                        <th class="">Company name</th>
                        <th class="">Phone</th>
                        <th class="">Associated Email</th>
                        <th>Status</th>
                        <th>Report</th>
                        <th>Import Log</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if(! $clientList->isEmpty() )
                    @foreach($clientList as $client)
                    <tr>
                        <td title="{!! $client->email !!}" class="cursor-pointor">{!! $client->email !!}</td>
                        <td class="" title="{!! $client->first_name !!}">{!! $client->first_name !!}</td>
                        <td class="" title="{!! $client->last_name !!}">{!! $client->last_name !!}</td>
                        <td class="" title="{!! $client->company_name !!}">{!! $client->company_name !!}</td>
                        <td class="" title="{!! $client->phone !!}">{!! $client->phone !!}</td>
                        @if($client->username)
                            <td class="cursor-pointor" title="{!! $client->username !!}">{!! $client->username !!}</td>
                        @else
                            <td class="cursor-pointor"><a
                                        href="{{ route('user.associate.email', ['id' => $client->id]) }}">Associate
                                    Email</a></td>
                        @endif
                        
                        @if($client->activated)
                            <td class="cursor-pointor"><a href="{{ route('user.change.status', ['id' => $client->id, 'status' => $client->activated]) }}" class="active">Active</a></td>
                        @else
                            <td class="cursor-pointor"><a href="{{ route('user.change.status', ['id' => $client->id, 'status' => $client->activated]) }}" class="inactive">Inactive</a></td>
                        @endif

                        @if(isset($client->company_name))
                            <td class="cursor-pointor text-center"><a href="{{route('report-dashboard', ['id' => $client->id])}}"><i class="fa fa-eye" aria-hidden="true"></i></a></td>
                        @else
                            <td class="cursor-pointor text-center"><a class="grey"><i class="fa fa-eye-slash" aria-hidden="true"></i></a></td>
                        @endif
                        @if($client->type == 1 )
                            <td class="cursor-pointor text-center"><a href="{{route('user-concur-log', ['id' => $client->id])}}"><i class="fa fa-eye" aria-hidden="true"></i></a></td>
                        @elseif($client->type == 2 )    
                            <td class="cursor-pointor text-center"><a href="{{route('user-expensify-log', ['id' => $client->id])}}"><i class="fa fa-eye" aria-hidden="true"></i></a></td>
                         @else   
                        <td class="cursor-pointor text-center"><a class="grey"><i class="fa fa-eye-slash" aria-hidden="true"></i></a></td>
                         @endif    
                        <td class="cursor-pointor">
                            <form action="{{route('user-delete')}}" method="POST" style="display: inline" onsubmit="return confirm('Do you really want to delete?');" class="square-box-del">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="id" value="{{ $client->id  }}">
                                <button class="btn btn-danger btn-xs" type="submit"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                    @else
                        <tr align="center">
                            <td colspan="8"><span class="text-warning 123"><h5>No results found.</h5></span></td>
                        </tr>
                    @endif
                </tbody>
            </table>
            
        </div><!--table-area-->
        <div class="paginator text-right">
                {{ $clientList->appends(Request::only('code'))->links() }}
        </div><!--paginator-->
    </div>

@stop
@extends('layouts.base', ['type' => session()->get('type'), 'group' => session()->get('group'), 'loginStatus' => session()->get('loggedin')])
@section('title')
Exchange Rate
@stop
@section('content')
<div class="container table-container">
    <div class="content_section contactlist-page no-padding col-sm-12">
    <div class="header-title no-padding col-sm-12 text-center currencylist">
        <h3><span>Exchange Rate</span>
         <a href="{{route('currency-add')}}" class="Zoho-reportlogin add_currnecy text-right show-tablet"><i class="fa fa-plus" aria-hidden="true"></i> Add Currency</a>
    </h3>
        
        </div>
    
	    
      <div class="search-and-pagination row">  
      <form action="{{route('currency-search')}}" method="POST" class="input_search col-sm-8 col-xs-12 ">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <div class="seach-submit col-xs-12"><input id="code" placeholder="Search Currency" type="text" name="code" value="{{ Session::pull('code') }}">
          <!-- <input  type="submit" name="submit" value="Search"> -->
          <button type="submit" name="submit"><i class="fa fa-search" aria-hidden="true"></i></button></div> 
        </form> 
        <div class="col-sm-4 col-xs-12 text-right hide-tablet">
             <a href="{{route('currency-add')}}" class="Zoho-reportlogin add_currnecy text-right hide-tablet"><i class="fa fa-plus" aria-hidden="true"></i> Add Currency</a>
        </div>
       
    </div><!--search-and-pagination-->
        <div class="table-area table-responsive col-sm-12">
           
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th class="">Currency</th>
                        <th class="">Exchange Rate</th>
                        <th class="">Base Currency</th>
                        <th class="">Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if(! $currencies->isEmpty() )
                        @foreach($currencies as $currency)
                        <tr>
                            <td class="">{!! $currency->currency_code !!}</td>
                            <td class="">{!! $currency->exchange_rate !!}</td>
                            @if($currency->base_currency)
                                <td class="cursor-pointor"><a href="#" class="active checkcircle-icon"><i class="fa fa-check" aria-hidden="true"></i></a></td>
                            @else
                                <td class="cursor-pointor"><a href="#" class="inactive checkcircle-icon"><i class="fa fa-times" aria-hidden="true"></i></a></td>
                            @endif
                            @if($currency->status)
                                <td class="cursor-pointor"><a href="{{ route('currency-change-status', ['id' => $currency->id, 'status' => $currency->status]) }}" class="active">Active</a></td>
                            @else
                                <td class="cursor-pointor"><a href="{{ route('currency-change-status', ['id' => $currency->id, 'status' => $currency->status]) }}" class="inactive">Inactive</a></td>
                            @endif
                            <td class="cursor-pointor"><a href="{{ route('currency-edit', ['id' => $currency->id])}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></td>
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
        <div class="paginator text-right col-sm-12 col-xs-12">
                {{ $currencies->appends(Request::only('code'))->links() }}
        </div><!--paginator-->
    </div>
</div>

@stop
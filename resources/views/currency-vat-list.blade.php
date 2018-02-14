@extends('layouts.base', ['type' => session()->get('type'), 'group' => session()->get('group'), 'loginStatus' => session()->get('loggedin')])
@section('title')
Currency VAT
@stop
@section('content')
<div class="container table-container">
    <div class="content_section contactlist-page no-padding col-sm-12">
        <div class="header-title no-padding col-sm-12 text-center currencylist">
            <h3>
                <span>Currency VAT</span>
                <a href="{{route('currency-vat-add')}}" class="Zoho-reportlogin add_currnecy text-right show-tablet"><i class="fa fa-plus" aria-hidden="true"></i> Add Currency VAT</a>
            </h3>
        </div>
        <div class="search-and-pagination row search-add-currency">  
            <form action="{{route('currency-vat-search')}}" method="POST" class="input_search col-sm-8 col-xs-12 ">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div>
                <div class="seach-submit col-sm-5 col-xs-5"><input id="code" placeholder="Currency" type="text" name="code" value="{{ Session::pull('code') }}"></div>
                    <div class="seach-submit col-sm-5 col-xs-5"><input id="expense" placeholder="Expense Type" type="text" name="expense" value="{{ Session::pull('expense') }}"></div>
                <button type="submit" name="submit"><i class="fa fa-search" aria-hidden="true"></i></button></div>
            </form>
            <div class="col-sm-4 col-xs-12 text-right hide-tablet">
                <a href="{{route('currency-vat-add')}}" class="Zoho-reportlogin add_currnecy text-right hide-tablet square-box-edit"><i class="fa fa-plus" aria-hidden="true"></i> Add Currency VAT</a>    
            </div>
           
        </div>
        <div class="table-area table-responsive col-sm-12">
           
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th class="">Currency</th>
                        <th class="">Expense Type</th>
                        <th class="">VAT</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if(! $CurrencyVat->isEmpty() )
                        @foreach($CurrencyVat as $cv)
                        <tr>
                            <td class="">{!! $cv->currency_code !!}</td>
                            <td class="">{!! ucfirst($cv->expense_type) !!}</td>
                            <td class="">{!! $cv->vat !!}</td>
                            <td class="cursor-pointor">
                                <a href="{{ route('currency-vat-edit', ['id' => $cv->id])}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                <form action="{{route('currency-vat-delete')}}" method="POST" style="display: inline" onsubmit="return confirm('Do you really want to delete?');">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="id" value="{{ $cv->id  }}">
                                    <button class="btn btn-danger btn-xs" type="submit"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr align="center">
                            <td colspan="5"><span class="text-warning"><h5>No results found.</h5></span></td>
                        </tr>
                    @endif
                </tbody>
            </table>
            

        </div><!--tabel-area-->
        
        <div class="paginator text-right col-sm-12 col-xs-12">
                {{ $CurrencyVat->appends(Request::only('code','expense'))->links() }}
        </div><!--paginator-->
    </div>
</div>

@stop
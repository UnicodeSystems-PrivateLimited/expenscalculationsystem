@extends('layouts.base', ['type' => session()->get('type'), 'group' => session()->get('group'), 'loginStatus' => session()->get('loggedin')])
@section('title')
    Currency VAT
@stop
@section('content')
<div class="content_section no-padding col-sm-12">
    <a href="{{ URL::previous() }}" class="go_back" title="Go back">
        <i class="fa fa-arrow-circle-left" aria-hidden="true"></i>
    </a>    
    <div class="header-title no-padding col-sm-12 text-center">
        <h3><span>Currency VAT</span></h3>
    </div>
       
    <div class="from-lgn formgroup  text-center no-padding col-sm-12">

        <div class="form_wrapper login">
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
                    <div class="alert alert-success">
                        <p id="message">{{ $message }}</p>
                    </div>  
                @endif
            </div><!--msgalert-section-->

            <div class="row row-wrapper">
                <form action="{{route('currency-vat-edit', ['id' => $id])}}" method="POST" class="country-vat-edit">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="id" value="{!! $id !!}">

                    <div class="input-group row">
                        <div class="col-sm-12 user_id">
                            <label class="col-sm-12" for="currency">Currency :</label>
                            <div class="input-group col-sm-12 no-padding select-group">
                                <select name="currency" required>
                                    <option value="">--Select Currency--</option>
                                    @foreach($currencies as $currency)
                                    <option value="{!! $currency->id !!}" {{ ($currencyVatDetail->currency_id == $currency->id) ? "selected" : "" }}>{!! $currency->currency_code !!}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="input-group row">
                        <div class="col-sm-12 user_id">
                            <label class="col-sm-12" for="exchange_rate">Expense :</label>
                            <div class="input-group col-sm-12 no-padding select-group">
                                <select name="expense" required>
                                    <option value="">--Select Expense--</option>
                                    @foreach($expenses as $expense)
                                    <option value="{!! $expense->id !!}" {{ ($currencyVatDetail->expense_id == $expense->id) ? "selected" : "" }}>{!! $expense->expense_type !!}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="input-group row">
                        <div class="col-sm-12 user_id">
                            <label class="col-sm-12" for="vat">VAT :</label>
                            <div class="input-value col-sm-12 no-padding">
                                <input type="text" name="vat" required value="{{ $currencyVatDetail->vat }}">
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 input-group submit">
                        <div class="input-value value-submit">
                            <button type="submit" name="submit">Submit <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></button>
                        </div>
                    </div>
                </form>
            </div>
       </div>
   </div>
</div>  
@stop
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;
use App\Models\Currency;
use View;
use DB;

class CurrencyController extends Controller {

    public function currencyList() {
        $currencies = Currency::getCurrencyList();
        return View::make('currency-list')->with(['currencies' => $currencies]);
    }
    
    public function statusChange(Request $request) {
        $data = ['status' => !$request->status];
        Currency::where('id', $request->id)->update($data);
        return redirect()->route("currency-list");
    }
    
    public function setBaseCurrency(Request $request) {
        Currency::where('base_currency', 1)->update(['base_currency' => 0]);
        Currency::where('id', $request->id)->update(['base_currency' => 1]);
        return redirect()->route("currency-list");
    }
    
    public function addCurrency(Request $request) {
        $data = [];
        if ($request->isMethod('post')) {
            $values = $request->only('currency_code','exchange_rate','status');
            $values['currency_code'] = strtoupper($values['currency_code']);
            $this->validate($request, [
                'currency_code' => 'required|unique:currency_rate,currency_code',
                'exchange_rate' => 'required|numeric'
                ], [
                'unique' => 'Currency already added.'
            ]);
            Currency::create($values);
            $data['message'] = 'Currency added successfully.';
        }
        return View('currency-add', $data);
    }
    
    public function editCurrency(Request $request) {
        $data = [];
        $id = $request->id;
        $data['currencyDetail'] = Currency::getCurrencyDetailsById($id);
        if ($request->isMethod('post')) {
            $values = $request->only('currency_code','exchange_rate','status');
            $this->validate($request, [
                'currency_code' => 'required|unique:currency_rate,currency_code,'.$id,
                'exchange_rate' => 'required|numeric'
                ], [
                'unique' => 'Currency already added.'
            ]);
            Currency::where('id', $request->id)->update($values);
            $data['message'] = 'Currency updated successfully.';
        }
        return View::make('currency-edit')->with(['id' => $id, 'data' => $data]);
    }
    
     public function searchCurrency( request $request) {
        $currencies = DB::table('currency_rate')->where('currency_code', $request->code)->paginate(1);
        session()->flash('code', $request->code);
        return View::make('currency-list')->with(['currencies' => $currencies]);
    }

}

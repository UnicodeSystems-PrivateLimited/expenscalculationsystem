<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;
use App\Models\CurrencyVat;
use App\Models\Currency;
use App\Models\Expenses;
use App\Helpers\Core;
use View;
use DB;

class CurrencyVatController extends Controller {

    public function CurrencyVatList() {
        $CurrencyVat = CurrencyVat::getCurrencyVatList();
        return View::make('currency-vat-list')->with(['CurrencyVat' => $CurrencyVat]);
    }
    
    public function addCurrencyVat(Request $request) {
        $data['currencies'] = Currency::getCurrencyListForVat();
        $data['expenses'] = Expenses::getExpenses();
        if ($request->isMethod('post')) {
            $values = $request->only('currency','expense','vat');
            $this->validate($request, [
                'currency' => 'required',
                'expense' => 'required|unique:currency_vat_data,expense_id,id,'. NULL .',currency_id,'. $request->currency,
                'vat' => 'required|numeric'
                ], [
                'unique' => 'VAT exist for seleted currency expense.'
            ]);
            $val['currency_id'] = $values['currency'];
            $val['expense_id'] = $values['expense'];
            $val['vat'] = $values['vat'];
            CurrencyVat::create($val);
            $data['message'] = 'Currency VAT added successfully.';
            $CurrencyVat = CurrencyVat::getAllCurrencyVat();
            foreach ($CurrencyVat as $cv) {
                $currencyVatArray[strtolower($cv->currency_code) . '_' . $cv->expense_type] = $cv->vat;
            }
            $contents = json_encode($currencyVatArray);
            Core::updateVatData($contents, 'vat_data_acc_to_currency.json');
        }
        return View('currency-vat-add', $data);
    }
    
    public function editCurrencyVat(Request $request) {
        $data = [];
        $data['id'] = $request->id;
        $data['currencies'] = Currency::getCurrencyListForVat();
        $data['expenses'] = Expenses::getExpenses();
        if ($request->isMethod('post')) {
            $values = $request->only('currency','expense','vat');
            $val['currency_id'] = $values['currency'];
            $val['expense_id'] = $values['expense'];
            $val['vat'] = $values['vat'];
            $this->validate($request, [
                'currency' => 'required',
                'expense' => 'required|unique:currency_vat_data,expense_id,'. $request->id .',id,currency_id,' . $request->currency,
                'vat' => 'required|numeric'
                ], [
                'unique' => 'VAT exist for seleted currency expense.'
            ]);
            CurrencyVat::where('id', $request->id)->update($val);
            $data['message'] = 'Currency VAT updated successfully.';
            $CurrencyVat = CurrencyVat::getAllCurrencyVat();
            foreach ($CurrencyVat as $cv) {
                $currencyVatArray[strtolower($cv->currency_code) . '_' . $cv->expense_type] = $cv->vat;
            }
            $contents = json_encode($currencyVatArray);
            Core::updateVatData($contents, 'vat_data_acc_to_currency.json');
        }
        $data['currencyVatDetail'] = CurrencyVat::getCurrencyVatDetailsById($data['id']);
        return View('currency-vat-edit', $data);
    }
    
    public function searchCurrencyVat( request $request) {
        $CurrencyVat =  CurrencyVat::searchCurrencyVatList($request->code,$request->expense);
        session()->flash('code', $request->code);
        session()->flash('expense', $request->expense);
        return View::make('currency-vat-list')->with(['CurrencyVat' => $CurrencyVat]);
    }
    
    public function deleteCurrencyVat( request $request) {
        CurrencyVat::find($request->id)->delete();
        $CurrencyVat = CurrencyVat::getAllCurrencyVat();
        foreach ($CurrencyVat as $cv) {
            $currencyVatArray[strtolower($cv->currency_code) . '_' . $cv->expense_type] = $cv->vat;
        }
        $contents = json_encode($currencyVatArray);
        Core::updateVatData($contents, 'vat_data_acc_to_currency.json');
        return redirect()->route('currency-vat-list');
    }

}

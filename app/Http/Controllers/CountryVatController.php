<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;
use App\Models\CountryVat;
use App\Models\Countries;
use App\Models\Expenses;
use App\Helpers\Core;
use View;
use DB;

class CountryVatController extends Controller {

    public function CountryVatList() {
        $CountryVat = CountryVat::getCountryVatList();
        return View::make('country-vat-list')->with(['CountryVat' => $CountryVat]);
    }
    
    public function addCountryVat(Request $request) {
        $data['countries'] = Countries::getCountries();
        $data['expenses'] = Expenses::getExpenses();
        if ($request->isMethod('post')) {
            $values = $request->only('country','expense','vat');
            $this->validate($request, [
                'country' => 'required',
                'expense' => 'required|unique:country_vat_data,expense_id,id,'. NULL .',country_id,'. $request->country,
                'vat' => 'required|numeric'
                ], [
                'unique' => 'VAT exist for seleted country expense.'
            ]);
            $val['country_id'] = $values['country'];
            $val['expense_id'] = $values['expense'];
            $val['vat'] = $values['vat'];
            CountryVat::create($val);
            $data['message'] = 'Country VAT added successfully.';
            $CountryVat = CountryVat::getAllCountryVat();
            foreach ($CountryVat as $cv) {
                $countryVatArray[strtolower($cv->country_code) . '_' . $cv->expense_type] = $cv->vat;
            }
            $contents = json_encode($countryVatArray);
            Core::updateVatData($contents, 'vat_data_acc_to_country.json');
        }
        return View('country-vat-add', $data);
    }
    
    public function editCountryVat(Request $request) {
        $data = [];
        $data['id'] = $request->id;
        $data['countries'] = Countries::getCountries();
        $data['expenses'] = Expenses::getExpenses();
        if ($request->isMethod('post')) {
            $values = $request->only('country','expense','vat');
            $val['country_id'] = $values['country'];
            $val['expense_id'] = $values['expense'];
            $val['vat'] = $values['vat'];
            $this->validate($request, [
                'country' => 'required',
                'expense' => 'required|unique:country_vat_data,expense_id,'. $request->id .',id,country_id,' . $request->country,
                'vat' => 'required|numeric'
                ], [
                'unique' => 'VAT exist for seleted country expense.'
            ]);
            CountryVat::where('id', $request->id)->update($val);
            $data['message'] = 'Country VAT updated successfully.';
            $CountryVat = CountryVat::getAllCountryVat();
            foreach ($CountryVat as $cv) {
                $countryVatArray[strtolower($cv->country_code) . '_' . $cv->expense_type] = $cv->vat;
            }
            $contents = json_encode($countryVatArray);
            Core::updateVatData($contents, 'vat_data_acc_to_country.json');
        }
        $data['countryVatDetail'] = CountryVat::getCountryVatDetailsById($data['id']);
        return View('country-vat-edit', $data);
    }
    
    public function searchCountryVat( request $request) {
        $CountryVat =  CountryVat::searchCountryVatList($request->code,$request->expense);
        session()->flash('code', $request->code);
        session()->flash('expense', $request->expense);
        return View::make('country-vat-list')->with(['CountryVat' => $CountryVat]);
    }
    
    public function deleteCountryVat( request $request) {
        CountryVat::find($request->id)->delete();
        $CountryVat = CountryVat::getAllCountryVat();
        foreach ($CountryVat as $cv) {
            $countryVatArray[strtolower($cv->country_code) . '_' . $cv->expense_type] = $cv->vat;
        }
        $contents = json_encode($countryVatArray);
        Core::updateVatData($contents, 'vat_data_acc_to_country.json');
        return redirect()->route('country-vat-list');
    }

}

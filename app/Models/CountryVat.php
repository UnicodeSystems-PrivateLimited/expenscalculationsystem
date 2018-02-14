<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class CountryVat extends Model {

    protected $table = 'country_vat_data';
    protected $guarded = ["id"];

    public static function getCountryVatList() {
        return DB::table('country_vat_data')
                        ->leftJoin('countries', 'countries.id', '=', 'country_vat_data.country_id')
                        ->leftJoin('expenses', 'expenses.id', '=', 'country_vat_data.expense_id')
                        ->select('country_vat_data.id', 'country_vat_data.vat', 'countries.country_code', 'expenses.expense_type')
                        ->orderBy('country_code', 'asc')
                        ->orderBy('expense_type', 'asc')
                        ->paginate(10);
    }
    
    public static function getAllCountryVat() {
        return DB::table('country_vat_data')
                        ->leftJoin('countries', 'countries.id', '=', 'country_vat_data.country_id')
                        ->leftJoin('expenses', 'expenses.id', '=', 'country_vat_data.expense_id')
                        ->select('country_vat_data.id', 'country_vat_data.vat', 'countries.country_code', 'expenses.expense_type')
                        ->orderBy('country_code', 'asc')
                        ->orderBy('expense_type', 'asc')
                        ->get();
    }

    public static function getCountryVatDetailsById($id) {
        return DB::table('country_vat_data')
                        ->where('id', $id)
                        ->first();
    }
    
    public static function searchCountryVatList($code,$expense) {
        return DB::table('country_vat_data')
                        ->leftJoin('countries', 'countries.id', '=', 'country_vat_data.country_id')
                        ->leftJoin('expenses', 'expenses.id', '=', 'country_vat_data.expense_id')
                        ->select('country_vat_data.id', 'country_vat_data.vat', 'countries.country_code', 'expenses.expense_type')
                        ->where('countries.country_code','LIKE', '%' . $code. '%')
                        ->where('expenses.expense_type','LIKE', '%' . $expense. '%')
                        ->orderBy('country_code', 'asc')
                        ->orderBy('expense_type', 'asc')
                        ->paginate(10)
                        ->setPath ( '' );
    }
    
    public static function getAllDistinctCountries() {
        $result = DB::table('country_vat_data')
                        ->leftJoin('countries', 'countries.id', '=', 'country_vat_data.country_id')
                        ->select(DB::raw("DISTINCT('country_vat_data.country_id')"), 'countries.country_code')
                        ->get();
        $returnArray = [];
        foreach ($result as $res) {
            array_push($returnArray, strtolower($res->country_code));
        }
        return $returnArray;
    }

}

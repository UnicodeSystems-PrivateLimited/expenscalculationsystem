<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class CurrencyVat extends Model {

    protected $table = 'currency_vat_data';
    protected $guarded = ["id"];

    public static function getCurrencyVatList() {
        return DB::table('currency_vat_data')
                        ->leftJoin('currency_rate', 'currency_rate.id', '=', 'currency_vat_data.currency_id')
                        ->leftJoin('expenses', 'expenses.id', '=', 'currency_vat_data.expense_id')
                        ->select('currency_vat_data.id', 'currency_vat_data.vat', 'currency_rate.currency_code', 'expenses.expense_type')
                        ->orderBy('currency_code', 'asc')
                        ->orderBy('expense_type', 'asc')
                        ->paginate(10);
    }
    
    public static function getAllCurrencyVat() {
        return DB::table('currency_vat_data')
                        ->leftJoin('currency_rate', 'currency_rate.id', '=', 'currency_vat_data.currency_id')
                        ->leftJoin('expenses', 'expenses.id', '=', 'currency_vat_data.expense_id')
                        ->select('currency_vat_data.id', 'currency_vat_data.vat', 'currency_rate.currency_code', 'expenses.expense_type')
                        ->orderBy('currency_code', 'asc')
                        ->orderBy('expense_type', 'asc')
                        ->get();
    }

    public static function getCurrencyVatDetailsById($id) {
        return DB::table('currency_vat_data')
                        ->where('id', $id)
                        ->first();
    }
    
    public static function searchCurrencyVatList($code,$expense) {
        return DB::table('currency_vat_data')
                        ->leftJoin('currency_rate', 'currency_rate.id', '=', 'currency_vat_data.currency_id')
                        ->leftJoin('expenses', 'expenses.id', '=', 'currency_vat_data.expense_id')
                        ->select('currency_vat_data.id', 'currency_vat_data.vat', 'currency_rate.currency_code', 'expenses.expense_type')
                        ->where('currency_rate.currency_code','LIKE', '%' . $code. '%')
                        ->where('expenses.expense_type','LIKE', '%' . $expense. '%')
                        ->orderBy('currency_code', 'asc')
                        ->orderBy('expense_type', 'asc')
                        ->paginate(10)
                        ->setPath ( '' );
    }

}

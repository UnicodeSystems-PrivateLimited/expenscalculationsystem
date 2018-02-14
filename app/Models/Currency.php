<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Currency extends Model {

    protected $table = 'currency_rate';
    protected $guarded = ["id"];

    public static function getCurrencyList() {
        return DB::table('currency_rate')
                        ->orderBy('currency_code', 'asc')
                        ->paginate(10);
    }
    
    public static function getCurrencyListForVat() {
        return DB::table('currency_rate')
                        ->orderBy('currency_code', 'asc')
                        ->get();
    }

    public static function getCurrencyDetailsById($id) {
        return DB::table('currency_rate')
                        ->where('id', $id)
                        ->first();
    }

    public static function getCurrencies() {
        $result = DB::table('currency_rate')
                ->select('currency_code', 'exchange_rate')
                ->orderBy('currency_code', 'asc')
                ->where('status', 1)
                ->get();
        
        foreach ($result as $res) {
            $returnArray[$res->currency_code] = $res->exchange_rate;
        }
        return $returnArray;
    }
    
    public static function getBaseCurrency() {
        $result =  DB::table('currency_rate')
                        ->select('currency_code')
                        ->where('base_currency', 1)
                        ->first();
        return $result ? $result->currency_code : FALSE;
    }

}

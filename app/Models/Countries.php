<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Countries extends Model {

    protected $table = 'countries';
    protected $guarded = ['id'];

    public static function getCountries() {
        $result = self::select('id', 'country_code')
                        ->orderBy('country_code', 'asc')
                        ->get();
        return $result;
    }
    
}

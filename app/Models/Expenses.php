<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expenses extends Model {

    protected $table = 'expenses';
    protected $guarded = ['id'];

    public static function getExpenses() {
        $result = self::select('id', 'expense_type')
                        ->orderBy('expense_type', 'asc')
                        ->get();
        return $result;
    }
    
    public static function getExpenseList() {
        $result = self::select('expense_type')
                        ->orderBy('expense_type', 'asc')
                        ->get();
        $returnArray = [];
        foreach ($result as $res) {
            array_push($returnArray, $res->expense_type);
        }
        return $returnArray;
    }
}

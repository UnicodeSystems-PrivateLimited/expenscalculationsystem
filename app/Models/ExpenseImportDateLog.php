<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class ExpenseImportDateLog extends Model {

    protected $table = 'expense_import_date_log';
    protected $guarded = ['id'];
    
    public static function checkImportStatus($userId): bool {
        $result = self::select('id')
            ->where(function ($query){
            $query->where('import_status','0')
            ->orWhere('import_status','1');
        })
            ->where('user_id', $userId)
        ->first();
        return $result ? TRUE : False;
    }
    
    public static function checkIfExists($fromdate,$todate,$userId): bool {
        $result = self::select('id')
            ->where(function ($query) use ($fromdate, $todate) {
                $query->whereRaw(DB::raw('(\'' . $fromdate . '\' between from_date and to_date) OR (\'' . $todate . '\' between from_date and to_date)'))
                    ->orWhereBetween('from_date', [$fromdate, $todate])
                    ->orWhereBetween('to_date', [$fromdate, $todate]);
            })
                ->where('user_id', $userId)
                ->first();
        return $result ? TRUE : FALSE;
    }
    
    public static function showDateLog($userId) {
        return DB::table('expense_import_date_log')
                        ->select('from_date', 'to_date','created_at')
                        ->where('user_id', $userId)
                        ->paginate(10)
                        ->setPath ( '' );
    }
    
    public static function getConcurLogList() {
        return DB::table('expense_import_date_log')
                ->leftJoin('user_profile', 'expense_import_date_log.user_id', '=', 'user_profile.user_id')
                ->leftjoin('users', 'expense_import_date_log.user_id', '=', 'users.id')
                ->select('user_profile.first_name','user_profile.last_name','user_profile.company_name','user_profile.phone','users.email','expense_import_date_log.from_date', 'expense_import_date_log.to_date','expense_import_date_log.created_at','expense_import_date_log.user_id')
                ->paginate(10);
    }
    
    public static function searchUserConcurLog($code) {
        return DB::table('expense_import_date_log')
                        ->leftJoin('user_profile', 'expense_import_date_log.user_id', '=', 'user_profile.user_id')
                        ->leftJoin('users', 'expense_import_date_log.user_id', '=', 'users.id')
                        ->select('users.email', 'user_profile.first_name', 'user_profile.last_name', 'user_profile.company_name', 'user_profile.phone','expense_import_date_log.user_id')
                        ->where('user_profile.first_name','LIKE', '%' . $code. '%')
                        ->orwhere('user_profile.last_name','LIKE', '%' . $code. '%')
                        ->orwhere('user_profile.company_name', 'LIKE', '%' .$code. '%')
                        ->orwhere('user_profile.phone', 'LIKE', '%' .$code. '%')
                        ->orwhere('users.email','LIKE', '%' . $code. '%')
                        ->paginate(10)
                        ->setPath ( '' );
    }

    public static function getLatestDateLog() {
        return DB::table('expense_import_date_log')
            ->select('id','user_id','from_date', 'to_date','current_from_date')
            ->where('import_status', 0)
            ->first();
    }
    

}

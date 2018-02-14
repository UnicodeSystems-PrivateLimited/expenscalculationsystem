<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Users extends Model {

    protected $table = 'users';
    protected $guarded = ["id"];

    public static function getClientList() {
        return DB::table('users')
                        ->leftJoin('user_profile', 'users.id', '=', 'user_profile.user_id')
                        ->leftJoin('users_groups', 'users.id', '=', 'users_groups.user_id')
                        ->leftJoin('api_accounts', 'users.id', '=', 'api_accounts.user_id')
                        ->select('users.id', 'users.email', 'users.activated', 'user_profile.first_name', 'user_profile.last_name', 'user_profile.company_name', 'user_profile.phone', 'api_accounts.username','api_accounts.type')
                        ->where('users_groups.group_id', 4)
                        ->paginate(10);
    }

    public static function getClientemail($id) {
        return DB::table('users')
                        ->leftJoin('user_profile', 'users.id', '=', 'user_profile.user_id')
                        ->select('users.id', 'users.email', 'user_profile.first_name', 'user_profile.last_name', 'user_profile.company_name')
                        ->where('users.id', $id)
                        ->get();
    }

    public static function getUserRole($id) {
        return DB::table('users')
                        ->leftJoin('users_groups', 'users.id', '=', 'users_groups.user_id')
                        ->select('users_groups.group_id')
                        ->where('users.id', $id)
                        ->get();
    }

    public static function getUserDetails($id) {
        return DB::table('users')
                        ->leftjoin('user_profile', 'users.id', '=', 'user_profile.user_id')
                        ->select('user_profile.first_name','user_profile.last_name','user_profile.company_name','user_profile.phone','user_profile.address','users.email')
                        ->where('users.id', $id)
                        ->get();
    }
    
    public static function searchClientList($code) {
        return DB::table('users')
                        ->leftJoin('user_profile', 'users.id', '=', 'user_profile.user_id')
                        ->leftJoin('api_accounts', 'users.id', '=', 'api_accounts.user_id')
                        ->select('users.id', 'users.email', 'users.activated', 'user_profile.first_name', 'user_profile.last_name', 'user_profile.company_name', 'user_profile.phone', 'api_accounts.username')
                        ->where('user_profile.first_name','LIKE', '%' . $code. '%')
                        ->orwhere('user_profile.last_name','LIKE', '%' . $code. '%')
                        ->orwhere('user_profile.company_name', 'LIKE', '%' .$code. '%')
                        ->orwhere('user_profile.phone', 'LIKE', '%' .$code. '%')
                        ->orwhere('users.email','LIKE', '%' . $code. '%')
                        ->orwhere('api_accounts.username', 'LIKE', '%' .$code. '%')
                        ->paginate(10)
                        ->setPath ( '' );
    }

    public static function getOwnerType($id) {
        return DB::table('users')
            ->select('is_owner')
            ->where('id', $id)
            ->get()->toArray();
    }

}

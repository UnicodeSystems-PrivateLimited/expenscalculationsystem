<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class UserProfile extends Model
{

    protected $table = 'user_profile';
    protected $guarded = ["id"];

    public static function updateUserProfile(array $data, $id) {
        DB::table('user_profile')
            ->where('user_profile.user_id', $id)
            ->update($data);
    }

    public static function getUserProfile($id) {
        return DB::table('user_profile')
            ->select('company_name')
            ->where('user_id', $id)
            ->get()->toArray();
    }


}

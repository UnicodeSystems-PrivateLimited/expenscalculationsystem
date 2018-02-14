<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsersGroups extends Model {

    protected $table = 'users_groups';
    protected $guarded = ["user_id"];


    public static function createUsersGroups (array $data) {
        self::insert($data);
    }

}

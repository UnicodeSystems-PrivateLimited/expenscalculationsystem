<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class ContactUs extends Model {

    protected $table = 'contact_us';
    protected $guarded = ["id"];

     public static function saveMessage(array $data) {
        if (!isset($data['from_email'])) {
            throw new \Exception('Email not provided.');
        }
            self::create($data);
    }
    
    public static function getContactRequestList() {
        return DB::table('contact_us')
                ->orderBy('created_at', 'desc')
                ->paginate(10);
    }

    public static function getContactDetailsById($id) {
        return DB::table('contact_us')
                ->where('id', $id)
                ->first();
    }
}

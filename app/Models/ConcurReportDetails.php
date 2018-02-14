<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class ConcurReportDetails extends Model
{

    protected $table = 'concur_report_details';
    protected $guarded = ['id'];

    public static function getLatestReportDetails() {
        return DB::table('concur_report_details')
            ->select('id', 'user_id', 'report_id', 'is_processed')
            ->where('is_processed', 0)
            ->orderby('id')
            ->limit(9)
            ->get()->toArray();
    }


}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class ConcurExpenseDetails extends Model
{

    protected $table = 'concur_expense_details';
    protected $guarded = ['id'];


    public static function saveExpenseDetails($data) {
        return self::create($data);
    }

    public static function getReceiptDetails() {
        return DB::table('concur_expense_details')
            ->select('id', 'user_id', 'report_entry_id')
            ->where('receipt_available', 1)
            ->where('receipt_downloaded', 0)
            ->first();
    }

    public static function getZohoUploadData() {
        return DB::table('concur_expense_details')
            ->select('id', 'user_id', 'report_entry_id', 'entity', 'transaction_date', 'expense_type', 'expense_entered_text', 'business_purpose', 'vendor', 'country_code', 'city', 'department', 'expense_amount', 'converted_expense_amount', 'transaction_currency', 'vat', 'converted_vat', 'currency_exchange_rate', 'vat_percentage', 'country', 'invoice_status', 'receipt_url')
            ->where('zoho_import', 0)
            ->orderby('id')
            ->limit(100)
            ->get()->ToArray();
    }

}

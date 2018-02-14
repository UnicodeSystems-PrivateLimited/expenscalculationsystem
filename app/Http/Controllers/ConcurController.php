<?php

namespace App\Http\Controllers;

use App\Models\UserProfile;
use Illuminate\Http\Request;
use App\Helpers\Zoho;
use App\Helpers\CURLRequest;
use Exception;
use App\Models\ApiAccounts;
use App\Models\ExpenseImportDateLog;
use App\Models\Currency;
use App\Models\Expenses;
use App\Models\CountryVat;
use App\Models\ConcurReportDetails;
use App\Models\ConcurExpenseDetails;
use SimpleXMLElement;
use View;
use Validator;
use DB;
use DateInterval;
use DateTime;

class ConcurController extends Controller
{

    private $accessToken;
    public $reports = [];
    public $expenses = [];
    public $attachments = [];
    public $receiptImg = [];
    public $fromdate;
    public $todate;
    private $clientID;
    private $userID;

    const DEFAULT_START_DATE = '2017-01-01';
    const GET_ACCESS_TOKEN_URL = 'https://us.api.concursolutions.com/oauth2/v0/token/';
    const GET_ALL_REPORTS_URL = 'https://www.concursolutions.com/api/v3.0/expense/reports/';
    const GET_REPORT_DETAIL_URL = 'https://www.concursolutions.com/api/expense/expensereport/v2.0/report/';
    const GET_RECIEPT_IMAGE_URL = 'https://www.concursolutions.com/api/image/v1.0/expenseentry/';
    const STORAGE = '/storage/';


    public function login() {
        $id = array_values(session()->get('laravel_acl_sentry'))[0];
        $userEmail = ApiAccounts::getUserEmail($id);
        if (empty(!$userEmail)) {
            return View::make('concur-login')->with(['userEmail' => $userEmail[0]['username']]);
        } else {
            return View::make('concur-login')->with(['userEmail' => NULL]);
        }
    }

    public function loginAction(Request $request) {
        try {
            $data = $request->all();
            $fdate = $request->fromdate;
            $tdate = $request->todate;
            $validator = Validator::make($data, [
                'username' => 'required',
                'password' => 'required',
                'client_id' => 'required',
                'client_secret' => 'required',
                'fromdate' => 'required',
                'todate' => 'required|after:' . $fdate
            ], [
                'after' => 'The To date must be a date after From date.'
            ]);
            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
            }
            unset($data['submit']);
            $data['grant_type'] = "password";
            $this->clientID = $data['username'];
            $id = $this->userID = array_values(session()->get('laravel_acl_sentry'))[0];
            $data['user_id'] = $id;
            $userExists = ApiAccounts::checkIfEmailExists($data['username'], $id);
            if ($userExists) {
                throw new Exception('Email already used.');
            }
            $pass = $data['password'];
            $data['password'] = urlencode($pass);
//            print_r($data);exit;
            $object = $this->getAccessToken($data);
//            Check Expense Import Date Log
            $saveExpense = ExpenseImportDateLog::checkIfExists($fdate, $tdate, $id);
            if ($saveExpense) {
                throw new Exception('Import is already done for given Date Range');
            } else {
                $importStatus = ExpenseImportDateLog::checkImportStatus($id);
                if ($importStatus) {
                    throw new Exception('Please wait till previous import is done');
                }
            }
            //Save User
            $user = ApiAccounts::saveUser([
                'username' => $data['username'],
                'client_id' => $data['client_id'],
                'password' => $pass,
                'client_secret' => $data['client_secret'],
                'user_id' => $id,
                'type' => 1,
                'ongoing_import' => 1,
                'last_retrieved_at' => date('Y-m-d H:i:s')
            ]);
            DB::table('api_accounts')->where('user_id', $data['user_id'])->update(array('access_token' => $object->access_token, 'refresh_token' => $object->refresh_token, 'ongoing_import' => '1'));
            //save user in zoho
            if (!$user) {
                $zoho = new Zoho();
                $zoho->saveClient(array(array('Client Email' => $data['username'])));
            }
//       Save Expense Import Date Log
            ExpenseImportDateLog::create([
                'from_date' => $data['fromdate'],
                'to_date' => $data['todate'],
                'user_id' => $data['user_id'],
                'type' => '1'
            ]);
            $viewData['message'] = 'Your request is been recorded. Please check your reports after sometime';
        } catch (Exception $ex) {
            $viewData = ['error' => $ex->getMessage()];
        }
        return redirect()->back()->with($viewData)->withInput();
//
    }


    public function getAccessToken($data) {
        $tokenData = $this->convertToString($data);
        $headers = [
            "cache-control: no-cache",
            "content-type: application/x-www-form-urlencoded;charset=utf-8",
        ];
        $response = CURLRequest::send(self::GET_ACCESS_TOKEN_URL, $headers, 'POST', $tokenData);
        $object = json_decode($response);
        if (!empty($object->access_token)) {
            return $object;
//            DB::table('api_accounts')->where('user_id', $data['user_id'])->update(array('access_token' => $object->access_token, 'refresh_token' => $object->refresh_token));
        } else {
            throw new Exception('Error occurred while fetching access token.');
        }
    }

    private function convertToString(array $data) {
        $returnString = '';
        $i = 0;
        foreach ($data as $key => $value) {
            if ($i !== 0) {
                $returnString .= '&';
            }
            $returnString .= "$key=$value";
            $i++;
        }
        return $returnString;
    }

    public function getAllReports() {
        $importDate = ExpenseImportDateLog::getLatestDateLog();
        if (isset($importDate->user_id)) {
            $newTokenStatus = ApiAccounts::getAccessTokenDetails($importDate->user_id);
            $accessToken = ApiAccounts::getAccessTokenDetails($importDate->user_id);
            DB::table('expense_import_date_log')->where('id', $importDate->id)->update(['import_status' => '1']);

            if (!empty($importDate->current_from_date && $newTokenStatus[0]['new_token_status'] != 1)) {
                $interval = date_diff(date_create($importDate->current_from_date), date_create($importDate->to_date));
                $interval = $interval->format('%a');
                $fromDateObj = new DateTime($importDate->current_from_date);
                $fromDateObj = $fromDateObj->add(new DateInterval('P1D'));
                $fromDate = $fromDateObj->format('Y-m-d');
                if ($interval > 10) {
                    $toDateObj = $fromDateObj->add(new DateInterval('P10D'));
                    $toDate = $toDateObj->format('Y-m-d');
                    DB::table('expense_import_date_log')->where('id', $importDate->id)->update(['current_from_date' => $toDate]);
                    DB::table('expense_import_date_log')->where('id', $importDate->id)->update(['import_status' => '0']);

                } else {
                    $toDate = $importDate->to_date;
                    DB::table('expense_import_date_log')->where('id', $importDate->id)->update(['import_status' => '2']);
                }
            } else {
                $fromDateObj = new DateTime($importDate->from_date);
                $fromDate = $fromDateObj->format('Y-m-d');
                $toDateObj = $fromDateObj->add(new DateInterval('P10D'));
                $toDate = $toDateObj->format('Y-m-d');
                DB::table('expense_import_date_log')->where('id', $importDate->id)->update(['current_from_date' => $toDate]);
                DB::table('expense_import_date_log')->where('id', $importDate->id)->update(['import_status' => '0']);

            }
            $url = self::GET_ALL_REPORTS_URL . '?user=ALL&limit=100&userDefinedDateAfter=' . $fromDate . '&userDefinedDateBefore=' . $toDate;

            $accessToken = $accessToken[0]['access_token'];
            $headers = [
                "authorization: Bearer $accessToken",
                "cache-control: no-cache"
            ];
            $reports = NULL;
            do {
                $response = CURLRequest::send(!empty($reports->NextPage) ? (string)$reports->NextPage : $url, $headers);
                $reports = new SimpleXMLElement($response);
                foreach ($reports->Items->Report as $report) {
                    $reportsID = $report->ID;
                    ConcurReportDetails::create([
                        'user_id' => $importDate->user_id,
                        'report_id' => $reportsID
                    ]);
                }
            } while (!empty($reports->NextPage));
        }
    }

    public function getAllExpenses() {
        $report = ConcurReportDetails::getLatestReportDetails();
        if (!empty($report)) {
            foreach ($report as $reportDetails) {

                $newTokenStatus = ApiAccounts::getAccessTokenDetails($reportDetails->user_id);
                if ($newTokenStatus[0]['new_token_status'] != 1) {

                    $accessToken = ApiAccounts::getAccessTokenDetails($reportDetails->user_id);
                    DB::table('concur_report_details')->where('id', $reportDetails->id)->update(['is_processed' => '1']);
                    $accessToken = $accessToken[0]['access_token'];
                    $headers = [
                        "authorization: Bearer $accessToken",
                        "cache-control: no-cache"
                    ];

                    $data = [];
                    $i = 0;
                    $currencies = Currency::getCurrencies();
                    $expenseList = Expenses::getExpenseList();
                    $countryList = CountryVat::getAllDistinctCountries();

                    $response = CURLRequest::send(self::GET_REPORT_DETAIL_URL . (string)$reportDetails->report_id, $headers);
                    $reportDetail = new SimpleXMLElement($response);

                    foreach ($reportDetail->ExpenseEntriesList->ExpenseEntry as $expense) {

                        if (strtoupper($expense->LocationCountry) != 'US' && strtoupper($expense->TransactionCurrencyCode) != 'USD') {
                            $vatExpense = Zoho::getVatPercent((string)$expense->LocationCountry, (string)$expense->TransactionCurrencyCode, (string)$expense->ExpenseTypeName, $countryList, $expenseList);
                            $expenseCategory = $vatExpense['expenseCategory'];
                            $vatPercent = $vatExpense['vatPercent'];
                            $vat = (((float)$expense->TransactionAmount) * $vatPercent) / (100 + $vatPercent);
                            $country = Zoho::getCountryName((string)$expense->LocationCountry);
                            $transactionCurrency = strtoupper((string)$expense->TransactionCurrencyCode);
                            $vat = number_format((float)$vat, 2, '.', '');
                            $expenseAmt = number_format((float)$expense->TransactionAmount, 2, '.', '');
                            $convertedVat = '';
                            $convertedExpenseAmt = '';
                            $exchangeRate = '';

//                if($expense->TransactionCurrencyCode != $baseCurrency) {
                            //Convert "Expense Amount" & "VAT" to base currency value
                            if (isset($currencies[$transactionCurrency])) {
                                $convertedVat = number_format(($vat / $currencies[$transactionCurrency]), 2, '.', '');
                                $convertedExpenseAmt = number_format(($expenseAmt / $currencies[$transactionCurrency]), 2, '.', '');
                                $exchangeRate = $currencies[$transactionCurrency];
                            }

                            $filePath = $this->getRecieptImage($expense->ReportEntryID, $accessToken);
                            $receiptUrl = '';
                            $receiptAvailable = 0;
                            if ($filePath) {
                                $img = explode("/", $filePath);
                                $receiptUrl = url(self::STORAGE . 'app/invoices/' . $img[count($img) - 1]);
                                $receiptAvailable = 1;
                            }
//                }


                            $data[] = [
                                'user_id' => $reportDetails->user_id,
                                'report_entry_id' => (string)$expense->ReportEntryID,
                                'receipt_available' => $receiptAvailable,
                                'entity' => (string)$expense->UserLoginID,
                                'transaction_date' => (string)$expense->TransactionDate,
                                'expense_type' => $expenseCategory,
                                'expense_entered_text' => (string)$expense->ExpenseTypeName,
                                'business_purpose' => (string)$expense->BusinessPurpose,
                                'vendor' => (string)$expense->VendorDescription,
                                'country_code' => (string)$expense->LocationCountry,
                                'city' => '',
                                'department' => '',
                                'expense_amount' => $expenseAmt,
                                'converted_expense_amount' => $convertedExpenseAmt,
                                'transaction_currency' => $transactionCurrency,
                                'vat' => $vat,
                                'converted_vat' => $convertedVat,
                                'currency_exchange_rate' => $exchangeRate,
                                'vat_percentage' => $vatPercent,
                                'country' => $country,
                                'invoice_status' => ($vat > 0 ? 'Claimable Vat' : ''),
                                'receipt_url' => $receiptUrl,
                            ];
                        }
                    }
                }
                DB::table('concur_report_details')->where('id', $reportDetails->id)->update(['is_processed' => '2']);
            }
            if (!empty($data)) {

                foreach ($data as $val) {
                    ConcurExpenseDetails::saveExpenseDetails($val);
                }
            }
        }
    }

    private function getRecieptImage($rep_id, $accessToken) {

        $headers = [
            "authorization: Bearer $accessToken",
            "cache-control: no-cache"
        ];
        $response = CURLRequest::send(self::GET_RECIEPT_IMAGE_URL . $rep_id, $headers);
        $report_detail = new SimpleXMLElement($response);
        $ch = curl_init((string)$report_detail->Url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
        $raw = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);
        $ext = '';
        if ($info['http_code'] === 200) {
            switch ($info['content_type']) {
                case 'image/jpeg':
                    $ext = '.jpg';
                    break;
                case 'image/png':
                    $ext = '.png';
                    break;
                case 'application/pdf':
                    $ext = '.pdf';
                    break;
            }
            $saveToPath = storage_path('app/invoices/') . $rep_id . $ext;
            return $saveToPath;
        }
        return NULL;
    }

    public function updateAccessToken() {
        $data = ApiAccounts::getRefreshAccessToken();
        if (!empty($data)) {
            foreach ($data as $val) {
                DB::table('api_accounts')->where('user_id', $val->user_id)->update(['new_token_status' => '1']);
                sleep(20);
                $valArray = get_object_vars($val);
                $valArray['grant_type'] = "refresh_token";
                $tokenData = $this->convertToString($valArray);
                $headers = [
                    "cache-control: no-cache",
                    "content-type: application/x-www-form-urlencoded;charset=utf-8",
                ];
                $response = CURLRequest::send(self::GET_ACCESS_TOKEN_URL, $headers, 'POST', $tokenData);
                $object = json_decode($response);
                if (!empty($object->access_token)) {
                    DB::table('api_accounts')->where('user_id', $valArray['user_id'])->update(array('access_token' => $object->access_token, 'refresh_token' => $object->refresh_token));
                    DB::table('api_accounts')->where('user_id', $val->user_id)->update(['new_token_status' => '0', 'ongoing_import' => '1']);
                }

            }
        }
    }


    public function saveImage() {
        $receiptImageDetails = ConcurExpenseDetails::getReceiptDetails();
        if (!empty($receiptImageDetails)) {
            $newTokenStatus = ApiAccounts::getAccessTokenDetails($receiptImageDetails->user_id);
            if ($newTokenStatus[0]['new_token_status'] != 1) {
                DB::table('concur_expense_details')->where('id', $receiptImageDetails->user_id)->update(['receipt_downloaded' => '1']);
                $accessToken = ApiAccounts::getAccessTokenDetails($receiptImageDetails->user_id);
                $accessToken = $accessToken[0]['access_token'];
                $headers = [
                    "authorization: Bearer $accessToken",
                    "cache-control: no-cache"
                ];
                $rep_id = $receiptImageDetails->report_entry_id;
                $response = CURLRequest::send(self::GET_RECIEPT_IMAGE_URL . $rep_id, $headers);
                $report_detail = new SimpleXMLElement($response);
                $ch = curl_init((string)$report_detail->Url);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
                $raw = curl_exec($ch);
                $info = curl_getinfo($ch);
                curl_close($ch);
                $ext = '';
                if ($info['http_code'] === 200) {
                    switch ($info['content_type']) {
                        case 'image/jpeg':
                            $ext = '.jpg';
                            break;
                        case 'image/png':
                            $ext = '.png';
                            break;
                        case 'application/pdf':
                            $ext = '.pdf';
                            break;
                    }
                    $saveToPath = storage_path('app/invoices/') . $rep_id . $ext;

                    $fp = fopen($saveToPath, 'w');
                    fwrite($fp, $raw);
                    fclose($fp);
                    DB::table('concur_expense_details')->where('id', $receiptImageDetails->id)->update(['receipt_downloaded' => '2']);
                }
            }
        }
    }

    public function uploadToZoho() {
        $zohoData = ConcurExpenseDetails::getZohoUploadData();
        foreach ($zohoData as $expense) {
            $companyData = UserProfile::getUserProfile($expense->user_id);
            $company = '';
            if (isset($companyData[0]->company_name)) {
                $company = $companyData[0]->company_name;
            }
            $id[] = $expense->id;

            $data[] = [
                'Entity' => (string)$expense->entity,
                'Transaction Date' => (string)$expense->transaction_date,
                'Expense Type' => '<![CDATA[' . rawurlencode(ucwords((string)$expense->expense_type)) . ']]>',
                'Expense Entered Text' => '<![CDATA[' . rawurlencode((string)$expense->expense_entered_text) . ']]>',
                'Business Purpose' => '<![CDATA[' . rawurlencode((string)$expense->business_purpose) . ']]>',
                'Vendor' => '<![CDATA[' . rawurlencode((string)$expense->vendor) . ']]>',
                'Country Code' => (string)$expense->country_code,
                'City' => '',
                'Department' => '',
                'Expense Amount' => $expense->expense_amount,
                'Converted Expense Amount' => $expense->converted_expense_amount,
                'Transaction Currency' => $expense->transaction_currency,
                'VAT' => $expense->vat,
                'Converted VAT' => $expense->converted_vat,
                'Currency Exchange Rate' => $expense->currency_exchange_rate,
                'VAT %25' => $expense->vat_percentage,
                'Invoice Number' => (string)$expense->report_entry_id,
                'Country' => $expense->country,
                'Company Name' => '<![CDATA[' . rawurlencode((string)$company) . ']]>',
                'Invoice Status' => $expense->invoice_status,
                'Receipt URL' => $expense->receipt_url
            ];

        }
// echo '<pre>';
//        print_r($id);exit;
        //Save Expenses
        $zoho = new Zoho();

        try {
            $response = $zoho->saveExpenses($data);
            if (!empty($response)) {
                DB::table('concur_expense_details')
                    ->whereIn('id', $id)
                    ->update(array('zoho_import' => 2));
            }
        } catch (Exception $ex) {
            if (!empty($response)) {
                DB::table('concur_expense_details')
                    ->whereIn('id', $id)
                    ->update(array('zoho_import' => 1));
                // Carry on
                // echo 'error aa gyi';
            }
        }
        // print_r($response);exit;

    }

    public function showDateLog() {

        $dateLog = ExpenseImportDateLog::showDateLog(array_values(session()->get('laravel_acl_sentry'))[0]);
        return View::make('concur-date-log')->with(['dateLog' => $dateLog]);
    }

    public
    function userConcurLog() {
        $concurLogList = ExpenseImportDateLog::getConcurLogList();
        return View::make('concur-log-list')->with(['concurLogList' => $concurLogList]);
    }

    public
    function getUserConcurLogDetails(Request $request) {
        $dateLog = ExpenseImportDateLog::showDateLog($request->id);
        return View::make('concur-date-log')->with(['dateLog' => $dateLog]);
    }

    public
    function searchUserConcurLog(Request $request) {
        $concurLogList = ExpenseImportDateLog::searchUserConcurLog($request->code);
        session()->flash('code', $request->code);
        return View::make('concur-log-list')->with(['concurLogList' => $concurLogList]);
    }

}

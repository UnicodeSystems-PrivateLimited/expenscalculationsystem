<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Zoho;
use App\Helpers\CURLRequest;
use Exception;
use App\Models\ApiAccounts;
use App\Models\Users;
use App\Models\Currency;
use App\Models\ExpenseImportDateLog;
use App\Models\Expenses;
use App\Models\CountryVat;
use View;
use Validator;

class ExpensifyController extends Controller
{

    const ENDPOINT = 'https://integrations.expensify.com/Integration-Server/ExpensifyIntegrations';
    const DEFAULT_START_DATE = '2017-01-01';

    private $partnerEmail;
    private $partnerUserID;
    private $partnerUserSecret;
    private $responseFileName;
    private $expenses;
    public $fromdate;
    public $todate;
    private $receiptURLs = [];
    private $postHeaders = [
        "cache-control: no-cache",
        "content-type: application/x-www-form-urlencoded",
        "Expect:"
    ];
    private $message;

    public function login() {
        $id = array_values(session()->get('laravel_acl_sentry'))[0];
        $userEmailAndPartnerId = ApiAccounts::getEmailAndPartnerId($id);
        if (empty(!$userEmailAndPartnerId)) {
            return View::make('expensify-login')->with(['userEmail' => $userEmailAndPartnerId[0]['username'], 'PartnerId' => $userEmailAndPartnerId[0]['client_id']]);
        } else {
            return View::make('expensify-login')->with(['userEmail' => NULL, 'PartnerId' => NULL]);
        }
    }

    public function loginAction(Request $request) {
        try {
            $data = $request->all();
            $this->fromdate = $request->fromdate;
            $this->todate = $request->todate;

            $validator = Validator::make($data, [
                'partner_email' => 'required',
                'partner_user_id' => 'required',
                'partner_user_secret' => 'required',
                'fromdate' => 'required',
                'todate' => 'required|after:' . $this->fromdate
            ], [
                'after' => 'The To date must be a date after From date.'
            ]);
            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
            }
            $this->partnerEmail = $data['partner_email'];
            $this->partnerUserID = $data['partner_user_id'];
            $this->partnerUserSecret = $data['partner_user_secret'];
            $id = array_values(session()->get('laravel_acl_sentry'))[0];
            $userExists = ApiAccounts::checkIfEmailExists($this->partnerEmail, $id);
            if ($userExists) {
                throw new Exception('Email already used.');
            }
            //      Check Expense Import Date Log
            $saveExpense = ExpenseImportDateLog::checkIfExists($data['fromdate'], $data['todate'], $id);
            if ($saveExpense) {
                throw new Exception('Import is already done for given Date Range');
            }
            $this->exportExpenses();
            $res['message'] = $this->getMessage();
        } catch (Exception $ex) {
            $res['error'] = $ex->getMessage();
        }
        return redirect()->back()->with($res)->withInput();
    }

    private function getRequestJobDescription() {
        return [
            'type' => 'file',
            'credentials' => [
                'partnerUserID' => $this->partnerUserID,
                'partnerUserSecret' => $this->partnerUserSecret
            ],
            'onReceive' => [
                'immediateResponse' => ['returnRandomFileName']
            ],
            'inputSettings' => [
                'type' => 'combinedReportData',
                'filters' => [
                    'startDate' => $this->fromdate,
                    'endDate' => $this->todate,
                ]
            ],
            'outputSettings' => [
                'fileExtension' => 'json'
            ]
        ];
    }

    private function getDownloadRequestJobDescription() {
        return [
            'type' => 'download',
            'credentials' => [
                'partnerUserID' => $this->partnerUserID,
                'partnerUserSecret' => $this->partnerUserSecret
            ],
            'fileName' => $this->responseFileName
        ];
    }

    private function exportExpenses() {
        $requestJobDescription = $this->getRequestJobDescription();
        $template = file_get_contents(resource_path('expensifytemplates/expensify_reports_template.ftl'));
        $postData = 'requestJobDescription=' . json_encode($requestJobDescription) . '&template=' . urlencode($template);
        $response = CURLRequest::send(self::ENDPOINT, $this->postHeaders, 'POST', $postData);
        if (!empty($response) && !json_decode($response) && strpos($response, '.json') !== FALSE) {
            $this->responseFileName = $response;
        } else {
            throw new Exception('Error occured while fetching report data.');
        }
        $this->downloadResponseData();
    }

    private function downloadResponseData() {
        $postData = 'requestJobDescription=' . json_encode($this->getDownloadRequestJobDescription());
        $response = CURLRequest::send(self::ENDPOINT, $this->postHeaders, 'POST', $postData);
        $response = json_decode($response);
        if (empty($response)) {
            throw new Exception('No new reports/expenses found.');
        }
        if (isset($response->responseCode) && $response->responseCode != 200) {
            throw new Exception('Error while response download' . (!empty($response->responseMessage) ? ": $response->responseMessage" : ''));
        }
//        print_r($response); exit;
        $this->expenses = $response;
        $this->postDataToZoho();
    }

    private function postDataToZoho() {
        $data = [];
        $i = 0;
        $baseCurrency = Currency::getBaseCurrency();
        $currencies = Currency::getCurrencies();
        $expenseList = Expenses::getExpenseList();
        $countryList = CountryVat::getAllDistinctCountries();
        $id = array_values(session()->get('laravel_acl_sentry'))[0];
        $getUserDetails = Users::getUserDetails($id);
        $company = $getUserDetails[0]->company_name;

        foreach ($this->expenses as $expense) {
            if (strtoupper($expense->currency) != 'USD') {
                $vatExpense = Zoho::getVatPercent(NULL, $expense->currency, $expense->expenseType, $countryList, $expenseList);
                $expenseCategory = $vatExpense['expenseCategory'];
                $vatPercent = $vatExpense['vatPercent'];
                $transactionCurrency = strtoupper((string)$expense->currency);
                $convertedVat = '';
                $convertedExpenseAmt = '';
                $exchangeRate = '';
                if ($expense->modifiedAmount) {
                    $expenseAmt = ((float)$expense->modifiedAmount) / 100;
                    $expenseAmt = number_format((float)$expenseAmt, 2, '.', '');
                } else {
                    $expenseAmt = ((float)$expense->amount) / 100;
                    $expenseAmt = number_format((float)$expenseAmt, 2, '.', '');
                }
                $vat = ($expenseAmt * $vatPercent) / (100 + $vatPercent);
                $vat = number_format((float)$vat, 2, '.', '');


//            if($expense->currency != $baseCurrency) {
                //Convert "Expense Amount" & "VAT" to base currency value
                if (isset($currencies[$transactionCurrency])) {
                    $convertedVat = number_format(($vat / $currencies[$transactionCurrency]), 2, '.', '');
                    $convertedExpenseAmt = number_format(($expenseAmt / $currencies[$transactionCurrency]), 2, '.', '');
                    $exchangeRate = $currencies[$transactionCurrency];
                }
//            }

                $data[] = [
                    'Entity' => $expense->entity,
                    'Transaction Date' => $expense->transactionDate,
                    'Expense Type' => (ucwords($expenseCategory)),
                    'Expense Entered Text' => '<![CDATA[' . rawurlencode((string)$expense->expenseType) . ']]>',
                    'Business Purpose' => '<![CDATA[' . rawurlencode((string)$expense->businessPurpose) . ']]>',
                    'Vendor' => '',
                    'Country Code' => '',
                    'City' => '',
                    'Department' => '',
                    'Expense Amount' => $expenseAmt,
                    'Converted Expense Amount' => $convertedExpenseAmt,
                    'Transaction Currency' => $transactionCurrency,
                    'VAT' => $vat,
                    'Converted VAT' => $convertedVat,
                    'Currency Exchange Rate' => $exchangeRate,
                    'Company Name' => '<![CDATA[' . rawurlencode((string)$company) . ']]>',
                    'Invoice Number' => $expense->invoiceNumber,
                    'VAT %25' => $vatPercent,
                    'Invoice Status' => ($vat > 0 ? 'Claimable Vat' : ''),
                    'Receipt URL' => $expense->receiptURL
                ];
                if ($expense->receiptURL) {
                    $this->receiptURLs[$i] = $expense->receiptURL;
                }
                $i++;
            }
        }
//        print_r($data); exit;
        $zoho = new Zoho();
        $chunks = array_chunk($data, 100);

        //Save Expenses
        $expenseResponse[] = '';

        foreach ($chunks as $chunk) {
            try {
                $response = $zoho->saveExpenses($chunk);
                $expenseResponse[] = $response;
            } catch (Exception $ex) {
                // Carry on
            }
        }
        $id = array_values(session()->get('laravel_acl_sentry'))[0];

        // Save the user
        $user = ApiAccounts::saveUser([
            'username' => $this->partnerEmail,
            'client_id' => $this->partnerUserID,
            'user_id' => $id,
            'type' => 2,
            'last_retrieved_at' => date('Y-m-d H:i:s')
        ]);
//        $this->getOwnerEmail();
        //save user in zoho
        if (!$user) {
            $zoho->saveClient(array(array('Client Email' => $this->partnerEmail)));
        }

        if (isset($expenseResponse[1]->message)) {
            $this->message = (string)$expenseResponse[1]->message;
            $id = array_values(session()->get('laravel_acl_sentry'))[0];
            ExpenseImportDateLog::create([
                'from_date' => $this->fromdate,
                'to_date' => $this->todate,
                'user_id' => $id
            ]);
        } else {
            $this->message = 'No new reports/expenses found.';
        }

//        $this->attachFile($expenseResponse);
    }

    private function attachFile($response) {
        $attachFileError = FALSE;
        foreach ($this->receiptURLs as $key => $url) {
//            $index1 = floor($key / 100);
//            $index2 = $key % 100;
//            $zohoRecordID = (string)$response[$index1]->recorddetail[$index2]->FL[0];
            try {
                $explode = explode('/', $url);
                $saveToPath = storage_path('app/invoices') . $explode[count($explode) - 1];
                $filePath = $this->saveImage($url, $saveToPath);
//                $zoho = new Zoho();
//                $zoho->attachFileToExpense($zohoRecordID, $filePath);
            } catch (Exception $ex) {
                $attachFileError = TRUE;
            }
        }
        if ($attachFileError) {
            $this->message = 'Some or all of receipts could not be attached to expenses.';
        } else {
            if (isset($response[1]->message)) {
                $this->message = (string)$response[1]->message;
                $id = array_values(session()->get('laravel_acl_sentry'))[0];
                ExpenseImportDateLog::create([
                    'from_date' => $this->fromdate,
                    'to_date' => $this->todate,
                    'user_id' => $id
                ]);
            } else {
                $this->message = 'No new reports/expenses found.';
            }
        }
    }

    private function saveImage($url, $saveToPath) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
        $raw = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);
        if ($info['http_code'] !== 200) {
            throw new Exception('File couldn\'t be downloaded.');
        }
        $fp = fopen($saveToPath, 'w');
        fwrite($fp, $raw);
        fclose($fp);
        return $saveToPath;
    }

    private function getMessage() {
        return $this->message;
    }

    public function showDateLog() {

        $dateLog = ExpenseImportDateLog::showDateLog(array_values(session()->get('laravel_acl_sentry'))[0]);
        return View::make('expensify-date-log')->with(['dateLog' => $dateLog]);
    }

    public function userExpensifyLog() {
        $concurLogList = ExpenseImportDateLog::getConcurLogList();
        return View::make('expensify-log-list')->with(['concurLogList' => $concurLogList]);
    }

    public function getUserExpensifyLogDetails(Request $request) {
        $dateLog = ExpenseImportDateLog::showDateLog($request->id);
        return View::make('expensify-date-log')->with(['dateLog' => $dateLog]);
    }

    private function getOwnerEmailJobDescription() {
        return [
            'type' => 'get',
            'credentials' => [
                'partnerUserID' => $this->partnerUserID,
                'partnerUserSecret' => $this->partnerUserSecret
            ],
            'inputSettings' => [
                "type" => "policyList",
                "adminOnly" => true,
                "userEmail" => $this->partnerEmail
            ],
        ];
    }

    public function getOwnerEmail() {
        $requestJobDescription = $this->getOwnerEmailJobDescription();
        $postData = 'requestJobDescription=' . json_encode($requestJobDescription);
        $response = json_decode(CURLRequest::send(self::ENDPOINT, $this->postHeaders, 'POST', $postData));
        if ($response->policyList[0]->role == 'admin') {
            $data = ['is_admin' => 1, 'company_name' => $response->policyList[0]->name];
            ApiAccounts::where('username', $this->partnerEmail)->update($data);
        }
    }

}
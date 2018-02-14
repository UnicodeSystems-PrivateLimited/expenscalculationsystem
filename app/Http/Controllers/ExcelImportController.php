<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Zoho;
use Exception;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\ApiAccounts;
use App\Models\Expenses;
use App\Models\CountryVat;
use App\Models\Currency;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Shared_Date;
use View;

class ExcelImportController extends Controller
{

    const GOOGLE_API_KEY = 'AIzaSyCVxV7lcSk2oqjkjHLjh2mtNT4sNpYb_SQ';

    public function import() {
        return view('excel-import');
    }

    public function importAction(Request $request) {
        try {
            ini_set('memory_limit', '-1');
            if (empty($request->file('file'))) {
                throw new Exception('Please select an excel file.');
            } else {
                $excelFile = $request->file('file');
                $extension = $excelFile->getClientOriginalExtension();
                $allowedExtensions = array('csv', 'xlsx', 'xls');
                $result = [];
                $items = [];
                $i = 0;
                $attachments = [];
                $expenseResponse = [];
                $incorrectDates = [];

                if (in_array($extension, $allowedExtensions)) {
                    $inputFileType = PHPExcel_IOFactory::identify($excelFile);
                    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                    $objPHPExcel = $objReader->load($excelFile);

                    $sheet = $objPHPExcel->getSheet(0);
                    $highestRow = $sheet->getHighestRow();
                    $highestColumn = $sheet->getHighestColumn();

                    if ($highestRow <= 15001) {
                        $baseCurrency = Currency::getBaseCurrency();
                        $currencies = Currency::getCurrencies();
                        $expenseList = Expenses::getExpenseList();
                        $countryList = CountryVat::getAllDistinctCountries();
                        $records = [];

                        for ($row = 1; $row <= $highestRow; $row++) {
                            if ($row > 1) {
                                $rowData = array(
                                    $sheet->getCell('A' . $row)->getValue(),
                                    $sheet->getCell('B' . $row)->getValue(),
                                    $sheet->getCell('C' . $row)->getValue(),
                                    $sheet->getCell('D' . $row)->getValue(),
                                    $sheet->getCell('E' . $row)->getValue(),
                                    $sheet->getCell('F' . $row)->getValue(),
                                    $sheet->getCell('G' . $row)->getValue(),
                                    $sheet->getCell('H' . $row)->getValue(),
                                    $sheet->getCell('I' . $row)->getValue(),
                                    $sheet->getCell('J' . $row)->getValue(),
                                    $sheet->getCell('K' . $row)->getValue(),
                                    $sheet->getCell('L' . $row)->getValue(),
                                    $sheet->getCell('M' . $row)->getValue(),
                                    $sheet->getCell('N' . $row)->getValue(),
                                    $sheet->getCell('O' . $row)->getValue(),
                                    $sheet->getCell('P' . $row)->getValue(),
                                    $sheet->getCell('Q' . $row)->getValue(),
                                    $sheet->getCell('R' . $row)->getValue(),
                                    $sheet->getCell('S' . $row)->getValue(),
                                    $sheet->getCell('T' . $row)->getValue()
                                );
                                $rowData = array_combine($header, $rowData);
                                $rowData = array_map('trim', $rowData);
                                if (PHPExcel_Shared_Date::isDateTime($objPHPExcel->getActiveSheet()->getCell('B' . $row))) {
                                    $transactionDate = PHPExcel_Shared_Date::ExcelToPHP($sheet->getCell('B' . $row)->getValue());
                                    if ($transactionDate < 0) {
                                        array_push($incorrectDates, $row);
                                    }
                                } else {
                                    $transactionDate = $rowData['Transaction Date'] instanceof DateTime ? $rowData['Transaction Date']->getTimestamp() : strtotime($rowData['Transaction Date']);
                                }
                                $rowData['Transaction Date'] = date('Y-m-d', $transactionDate);
                                $email[] = $rowData['Entity'];

                                //If city name is give and currency is USD and Country code is empty
                                if (empty ($rowData['Country Code']) && (strtoupper($rowData['Transaction Currency']) == "USD") && !empty ($rowData['City'])) {
                                    if (!in_array($rowData['City'], $records)) {
                                        $cityData = json_decode(file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($rowData['City']) . '&key=' . self::GOOGLE_API_KEY));
                                        $cityData = $cityData->results[0]->address_components;
                                        $countryCode = $cityData[(count($cityData)) - 1]->short_name;
                                        $records[$countryCode] = $rowData['City'];
                                        $rowData['Country Code'] = $countryCode;
                                    } else {
                                        $countryCode = array_search($rowData['City'], $records);
                                        $rowData['Country Code'] = $countryCode;
                                    }
                                }

                                $invoiceStatusType = 0;
                                $country = !empty($rowData['Country Code']) ? Zoho::getCountryName($rowData['Country Code']) : NULL;

                                //VAT Calculation
                                if (strtoupper($rowData['Country Code']) == strtoupper($rowData['Base Country Code']) && strtoupper($rowData['Base Country Code']) == "US") {
                                    //No Need to calculate VAT
                                    $vatPercent = '';
                                    $vat = $rowData['VAT'];
                                    $expenseCategory = Zoho::getExpenseTypeCategory($rowData['Expense Type']);

                                } else {
                                    //Calculate VAT
                                    if ($rowData['VAT'] != '') {
                                        $vatPercent = '';
                                        $vat = $rowData['VAT'];
                                        $expenseCategory = Zoho::getExpenseTypeCategory($rowData['Expense Type']);
                                    } else {
                                        if (empty ($rowData['Country Code']) && empty($rowData['Base Country Code']) && empty ($rowData['City']) && (strtoupper($rowData['Transaction Currency']) == "USD")) {
                                            $vatPercent = 15;
                                            $vat = (((float)$rowData['Expense Amount']) * $vatPercent) / (100 + $vatPercent);
                                            $invoiceStatusType = 1;
                                        } else {
                                            $vatExpense = Zoho::getVatPercent(!empty($rowData['Country Code']) ? trim($rowData['Country Code']) : NULL, !empty($rowData['Transaction Currency']) ? trim($rowData['Transaction Currency']) : NULL, !empty($rowData['Expense Type']) ? trim($rowData['Expense Type']) : NULL, $countryList, $expenseList);
                                            $expenseCategory = $vatExpense['expenseCategory'];
                                            $vatPercent = $vatExpense['vatPercent'];
                                            $vat = (((float)$rowData['Expense Amount']) * $vatPercent) / (100 + $vatPercent);
                                        }
                                    }
                                }

                                $invoiceStatus = ($vat > 0 ? ($invoiceStatusType > 0 ? 'Vat Undefined' : 'Claimable Vat') : $rowData['Invoice Status']);
                                $transactionCurrency = !empty($rowData['Transaction Currency']) ? strtoupper(trim($rowData['Transaction Currency'])) : NULL;
                                $vat = number_format((float)$vat, 2, '.', '');
                                $expenseAmt = number_format((float)$rowData['Expense Amount'], 2, '.', '');
                                $convertedVat = '';
                                $convertedExpenseAmt = '';
                                $exchangeRate = '';

//                                if(!empty($rowData['Transaction Currency']) && ($rowData['Transaction Currency'] != $baseCurrency)) {
                                //Convert "Expense Amount" & "VAT" to base currency value
                                if (isset($currencies[$transactionCurrency])) {
                                    $convertedVat = number_format(($vat / $currencies[$transactionCurrency]), 2, '.', '');
                                    $convertedExpenseAmt = number_format(($expenseAmt / $currencies[$transactionCurrency]), 2, '.', '');
                                    $exchangeRate = $currencies[$transactionCurrency];
                                }
//                                } 

                                //Zoho Expense Data Array
                                $items[] = array(
                                    'Entity' => $rowData['Entity'],
                                    'Transaction Date' => $rowData['Transaction Date'],
                                    'City' => '<![CDATA[' . rawurlencode($rowData['City']) . ']]>',
                                    'Base Country Code' => $rowData['Base Country Code'],
                                    'Country Code' => $rowData['Country Code'],
                                    'Country' => $country,
                                    'Department' => '<![CDATA[' . rawurlencode($rowData['Department']) . ']]>',
                                    'Vendor' => '<![CDATA[' . rawurlencode($rowData['Vendor']) . ']]>',
                                    'Business Purpose' => '<![CDATA[' . rawurlencode($rowData['Business Purpose']) . ']]>',
                                    'Expense Type' => '<![CDATA[' . rawurlencode(ucwords($expenseCategory)) . ']]>',
                                    'Expense Entered Text' => '<![CDATA[' . rawurlencode($rowData['Expense Type']) . ']]>',
                                    'Transaction Currency' => $transactionCurrency,
                                    'Invoice Number' => $rowData['Invoice Number'],
                                    'Claim Number' => $rowData['Claim Number'],
                                    'VAT' => $vat,
                                    'Converted VAT' => $convertedVat,
                                    'Expense Amount' => $expenseAmt,
                                    'Converted Expense Amount' => $convertedExpenseAmt,
                                    'Currency Exchange Rate' => $exchangeRate,
                                    'VAT %25' => $vatPercent,
                                    'Report ID' => $rowData['Report ID'],
                                    'Payment Type' => $rowData['Payment Type'],
                                    'Employee' => $rowData['Employee'],
                                    'Company Name' => $rowData['Company Name'],
                                    'Invoice Status' => $invoiceStatus,
                                    'Receipt URL' => $rowData['Receipt']
                                );

                                //Data for attachments
                                if ($rowData['Receipt'] != '') {
                                    $res = $this->saveImage($rowData['Receipt']);
                                    if ($res) {
                                        $attachments[$i] = $res;
                                    }
                                }
                                $i++;
                            } else {
                                $header = array(
                                    $sheet->getCell('A' . $row)->getValue(),
                                    $sheet->getCell('B' . $row)->getValue(),
                                    $sheet->getCell('C' . $row)->getValue(),
                                    $sheet->getCell('D' . $row)->getValue(),
                                    $sheet->getCell('E' . $row)->getValue(),
                                    $sheet->getCell('F' . $row)->getValue(),
                                    $sheet->getCell('G' . $row)->getValue(),
                                    $sheet->getCell('H' . $row)->getValue(),
                                    $sheet->getCell('I' . $row)->getValue(),
                                    $sheet->getCell('J' . $row)->getValue(),
                                    $sheet->getCell('K' . $row)->getValue(),
                                    $sheet->getCell('L' . $row)->getValue(),
                                    $sheet->getCell('M' . $row)->getValue(),
                                    $sheet->getCell('N' . $row)->getValue(),
                                    $sheet->getCell('O' . $row)->getValue(),
                                    $sheet->getCell('P' . $row)->getValue(),
                                    $sheet->getCell('Q' . $row)->getValue(),
                                    $sheet->getCell('R' . $row)->getValue(),
                                    $sheet->getCell('S' . $row)->getValue(),
                                    $sheet->getCell('T' . $row)->getValue()
                                );
                                $header = array_map('trim', $header);
                            }
                        }
                    } else {
                        throw new Exception('Please Select Excel File with 15000 or less data.');
                    }

                    //check if incorrect dates exists
                    if (count($incorrectDates) > 0) {
                        return View::make('excel-import-error')->with(['incorrectData' => $incorrectDates]);
                    }
//                    echo "<pre>";
//                    print_r($items);
//                    exit;
                    $email = array_unique(($email), SORT_REGULAR);
                    $data = ApiAccounts::checkIfExistsForExcel($email);
                    $data = array_column($data, 'username');
                    $diff = array_diff($email, $data);
                    $zoho = new Zoho();

                    //save user in db and zoho
                    foreach ($diff as $diff) {
                        if ($diff != '') {
                            $response = $zoho->saveClient(array(array('Client Email' => $diff)));
                            ApiAccounts::saveUserForExcel(['username' => $diff]);
                        }
                    }

                    $chunks = array_chunk($items, 100);

                    //Save Expenses
                    foreach ($chunks as $chunk) {
                        try {
                            $response = $zoho->saveExpenses($chunk);
                            $expenseResponse[] = $response;
                        } catch (Exception $ex) {
                            // Carry on
                        }
                    }

                    //Save attachments
                    $attachFileError = FALSE;
                    if (!empty($expenseResponse) && !empty($attachments)) {
                        foreach ($attachments as $key => $val) {
                            $index1 = floor($key / 100);
                            $index2 = $key % 100;

                            $expenseId = (string)$expenseResponse[$index1]->recorddetail[$index2]->FL[0];
                            if ($val) {
                                try {
                                    $zoho->attachFileToExpense($expenseId, $val);
                                } catch (Exception $ex) {
                                    $attachFileError = true;
                                }
                            }
                        }
                    }

                    if ($attachFileError) {
                        $viewData = ['message' => 'Expense insertion complete, but some or all of the attachments could not be attached successfully.'];
                    } else {
                        $viewData = ['message' => 'Expense insertion to Zoho completed successfully.'];
                    }
//                    }
                    //---------------------------------------------
                } else {
                    throw new Exception('Please Select valid Excel File.');
                }
            }
        } catch (Exception $ex) {
            $viewData = ['error' => $ex->getMessage()];
        }
        return redirect()->back()->with($viewData);
    }

    private function saveImage($url) {
        $explode = explode('/', $url);
        $saveToPath = storage_path('app/invoices') . $explode[count($explode) - 1];

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

}

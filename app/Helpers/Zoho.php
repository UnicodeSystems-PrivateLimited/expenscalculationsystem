<?php

namespace App\Helpers;

use App\Helpers\CURLRequest;
use SimpleXMLElement;
use Exception;
use CURLFile;

class Zoho {

    private $authToken = '4a12a5a5e76981672aae6aa79241aa34';
    const CLIENT_MODULE = 'CustomModule1';
    const REPORT_MODULE = 'CustomModule2';
    const EXPENSE_MODULE = 'CustomModule3';
    const USERNAME = 'david@globalvatax.com';
    const PASSWORD = 'Zulick1836!';
    const GET_AUTH_URL = 'https://accounts.zoho.com/apiauthtoken/nb/create';

    private static $vatDataAccToCurrency;
    private static $vatDataAccToCountry;
    private static $CountryAccToCountryCode;

    public function getAuth() {
        $params = "SCOPE=ZohoCRM/crmapi&EMAIL_ID=" . self::USERNAME . "&PASSWORD=" . self::PASSWORD;
        $result = CURLRequest::send(self::GET_AUTH_URL, [], 'POST', $params);
        /* This part of the code below will separate the Authtoken from the result.
          Remove this part if you just need only the result */
        $anArray = explode("\n", $result);
        $authToken = explode("=", $anArray['2']);
        $cmp = strcmp($authToken[0], "AUTHTOKEN");
        if ($cmp == 0) {
            $this->authToken = $authToken[1];
        } else {
            throw new Exception('Error occured while fetching Zoho\'s access token');
        }
    }

    private function xmlMaker($data) {
        $rowNo = 1;
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<Clients>';
        foreach ($data as $row) {
            $xml .= '<row no="' . $rowNo++ . '">';
            foreach ($row as $key => $value) {
                $xml .= '<FL val="' . $key . '">' . $value . '</FL>';
            }
            $xml .= '</row>';
        }
        $xml .= '</Clients>';
        return $xml;
       // print_r($xml);exit;
    }

    public function saveExpenseReports($reports) {
        $xml = $this->xmlMaker($reports);
        $query = "authtoken=" . $this->authToken . "&scope=crmapi&newFormat=1&xmlData=" . $xml;
        $response = CURLRequest::send(self::getSaveReportsURL(), [], 'POST', $query);
        $response = new SimpleXMLElement($response);
        if (isset($response->error)) {
            throw new Exception('Error while report saving: ' . (string) $response->error->message);
        }
        return $response->result;
    }

    public function saveExpenses($expenses) {
        $xml = $this->xmlMaker($expenses);
        $query = "authtoken=" . $this->authToken . "&scope=crmapi&newFormat=1&xmlData=" . $xml;
        $response = CURLRequest::send(self::getSaveExpensesURL(), [], 'POST', $query);
        $response = new SimpleXMLElement($response);
        if (isset($response->error)) {
            file_put_contents(storage_path('logs/expense_save.log'), 'Error with below XML with message: ' . (string) $response->error->message . PHP_EOL . $xml . PHP_EOL, FILE_APPEND);
            throw new Exception('Error while expense saving: ' . (string) $response->error->message);
        }
        return $response->result;
    }


    public function saveClient($clients)
    {   
        $xml = $this->xmlMaker($clients);
        $query = "authtoken=" . $this->authToken . "&scope=crmapi&duplicateCheck=2&version=4&newFormat=1&xmlData=" . $xml; 
        $response = CURLRequest::send(self::getSaveClientsURL(), [], 'POST', $query);
        $response = new SimpleXMLElement($response);
        if (isset($response->error)) {
            file_put_contents(storage_path('logs/expense_save.log'), 'Error with below XML with message: ' . (string) $response->error->message . PHP_EOL . $xml . PHP_EOL, FILE_APPEND);
            throw new Exception('Error while client saving: ' . (string) $response->error->message);
        }
        return $response->result;
    }

    public function attachFileToExpense($id, $filePath) {
        $data = [
            'authtoken' => $this->authToken,
            'scope' => 'crmapi',
            'id' => $id,
            'content' => new CURLFile($filePath)
        ];
        $response = CURLRequest::send(self::getUploadFileURL(), [], 'POST', $data);
        $response = new SimpleXMLElement($response);
        // unlink($filePath); // Delete the file after use.
        if (isset($response->error)) {
            throw new Exception('Error while Attaching file to expense: ' . (string) $response->error->message);
        }
        return $response->result;
    }

    private static function getSaveReportsURL() {
        return 'https://crm.zoho.com/crm/private/xml/' . self::REPORT_MODULE . '/insertRecords';
    }

    private static function getSaveExpensesURL() {
        return 'https://crm.zoho.com/crm/private/xml/' . self::EXPENSE_MODULE . '/insertRecords';
    }
    private static function getSaveClientsURL() {
        return 'https://crm.zoho.com/crm/private/xml/'. self::CLIENT_MODULE . '/insertRecords';
    }

    private static function getUploadFileURL() {
        return 'https://crm.zoho.com/crm/private/xml/' . self::EXPENSE_MODULE . '/uploadFile';
    }

    public static function getVatPercent($country, $currency, $expenseType, $countries, $expenseTypes) {
        //Convert all parameter in lowercase
        $country = strtolower($country);  $currency = strtolower($currency);  $expenseType = strtolower($expenseType);
        
        //Arrays containg Country list & Expense Type list
//        $countries = array('au', 'at', 'be', 'ba', 'ca', 'dk', 'fi', 'fr', 'mc', 'de', 'is', 'ie', 'jp', 'lv', 'lu', 'mk', 'mt', 'me', 'nl', 'nz', 'no', 'pt', 'kr', 'es', 'se', 'ch', 'uk');
//        $expenseTypes = array('hotel', 'airfare', 'tips', 'parking', 'tolls', 'train', 'fuel', 'diesel', 'car', 'taxi', 'goods', 'meal', 'venue', 'entertainment', 'office', 'supplies', 'printing', 'software', 'computer', 'furniture', 'utilities', 'Shipping', 'internet', 'mobile', 'advertising', 'marketing', 'promotional', 'tradeshow', 'conference', 'event', 'teambuilding', 'training', 'seminar', 'legal', 'consultant', 'accounting', 'dues', 'subscription', 'storage', 'fee', 'miscellaneous', 'other');
        
        //Check if given Expense Type exists then return an array 
        //that contains matched expense from $expenseTypes array
        $matchExpense = array_filter($expenseTypes, array(new class($expenseType){
                private $eType;

                function __construct($expenseType) {
                        $this->eType = $expenseType;
                }

                function checkIfExists($et) {
                    $pos = strpos($this->eType, $et);
                    if($pos !== FALSE) {
                        return $pos + 1;
                    }
                }
        }, 'checkIfExists'));

        //reindex the array
        $matchExpense = array_values($matchExpense);
        
        //Set Expense Type
        $expType = (count($matchExpense)>0) ? $matchExpense[0] : 'other';

        //Get currency_expenseType and country_expenseType VAT Rate list
        $vatDataAccToCurrency = self::getVatDataAccToCurrency();
        $vatDataAccToCountry = self::getVatDataAccToCountry();
        if (!empty($country) && in_array($country, $countries)) {
            $attr = $country . '_' . $expType;
            if (isset($vatDataAccToCountry->$attr)) {
                $vatpercent = $vatDataAccToCountry->$attr;
            } else {
                $vatpercent = $vatDataAccToCountry->{$country . '_other'};
            }
        } else if (!empty($currency)) {
            $attr = $currency . '_' . $expType;
            if (isset($vatDataAccToCurrency->$attr)) {
                $vatpercent = $vatDataAccToCurrency->$attr;
            } else {
                $vatpercent = isset($vatDataAccToCurrency->{$currency . '_other'}) ? $vatDataAccToCurrency->{$currency . '_other'} : 0;
            }
        } else {
            $vatpercent = 0;
        }
        
        $vatAndExpense = ['vatPercent' => $vatpercent, 'expenseCategory' => $expType];
        return $vatAndExpense;
    }

    public static function getVatDataAccToCurrency() {
        if (empty(self::$vatDataAccToCurrency)) {
            self::$vatDataAccToCurrency = json_decode(file_get_contents(resource_path('json/vat_data_acc_to_currency.json')));
        }
        return self::$vatDataAccToCurrency;
    }
    
    public static function getVatDataAccToCountry() {
        if (empty(self::$vatDataAccToCountry)) {
            self::$vatDataAccToCountry = json_decode(file_get_contents(resource_path('json/vat_data_acc_to_country.json')));
        }
        return self::$vatDataAccToCountry;
        
    }
    public static function getCountryName($countryName) {
        $CountryAccToCountryCode = self::getCountryAccToCountryCode();
        if (isset($CountryAccToCountryCode->$countryName)) {
            return $CountryAccToCountryCode->$countryName;
        } 
    }

     public static function getCountryAccToCountryCode() {
        if (empty(self::$CountryAccToCountryCode)) {
            self::$CountryAccToCountryCode = json_decode(file_get_contents(resource_path('json/countries.json')));
        }
        return self::$CountryAccToCountryCode;
    }
    
    public static function getExpenseTypeCategory($expenseType) {
        //Convert all parameter in lowercase
        $expenseType = strtolower($expenseType);

        //Arrays containg Country list & Expense Type list
        $expenseTypes = array('hotel', 'airfare', 'tips', 'parking', 'tolls', 'train', 'fuel', 'diesel', 'car', 'taxi', 'goods', 'meal', 'venue', 'entertainment', 'office', 'supplies', 'printing', 'software', 'computer', 'furniture', 'utilities', 'Shipping', 'internet', 'mobile', 'advertising', 'marketing', 'promotional', 'tradeshow', 'conference', 'event', 'teambuilding', 'training', 'seminar', 'legal', 'consultant', 'accounting', 'dues', 'subscription', 'storage', 'fee', 'miscellaneous', 'other');

        //Check if given Expense Type exists then return an array 
        //that contains matched expense from $expenseTypes array
        $matchExpense = array_filter($expenseTypes, array(new class($expenseType) {

                private $eType;

                function __construct($expenseType) {
                    $this->eType = $expenseType;
                }

                function checkIfExists($et) {
                    $pos = strpos($this->eType, $et);
                    if ($pos !== FALSE) {
                        return $pos + 1;
                    }
                }
            }, 'checkIfExists'));

        //reindex the array
        $matchExpense = array_values($matchExpense);

        //Set Expense Type
        $expType = (count($matchExpense) > 0) ? $matchExpense[0] : 'other';
        
        return $expType;
    }

}
 
<?php

namespace App\Http\Controllers;

use App\Models\UserProfile;
use Illuminate\Http\Request;
use App\Helpers\ReportClient;
use App\Models\Users;
use App\Helpers\Zoho;
use App\Models\ApiAccounts;
use View;

class ReportLoginController extends Controller
{

    private $emailId = Zoho::USERNAME;

    const AUTHTOKEN = '9ba3c47a6715889a112f04ba7262398a';
    const SUBMISSION_ANALYSIS_REPORT_TABLE = 'Submission Analysis Report Dashboard';
    const VAT_POTENTIAL_ANALYSIS_REPORT_TABLE = 'Vat Potential Analysis Reports Dashboard';
    const DB_NAME = 'Global VatTax';

    public function reportDashboard(Request $request) {

        if (isset($request->id)) {
            $client = Users::getClientemail($request->id);
            return View::make('report-dashboard')->with(['id' => $request->id])->with(['email' => $client[0]->email, 'first_name' => $client[0]->first_name, 'last_name' => $client[0]->last_name, 'company_name' => $client[0]->company_name]);
        } else {
            $client = Users::getClientemail(array_values(session()->get('laravel_acl_sentry'))[0]);
            return View::make('report-dashboard')->with(['id' => null])->with(['id' => $request->id])->with(['email' => $client[0]->email, 'first_name' => $client[0]->first_name, 'last_name' => $client[0]->last_name, 'company_name' => $client[0]->company_name]);
        }
    }

    public function submissionAnalysis(Request $request) {

        try {
            $id = isset($request->id) ? $request->id : array_values(session()->get('laravel_acl_sentry'))[0];
            $email = ApiAccounts::getUserEmail($id);
            $entity = urlencode($email[0]['username']);
            $ownerType = Users::getOwnerType($id);
            $company = Users::getUserDetails($id);
            $type = ApiAccounts::checkUserType($id);
        } catch (\ErrorException $e) {
            $errors = 'No associated email found';
            return redirect()->route("dashboard")->withInput()->withErrors($errors);
        }
        return View::make('submission-analysis')->with(['entity' => $entity, 'ownerType' => $ownerType[0]->is_owner, 'company' => urlencode($company[0]->company_name), 'type' => $type[0]['type']]);
    }

    public function vatPotential(Request $request) {

        try {
            $id = isset($request->id) ? $request->id : array_values(session()->get('laravel_acl_sentry'))[0];
            $email = ApiAccounts::getUserEmail($id);
            $entity = urlencode($email[0]['username']);
            $ownerType = Users::getOwnerType($id);
            $company = Users::getUserDetails($id);
            $type = ApiAccounts::checkUserType($id);
        } catch (\ErrorException $e) {
            $errors = 'No associated email found';
            return redirect()->route("dashboard")->withInput()->withErrors($errors);
        }
        return View::make('vat-potiential')->with(['entity' => $entity, 'ownerType' => $ownerType[0]->is_owner, 'company' => urlencode($company[0]->company_name), 'type' => $type[0]['type']]);
    }

}
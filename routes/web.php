<?php

/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */

Route::get('/', [
    'uses' => 'FrontController@login',
    'as' => 'login'
]);

Route::get('dashboard', [
    'uses' => 'FrontController@dashboard',
    'middleware' => 'uAuth',
    'as' => 'dashboard'
]);

Route::get('concur/login', [
    'uses' => 'ConcurController@login',
    'middleware' => 'uAuth',
    'as' => 'concur-login'
]);

Route::post('concur/save-data', [
    'uses' => 'ConcurController@loginAction',
    'middleware' => 'uAuth',
    'as' => 'concur-login-action'
]);

Route::any('concur/date', [
    'uses' => 'ConcurController@showDateLog',
    'middleware' => 'uAuth',
    'as' => 'concur-date-log'
]);

Route::any('concur/date/log', [
    'uses' => 'ConcurController@userConcurLog',
    'middleware' => 'uAuth',
    'as' => 'concur-log-details'
]);

Route::get('concur/date/log/{id}', [
    'uses' => 'ConcurController@getUserConcurLogDetails',
    'middleware' => 'uAuth',
    'as' => 'user-concur-log'
]);

Route::any('concur/date/log/search', [
    'uses' => 'ConcurController@searchUserConcurLog',
    'middleware' => 'uAuth',
    'as' => 'concur-log-details-search'
]);

Route::get('expensify/login', [
    'uses' => 'ExpensifyController@login',
    'middleware' => 'uAuth',
    'as' => 'expensify-login'
]);

Route::post('expensify/save-data', [
    'uses' => 'ExpensifyController@loginAction',
    'middleware' => 'uAuth',
    'as' => 'expensify-login-action'
]);

Route::any('expensify/date', [
    'uses' => 'ExpensifyController@showDateLog',
    'middleware' => 'uAuth',
    'as' => 'expensify-date-log'
]);

Route::any('expensify/date/log', [
    'uses' => 'ExpensifyController@userExpensifyLog',
    'middleware' => 'uAuth',
    'as' => 'expensify-log-details'
]);

Route::get('expensify/date/log/{id}', [
    'uses' => 'ExpensifyController@getUserExpensifyLogDetails',
    'middleware' => 'uAuth',
    'as' => 'user-expensify-log'
]);

Route::get('excel/import', [
    'uses' => 'ExcelImportController@import',
    'middleware' => 'uAuth',
    'as' => 'excel-import'
]);

Route::post('excel/import/save-data', [
    'uses' => 'ExcelImportController@importAction',
    'middleware' => 'uAuth',
    'as' => 'excel-import-action'
]);

Route::get('send/excel', [
    'uses' => 'FrontController@sendEmail',
    'middleware' => 'uAuth',
    'as' => 'send-email'
]);

Route::post('send/excel', [
    'uses' => 'FrontController@sendEmail',
    'middleware' => 'uAuth',
    'as' => 'send-email'
]);

Route::get('report/dashboard/{id?}', [
    'uses' => 'ReportLoginController@reportDashboard',
    'middleware' => 'uAuth',
    'as' => 'report-dashboard'
]);

Route::get('submission-analysis/{id?}', [
    'uses' => 'ReportLoginController@submissionAnalysis',
    'middleware' => 'uAuth',
    'as' => 'submission-analysis'
]);
Route::post('submission-analysis/{id?}', [
    'uses' => 'ReportLoginController@submissionAnalysis',
    'middleware' => 'uAuth',
    'as' => 'submission-analysis-iframe'
]);

Route::get('vat-potential/{id?}', [
    'uses' => 'ReportLoginController@vatPotential',
    'middleware' => 'uAuth',
    'as' => 'vat-potiential'
]);
Route::post('vat-potential/{id?}', [
    'uses' => 'ReportLoginController@vatPotential',
    'middleware' => 'uAuth',
    'as' => 'vat-potiential-iframe'
]);

Route::get('user/details', [
    'uses' => 'FrontController@showUserDetails',
    'middleware' => 'uAuth',
    'as' => 'user-details'
]);

Route::get('contact-us', [
    'uses' => 'FrontController@contactUs',
    'as' => 'contactUs'
]);

Route::post('contact-us', [
    'uses' => 'FrontController@contactUs',
    'middleware' => 'uAuth',
    'as' => 'contactUs'
]);

Route::get('contact-request/list', [
    'uses' => 'FrontController@contactRequestList',
    'middleware' => 'uAuth',
    'as' => 'contact-request-list'
]);


//user 

Route::post('account/login', [
    'uses' => 'FrontController@postLogin',
    'as' => 'account.login.post'
]);

Route::get('password-recovery', [
    "as" => "recovery-password",
    "uses" => 'FrontController@forgotPassword'
]);

Route::post('account/reminder', [
    'uses' => 'FrontController@getforgotPassword',
    "as" => "account.reminder"
]);

Route::get('account/forget-password', [
    "as" => "user.forget-password",
    "uses" => 'FrontController@resetForgetPassword'
]);

Route::post('account/forget-password', [
    'uses' => 'FrontController@checkForgetPassword',
    "as" => "account.checkForgetPassword"
]);

Route::get('account/reset-password', [
    "as" => "user.reset-password",
    'middleware' => 'uAuth',
    "uses" => 'FrontController@resetPassword'
]);

Route::post('account/checkpassword', [
    'uses' => 'FrontController@checkPassword',
    "as" => "user.checkpassword"
]);

Route::get('account/password-reset-success', [
    "uses" => function () {
        return view('user.reset-password-success');
    },
    "as" => "account.password-reset-success"
]);

Route::get('account/reminder-success', [
    "uses" => function () {
        return view('user.forgot-success');
    },
    "as" => "account.reminder-success"
]);

Route::get('user/profile', [
    "uses" => 'FrontController@showUserprofile',
    'middleware' => 'uAuth',
    "as" => "user-profile"
]);

Route::post('edit/profile', [
    "uses" => 'FrontController@editProfile',
    'middleware' => 'uAuth',
    "as" => "user-profile-edit"
]);

Route::get('logout', [
    "uses" => 'FrontController@logout',
    "as" => "account.logout"
]);

Route::get('account/signup', [
    "uses" => 'FrontController@signup',
    "as" => "account.create"
]);

Route::post('account/signup', [
    "uses" => 'FrontController@postSignup',
    "as" => "account.signup.process"
]);

Route::get('account/signup-success', [
    "uses" => function () {
        return view('user.signup-success');
    },
    "as" => "account.signup-success"
]);

Route::get('user/change-status/{id}/{status}', [
    'uses' => 'FrontController@statusChange',
    'middleware' => 'uAuth',
    'as' => 'user.change.status'
]);

Route::get('user/associate-email/{id}', [
    'uses' => 'FrontController@associateEmail',
    'middleware' => 'uAuth',
    'as' => 'user.associate.email'
]);

Route::post('user/associate-email/save-data', [
    'uses' => 'FrontController@associateEmailWithUser',
    'middleware' => 'uAuth',
    'as' => 'user.associate.email.action'
]);

Route::get('contact-request/details/{id}', [
    'uses' => 'FrontController@getContactRequestDetails',
    'middleware' => 'uAuth',
    'as' => 'contact-request-details'
]);

Route::get('currency/list', [
    'uses' => 'CurrencyController@currencyList',
    'middleware' => 'uAuth',
    'as' => 'currency-list'
]);

Route::get('currency/change-status/{id}/{status}', [
    'uses' => 'CurrencyController@statusChange',
    'middleware' => 'uAuth',
    'as' => 'currency-change-status'
]);

Route::get('currency/base-currency/{id}', [
    'uses' => 'CurrencyController@setBaseCurrency',
    'middleware' => 'uAuth',
    'as' => 'base-currency'
]);

Route::get('currency/add', [
    'uses' => 'CurrencyController@addCurrency',
    'middleware' => 'uAuth',
    'as' => 'currency-add'
]);

Route::post('currency/add', [
    'uses' => 'CurrencyController@addCurrency',
    'middleware' => 'uAuth',
    'as' => 'currency-add'
]);

Route::get('currency/edit/{id}', [
    'uses' => 'CurrencyController@editCurrency',
    'middleware' => 'uAuth',
    'as' => 'currency-edit'
]);

Route::post('currency/edit/{id}', [
    'uses' => 'CurrencyController@editCurrency',
    'middleware' => 'uAuth',
    'as' => 'currency-edit'
]);

Route::post('currency/search', [
    'uses' => 'CurrencyController@searchCurrency',
    'middleware' => 'uAuth',
    'as' => 'currency-search'
]);

Route::any('contactUs/search', [
    'uses' => 'FrontController@searchContactRequestList',
    'middleware' => 'uAuth',
    'as' => 'contact-search'
]);

Route::any('user/search', [
    'uses' => 'FrontController@searchUserDetails',
    'middleware' => 'uAuth',
    'as' => 'user-search'
]);

Route::get('country-vat/list', [
    'uses' => 'CountryVatController@CountryVatList',
    'middleware' => 'uAuth',
    'as' => 'country-vat-list'
]);

Route::any('country-vat/search', [
    'uses' => 'CountryVatController@searchCountryVat',
    'middleware' => 'uAuth',
    'as' => 'country-vat-search'
]);

Route::post('country-vat/delete', [
    'uses' => 'CountryVatController@deleteCountryVat',
    'middleware' => 'uAuth',
    'as' => 'country-vat-delete'
]);

Route::any('country-vat/add', [
    'uses' => 'CountryVatController@addCountryVat',
    'middleware' => 'uAuth',
    'as' => 'country-vat-add'
]);

Route::any('country-vat/edit/{id}', [
    'uses' => 'CountryVatController@editCountryVat',
    'middleware' => 'uAuth',
    'as' => 'country-vat-edit'
]);

Route::get('currency-vat/list', [
    'uses' => 'CurrencyVatController@CurrencyVatList',
    'middleware' => 'uAuth',
    'as' => 'currency-vat-list'
]);

Route::any('currency-vat/search', [
    'uses' => 'CurrencyVatController@searchCurrencyVat',
    'middleware' => 'uAuth',
    'as' => 'currency-vat-search'
]);

Route::post('currency-vat/delete', [
    'uses' => 'CurrencyVatController@deleteCurrencyVat',
    'middleware' => 'uAuth',
    'as' => 'currency-vat-delete'
]);

Route::any('currency-vat/add', [
    'uses' => 'CurrencyVatController@addCurrencyVat',
    'middleware' => 'uAuth',
    'as' => 'currency-vat-add'
]);

Route::any('currency-vat/edit/{id}', [
    'uses' => 'CurrencyVatController@editCurrencyVat',
    'middleware' => 'uAuth',
    'as' => 'currency-vat-edit'
]);

Route::post('user/delete', [
    'uses' => 'FrontController@deleteUser',
    'middleware' => 'uAuth',
    'as' => 'user-delete'
]);

Route::any('concur/reports', [
    'uses' => 'ConcurController@getAllReports'
]);

Route::any('concur/expenses', [
    'uses' => 'ConcurController@getAllExpenses'
]);
Route::any('concur/refresh-token', [
    'uses' => 'ConcurController@updateAccessToken'
]);

Route::any('concur/upload/zoho', [
    'uses' => 'ConcurController@uploadToZoho'
]);

Route::any('concur/save/receipt', [
    'uses' => 'ConcurController@saveImage'
]);

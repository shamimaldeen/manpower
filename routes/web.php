<?php

use Illuminate\Support\Facades\Route;

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
// clear application cache
Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    return "Application cache flushed";
});

// clear route cache
Route::get('/clear-route-cache', function() {
    Artisan::call('route:clear');
    return "Route cache file removed";
});

// clear view compiled files
Route::get('/clear-view-compiled-cache', function() {
    Artisan::call('view:clear');
    return "View compiled files removed";
});

// clear config files
Route::get('/clear-config-cache', function() {
    Artisan::call('config:clear');
    return "Configuration cache file removed";
});

Route::get('/', function () {
  //  $passports = '';
    //return view('welcome',compact('passports'));

    return redirect()->route('admin.login');
});

/**
 * Json Response with web middleware
 */
include 'json.php';

Route::group(['namespace' => 'BackEndCon', 'middleware' => ['auth:admin']], function () {
    Route::get('dashboard', 'DashboardController@dashboard')->name('dashboard.index');
    Route::post('agent_wise.calculation', 'DashboardController@agent_wise_calculation')->name('agent_wise.calculation');
    Route::post('group_wise.calculation', 'DashboardController@group_wise_calculation')->name('group_wise.calculation');

    Route::resource('dashboard1', 'HajjController');
    Route::resource('groups', 'GroupController');
    Route::get('groups-group-history/{id}', 'HajjController@group_wise_history')->name('groups.group-history');
    Route::get('agent-wise-history/{id}', 'HajjController@agent_wise_history')->name('agent-customer.agent-history');
    Route::get('date-wise-report', 'HajjController@date_wise_report')->name('date-wise-report');
    Route::post('date-wise-report/show', 'HajjController@date_wise_report_show')->name('date-wise-report.show');



    Route::get('agent-customer', 'GroupController@agent_customer_list');

    Route::get('new/agent-customer', 'GroupController@agent_customer_create')->name('agent-customer.create');
    Route::post('store/agent-customer', 'GroupController@agent_customer_store')->name('agent-customer.store');
    Route::put('update/agent-customer/{id}', 'GroupController@agent_customer_update')->name('agent-customer.update');
    Route::get('show/agent-customer/{id}', 'GroupController@agent_customer_show')->name('agent-customer.show');
    Route::get('edit/agent-customer/{id}', 'GroupController@agent_customer_edit')->name('agent-customer.edit');
    Route::get('delete/agent-customer/{id}', 'GroupController@agent_customer_deleteagent_customer_delete')->name('agent-customer.delete');
    Route::resource('hajj-groups', 'HajjGroupController');
    Route::resource('omra-hajj-groups', 'OmraHajjGroupController');
    Route::resource('customer', 'CustomerController');


    Route::get('customer/pdf/{customer}', 'CustomerController@customerInfoPDF')->name('customer.pdf');
    Route::get('customer/print/{customer}', 'CustomerController@customerInfoPrint')->name('customer.print');
    Route::post('/passport-info/delete-document/{document_id}', 'PassportController@deleteDocument');
    Route::resource('passport-info', 'PassportController');
    Route::resource('passport-collection', 'PassportCollectionController');
    Route::get('passport-collection/pdf/{passport_collection}', 'PassportCollectionController@pdf')->name('passport-collection.pdf');
    Route::get('passport-history/receipt/print/{id}', 'PassportHistoryController@printReceipt')->name('passport-history.print-receipt');
    Route::resource('passport-history', 'PassportHistoryController');
    Route::post('passport-history/change-status', 'PassportHistoryController@changeStatus')->name('passport-history.change-status');
    Route::get('passport-alert', 'PassportHistoryController@passport_alert')->name('passport.alert');
    Route::get('visa-expire', 'PassportHistoryController@visa_expire_alert')->name('visa.expire');
    Route::get('medical-expire', 'PassportHistoryController@medical_expire_alert')->name('medical.alert');
    Route::resource('package', 'HajjPackageController');
    Route::resource('omra-hajj-package', 'OmraHajjPackageController');
    Route::resource('package-service', 'HajjController');
    Route::resource('omra-haji', 'OmraHajjController');
    Route::resource('sms-sending-system', 'SmsSenderController');


    Route::get('deposit-details/{hajj_id}', 'Accounts\DepositController@depositDetails')->name('deposit-details.view');

    Route::get('haji-payment-details', 'HajjPaymentController@index')->name('hajj-payment.index');
    Route::get('haji-payment-details/{payment_id}', 'HajjPaymentController@destroy')->name('hajj-payment.destroy');
    Route::get('hajj-payment/create/{hajj_id}', 'HajjPaymentController@create')->name('hajj-payment.create');
    Route::post('hajj-payment', 'HajjPaymentController@store')->name('hajj-payment.store');
    Route::get('hajj-payment/{payment_id}/edit', 'HajjPaymentController@edit')->name('hajj-payment.edit');
    Route::put('hajj-payment/{payment_id}', 'HajjPaymentController@update')->name('hajj-payment.update');

    Route::get('omra-haji-payment-details', 'OmraHajjPaymentController@index')->name('omra-hajj-payment.index');
    Route::get('omra-haji-payment-details/{payment_id}', 'OmraHajjPaymentController@destroy')->name('omra-hajj-payment.destroy');
    Route::get('omra-hajj-payment/create/{hajj_id}', 'OmraHajjPaymentController@create')->name('omra-hajj-payment.create');
    Route::post('omra-hajj-payment', 'OmraHajjPaymentController@store')->name('omra-hajj-payment.store');
    Route::get('omra-hajj-payment/{payment_id}/edit', 'OmraHajjPaymentController@edit')->name('omra-hajj-payment.edit');
    Route::put('omra-hajj-payment/{payment_id}', 'OmraHajjPaymentController@update')->name('omra-hajj-payment.update');

//bank list
Route::get('/view-bank', 'BankController@viewBank')->name('view-bank');

Route::get('/add-bank', 'BankController@addBank')->name('add-bank');
Route::post('/save-bank', 'BankController@saveBank')->name('save-bank');

// Route::get('/bank/published/{id}', 'BankController@publishedBank')->name('published-bank');
// Route::get('/bank/unpublished/{id}', 'BankController@unpublishedBank')->name('unpublished-bank');

Route::post('/update-bank', 'BankController@updateBank')->name('update-bank');
Route::post('/delete-bank/{id}', 'BankController@deleteBank')->name('delete-bank');

//Expense Categories
Route::get('/view-expense-category', 'CatController@viewCat')->name('view-expense-category');

Route::get('/add-expense-category', 'CatController@addCat')->name('add-expense-category');
Route::post('/save-expense-category', 'CatController@saveCat')->name('save-expense-category');

// Route::get('/expense-category/published/{id}', 'CatController@publishedCat')->name('published-cat');
// Route::get('/expense-category/unpublished/{id}', 'CatController@unpublishedCat')->name('unpublished-cat');

Route::post('/update-expense-category', 'CatController@updateCat')->name('update-expense-category');
Route::post('/delete-expense-category/{id}', 'CatController@deleteCat')->name('delete-expense-category');


    // Makka-Madina Management Routes
    Route::resource('hotel-rate', 'HotelRateController');
    Route::resource('vehicle-rate', 'VehicleRateController');
    // END Makka-Madina Management Routes

    // Accounts Management Routes
    Route::group(['namespace' => 'Accounts'], function () {
        Route::get('deposit-list/receipt/print/{id}', 'DepositController@printReceipt')->name('deposit-list.print-receipt');
        Route::resource('deposit-list', 'DepositController');
        Route::get('deposit-list/add/{hajj_id}', 'DepositController@addPayment')->name('deposit-list.add-payment');
        Route::post('hajj-payment-status/change', 'DepositController@changePaymentStatus')->name('deposit-list.change-status');
        Route::get('hajj-payment/status/change/{hajj_id}', 'DepositController@payment_changeStatus')->name('deposit.change.status');
        Route::resource('expense-list', 'ExpenseController');
        Route::resource('cash-in-hand', 'CashInHandController');
        Route::get('all/customer/due/', 'DepositController@due_customer')->name('customer.due.index');
        



          //daily cash in hand
        Route::get('daily-cash-in-hand', 'CashInHandController@deposit_expense')->name('daily-cash-in-hand');
        Route::post('deposit-expense-stor', 'CashInHandController@deposit_expense_stor')->name('deposit-expense-stor');







          //cash in deposit
        Route::get('cash-in-deposit', 'CashInDepositController@cash_in_deposit')->name('cash-in-deposit');
        Route::get('add-cash-in-deposit', 'CashInDepositController@add_cash_in_deposit')->name('add-cash-in-deposit');
        Route::post('save-cash-deposit', 'CashInDepositController@save_cash_deposit')->name('save-cash-deposit');
        Route::get('deposit-edit/{id}', 'CashInDepositController@deposit_edit')->name('deposit-edit');
        Route::post('update-deposit/{id}', 'CashInDepositController@update_deposit')->name('update-deposit');
        Route::post('delete-deposit/{id}', 'CashInDepositController@delete_deposit')->name('delete-deposit');
        
    });
    // END Accounts Management Routes

    Route::resource('customer-payment', 'CustomerPaymentController');

    Route::resource('visa-management', 'VisaManagementController');

    // Reports Routes
    Route::group(['namespace' => 'Reports'], function () {
        Route::resource('customer-report', 'CustomerReportController');
        Route::resource('haji-report', 'HajjReportController');
        Route::resource('omra-haji-report', 'OmraHajjReportController');
        Route::resource('passport-report', 'PassportReportController');
    });
    // END Reports Routes

    // Ticket Management Routes
    Route::group(['namespace' => 'TicketManagement'], function () {
        Route::resource('ticketing-airlines', 'TicketingAirlineController');
        Route::resource('ticket-sales', 'TicketSalesController');
        Route::resource('ticket-refund', 'TicketRefundController');
        Route::get('ticket-sales-commission', 'TicketSalesCommissionController@index');
    });
    // END Ticket Management Routes

    Route::resource('passport-status', 'PassportStatusController');
});


// Turned off Register Routes
Auth::routes([
    'register' => false, // Registration Routes...
    'reset' => false, // Password Reset Routes...
    'verify' => false, // Email Verification Routes...
]);

// frontend
Route::get('passport-check', 'Frontend\HomeController@checkindex');
Route::post('passport-check/status', 'Frontend\HomeController@check_status')->name('passport.check.status');

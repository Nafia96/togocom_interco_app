<?php

use App\Http\Controllers\OperatorController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NationalController;
use App\Http\Controllers\OperationController;
use App\Http\Controllers\RoamingController;
use App\Http\Controllers\StaController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\superAdmin;
use App\Http\Middleware\NotConnected;


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



Route::match(['get', 'post'], '/', 'App\Http\Controllers\HomeController@index')->name('home');
Route::match(['get', 'post'], 'forgot_password', 'App\Http\Controllers\HomeController@forgot_password')->name('forgot_password');
Route::match(['get', 'post'], 'forget_password', 'App\Http\Controllers\HomeController@forget_password')->name('forget_password');
Route::match(['get', 'post'], 'forget_password_confirme/', 'App\Http\Controllers\HomeController@forget_password_confirme')->name('forget_password_confirme');
Route::match(['get', 'post'], 'add_new_password/', 'App\Http\Controllers\HomeController@add_new_password')->name('add_new_password');
Route::get('logs', [App\Http\Controllers\HomeController::class, 'journaux'])->name('logs');



Route::get('update_password', [HomeController::class, 'update_password'])->name('update_password');
Route::post('update_password_save', [HomeController::class, 'update_password_save'])->name('update_password_save');

Route::get('dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
Route::get('national', [HomeController::class, 'national'])->name('national');
Route::get('billing', [HomeController::class, 'billing2'])->name('billing2');
Route::get('billing2', [HomeController::class, 'billing2'])->name('billing2');
Route::get('roaming', [HomeController::class, 'roaming2'])->name('roaming');
Route::get('billingp', [HomeController::class, 'billingpivot'])->name('billingp');


Route::get('billingn', [HomeController::class, 'billingPivotNetCarrier'])->name('billingPivotNetCarrier');


Route::get('/billingc', [App\Http\Controllers\HomeController::class, 'billingPivotCountryCarrier'])->name('billingPivotCountryCarrier');

Route::get('/billingKp', [HomeController::class, 'billingKp'])->name('billingKp');

Route::get('/billingKpiNetwork', [HomeController::class, 'billingKpiNetwork'])->name('billingKpiNetwork');

// KPI pivot
Route::get('kpi/pivot', [HomeController::class, 'kpip'])->name('kpi.pivot');
Route::get('/kpi/network', [HomeController::class, 'Kpin'])->name('kpi.network');

Route::get('/kpi/KpinCarrier', [HomeController::class, 'KpinCarrier'])->name('kpi.KpinCarrier');

Route::get('kpi', [HomeController::class, 'kpip'])->name('kpi.index');

Route::get('partnerKpi', [HomeController::class, 'partnerKpi'])->name('partnerKpi');

Route::get('networkkpi', [HomeController::class, 'networkKpi'])->name('networkkpi');

Route::get('complation', [HomeController::class, 'complation'])->name('complation');

//roaming rout
Route::get('iot_discount', [RoamingController::class, 'iot_discount'])->name('iot_discount');
Route::get('iot_sms_data', [RoamingController::class, 'iot_sms_data'])->name('iot_sms_data');
Route::get('iot_sms_voice', [RoamingController::class, 'iot_sms_voice'])->name('iot_sms_voice');
Route::post('iot_discount_register', [RoamingController::class, 'iot_discount_register'])->name('iot_discount_register');



Route::get('logout', 'App\Http\Controllers\HomeController@logout');

Route::middleware([NotConnected::class])->group(function () {
    Route::middleware(['interco.agent'])->group(function () {

        Route::middleware([superAdmin::class])->group(function () {


            //User route
            Route::get('add_user', [UserController::class, 'add_user'])->name('add_user');
            Route::get('setting', [UserController::class, 'setting'])->name('setting');
            Route::post('user_register', [UserController::class, 'user_register'])->name('user_register');
            Route::get('users_list', [UserController::class, 'users_list'])->name('users_list');
            Route::get('delete_user_liste', [UserController::class, 'delete_user_liste'])->name('delete_user_liste');
            Route::get('update_user/{id}', [UserController::class, 'update_user'])->name('update_user');
            Route::post('save_update_user', [UserController::class, 'save_update_user'])->name('save_update_user');
            Route::get('delete_user/{id}', [UserController::class, 'delete_user'])->name('delete_user');
            Route::get('activate_user/{id}', [UserController::class, 'activate_user'])->name('activate_user');




            //Operator route
            Route::get('add_operator', [OperatorController::class, 'add_operator'])->name('add_operator');
            Route::post('add_operator', [OperatorController::class, 'operator_register'])->name('operator_register');
            Route::get('delete_operator_liste', [OperatorController::class, 'delete_operator_liste'])->name('delete_operator_liste');
            Route::get('update_operator/{id}', [OperatorController::class, 'update_operator'])->name('update_operator');
            Route::post('update_operator', [OperatorController::class, 'save_update'])->name('save_update');
            Route::post('save_setting', [OperatorController::class, 'save_setting'])->name('save_setting');
            Route::get('delete_operator/{id}', [OperatorController::class, 'delete_operator'])->name('delete_operator');
            Route::get('activate_operator/{id}', [OperatorController::class, 'activate_operator'])->name('activate_operator');

            //operation

            Route::get('admin_operations', [OperationController::class, 'admin_operations'])->name('admin_operations');
        });

        Route::get('ope_dashboard/{id}', [OperatorController::class, 'ope_dashboard'])->name('ope_dashboard');


        Route::get('delete_operation/{id}', [OperationController::class, 'delete_operation'])->name('delete_operation');


        Route::get('operator_dashboard', [OperatorController::class, 'operator_dashboard'])->name('operator_dashboard');


        Route::get('liste_operator', [OperatorController::class, 'liste_operator'])->name('liste_operator');

         Route::get('liste_operator_netting', [OperatorController::class, 'liste_operator_netting'])->name('liste_operator_netting');


    // Routes for national measurements
    // Existing specific route (kept for compatibility)
    // Specific routes for each direction (used by the sidebar menu)
    Route::get('show_tgt_tgc', [NationalController::class, 'show_tgt_tgc'])->name('show_tgt_tgc');
    // Simple hyphenated routes for nicer URLs (no URL-encoding required in blade)
    Route::get('tgt-tgc', [NationalController::class, 'show_tgt_tgc'])->name('tgt-tgc');
    Route::get('tgc-tgt', [NationalController::class, 'show_tgc_tgt'])->name('tgc-tgt');
    Route::get('tgt-mat', [NationalController::class, 'show_tgt_mat'])->name('tgt-mat');
    Route::get('mat-tgt', [NationalController::class, 'show_mat_tgt'])->name('mat-tgt');
    Route::post('mesure_tgt_tgc', [NationalController::class, 'mesure_tgt_tgc'])->name('mesure_tgt_tgc');

    // Generic routes that work with any direction value. Use URL-encoded direction names when needed.
    Route::get('show_measure/{direction}', [NationalController::class, 'show_by_direction'])->name('show_measure');
    Route::post('mesure', [NationalController::class, 'mesure_store'])->name('mesure.store');
    // Dedicated dashboards per sens (unique pages for each direction)
    Route::get('tgc_tgt_dashboard', [NationalController::class, 'show_tgc_tgt'])->name('tgc_tgt_dashboard');
    Route::get('tgt_mat_dashboard', [NationalController::class, 'show_tgt_mat'])->name('tgt_mat_dashboard');
    Route::get('mat_tgt_dashboard', [NationalController::class, 'show_mat_tgt'])->name('mat_tgt_dashboard');
    Route::get('mat_tgc_dashboard', [NationalController::class, 'show_mat_tgc'])->name('mat_tgc_dashboard');
    // Set traffic validated for a measure (manual input)
    Route::post('measures/{id}/set_validated', [NationalController::class, 'set_validated_traffic'])->name('measures.set_validated');
    // Update a measure (edit line)
    Route::post('measures/{id}/update', [NationalController::class, 'update_measure'])->name('measures.update');
    // Get validation audits for a measure (used by comment popup)
    Route::get('measures/{id}/audits', [NationalController::class, 'get_measure_audits'])->name('measures.audits');
    // Generate invoice from selected measures
    Route::post('measures/generate_invoice', [NationalController::class, 'generate_invoice_from_measures'])->name('measures.generate_invoice');
    // National invoices list and download
    Route::get('national_invoices', [NationalController::class, 'national_invoices_index'])->name('national_invoices.index');
    Route::get('national_invoices/download/{filename}', [NationalController::class, 'national_invoice_download'])->name('national_invoices.download');
    // Generate export (pdf/docx) for national invoice
    Route::get('national_invoice/{id}/generate/{format?}', [App\Http\Controllers\InvoiceExportController::class, 'generate'])->name('national_invoice.generate');
    // Unit price management page
    Route::get('unit_prices', [NationalController::class, 'unit_prices_index'])->name('unit_prices.index');
    Route::post('unit_prices', [NationalController::class, 'unit_prices_store'])->name('unit_prices.store');

        //Route des operations

        Route::post('tgc_to_ope_invoice', [OperationController::class, 'tgc_to_ope_invoice'])->name('tgc_to_ope_invoice');
        Route::post('ope_to_tgc_invoice', [OperationController::class, 'ope_to_tgc_invoice'])->name('ope_to_tgc_invoice');
        Route::post('update_estimated_invoice', [OperationController::class, 'update_estimated_invoice'])->name('update_estimated_invoice');
        Route::post('update_all_invoice', [OperationController::class, 'update_all_invoice'])->name('update_all_invoice');
        Route::post('add_settlement', [OperationController::class, 'add_settlement'])->name('add_settlement');
        Route::post('add_contestation', [OperationController::class, 'add_contestation'])->name('add_contestation');
        Route::post('add_cn', [OperationController::class, 'add_cn'])->name('add_cn');
        Route::get('operations_list/{id_operator}', [OperationController::class, 'operations_list'])->name('operations_list');
        Route::get('invoice_list/{id_operator}', [OperationController::class, 'invoice_list'])->name('invoice_list');
        Route::get('all_invoice_list', [OperationController::class, 'all_invoice_list'])->name('all_invoice_list');
        Route::get('send_invoices', [OperationController::class, 'send_invoices'])->name('send_invoices');
        Route::post('selection', [OperationController::class, 'selection'])->name('selection');
        Route::POST('/envoyerFactures', [OperationController::class, 'envoyerFactures'])->name('envoyerFactures');
        Route::get('all_resum_list', [OperationController::class, 'all_resum_list'])->name('all_resum_list');
        Route::get('delete_invoice_list', [OperationController::class, 'delete_invoice_list'])->name('delete_invoice_list');
        Route::get('all_operations', [OperationController::class, 'all_operations'])->name('all_operations');
        Route::get('all_cancel_operations', [OperationController::class, 'all_cancel_operations'])->name('all_cancel_operations');
        Route::get('cancel_operation/{id_operation}', [OperationController::class, 'cancel_operation'])->name('cancel_operation');
        Route::get('receivable_debt/{id_operator}', [OperationController::class, 'receivable_debt'])->name('receivable_debt');

        // Teléhargement des factures
        Route::get('downloadinvoice/{year}/{month}/{operatorId}', [OperationController::class, 'downloadInvoice'])->name('downloadinvoice');


        //Fin route operations

        //stactistique route
        Route::get('sta_dashboard', [StaController::class, 'sta_dashboard'])->name('sta_dashboard');


        Route::match(['get', 'post'], 'lunchpade', 'App\Http\Controllers\HomeController@lunchpade')->name('lunchepade');
    });
    //Les pages pour les gars de BI
    Route::match(['get', 'post'], 'lunchpadb', 'App\Http\Controllers\HomeController@lunchpadb')->name('lunchepadb');
    Route::get('interco_details', [HomeController::class, 'interco_details'])->name('interco_details');
    Route::get('add_credit', [HomeController::class, 'add_credit'])->name('add_credit');
    Route::post('/import-excel', [HomeController::class, 'import'])->name('import.excel');
    Route::post('/update_credit', [HomeController::class, 'update_credit'])->name('update_creditl');
    Route::post('/add_roaming_credit', [HomeController::class, 'add_roaming_credit'])->name('add_roaming_credit');

});
// For others functionality
Route::get('/migrate-fresh', function () {

    Artisan::call('migrate:fresh');

    Artisan::call('db:seed');

    /*Artisan::call('ide-helper:models -R');*/

    Artisan::call('config:cache');

    Artisan::call('config:clear');

    Artisan::call('cache:clear');

    Artisan::call('route:clear');

    Artisan::call('view:clear');

    Artisan::call('clear-compiled');

    return "OK.";
});

// For others functionality
Route::get('/migrate', function () {

    Artisan::call('migrate');

    Artisan::call('config:cache');

    Artisan::call('config:clear');

    Artisan::call('cache:clear');

    Artisan::call('route:clear');

    Artisan::call('view:clear');

    Artisan::call('clear-compiled');

    return "OK.";
});

Route::get('/clear-cache', function () {

    Artisan::call('config:cache');

    Artisan::call('config:clear');

    Artisan::call('cache:clear');

    Artisan::call('route:clear');

    Artisan::call('view:clear');

    Artisan::call('clear-compiled');

    return "OK.";
});

<?php

use App\Http\Controllers\OperatorController;
use App\Http\Controllers\AgentBureauxController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ComptesController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OperationController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\superAdmin;
use App\Http\Middleware\NotConnected;

use App\Models\Operator;

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


Route::get('logout', 'App\Http\Controllers\HomeController@logout');

Route::middleware([NotConnected::class])->group(function(){

Route::middleware([superAdmin::class])->group(function(){



//Operator route
Route::get('add_operator',[OperatorController::class, 'add_operator'])->name('add_operator');
Route::post('add_operator',[OperatorController::class, 'operator_register'])->name('operator_register');
Route::get('liste_operator',[OperatorController::class, 'liste_operator'])->name('liste_operator');
Route::get('delete_operator_liste',[OperatorController::class, 'delete_operator_liste'])->name('delete_operator_liste');
Route::get('update_operator/{id}',[OperatorController::class, 'update_operator'])->name('update_operator');
Route::post('update_operator',[OperatorController::class, 'save_update'])->name('save_update');
Route::get('delete_operator/{id}',[OperatorController::class, 'delete_operator'])->name('delete_operator');
Route::get('activate_operator/{id}',[OperatorController::class, 'activate_operator'])->name('activate_operator');
Route::get('new_chef_operator/{id}',[OperatorController::class, 'get_new_chef_operator'])->name('new_chef_operator');
Route::post('add_new_chef_operator',[OperatorController::class, 'add_new_chef_operator'])->name('add_new_chef_operator');
Route::get('operator_statistique/{id}',[OperatorController::class,'operator_statistique'])->name('operator_statistique');

//operation

Route::get('admin_operations',[OperationController::class, 'admin_operations'])->name('admin_operations');


});

Route::get('delete_operation/{id}',[OperationController::class,'delete_operation'])->name('delete_operation');


Route::get('operator_dashboard',[OperatorController::class,'operator_dashboard'])->name('operator_dashboard');

//Agent Route
Route::get('add_agent',[AgentController::class, 'add_agent'])->name('add_agent');
Route::post('add_agent',[AgentController::class, 'agent_register'])->name('agent_register');
Route::get('liste_agent',[AgentController::class, 'liste_agent'])->name('liste_agent');
Route::get('delete_agent_liste',[AgentController::class, 'delete_agent_liste'])->name('delete_agent_liste');
Route::get('update_agent/{id}',[AgentController::class, 'update_agent'])->name('update_agent');
Route::post('update_agent',[AgentController::class, 'save_update']);
Route::get('delete_agent/{id}',[AgentController::class, 'delete_agent'])->name('delete_agent');
Route::get('activate_agent/{id}',[AgentController::class, 'activate_agent'])->name('activate_agent');


Route::get('agent_dashboard',[AgentController::class,'agent_dashboard'])->name('agent_dashboard');



//AgentBureaux Route
Route::get('add_agent_br',[AgentBureauxController::class, 'add_agent_br'])->name('add_agent_br');
Route::post('agentadd_agent_br_register',[AgentBureauxController::class, 'agentadd_agent_br_register'])->name('agentadd_agent_br_register');
Route::get('liste_agent_br',[AgentBureauxController::class, 'liste_agent_br'])->name('liste_agent_br');
Route::get('delete_agent_br_liste',[AgentBureauxController::class, 'delete_agent_br_liste'])->name('delete_agent_br_liste');
Route::get('update_agent_br/{id}',[AgentBureauxController::class, 'update_agent_br'])->name('update_agent_br');
Route::post('update_agent',[AgentBureauxController::class, 'save_update']);
Route::get('delete_agent/{id}',[AgentBureauxController::class, 'delete_agent'])->name('delete_agent');
Route::get('activate_agent/{id}',[AgentBureauxController::class, 'activate_agent'])->name('activate_agent');
Route::get('agent_statistique/{id}',[AgentBureauxController::class, 'agent_statistique'])->name('agent_statistique');


//Client route
Route::get('add_client',[ClientController::class, 'add_client'])->name('add_client');
Route::post('add_client',[ClientController::class, 'client_register'])->name('client_register');
Route::get('liste_client',[ClientController::class, 'liste_client'])->name('liste_client');
Route::get('liste_client_agent',[ClientController::class, 'liste_client_agent'])->name('liste_client_agent');
Route::get('delete_client_liste',[ClientController::class, 'delete_client_liste'])->name('delete_client_liste');
Route::get('update_client/{id}',[ClientController::class, 'update_client'])->name('update_client');
Route::post('update_client',[ClientController::class, 'save_update']);
Route::get('delete_client/{id}',[ClientController::class, 'delete_client'])->name('delete_client');
Route::get('activate_client/{id}',[ClientController::class, 'activate_client'])->name('activate_client');
Route::get('client_dashboard',[ClientController::class,'client_dashboard'])->name('client_dashboard');


//Comptes tontine route
Route::get('add_tontine',[ComptesController::class, 'add_tontine'])->name('add_tontine');
Route::post('add_tontine',[ComptesController::class, 'tontine_register'])->name('tontine_register');
Route::get('liste_tontine',[ComptesController::class, 'liste_tontine'])->name('liste_tontine');
Route::get('liste_tontine_agent',[ComptesController::class, 'liste_tontine_agent'])->name('liste_tontine_agent');
Route::get('liste_tontine_client',[ComptesController::class, 'liste_tontine_client'])->name('liste_tontine_client');
Route::get('liste_epargne_client',[ComptesController::class, 'liste_epargne_client'])->name('liste_epargne_client');
Route::get('update_tontine/{id}',[ComptesController::class, 'update_tontine'])->name('update_tontine');
Route::post('save_tontine_update',[ComptesController::class, 'save_tontine_update'])->name('save_tontine_update');
Route::get('delete_tontine/{id}',[ComptesController::class, 'delete_tontine'])->name('delete_tontine');
Route::get('delete_tontine_liste',[ComptesController::class, 'delete_tontine_liste'])->name('delete_tontine_liste');
Route::get('activate_tontine/{id}',[ComptesController::class, 'activate_tontine'])->name('activate_tontine');



//Comptes epargne route
Route::get('add_epargne',[ComptesController::class, 'add_epargne'])->name('add_epargne');
Route::post('add_epargne',[ComptesController::class, 'epargne_register'])->name('epargne_register');
Route::get('liste_epargne',[ComptesController::class, 'liste_epargne'])->name('liste_epargne');
Route::get('liste_epargne_agent',[ComptesController::class, 'liste_epargne_agent'])->name('liste_epargne_agent');
Route::get('update_epargne/{id}',[ComptesController::class, 'update_epargne'])->name('update_epargne');
Route::post('save_epargne_update',[ComptesController::class, 'save_epargne_update'])->name('save_epargne_update');
Route::get('delete_epargne/{id}',[ComptesController::class, 'delete_epargne'])->name('delete_epargne');
Route::get('delete_epargne_liste',[ComptesController::class, 'delete_epargne_liste'])->name('delete_epargne_liste');
Route::get('activate_epargne/{id}',[ComptesController::class, 'activate_epargne'])->name('activate_epargne');



//Comptes salaire route
Route::get('add_salaire',[ComptesController::class, 'add_salaire'])->name('add_salaire');
Route::post('add_salaire',[ComptesController::class, 'salaire_register'])->name('salaire_register');
Route::get('liste_salaire',[ComptesController::class, 'liste_salaire'])->name('liste_salaire');
Route::get('update_salaire/{id}',[ComptesController::class, 'update_salaire'])->name('update_salaire');
Route::post('save_salaire_update',[ComptesController::class, 'save_salaire_update'])->name('save_salaire_update');
Route::get('delete_salaire/{id}',[ComptesController::class, 'delete_salaire'])->name('delete_salaire');
Route::get('delete_salaire_liste',[ComptesController::class, 'delete_salaire_liste'])->name('delete_salaire_liste');
Route::get('activate_salaire/{id}',[ComptesController::class, 'activate_salaire'])->name('activate_salaire');



//Route des operations

Route::post('tgc_to_ope_invoice',[OperationController::class, 'tgc_to_ope_invoice'])->name('tgc_to_ope_invoice');
Route::post('ope_to_tgc_invoice',[OperationController::class, 'ope_to_tgc_invoice'])->name('ope_to_tgc_invoice');
Route::get('operations_list/{id_operator}',[OperationController::class, 'operations_list'])->name('operations_list');
Route::get('invoice_list/{id_operator}',[OperationController::class, 'invoice_list'])->name('invoice_list');
Route::get('all_operations',[OperationController::class, 'all_operations'])->name('all_operations');


Route::post('epargne_tontine',[OperationController::class, 'epargne_tontine'])->name('epargne_tontine');
Route::post('retrait',[OperationController::class, 'retrait'])->name('retrait');
Route::get('retrait_admin',[OperationController::class, 'retrait_admin'])->name('retrait_admin');
Route::get('versement',[OperationController::class, 'versement'])->name('versement');
Route::get('operator_operations',[OperationController::class, 'operator_operations'])->name('operator_operations');
Route::get('agent_operations',[OperationController::class, 'agent_operations'])->name('agent_operations');
Route::get('client_operations',[OperationController::class, 'client_operations'])->name('client_operations');
Route::post('versement_save',[OperationController::class, 'versement_save'])->name('versement_save');
Route::post('retrait_admin_save',[OperationController::class, 'retrait_admin_save'])->name('retrait_admin_save');
Route::get('facture/{id_operation}',[OperationController::class, 'facture'])->name('facture');
Route::get('new_carnet/{id_compte}',[OperationController::class, 'new_carnet'])->name('new_carnet');




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

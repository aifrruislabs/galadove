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

//Index
Route::get('/', ['uses' => 'Controller@index']);

//Terms
Route::get('/terms', ['uses' => 'Controller@terms'])->name('terms');

//Auth
Auth::routes();


//Authenticated Routes
Route::middleware(['auth'])->group(function () {

	//System Logs
	Route::get('/system/logs', ['uses' => 'Controller@systemLogs'])->name('system-logs');

	//Generate API Key
	Route::post('/generate/api/key', ['uses' => 'Controller@generateApiKey'])->name('generate-api-key');

	//Incoming SMS
	Route::get('/incoming/sms', ['uses' => 'IncomingSMSController@incomingSMS'])->name('incoming-sms');

	//Outgoing SMS
	Route::get('/outgoing/sms', ['uses' => 'OutgoingSMSController@outgoingSms'])->name('outgoing-sms');

	//Add New Smartphone
	Route::post('/add/new/smartphone', ['uses' => 'SmartPhoneController@addNewSmartphone'])->name('add-new-smartphone');

	//Settings
	Route::get('/settings', ['uses' => 'Controller@settings'])->name('settings');

	//SMS-Analytics
	Route::get('/sms/analytics', ['uses' => 'SMSController@smsAnalytics'])->name('sms-analytics');

	//Smart Phones
	Route::get('/smart/phones', ['uses' => 'SmartPhoneController@smartPhones'])->name('smart-phones');

	//Home
	Route::get('/home', 'HomeController@index')->name('home');

});
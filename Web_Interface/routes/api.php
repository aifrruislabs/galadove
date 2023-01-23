<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'v1'], function () {

	//Reset Not Delivered Messages for 120 Seconds
	Route::get('/reset/not/delivered/messages', ['uses' => 'OutgoingSMSController@resetNotDeliveredMessages']);

	//Clear SMS Delivery Report
	Route::post('/mobile/interface/clear/delivery/rpt', ['uses' => 'OutgoingSMSController@clearDlrSMS']);

	//Clear Send SMS from Smart Phone
	Route::post('/mobile/interface/clear/outgoing', ['uses' => 'OutgoingSMSController@clearSentSMS']);

	//Mobile Interface Login
	Route::post('/mobile/interface/login', ['uses' => 'Controller@mobileInterfaceLogin']);

	//Post SMS Incoming
	Route::post('/receive/action/sms/incoming', ['uses' => 'IncomingSMSController@receiveSMSIncoming']);

	//Get SMS for Outgoing
	Route::get('/get/action/outgoing/sms', ['uses' => 'OutgoingSMSController@getActionOutgoingSMS']);

	//Receive SMS from External
	Route::post('/receive/action/send/sms', ['uses' => 'OutgoingSMSController@receiveActionSendSMS']);

});
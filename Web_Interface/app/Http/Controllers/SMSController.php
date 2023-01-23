<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SMSController extends Controller
{
    //smsAnalytics 
    public function smsAnalytics(Request $request)
    {
    	return view("pages.smsAnalytics");
    }
}

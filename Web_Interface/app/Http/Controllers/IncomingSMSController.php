<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IncomingSMSController extends Controller
{

	//incomingSMS
	public function incomingSMS(Request $request)
	{
		return view("pages.incomingSMS");
	}


    //receiveSMSIncoming
    public function receiveSMSIncoming(Request $request)
    {
    	
    }
}

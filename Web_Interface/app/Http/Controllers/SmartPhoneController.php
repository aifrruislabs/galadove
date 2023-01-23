<?php

namespace App\Http\Controllers;

use App\SmartPhone;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SmartPhoneController extends Controller
{
	//addNewSmartphone
	public function addNewSmartphone(Request $request)
	{
		//Input Validation
		$this->validate($request,
			[
				'title' => 'required',
				'username' => 'required',
				'password' => 'required'
			]);

		//Adding New Smartphone
		$newSmartPhone = new SmartPhone();
		$newSmartPhone->userId = Auth::User()->id;
		$newSmartPhone->title = $request->title;
		$newSmartPhone->info = $request->info;
		$newSmartPhone->username = $request->username;
		$newSmartPhone->password = $request->password;
		$newSmartPhone->connectionStatus = "F";

		if ($newSmartPhone->save()) {
			$message = "New Smart Phone Device Have Been Added Successfully";
			return redirect()->back()->with(['successMessage' => $message]);
		}else {
			$message = "Failed to Add New Smart Phone Device. Please Try Again Later";
			return redirect()->back()->with(['errorMessage' => $message]);
		}
	}

    //smartPhones
    public function smartPhones(Request $request)
    {
    	$smartPhonesList = SmartPhone::where('userId', Auth::User()->id)
    						->orderBy('created_at', 'DESC')
    						->paginate(12);

    	return view("pages.smartPhones",
    			[
    				'smartPhonesList' => $smartPhonesList
    			]);
    }
}

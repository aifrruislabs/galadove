<?php

namespace App\Http\Controllers;

use App\SMSAPIKey;
use App\SmartPhone;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    //systemLogs
    public function systemLogs(Request $request)
    {
        return view("pages.systemLogs");
    }


        /**
         * @OA\POST(
         *     path="/api/v1/mobile/interface/login",
         *     tags={"MOBILE_INTERFACE"},
         *     summary="Mobile Interface Login to Get Api Key for Communication",
         *     description="Mobile Interface Login to Get Api Key for Communication",
         *
         *     @OA\Parameter(
         *          name="smartPhoneId",
         *          in="query",
         *          required=true,
         *          description="Enter Smart Phone Id",
         *          @OA\Schema(
         *              type="string"
         *          ),
         *     ),
         *
         *     @OA\Parameter(
         *          name="username",
         *          in="query",
         *          required=true,
         *          description="Enter Smart Phone Username",
         *          @OA\Schema(
         *              type="string"
         *          ),
         *     ),
         *
         *
         *     @OA\Parameter(
         *          name="password",
         *          in="query",
         *          required=true,
         *          description="Enter Smart Phone password",
         *          @OA\Schema(
         *              type="string"
         *          ),
         *     ),

         *
         *     @OA\Response(
         *         response="default",
         *         description="successful operation"
         *     )
         * )
         */
    //mobileInterfaceLogin
    public function mobileInterfaceLogin(Request $request)
    {
        //Input Validation
        $this->validate($request,
                [
                    'smartPhoneId' => 'required',
                    'username' => 'required',
                    'password' => 'required'
                ]);

        $userId = "";
        $smartPhoneId = "";
        $explodeId = explode("-", $request->smartPhoneId);

        if (sizeof($explodeId) == 2) {
            $userId = $explodeId['0'];
            $smartPhoneId = $explodeId['1'];
        }else {
            return response()->json(array('status' => False, 'message_code' => 'ERROR_ID'), 200);
        }

        //Verify Auth Login for Mobile Device
        $verifyLoginInfo = SmartPhone::where([
                            ['userId', '=', $userId],
                            ['id', '=', $smartPhoneId],
                            ['username', '=', $request->username],
                            ['password', '=', $request->password]
                            ])->get()->toArray();

        if (sizeof($verifyLoginInfo) == 1) {
            //Get API Key for Success Auth
            $apiKeyQ = SMSAPIKey::where('userId', $userId)->get()->toArray();

            if (sizeof($apiKeyQ) == 1) {
                return response()->json(array('status' => True, 'apiKey' => $apiKeyQ['0']['apiKey']), 200);
            }else {
                return response()->json(array('status' => False, 'message_code' => 'No API Key Generated'), 200);
            }

        }else {
            return response()->json(array('status' => False, 'message_code' => 'INVALID_CRED'), 200);
        }
    }

    //generateApiKey
    public function generateApiKey(Request $request)
    {
        //Check if APi Key Exists
        $checkAPIKey = SMSAPIKey::where('userId', Auth::User()->id)->get()->toArray();

        if (sizeof($checkAPIKey) == 1) {
            //Regenerate
            $updateApiKey = SMSAPIKey::find($checkAPIKey['0']['id']);
            $updateApiKey->apiKey = substr(bcrypt(Auth::User()->id . random_int(100, 9999) . Auth::User()->created_at), 10, 26);
            $updateApiKey->update();

        }else {
            //Generate New
            $newApiKey = new SMSAPIKey();
            $newApiKey->userId = Auth::User()->id;
            $newApiKey->apiKey = substr(bcrypt(Auth::User()->id . "ABCDE" . Auth::User()->created_at), 10, 16);
            $newApiKey->save();
        }

        $message = "New API Key Have Been Generated Successfully. Please Re-Authenticate Mobile Interface to Apply the Changes";
        return redirect()->back()->with(['successMessage' => $message]);
    }

    //Terms
    public function terms()
    {
        return view("pages.terms");
    }

    //Settings
    public function settings(Request $request)
    {
        //Get API Key
        $apiKey = "No Key Generated Yet";
        $apiKeyQ = SMSAPIKey::where('userId', Auth::User()->id)->get()->toArray();

        if (sizeof($apiKeyQ) == 1) {
            $apiKey = $apiKeyQ['0']['apiKey'];
        }

    	return view("pages.settings",
                [
                    'apiKey' => $apiKey
                ]);
    }

    //Index
    public function index()
    {
    	return view("index");
    }

}

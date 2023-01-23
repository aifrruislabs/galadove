<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\SMSAPIKey;
use App\OutgoingSMS;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OutgoingSMSController extends Controller
{
    //resetNotDeliveredMessages
    public function resetNotDeliveredMessages(Request $request)
    {
        //Input Validation
        $this->validate($request,
                [
                    'apiKey' => 'required'
                ]);

        //Verify API Key
        $verifyApiKey = SMSAPIKey::where('apiKey', $request->apiKey)->get()->toArray();

        if (sizeof($verifyApiKey) == 1) {
            //Get All Outgoing SMS with Sent Status 1 and Delivered Status 0
            //Created At More than 120 seconds
            $outgoingSMSListNotDlr = OutgoingSMS::where([
                                ['sentStatus', '=', 1],
                                ['dlrReport', '=', 0],
                                ['created_at', '<', Carbon::now()->subMinutes(1)->toDateTimeString()]
                                ])->get();

            foreach ($outgoingSMSListNotDlr as $outSMS) {
                //Update Sent Status to Zero
                $updateSMS = OutgoingSMS::find($outSMS->id);
                $updateSMS->sentStatus = 0;
                $updateSMS->update();
            }
            return response()->json(array('status' => True), 200);
        }

        return response()->json(array('status' => False, 'message' => 'Invalid API Key'), 200);
    }

    //clearDlrSMS
    public function clearDlrSMS(Request $request)
    {
        //Inputs Validation
        $this->validate($request,
            [
                'apiKey' => 'required',
                'smsId' => 'required'
            ]);

        //Verify API Key
        $verifyApiKey = SMSAPIKey::where('apiKey', $request->apiKey)->get()->toArray();

        if (sizeof($verifyApiKey) == 1) {
            //Clearing Outgoing SMS for Delivered
            $clearDlrOutgoing = OutgoingSMS::find($request->smsId);
            $clearDlrOutgoing->dlrReport = 1;
            $clearDlrOutgoing->update();

            return response()->json(array('status' => True), 201);

        }else {
            return response()->json(array('status' => False, 'message_code' => 'INVALID_API_KEY'), 200);
        }
    }

    //clearSentSMS
    public function clearSentSMS(Request $request)
    {
        //Input Validation
        $this->validate($request,
                [
                    'apiKey' => 'required',
                    'smsId' => 'required'
                ]);

        //Verify API Key
        $verifyApiKey = SMSAPIKey::where('apiKey', $request->apiKey)->get()->toArray();

        if (sizeof($verifyApiKey) == 1) {
            //Clearing Outgoing SMS for Sent
            $clearSentOutgoing = OutgoingSMS::find($request->smsId);
            $clearSentOutgoing->sentStatus = 1;
            $clearSentOutgoing->update();

            return response()->json(array('status' => True), 201);

        }else {
            return response()->json(array('status' => False, 'message_code' => 'INVALID_API_KEY'), 200);
        }
    }

    //outgoingSms
    public function outgoingSms(Request $request)
    {
        $outgoingSMSList = OutgoingSMS::where('userId', Auth::User()->id)
                            ->orderBy('created_at', 'DESC')->paginate(12);

        return view("pages.outgoingSms",
                        [
                            'outgoingSMSList' => $outgoingSMSList
                        ]);
    }

	/**
	 * @OA\GET(
	 *     path="/api/v1/get/action/outgoing/sms",
	 *     tags={"OUTGOING_SMS"},
	 *     summary="Get SMS from Mobile Interface to send them to End Users",
	 *     description="Get SMS from Mobile Interface to send them to End Users",
	 *
	 *     @OA\Parameter(
     *          name="apiKey",
     *          in="query",
     *          required=true,
     *          description="Enter API Key",
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
	//getActionOutgoingSMS
	public function getActionOutgoingSMS(Request $request)
	{
		//Input Validation
		$this->validate($request,
				[
					'apiKey' => 'required'
				]);

		//Verify API Key
		$verifyApiKey = SMSAPIKey::where('apiKey', $request->apiKey)->get()->toArray();

		if (sizeof($verifyApiKey) == 1) {
			//Get Outgoing SMS for this apiKey
			$userId = $verifyApiKey['0']['userId'];

			$OutgoingSMSList = OutgoingSMS::where([
                                    ['userId', '=', $userId],
                                    ['sentStatus', '=', 0],
                                ])->orderBy('created_at', 'DESC')
                                ->limit(10)->get();

            return response()->json(array('outgoing_list' => $OutgoingSMSList, 'status' => True), 200);

		}else {
			return response()->json(array('status' => False, 'message' => 'Invalid API Key'), 403);
		}

	}

	/**
	 * @OA\POST(
	 *     path="/api/v1/receive/action/send/sms",
	 *     tags={"OUTGOING_SMS"},
	 *     summary="Send Your SMS from Your App to this API to Get it In Dove SMS Lifecyle",
	 *     description="Send Your SMS from Your App to this API to Get it In Dove SMS Lifecyle",
	 *
	 *     @OA\Parameter(
     *          name="apiKey",
     *          in="query",
     *          required=true,
     *          description="Enter API Key",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
 	 *
 	 *     @OA\Parameter(
     *          name="phoneNumber",
     *          in="query",
     *          required=true,
     *          description="Enter Recepient Phone Number example : +255712100100",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *
 	 *     @OA\Parameter(
     *          name="messageContent",
     *          in="query",
     *          required=true,
     *          description="Enter Message Content to Sent to End Users",
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
    //receiveActionSendSMS
    public function receiveActionSendSMS(Request $request)
    {
    	//Input Validation
    	$this->validate($request,
    		[
    			'apiKey' => 'required',
    			'phoneNumber' => 'required',
    			'messageContent' => 'required'
    		]);

    	//Verify API Key
    	$verifyApiKey = SMSAPIKey::where('apiKey', $request->apiKey)->get()->toArray();

    	if (sizeof($verifyApiKey) == 1) {
    		//Get User ID
    		$userId = $verifyApiKey['0']['userId'];

    		//Add New SMS in Outgoing SMS Queue
    		$newOutgoingSMS = new OutgoingSMS();
    		$newOutgoingSMS->userId = $userId;
    		$newOutgoingSMS->phoneNumber = $request->phoneNumber;
    		$newOutgoingSMS->messageContent = $request->messageContent;
    		$newOutgoingSMS->sentStatus = 0;
    		$newOutgoingSMS->dlrReport = 0;

    		if ($newOutgoingSMS->save()) {
    			return response()->json(array('status' => True, 'smsId' => $newOutgoingSMS->id), 201);
    		}else {
    			return response()->json(array('status' => False, 'message' => 'Server Error'), 200);
    		}
    	}else {
    		return response()->json(array('status' => False, 'message' => 'Invalid API Key'), 403);
    	}

    }
}

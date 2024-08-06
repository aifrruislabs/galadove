package com.aifrruislabs.apps.galadove.utils;

import android.app.PendingIntent;
import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.Intent;
import android.content.IntentFilter;
import android.telephony.SmsManager;
import android.util.Log;
import android.widget.Toast;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;

import java.util.HashMap;
import java.util.Map;

import androidx.annotation.Nullable;

public class OutgoingSMS {

    private Context context;
    private SmsManager smsManager;
    private SessionManager sessionManager;

    private String SENT_ACTION = "SMS_SENT_ACTION";
    private String DELIVERED_ACTION = "SMS_DELIVERED_ACTION";

    //Constructor
    public OutgoingSMS(Context context, SessionManager sessionManager) {
        this.context = context;
        this.sessionManager = sessionManager;
        smsManager = SmsManager.getDefault();
    }

    //Send SMS
    public boolean sendSMS(String smsId, String apiKey, String phoneNumber, String messageContent) {

        //Put Request Codes to smsId so We can Track
        //Which Message was Sent and What was Delivered as well
        PendingIntent sentIntent = PendingIntent.getBroadcast(context, 100,  new
                Intent(SENT_ACTION).putExtra("smsId", smsId),  PendingIntent.FLAG_IMMUTABLE);

        PendingIntent deliveryIntent = PendingIntent.getBroadcast(context, 200, new
                Intent(DELIVERED_ACTION).putExtra("smsId", smsId), PendingIntent.FLAG_IMMUTABLE);

        //Register SENT ACTION Receiver
        context.registerReceiver(new BroadcastReceiver() {
            @Override
            public void onReceive(Context context, Intent intent) {
                //Clear Sent SMS
                clearSentSMS(smsId, apiKey);

                //Log Message
                Log.d("SMS_ACTION", "SMS Sent : " + intent.getExtras().getString("smsId"));
            }
        }, new IntentFilter(SENT_ACTION));


        //Register DELIVERY ACTION Receiver
        context.registerReceiver(new BroadcastReceiver() {
            @Override
            public void onReceive(Context context, Intent intent) {
                //Clear Delivered SMS
                clearDeliveredSMS(smsId, apiKey);

                //Log Message
                Log.d("SMS_ACTION", "SMS Delivered : " + intent.getExtras().getString("smsId"));
            }
        }, new IntentFilter(DELIVERED_ACTION));

        try {
            smsManager.sendTextMessage(phoneNumber,null, messageContent, sentIntent, deliveryIntent);
            Toast.makeText(context,"Message Sent", Toast.LENGTH_LONG).show();
        }catch (Exception e)
        {
            Toast.makeText(context,"Some fields are Empty",Toast.LENGTH_LONG).show();
        }

        return true;
    }

    //Clear Delivered SMS
    private void clearDeliveredSMS(String smsId, String apiKey) {
        StringRequest clearDeliveredSMSRequest = new StringRequest(Request.Method.POST, sessionManager.getServerUrl() + ServerParam.postDeliveredSMSUrl,
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        //Toasting
                        //Toast.makeText(context, context.getString(R.string.clear_sms_success), Toast.LENGTH_SHORT).show();
                    }
                },

                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        //Toasting
                        //Toast.makeText(context, context.getString(R.string.clearing_sms_failure), Toast.LENGTH_SHORT).show();
                    }
                }) {
            @Nullable
            @Override
            protected Map<String, String> getParams() {
                HashMap<String, String> params = new HashMap<>();
                params.put("apiKey", apiKey);
                params.put("smsId", smsId);
                return params;
            }
        };

        //Adding Request to Request Queue
        RequestQueue clearDlrSMSQueue = Volley.newRequestQueue(context);
        clearDlrSMSQueue.getCache().clear();
        clearDlrSMSQueue.add(clearDeliveredSMSRequest);
    }

    //Clear Success Sent SMS
    private void clearSentSMS(String smsId, String apiKey) {
        StringRequest clearSentSMSRequest = new StringRequest(Request.Method.POST, sessionManager.getServerUrl() + ServerParam.postSentSMSUrl,
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        //Toasting
                        //Toast.makeText(context, context.getString(R.string.clear_sms_success), Toast.LENGTH_SHORT).show();
                    }
                },

                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        //Toasting
                        //Toast.makeText(context, context.getString(R.string.clearing_sms_failure), Toast.LENGTH_SHORT).show();
                    }
                }) {
            @Nullable
            @Override
            protected Map<String, String> getParams() {
                HashMap<String, String> params = new HashMap<>();
                params.put("apiKey", apiKey);
                params.put("smsId", smsId);
                return params;
            }
        };

        //Adding Request to Request Queue
        RequestQueue clearSMSQueue = Volley.newRequestQueue(context);
        clearSMSQueue.getCache().clear();
        clearSMSQueue.add(clearSentSMSRequest);
    }
}

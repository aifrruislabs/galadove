package com.aifrruislabs.apps.galadove;

import androidx.appcompat.app.AppCompatActivity;
import galadove.R;

import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.view.Menu;
import android.view.MenuInflater;
import android.view.MenuItem;
import android.view.WindowManager;
import android.widget.TextView;
import android.widget.Toast;

import com.aifrruislabs.apps.galadove.utils.Laviel;
import com.aifrruislabs.apps.galadove.utils.OutgoingSMS;
import com.aifrruislabs.apps.galadove.utils.ServerParam;
import com.aifrruislabs.apps.galadove.utils.SessionManager;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

public class MainActivity extends AppCompatActivity {

    private Laviel laviel;
    private TextView activitiesTextView;

    private OutgoingSMS outgoingSMS;
    private Thread getSyncSendSMSThread;
    private SessionManager sessionManager;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        //Sentry.captureMessage("testing SDK setup");

        //Keep Screen On
        getWindow(). addFlags (WindowManager.LayoutParams.FLAG_KEEP_SCREEN_ON);

        //Casting Views
        activitiesTextView = (TextView) findViewById(R.id.activitiesTextView);

        //Inits
        sessionManager = new SessionManager(this);
        outgoingSMS = new OutgoingSMS(this, sessionManager);

        laviel = new Laviel(this, sessionManager);

        //Get Sync Send SMS Thread
        getSyncSendSMSThread = new Thread() {
            @Override
            public void run() {
                while (Boolean.TRUE) {
                    try {
                        getSMSAndSendThem();

                        //Sleep 2 Seconds
                        sleep( 2 *1000);

                        runOnUiThread(new Runnable() {
                            @Override
                            public void run() {
                                activitiesTextView.setText("Waiting for 10 Seconds Before Next Check");
                            }
                        });

                        //Sleep 10 Seconds
                        sleep(10 * 1000);

                        Log.d("GALA_TAG", "Sending SMS Now");

                    }catch (InterruptedException interruptedException) {
                        //Send Error to Senty
                        interruptedException.printStackTrace();
                    }


                }
            }
        };

        getSyncSendSMSThread.start();
    }


    //Get Sync SMS Thread
    private void getSMSAndSendThem() {
        runOnUiThread(new Runnable() {
            @Override
            public void run() {
                activitiesTextView.setText("Sending Request To Get SMS");
            }
        });

        StringRequest getSMSRequest = new StringRequest(Request.Method.GET, sessionManager.getServerUrl() + ServerParam.getSMSListToSendUrl + "?apiKey=" + sessionManager.getApiKey(),
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {

                        JSONObject jsonObject;

                        try {
                            jsonObject = new JSONObject(response);

                            JSONArray jsonArray = jsonObject.getJSONArray("outgoing_list");

                            for (int i = 0; i < jsonArray.length(); i++) {
                                JSONObject smsObject = jsonArray.getJSONObject(i);

                                String smsId = smsObject.getString("id");
                                String phoneNumber = smsObject.getString("phoneNumber");
                                String messageContent = smsObject.getString("messageContent");

                                runOnUiThread(new Runnable() {
                                    @Override
                                    public void run() {
                                        activitiesTextView.setText("Sending SMS to " + phoneNumber);
                                    }
                                });

                                outgoingSMS.sendSMS(smsId, sessionManager.getApiKey(), phoneNumber, messageContent);

                            }

                        }catch (JSONException jsonException) {
                            //Send to Sentry Exception
                            jsonException.printStackTrace();
                        }

                    }
                },

                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        Log.d("GALA_TAG", " Our Error : " + error.getMessage());

                        //Toast
                        Toast.makeText(MainActivity.this, getString(R.string.network_error), Toast.LENGTH_SHORT).show();
                    }
                });

        //Add Request to Request Queue
        RequestQueue smsSendQue = Volley.newRequestQueue(getBaseContext());
        smsSendQue.getCache().clear();
        smsSendQue.add(getSMSRequest);
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        MenuInflater inflater = getMenuInflater();
        inflater.inflate(R.menu.primary_menu, menu);
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        switch (item.getItemId()) {
            case R.id.logout:
                laviel.logout();
                return true;

            default:
                return super.onOptionsItemSelected(item);
        }
    }


    @Override
    protected void onPause() {
        Log.d("GALA_TAG", "The On Pause Called");

        super.onPause();
        //Restart Activity

        restartActivity();
    }

    @Override
    protected void onStop() {
        Log.d("GALA_TAG", "The On Stop Called");

        super.onStop();
        //Restart Activity

        restartActivity();
    }

    public void restartActivity() {
        //Restart This Intent
        Intent mainActivityIntent = new Intent(getBaseContext(), MainActivity.class);
        startActivity(mainActivityIntent);
    }
}
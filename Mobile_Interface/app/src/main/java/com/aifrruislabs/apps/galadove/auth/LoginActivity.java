package com.aifrruislabs.apps.galadove.auth;

import androidx.annotation.Nullable;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.app.ActivityCompat;
import galadove.R;

import android.Manifest;
import android.app.Activity;
import android.app.ProgressDialog;
import android.content.Intent;
import android.content.pm.PackageManager;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

import com.aifrruislabs.apps.galadove.MainActivity;
import com.aifrruislabs.apps.galadove.utils.ServerParam;
import com.aifrruislabs.apps.galadove.utils.SessionManager;
import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;

import org.json.JSONException;
import org.json.JSONObject;

import java.util.HashMap;
import java.util.Map;

public class LoginActivity extends AppCompatActivity {

    private EditText serverUrlEditText;
    private EditText smartphoneIdEditText;
    private EditText usernameEditText;
    private EditText passwordEditText;
    private Button loginButton;
    private ProgressDialog progressDialog;

    private Button sendTestSMS;

    private SessionManager sessionManager;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_login);

        sessionManager = new SessionManager(this);
        progressDialog = new ProgressDialog(this);

        //Check for Read and Send SMS Permissions
        verifyStoragePermissions(this);

        //Casting Views
        serverUrlEditText = (EditText) findViewById(R.id.serverUrlEditText);
        smartphoneIdEditText = (EditText) findViewById(R.id.smartphoneIdEditText);
        usernameEditText = (EditText) findViewById(R.id.usernameEditText);
        passwordEditText = (EditText) findViewById(R.id.passwordEditText);
        loginButton = (Button) findViewById(R.id.loginButton);

        //Setting on Event Click Listener on loginButton
        loginButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                String serverUrl = serverUrlEditText.getText().toString();
                String smartphoneId = smartphoneIdEditText.getText().toString();
                String username = usernameEditText.getText().toString();
                String password = passwordEditText.getText().toString();

                if ( (!serverUrl.isEmpty()) && (!smartphoneId.isEmpty()) && (!username.isEmpty()) && (!password.isEmpty()) ) {
                    //Store Server Url
                    sessionManager.storeServerUrl(serverUrl);

                    performAuthLogin(smartphoneId, username, password, progressDialog);
                }else {
                    //Toast
                    Toast.makeText(LoginActivity.this, getString(R.string.fillInputsError), Toast.LENGTH_SHORT).show();
                }
            }
        });
    }

    private static String[] PERMISSIONS_LIST = {
            Manifest.permission.READ_PHONE_STATE,
            Manifest.permission.READ_SMS,
            Manifest.permission.RECEIVE_SMS,
            Manifest.permission.SEND_SMS,
            Manifest.permission.SMS_FINANCIAL_TRANSACTIONS,
            Manifest.permission.BROADCAST_SMS
    };

    //and the verifyStoragePermissions methode is
    public static void verifyStoragePermissions(Activity activity) {
        int readPhoneStatePermission = ActivityCompat.checkSelfPermission(activity, Manifest.permission.READ_PHONE_STATE);
        int smsReadPermission = ActivityCompat.checkSelfPermission(activity, Manifest.permission.READ_SMS);
        int smsReceivePermission = ActivityCompat.checkSelfPermission(activity, Manifest.permission.RECEIVE_SMS);
        int smsSendPermission = ActivityCompat.checkSelfPermission(activity, Manifest.permission.SEND_SMS);
        int smsFinancePermission = ActivityCompat.checkSelfPermission(activity, Manifest.permission.SMS_FINANCIAL_TRANSACTIONS);
        int smsBroadcastPermission = ActivityCompat.checkSelfPermission(activity, Manifest.permission.BROADCAST_SMS);

        if (readPhoneStatePermission != PackageManager.PERMISSION_GRANTED &&
                smsReadPermission != PackageManager.PERMISSION_GRANTED &&
                smsReceivePermission != PackageManager.PERMISSION_GRANTED &&
                smsSendPermission != PackageManager.PERMISSION_GRANTED  &&
                smsFinancePermission != PackageManager.PERMISSION_GRANTED &&
                smsBroadcastPermission != PackageManager.PERMISSION_GRANTED ) {

            //Request Permissions
            ActivityCompat.requestPermissions(activity, PERMISSIONS_LIST,  1561);
        }
    }

    //Perform Auth Op
    private void performAuthLogin(String smartphoneId, String username, String password, ProgressDialog progressDialog) {

        //Show Progress Dialog
        progressDialog.setMessage(getString(R.string.verifying_credentials));
        progressDialog.show();

        StringRequest loginRequest = new StringRequest(Request.Method.POST, sessionManager.getServerUrl() + ServerParam.loginUrl,
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {

                        //Dismiss Progress Dialog
                        progressDialog.dismiss();

                        //Get Response
                        JSONObject jsonObject;

                        try {
                            jsonObject = new JSONObject(response);

                            if (jsonObject.getBoolean("status")) {
                                //Toasting
                                Toast.makeText(LoginActivity.this, getString(R.string.correct_credentials), Toast.LENGTH_SHORT).show();

                                //Get API Key to Session Manager
                                String apiKey = jsonObject.getString("apiKey");

                                //Add to Session Manager
                                sessionManager.storeApiKey(apiKey);
                                sessionManager.storeUserLogin(username);

                                //Go to Main Activity
                                Intent mainActivityIntent = new Intent(getBaseContext(), MainActivity.class);
                                startActivity(mainActivityIntent);

                            }else {
                                //Toasting
                                Toast.makeText(LoginActivity.this, getString(R.string.invalid_login), Toast.LENGTH_SHORT).show();
                            }
                        }catch (JSONException jsonException) {
                            //Send Error to Sentry
                            jsonException.printStackTrace();
                        }

                    }
                },

                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        //Dismiss Progress Dialog
                        progressDialog.dismiss();

                        //Toasting
                        Toast.makeText(LoginActivity.this, getString(R.string.loginError), Toast.LENGTH_SHORT).show();
                    }
                }) {
            @Nullable
            @Override
            protected Map<String, String> getParams() throws AuthFailureError {
                HashMap<String, String> params = new HashMap<>();
                params.put("smartPhoneId", smartphoneId);
                params.put("username", username);
                params.put("password", password);
                return params;
            }
        };

        //Adding Request to Request Queue
        RequestQueue reqQue = Volley.newRequestQueue(getBaseContext());
        reqQue.getCache().clear();
        reqQue.add(loginRequest);

    }
}
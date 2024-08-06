package com.aifrruislabs.apps.galadove.init;

import androidx.appcompat.app.AppCompatActivity;
import galadove.R;

import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.Toast;

import com.aifrruislabs.apps.galadove.MainActivity;
import com.aifrruislabs.apps.galadove.auth.LoginActivity;
import com.aifrruislabs.apps.galadove.utils.SessionManager;

import org.json.JSONException;
import org.json.JSONObject;

public class SplashScreen extends AppCompatActivity {
    private Thread delayThread;
    private SessionManager sessionManager;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_splash_screen);

        //Initialize Session Manager
        sessionManager = new SessionManager(this);

        //Initialize Delay Thread
        delayThread = new Thread() {
            @Override
            public void run() {
                super.run();

                try {
                    sleep(3000);
                }catch (InterruptedException iEx) {
                    iEx.printStackTrace();
                    //Send to Sentry
                }finally {
                    if (sessionManager.getCurrentUser() == null) {
                        //Go to Login Activity
                        Intent loginIntent = new Intent(getBaseContext(), LoginActivity.class);
                        startActivity(loginIntent);
                    }else {
                        //Go to Dashboard Activity
                        Intent mainActivityIntent = new Intent(getBaseContext(), MainActivity.class);
                        startActivity(mainActivityIntent);
                    }
                }
            }
        };

        delayThread.start();

    }
}
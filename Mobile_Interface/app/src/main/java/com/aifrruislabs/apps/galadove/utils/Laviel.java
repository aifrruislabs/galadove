package com.aifrruislabs.apps.galadove.utils;

import android.content.Context;
import android.content.Intent;

import com.aifrruislabs.apps.galadove.auth.LoginActivity;

public class Laviel {

    private Context context;
    private SessionManager sessionManager;

    public Laviel(Context context, SessionManager sessionManager) {
        this.context = context;
        this.sessionManager = sessionManager;
    }

    public void logout() {
        //Clear User Session
        sessionManager.removeUserSession();

        //Go to Login Screen
        Intent loginIntent = new Intent(context, LoginActivity.class);
        loginIntent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
        context.startActivity(loginIntent);
    }

}

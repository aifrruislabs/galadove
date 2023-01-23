package com.aifrruislabs.apps.galadove.utils;

import android.content.Context;
import android.content.SharedPreferences;
import android.preference.PreferenceManager;

public class SessionManager {

    private Context context;

    //Constructor
    public SessionManager(Context context) {
        this.context = context;
    }

    //Get Current User
    public String getCurrentUser() {
        SharedPreferences prefs = PreferenceManager.getDefaultSharedPreferences(context);
        return prefs.getString("DOVE_SMS_USER_NAME", null);
    }

    //Store User
    public boolean storeUserLogin(String username) {
        //Username = userid-deviceid-randomid
        SharedPreferences preferences = PreferenceManager.getDefaultSharedPreferences(context);
        SharedPreferences.Editor prefsEditor = preferences.edit();
        prefsEditor.putString("DOVE_SMS_USER_NAME", username);
        prefsEditor.apply();
        return true;
    }

    //Remove Dove User from Session
    public boolean removeUserSession() {
        SharedPreferences preferences = PreferenceManager.getDefaultSharedPreferences(context);
        SharedPreferences.Editor prefsEditor = preferences.edit();
        prefsEditor.remove("DOVE_SMS_USER_NAME");
        prefsEditor.apply();
        return true;
    }

    //Get API Key
    public String getApiKey() {
        SharedPreferences prefs = PreferenceManager.getDefaultSharedPreferences(context);
        return prefs.getString("DOVE_SMS_API_KEY", null);
    }

    //Store Api Key
    public void storeApiKey(String apiKey) {
        //Username = userid-deviceid-randomid
        SharedPreferences preferences = PreferenceManager.getDefaultSharedPreferences(context);
        SharedPreferences.Editor prefsEditor = preferences.edit();
        prefsEditor.putString("DOVE_SMS_API_KEY", apiKey);
        prefsEditor.apply();
    }

    //Store Server URL
    public void storeServerUrl(String serverUrl) {
        SharedPreferences preferences = PreferenceManager.getDefaultSharedPreferences(context);
        SharedPreferences.Editor prefsEditor = preferences.edit();
        prefsEditor.putString("DOVE_SERVER_URL", serverUrl);
        prefsEditor.apply();
    }

    //Get Server URL
    public String getServerUrl() {
        SharedPreferences prefs = PreferenceManager.getDefaultSharedPreferences(context);
        return prefs.getString("DOVE_SERVER_URL", null);
    }
}

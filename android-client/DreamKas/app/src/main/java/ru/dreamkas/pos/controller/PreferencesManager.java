package ru.dreamkas.pos.controller;

import android.content.Context;
import android.content.SharedPreferences;
import android.preference.PreferenceManager;

public class PreferencesManager{
    private static final String CURRENT_STORE_ID = "current_store_id";
    private static final String TOKEN1 = "token";

    private static PreferencesManager sInstance;
    private final SharedPreferences sharedPreferences;

    private PreferencesManager(Context context){
        sharedPreferences = PreferenceManager.getDefaultSharedPreferences(context);
    }

    public static synchronized void initializeInstance(Context context){
        if (sInstance == null && context != null){
            sInstance = new PreferencesManager(context);
        }

        if(context == null){
            sInstance = null;
        }
    }

    public static synchronized PreferencesManager getInstance(){

        if (sInstance == null){
            throw new IllegalStateException(PreferencesManager.class.getSimpleName() + " is not initialized, call initializeInstance(..) method first.");
        }
        return sInstance;
    }

    public String getCurrentStore(){
        return sharedPreferences.getString(CURRENT_STORE_ID, null);
    }

    public void setCurrentStore(String currentStoreId){
        setValue(CURRENT_STORE_ID, currentStoreId);
    }

    public void removeCurrentStore(){
        sharedPreferences.edit().remove(CURRENT_STORE_ID).apply();
    }

    public String getToken(){
        return sharedPreferences.getString(TOKEN1, "");
    }

    public void setToken(String token){
        setValue(TOKEN1, token);
    }

    public void removeToken(){
        sharedPreferences.edit().remove(TOKEN1).apply();
    }

    public void clear(){
        sharedPreferences.edit().clear().apply();
    }

    private void setValue(String key, String value){
        SharedPreferences.Editor editor = sharedPreferences.edit();
        editor.putString(key, value);
        editor.apply();
    }
}

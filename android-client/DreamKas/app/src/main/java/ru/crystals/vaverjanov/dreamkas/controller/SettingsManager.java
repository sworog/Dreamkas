package ru.crystals.vaverjanov.dreamkas.controller;

import android.content.Context;
import android.content.SharedPreferences;
import android.preference.PreferenceManager;

public class SettingsManager
{
    Context context;
    SharedPreferences preferences;

    public SettingsManager(Context context)
    {
        this.context = context;
    }

    public String getCurrentStore()
    {
        preferences = PreferenceManager.getDefaultSharedPreferences(context);
        return preferences.getString("current_store", "");
    }

    public void setCurrentStore(String storeId)
    {
        SharedPreferences.Editor editor = preferences.edit();
        editor.putString("current_store", storeId).apply();
    }



}

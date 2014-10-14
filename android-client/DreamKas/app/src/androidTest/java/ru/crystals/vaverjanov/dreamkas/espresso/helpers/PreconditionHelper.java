package ru.crystals.vaverjanov.dreamkas.espresso.helpers;

import android.content.Context;
import android.content.Intent;
import ru.crystals.vaverjanov.dreamkas.controller.requests.AuthRequest_;
import ru.crystals.vaverjanov.dreamkas.controller.PreferencesManager;
import ru.crystals.vaverjanov.dreamkas.controller.requests.AuthRequest;
import ru.crystals.vaverjanov.dreamkas.model.api.AuthObject;
import ru.crystals.vaverjanov.dreamkas.model.api.Token;

public class PreconditionHelper
{
    public Intent login(Context context) throws Exception
    {
        AuthObject ao = new AuthObject("webfront_webfront", "owner@lighthouse.pro", "lighthouse", "secret");

        AuthRequest authRequest = AuthRequest_.getInstance_(context);
        authRequest.setCredentials(ao);
        Token response = authRequest.loadDataFromNetwork();

        Intent intent = new Intent();
        intent.putExtra("access_token", response.getAccess_token());
        return intent;
    }

    public void clearPreference(Context context)
    {
        PreferencesManager.initializeInstance(context);
        PreferencesManager preferences = PreferencesManager.getInstance();
        preferences.clear();
    }


}

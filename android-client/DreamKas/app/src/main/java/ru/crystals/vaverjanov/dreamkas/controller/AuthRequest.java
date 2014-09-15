package ru.crystals.vaverjanov.dreamkas.controller;

import android.util.Log;

import com.google.android.apps.common.testing.ui.espresso.IdlingResource;
import com.octo.android.robospice.request.SpiceRequest;
import com.octo.android.robospice.request.listener.RequestProgress;
import com.octo.android.robospice.request.listener.RequestStatus;

import org.androidannotations.annotations.EBean;
import org.androidannotations.annotations.rest.RestService;

import ru.crystals.vaverjanov.dreamkas.model.AuthObject;
import ru.crystals.vaverjanov.dreamkas.model.Token;

@EBean
public class AuthRequest extends SpiceRequest<Token>
{
    @RestService
    LighthouseRestClient restClient;

    private AuthObject authObject;

    public AuthRequest()
    {
        super(Token.class);
    }


    public void setCredentials(AuthObject authObject)
    {
        this.authObject = authObject;
    }

    @Override
    public Token loadDataFromNetwork() throws Exception
    {
        Token token = restClient.Auth(authObject);
        return token;
    }

    public void setRestClient(LighthouseRestClient_ restClient)
    {
        this.restClient = restClient;
    }


    //private RequestStatus status = RequestStatus.LOADING_FROM_NETWORK;


}

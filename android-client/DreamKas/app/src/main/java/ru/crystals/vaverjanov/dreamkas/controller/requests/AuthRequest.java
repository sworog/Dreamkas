package ru.crystals.vaverjanov.dreamkas.controller.requests;

import com.octo.android.robospice.request.SpiceRequest;

import org.androidannotations.annotations.EBean;
import org.androidannotations.annotations.rest.RestService;

import ru.crystals.vaverjanov.dreamkas.controller.ILighthouseRestClient;
import ru.crystals.vaverjanov.dreamkas.model.api.AuthObject;
import ru.crystals.vaverjanov.dreamkas.model.api.Token;

@EBean
public class AuthRequest extends SpiceRequest<Token>
{
    @RestService
    ILighthouseRestClient restClient;

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



    /*public void setRestClient(LighthouseRestClient_ restClient)
    {
        this.restClient = restClient;
    }*/


    //private RequestStatus status = RequestStatus.LOADING_FROM_NETWORK;


}

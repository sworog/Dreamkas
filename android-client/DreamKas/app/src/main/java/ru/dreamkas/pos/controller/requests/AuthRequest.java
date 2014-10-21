package ru.dreamkas.pos.controller.requests;

import com.octo.android.robospice.request.SpiceRequest;

import org.androidannotations.annotations.EBean;
import org.androidannotations.annotations.rest.RestService;

import ru.dreamkas.pos.controller.DreamkasRestClient;
import ru.dreamkas.pos.model.api.AuthObject;
import ru.dreamkas.pos.model.api.Token;

@EBean
public class AuthRequest extends SpiceRequest<Token>{
    @RestService
    DreamkasRestClient restClient;

    private AuthObject authObject;

    public AuthRequest(){
        super(Token.class);
    }

    public void setCredentials(AuthObject authObject){
        this.authObject = authObject;
    }

    @Override
    public Token loadDataFromNetwork() throws Exception{

        Token token = restClient.Auth(authObject);
        return token;
    }
}

package ru.dreamkas.pos.controller.listeners.request;

import com.octo.android.robospice.persistence.exception.SpiceException;
import com.octo.android.robospice.request.listener.RequestProgress;
import com.octo.android.robospice.request.listener.RequestProgressListener;
import ru.dreamkas.pos.model.api.Token;

public class AuthRequestListener extends ExtRequestListener<Token> implements RequestProgressListener{

    private final IAuthRequestHandler managedActivity;

    public AuthRequestListener(IAuthRequestHandler activity){
        managedActivity = activity;
    }

    @Override
    public void onRequestSuccess(Token authResult){
        //do some logic here
        managedActivity.onAuthSuccessRequest(authResult);
        super.onRequestSuccess(authResult);
    }

    @Override
    public void onRequestFailure(SpiceException spiceException){
        managedActivity.onAuthFailureRequest(spiceException);
        super.onRequestFailure(spiceException);
    }
}
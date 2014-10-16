package ru.crystals.vaverjanov.dreamkas.controller.listeners.request;

import com.octo.android.robospice.persistence.exception.SpiceException;
import com.octo.android.robospice.request.listener.RequestProgressListener;
import ru.crystals.vaverjanov.dreamkas.model.api.Token;

public class AuthRequestListener extends ExtRequestListener<Token> implements RequestProgressListener{

    private final IAuthRequestHandler managedActivity;


    public AuthRequestListener(IAuthRequestHandler activity)
    {
        managedActivity = activity;
    }

    @Override
    public void onRequestSuccess(Token authResult)
    {
        //do some logic here
        managedActivity.onAuthSuccessRequest(authResult);
        super.onRequestSuccess(authResult);
    }

    @Override
    public void onRequestFailure(SpiceException spiceException)
    {
        managedActivity.onAuthFailureRequest(spiceException);
        super.onRequestFailure(spiceException);
    }

    /*@Override
    public void onRequestProgressUpdate(RequestProgress progress)
    {
        super.onRequestFailure(spiceException);

    }*/
}
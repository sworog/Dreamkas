package ru.crystals.vaverjanov.dreamkas.controller.listeners;

import android.util.Log;

import com.google.android.apps.common.testing.ui.espresso.IdlingResource;
import com.octo.android.robospice.persistence.exception.SpiceException;
import com.octo.android.robospice.request.listener.RequestListener;
import com.octo.android.robospice.request.listener.RequestProgress;
import com.octo.android.robospice.request.listener.RequestProgressListener;
import com.octo.android.robospice.request.listener.RequestStatus;

import ru.crystals.vaverjanov.dreamkas.model.Token;

public class AuthRequestListener implements RequestListener<Token>, RequestProgressListener{

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
    }

    @Override
    public void onRequestFailure(SpiceException spiceException)
    {
        managedActivity.onAuthFailureRequest(spiceException);
    }

    @Override
    public void onRequestProgressUpdate(RequestProgress progress)
    {
        Log.d("AAA", "dasd");

    }

    public void requestStarted() {

    }
}
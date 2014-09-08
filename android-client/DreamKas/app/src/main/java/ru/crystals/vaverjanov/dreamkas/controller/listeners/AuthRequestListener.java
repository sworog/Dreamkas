package ru.crystals.vaverjanov.dreamkas.controller.listeners;

import com.google.android.apps.common.testing.ui.espresso.IdlingResource;
import com.octo.android.robospice.persistence.exception.SpiceException;
import com.octo.android.robospice.request.listener.RequestListener;

import ru.crystals.vaverjanov.dreamkas.model.Token;

public class AuthRequestListener implements RequestListener<Token>, IdlingResource {

    private final AuthRequestActivity managedActivity;

    public AuthRequestListener(AuthRequestActivity activity)
    {
        managedActivity = activity;
    }

    private boolean isReady;

    @Override
    public void onRequestSuccess(Token authResult)
    {
        isReady=true;
        //do some logic here
        managedActivity.onAuthSuccessRequest(authResult);
    }

    @Override
    public void onRequestFailure(SpiceException spiceException)
    {
        isReady=true;
        managedActivity.onAuthFailureRequest(spiceException);
    }


    private ResourceCallback callback;

    @Override
    public String getName()
    {
        return "authorization";
    }

    @Override
    public boolean isIdleNow()
    {
        boolean isIdle = false;

        if (!isReady) {
            isIdle = false;
            callback.onTransitionToIdle();
        } else {
            isIdle = true;
        }

        return isIdle;
        //return !status.equals(RequestStatus.COMPLETE);
    }

    @Override
    public void registerIdleTransitionCallback(ResourceCallback resourceCallback)
    {
        this.callback = resourceCallback;
    }
}
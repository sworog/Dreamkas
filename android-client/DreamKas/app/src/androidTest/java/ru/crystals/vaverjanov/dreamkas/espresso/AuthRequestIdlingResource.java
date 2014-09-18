package ru.crystals.vaverjanov.dreamkas.espresso;

import com.google.android.apps.common.testing.ui.espresso.IdlingResource;
import com.octo.android.robospice.persistence.exception.SpiceException;
import com.octo.android.robospice.request.listener.RequestListener;
import com.octo.android.robospice.request.listener.RequestProgress;
import com.octo.android.robospice.request.listener.RequestStatus;

import java.util.Date;

import ru.crystals.vaverjanov.dreamkas.controller.listeners.AuthRequestListener;
import ru.crystals.vaverjanov.dreamkas.controller.listeners.IAuthRequestHandler;
import ru.crystals.vaverjanov.dreamkas.model.Token;

public class AuthRequestIdlingResource extends AuthRequestListener implements IdlingResource
{
    boolean isIdle = true;
    private ResourceCallback callback;
    private String salt;

    public AuthRequestIdlingResource(IAuthRequestHandler activity)
    {
        super(activity);
        salt = String.valueOf(new Date().getTime());
    }

    @Override
    public void requestStarted()
    {
        this.isIdle = false;
    }

    @Override
    public void onRequestSuccess(Token authResult)
    {
        super.onRequestSuccess(authResult);

        ready();
    }

    private void ready()
    {
        isIdle = true;
        callback.onTransitionToIdle();
    }

    @Override
    public void onRequestFailure(SpiceException spiceException)
    {
        super.onRequestFailure(spiceException);
        ready();
    }



    @Override
    public String getName()
    {
        return "authorization" + salt;
    }

    @Override
    public boolean isIdleNow()
    {
        return this.isIdle;
    }

    @Override
    public void registerIdleTransitionCallback(ResourceCallback resourceCallback)
    {
        this.callback = resourceCallback;
    }

    @Override
    public void onRequestProgressUpdate(RequestProgress progress)
    {
        //RequestStatus currentStatus = progress.getStatus();
        isIdle = false;
    }
}
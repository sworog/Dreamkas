package ru.crystals.vaverjanov.dreamkas.espresso.helpers;

import com.google.android.apps.common.testing.ui.espresso.IdlingResource;
import java.util.Date;

import ru.crystals.vaverjanov.dreamkas.controller.listeners.request.ExtRequestListener;

public class RequestIdlingResource implements IdlingResource, ExtRequestListener.OnRequestEventListener
{
    boolean isIdle = true;
    private ResourceCallback callback;
    private String salt;

    public RequestIdlingResource(ExtRequestListener requestListener)
    {
        requestListener.setRequestEventListener(this);
        isIdle = requestListener.isInProgress();

        salt = String.valueOf(new Date().getTime());
    }

    private void ready()
    {
        isIdle = true;
        callback.onTransitionToIdle();
    }

    @Override
    public String getName()
    {
        return "request_idling_resource_" + salt;
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
    public void onEvent(ExtRequestListener.RequestEventType eventType)
    {
        switch (eventType)
        {
            case RequestSuccess:
            case RequestFailure:
                ready();
                break;
            case RequestStarted:
            case RequestProgressUpdate:
                this.isIdle = false;
                break;
        }

    }
}
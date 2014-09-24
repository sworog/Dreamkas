package ru.crystals.vaverjanov.dreamkas.controller.listeners.request;

import com.octo.android.robospice.persistence.exception.SpiceException;
import com.octo.android.robospice.request.listener.RequestListener;
import com.octo.android.robospice.request.listener.RequestProgress;
import com.octo.android.robospice.request.listener.RequestProgressListener;

public class ExtRequestListener<T> implements RequestListener<T>, RequestProgressListener
{
    OnRequestEventListener mListener;

    private boolean mIsInProgress = true;

    public boolean isInProgress()
    {
        return mIsInProgress;
    }

    public interface OnRequestEventListener
    {
        public void onEvent(RequestEventType eventType);
    }

    public enum RequestEventType
    {
        RequestSuccess, RequestFailure, RequestProgressUpdate, RequestStarted;
    }

    public void setRequestEventListener(OnRequestEventListener eventListener)
    {
        mListener=eventListener;
    }

    @Override
    public void onRequestSuccess(T result)
    {
        mIsInProgress = true;
        invoke(RequestEventType.RequestSuccess);
    }

    @Override
    public void onRequestFailure(SpiceException spiceException)
    {
        mIsInProgress = true;
        invoke(RequestEventType.RequestFailure);
    }

    @Override
    public void onRequestProgressUpdate(RequestProgress progress)
    {
        mIsInProgress = false;
        invoke(RequestEventType.RequestProgressUpdate);
    }

    public void requestStarted()
    {
        mIsInProgress = false;
        invoke(RequestEventType.RequestStarted);
    }

    private void invoke(RequestEventType requestProgressUpdate)
    {
        if(mListener != null)
        {
            mListener.onEvent(requestProgressUpdate);
        }
    }
}
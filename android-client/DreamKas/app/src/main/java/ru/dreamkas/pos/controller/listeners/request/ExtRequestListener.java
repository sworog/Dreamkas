package ru.dreamkas.pos.controller.listeners.request;

import com.octo.android.robospice.persistence.exception.SpiceException;
import com.octo.android.robospice.request.listener.RequestListener;
import com.octo.android.robospice.request.listener.RequestProgress;
import com.octo.android.robospice.request.listener.RequestProgressListener;

public class ExtRequestListener<T> implements RequestListener<T>, RequestProgressListener{
    OnRequestEventListener mListener;

    private boolean mIsInProgress = false;

    public boolean isInProgress(){
        return mIsInProgress;
    }

    public interface OnRequestEventListener{
        public void onEvent(RequestEventType eventType);
    }

    public enum RequestEventType{
        RequestSuccess, RequestFailure, RequestProgressUpdate, RequestStarted;
    }

    public void setRequestEventListener(OnRequestEventListener eventListener){
        mListener=eventListener;
    }

    @Override
    public void onRequestSuccess(T result){
        mIsInProgress = false;
        invoke(RequestEventType.RequestSuccess);
    }

    @Override
    public void onRequestFailure(SpiceException spiceException){
        mIsInProgress = false;
        invoke(RequestEventType.RequestFailure);
    }

    @Override
    public void onRequestProgressUpdate(RequestProgress progress){
        mIsInProgress = true;
        invoke(RequestEventType.RequestProgressUpdate);
    }

    public void requestStarted(){
        mIsInProgress = true;
        invoke(RequestEventType.RequestStarted);
    }

    private void invoke(RequestEventType requestProgressUpdate){
        if(mListener != null)
        {
            mListener.onEvent(requestProgressUpdate);
        }
    }
}
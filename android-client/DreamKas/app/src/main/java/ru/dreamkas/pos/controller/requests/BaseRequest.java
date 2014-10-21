package ru.dreamkas.pos.controller.requests;

import com.octo.android.robospice.request.SpiceRequest;

import ru.dreamkas.pos.controller.DreamkasRestClient;

public abstract class BaseRequest<T> extends SpiceRequest<T>{
    protected DreamkasRestClient mRestClient;

    public BaseRequest(Class<T> clazz) {
        super(clazz);
    }

    public void init(DreamkasRestClient restClient){
        mRestClient = restClient;
    }
}

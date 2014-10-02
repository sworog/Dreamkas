package ru.crystals.vaverjanov.dreamkas.controller.requests;

import com.octo.android.robospice.request.SpiceRequest;

import ru.crystals.vaverjanov.dreamkas.controller.LighthouseRestClient;

public abstract class BaseRequest<T> extends SpiceRequest<T>
{
    protected LighthouseRestClient mRestClient;

    public BaseRequest(Class<T> clazz) {
        super(clazz);
    }

    public void init(LighthouseRestClient restClient){
        mRestClient = restClient;
    }
}

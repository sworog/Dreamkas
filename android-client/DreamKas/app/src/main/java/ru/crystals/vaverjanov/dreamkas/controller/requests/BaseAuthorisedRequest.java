package ru.crystals.vaverjanov.dreamkas.controller.requests;

import com.octo.android.robospice.request.SpiceRequest;

import org.androidannotations.annotations.EBean;
import org.androidannotations.annotations.rest.RestService;
import ru.crystals.vaverjanov.dreamkas.controller.ILighthouseRestClient;
import ru.crystals.vaverjanov.dreamkas.model.api.NamedObjects;

@EBean
public abstract class BaseAuthorisedRequest extends SpiceRequest<NamedObjects>
{
    @RestService
    protected ILighthouseRestClient restClient;
    protected String token;

    public BaseAuthorisedRequest()
    {
        super(NamedObjects.class);
    }

    public void setToken(String token)
    {
        this.token = token;
        restClient.setHeader("Authorization", "Bearer " + token);
    }
}

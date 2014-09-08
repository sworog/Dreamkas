package ru.crystals.vaverjanov.dreamkas.controller;

import com.octo.android.robospice.request.SpiceRequest;

import org.androidannotations.annotations.Bean;
import org.androidannotations.annotations.EBean;
import org.androidannotations.annotations.rest.RestService;

import java.lang.reflect.ParameterizedType;

import ru.crystals.vaverjanov.dreamkas.model.NamedObjects;
import ru.crystals.vaverjanov.dreamkas.model.Token;

//@Bean
public abstract class BaseAuthorisedRequest<T> extends SpiceRequest<T>
{
    public BaseAuthorisedRequest(Class<T> clazz)
    {
        super(clazz);
    }

  //  @RestService
  //  protected LighthouseRestClient restClient;
    protected String token;

    public void setToken(String token)
    {
        this.token = token;
        //restClient.setHeader("Authorization", "Bearer " + token);
    }

   /* @Override
    public T loadDataFromNetwork() throws Exception
    {
        restClient.setHeader("Authorization", "Bearer " + token);
        return null;
    }*/
}

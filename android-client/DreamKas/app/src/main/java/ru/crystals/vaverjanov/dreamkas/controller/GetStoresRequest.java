package ru.crystals.vaverjanov.dreamkas.controller;

import org.androidannotations.annotations.EBean;
import org.androidannotations.annotations.rest.RestService;

import ru.crystals.vaverjanov.dreamkas.controller.BaseAuthorisedRequest;
import ru.crystals.vaverjanov.dreamkas.controller.LighthouseRestClient;
import ru.crystals.vaverjanov.dreamkas.model.NamedObjects;

@EBean
public class GetStoresRequest extends BaseAuthorisedRequest {

    //@RestService
    //protected LighthouseRestClient restClient;

    @Override
    public NamedObjects loadDataFromNetwork() throws Exception
    {
        //super.loadDataFromNetwork();
        NamedObjects groups = restClient.getStores();
        return groups;
    }
}

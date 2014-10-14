package ru.crystals.vaverjanov.dreamkas.controller.requests;

import org.androidannotations.annotations.EBean;
import org.androidannotations.annotations.rest.RestService;

import ru.crystals.vaverjanov.dreamkas.controller.ILighthouseRestClient;
import ru.crystals.vaverjanov.dreamkas.model.api.NamedObjects;

@EBean
public class GetGroupsRequest extends BaseAuthorisedRequest {

    @RestService
    ILighthouseRestClient restClient;

    @Override
    public NamedObjects loadDataFromNetwork() throws Exception
    {
        NamedObjects groups = restClient.getGroups();
        return groups;
    }
}

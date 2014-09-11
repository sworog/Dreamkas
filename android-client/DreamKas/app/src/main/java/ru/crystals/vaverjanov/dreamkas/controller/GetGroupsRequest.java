package ru.crystals.vaverjanov.dreamkas.controller;

import android.provider.ContactsContract;

import com.octo.android.robospice.request.SpiceRequest;

import org.androidannotations.annotations.EBean;
import org.androidannotations.annotations.rest.RestService;

import java.util.ArrayList;

import ru.crystals.vaverjanov.dreamkas.model.AuthObject;
import ru.crystals.vaverjanov.dreamkas.model.NamedObject;
import ru.crystals.vaverjanov.dreamkas.model.NamedObjects;
import ru.crystals.vaverjanov.dreamkas.model.Token;

@EBean
public class GetGroupsRequest extends BaseAuthorisedRequest<NamedObjects> {

    @RestService
    LighthouseRestClient restClient;

    public GetGroupsRequest()
    {
        super(NamedObjects.class);
    }

    @Override
    public NamedObjects loadDataFromNetwork() throws Exception
    {
        //super.loadDataFromNetwork();
        NamedObjects groups = restClient.getGroups();
        return groups;
    }
}

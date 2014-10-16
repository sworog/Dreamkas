package ru.crystals.vaverjanov.dreamkas.controller.requests;

import org.androidannotations.annotations.EBean;
import ru.crystals.vaverjanov.dreamkas.model.api.NamedObjects;

@EBean
public class GetStoresRequest extends BaseAuthorisedRequest
{
    @Override
    public NamedObjects loadDataFromNetwork() throws Exception
    {
        NamedObjects groups = restClient.getStores();
        return groups;
    }
}

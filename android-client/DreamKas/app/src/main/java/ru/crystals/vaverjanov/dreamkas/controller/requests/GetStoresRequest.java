package ru.crystals.vaverjanov.dreamkas.controller.requests;
import ru.crystals.vaverjanov.dreamkas.model.api.collections.NamedObjects;

public class GetStoresRequest extends BaseRequest<NamedObjects>
{
    public GetStoresRequest(){
        super(NamedObjects.class);
    }

    @Override
    public NamedObjects loadDataFromNetwork() throws Exception
    {
        NamedObjects groups = mRestClient.getStores();
        return groups;
    }
}

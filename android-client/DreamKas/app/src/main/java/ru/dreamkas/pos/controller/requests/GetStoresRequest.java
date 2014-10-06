package ru.dreamkas.pos.controller.requests;
import ru.dreamkas.pos.model.api.collections.NamedObjects;

public class GetStoresRequest extends BaseRequest<NamedObjects>{
    public GetStoresRequest(){
        super(NamedObjects.class);
    }

    @Override
    public NamedObjects loadDataFromNetwork() throws Exception{
        NamedObjects groups = mRestClient.getStores();
        return groups;
    }
}

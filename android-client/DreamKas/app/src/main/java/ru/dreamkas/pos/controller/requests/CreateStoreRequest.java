package ru.dreamkas.pos.controller.requests;
import ru.dreamkas.pos.model.api.NamedObject;
import ru.dreamkas.pos.model.api.StoreApiObject;

public class CreateStoreRequest extends BaseRequest<NamedObject>{
    private StoreApiObject mStore;

    public CreateStoreRequest(){
        super(NamedObject.class);
    }

    public void setStore(StoreApiObject store){
        this.mStore = store;
    }

    @Override
    public NamedObject loadDataFromNetwork() throws Exception
    {
        NamedObject store = mRestClient.createStore(mStore);
        return store;
    }
}

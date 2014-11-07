package ru.dreamkas.pos.controller.requests;
import ru.dreamkas.pos.model.api.NamedObject;

public class GetStoreRequest extends BaseRequest<NamedObject>{
    private CharSequence mStoreId;

    public GetStoreRequest(){
        super(NamedObject.class);
    }

    public void setStoreId(CharSequence storeId){
        this.mStoreId = storeId;
    }

    @Override
    public NamedObject loadDataFromNetwork() throws Exception
    {
        NamedObject store = mRestClient.getStore(mStoreId);
        return store;
    }
}

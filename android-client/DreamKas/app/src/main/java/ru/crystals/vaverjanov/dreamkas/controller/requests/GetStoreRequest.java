package ru.crystals.vaverjanov.dreamkas.controller.requests;
import ru.crystals.vaverjanov.dreamkas.model.api.NamedObject;

public class GetStoreRequest extends BaseRequest<NamedObject>
{
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

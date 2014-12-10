package ru.dreamkas.pos.controller.requests;

import ru.dreamkas.pos.model.Receipt;
import ru.dreamkas.pos.model.api.NamedObject;
import ru.dreamkas.pos.model.api.ProductApiObject;
import ru.dreamkas.pos.model.api.ReceiptApiObject;
import ru.dreamkas.pos.model.api.SaleApiObject;

public class RegisterReceiptRequest extends BaseRequest<NamedObject>{
    private ReceiptApiObject mReceipt;
    private CharSequence mStore;

    public RegisterReceiptRequest(){
        super(NamedObject.class);
    }

    public void setReceipt(Receipt receipt){
        this.mReceipt = new ReceiptApiObject(receipt);
    }

    public void setStore(CharSequence store){
        this.mStore = store;
    }

    @Override
    public SaleApiObject loadDataFromNetwork() throws Exception
    {
        SaleApiObject store = mRestClient.registerReceipt(mStore, mReceipt);
        return store;
    }
}

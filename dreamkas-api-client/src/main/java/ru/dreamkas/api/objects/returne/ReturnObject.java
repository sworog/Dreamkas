package ru.dreamkas.api.objects.returne;

import org.json.JSONException;
import ru.dreamkas.api.objects.Store;
import ru.dreamkas.api.objects.abstraction.AbstractProductableObject;
import ru.dreamkas.api.objects.sale.SaleObject;

public class ReturnObject extends AbstractProductableObject {

    private Store store;

    public ReturnObject(String date) throws JSONException {
        super();
        getJsonObject()
                .put("date", date);
    }

    @Override
    public String getApiUrl() {
        return String.format("/stores/%s/returns", store.getId());
    }

    public void setStore(Store store) {
        this.store = store;
    }

    public void setSaleObject(SaleObject saleObject) throws JSONException {
        getJsonObject().put("sale", saleObject.getId());
    }
}

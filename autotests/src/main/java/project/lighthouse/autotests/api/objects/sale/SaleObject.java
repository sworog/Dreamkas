package project.lighthouse.autotests.api.objects.sale;

import org.json.JSONException;
import org.json.JSONObject;
import project.lighthouse.autotests.objects.api.Store;
import project.lighthouse.autotests.objects.api.abstraction.AbstractProductableObject;

public class SaleObject extends AbstractProductableObject {

    private Store store;

    public SaleObject(String date) throws JSONException {
        super();
        getJsonObject()
                .put("date", date)
                .put("payment", new JSONObject());
    }

    @Override
    public String getApiUrl() {
        return String.format("/stores/%s/sales", store.getId());
    }

    public void setStore(Store store) {
        this.store = store;
    }

    public void setAmountTendered(String amountTendered) throws JSONException {
        getJsonObject().getJSONObject("payment").put("amountTendered", amountTendered);
    }

    public void setPaymentMethod(String type) throws JSONException {
        getJsonObject().getJSONObject("payment").put("type", type);
    }
}

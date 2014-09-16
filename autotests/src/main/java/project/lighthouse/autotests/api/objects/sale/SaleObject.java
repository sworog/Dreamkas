package project.lighthouse.autotests.api.objects.sale;

import org.json.JSONException;
import org.json.JSONObject;
import project.lighthouse.autotests.objects.api.Store;
import project.lighthouse.autotests.objects.api.abstraction.AbstractProductableObject;

public class SaleObject extends AbstractProductableObject {

    private Store store;

    public SaleObject(String date, String type, String amountTendered) throws JSONException {
        super();
        getJsonObject()
                .put("date", date)
                .put("payment", new JSONObject()
                        .put("type", type)
                        .put("amountTendered", amountTendered));
    }

    @Override
    public String getApiUrl() {
        return String.format("/stores/%s/sales", store.getId());
    }

    public void setStore(Store store) {
        this.store = store;
    }
}

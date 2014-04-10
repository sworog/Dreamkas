package project.lighthouse.autotests.objects.api.order;

import org.json.JSONException;
import org.json.JSONObject;
import project.lighthouse.autotests.objects.api.abstraction.AbstractProductableObject;

public class Order extends AbstractProductableObject {

    private String storeId;
    private static final String API_URL = "/stores/%s/orders";

    public Order(String supplierId) throws JSONException {
        super(new JSONObject()
                .put("supplier", supplierId)
        );
    }

    @Override
    public String getApiUrl() {
        return String.format(API_URL, storeId);
    }

    public void setStoreId(String storeId) {
        this.storeId = storeId;
    }

    public String getStoreId() {
        return storeId;
    }
}

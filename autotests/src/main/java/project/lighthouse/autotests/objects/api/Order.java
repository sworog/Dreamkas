package project.lighthouse.autotests.objects.api;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;
import project.lighthouse.autotests.objects.api.abstraction.AbstractObject;
import project.lighthouse.autotests.objects.api.abstraction.AbstractProductObject;
import project.lighthouse.autotests.objects.api.interfaces.Productable;

public class Order extends AbstractObject implements Productable {

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

    @Override
    public Productable addProduct(AbstractProductObject product) {
        putProduct(product);
        return this;
    }

    private void putProduct(AbstractProductObject product) {
        try {
            if (getJsonObject().has("products")) {
                JSONArray jsonArray = getJsonObject().getJSONArray("products");
                jsonArray.put(product.getJsonObject());
            } else {
                getJsonObject().put("products", new JSONArray().put(product.getJsonObject()));
            }
        } catch (JSONException e) {
            throw new AssertionError(e);
        }
    }

    public void setStoreId(String storeId) {
        this.storeId = storeId;
    }

    public String getStoreId() {
        return storeId;
    }
}

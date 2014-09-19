package project.lighthouse.autotests.api.objects.stockmovement;

import org.json.JSONArray;
import org.json.JSONException;
import project.lighthouse.autotests.api.objects.abstraction.AbstractProductableObject;

public abstract class StockMovement<T extends StockMovementProduct> extends AbstractProductableObject {

    public StockMovement(String storeId, String date) throws JSONException {
        super();
        jsonObject.put("store", storeId);
        jsonObject.put("date", date);
    }

    public String getNumber() {
        return getPropertyAsString("number");
    }

    protected JSONArray getProductsJSON() throws JSONException {
        if (!jsonObject.has("products")) {
            jsonObject.put("products", new JSONArray());
        }
        return jsonObject.getJSONArray("products");
    }

    public StockMovement putProduct(T product) throws JSONException {

        getProductsJSON().put(product.getJsonObject());
        return this;
    }
}

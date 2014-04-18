package project.lighthouse.autotests.objects.api.abstraction;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

abstract public class AbstractProductableObject extends AbstractObject {

    public AbstractProductableObject(JSONObject jsonObject) {
        super(jsonObject);
    }

    @Override
    public String getApiUrl() {
        return null;
    }

    public void putProducts(AbstractProductObject[] abstractProductObjects) {
        for (AbstractProductObject abstractProductObject : abstractProductObjects) {
            putProduct(abstractProductObject);
        }
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
}

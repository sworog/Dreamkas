package ru.dreamkas.api.objects.abstraction;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

abstract public class AbstractProductableObject extends AbstractObject {

    public AbstractProductableObject(JSONObject jsonObject) {
        super(jsonObject);
    }

    public AbstractProductableObject() {
        super();
    }

    public void putProducts(AbstractProductObject[] abstractProductObjects) {
        for (AbstractProductObject abstractProductObject : abstractProductObjects) {
            putProduct(abstractProductObject);
        }
    }

    public void putProduct(AbstractProductObject product) {
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

package ru.dreamkas.api.objects.returne;

import org.json.JSONException;
import org.json.JSONObject;
import ru.dreamkas.api.objects.abstraction.AbstractProductObject;

public class ReturnProduct extends AbstractProductObject {

    public ReturnProduct(String productId, String quantity) throws JSONException {
        super(new JSONObject()
                .put("product", productId)
                .put("quantity", quantity));
    }
}

package ru.dreamkas.api.objects.sale;

import org.json.JSONException;
import org.json.JSONObject;
import ru.dreamkas.api.objects.abstraction.AbstractProductObject;

public class SaleProduct extends AbstractProductObject {

    public SaleProduct(String productId, String quantity, String price) throws JSONException {
        super(new JSONObject()
                .put("product", productId)
                .put("quantity", quantity)
                .put("price", price));
    }
}

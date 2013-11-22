package project.lighthouse.autotests.objects.api;

import org.json.JSONException;
import org.json.JSONObject;

public class InvoiceProduct {

    public static JSONObject getJsonObject(String product, String quantity, String price) throws JSONException {
        return new JSONObject()
                .put("product", product)
                .put("quantity", quantity)
                .put("priceEntered", price);
    }
}

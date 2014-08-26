package project.lighthouse.autotests.api.objects.stockmovement.writeoff;

import org.json.JSONException;
import org.json.JSONObject;
import project.lighthouse.autotests.api.objects.stockmovement.StockMovementProduct;
import project.lighthouse.autotests.objects.api.abstraction.AbstractProductObject;

public class WriteOffProduct extends StockMovementProduct {

    public WriteOffProduct(String productId, String quantity, String price, String cause) throws JSONException {
        super(productId, quantity, price);
        put("cause", cause);
    }
}

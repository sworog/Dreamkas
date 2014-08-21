package project.lighthouse.autotests.api.objects.stockmovement;

import org.json.JSONException;
import project.lighthouse.autotests.api.objects.ApiObject;

public class StockMovementProduct extends ApiObject {

    public StockMovementProduct(String productId, String quantity, String price) throws JSONException {
        super();
        put("product", productId);
        put("quantity", quantity);
        put("price", price);
    }
}

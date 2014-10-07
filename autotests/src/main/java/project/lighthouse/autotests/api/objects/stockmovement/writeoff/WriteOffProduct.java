package project.lighthouse.autotests.api.objects.stockmovement.writeoff;

import org.json.JSONException;
import project.lighthouse.autotests.api.objects.stockmovement.StockMovementProduct;

public class WriteOffProduct extends StockMovementProduct {

    public WriteOffProduct(String productId, String quantity, String price, String cause) throws JSONException {
        super(productId, quantity, price);
        put("cause", cause);
    }
}

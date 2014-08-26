package project.lighthouse.autotests.api.objects.stockmovement.stockin;

import org.json.JSONException;
import project.lighthouse.autotests.api.objects.stockmovement.StockMovementProduct;

public class StockInProduct extends StockMovementProduct {
    public StockInProduct(String productId, String quantity, String price) throws JSONException {
        super(productId, quantity, price);
    }
}

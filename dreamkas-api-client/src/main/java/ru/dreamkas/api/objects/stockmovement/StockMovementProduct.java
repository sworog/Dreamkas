package ru.dreamkas.api.objects.stockmovement;

import org.json.JSONException;
import ru.dreamkas.api.objects.ApiObject;

public class StockMovementProduct extends ApiObject {

    public StockMovementProduct() {
        super();
    }

    public StockMovementProduct(String productId, String quantity, String price) throws JSONException {
        super();
        put("product", productId);
        put("quantity", quantity);
        put("price", price);
    }
}

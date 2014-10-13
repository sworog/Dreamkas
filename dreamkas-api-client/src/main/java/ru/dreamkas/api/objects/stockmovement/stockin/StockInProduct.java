package ru.dreamkas.api.objects.stockmovement.stockin;

import org.json.JSONException;
import ru.dreamkas.api.objects.stockmovement.StockMovementProduct;

public class StockInProduct extends StockMovementProduct {
    public StockInProduct(String productId, String quantity, String price) throws JSONException {
        super(productId, quantity, price);
    }
}

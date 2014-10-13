package ru.dreamkas.api.objects.stockmovement.stockin;

import org.json.JSONException;
import ru.dreamkas.api.objects.stockmovement.StockMovement;

public class StockIn extends StockMovement<StockInProduct> {

    public StockIn(String storeId, String date) throws JSONException {
        super(storeId, date);
    }

    @Override
    public String getApiUrl() {
        return "/stockIns";
    }

    @SuppressWarnings("unchecked")
    public StockIn putProduct(String productId, String quantity, String price) throws JSONException
    {
        putProduct(new StockInProduct(productId, quantity, price));
        return this;
    }
}

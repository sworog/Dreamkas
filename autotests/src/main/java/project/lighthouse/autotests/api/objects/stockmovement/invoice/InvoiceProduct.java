package project.lighthouse.autotests.api.objects.stockmovement.invoice;

import org.json.JSONException;
import project.lighthouse.autotests.api.objects.stockmovement.StockMovementProduct;

public class InvoiceProduct extends StockMovementProduct {

    public InvoiceProduct(String productId, String quantity, String price) throws JSONException {
        super();
        put("product", productId);
        put("quantity", quantity);
        put("priceEntered", price);
    }
}

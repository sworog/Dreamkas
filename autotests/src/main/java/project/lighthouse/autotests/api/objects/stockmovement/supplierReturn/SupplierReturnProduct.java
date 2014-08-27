package project.lighthouse.autotests.api.objects.stockmovement.supplierReturn;

import org.json.JSONException;
import project.lighthouse.autotests.api.objects.stockmovement.StockMovementProduct;

public class SupplierReturnProduct extends StockMovementProduct {
    public SupplierReturnProduct(String productId, String quantity, String price) throws JSONException {
        super(productId, quantity, price);
    }
}

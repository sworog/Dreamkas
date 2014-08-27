package project.lighthouse.autotests.api.objects.stockmovement.supplierReturn;

import org.json.JSONException;
import project.lighthouse.autotests.api.objects.stockmovement.StockMovement;

public class SupplierReturn extends StockMovement<SupplierReturnProduct> {

    public SupplierReturn(String storeId, String date, Boolean paid, String supplierId) throws JSONException {
        super(storeId, date);
        jsonObject.put("paid", paid);
        jsonObject.put("supplier", supplierId);
    }

    @Override
    public String getApiUrl() {
        return "/supplierReturns";
    }

    public SupplierReturn putProduct(String productId, String quantity, String price) throws JSONException {
        putProduct(new SupplierReturnProduct(productId, quantity, price));
        return this;
    }
}

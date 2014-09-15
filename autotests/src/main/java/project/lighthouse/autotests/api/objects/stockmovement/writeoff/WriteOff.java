package project.lighthouse.autotests.api.objects.stockmovement.writeoff;

import org.json.JSONException;
import project.lighthouse.autotests.api.objects.stockmovement.StockMovement;

public class WriteOff extends StockMovement<WriteOffProduct> {

    public WriteOff(String storeId, String date) throws JSONException {
        super(storeId, date);
    }

    @Override
    public String getApiUrl() {
        return "/writeOffs";
    }

    public WriteOff putProduct(String productId, String quantity, String price, String cause) throws JSONException {
        putProduct(new WriteOffProduct(productId, quantity, price, cause));
        return this;
    }
}

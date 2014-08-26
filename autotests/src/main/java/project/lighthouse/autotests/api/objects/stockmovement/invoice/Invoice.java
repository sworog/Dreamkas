package project.lighthouse.autotests.api.objects.stockmovement.invoice;

import org.json.JSONException;
import project.lighthouse.autotests.api.objects.stockmovement.StockMovement;

public class Invoice extends StockMovement<InvoiceProduct> {

    public Invoice(String date,
                   Boolean paid,
                   String storeId,
                   String supplierId) throws JSONException {
        super(storeId, date);
        jsonObject.put("paid", paid);
        jsonObject.put("supplier", supplierId);
    }

    @Override
    public String getApiUrl() {
        return "/invoices";
    }

    public Invoice putProduct(String productId, String quantity, String price) throws JSONException {
        putProduct(new InvoiceProduct(productId, quantity, price));
        return this;
    }
}

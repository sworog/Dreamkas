package project.lighthouse.autotests.objects.api.invoice;

import org.json.JSONException;
import org.json.JSONObject;
import project.lighthouse.autotests.objects.api.abstraction.AbstractProductableObject;

public class Invoice extends AbstractProductableObject {

    String storeId;

    private static final String API_URL = "/stores/%s/invoices";

    public Invoice(String supplierId, String acceptanceDate, String accepter, String legalEntity,
                   String supplierInvoiceNumber) throws JSONException {
        super(new JSONObject()
                        .put("supplier", supplierId)
                        .put("acceptanceDate", acceptanceDate)
                        .put("accepter", accepter)
                        .put("legalEntity", legalEntity)
                        .put("supplierInvoiceNumber", supplierInvoiceNumber)
                        .put("includesVAT", true)
        );
    }

    @Override
    public String getApiUrl() {
        return String.format(API_URL, storeId);
    }

    public String getSku() throws JSONException {
        return getPropertyAsString("sku");
    }

    public void setStoreId(String storeId) {
        this.storeId = storeId;
    }

    public String getStoreId() {
        return storeId;
    }
}

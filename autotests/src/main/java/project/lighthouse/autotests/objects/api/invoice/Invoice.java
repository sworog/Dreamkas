package project.lighthouse.autotests.objects.api.invoice;

import org.json.JSONException;
import org.json.JSONObject;
import project.lighthouse.autotests.objects.api.Store;
import project.lighthouse.autotests.objects.api.abstraction.AbstractProductableObject;

public class Invoice extends AbstractProductableObject {

    Store store;

    private static final String API_URL = "/invoices";

    @Deprecated
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

    public Invoice(String date,
                   Boolean paid,
                   String storeId,
                   String supplierId) throws JSONException {
        super(new JSONObject()
                        .put("date", date)
                        .put("paid", paid)
                        .put("store", storeId)
                        .put("supplier", supplierId)
        );
    }

    @Override
    public String getApiUrl() {
        return String.format(API_URL);
    }

    public String getSku() throws JSONException {
        return getPropertyAsString("sku");
    }

    public String getNumber() throws JSONException {
        return getPropertyAsString("number");
    }

    public void setStore(Store store) throws JSONException {
        this.store = store;
    }

    public String getStore() {
        return getPropertyAsString("store");
    }
}

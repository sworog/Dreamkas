package project.lighthouse.autotests.objects.api.invoice;

import org.json.JSONException;
import org.json.JSONObject;
import project.lighthouse.autotests.objects.api.Store;
import project.lighthouse.autotests.objects.api.abstraction.AbstractProductableObject;

public class Invoice extends AbstractProductableObject {

    Store store;

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
        return String.format(API_URL, store.getId());
    }

    public String getSku() throws JSONException {
        return getPropertyAsString("sku");
    }

    public void setStore(Store store) throws JSONException {
        this.store = store;
    }

    public Store getStore() {
        return store;
    }
}

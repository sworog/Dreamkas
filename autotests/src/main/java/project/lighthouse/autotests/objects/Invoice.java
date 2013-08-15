package project.lighthouse.autotests.objects;

import org.json.JSONException;
import org.json.JSONObject;

public class Invoice extends AbstractObject {

    private static final String API_URL = "/invoices";

    public Invoice(JSONObject jsonObject) throws JSONException {
        super(jsonObject);
    }

    public Invoice(String sku, String supplier, String acceptanceDate, String accepter, String legalEntity,
                   String supplierInvoiceSku, String supplierInvoiceDate) throws JSONException {
        this(new JSONObject()
                .put("sku", sku)
                .put("supplier", supplier)
                .put("acceptanceDate", acceptanceDate)
                .put("accepter", accepter)
                .put("legalEntity", legalEntity)
                .put("supplierInvoiceSku", supplierInvoiceSku)
                .put("supplierInvoiceDate", supplierInvoiceDate)
        );
    }

    @Override
    public String getApiUrl() {
        return API_URL;
    }

    public String getSku() throws JSONException {
        return getPropertyAsString("sku");
    }
}

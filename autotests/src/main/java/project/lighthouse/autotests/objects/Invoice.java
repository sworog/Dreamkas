package project.lighthouse.autotests.objects;

import org.json.JSONException;
import org.json.JSONObject;

public class Invoice {

    private JSONObject jsonObject;

    public Invoice(JSONObject jsonObject) throws JSONException {
        this.jsonObject = jsonObject;
    }

    public String getId() throws JSONException {
        return (String) jsonObject.get("id");
    }

    public static JSONObject getJsonObject(String sku, String supplier, String acceptanceDate, String accepter, String legalEntity, String supplierInvoiceSku, String supplierInvoiceDate) throws JSONException {
        return new JSONObject()
                .put("invoice",
                        new JSONObject()
                                .put("sku", sku)
                                .put("supplier", supplier)
                                .put("acceptanceDate", acceptanceDate)
                                .put("accepter", accepter)
                                .put("legalEntity", legalEntity)
                                .put("supplierInvoiceSku", supplierInvoiceSku)
                                .put("supplierInvoiceDate", supplierInvoiceDate)
                );
    }
}

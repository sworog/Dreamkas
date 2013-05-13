package project.lighthouse.autotests.objects;

import org.json.JSONException;
import org.json.JSONObject;

public class Invoice {

    public static final String invoiceJsonPattern = "{\"invoice\":{\"sku\":\"%s\",\"supplier\":\"supplier\",\"acceptanceDate\":\"%s\",\"accepter\":\"accepter\",\"legalEntity\":\"legalEntity\",\"supplierInvoiceSku\":\"\",\"supplierInvoiceDate\":\"\"}}";
    ;

    private JSONObject jsonInvoiceObject;

    public Invoice(JSONObject jsonInvoiceObject) throws JSONException {
        this.jsonInvoiceObject = jsonInvoiceObject;
    }

    public String getInvoiceId() throws JSONException {
        return (String) jsonInvoiceObject.get("id");
    }
}

package project.lighthouse.autotests.objects;

import org.json.JSONException;
import org.json.JSONObject;

public class Invoice {

    public static final String jsonPattern = "{\"invoice\":{\"sku\":\"%s\",\"supplier\":\"supplier\"," +
            "\"acceptanceDate\":\"%s\",\"accepter\":\"accepter\",\"legalEntity\":\"legalEntity\",\"supplierInvoiceSku\":\"\",\"supplierInvoiceDate\":\"\"}}";

    private JSONObject jsonObject;

    public Invoice(JSONObject jsonObject) throws JSONException {
        this.jsonObject = jsonObject;
    }

    public String getId() throws JSONException {
        return (String) jsonObject.get("id");
    }
}

package project.lighthouse.autotests.objects.api.invoice;

import org.json.JSONException;
import org.json.JSONObject;
import project.lighthouse.autotests.objects.api.abstraction.AbstractProductObject;

public class InvoiceProduct extends AbstractProductObject {

    public InvoiceProduct(String product, String quantity, String price) throws JSONException {
        super(new JSONObject()
                .put("product", product)
                .put("quantity", quantity)
                .put("priceEntered", price));
    }
}

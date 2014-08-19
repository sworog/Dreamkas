package project.lighthouse.autotests.objects.api.writeoff;

import org.json.JSONException;
import org.json.JSONObject;
import project.lighthouse.autotests.objects.api.abstraction.AbstractProductObject;

public class WriteOffProduct extends AbstractProductObject {

    public WriteOffProduct(String productId, String quantity, String price, String cause) throws JSONException {
        super(new JSONObject()
                        .put("product", productId)
                        .put("quantity", quantity)
                        .put("price", price)
                        .put("cause", cause)
        );
    }
}

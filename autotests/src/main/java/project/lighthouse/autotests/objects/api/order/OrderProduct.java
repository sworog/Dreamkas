package project.lighthouse.autotests.objects.api.order;

import org.json.JSONException;
import org.json.JSONObject;
import project.lighthouse.autotests.objects.api.abstraction.AbstractProductObject;

public class OrderProduct extends AbstractProductObject {

    public OrderProduct(String productId, String quantity) throws JSONException {
        super(new JSONObject()
                .put("product", productId)
                .put("quantity", quantity));
    }
}

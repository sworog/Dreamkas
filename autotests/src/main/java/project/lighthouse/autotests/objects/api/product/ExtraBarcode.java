package project.lighthouse.autotests.objects.api.product;

import org.json.JSONException;
import org.json.JSONObject;
import project.lighthouse.autotests.objects.api.abstraction.AbstractObject;

/**
 * Object to store extra barcode data
 */
public class ExtraBarcode extends AbstractObject {

    public ExtraBarcode(String barcode, String quantity, String price) throws JSONException {
        super(new JSONObject()
                        .put("barcode", barcode)
                        .put("quantity", quantity)
                        .put("price", price)
        );
    }

    @Override
    public String getApiUrl() {
        return null;
    }
}

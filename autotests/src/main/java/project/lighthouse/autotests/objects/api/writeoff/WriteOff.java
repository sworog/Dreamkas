package project.lighthouse.autotests.objects.api.writeoff;

import org.json.JSONException;
import org.json.JSONObject;
import project.lighthouse.autotests.objects.api.abstraction.AbstractProductableObject;

public class WriteOff extends AbstractProductableObject {

    private static final String API_URL = "/writeOffs";

    public WriteOff(JSONObject jsonObject) {
        super(jsonObject);
    }

    public WriteOff(String storeId, String date) throws JSONException {
        this(new JSONObject()
                .put("store", storeId)
                .put("date", date)
        );
    }

    @Override
    public String getApiUrl() {
        return API_URL;
    }

    public String getNumber() {
        return getPropertyAsString("number");
    }

    public void putProduct(String productId, String quantity, String price, String cause) throws JSONException
    {
        putProduct(new WriteOffProduct(productId, quantity, price, cause));
    }
}

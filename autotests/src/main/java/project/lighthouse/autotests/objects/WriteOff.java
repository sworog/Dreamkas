package project.lighthouse.autotests.objects;

import org.json.JSONException;
import org.json.JSONObject;

public class WriteOff {

    JSONObject productJsonObject;

    public static final String jsonPattern = "{\"writeOff\":{\"number\":\"%s\",\"date\":\"%s\"}}";

    public WriteOff(JSONObject productJsonObject) {
        this.productJsonObject = productJsonObject;
    }

    public String getId() throws JSONException {
        return productJsonObject.getString("id");
    }
}

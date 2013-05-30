package project.lighthouse.autotests.objects;

import org.json.JSONException;
import org.json.JSONObject;

public class WriteOff {

    JSONObject productJsonObject;

    public WriteOff(JSONObject productJsonObject) {
        this.productJsonObject = productJsonObject;
    }

    public String getId() throws JSONException {
        return productJsonObject.getString("id");
    }

    public static JSONObject getJsonObject(String number, String date) throws JSONException {
        return new JSONObject()
                .put("writeOff",
                        new JSONObject()
                                .put("number", number)
                                .put("date", date)
                );
    }
}

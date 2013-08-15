package project.lighthouse.autotests.objects;

import org.json.JSONException;
import org.json.JSONObject;

public class WriteOff extends AbstractObject {

    private static final String API_URL = "/writeoffs";

    public WriteOff(JSONObject jsonObject) {
        super(jsonObject);
    }

    public WriteOff(String number, String date) throws JSONException {
        this(new JSONObject()
                .put("number", number)
                .put("date", date)
        );
    }

    @Override
    public String getApiUrl() {
        return API_URL;
    }

    public String getNumber() throws JSONException {
        return getPropertyAsString("number");
    }
}

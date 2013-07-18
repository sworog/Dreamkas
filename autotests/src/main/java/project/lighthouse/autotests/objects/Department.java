package project.lighthouse.autotests.objects;

import org.json.JSONException;
import org.json.JSONObject;

public class Department extends AbstractObject {
    final public static String NAME = "department";
    final public static String API_URL = "/departments";

    final public static String DEFAULT_NUMBER = "department";
    final public static String DEFAULT_NAME = "department name";

    public Department(JSONObject jsonObject) {
        super(jsonObject);
    }

    public Department(String number, String name) throws JSONException {
        this(new JSONObject()
                .put("number", number)
                .put("name", name)
        );
    }

    public Department() throws JSONException {
        this(DEFAULT_NUMBER, DEFAULT_NAME);
    }

    @Override
    public String getApiUrl() {
        return API_URL;
    }

    public String getNumber() throws JSONException {
        return getPropertyAsString("number");
    }

    public static JSONObject getJsonObject(String number, String name, String storeId) throws JSONException {
        return new JSONObject()
                .put("number", number)
                .put("name", name)
                .put("store", storeId);
    }

    public String getStoreID() throws JSONException {
        return jsonObject.getJSONObject("store").getString("id");
    }
}

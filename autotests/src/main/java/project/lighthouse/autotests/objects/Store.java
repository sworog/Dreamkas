package project.lighthouse.autotests.objects;

import org.json.JSONException;
import org.json.JSONObject;

public class Store extends AbstractObject {
    final public static String NAME = "store";
    final public static String API_URL = "/stores";

    final public static String DEFAULT_NUMBER = "store1";
    final public static String DEFAULT_ADDRESS = "address1";
    final public static String DEFAULT_CONTACTS = "contacts1";

    public Store(JSONObject jsonObject) {
        super(jsonObject);
    }

    public Store(String number, String address, String contacts) throws JSONException {
        this(new JSONObject()
                .put("number", number)
                .put("address", address)
                .put("contacts", contacts)
        );
    }

    public Store() throws JSONException {
        this(DEFAULT_NUMBER, DEFAULT_ADDRESS, DEFAULT_CONTACTS);
    }

    @Override
    public String getApiUrl() {
        return API_URL;
    }

    public String getId() throws JSONException {
        return getPropertyAsString("id");
    }
}

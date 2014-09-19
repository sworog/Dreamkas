package project.lighthouse.autotests.objects.api;

import org.json.JSONException;
import org.json.JSONObject;
import project.lighthouse.autotests.objects.api.abstraction.AbstractObject;

public class Store extends AbstractObject {
    final public static String API_URL = "/stores";

    public Store(JSONObject jsonObject) {
        super(jsonObject);
    }

    public Store(String name, String address) throws JSONException {
        this(new JSONObject()
                .put("name", name)
                .put("address", address));
    }

    @Override
    public String getApiUrl() {
        return API_URL;
    }

    public String getNumber() throws JSONException {
        return getPropertyAsString("number");
    }

    public String getAddress() throws JSONException {
        return getPropertyAsString("address");
    }

    public String getContacts() throws JSONException {
        return getPropertyAsString("contacts");
    }
}

package project.lighthouse.autotests.objects.api;

import org.json.JSONException;
import org.json.JSONObject;
import project.lighthouse.autotests.objects.api.abstraction.AbstractObject;

@Deprecated
public class User extends AbstractObject {

    private Store store;

    private static final String API_URL = "/users";

    public User(JSONObject jsonObject) {
        super(jsonObject);
    }

    public User(String name, String position, String login, String password, String role) throws JSONException {
        this(new JSONObject()
                .put("name", name)
                .put("position", position)
                .put("username", login)
                .put("password", password)
                .put("role", role)
        );
    }

    @Override
    public String getApiUrl() {
        return API_URL;
    }

    public String getUserName() throws JSONException {
        return getPropertyAsString("username");
    }

    public void setStore(Store store) {
        this.store = store;
    }

    public Store getStore() {
        return store;
    }
}

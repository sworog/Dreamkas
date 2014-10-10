package ru.dreamkas.storage.containers.user;

import org.json.JSONException;
import org.json.JSONObject;
import ru.dreamkas.api.objects.Store;

/**
 * Class to store user data
 */
public class UserContainer {

    private JSONObject jsonObject;
    private Store store;
    private String password;

    public UserContainer(JSONObject jsonObject) {
        this.jsonObject = jsonObject;
    }

    public String getEmail() {
        return getAsString("email");
    }

    public Store getStore() {
        return store;
    }

    public void setStore(Store store) {
        this.store = store;
    }

    private String getAsString(String key) {
        try {
            return jsonObject.getString(key);
        } catch (JSONException e) {
            throw new AssertionError(e);
        }
    }

    public String getPassword() {
        return password;
    }

    public void setPassword(String password) {
        this.password = password;
    }
}

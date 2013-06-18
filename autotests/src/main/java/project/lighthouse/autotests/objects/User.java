package project.lighthouse.autotests.objects;

import org.json.JSONException;
import org.json.JSONObject;

public class User {

    JSONObject jsonObject;

    public User(JSONObject jsonObject) {
        this.jsonObject = jsonObject;
    }

    public String getId() throws JSONException {
        return jsonObject.getString("id");
    }

    public static JSONObject getJsonObject(String name, String position, String login, String password, String role) throws JSONException {
        return new JSONObject()
                .put("name", name)
                .put("position", position)
                .put("login", login)
                .put("password", password)
                .put("role", role);
    }
}

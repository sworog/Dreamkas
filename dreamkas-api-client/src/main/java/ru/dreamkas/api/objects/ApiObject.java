package ru.dreamkas.api.objects;

import org.json.JSONException;
import org.json.JSONObject;

public class ApiObject {

    private JSONObject jsonObject;

    public ApiObject() {
        this(new JSONObject());
    }

    public ApiObject(JSONObject jsonObject) {
        this.jsonObject = jsonObject;
    }

    public String getPropertyAsString(String key) throws JSONException {
        return jsonObject.getString(key);
    }

    public ApiObject put(String name, String value) throws JSONException {
        jsonObject.put(name, value);
        return this;
    }

    public JSONObject getJsonObject() {
        return jsonObject;
    }
}

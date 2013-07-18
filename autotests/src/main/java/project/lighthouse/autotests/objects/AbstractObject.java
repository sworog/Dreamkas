package project.lighthouse.autotests.objects;

import org.json.JSONException;
import org.json.JSONObject;

abstract public class AbstractObject {

    protected JSONObject jsonObject;

    public AbstractObject(JSONObject jsonObject) {
        this.jsonObject = jsonObject;
    }

    public String getPropertyAsString(String key) throws JSONException {
        return jsonObject.getString(key);
    }

    public JSONObject getJsonObject() {
        return jsonObject;
    }

    abstract public String getApiUrl();
}

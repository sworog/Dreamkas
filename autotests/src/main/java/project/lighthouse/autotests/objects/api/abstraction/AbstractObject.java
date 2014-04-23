package project.lighthouse.autotests.objects.api.abstraction;

import org.json.JSONException;
import org.json.JSONObject;

abstract public class AbstractObject {

    protected JSONObject jsonObject;

    public AbstractObject(JSONObject jsonObject) {
        this.jsonObject = jsonObject;
    }

    public AbstractObject() {
        this.jsonObject = new JSONObject();
    }

    public String getPropertyAsString(String key) {
        try {
            return jsonObject.getString(key);
        } catch (JSONException e) {
            throw new AssertionError(e);
        }
    }

    public JSONObject getJsonObject() {
        return jsonObject;
    }

    public void setJsonObject(JSONObject jsonObject) {
        this.jsonObject = jsonObject;
    }

    abstract public String getApiUrl();

    public String getId() {
        return getPropertyAsString("id");
    }

    public String getName() throws JSONException {
        return getPropertyAsString("name");
    }
}

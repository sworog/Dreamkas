package project.lighthouse.autotests.objects;

import org.json.JSONException;
import org.json.JSONObject;

public class Group extends AbstractClassifierNode {

    private static final String API_URL = "/groups";

    static public String DEFAULT_NAME = "defaultGroup";

    public Group(JSONObject jsonObject) {
        super(jsonObject);
    }

    public Group(String name) throws JSONException {
        super(name);
    }

    @Override
    public String getApiUrl() {
        return API_URL;
    }
}

package project.lighthouse.autotests.objects;

import org.json.JSONException;
import org.json.JSONObject;

public class Category extends AbstractClassifierNode {

    private static final String API_URL = "/categories";

    static public String DEFAULT_NAME = "defaultCategory";

    public Category(JSONObject jsonObject) {
        super(jsonObject);
    }

    public Category(String name) throws JSONException {
        super(name);
        jsonObject.put("group", getGroup().getId());
    }

    public Category(String name, String groupId) throws JSONException {
        super(name);
        jsonObject.put("group", groupId);
    }

    public Group getGroup() throws JSONException {
        return new Group(jsonObject.getJSONObject("group"));
    }

    public Boolean hasGroup(String groupName) throws JSONException {
        return getGroup().getName().equals(groupName);
    }

    @Override
    public String getApiUrl() {
        return API_URL;
    }
}

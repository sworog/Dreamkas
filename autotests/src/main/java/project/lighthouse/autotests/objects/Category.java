package project.lighthouse.autotests.objects;

import org.json.JSONException;
import org.json.JSONObject;

public class Category {

    static public String DEFAULT_NAME = "defaultCategory";

    JSONObject jsonObject;

    public Category(JSONObject jsonObject) {
        this.jsonObject = jsonObject;
    }

    public String getId() throws JSONException {
        return jsonObject.getString("id");
    }

    public String getName() throws JSONException {
        return jsonObject.getString("name");
    }

    public Group getGroup() throws JSONException {
        return new Group(jsonObject.getJSONObject("group"));
    }

    public static JSONObject getJsonObject(String name, String groupId) throws JSONException {
        return new JSONObject()
                .put("name", name)
                .put("group", groupId);
    }

    public Boolean hasGroup(String groupName) throws JSONException {
        return getGroup().getGroupName().equals(groupName);
    }
}

package project.lighthouse.autotests.objects;

import org.json.JSONException;
import org.json.JSONObject;

public class Group {

    JSONObject jsonObject;

    public Group(JSONObject jsonObject) {
        this.jsonObject = jsonObject;
    }

    public String getId() throws JSONException {
        return jsonObject.getString("id");
    }

    public static JSONObject getJsonObject(String name, String klassId) throws JSONException {
        return new JSONObject()
                .put("group",
                        new JSONObject()
                                .put("name", name)
                                .put("klass", klassId)
                );
    }
}

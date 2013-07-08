package project.lighthouse.autotests.objects;

import org.json.JSONException;
import org.json.JSONObject;

public class SubCategory {

    JSONObject jsonObject;

    public SubCategory(JSONObject jsonObject) {
        this.jsonObject = jsonObject;
    }

    public String getId() throws JSONException {
        return jsonObject.getString("id");
    }

    public static JSONObject getJsonObject(String name, String categoryId) throws JSONException {
        return new JSONObject()
                .put("name", name)
                .put("category", categoryId);
    }
}

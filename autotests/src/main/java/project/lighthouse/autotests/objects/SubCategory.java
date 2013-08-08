package project.lighthouse.autotests.objects;

import org.json.JSONException;
import org.json.JSONObject;

public class SubCategory {

    static public String DEFAULT_NAME = "defaultSubCategory";

    JSONObject jsonObject;

    public SubCategory(JSONObject jsonObject) {
        this.jsonObject = jsonObject;
    }

    public String getId() throws JSONException {
        return jsonObject.getString("id");
    }

    public String getName() throws JSONException {
        return jsonObject.getString("name");
    }

    public Category getCategory() throws JSONException {
        return new Category(jsonObject.getJSONObject("category"));
    }

    public Boolean hasCategory(String categoryName) throws JSONException {
        return getCategory().getName().equals(categoryName);
    }

    public static JSONObject getJsonObject(String name, String categoryId) throws JSONException {
        return new JSONObject()
                .put("name", name)
                .put("category", categoryId);
    }
}

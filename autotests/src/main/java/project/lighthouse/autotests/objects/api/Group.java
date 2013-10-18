package project.lighthouse.autotests.objects.api;

import org.json.JSONArray;
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

    public Boolean hasCategory(Category expectedCategory) throws JSONException {
        JSONArray jsonArray = getJsonObject().getJSONArray("categories");
        if (jsonArray.length() != 0) {
            for (int i = 0; i < jsonArray.length(); i++) {
                Category category = new Category(jsonArray.getJSONObject(i));
                if (category.getName().equals(expectedCategory.getName())) {
                    return true;
                }
            }
        }
        return false;
    }

    public Category getCategory(Category categoryToGet) throws JSONException {
        JSONArray jsonArray = getJsonObject().getJSONArray("categories");
        if (jsonArray.length() != 0) {
            for (int i = 0; i < jsonArray.length(); i++) {
                Category category = new Category(jsonArray.getJSONObject(i));
                if (category.getName().equals(categoryToGet.getName())) {
                    return category;
                }
            }
        }
        return categoryToGet;
    }
}

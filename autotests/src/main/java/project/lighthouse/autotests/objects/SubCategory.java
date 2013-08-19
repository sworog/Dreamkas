package project.lighthouse.autotests.objects;

import org.json.JSONException;
import org.json.JSONObject;

public class SubCategory extends AbstractClassifierNode {

    private static final String API_URL = "/subcategories";

    static public String DEFAULT_NAME = "defaultSubCategory";

    public SubCategory(JSONObject jsonObject) {
        super(jsonObject);
    }

    public SubCategory(String name) throws JSONException {
        super(name);
        jsonObject.put("category", getCategory().getId());
    }

    public SubCategory(String name, String categoryId) throws JSONException {
        super(name);
        jsonObject.put("category", categoryId);
    }

    @Override
    public String getApiUrl() {
        return API_URL;
    }

    public Category getCategory() throws JSONException {
        return new Category(jsonObject.getJSONObject("category"));
    }
}

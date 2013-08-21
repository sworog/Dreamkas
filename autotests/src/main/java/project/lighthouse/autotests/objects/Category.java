package project.lighthouse.autotests.objects;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;
import project.lighthouse.autotests.StaticData;

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

    public Boolean hasSubCategory(SubCategory expectedSubCategory) throws JSONException {
        JSONArray jsonArray = StaticData.categories.get(getName()).getJsonObject().getJSONArray("subCategories");
        if (jsonArray.length() != 0) {
            for (int i = 0; i < jsonArray.length(); i++) {
                SubCategory subCategory = new SubCategory(jsonArray.getJSONObject(i));
                if (subCategory.getName().equals(expectedSubCategory.getName())) {
                    return true;
                }
            }
        }
        return false;
    }

    @Override
    public String getApiUrl() {
        return API_URL;
    }
}

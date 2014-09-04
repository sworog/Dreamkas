package project.lighthouse.autotests.objects.api;

import org.json.JSONException;
import org.json.JSONObject;
import project.lighthouse.autotests.helper.UrlHelper;
import project.lighthouse.autotests.objects.api.abstraction.AbstractClassifierNode;
import project.lighthouse.autotests.storage.Storage;
import project.lighthouse.autotests.storage.variable.CustomVariableStorage;

import java.util.ArrayList;

public class SubCategory extends AbstractClassifierNode {

    private static final String API_URL = "/catalog/groups";

    static public String DEFAULT_NAME = "defaultSubCategory";

    public SubCategory(JSONObject jsonObject) {
        super(jsonObject);
    }

    public SubCategory(String name) throws JSONException {
        super(name);
        //jsonObject.put("category", getCategory().getId());
    }

    public SubCategory(String name, String categoryId) throws JSONException {
        super(name);
        jsonObject.put("category", categoryId);
    }

    public SubCategory(String name, String categoryId, String rounding) throws JSONException {
        this(name, categoryId);
        jsonObject.put("rounding", rounding);
    }

    @Override
    public String getApiUrl() {
        return API_URL;
    }

    public Category getCategory() throws JSONException {
        return new Category(jsonObject.getJSONObject("category"));
    }

    public Boolean hasProduct(Product product) throws JSONException {
        CustomVariableStorage customVariableStorage = Storage.getCustomVariableStorage();
        if (customVariableStorage.getSubCategoryProducts().containsKey(getId())) {
            ArrayList<Product> products = customVariableStorage.getSubCategoryProducts().get(getId());
            for (Product p : products) {
                if (p.getName().equals(product.getName())) {
                    return true;
                }
            }
        }
        return false;
    }

    public Product getProduct(Product product) throws JSONException {
        ArrayList<Product> products = Storage.getCustomVariableStorage().getSubCategoryProducts().get(getId());
        for (Product p : products) {
            if (p.getName().equals(product.getName())) {
                return p;
            }
        }
        return product;
    }

    public void addProduct(Product product) throws JSONException {
        CustomVariableStorage customVariableStorage = Storage.getCustomVariableStorage();
        ArrayList<Product> products;
        if (!customVariableStorage.getSubCategoryProducts().containsKey(getId())) {
            products = new ArrayList<>();
        } else {
            products = customVariableStorage.getSubCategoryProducts().get(getId());
        }
        products.add(product);
        customVariableStorage.getSubCategoryProducts().put(getId(), products);
    }

    public static String getPageUrl(String groupName) throws JSONException {
        String groupId = Storage.getCustomVariableStorage().getSubCategories().get(groupName).getId();
        return String.format("%s/catalog/groups/%s", UrlHelper.getWebFrontUrl(), groupId);
    }
}

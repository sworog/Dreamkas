package project.lighthouse.autotests.objects.api;

import org.json.JSONException;
import org.json.JSONObject;
import project.lighthouse.autotests.StaticData;
import project.lighthouse.autotests.objects.api.abstraction.AbstractClassifierNode;

import java.util.ArrayList;

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
        if (StaticData.subCategoryProducts.containsKey(getId())) {
            ArrayList<Product> products = StaticData.subCategoryProducts.get(getId());
            for (Product p : products) {
                if (p.getSku().equals(product.getSku())) {
                    return true;
                }
            }
        }
        return false;
    }

    public Product getProduct(Product product) throws JSONException {
        ArrayList<Product> products = StaticData.subCategoryProducts.get(getId());
        for (Product p : products) {
            if (p.getSku().equals(product.getSku())) {
                return p;
            }
        }
        return product;
    }

    public void addProduct(Product product) throws JSONException {
        ArrayList<Product> products;
        if (!StaticData.subCategoryProducts.containsKey(getId())) {
            products = new ArrayList<>();
        } else {
            products = StaticData.subCategoryProducts.get(getId());
        }
        products.add(product);
        StaticData.subCategoryProducts.put(getId(), products);
    }
}

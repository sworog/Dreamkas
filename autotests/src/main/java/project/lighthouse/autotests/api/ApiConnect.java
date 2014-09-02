package project.lighthouse.autotests.api;

import org.json.JSONException;
import org.json.JSONObject;
import project.lighthouse.autotests.StaticData;
import project.lighthouse.autotests.api.http.HttpExecutor;
import project.lighthouse.autotests.api.http.HttpRequestable;
import project.lighthouse.autotests.helper.UrlHelper;
import project.lighthouse.autotests.objects.api.*;

import java.io.IOException;

public class ApiConnect {

    private final HttpRequestable httpRequestable;

    public ApiConnect(String userName, String password) throws IOException, JSONException {
        httpRequestable = HttpExecutor.getHttpRequestable(userName, password);
    }

    public Product createProductThroughPost(Product product, SubCategory subCategory) throws JSONException, IOException {
        if (!subCategory.hasProduct(product)) {
            httpRequestable.executePostRequest(product);
            subCategory.addProduct(product);
            StaticData.products.put(product.getName(), product);
            return product;
        } else {
            return subCategory.getProduct(product);
        }
    }

    public Group createGroupThroughPost(Group group) throws JSONException, IOException {
        if (!StaticData.isGroupCreated(group.getName())) {
            httpRequestable.executePostRequest(group);
            StaticData.groups.put(group.getName(), group);
            return group;
        } else {
            return getUpdatedGroup(group);
        }
    }

    private Group getUpdatedGroup(Group group) throws IOException, JSONException {
        group = StaticData.groups.get(group.getName());
        return updatedGroup(group);
    }

    private Group updatedGroup(Group group) throws JSONException, IOException {
        String apiUrl = String.format("%s/%s", UrlHelper.getApiUrl(group.getApiUrl()), group.getId());
        Group updatedGroup = new Group(new JSONObject(httpRequestable.executeGetRequest(apiUrl)));
        StaticData.groups.put(group.getName(), updatedGroup);
        return updatedGroup;
    }

    public Category createCategoryThroughPost(Category category, Group group) throws IOException, JSONException {
        if (!(group.hasCategory(category))) {
            createGroupThroughPost(group);
            httpRequestable.executePostRequest(category);
            StaticData.categories.put(category.getName(), category);
            return category;
        } else {
            Category updatedCategory = updatedGroup(group).getCategory(category);
            StaticData.categories.put(category.getName(), updatedCategory);
            return updatedCategory;
        }
    }

    public String getGroupPageUrl(String groupName) throws JSONException {
        String groupId = StaticData.groups.get(groupName).getId();
        return String.format("%s/groups/%s", UrlHelper.getWebFrontUrl(), groupId);
    }

    public String getCategoryPageUrl(String categoryName, String groupName) throws JSONException {
        String groupPageUrl = getGroupPageUrl(groupName) + "/categories/%s";
        String categoryId = StaticData.categories.get(categoryName).getId();
        return String.format(groupPageUrl, categoryId);
    }

    public SubCategory createSubCategoryThroughPost(SubCategory subCategory, Category category, Group group) throws JSONException, IOException {
        if (!category.hasSubCategory(subCategory)) {
            group = createGroupThroughPost(group);
            createCategoryThroughPost(category, group);
            httpRequestable.executePostRequest(subCategory);
            StaticData.subCategories.put(subCategory.getName(), subCategory);
            return subCategory;
        } else {
            return StaticData.subCategories.get(subCategory.getName());
        }
    }

    public SubCategory createSubCategoryThroughPost(SubCategory subCategory) throws JSONException, IOException {
        if (!StaticData.hasSubCategory(subCategory.getName())) {
            httpRequestable.executePostRequest(subCategory);
            StaticData.subCategories.put(subCategory.getName(), subCategory);
            return subCategory;
        } else {
            return StaticData.subCategories.get(subCategory.getName());
        }
    }

    public String getSubCategoryProductListPageUrl(String subCategoryName, String categoryName, String groupName) throws JSONException {
        String categoryPageUrl = getCategoryPageUrl(categoryName, groupName);
        String subCategoryId = StaticData.subCategories.get(subCategoryName).getId();
        return String.format("%s?subCategoryId=%s&section=subCategory", categoryPageUrl, subCategoryId);
    }

    public String getSubCategoryProductCreatePageUrl(String subCategoryName) throws JSONException {
        String subCategoryId = StaticData.subCategories.get(subCategoryName).getId();
        return String.format("%s/products/create?subCategory=%s", UrlHelper.getWebFrontUrl(), subCategoryId);
    }

    public Store createStoreThroughPost(Store store) throws JSONException, IOException {
        if (!StaticData.stores.containsKey(store.getName())) {
            httpRequestable.executePostRequest(store);
            StaticData.stores.put(store.getName(), store);
            return store;
        } else {
            return StaticData.stores.get(store.getName());
        }
    }

    public String getStoreId(String storeNumber) throws JSONException {
        return StaticData.stores.get(storeNumber).getId();
    }

    public String setSet10ImportUrl(String value) throws IOException, JSONException {
        JSONObject jsonObject = new JSONObject(httpRequestable.executeGetRequest(UrlHelper.getApiUrl("/configs/by/name?query=set10-import-url")));
        String targetUrl = UrlHelper.getApiUrl("/configs/" + jsonObject.getString("id"));
        return httpRequestable.executePutRequest(targetUrl, new JSONObject()
                .put("name", "set10-import-url")
                .put("value", value));
    }
}

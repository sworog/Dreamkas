package project.lighthouse.autotests.api;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;
import project.lighthouse.autotests.StaticData;
import project.lighthouse.autotests.helper.UrlHelper;
import project.lighthouse.autotests.objects.api.*;

import java.io.IOException;

public class ApiConnect {

    private final HttpRequestable httpRequestable;

    public ApiConnect(String userName, String password) throws IOException, JSONException {
        httpRequestable = HttpExecutor.getHttpRequestable(userName, password);
    }

    public Department createStoreDepartmentThroughPost(Department department) throws JSONException, IOException {
        if (!StaticData.departments.containsKey(department.getNumber())) {
            httpRequestable.executePostRequest(department);
            StaticData.departments.put(department.getNumber(), department);
            return department;
        } else {
            return StaticData.departments.get(department.getNumber());
        }
    }

    public Product createProductThroughPost(Product product, SubCategory subCategory) throws JSONException, IOException {
        if (!subCategory.hasProduct(product)) {
            getSubCategoryMarkUp(subCategory);
            httpRequestable.executePostRequest(product);
            subCategory.addProduct(product);
            StaticData.products.put(product.getName(), product);
            return product;
        } else {
            return subCategory.getProduct(product);
        }
    }

    public void getSubCategoryMarkUp(SubCategory subCategory) throws IOException, JSONException {
        String apiUrl = String.format("%s/%s", UrlHelper.getApiUrl("/subcategories"), subCategory.getId());
        String response = httpRequestable.executeGetRequest(apiUrl);
        JSONObject jsonObject = new JSONObject(response);
        StaticData.retailMarkupMax = (!jsonObject.isNull("retailMarkupMax"))
                ? jsonObject.getString("retailMarkupMax")
                : null;
        StaticData.retailMarkupMin = (!jsonObject.isNull("retailMarkupMin"))
                ? jsonObject.getString("retailMarkupMin")
                : null;
    }

    public String getProductPageUrl(String productName) throws JSONException {
        String productId = StaticData.products.get(productName).getId();
        return String.format("%s/products/%s", UrlHelper.getWebFrontUrl(), productId);
    }

    public WriteOff createWriteOffThroughPost(WriteOff writeOff) throws JSONException, IOException {
        if (!StaticData.writeOffs.containsKey(writeOff.getNumber())) {
            httpRequestable.executePostRequest(writeOff);
            StaticData.writeOffs.put(writeOff.getNumber(), writeOff);
            return writeOff;
        } else {
            return StaticData.writeOffs.get(writeOff.getNumber());
        }
    }

    public void addProductToWriteOff(String writeOffNumber, String productName, String quantity, String price, String cause)
            throws JSONException, IOException {
        Product product = StaticData.products.get(productName);
        WriteOff writeOff = StaticData.writeOffs.get(writeOffNumber);
        String apiUrl = String.format("%s%s/%s/products.json", UrlHelper.getApiUrl(""), writeOff.getApiUrl(), writeOff.getId());

        String productJsonData = WriteOffProduct.getJsonObject(product.getId(), quantity, price, cause).toString();
        httpRequestable.executePostRequest(apiUrl, productJsonData);
    }

    public String getWriteOffPageUrl(String writeOffNumber) throws JSONException {
        String writeOffId = StaticData.writeOffs.get(writeOffNumber).getId();
        return String.format("%s/writeOffs/%s", UrlHelper.getWebFrontUrl(), writeOffId);
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

    public String getSubCategoryProductListPageUrl(String subCategoryName, String categoryName, String groupName) throws JSONException {
        String categoryPageUrl = getCategoryPageUrl(categoryName, groupName);
        String subCategoryId = StaticData.subCategories.get(subCategoryName).getId();
        return String.format("%s?subCategoryId=%s&section=subCategory", categoryPageUrl, subCategoryId);
    }

    public String getSubCategoryProductCreatePageUrl(String subCategoryName) throws JSONException {
        String subCategoryId = StaticData.subCategories.get(subCategoryName).getId();
        return String.format("%s/products/create?subCategory=%s", UrlHelper.getWebFrontUrl(), subCategoryId);
    }

    @Deprecated
    private User createUserThroughPost(User user) throws JSONException, IOException {
        if (!StaticData.users.containsKey(user.getUserName())) {
            httpRequestable.executePostRequest(user);
            StaticData.users.put(user.getUserName(), user);
            return user;
        } else {
            return StaticData.users.get(user.getUserName());
        }
    }

    @Deprecated
    public User createUserThroughPost(String name, String position, String login, String password, String role) throws JSONException, IOException {
        User user = new User(name, position, login, password, role);
        return createUserThroughPost(user);
    }

    public User getUser(String userName) throws IOException, JSONException {
        JSONArray jsonArray = new JSONArray(httpRequestable.executeGetRequest(UrlHelper.getApiUrl("/users")));
        for (int i = 0; i < jsonArray.length(); i++) {
            JSONObject jsonObject = jsonArray.getJSONObject(i);
            if (jsonObject.getString("username").equals(userName)) {
                User user = new User(new JSONObject());
                user.setJsonObject(jsonObject);
                StaticData.users.put(userName, user);
                return user;
            }
        }
        return null;
    }

    public Store createStoreThroughPost(Store store) throws JSONException, IOException {
        if (!StaticData.stores.containsKey(store.getNumber())) {
            httpRequestable.executePostRequest(store);
            StaticData.stores.put(store.getNumber(), store);
            return store;
        } else {
            return StaticData.stores.get(store.getNumber());
        }
    }

    public String getStoreId(String storeNumber) throws JSONException {
        return StaticData.stores.get(storeNumber).getId();
    }

    public String getUserPageUrl(String userName) throws JSONException {
        String userId = StaticData.users.get(userName).getId();
        return String.format("%s/users/%s", UrlHelper.getWebFrontUrl(), userId);
    }

    public void setSubCategoryMarkUp(String retailMarkupMax, String retailMarkupMin, SubCategory subCategory) throws JSONException, IOException {
        String apiUrl = String.format("%s/%s", UrlHelper.getApiUrl("/subcategories"), subCategory.getId());
        httpRequestable.executePutRequest(apiUrl, new JSONObject()
                        .put("category", subCategory.getCategory().getId())
                        .put("name", subCategory.getName())
                        .put("retailMarkupMax", retailMarkupMax)
                        .put("retailMarkupMin", retailMarkupMin)
        );
    }

    @Deprecated
    public void promoteUserToManager(Store store, User user, String type) throws JSONException, IOException {
        if (!hasStoreManager(store, user, type)) {
            String apiUrl = String.format("%s/%s", UrlHelper.getApiUrl("/stores"), store.getId());
            httpRequestable.executeLinkRequest(apiUrl, getLinkHeaderValue(user, type));
            user.setStore(store);
        }
    }

    private String getLinkHeaderValue(User user, String type) throws JSONException {
        return String.format("<%s/%s>; rel=\"%s\"", UrlHelper.getApiUrl("/users"), user.getId(), type);
    }

    private Boolean hasStoreManager(Store store, User user, String type) throws JSONException, IOException {
        String apiUrl = String.format("%s/%s/%s", UrlHelper.getApiUrl("/stores"), store.getId(), type);
        String response = httpRequestable.executeGetRequest(apiUrl);
        JSONArray jsonArray = new JSONArray(response);
        for (int i = 0; i < jsonArray.length(); i++) {
            if (jsonArray.getJSONObject(i).getString("id").equals(user.getId())) {
                return true;
            }
        }
        return false;
    }

    public String setSet10ImportUrl(String value) throws IOException, JSONException {
        JSONObject jsonObject = new JSONObject(httpRequestable.executeGetRequest(UrlHelper.getApiUrl("/configs/by/name?query=set10-import-url")));
        String targetUrl = UrlHelper.getApiUrl("/configs/" + jsonObject.getString("id"));
        return httpRequestable.executePutRequest(targetUrl, new JSONObject()
                .put("name", "set10-import-url")
                .put("value", value));
    }

    public Supplier createSupplier(String name) throws JSONException, IOException {
        Supplier supplier = new Supplier(name);
        return createSupplier(supplier);
    }

    public Supplier createSupplier(Supplier supplier) throws IOException, JSONException {
        if (!StaticData.suppliers.containsKey(supplier.getName())) {
            httpRequestable.executePostRequest(supplier);
            StaticData.suppliers.put(supplier.getName(), supplier);
            return supplier;
        } else {
            return StaticData.suppliers.get(supplier.getName());
        }
    }

    public String getSupplierPageUrl(String supplierName) throws JSONException {
        String groupId = StaticData.suppliers.get(supplierName).getId();
        return String.format("%s/suppliers/%s", UrlHelper.getWebFrontUrl(), groupId);
    }
}

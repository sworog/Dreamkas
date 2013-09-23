package project.lighthouse.autotests;

import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.client.methods.HttpEntityEnclosingRequestBase;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.client.methods.HttpPut;
import org.apache.http.entity.StringEntity;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.message.BasicHeader;
import org.apache.http.params.BasicHttpParams;
import org.apache.http.params.HttpConnectionParams;
import org.apache.http.params.HttpParams;
import org.apache.http.protocol.HTTP;
import org.apache.http.util.EntityUtils;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;
import project.lighthouse.autotests.objects.*;

import java.io.IOException;
import java.io.UnsupportedEncodingException;
import java.util.Iterator;

import static junit.framework.Assert.assertEquals;
import static junit.framework.Assert.fail;

public class ApiConnect {

    String userName;
    String password;

    public ApiConnect(String userName, String password) throws JSONException {
        this.userName = userName;
        this.password = password;
    }

    public Department createStoreDepartmentThroughPost(Department department) throws JSONException, IOException {
        if (!StaticData.departments.containsKey(department.getNumber())) {
            executePostRequest(department);
            StaticData.departments.put(department.getNumber(), department);
            return department;
        } else {
            return StaticData.departments.get(department.getNumber());
        }
    }

    public Product createProductThroughPost(Product product, SubCategory subCategory) throws JSONException, IOException {
        if (!subCategory.hasProduct(product)) {
            getSubCategoryMarkUp(subCategory);
            executePostRequest(product);
            subCategory.addProduct(product);
            StaticData.products.put(product.getSku(), product);
            return product;
        } else {
            return subCategory.getProduct(product);
        }
    }

    public void getSubCategoryMarkUp(SubCategory subCategory) throws IOException, JSONException {
        String apiUrl = String.format("%s/%s", UrlHelper.getApiUrl("/subcategories"), subCategory.getId());
        String response = executeSimpleGetRequest(apiUrl, true);
        JSONObject jsonObject = new JSONObject(response);
        StaticData.retailMarkupMax = (!jsonObject.isNull("retailMarkupMax"))
                ? jsonObject.getString("retailMarkupMax")
                : null;
        StaticData.retailMarkupMin = (!jsonObject.isNull("retailMarkupMin"))
                ? jsonObject.getString("retailMarkupMin")
                : null;
    }

    public String getProductPageUrl(String productSku) throws JSONException {
        String productId = StaticData.products.get(productSku).getId();
        return String.format("%s/products/%s", UrlHelper.getWebFrontUrl(), productId);
    }

    public Invoice createInvoiceThroughPost(Invoice invoice) throws JSONException, IOException {
        if (!StaticData.invoices.containsKey(invoice.getSku())) {
            executePostRequest(invoice);
            StaticData.invoices.put(invoice.getSku(), invoice);
            return invoice;
        } else {
            return StaticData.invoices.get(invoice.getSku());
        }
    }

    public String getInvoicePageUrl(String invoiceName) throws JSONException {
        Invoice invoice = StaticData.invoices.get(invoiceName);
        return String.format("%s/invoices/%s?editMode=true", UrlHelper.getWebFrontUrl(), invoice.getId());
    }

    public void addProductToInvoice(String invoiceName, String productSku)
            throws JSONException, IOException {
        Product product = StaticData.products.get(productSku);
        Invoice invoice = StaticData.invoices.get(invoiceName);
        if (!hasInvoiceProduct(invoice, product)) {
            String apiUrl = String.format("%s%s/%s/products.json", UrlHelper.getApiUrl(""), invoice.getApiUrl(), invoice.getId());
            String productJsonData = InvoiceProduct.getJsonObject(product.getId(), "1", "1").toString();
            executePostRequest(apiUrl, productJsonData);
        }
    }

    public Boolean hasInvoiceProduct(Invoice invoice, Product product) throws IOException, JSONException {
        JSONArray jsonArray = getInvoiceProducts(invoice);
        for (int i = 0; i < jsonArray.length(); i++) {
            if (jsonArray.getJSONObject(i).getJSONObject("product").getString("id").equals(product.getId())) {
                return true;
            }
        }
        return false;
    }

    public JSONArray getInvoiceProducts(Invoice invoice) throws IOException, JSONException {
        String url = String.format("%s%s/%s/products", UrlHelper.getApiUrl(""), invoice.getApiUrl(), invoice.getId());
        String response = executeSimpleGetRequest(url, true);
        return new JSONArray(response);
    }

    public void averagePriceRecalculation() throws IOException, JSONException {
        String url = UrlHelper.getApiUrl() + "/api/1/service/recalculate-average-purchase-price";
        String response = executePostRequest(url, "");
        assertEquals(
                String.format("Average price recalculation failed!\nResponse: %s", response),
                response, "{\"ok\":true}"
        );
    }

    public WriteOff createWriteOffThroughPost(WriteOff writeOff) throws JSONException, IOException {
        if (!StaticData.writeOffs.containsKey(writeOff.getNumber())) {
            executePostRequest(writeOff);
            StaticData.writeOffs.put(writeOff.getNumber(), writeOff);
            return writeOff;
        } else {
            return StaticData.writeOffs.get(writeOff.getNumber());
        }
    }

    public void addProductToWriteOff(String writeOffNumber, String productSku, String quantity, String price, String cause)
            throws JSONException, IOException {
        Product product = StaticData.products.get(productSku);
        WriteOff writeOff = StaticData.writeOffs.get(writeOffNumber);
        String apiUrl = String.format("%s%s/%s/products.json", UrlHelper.getApiUrl(""), writeOff.getApiUrl(), writeOff.getId());

        String productJsonData = WriteOffProduct.getJsonObject(product.getId(), quantity, price, cause).toString();
        executePostRequest(apiUrl, productJsonData);
    }

    public String getWriteOffPageUrl(String writeOffNumber) throws JSONException {
        String writeOffId = StaticData.writeOffs.get(writeOffNumber).getId();
        return String.format("%s/writeOffs/%s", UrlHelper.getWebFrontUrl(), writeOffId);
    }

    public Group createGroupThroughPost(Group group) throws JSONException, IOException {
        if (!StaticData.isGroupCreated(group.getName())) {
            executePostRequest(group);
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
        Group updatedGroup = new Group(new JSONObject(executeSimpleGetRequest(apiUrl, true)));
        StaticData.groups.put(group.getName(), updatedGroup);
        return updatedGroup;
    }

    public Category createCategoryThroughPost(Category category, Group group) throws IOException, JSONException {
        if (!(group.hasCategory(category))) {
            createGroupThroughPost(group);
            executePostRequest(category);
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
        return String.format("%s/catalog/%s", UrlHelper.getWebFrontUrl(), groupId);
    }

    public String getCategoryPageUrl(String categoryName, String groupName) throws JSONException {
        String groupPageUrl = getGroupPageUrl(groupName) + "/%s";
        String categoryId = StaticData.categories.get(categoryName).getId();
        return String.format(groupPageUrl, categoryId);
    }

    public SubCategory createSubCategoryThroughPost(SubCategory subCategory, Category category, Group group) throws JSONException, IOException {
        if (!category.hasSubCategory(subCategory)) {
            group = createGroupThroughPost(group);
            createCategoryThroughPost(category, group);
            executePostRequest(subCategory);
            StaticData.subCategories.put(subCategory.getName(), subCategory);
            return subCategory;
        } else {
            return StaticData.subCategories.get(subCategory.getName());
        }
    }

    public String getSubCategoryProductListPageUrl(String subCategoryName, String categoryName, String groupName) throws JSONException {
        String categoryPageUrl = getCategoryPageUrl(categoryName, groupName);
        String subCategoryId = StaticData.subCategories.get(subCategoryName).getId();
        return categoryPageUrl + "/" + subCategoryId;
    }

    public String getSubCategoryProductCreatePageUrl(String subCategoryName) throws JSONException {
        String subCategoryId = StaticData.subCategories.get(subCategoryName).getId();
        return String.format("%s/products/create?subCategory=%s", UrlHelper.getWebFrontUrl(), subCategoryId);
    }

    public User createUserThroughPost(User user) throws JSONException, IOException {
        if (!StaticData.users.containsKey(user.getUserName())) {
            executePostRequest(user);
            StaticData.users.put(user.getUserName(), user);
            return user;
        } else {
            return StaticData.users.get(user.getUserName());
        }
    }

    public User createUserThroughPost(String name, String position, String login, String password, String role) throws JSONException, IOException {
        User user = new User(name, position, login, password, role);
        return createUserThroughPost(user);
    }

    public User getUser(String userName) throws IOException, JSONException {
        JSONArray jsonArray = new JSONArray(executeSimpleGetRequest(UrlHelper.getApiUrl("/users"), true));
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
            executePostRequest(store);
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
        executePutRequest(apiUrl, new JSONObject()
                .put("category", subCategory.getCategory().getId())
                .put("name", subCategory.getName())
                .put("retailMarkupMax", retailMarkupMax)
                .put("retailMarkupMin", retailMarkupMin)
        );
    }

    public void promoteUserToManager(Store store, User user, String type) throws JSONException, IOException {
        if (!hasStoreManager(store, user, type)) {
            String apiUrl = String.format("%s/%s", UrlHelper.getApiUrl("/stores"), store.getId());
            String data = "_method=LINK";
            HttpPost httpPost = getHttpPost(apiUrl);
            httpPost.setHeader("Link", getLinkHeaderValue(user, type));
            StringEntity entity = new StringEntity(data, "UTF-8");
            entity.setContentType("application/x-www-form-urlencoded; charset=UTF-8");
            httpPost.setEntity(entity);
            executeHttpMethod(httpPost);
        }
    }

    private Boolean hasStoreManager(Store store, User user, String type) throws JSONException, IOException {
        String apiUrl = String.format("%s/%s/%s", UrlHelper.getApiUrl("/stores"), store.getId(), type);
        String response = executeSimpleGetRequest(apiUrl, true);
        JSONArray jsonArray = new JSONArray(response);
        for (int i = 0; i < jsonArray.length(); i++) {
            if (jsonArray.getJSONObject(i).getString("id").equals(user.getId())) {
                return true;
            }
        }
        return false;
    }

    private void setHeaders(HttpEntityEnclosingRequestBase httpEntityEnclosingRequestBase) throws IOException, JSONException {
        httpEntityEnclosingRequestBase.setHeader("Accept", "application/json");
        httpEntityEnclosingRequestBase.setHeader("Authorization", "Bearer " + getAccessToken());
    }

    private HttpPost getHttpPost(String url) throws IOException, JSONException {
        HttpPost httpPost = new HttpPost(url);
        setHeaders(httpPost);
        return httpPost;
    }

    private HttpPut getHttpPut(String url) throws IOException, JSONException {
        HttpPut httpPut = new HttpPut(url);
        setHeaders(httpPut);
        return httpPut;
    }

    private StringEntity getStringEntity(String data) throws UnsupportedEncodingException {
        StringEntity entity = new StringEntity(data, "UTF-8");
        entity.setContentType("application/json;charset=UTF-8");
        entity.setContentEncoding(new BasicHeader(HTTP.CONTENT_TYPE, "application/json;charset=UTF-8"));
        return entity;
    }

    private String executeHttpMethod(HttpEntityEnclosingRequestBase httpEntityEnclosingRequestBase) throws IOException {
        DefaultHttpClient httpClient = new DefaultHttpClient();
        httpClient.getParams().setParameter("http.protocol.content-charset", "UTF-8");
        HttpResponse response = httpClient.execute(httpEntityEnclosingRequestBase);

        HttpEntity httpEntity = response.getEntity();
        if (httpEntity != null) {
            String responseMessage = EntityUtils.toString(httpEntity, "UTF-8");
            validateResponseMessage(response, responseMessage);
            return responseMessage;
        } else {
            return "";
        }
    }

    //Fix for the connection time out problem?
    private DefaultHttpClient getDefaultHttpClientWithHttpParams() {
        HttpParams httpParams = new BasicHttpParams();
        int timeoutConnection = 3000;
        HttpConnectionParams.setConnectionTimeout(httpParams, timeoutConnection);
        int timeoutSocket = 5000;
        HttpConnectionParams.setSoTimeout(httpParams, timeoutSocket);
        return new DefaultHttpClient(httpParams);
    }

    private String getLinkHeaderValue(User user, String type) throws JSONException {
        return String.format("<%s/%s>; rel=\"%s\"", UrlHelper.getApiUrl("/users"), user.getId(), type);
    }

    private String executePutRequest(String targetURL, String urlParameters) throws IOException, JSONException {
        HttpPut httpPut = getHttpPut(targetURL);
        StringEntity stringEntity = getStringEntity(urlParameters);
        httpPut.setEntity(stringEntity);
        return executeHttpMethod(httpPut);
    }

    private String executePutRequest(String targetUrl, JSONObject jsonObject) throws IOException, JSONException {
        return executePutRequest(targetUrl, jsonObject.toString());
    }

    private String executePostRequest(String targetURL, String urlParameters) throws IOException, JSONException {
        HttpPost httpPost = getHttpPost(targetURL);
        StringEntity stringEntity = getStringEntity(urlParameters);
        httpPost.setEntity(stringEntity);
        return executeHttpMethod(httpPost);
    }

    private String executePostRequest(String targetURL, JSONObject jsonObject) throws IOException, JSONException {
        return executePostRequest(targetURL, jsonObject.toString());
    }

    private void executePostRequest(AbstractObject object) throws IOException, JSONException {
        String targetUrl = UrlHelper.getApiUrl(object.getApiUrl());
        JSONObject jsonObject = object.getJsonObject();
        object.setJsonObject(
                new JSONObject(
                        executePostRequest(targetUrl, jsonObject)
                )
        );
    }

    private void validateResponseMessage(HttpResponse httpResponse, String responseMessage) {
        int statusCode = httpResponse.getStatusLine().getStatusCode();
        if (statusCode != 201 && statusCode != 200) {
            StringBuilder builder = new StringBuilder();
            JSONObject mainJsonObject = null;
            try {
                mainJsonObject = new JSONObject(responseMessage);

                if (!mainJsonObject.isNull("errors")) {
                    JSONArray jsonArray = mainJsonObject.getJSONArray("errors");
                    for (int i = 0; i < jsonArray.length(); i++) {
                        builder.append(jsonArray.get(i));
                    }
                } else if (!mainJsonObject.isNull("children")) {
                    JSONObject jsonObject = mainJsonObject.getJSONObject("children");
                    for (Iterator keys = jsonObject.keys(); keys.hasNext(); ) {
                        String key = (String) keys.next();
                        if (jsonObject.get(key) instanceof JSONObject) {
                            JSONArray jsonArray = jsonObject.getJSONObject(key).getJSONArray("errors");
                            for (int i = 0; i < jsonArray.length(); i++) {
                                String message = String.format("%s : '%s'", key, jsonArray.get(i));
                                builder.append(message);
                            }
                        }
                    }
                } else {
                    String message = String.format("message: '%s', statusCode: '%s', status: '%s', statusText: '%s', currentContent: '%s'.",
                            mainJsonObject.getString("message"),
                            mainJsonObject.getString("statusCode"),
                            mainJsonObject.getString("status"),
                            mainJsonObject.getString("statusText"),
                            mainJsonObject.getString("currentContent")
                    );
                    builder.append(message);
                }
            } catch (JSONException e) {
                fail(
                        String.format("Exception message: %s. Json: %s", e.getMessage(),
                                mainJsonObject != null
                                        ? mainJsonObject.toString()
                                        : null)
                );
            }
            fail(
                    String.format("Response json error: '%s'", builder.toString())
            );
        }
    }

    private String executeSimpleGetRequest(String targetUrl, boolean forAccessToken) throws IOException, JSONException {

        //TODO Work around for token expiration 401 : The access token provided has expired.
        HttpGet request = new HttpGet(targetUrl);
        if (forAccessToken) {
            request.setHeader("Authorization", "Bearer " + getAccessToken());
        }
        DefaultHttpClient httpClient = new DefaultHttpClient();
        HttpResponse response = httpClient.execute(request);
        HttpEntity httpEntity = response.getEntity();
        String responseMessage = EntityUtils.toString(httpEntity, "UTF-8");
        validateResponseMessage(response, responseMessage);
        return responseMessage;
    }

    private String getAccessToken() throws JSONException, IOException {
        receiveAccessToken(userName, password);
        return StaticData.userTokens.get(userName).getAccessToken();
    }

    private void receiveAccessToken(String userName, String password) throws JSONException, IOException {
        if (!StaticData.userTokens.containsKey(userName)) {
            String url = String.format("%s/oauth/v2/token", UrlHelper.getApiUrl());
            String parameters = String.format("?grant_type=password&username=%s&password=%s&client_id=%s&client_secret=%s",
                    userName, password, StaticData.client_id, StaticData.client_secret);
            String response = executeSimpleGetRequest(url + parameters, false);
            OauthAuthorizeData oauthAuthorize = new OauthAuthorizeData(new JSONObject(response));
            StaticData.userTokens.put(userName, oauthAuthorize);
        }
    }
}

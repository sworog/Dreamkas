package project.lighthouse.autotests;

import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.entity.StringEntity;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.message.BasicHeader;
import org.apache.http.protocol.HTTP;
import org.apache.http.util.EntityUtils;
import org.json.JSONException;
import org.json.JSONObject;
import project.lighthouse.autotests.elements.DateTime;
import project.lighthouse.autotests.objects.*;

import java.io.IOException;
import java.util.HashMap;
import java.util.Iterator;
import java.util.Map;

public class ApiConnect {

    String userName;
    String password;

    public ApiConnect(String userName, String password) throws JSONException {
        this.userName = userName;
        this.password = password;
    }

    public void сreateProductThroughPost(String name, String sku, String barcode, String units, String purchasePrice) throws JSONException, IOException {
        if (!StaticDataCollections.products.containsKey(sku)) {
            String getApiUrl = UrlHelper.getApiUrl() + "/api/1/products.json";
            String jsonData = Product.getJsonObject(name, units, "0", purchasePrice, barcode, sku, "Тестовая страна", "Тестовый производитель", "").toString();
            String postResponse = executePostRequest(getApiUrl, jsonData);

            Product product = new Product(new JSONObject(postResponse));
            StaticDataCollections.products.put(sku, product);
        }
    }

    public void createInvoiceThroughPost(String invoiceName) throws JSONException, IOException {
        createInvoiceThroughPostWithoutNavigation(invoiceName);
    }

    public void createInvoiceThroughPostWithoutNavigation(String invoiceName) throws JSONException, IOException {
        if (!StaticDataCollections.invoices.containsKey(invoiceName)) {
            String getApiUrl = String.format("%s/api/1/invoices.json", UrlHelper.getApiUrl());
            String jsonData = Invoice.getJsonObject(invoiceName, "supplier", DateTime.getTodayDate(DateTime.DATE_TIME_PATTERN), "accepter", "legalEntity", "", "").toString();
            String postResponse = executePostRequest(getApiUrl, jsonData);

            Invoice invoice = new Invoice(new JSONObject(postResponse));
            StaticDataCollections.invoices.put(invoiceName, invoice);
        }
    }

    public String getInvoicePageUrl(String invoiceName) throws JSONException {
        String invoiceId = StaticDataCollections.invoices.get(invoiceName).getId();
        return String.format("%s/invoices/%s?editMode=true", UrlHelper.getWebFrontUrl(), invoiceId);
    }

    public void createInvoiceThroughPost(String invoiceName, String productSku) throws IOException, JSONException {
        createInvoiceThroughPostWithoutNavigation(invoiceName);
        addProductToInvoice(invoiceName, productSku);
    }

    public void addProductToInvoice(String invoiceName, String productSku)
            throws JSONException, IOException {
        String productId = StaticDataCollections.products.get(productSku).getId();
        String invoiceId = StaticDataCollections.invoices.get(invoiceName).getId();
        String apiUrl = String.format("%s/api/1/invoices/%s/products.json", UrlHelper.getApiUrl(), invoiceId);

        String productJsonData = InvoiceProduct.getJsonObject(productId, "1", "1").toString();
        executePostRequest(apiUrl, productJsonData);
    }

    public void averagePriceRecalculation() throws IOException, JSONException {
        String url = UrlHelper.getApiUrl() + "/api/1/service/recalculate-average-purchase-price";
        String response = executePostRequest(url, "");
        if (!response.contains("{\"ok\":true}")) {
            throw new AssertionError("Average price recalculation failed!\nResponse: " + response);
        }
    }

    public void createWriteOffThroughPost(String writeOffNumber, String productSku, String quantity, String price, String cause)
            throws JSONException, IOException {

        createWriteOffThroughPost(writeOffNumber);
        addProductToWriteOff(writeOffNumber, productSku, quantity, price, cause);
    }

    public void createWriteOffThroughPost(String writeOffNumber) throws IOException, JSONException {
        if (!StaticDataCollections.writeOffs.containsKey(writeOffNumber)) {
            String getApiUrl = String.format("%s/api/1/writeoffs.json", UrlHelper.getApiUrl());
            String jsonData = WriteOff.getJsonObject(writeOffNumber, DateTime.getTodayDate(DateTime.DATE_PATTERN)).toString();
            String postResponse = executePostRequest(getApiUrl, jsonData);

            WriteOff writeOff = new WriteOff(new JSONObject(postResponse));
            StaticDataCollections.writeOffs.put(writeOffNumber, writeOff);
        }
    }

    public void addProductToWriteOff(String writeOffNumber, String productSku, String quantity, String price, String cause)
            throws JSONException, IOException {
        String productId = StaticDataCollections.products.get(productSku).getId();
        String writeOffId = StaticDataCollections.writeOffs.get(writeOffNumber).getId();
        String apiUrl = String.format("%s/api/1/writeoffs/%s/products.json", UrlHelper.getApiUrl(), writeOffId);

        String productJsonData = WriteOffProduct.getJsonObject(productId, quantity, price, cause).toString();
        executePostRequest(apiUrl, productJsonData);
    }

    public String getWriteOffPageUrl(String writeOffNumber) throws JSONException {
        String writeOffId = StaticDataCollections.writeOffs.get(writeOffNumber).getId();
        return String.format("%s/writeOffs/%s", UrlHelper.getWebFrontUrl(), writeOffId);
    }

    public void createKlassThroughPost(String klassName) throws IOException, JSONException {
        if (!StaticDataCollections.klasses.containsKey(klassName)) {
            String getApiUrl = String.format("%s/api/1/klasses.json", UrlHelper.getApiUrl());
            String jsonData = Klass.getJsonObject(klassName).toString();
            String postResponse = executePostRequest(getApiUrl, jsonData);

            Klass klass = new Klass(new JSONObject(postResponse));
            StaticDataCollections.klasses.put(klassName, klass);
        }
    }

    public void createGroupThroughPost(String groupName, String klassName) throws IOException, JSONException {
        createKlassThroughPost(klassName);
        String apiUrl = String.format("%s/api/1/groups.json", UrlHelper.getApiUrl());
        String klassId = StaticDataCollections.klasses.get(klassName).getId();
        String groupJsonData = Group.getJsonObject(groupName, klassId).toString();
        executePostRequest(apiUrl, groupJsonData);
    }

    public String getKlassPageUrl(String klassName) throws JSONException {
        String klassId = StaticDataCollections.klasses.get(klassName).getId();
        return String.format("%s/catalog/%s", UrlHelper.getWebFrontUrl(), klassId);
    }

    public void createUserThroughPost(String name, String position, String login, String password, String role) throws JSONException, IOException {
        if (!StaticDataCollections.users.containsKey(login)) {
            String apiUrl = String.format("%s/api/1/users.json", UrlHelper.getApiUrl());
            String jsonData = User.getJsonObject(name, position, login, password, role).toString();
            String postResponse = executePostRequest(apiUrl, jsonData);

            User user = new User(new JSONObject(postResponse));
            StaticDataCollections.users.put(login, user);
        }
    }

    public String getUserPageUrl(String userName) throws JSONException {
        String userId = StaticDataCollections.users.get(userName).getId();
        return String.format("%s/users/%s", UrlHelper.getWebFrontUrl(), userId);
    }

    private String executePostRequest(String targetURL, String urlParameters) throws IOException, JSONException {
        HttpPost request = new HttpPost(targetURL);
        StringEntity entity = new StringEntity(urlParameters, "UTF-8");
        entity.setContentType("application/json;charset=UTF-8");
        entity.setContentEncoding(new BasicHeader(HTTP.CONTENT_TYPE, "application/json;charset=UTF-8"));
        request.setHeader("Accept", "application/json");
        request.setHeader("Authorization", "Bearer " + getAccessToken());
        request.setEntity(entity);

        DefaultHttpClient httpClient = new DefaultHttpClient();
        httpClient.getParams().setParameter("http.protocol.content-charset", "UTF-8");
        HttpResponse response = httpClient.execute(request);

        HttpEntity httpEntity = response.getEntity();
        String responseMessage = EntityUtils.toString(httpEntity, "UTF-8");
        validateResponseMessage(response, responseMessage);
        return responseMessage;
    }

    private void validateResponseMessage(HttpResponse httpResponse, String responseMessage) {
        int statusCode = httpResponse.getStatusLine().getStatusCode();
        if (statusCode != 201 && statusCode != 200) {
            StringBuilder builder = new StringBuilder();
            JSONObject mainJsonObject = null;
            try {
                mainJsonObject = new JSONObject(responseMessage);
                JSONObject jsonObject = !mainJsonObject.isNull("children") ? mainJsonObject.getJSONObject("children") : mainJsonObject;
                Object[] objects = toMap(jsonObject).values().toArray();

                for (int i = 0; i < objects.length; i++) {
                    if (objects[i] instanceof HashMap) {
                        String errors = new JSONObject(objects[i].toString()).getString("errors");
                        builder.append(errors);
                    }
                    if (objects[i] instanceof String) {
                        builder.append(objects[i] + ";");
                    }
                }
            } catch (JSONException e) {
                String errorMessage = String.format("Exception message: %s\nJson: %s", e.getMessage(), mainJsonObject.toString());
                throw new AssertionError(errorMessage);
            }
            String errorMessage = String.format("Responce json error: '%s'", builder.toString());
            throw new AssertionError(errorMessage);
        }
    }

    private String executeSimpleGetRequest(String targetUrl) throws IOException {

        //TODO Work around for token expiration 401 : The access token provided has expired.
        HttpGet request = new HttpGet(targetUrl);
        DefaultHttpClient httpClient = new DefaultHttpClient();
        HttpResponse response = httpClient.execute(request);
        HttpEntity httpEntity = response.getEntity();
        String responseMessage = EntityUtils.toString(httpEntity, "UTF-8");
        validateResponseMessage(response, responseMessage);
        return responseMessage;
    }

    private String getAccessToken() throws JSONException, IOException {
        receiveAccessToken(userName, password);
        return StaticDataCollections.userTokens.get(userName).getAccessToken();
    }

    private void receiveAccessToken(String userName, String password) throws JSONException, IOException {
        if (!StaticDataCollections.userTokens.containsKey(userName)) {
            String url = String.format("%s/oauth/v2/token", UrlHelper.getApiUrl());
            String parameters = String.format("?grant_type=password&username=%s&password=%s&client_id=%s&client_secret=%s",
                    userName, password, StaticDataCollections.client_id, StaticDataCollections.client_secret);
            String response = executeSimpleGetRequest(url + parameters);
            OauthAuthorizeData oauthAuthorize = new OauthAuthorizeData(new JSONObject(response));
            StaticDataCollections.userTokens.put(userName, oauthAuthorize);
        }
    }

    private static Map<String, Object> toMap(JSONObject object) throws JSONException {
        Map<String, Object> map = new HashMap();
        Iterator keys = object.keys();
        while (keys.hasNext()) {
            String key = (String) keys.next();
            map.put(key, fromJson(object.get(key)));
        }
        return map;
    }

    private static Object fromJson(Object json) throws JSONException {
        if (json == JSONObject.NULL) {
            return null;
        } else if (json instanceof JSONObject) {
            return toMap((JSONObject) json);
        } else {
            return json;
        }
    }
}

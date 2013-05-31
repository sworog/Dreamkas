package project.lighthouse.autotests;

import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.entity.StringEntity;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.message.BasicHeader;
import org.apache.http.protocol.HTTP;
import org.apache.http.util.EntityUtils;
import org.json.JSONException;
import org.json.JSONObject;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.objects.*;
import project.lighthouse.autotests.pages.elements.DateTime;

import java.io.IOException;
import java.util.HashMap;
import java.util.Iterator;
import java.util.Map;

public class ApiConnect {

    WebDriver driver;

    public ApiConnect(WebDriver driver) {
        this.driver = driver;
    }

    public void сreateProductThroughPost(String name, String sku, String barcode, String units, String purchasePrice) throws JSONException, IOException {
        if (!StaticDataCollections.products.containsKey(sku)) {
            String getApiUrl = getApiUrl() + "/api/1/products.json";
            String jsonData = String.format(Product.jsonPattern, name, units, purchasePrice, barcode, sku);
            String postResponse = executePostRequest(getApiUrl, jsonData);

            Product product = new Product(new JSONObject(postResponse));
            StaticDataCollections.products.put(sku, product);
        }
    }

    public void createInvoiceThroughPost(String invoiceName) throws JSONException, IOException {
        createInvoiceThroughPostWithoutNavigation(invoiceName);
        navigateToInvoicePage(invoiceName);
    }

    public void createInvoiceThroughPostWithoutNavigation(String invoiceName) throws JSONException, IOException {
        if (!StaticDataCollections.invoices.containsKey(invoiceName)) {
            String getApiUrl = String.format("%s/api/1/invoices.json", getApiUrl());
            String jsonData = String.format(Invoice.jsonPattern, invoiceName, DateTime.getTodayDate(DateTime.DATE_TIME_PATTERN));
            String postResponse = executePostRequest(getApiUrl, jsonData);

            Invoice invoice = new Invoice(new JSONObject(postResponse));
            StaticDataCollections.invoices.put(invoiceName, invoice);
        }
    }

    public void navigateToInvoicePage(String invoiceName) throws JSONException {
        String invoiceId = StaticDataCollections.invoices.get(invoiceName).getId();
        String invoiceUrl = String.format("%s/invoice/%s?editMode=true", getApiUrl().replace("api", "webfront"), invoiceId);
        driver.navigate().to(invoiceUrl);
    }

    public void createInvoiceThroughPost(String invoiceName, String productSku) throws IOException, JSONException {
        createInvoiceThroughPost(invoiceName);
        addProductToInvoice(invoiceName, productSku);
    }

    public void averagePriceRecalculation() {
        String url = getApiUrl() + "/api/1/service/recalculate-average-purchase-price";
        String response = executePost(url, "");
        if (!response.contains("{\"ok\":true}")) {
            throw new AssertionError("Average price recalculation failed!");
        }
    }

    public void createWriteOffThroughPost(String writeOffNumber, String productName, String productSku, String productBarCode, String productUnits, String purchasePrice,
                                          String quantity, String price, String cause)
            throws JSONException, IOException {

        createWriteOffThroughPost(writeOffNumber);
        addProductToWriteOff(writeOffNumber, productSku, productUnits, purchasePrice, quantity, price, cause);
    }

    public void createWriteOffThroughPost(String writeOffNumber) throws IOException, JSONException {
        if (!StaticDataCollections.writeOffs.containsKey(writeOffNumber)) {
            String getApiUrl = String.format("%s/api/1/writeoffs.json", getApiUrl());

            String jsonData = String.format(WriteOff.jsonPattern, writeOffNumber, DateTime.getTodayDate(DateTime.DATE_PATTERN));
            String postResponse = executePostRequest(getApiUrl, jsonData);

            WriteOff writeOff = new WriteOff(new JSONObject(postResponse));
            StaticDataCollections.writeOffs.put(writeOffNumber, writeOff);
        }
    }

    public void addProductToWriteOff(String writeOffNumber, String productSku, String productUnits, String purchasePrice, String quantity, String price, String cause)
            throws JSONException, IOException {
        if (!StaticDataCollections.products.containsKey(productSku)) {
            сreateProductThroughPost(productSku, productSku, productSku, productUnits, purchasePrice);
        }
        String productId = StaticDataCollections.products.get(productSku).getId();
        String writeOffId = StaticDataCollections.writeOffs.get(writeOffNumber).getId();
        String apiUrl = String.format("%s/api/1/writeoffs/%s/products.json", getApiUrl(), writeOffId);

        String productJsonData = String.format("{\"writeOffProduct\":{\"product\":\"%s\",\"quantity\":\"%s\",\"price\":\"%s\",\"cause\":\"%s\"}}", productId, quantity, price, cause);
        executePostRequest(apiUrl, productJsonData);
    }

    public void addProductToInvoice(String InvoiceName, String productSku)
            throws JSONException, IOException {
        String productId = StaticDataCollections.products.get(productSku).getId();
        String invoiceId = StaticDataCollections.invoices.get(InvoiceName).getId();
        String apiUrl = String.format("%s/api/1/invoices/%s/products.json", getApiUrl(), invoiceId);

        String productJsonData = String.format("{\"invoiceProduct\":{\"product\":\"%s\",\"quantity\":\"%s\",\"price\":\"%s\"}}", productId, "1", "1");
        executePostRequest(apiUrl, productJsonData);
    }

    public void createWriteOffAndNavigateToIt(String writeOffNumber, String productName, String productSku, String productBarCode, String productUnits, String purchasePrice,
                                              String quantity, String price, String cause)
            throws JSONException, IOException {
        createWriteOffThroughPost(writeOffNumber, productName, productSku, productBarCode, productUnits, purchasePrice, quantity, price, cause);
        navigatoToWriteOffPage(writeOffNumber);
    }

    public void createWriteOffAndNavigateToIt(String writeOffNumber) throws JSONException, IOException {
        createWriteOffThroughPost(writeOffNumber);
        navigatoToWriteOffPage(writeOffNumber);
    }

    public void navigatoToWriteOffPage(String writeOffNumber) throws JSONException {
        String writeOffId = StaticDataCollections.writeOffs.get(writeOffNumber).getId();
//        String url = String.format("%s/writeOff/%s?editMode=true", getWebFrontUrl(), writeOffId);
        String url = String.format("%s/writeOff/%s", getWebFrontUrl(), writeOffId);
        driver.navigate().to(url);
    }

    public void createKlassThroughPost(String klassName) throws IOException, JSONException {
        if (!StaticDataCollections.klasses.containsKey(klassName)) {
            String getApiUrl = String.format("%s/api/1/klasses.json", getApiUrl());
            String jsonData = String.format(Klass.jsonPattern, klassName);
            String postResponse = executePostRequest(getApiUrl, jsonData);

            Klass klass = new Klass(new JSONObject(postResponse));
            StaticDataCollections.klasses.put(klassName, klass);
        }
    }

    public void createGroupThroughPost(String groupName, String klassName) throws IOException, JSONException {
        createKlassThroughPost(klassName);
        String apiUrl = String.format("%s/api/1/groups.json", getApiUrl());
        String klassId = StaticDataCollections.klasses.get(klassName).getId();
        String groupJsonData = String.format(Group.jsonPattern, groupName, klassName);
        executePostRequest(apiUrl, groupJsonData);
    }

    public void navigateToKlassPage(String klassName) throws JSONException {
        String klassId = StaticDataCollections.klasses.get(klassName).getId();
        String url = String.format("%s/catalog/%s", getWebFrontUrl(), klassId);
        driver.navigate().to(url);
    }

    private String executePostRequest(String targetURL, String urlParameters) throws IOException {
        HttpPost request = new HttpPost(targetURL);
        StringEntity entity = new StringEntity(urlParameters, "UTF-8");
        entity.setContentType("application/json;charset=UTF-8");
        entity.setContentEncoding(new BasicHeader(HTTP.CONTENT_TYPE, "application/json;charset=UTF-8"));
        request.setHeader("Accept", "application/json");
        request.setEntity(entity);

        DefaultHttpClient httpClient = new DefaultHttpClient();
        httpClient.getParams().setParameter("http.protocol.content-charset", "UTF-8");
        HttpResponse response = httpClient.execute(request);

        HttpEntity httpEntity = response.getEntity();
        String responseMessage = EntityUtils.toString(httpEntity, "UTF-8");
        if (response.getStatusLine().getStatusCode() != 201) {
            StringBuilder builder = new StringBuilder();
            JSONObject jsonObject = null;
            try {
                jsonObject = new JSONObject(responseMessage).getJSONObject("children");
                Object[] objects = toMap(jsonObject).values().toArray();

                for (int i = 0; i < objects.length; i++) {
                    if (objects[i] instanceof HashMap) {
                        String errors = new JSONObject(objects[i].toString()).getString("errors");
                        builder.append(errors);
                    }
                }
            } catch (JSONException e) {
                String errorMessage = String.format("Exception message: %s\nJson: %s", e.getMessage(), jsonObject.toString());
                throw new AssertionError(errorMessage);
            }
            String errorMessage = String.format("Responce json error: '%s'", builder.toString());
            throw new AssertionError(errorMessage);
        }
        return responseMessage;
    }

    private String executePost(String targetUrl, String urlParameters) {
        try {
            HttpPost request = new HttpPost(targetUrl);
            StringEntity entity = new StringEntity(urlParameters, "UTF-8");
            entity.setContentType("application/json;charset=UTF-8");
            entity.setContentEncoding(new BasicHeader(HTTP.CONTENT_TYPE, "application/json;charset=UTF-8"));
            request.setHeader("Accept", "application/json");
            request.setEntity(entity);

            HttpResponse response = null;
            DefaultHttpClient httpClient = new DefaultHttpClient();
            httpClient.getParams().setParameter("http.protocol.content-charset", "UTF-8");
            response = httpClient.execute(request);

            HttpEntity httpEntity = response.getEntity();
            return EntityUtils.toString(httpEntity, "UTF-8");
        } catch (Exception e) {
            throw new AssertionError(e.getMessage());
        }
    }

    private String getApiUrl() {
        return StaticDataCollections.WEB_DRIVER_BASE_URL.replace("webfront", "api");
    }

    private String getWebFrontUrl() {
        return StaticDataCollections.WEB_DRIVER_BASE_URL;
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

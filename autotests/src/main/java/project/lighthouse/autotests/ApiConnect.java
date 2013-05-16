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
import project.lighthouse.autotests.objects.Invoice;
import project.lighthouse.autotests.objects.Product;
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
            String jsonData = String.format(Product.productJsonDataPattern, name, units, purchasePrice, barcode, sku);
            String postResponse = executePostRequest(getApiUrl, jsonData);

            Product product = new Product(new JSONObject(postResponse));
            StaticDataCollections.products.put(sku, product);
        }
    }

    public void createInvoiceThroughPost(String invoiceName) throws JSONException, IOException {
        if (!StaticDataCollections.invoices.containsKey(invoiceName)) {
            String getApiUrl = String.format("%s/api/1/invoices.json", getApiUrl());
            String jsonData = String.format(Invoice.invoiceJsonPattern, invoiceName, DateTime.getTodayDate(DateTime.DATE_TIME_PATTERN));
            String postResponse = executePostRequest(getApiUrl, jsonData);

            Invoice invoice = new Invoice(new JSONObject(postResponse));
            StaticDataCollections.invoices.put(invoiceName, invoice);

            String invoiceUrl = String.format("%s/invoice/%s?editMode=true", getApiUrl().replace("api", "webfront"), invoice.getInvoiceId());
            driver.navigate().to(invoiceUrl);
        }
    }

    public void averagePriceRecalculation() {
        String url = getApiUrl() + "/api/1/service/recalculate-average-purchase-price";
        String response = executePost(url, "");
        if (!response.contains("{\"ok\":true}")) {
            throw new AssertionError("Average price recalculation failed!");
        }
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
        return "http://" + driver.getCurrentUrl().replaceFirst(".*//*/(.*)\\.webfront\\.([a-zA-Z\\.]+)/.*", "$1.api.$2");
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

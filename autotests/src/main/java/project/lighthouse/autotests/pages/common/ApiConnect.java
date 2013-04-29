package project.lighthouse.autotests.pages.common;

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
import project.lighthouse.autotests.StaticDataCollections;
import project.lighthouse.autotests.pages.elements.DateTime;

import java.util.HashMap;
import java.util.Iterator;
import java.util.Map;

public class ApiConnect {

    WebDriver driver;

    public ApiConnect(WebDriver driver) {
        this.driver = driver;
    }

    public void сreateProductThroughPost(String name, String sku, String barcode, String units, String purchasePrice) {
        if (!StaticDataCollections.products.contains(sku)) {
            String getApiUrl = getApiUrl() + "/api/1/products.json";
            String jsonDataPattern = "{\"product\":{\"name\":\"%s\",\"units\":\"%s\",\"vat\":\"0\",\"purchasePrice\":\"%s\",\"barcode\":\"%s\",\"sku\":\"%s\",\"vendorCountry\":\"Тестовая страна\",\"vendor\":\"Тестовый производитель\",\"info\":\"\"}}";
            String jsonData = String.format(jsonDataPattern, name, units, purchasePrice, barcode, sku);
            executePost(getApiUrl, jsonData);
            StaticDataCollections.products.add(sku);
        }
    }

    public void createInvoiceThroughPost(String invoiceName) {
        if (!StaticDataCollections.invoices.contains(invoiceName)) {
            String getApiUrl = String.format("%s/api/1/invoices.json", getApiUrl());
            String jsonDataPattern = "{\"invoice\":{\"sku\":\"%s\",\"supplier\":\"supplier\",\"acceptanceDate\":\"%s\",\"accepter\":\"accepter\",\"legalEntity\":\"legalEntity\",\"supplierInvoiceSku\":\"\",\"supplierInvoiceDate\":\"\"}}";
            String jsonData = String.format(jsonDataPattern, invoiceName, DateTime.getTodayDate(DateTime.DATE_TIME_PATTERN));
            String postResponse = executePost(getApiUrl, jsonData);
            StaticDataCollections.invoices.add(invoiceName);
            try {
                JSONObject object = new JSONObject(postResponse);
                String invoiceId = (String) object.get("id");
                String invoiceUrl = String.format("%s/invoice/products/%s", getApiUrl().replace("api", "webfront"), invoiceId);
                driver.navigate().to(invoiceUrl);
            } catch (Exception e) {
                e.printStackTrace();
                throw new AssertionError(e.getMessage());
            }
        }
    }

    private String executePost(String targetURL, String urlParameters) {
        HttpPost request = new HttpPost(targetURL);
        try {
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
            String responceMessage = EntityUtils.toString(httpEntity, "UTF-8");

            if (response.getStatusLine().getStatusCode() != 201) {
                StringBuilder builder = new StringBuilder();
                JSONObject jsonObject = new JSONObject(responceMessage).getJSONObject("children");
                Object[] objects = toMap(jsonObject).values().toArray();
                for (int i = 0; i < objects.length; i++) {
                    if (objects[i] instanceof HashMap) {
                        String errors = new JSONObject(objects[i].toString()).getString("errors");
                        builder.append(errors);
                    }
                }
                String errorMessage = String.format("Responce json error: '%s'", builder.toString());
                throw new AssertionError(errorMessage);
            }
            return responceMessage;
        } catch (Exception e) {
            e.printStackTrace();
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

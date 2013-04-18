package project.lighthouse.autotests.pages.common;

import net.thucydides.core.pages.PageObject;
import net.thucydides.core.pages.WebElementFacade;
import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.entity.StringEntity;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.message.BasicHeader;
import org.apache.http.protocol.HTTP;
import org.apache.http.util.EntityUtils;
import org.jbehave.core.model.ExamplesTable;
import org.json.JSONException;
import org.json.JSONObject;
import org.openqa.selenium.Alert;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.CommonPageInterface;
import project.lighthouse.autotests.pages.elements.Autocomplete;
import project.lighthouse.autotests.pages.elements.DateTime;
import project.lighthouse.autotests.pages.invoice.InvoiceListPage;
import project.lighthouse.autotests.pages.product.ProductListPage;

import java.util.*;

public class CommonPage extends PageObject implements CommonPageInterface {

    public static final String ERROR_MESSAGE = "No such option for '%s'";
    public static final String STRING_EMPTY = "";

    public ArrayList<String> products = new ArrayList<>();
    public ArrayList<String> invoices = new ArrayList<>();

    public CommonPage(WebDriver driver) {
        super(driver);
    }

    public void isRequiredPageOpen(String pageObjectName) {
        String defaultUrl = getPageObjectDefaultUrl(pageObjectName).replaceFirst(".*\\(value=(.*)\\)", "$1");
        String actualUrl = getDriver().getCurrentUrl();
        if (!actualUrl.contains(defaultUrl)) {
            String errorMessage = String.format("The %s is not open!\nActual url: %s\nExpected url: %s", pageObjectName, actualUrl, defaultUrl);
            throw new AssertionError(errorMessage);
        }
    }

    public String getPageObjectDefaultUrl(String pageObjectName) {
        switch (pageObjectName) {
            case "ProductListPage":
                return ProductListPage.class.getAnnotations()[0].toString();
            case "InvoiceListPage":
                return InvoiceListPage.class.getAnnotations()[0].toString();
            default:
                String errorMessage = String.format(ERROR_MESSAGE, pageObjectName);
                throw new AssertionError(errorMessage);
        }
    }

    public String generateTestData(int n) {
        String str = "a";
        String testData = new String(new char[n]).replace("\0", str);
        StringBuilder formattedData = new StringBuilder(testData);
        for (int i = 0; i < formattedData.length(); i++) {
            if (i % 26 == 1) {
                formattedData.setCharAt(i, ' ');
            }
        }
        return formattedData.toString();
    }

    public boolean isPresent(String xpath) {
        try {
            return findBy(xpath).isPresent();
        } catch (Exception e) {
            return false;
        }
    }

    public void checkCreateAlertSuccess(String name) {
        boolean isAlertPresent;
        Alert alert = null;
        try {
            alert = getAlert();
            isAlertPresent = true;
        } catch (Exception e) {
            isAlertPresent = false;
        }
        if (isAlertPresent) {
            String errorAlertMessage = "Ошибка";
            String alertText = alert.getText();
            if (alertText.contains(errorAlertMessage)) {
                String errorMessage = String.format("Can't create new '%s'. Error alert is present. Alert text: %s", name, alertText);
                throw new AssertionError(errorMessage);
            }
        }
    }

    public void checkFieldLength(String elementName, int fieldLength, int actualLength) {
        if (actualLength != fieldLength) {
            String errorMessage = String.format("The '%s' field doesn't contains '%s' symbols. It actually contains '%s' symbols.", elementName, fieldLength, actualLength);
            throw new AssertionError(errorMessage);
        }
    }

    public void checkFieldLength(String elementName, int fieldLength, CommonItem item) {
        checkFieldLength(elementName, fieldLength, item.length());
    }

    public void checkFieldLength(String elementName, int fieldLength, WebElement element) {
        int length;
        switch (element.getTagName()) {
            case "input":
                length = $(element).getTextValue().length();
                break;
            case "textarea":
                length = $(element).getValue().length();
                break;
            default:
                length = $(element).getText().length();
                break;
        }
        checkFieldLength(elementName, fieldLength, length);
    }

    public void checkErrorMessages(ExamplesTable errorMessageTable) {
        for (Map<String, String> row : errorMessageTable.getRows()) {
            String expectedErrorMessage = row.get("error message");
            String xpath = String.format("//*[contains(@lh_field_error,'%s')]", expectedErrorMessage);
            if (!isPresent(xpath)) {
                String errorXpath = "//*[@lh_field_error]";
                String errorMessage;
                if (isPresent(errorXpath)) {
                    List<WebElementFacade> webElementList = findAll(By.xpath(errorXpath));
                    StringBuilder builder = new StringBuilder("Another validation errors are present:");
                    for (WebElementFacade element : webElementList) {
                        builder.append(element.getAttribute("lh_field_error"));
                    }
                    errorMessage = builder.toString();
                } else {
                    errorMessage = "There are no error field validation messages on the page!";
                }
                throw new AssertionError(errorMessage);
            }
        }
    }

    public void checkNoErrorMessages(ExamplesTable errorMessageTable) {
        for (Map<String, String> row : errorMessageTable.getRows()) {
            String expectedErrorMessage = row.get("error message");
            String xpath = String.format("//*[contains(@lh_field_error,'%s')]", expectedErrorMessage);
            if (isPresent(xpath)) {
                String errorMessage = ("The error message is present!");
                throw new AssertionError(errorMessage);
            }
        }
    }

    public void checkNoErrorMessages() {
        String xpath = "//*[@lh_field_error]";
        if (isPresent(xpath)) {
            String errorMessage = "There are error field validation messages on the page!";
            throw new AssertionError(errorMessage);
        }
    }

    public void setValue(CommonItem item, String value) {
        item.setValue(value);
    }

    public void checkAutoCompleteNoResults() {
        String xpath = "//*[@role='presentation']/*[text()]";
        if (isPresent(xpath)) {
            String errorMessage = "There are autocomplete results on the page";
            throw new AssertionError(errorMessage);
        }
    }

    public void checkAutoCompleteResults(ExamplesTable checkValuesTable) {
        for (Map<String, String> row : checkValuesTable.getRows()) {
            String autoCompleteValue = row.get("autocomlete result");
            String xpathPattern = String.format(Autocomplete.AUTOCOMPLETE_XPATH_PATTERN, autoCompleteValue);
            findBy(xpathPattern).shouldBePresent();
        }
    }

    public void shouldContainsText(String elementName, WebElement element, String expectedValue) {
        String actualValue;
        switch (element.getTagName()) {
            case "input":
                actualValue = $(element).getTextValue();
                break;
            default:
                actualValue = $(element).getText();
                break;
        }
        if (!actualValue.contains(expectedValue)) {
            String errorMessage = String.format("Element '%s' doesnt contain '%s'. It contains '%s'", elementName, expectedValue, actualValue);
            throw new AssertionError(errorMessage);
        }
    }

    public void сreateProductThroughPost(String name, String sku, String barcode, String units) {
        if (!products.contains(sku)) {
            String getApiUrl = getApiUrl() + "/api/1/products.json";
            String jsonDataPattern = "{\"product\":{\"name\":\"%s\",\"units\":\"%s\",\"vat\":\"0\",\"purchasePrice\":\"123\",\"barcode\":\"%s\",\"sku\":\"%s\",\"vendorCountry\":\"Тестовая страна\",\"vendor\":\"Тестовый производитель\",\"info\":\"\"}}";
            String jsonData = String.format(jsonDataPattern, name, units, barcode, sku);
            executePost(getApiUrl, jsonData);
            products.add(sku);
        }
    }

    public void createInvoiceThroughPost(String invoiceName) {
        if (!invoices.contains(invoiceName)) {
            String getApiUrl = String.format("%s/api/1/invoices.json", getApiUrl());
            String jsonDataPattern = "{\"invoice\":{\"sku\":\"%s\",\"supplier\":\"supplier\",\"acceptanceDate\":\"%s\",\"accepter\":\"accepter\",\"legalEntity\":\"legalEntity\",\"supplierInvoiceSku\":\"\",\"supplierInvoiceDate\":\"\"}}";
            String jsonData = String.format(jsonDataPattern, invoiceName, DateTime.getTodayDate(DateTime.DATE_TIME_PATTERN));
            String postResponse = executePost(getApiUrl, jsonData);
            invoices.add(invoiceName);
            try {
                JSONObject object = new JSONObject(postResponse);
                String invoiceId = (String) object.get("id");
                String invoiceUrl = String.format("%s/invoice/products/%s", getApiUrl().replace("api", "webfront"), invoiceId);
                getDriver().navigate().to(invoiceUrl);
            } catch (Exception e) {
                e.printStackTrace();
                throw new AssertionError(e.getMessage());
            }
        }
    }

    public String executePost(String targetURL, String urlParameters) {
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

    public String getApiUrl() {
        return "http://" + getDriver().getCurrentUrl().replaceFirst(".*//*/(.*)\\.webfront\\.([a-zA-Z\\.]+)/.*", "$1.api.$2");
    }

    public static Map<String, Object> toMap(JSONObject object) throws JSONException {
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

    public void checkAlertText(String expectedText) {
        boolean isAlertPresent;
        Alert alert;
        try {
            alert = getAlert();
            isAlertPresent = true;
        } catch (Exception e) {
            String errorMessage = "No alert is present";
            throw new AssertionError(errorMessage);
        }
        if (isAlertPresent) {
            String alertText = alert.getText();
            if (alertText.contains(expectedText)) {
                String errorMessage = String.format("Alert text is '%s'. Should be '%s'.", alert, expectedText);
                throw new AssertionError(errorMessage);
            }
        }
        alert.accept();
    }

    public void NoAlertIsPresent() {
        try {
            String alertText = getAlert().getText();
            throw new AssertionError(String.format("Alert is present! Alert text: '%s'", alertText));
        } catch (Exception e) {
//            e.printStackTrace();
        }
    }
}

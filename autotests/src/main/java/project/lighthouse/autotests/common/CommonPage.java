package project.lighthouse.autotests.common;

import net.thucydides.core.pages.PageObject;
import net.thucydides.core.pages.WebElementFacade;
import org.jbehave.core.model.ExamplesTable;
import org.json.JSONException;
import org.openqa.selenium.Alert;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.ApiConnect;
import project.lighthouse.autotests.CommonPageInterface;
import project.lighthouse.autotests.Waiter;
import project.lighthouse.autotests.pages.elements.Autocomplete;
import project.lighthouse.autotests.pages.invoice.InvoiceListPage;
import project.lighthouse.autotests.pages.product.ProductListPage;

import java.io.IOException;
import java.util.List;
import java.util.Map;

public class CommonPage extends PageObject implements CommonPageInterface {

    public static final String ERROR_MESSAGE = "No such option for '%s'";
    public static final String STRING_EMPTY = "";

    protected ApiConnect apiConnect = new ApiConnect(getDriver());

    protected Waiter waiter = new Waiter(getDriver());

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
        return generateTestData(n, "a");
    }

    public String generateTestData(int n, String str) {
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

    public void сreateProductThroughPost(String name, String sku, String barcode, String units, String purchasePrice) throws JSONException, IOException {
        apiConnect.сreateProductThroughPost(name, sku, barcode, units, purchasePrice);
    }

    public void createInvoiceThroughPost(String invoiceName) throws JSONException, IOException {
        apiConnect.createInvoiceThroughPost(invoiceName);
    }

    public void createInvoiceThroughPost(String invoiceName, String productSku) throws IOException, JSONException {
        apiConnect.createInvoiceThroughPost(invoiceName, productSku);
    }

    public void createWriteOffThroughPost(String writeOffNumber) throws IOException, JSONException {
        apiConnect.createWriteOffThroughPost(writeOffNumber);
    }

    public void createWriteOffThroughPost(String writeOffNumber, String productName, String productSku, String productBarCode, String productUnits, String purchasePrice,
                                          String quantity, String price, String cause)
            throws IOException, JSONException {
        apiConnect.createWriteOffThroughPost(writeOffNumber, productName, productSku, productBarCode, productUnits, purchasePrice, quantity, price, cause);
    }

    public void createWriteOffAndNavigateToIt(String writeOffNumber, String productName, String productSku, String productBarCode, String productUnits, String purchasePrice,
                                              String quantity, String price, String cause)
            throws JSONException, IOException {
        apiConnect.createWriteOffAndNavigateToIt(writeOffNumber, productName, productSku, productBarCode, productUnits, purchasePrice, quantity, price, cause);
    }

    public void createWriteOffAndNavigateToIt(String writeOffNumber)
            throws JSONException, IOException {
        apiConnect.createWriteOffAndNavigateToIt(writeOffNumber);
    }

    public void navigatoToWriteOffPage(String writeOffNumber) throws JSONException {
        apiConnect.navigatoToWriteOffPage(writeOffNumber);
    }

    public void averagePriceCalculation() {
        apiConnect.averagePriceRecalculation();
    }

    public void createKlassThroughPost(String klassName) throws IOException, JSONException {
        apiConnect.createKlassThroughPost(klassName);
    }

    public void createGroupThroughPost(String groupName, String klassName) throws IOException, JSONException {
        apiConnect.createGroupThroughPost(groupName, klassName);
    }

    public void navigateToKlassPage(String klassName) throws JSONException {
        apiConnect.navigateToKlassPage(klassName);
    }

    public void checkAlertText(String expectedText) {
        Alert alert = waiter.getAlert();
        String alertText = alert.getText();
        if (!alertText.contains(expectedText)) {
            String errorMessage = String.format("Alert text is '%s'. Should be '%s'.", alert, expectedText);
            throw new AssertionError(errorMessage);
        }
        alert.accept();
    }

    public void NoAlertIsPresent() {
        try {
            String alertText = getAlert().getText();
            throw new AssertionError(String.format("Alert is present! Alert text: '%s'", alertText));
        } catch (Exception e) {
        }
    }
}

package project.lighthouse.autotests.pages.common;

import net.thucydides.core.pages.PageObject;
import net.thucydides.core.pages.WebElementFacade;
import org.jbehave.core.model.ExamplesTable;
import org.openqa.selenium.Alert;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.CommonPageInterface;
import project.lighthouse.autotests.pages.elements.Autocomplete;
import project.lighthouse.autotests.pages.invoice.InvoiceListPage;
import project.lighthouse.autotests.pages.product.ProductListPage;

import java.util.List;
import java.util.Map;

public class CommonPage extends PageObject implements CommonPageInterface {

    public static final String ERROR_MESSAGE = "No such option for '%s'";
    public static final String STRING_EMPTY = "";

    protected ApiConnect apiConnect = new ApiConnect(getDriver());

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

    public void сreateProductThroughPost(String name, String sku, String barcode, String units, String purchasePrice) {
        apiConnect.сreateProductThroughPost(name, sku, barcode, units, purchasePrice);
    }

    public void createInvoiceThroughPost(String invoiceName) {
        apiConnect.createInvoiceThroughPost(invoiceName);
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
        }
    }
}

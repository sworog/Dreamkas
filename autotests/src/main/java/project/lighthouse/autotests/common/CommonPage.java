package project.lighthouse.autotests.common;

import net.thucydides.core.pages.PageObject;
import net.thucydides.core.pages.WebElementFacade;
import org.jbehave.core.model.ExamplesTable;
import org.openqa.selenium.Alert;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.CommonPageInterface;
import project.lighthouse.autotests.Waiter;
import project.lighthouse.autotests.elements.Autocomplete;
import project.lighthouse.autotests.pages.commercialManager.product.ProductListPage;
import project.lighthouse.autotests.pages.departmentManager.invoice.InvoiceListPage;

import java.util.List;
import java.util.Map;

public class CommonPage extends PageObject implements CommonPageInterface {

    public static final String ERROR_MESSAGE = "No such option for '%s'";

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

    public String generateTestDataWithoutWhiteSpaces(int n) {
        return generateString(n, "a");
    }

    public String generateTestDataWithoutWhiteSpaces(int n, String str) {
        return generateString(n, str);
    }

    public String generateTestData(int n, String str) {
        String testData = generateString(n, str);
        StringBuilder formattedData = new StringBuilder(testData);
        for (int i = 0; i < formattedData.length(); i++) {
            if (i % 26 == 1) {
                formattedData.setCharAt(i, ' ');
            }
        }
        return formattedData.toString();
    }

    public String generateString(int n, String str) {
        return new String(new char[n]).replace("\0", str);
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
                    errorMessage = getErrorMessages(errorXpath);
                } else {
                    errorMessage = "There are no error field validation messages on the page!";
                }
                throw new AssertionError(errorMessage);
            }
        }
    }

    public String getErrorMessages(String xpath) {
        List<WebElementFacade> webElementList = findAll(By.xpath(xpath));
        StringBuilder builder = new StringBuilder("Validation errors are present: ");
        for (WebElementFacade element : webElementList) {
            builder.append(element.getAttribute("lh_field_error"));
        }
        return builder.toString();
    }

    public void checkNoErrorMessages(ExamplesTable errorMessageTable) {
        for (Map<String, String> row : errorMessageTable.getRows()) {
            String expectedErrorMessage = row.get("error message");
            String xpath = String.format("//*[contains(@lh_field_error,'%s')]", expectedErrorMessage);
            if (isPresent(xpath)) {
                String errorMessage = getErrorMessages(xpath);
                throw new AssertionError(errorMessage);
            }
        }
    }

    public void checkNoErrorMessages() {
        String xpath = "//*[@lh_field_error]";
        if (isPresent(xpath)) {
            String errorMessage = getErrorMessages(xpath);
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

    public void checkAlertText(String expectedText) {
        Alert alert = waiter.getAlert();
        String alertText = alert.getText();
        alert.accept();
        if (!alertText.contains(expectedText)) {
            String errorMessage = String.format("Alert text is '%s'. Should be '%s'.", alert, expectedText);
            throw new AssertionError(errorMessage);
        }
    }

    public void NoAlertIsPresent() {
//        try {
//            String alertText = getAlert().getText();
//            throw new AssertionError(String.format("Alert is present! Alert text: '%s'", alertText));
//        } catch (Exception e) {
//        }
        try {
            Alert alert = waiter.getAlert();
            throw new AssertionError(String.format("Alert is present! Alert text: '%s'", alert.getText()));
        } catch (Exception e) {

        }
    }
}

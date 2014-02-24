package project.lighthouse.autotests.common;

import net.thucydides.core.pages.PageObject;
import org.hamcrest.Matchers;
import org.jbehave.core.model.ExamplesTable;
import org.openqa.selenium.Alert;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.Waiter;
import project.lighthouse.autotests.elements.Autocomplete;

import java.util.Map;

import static junit.framework.Assert.*;
import static org.junit.Assert.assertThat;

public class CommonPage extends PageObject {

    public static final String ERROR_MESSAGE = "No such option for '%s'";

    protected Waiter waiter = new Waiter(getDriver());

    public CommonPage(WebDriver driver) {
        super(driver);
    }

    public boolean isPresent(String xpath) {
        try {
            return findBy(xpath).isPresent();
        } catch (Exception e) {
            return false;
        }
    }

    public void setValue(CommonItem item, String value) {
        item.setValue(value);
    }

    public void checkAutoCompleteNoResults() {
        String xpath = "//*[@role='presentation']/*[text()]";
        assertFalse("There are autocomplete results on the page", isPresent(xpath));
    }

    public void checkAutoCompleteResults(ExamplesTable checkValuesTable) {
        for (Map<String, String> row : checkValuesTable.getRows()) {
            String autoCompleteValue = row.get("autocomlete result");
            checkAutoCompleteResult(autoCompleteValue);
        }
    }

    public void checkAutoCompleteResult(String autoCompleteValue) {
        String xpathPattern = String.format(Autocomplete.AUTOCOMPLETE_XPATH_PATTERN, autoCompleteValue);
        waiter.getVisibleWebElement(By.xpath(xpathPattern));
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
        assertTrue(
                String.format("Element '%s' doesnt contain '%s'. It contains '%s'", elementName, expectedValue, actualValue),
                actualValue.contains(expectedValue)
        );
    }

    public void checkAlertText(String expectedText) {
        Alert alert = waiter.getAlert();
        String alertText = alert.getText();
        alert.accept();
        assertEquals(
                String.format("Alert text is '%s'. Should be '%s'.", alertText, expectedText),
                alertText, expectedText);
    }

    public void NoAlertIsPresent() {
        try {
            Alert alert = waiter.getAlert();
            fail(
                    String.format("Alert is present! Alert text: '%s'", alert.getText())
            );
        } catch (Exception ignored) {
        }
    }

    public void checkDropDownDefaultValue(WebElement dropDownElement, String expectedValue) {
        String selectedValue = $(dropDownElement).getSelectedVisibleTextValue();
        assertThat(
                "The dropDawn value:",
                selectedValue, Matchers.containsString(expectedValue)
        );
    }

    public void pageContainsText(String text) {
        waiter.getVisibleWebElement(
                By.xpath(
                        String.format("//*[contains(normalize-space(text()), '%s')]", text)
                )
        );
    }
}

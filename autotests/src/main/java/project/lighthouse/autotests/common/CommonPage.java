package project.lighthouse.autotests.common;

import org.jbehave.core.model.ExamplesTable;
import org.openqa.selenium.Alert;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.elements.Autocomplete;

import java.util.Map;

import static junit.framework.Assert.assertFalse;
import static junit.framework.Assert.fail;

public class CommonPage extends CommonPageObject {

    public static final String ERROR_MESSAGE = "No such option for '%s'";

    public CommonPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
    }

    public boolean isPresent(String xpath) {
        try {
            return findBy(xpath).isPresent();
        } catch (Exception e) {
            return false;
        }
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
        getWaiter().getVisibleWebElement(By.xpath(xpathPattern));
    }

    public void NoAlertIsPresent() {
        try {
            Alert alert = getWaiter().getAlert();
            fail(
                    String.format("Alert is present! Alert text: '%s'", alert.getText())
            );
        } catch (Exception ignored) {
        }
    }

    public void pageContainsText(String text) {
        getWaiter().getVisibleWebElement(
                By.xpath(
                        String.format("//*[contains(normalize-space(text()), '%s')]", text)
                )
        );
    }
}

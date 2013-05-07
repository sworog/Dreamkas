package project.lighthouse.autotests.pages.common;

import net.thucydides.core.pages.PageObject;
import net.thucydides.core.pages.WebElementFacade;
import org.jbehave.core.model.ExamplesTable;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.Waiter;

import java.util.HashMap;
import java.util.Map;

abstract public class CommonPageObject extends PageObject {

    protected CommonPage commonPage = new CommonPage(getDriver());

    protected Waiter waiter = new Waiter(getDriver());

    public Map<String, CommonItem> items = new HashMap();

    private String errorMessage1 = "Element not found in the cache - perhaps the page has changed since it was looked up";
    private String errorMessage2 = "Element is no longer attached to the DOM";

    public CommonPageObject(WebDriver driver) {
        super(driver);
        createElements();
    }

    abstract public void createElements();

    public void input(String elementName, String inputText) {
        try {
            items.get(elementName).setValue(inputText);
        } catch (Exception e) {
            String getCauseMessage = e.getCause().getMessage();
            if (getCauseMessage.contains(errorMessage1) || getCauseMessage.contains(errorMessage2)) {
                input(elementName, inputText);
            } else {
                throw e;
            }
        }
    }

    public void checkElementValue(String checkType, String elementName, String expectedValue) {
        try {
            WebElement element;
            if (checkType.isEmpty()) {
                element = items.get(elementName).getWebElement();
            } else {
                WebElement parent = items.get(checkType).getWebElement();
                element = items.get(elementName).getWebElement(parent);
            }
            commonPage.shouldContainsText(elementName, element, expectedValue);
        } catch (Exception e) {
            String getCauseMessage = e.getCause().getMessage();
            if (getCauseMessage.contains(errorMessage1) || getCauseMessage.contains(errorMessage2)) {
                checkElementValue(checkType, elementName, expectedValue);
            } else {
                throw e;
            }
        }
    }

    public void checkElementValue(String checkType, ExamplesTable checkValuesTable) {
        for (Map<String, String> row : checkValuesTable.getRows()) {
            String elementName = row.get("elementName");
            String expectedValue = row.get("expectedValue");
            checkElementValue(checkType, elementName, expectedValue);
        }
    }

    public void elementShouldBePresent(WebElementFacade webElementFacade) {
        try {
            webElementFacade.shouldBePresent();
        } catch (Exception e) {
            String getExceptionMessage = e.getMessage();
            if (getExceptionMessage.contains(errorMessage1) || getExceptionMessage.contains(errorMessage2)) {
                elementShouldBePresent(webElementFacade);
            } else {
                throw e;
            }
        }
    }

    public WebElement findElement(By by) {
        return waiter.getWebElement(by);
    }
}

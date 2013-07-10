package project.lighthouse.autotests;

import net.thucydides.core.pages.PageObject;
import org.jbehave.core.model.ExamplesTable;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.common.CommonItem;
import project.lighthouse.autotests.common.CommonPage;
import project.lighthouse.autotests.common.CommonView;

import java.util.Map;

public class CommonActions extends PageObject {

    Map<String, CommonItem> items;

    private String errorMessage1 = "Element not found in the cache - perhaps the page has changed since it was looked up";
    private String errorMessage2 = "Element is no longer attached to the DOM";
    private String errorMessage3 = "Element does not exist in cache";

    protected CommonPage commonPage = new CommonPage(getDriver());
    protected Waiter waiter = new Waiter(getDriver());

    public CommonActions(WebDriver driver, Map<String, CommonItem> items) {
        super(driver);
        this.items = items;
    }

    public void input(String elementName, String inputText) {
        try {
            items.get(elementName).setValue(inputText);
        } catch (Exception e) {
            String getExceptionMessage = e.getCause() != null
                    ? e.getCause().getMessage()
                    : e.getMessage();
            if (getExceptionMessage.contains(errorMessage1) || getExceptionMessage.contains(errorMessage2)) {
                input(elementName, inputText);
            } else {
                throw e;
            }
        }
    }

    public void checkElementValue(String checkType, String elementName, String expectedValue) {
        try {
            WebElement element;
            By findBy;
            if (checkType.isEmpty()) {
                findBy = items.get(elementName).getFindBy();
                element = waiter.getVisibleWebElement(findBy);
            } else {
                By parentFindBy = items.get(checkType).getFindBy();
                WebElement parent = waiter.getVisibleWebElement(parentFindBy);
                element = items.get(elementName).getWebElement(parent);
            }
            commonPage.shouldContainsText(elementName, element, expectedValue);
        } catch (Exception e) {
            String getExceptionMessage = e.getCause() != null
                    ? e.getCause().getMessage()
                    : e.getMessage();
            if (getExceptionMessage.contains(errorMessage1) || getExceptionMessage.contains(errorMessage2)) {
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

    public void elementShouldBeVisible(String value, CommonView commonView) {
        WebElement element = commonView.getWebElementItem(value);
        try {
            waiter.getVisibleWebElement(element);
        } catch (Exception e) {
            String getExceptionMessage = e.getCause() != null
                    ? e.getCause().getMessage()
                    : e.getMessage();
            if (getExceptionMessage.contains(errorMessage1) || getExceptionMessage.contains(errorMessage2) || getExceptionMessage.contains(errorMessage3)) {
                elementShouldBeVisible(value, commonView);
            } else {
                throw e;
            }
        }
    }

    public void elementClick(String elementName) {
        By findBy = items.get(elementName).getFindBy();
        try {
            waiter.getVisibleWebElement(findBy).click();
        } catch (Exception e) {
            String getExceptionMessage = e.getCause() != null
                    ? e.getCause().getMessage()
                    : e.getMessage();
            if (getExceptionMessage.contains(errorMessage1) || getExceptionMessage.contains(errorMessage2) || getExceptionMessage.contains(errorMessage3)) {
                elementClick(elementName);
            } else {
                throw e;
            }
        }
    }

    public void elementClick(By findBy) {
        try {
            waiter.getVisibleWebElement(findBy).click();
        } catch (Exception e) {
            String getExceptionMessage = e.getCause() != null
                    ? e.getCause().getMessage()
                    : e.getMessage();
            if (getExceptionMessage.contains(errorMessage1) || getExceptionMessage.contains(errorMessage2) || getExceptionMessage.contains(errorMessage3)) {
                elementClick(findBy);
            } else {
                throw e;
            }
        }
    }

    public void presentElementClick(By findBy) {
        try {
            waiter.getPresentWebElement(findBy).click();
        } catch (Exception e) {
            String getExceptionMessage = e.getCause() != null
                    ? e.getCause().getMessage()
                    : e.getMessage();
            if (getExceptionMessage.contains(errorMessage1) || getExceptionMessage.contains(errorMessage2) || getExceptionMessage.contains(errorMessage3)) {
                elementClick(findBy);
            } else {
                throw e;
            }
        }
    }

    public void elementSelect(String value, By findBy) {
        try {
            WebElement element = waiter.getVisibleWebElement(findBy);
            $(element).selectByValue(value);
        } catch (Exception e) {
            String getExceptionMessage = e.getCause() != null
                    ? e.getCause().getMessage()
                    : e.getMessage();
            if (getExceptionMessage.contains(errorMessage1) || getExceptionMessage.contains(errorMessage2) || getExceptionMessage.contains(errorMessage3)) {
                elementSelect(value, findBy);
            } else {
                throw e;
            }
        }

    }
}

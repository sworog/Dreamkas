package project.lighthouse.autotests;

import net.thucydides.core.pages.PageObject;
import net.thucydides.core.webdriver.WebDriverFacade;
import org.jbehave.core.model.ExamplesTable;
import org.openqa.selenium.By;
import org.openqa.selenium.Capabilities;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.remote.RemoteWebDriver;
import project.lighthouse.autotests.common.CommonItem;
import project.lighthouse.autotests.common.CommonPage;
import project.lighthouse.autotests.common.CommonView;

import java.util.List;
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

    public CommonActions(WebDriver driver) {
        super(driver);
    }

    public void input(String elementName, String inputText) {
        try {
            defaultInput(elementName, inputText);
        } catch (Exception e) {
            if (isSkippableException(e, false)) {
                input(elementName, inputText);
            } else if (isStrangeFirefoxBehaviour(e)) {
                defaultInput(elementName, inputText);
            } else {
                throw e;
            }
        }
    }

    public void defaultInput(String elementName, String inputText) {
        items.get(elementName).setValue(inputText);
    }

    public void type(By findBy, String inputText) {
        try {
            $(waiter.getVisibleWebElement(findBy)).type(inputText);
        } catch (Exception e) {
            if (isSkippableException(e)) {
                type(findBy, inputText);
            } else {
                throw e;
            }
        }
    }

    public void inputTable(ExamplesTable fieldInputTable) {
        for (Map<String, String> row : fieldInputTable.getRows()) {
            String elementName = row.get("elementName");
            String inputText = row.get("value");
            if (row.containsKey("repeat")) {
                Integer count = Integer.parseInt(row.get("repeat"));
                inputText = commonPage.generateString(count, inputText);
            }
            input(elementName, inputText);
        }
    }

    public void checkElementValue(String elementName, String expectedValue) {
        checkElementValue("", elementName, expectedValue);
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
            if (isSkippableException(e, false)) {
                checkElementValue(checkType, elementName, expectedValue);
            } else {
                throw e;
            }
        }
    }

    public void checkElementText(String elementName, String expectedValue) {
        try {
            WebElement element = items.get(elementName).getOnlyVisibleWebElement();
            commonPage.shouldContainsText(elementName, element, expectedValue);
        } catch (Exception e) {
            if (isSkippableException(e, false)) {
                checkElementText(elementName, expectedValue);
            } else {
                throw e;
            }
        }
    }

    public void checkElementValue(String checkType, List<Map<String, String>> checkValuesList) {
        for (Map<String, String> row : checkValuesList) {
            String elementName = row.get("elementName");
            String expectedValue = row.get("value");
            checkElementValue(checkType, elementName, expectedValue);
        }
    }

    public void checkElementValue(String checkType, ExamplesTable examplesTable) {
        checkElementValue(checkType, examplesTable.getRows());
    }

    public void elementShouldBeVisible(String value, CommonView commonView) {
        WebElement element = commonView.getWebElementItem(value);
        try {
            waiter.getVisibleWebElement(element);
        } catch (Exception e) {
            if (isSkippableException(e)) {
                elementShouldBeVisible(value, commonView);
            } else {
                throw e;
            }
        }
    }

    public void elementClick(String elementName) {
        By findBy = items.get(elementName).getFindBy();
        elementClick(findBy);
    }

    public Capabilities getCapabilities() {
        WebDriverFacade webDriverFacade = (WebDriverFacade) getDriver();
        RemoteWebDriver remoteWebDriver = (RemoteWebDriver) webDriverFacade.getProxiedDriver();
        return remoteWebDriver.getCapabilities();
    }

    public void elementClickByFirefox(By findBy) {
        try {
            List<WebElement> presentWebElements = waiter.getPresentWebElements(findBy);
            if (presentWebElements.size() != 1 && !presentWebElements.isEmpty()) {
                presentWebElements.get(presentWebElements.size() - 1).click();
            } else {
                presentWebElements.get(0).click();
            }
        } catch (Exception e) {
            if (isSkippableException(e)) {
                elementClick(findBy);
            } else {
                throw e;
            }
        }
    }

    public void elementClick(By findBy) {
        try {
            waiter.getVisibleWebElement(findBy).click();
        } catch (Exception e) {
            if (isSkippableException(e)) {
                elementClick(findBy);
            } else {
                throw e;
            }
        }
    }

    public void catalogElementClick(By findBy) {
        try {
            waiter.getOnlyVisibleElementFromTheList(findBy).click();
        } catch (Exception e) {
            if (isSkippableException(e)) {
                elementClick(findBy);
            } else {
                throw e;
            }
        }
    }

    public void selectByValue(String value, By findBy) {
        try {
            WebElement element = waiter.getVisibleWebElement(findBy);
            $(element).selectByValue(value);
        } catch (Exception e) {
            if (isSkippableException(e)) {
                selectByValue(value, findBy);
            } else {
                throw e;
            }
        }
    }

    public void selectByVisibleText(String label, By findBy) {
        try {
            defaultSelectByVisibleText(label, findBy);
        } catch (Exception e) {
            if (isSkippableException(e)) {
                selectByVisibleText(label, findBy);
            } else if (isStrangeFirefoxBehaviour(e)) {
                defaultSelectByVisibleText(label, findBy);
            } else {
                throw e;
            }
        }
    }

    public void defaultSelectByVisibleText(String label, By findBy) {
        WebElement element = waiter.getVisibleWebElement(findBy);
        $(element).selectByVisibleText(label);
    }

    private String getExceptionMessage(Exception e) {
        return e.getCause() != null ? e.getCause().getMessage() : e.getMessage();
    }

    private boolean isSkippableException(Exception e, boolean checkThirdErrorMessage) {
        String exceptionMessage = getExceptionMessage(e);
        return exceptionMessage.contains(errorMessage1)
                || exceptionMessage.contains(errorMessage2)
                || (checkThirdErrorMessage && exceptionMessage.contains(errorMessage3));
    }

    private boolean isSkippableException(Exception e) {
        return isSkippableException(e, true);
    }

    private Boolean isStrangeFirefoxBehaviour(Exception e) {
        String exceptionMessage = getExceptionMessage(e);
        return getCapabilities().getBrowserName().equals("firefox")
                && exceptionMessage.contains("Timed out after");
    }

    public Boolean visibleWebElementHasTagName(String xpath, String expectedTagName) {
        return waiter.getVisibleWebElement(By.xpath(xpath)).getTagName().equals(expectedTagName);
    }

    public Boolean webElementHasTagName(String xpath, String expectedTagName) {
        return waiter.getOnlyVisibleElementFromTheList(By.xpath(xpath)).getTagName().equals(expectedTagName);
    }
}

package project.lighthouse.autotests.common;

import net.thucydides.core.webdriver.WebDriverFacade;
import org.hamcrest.Matchers;
import org.openqa.selenium.Alert;
import org.openqa.selenium.By;
import org.openqa.selenium.Capabilities;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.remote.RemoteWebDriver;
import project.lighthouse.autotests.common.objects.CommonPageObject;

import static junit.framework.Assert.assertEquals;
import static org.junit.Assert.assertThat;

public class CommonActions {

    private static final String ERROR_MESSAGE_1 = "Element not found in the cache - perhaps the page has changed since it was looked up";
    private static final String ERROR_MESSAGE_2 = "Element is no longer attached to the DOM";
    private static final String ERROR_MESSAGE_3 = "Element does not exist in cache";

    private Waiter waiter;

    private CommonPageObject pageObject;

    public CommonActions(CommonPageObject pageObject) {
        this.pageObject = pageObject;
        waiter = new Waiter(pageObject.getDriver());
    }

    public Waiter getWaiter() {
        return waiter;
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
                String message = String.format("Failed with input in element with name '%s' with text '%s': %s", elementName, inputText, e.getMessage());
                throw new AssertionError(message);
            }
        }
    }

    private void defaultInput(String elementName, String inputText) {
        pageObject.getItems().get(elementName).setValue(inputText);
    }

    public Capabilities getCapabilities() {
        WebDriverFacade webDriverFacade = (WebDriverFacade) pageObject.getDriver();
        RemoteWebDriver remoteWebDriver = (RemoteWebDriver) webDriverFacade.getProxiedDriver();
        return remoteWebDriver.getCapabilities();
    }

    public void catalogElementSubmit(By findBy) {
        try {
            waiter.getOnlyVisibleElementFromTheList(findBy).submit();
        } catch (Exception e) {
            if (isSkippableException(e)) {
                elementClick(findBy);
            } else {
                throw e;
            }
        }
    }

    public void elementSubmit(By findBy) {
        try {
            waiter.getVisibleWebElement(findBy).submit();
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
            pageObject.$(element).selectByValue(value);
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

    private void defaultSelectByVisibleText(String label, By findBy) {
        WebElement element = waiter.getVisibleWebElement(findBy);
        pageObject.$(element).selectByVisibleText(label);
    }

    private String getExceptionMessage(Exception e) {
        return e.getCause() != null ? e.getCause().getMessage() : e.getMessage();
    }

    private boolean isSkippableException(Exception e, boolean checkThirdErrorMessage) {
        String exceptionMessage = getExceptionMessage(e);
        return exceptionMessage.contains(ERROR_MESSAGE_1)
                || exceptionMessage.contains(ERROR_MESSAGE_2)
                || (checkThirdErrorMessage && exceptionMessage.contains(ERROR_MESSAGE_3));
    }

    private boolean isSkippableException(Exception e) {
        return isSkippableException(e, true);
    }

    private Boolean isStrangeFirefoxBehaviour(Exception e) {
        String exceptionMessage = getExceptionMessage(e);
        return getCapabilities().getBrowserName().equals("firefox")
                && exceptionMessage.contains("Timed out after");
    }

    public Boolean visibleWebElementHasTagName(By findBy, String expectedTagName) {
        return waiter.getVisibleWebElement(findBy).getTagName().equals(expectedTagName);
    }

    public Boolean webElementHasTagName(By findBy, String expectedTagName) {
        return waiter.getOnlyVisibleElementFromTheList(findBy).getTagName().equals(expectedTagName);
    }

    public void checkDropDownDefaultValue(WebElement dropDownElement, String expectedValue) {
        String selectedValue = pageObject.$(dropDownElement).getSelectedVisibleTextValue();
        assertThat(
                "The dropDawn value:",
                selectedValue, Matchers.containsString(expectedValue)
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
}

package ru.dreamkas.common;

import jline.internal.Nullable;
import org.openqa.selenium.*;
import org.openqa.selenium.support.ui.ExpectedCondition;
import org.openqa.selenium.support.ui.ExpectedConditions;
import org.openqa.selenium.support.ui.WebDriverWait;
import ru.dreamkas.storage.DefaultStorage;

import java.util.List;

public class Waiter {

    private WebDriverWait waiter;
    private WebDriverWait waitForPageLoadWaiter;

    private static final Integer DEFAULT_TIMEOUT = DefaultStorage.getTimeOutConfigurationVariableStorage().getTimeOutProperty("default.timeout");

    private Waiter(WebDriver driver) {
        this(driver, 1);
    }

    private Waiter(WebDriver driver, long waiterTimeOut) {
        waiter = new WebDriverWait(driver, waiterTimeOut);
        waitForPageLoadWaiter = new WebDriverWait(driver, DEFAULT_TIMEOUT);
    }

    public static Waiter getDefaultWaiter(WebDriver driver) {
        return new Waiter(driver);
    }

    public static Waiter getWaiterWithCustomTimeOut(WebDriver driver, long customWaiterTimeOut) {
        return new Waiter(driver, customWaiterTimeOut);
    }

    private void waitPageToLoad() {
        waitForPageLoadWaiter.until(ExpectedConditions.invisibilityOfElementLocated(By.xpath("//*[contains(@class, 'loading')]")));
    }

    public WebElement getPresentWebElement(By findBy) {
        waitPageToLoad();
        return waiter.until(ExpectedConditions.presenceOfElementLocated(findBy));
    }

    public WebElement getVisibleWebElement(By findBy) {
        waitPageToLoad();
        return waiter.until(ExpectedConditions.visibilityOfElementLocated(findBy));
    }

    public WebElement getVisibleWebElement(WebElement element) {
        waitPageToLoad();
        return waiter.until(ExpectedConditions.visibilityOf(element));
    }

    public Boolean visibilityOfElementLocated(final WebElement element) {
        waitPageToLoad();
        try {
            return getVisibleWebElement(element).isDisplayed();
        } catch (TimeoutException | NoSuchElementException e) {
            return false;
        }
    }

    public Boolean visibilityOfElementLocated(By findBy) {
        waitPageToLoad();
        try {
            return getVisibleWebElement(findBy).isDisplayed();
        } catch (TimeoutException | NoSuchElementException e) {
            return false;
        }
    }

    public void waitUntilIsNotVisible(By findBy) {
        waitPageToLoad();
        waiter.until(ExpectedConditions.invisibilityOfElementLocated(findBy));
    }

    public Alert getAlert() {
        waitPageToLoad();
        return waiter.until(ExpectedConditions.alertIsPresent());
    }

    public List<WebElement> getPresentWebElements(By findBy) {
        waitPageToLoad();
        return waiter.until(ExpectedConditions.presenceOfAllElementsLocatedBy(findBy));
    }

    public List<WebElement> getVisibleWebElements(By findBy) {
        waitPageToLoad();
        return waiter.until(ExpectedConditions.visibilityOfAllElementsLocatedBy(findBy));
    }

    public WebElement getOnlyVisibleElementFromTheList(By findBy) {
        waitPageToLoad();
        for (WebElement element : getPresentWebElements(findBy)) {
            if (element.isDisplayed()) {
                return element;
            }
        }
        return null;
    }

    public Boolean invisibilityOfElementLocated(By findBy) {
        waitPageToLoad();
        try {
            return waiter.until(ExpectedConditions.invisibilityOfElementLocated(findBy));
        } catch (NoSuchElementException e) {
            return true;
        } catch (TimeoutException e) {
            return false;
        }
    }

    public Boolean invisibilityOfElementLocated(WebElement element) {
        waitPageToLoad();
        try {
            return waiter.until(ExpectedConditions.not(ExpectedConditions.visibilityOf(element)));
        } catch (NoSuchElementException e) {
            return true;
        } catch (TimeoutException e) {
            return false;
        }
    }

    public Boolean invisibilityOfElementLocated(final WebElement parentElement, final By childFindBy) {
        waitPageToLoad();
        try {
            return waiter.until(new ExpectedCondition<Boolean>() {
                @Override
                public Boolean apply(@Nullable org.openqa.selenium.WebDriver input) {
                    try {
                        return !(parentElement.findElement(childFindBy).isDisplayed());
                    } catch (NoSuchElementException e) {
                        return true;
                    } catch (StaleElementReferenceException e) {
                        return true;
                    }
                }

                @Override
                public String toString() {
                    return String.format("element to no longer be visible: %s", childFindBy);
                }
            });
        } catch (Exception e) {
            return false;
        }
    }
}

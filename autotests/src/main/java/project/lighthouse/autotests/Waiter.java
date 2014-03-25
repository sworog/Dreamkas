package project.lighthouse.autotests;

import jline.internal.Nullable;
import org.openqa.selenium.*;
import org.openqa.selenium.support.ui.ExpectedCondition;
import org.openqa.selenium.support.ui.ExpectedConditions;
import org.openqa.selenium.support.ui.WebDriverWait;

import java.util.List;

public class Waiter {

    private WebDriverWait waiter;

    public Waiter(WebDriver driver) {
        this(driver, StaticData.DEFAULT_TIMEOUT);
    }

    public Waiter(WebDriver driver, long timeout) {
        waiter = new WebDriverWait(driver, timeout);
    }

    public WebElement getPresentWebElement(By findBy) {
        return waiter.until(ExpectedConditions.presenceOfElementLocated(findBy));
    }

    public WebElement getVisibleWebElement(By findBy) {
        return waiter.until(ExpectedConditions.visibilityOfElementLocated(findBy));
    }

    public WebElement getVisibleWebElement(WebElement element) {
        return waiter.until(ExpectedConditions.visibilityOf(element));
    }

    public Boolean visibilityOfElementLocated(final WebElement element) {
        try {
            return getVisibleWebElement(element).isDisplayed();
        } catch (TimeoutException | NoSuchElementException e) {
            return false;
        }
    }

    public void waitUntilIsNotVisible(By findBy) {
        waiter.until(ExpectedConditions.invisibilityOfElementLocated(findBy));
    }

    public Alert getAlert() {
        return waiter.until(ExpectedConditions.alertIsPresent());
    }

    public List<WebElement> getPresentWebElements(By findBy) {
        return waiter.until(ExpectedConditions.presenceOfAllElementsLocatedBy(findBy));
    }

    public List<WebElement> getVisibleWebElements(By findBy) {
        return waiter.until(ExpectedConditions.visibilityOfAllElementsLocatedBy(findBy));
    }

    public WebElement getOnlyVisibleElementFromTheList(By findBy) {
        for (WebElement element : getPresentWebElements(findBy)) {
            if (element.isDisplayed()) {
                return element;
            }
        }
        return null;
    }

    public Boolean invisibilityOfElementLocated(By findBy) {
        try {
            return waiter.until(ExpectedConditions.invisibilityOfElementLocated(findBy));
        } catch (Exception e) {
            return false;
        }
    }

    public Boolean invisibilityOfElementLocated(WebElement element) {
        try {
            return waiter.until(ExpectedConditions.not(ExpectedConditions.visibilityOf(element)));
        } catch (NoSuchElementException | TimeoutException e) {
            return true;
        }
    }

    public Boolean isElementVisible(By findBy) {
        try {
            return getPresentWebElement(findBy).isDisplayed();
        } catch (Exception e) {
            return false;
        }
    }

    public Boolean invisibilityOfElementLocated(final WebElement parentElement, final By childFindBy) {
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

    public WebElement elementToBeClickable(By findBy) {
        return waiter.until(ExpectedConditions.elementToBeClickable(findBy));
    }

    public WebElement elementToBeClickable(WebElement element) {
        return waiter.until(ExpectedConditions.elementToBeClickable(element));
    }
}

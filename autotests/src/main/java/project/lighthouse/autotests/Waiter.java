package project.lighthouse.autotests;

import org.openqa.selenium.Alert;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.ui.ExpectedConditions;
import org.openqa.selenium.support.ui.WebDriverWait;

import java.util.List;

public class Waiter {

    WebDriver driver;
    WebDriverWait waiter;

    public Waiter(WebDriver driver) {
        this(driver, StaticData.TIMEOUT / 1000);
    }

    public Waiter(WebDriver driver, long timeout) {
        this.driver = driver;
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
}

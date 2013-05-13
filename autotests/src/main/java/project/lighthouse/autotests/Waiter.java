package project.lighthouse.autotests;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.ui.ExpectedConditions;
import org.openqa.selenium.support.ui.WebDriverWait;

public class Waiter {

    WebDriver driver;
    WebDriverWait waiter = new WebDriverWait(driver, Integer.parseInt(StaticDataCollections.TIMEOUT) / 1000);

    public Waiter(WebDriver driver) {
        this.driver = driver;
    }

    public WebElement getPresentWebElement(By findBy) {
        return waiter.until(ExpectedConditions.presenceOfElementLocated(findBy));
    }

    public WebElement getVisibleWebElement(By findBy) {
        return waiter.until(ExpectedConditions.visibilityOfElementLocated(findBy));
    }

    public void waitUntilIsNotPresent(By findBy) {
        waiter.until(ExpectedConditions.invisibilityOfElementLocated(findBy));
    }
}

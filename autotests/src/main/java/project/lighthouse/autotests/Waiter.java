package project.lighthouse.autotests;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.ui.ExpectedConditions;
import org.openqa.selenium.support.ui.WebDriverWait;

public class Waiter {

    WebDriver driver;

    public Waiter(WebDriver driver) {
        this.driver = driver;
    }

    public WebElement getFluentWaitWebElement(final By by) {
        return new WebDriverWait(driver, Integer.parseInt(StaticDataCollections.TIMEOUT) / 100).until(ExpectedConditions.visibilityOfElementLocated(by));
    }

    public WebElement getWebElement(By findBy) {
        return getFluentWaitWebElement(findBy);
    }
}

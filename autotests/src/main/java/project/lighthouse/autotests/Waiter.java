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
        return new WebDriverWait(driver, 10).until(ExpectedConditions.visibilityOfElementLocated(by));
       /* return new FluentWait<>(driver)
                // Waiting StaticDataCollections.TIMEOUT seconds for an element to be present on the page, checking
                // for its presence once every 1 seconds.
                .withTimeout(Integer.parseInt(StaticDataCollections.TIMEOUT), TimeUnit.MILLISECONDS)
                .pollingEvery(100, TimeUnit.MILLISECONDS)
                .ignoring(Throwable.class)
                .until(new Function<WebDriver, WebElement>() {
                    public WebElement apply(WebDriver driver) {
                        return driver.findElement(by);
                    }
                });*/
    }

    public WebElement getWebElement(By findBy) {
        return getFluentWaitWebElement(findBy);
    }
}

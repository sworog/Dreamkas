package project.lighthouse.autotests;

import com.google.common.base.Function;
import net.thucydides.core.webdriver.WebdriverAssertionError;
import org.openqa.selenium.By;
import org.openqa.selenium.NoSuchElementException;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.ui.ExpectedConditions;
import org.openqa.selenium.support.ui.FluentWait;

import java.util.concurrent.TimeUnit;

public class Waiter {

    WebDriver driver;

    public Waiter(WebDriver driver) {
        this.driver = driver;
    }

    public WebElement getFluentWaitWebElement(final By by) {
        return new FluentWait<>(driver)
                // Waiting StaticDataCollections.TIMEOUT seconds for an element to be present on the page, checking
//                // for its presence once every 1 seconds.
                .withTimeout(Integer.parseInt(StaticDataCollections.TIMEOUT), TimeUnit.MILLISECONDS)
                .pollingEvery(1, TimeUnit.SECONDS)
                .ignoring(NoSuchElementException.class)
                .ignoring(WebdriverAssertionError.class)
                .until(new Function<WebDriver, WebElement>() {
                    public WebElement apply(WebDriver driver) {
                        return driver.findElement(by);
                    }
                });
    }

    public WebElement getWebElement(By findBy) {
        return getFluentWaitWebElement(findBy);
    }

    public void checkElementIsNotVisible(final By findBy) {
        new FluentWait<>(driver)
                // Waiting 2 seconds for an element to be present on the page, checking
//                // for its presence once every 1 seconds.
                .withTimeout(2, TimeUnit.SECONDS)
                .pollingEvery(1, TimeUnit.SECONDS)
                .ignoring(NoSuchElementException.class)
                .until(ExpectedConditions.invisibilityOfElementLocated(findBy));
    }
}

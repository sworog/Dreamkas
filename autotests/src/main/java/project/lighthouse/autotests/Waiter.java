package project.lighthouse.autotests;

import com.google.common.base.Function;
import org.openqa.selenium.By;
import org.openqa.selenium.NoSuchElementException;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.ui.FluentWait;
import org.openqa.selenium.support.ui.Wait;

import java.util.concurrent.TimeUnit;

public class Waiter {

    WebDriver driver;
    Wait<WebDriver> wait;

    public Waiter(WebDriver driver) {
        this.driver = driver;
        wait = new FluentWait<>(driver)
                // Waiting 10 seconds for an element to be present on the page, checking
                // for its presence once every 2 seconds.
                .withTimeout(10, TimeUnit.SECONDS)
                .pollingEvery(2, TimeUnit.SECONDS)
                .ignoring(NoSuchElementException.class);
    }

    public WebElement getFluenWaitWebElement(final By by) {
        return wait.until(new Function<WebDriver, WebElement>() {
            public WebElement apply(WebDriver driver) {
                return driver.findElement(by);
            }
        });
    }
}

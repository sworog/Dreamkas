package project.lighthouse.autotests.elements;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.Waiter;

public class PreLoader {

    WebDriver driver;
    Waiter waiter;
    private static final String PRE_LOADER_XPATH = "//*[*[contains(@class, 'preloader_rows')] and *[not(contains(@class, 'preloader_spinner'))]]";

    public PreLoader(WebDriver driver) {
        this.driver = driver;
        waiter = new Waiter(driver, 5);
    }

    public void await() {
        waiter.waitUntilIsNotVisible(By.xpath(PRE_LOADER_XPATH));
    }
}

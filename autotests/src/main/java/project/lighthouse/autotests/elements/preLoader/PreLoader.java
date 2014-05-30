package project.lighthouse.autotests.elements.preLoader;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.StaticData;
import project.lighthouse.autotests.Waiter;

public class PreLoader {

    private Waiter waiter;
    private static final String PRE_LOADER_XPATH = "//*[*[contains(@class, 'preloader_stripes')] and *[not(@status='loading']]";

    public PreLoader(WebDriver driver) {
        waiter = new Waiter(driver, StaticData.DEFAULT_PRE_LOADER_TIMEOUT);
    }

    public PreLoader(WebDriver driver, int seconds) {
        waiter = new Waiter(driver, seconds);
    }

    public void await() {
        waiter.waitUntilIsNotVisible(By.xpath(PRE_LOADER_XPATH));
    }
}

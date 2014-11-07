package ru.dreamkas.elements.preLoader;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import ru.dreamkas.common.Waiter;
import ru.dreamkas.storage.DefaultStorage;

public class PreLoader {

    private Waiter waiter;
    private static final String PRE_LOADER_XPATH = "//*[*[contains(@class, 'loading')] and *[not(@status='loading')]]";

    public PreLoader(WebDriver driver) {
        Integer defaultPreloaderTimeOut =
                DefaultStorage.getTimeOutConfigurationVariableStorage().getTimeOutProperty("default.preloader.timeout");
        waiter = new Waiter(driver, defaultPreloaderTimeOut);
    }

    public PreLoader(WebDriver driver, int seconds) {
        waiter = new Waiter(driver, seconds);
    }

    public void await() {
        waiter.waitUntilIsNotVisible(By.xpath(PRE_LOADER_XPATH));
    }
}

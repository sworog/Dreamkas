package ru.dreamkas.elements.preLoader.abstraction;


import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import ru.dreamkas.common.Waiter;
import ru.dreamkas.storage.DefaultStorage;

public abstract class AbstractPreLoader {

    private Waiter waiter;

    public AbstractPreLoader(WebDriver driver) {
        Integer defaultPreloaderTimeOut =
                DefaultStorage.getTimeOutConfigurationVariableStorage().getTimeOutProperty("default.preloader.timeout");
        waiter = new Waiter(driver, defaultPreloaderTimeOut);
    }

    public void await() {
        waiter.waitUntilIsNotVisible(By.xpath(getXpath()));
    }

    public abstract String getXpath();
}

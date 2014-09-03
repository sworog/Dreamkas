package project.lighthouse.autotests.elements.preLoader.abstraction;


import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.Waiter;
import project.lighthouse.autotests.storage.Storage;

public abstract class AbstractPreLoader {

    private Waiter waiter;

    public AbstractPreLoader(WebDriver driver) {
        Integer defaultPreloaderTimeOut =
                Storage.getConfigurationVariableStorage().getTimeOutProperty("default.preloader.timeout");
        waiter = new Waiter(driver, defaultPreloaderTimeOut);
    }

    public void await() {
        waiter.waitUntilIsNotVisible(By.xpath(getXpath()));
    }

    public abstract String getXpath();
}

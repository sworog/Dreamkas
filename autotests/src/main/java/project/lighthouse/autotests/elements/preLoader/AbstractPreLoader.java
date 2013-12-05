package project.lighthouse.autotests.elements.preLoader;


import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.StaticData;
import project.lighthouse.autotests.Waiter;

public abstract class AbstractPreLoader {

    private Waiter waiter;

    public AbstractPreLoader(WebDriver driver) {
        waiter = new Waiter(driver, StaticData.DEFAULT_PRE_LOADER_TIMEOUT);
    }

    public void await() {
        waiter.waitUntilIsNotVisible(By.xpath(getXpath()));
    }

    public abstract String getXpath();
}

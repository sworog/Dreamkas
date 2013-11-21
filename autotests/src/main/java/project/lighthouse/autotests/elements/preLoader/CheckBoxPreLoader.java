package project.lighthouse.autotests.elements.preLoader;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.StaticData;
import project.lighthouse.autotests.Waiter;

public class CheckBoxPreLoader {

    private Waiter waiter;
    private static final String PRE_LOADER_XPATH = "//*[@class='preloader_spinner']";

    public CheckBoxPreLoader(WebDriver driver) {
        waiter = new Waiter(driver, StaticData.DEFAULT_PRE_LOADER_TIMEOUT);
    }

    public void await() {
        waiter.waitUntilIsNotVisible(By.xpath(PRE_LOADER_XPATH));
    }
}

package dreamkas.pageObjects;

import io.appium.java_client.AppiumDriver;
import net.thucydides.core.pages.PageObject;
import net.thucydides.core.webdriver.WebDriverFacade;

public class CommonPageObject extends PageObject {

    public AppiumDriver getAppiumDriver() {
        WebDriverFacade webDriverFacade = (WebDriverFacade)getDriver();
        return  (AppiumDriver)webDriverFacade.getProxiedDriver();
    }

    public String getCurrentActivity() {
        return getAppiumDriver().currentActivity();
    }
}

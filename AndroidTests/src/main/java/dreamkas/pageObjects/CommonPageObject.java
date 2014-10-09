package dreamkas.pageObjects;

import io.appium.java_client.AppiumDriver;
import net.thucydides.core.pages.PageObject;
import net.thucydides.core.webdriver.WebDriverFacade;
import org.openqa.selenium.WebElement;

import java.util.List;

public class CommonPageObject extends PageObject {

    public AppiumDriver getAppiumDriver() {
        WebDriverFacade webDriverFacade = (WebDriverFacade)getDriver();
        return  (AppiumDriver)webDriverFacade.getProxiedDriver();
    }

    public String getCurrentActivity() {
        return getAppiumDriver().currentActivity();
    }

    public void closeApp() {
        getAppiumDriver().closeApp();
    }

    public void launchApp() {
        getAppiumDriver().launchApp();
    }

    public void clickOnElementWithText(List<WebElement> webElements, String text) {
        for (WebElement webElement : webElements) {
            if (webElement.getText().equals(text)) {
                webElement.click();
                break;
            }
        }
        // TODO throw exception if not clicked
    }
}

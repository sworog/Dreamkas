package ru.dreamkas.pageObjects;

import net.thucydides.core.pages.PageObject;
import net.thucydides.core.webdriver.WebDriverFacade;

import org.openqa.selenium.WebDriver;

import java.util.HashMap;
import java.util.Map;

import io.appium.java_client.android.AndroidDriver;
import ru.dreamkas.pageObjects.elements.interfaces.Elementable;

public class CommonPageObject extends PageObject {

    public CommonPageObject(WebDriver driver) {
        super(driver, 10000);
    }

    private Map<String,Elementable> elements = new HashMap<String, Elementable>();

    public Map<String, Elementable> getElements() {
        return elements;
    }

    public void putElementable(String elementName, Elementable elementable) {
        elements.put(elementName, elementable);
    }

    public AndroidDriver getAppiumDriver() {
        WebDriverFacade webDriverFacade = (WebDriverFacade)getDriver();
        return  (AndroidDriver)webDriverFacade.getProxiedDriver();
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

}

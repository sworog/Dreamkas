package ru.dreamkas.pages;

import io.appium.java_client.ios.IOSDriver;
import net.thucydides.core.pages.PageObject;
import net.thucydides.core.webdriver.WebDriverFacade;
import ru.dreamkas.elements.interfaces.Elementable;

import java.util.HashMap;
import java.util.Map;

public abstract class AbstractPageObject extends PageObject {

    private Map<String, Elementable> elementableMap = new HashMap<String, Elementable>();

    public abstract void createElements();

    public AbstractPageObject() {
        createElements();
    }

    public void putElement(String elementName, Elementable elementable) {
        elementableMap.put(elementName, elementable);
    }

    public IOSDriver getAppiumDriver() {
        WebDriverFacade webDriverFacade = (WebDriverFacade)getDriver();
        return  (IOSDriver)webDriverFacade.getProxiedDriver();
    }

    public Elementable getElementableByName(String name) {
        return elementableMap.get(name);
    }
}

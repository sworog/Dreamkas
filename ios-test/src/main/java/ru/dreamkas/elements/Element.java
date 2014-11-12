package ru.dreamkas.elements;

import io.appium.java_client.ios.IOSDriver;
import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import ru.dreamkas.pages.AbstractPageObject;

public class Element {

    private By findBy;
    private AbstractPageObject pageObject;

    public Element(AbstractPageObject pageObject, String id) {
        findBy = By.id(id);
        this.pageObject = pageObject;
    }

    public IOSDriver getAppiumDriver() {
       return pageObject.getAppiumDriver();
    }

    public WebElement getElement() {
        return getAppiumDriver().findElement(findBy);
    }
}

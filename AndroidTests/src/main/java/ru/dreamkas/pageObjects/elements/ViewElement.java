package ru.dreamkas.pageObjects.elements;

import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;

import java.util.NoSuchElementException;

import io.appium.java_client.android.AndroidDriver;
import ru.dreamkas.pageObjects.CommonPageObject;
import ru.dreamkas.pageObjects.elements.interfaces.Elementable;

public class ViewElement implements Elementable {

    private long timeoutInMilliseconds = 10000;
    private CommonPageObject commonPageObject;
    private By findBy;
    private WebElement element;

    public ViewElement(CommonPageObject commonPageObject, String id) {
        this.commonPageObject = commonPageObject;
        findBy = By.id("ru.dreamkas.pos.debug:id/" + id);
        getCommonPageObject().setWaitForTimeout(10000);
    }

    public ViewElement(CommonPageObject commonPageObject, String customPackage, String id) {
        this.commonPageObject = commonPageObject;
        findBy = By.id(customPackage + id);
        getCommonPageObject().setWaitForTimeout(10000);
    }

    public CommonPageObject getCommonPageObject() {
        return commonPageObject;
    }

    public AndroidDriver getAppiumDriver() {
        return commonPageObject.getAppiumDriver();
    }

    protected WebElement getElement() {
        long startTime = System.currentTimeMillis();
        while(element == null && (System.currentTimeMillis()-startTime) < timeoutInMilliseconds)
        {
            try {
                element = getCommonPageObject().getAppiumDriver().findElement(findBy);
            }catch (Exception ex){}
        }

        return element;
    }
}

package ru.dreamkas.pageObjects.elements;

import org.openqa.selenium.By;

import io.appium.java_client.android.AndroidDriver;
import ru.dreamkas.pageObjects.CommonPageObject;

public class ViewElement {

    private CommonPageObject commonPageObject;
    private By findBy;

    public ViewElement(CommonPageObject commonPageObject, String id) {
        this.commonPageObject = commonPageObject;
        findBy = By.id("ru.dreamkas.pos.debug:id/" + id);
    }

    public ViewElement(CommonPageObject commonPageObject, String customPackage, String id) {
        this.commonPageObject = commonPageObject;
        findBy = By.id(customPackage + id);
    }

    public CommonPageObject getCommonPageObject() {
        return commonPageObject;
    }

    public AndroidDriver getAppiumDriver() {
        return commonPageObject.getAppiumDriver();
    }

    public By getFindBy() {
        return findBy;
    }
}

package ru.dreamkas.pageObjects.elements;

import ru.dreamkas.pageObjects.CommonPageObject;
import ru.dreamkas.pageObjects.elements.interfaces.Settable;

public class Input extends TextView implements Settable {

    public Input(CommonPageObject commonPageObject, String id) {
        super(commonPageObject, id);
    }

    @Override
    public void set(String value) {
        getCommonPageObject().getAppiumDriver().findElement(getFindBy()).sendKeys(value);
    }
}

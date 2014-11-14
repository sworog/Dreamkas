package ru.dreamkas.pageObjects.elements;

import io.appium.java_client.MobileElement;
import io.appium.java_client.android.AndroidElement;
import ru.dreamkas.pageObjects.CommonPageObject;
import ru.dreamkas.pageObjects.elements.interfaces.Settable;

public class Input extends TextView implements Settable {

    public Input(CommonPageObject commonPageObject, String id) {
        super(commonPageObject, id);
    }

    @Override
    public void set(String value) {
        //getElement().clear();
        getElement().sendKeys(value);
        //((MobileElement)getElement()).sendKeys(value);

    }
}



package ru.dreamkas.pageObjects.elements;

import ru.dreamkas.pageObjects.CommonPageObject;
import ru.dreamkas.pageObjects.elements.interfaces.Clickable;

public class Button extends ViewElement implements Clickable {

    public Button(CommonPageObject commonPageObject, String id) {
        super(commonPageObject, id);
    }

    @Override
    public void click() {
        getCommonPageObject().getAppiumDriver().findElement(getFindBy()).click();
    }
}

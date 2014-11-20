package ru.dreamkas.pageObjects.elements;

import ru.dreamkas.pageObjects.CommonPageObject;
import ru.dreamkas.pageObjects.elements.interfaces.Clickable;
import ru.dreamkas.pageObjects.elements.interfaces.Gettable;

public class Button extends ViewElement implements Clickable, Gettable {

    public Button(CommonPageObject commonPageObject, String id) {
        super(commonPageObject, id);
    }

    @Override
    public void click() {
        getElement().click();
    }

    @Override
    public Boolean isEnabled() {
        return getElement().isEnabled();
    }

    @Override
    public String getText() {
        return getElement().getText();
    }
}

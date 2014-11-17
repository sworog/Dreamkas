package ru.dreamkas.elements;

import ru.dreamkas.elements.interfaces.Clickable;
import ru.dreamkas.pages.AbstractPageObject;

public class Button extends Element implements Clickable {

    public Button(AbstractPageObject pageObject, String id) {
        super(pageObject, id);
    }

    @Override
    public void click() {
        getElement().click();
    }
}

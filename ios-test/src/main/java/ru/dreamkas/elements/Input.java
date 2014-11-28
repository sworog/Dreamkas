package ru.dreamkas.elements;

import ru.dreamkas.elements.interfaces.Inputtable;
import ru.dreamkas.pages.AbstractPageObject;

public class Input extends Element implements Inputtable{

    public Input(AbstractPageObject pageObject, String id) {
        super(pageObject, id);
    }

    @Override
    public void set(String value) {
        getElement().sendKeys(value);
    }
}

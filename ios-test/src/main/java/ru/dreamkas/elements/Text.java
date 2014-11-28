package ru.dreamkas.elements;

import ru.dreamkas.elements.interfaces.Gettable;
import ru.dreamkas.pages.AbstractPageObject;

public class Text extends Element implements Gettable {

    public Text(AbstractPageObject pageObject, String id) {
        super(pageObject, id);
    }

    @Override
    public String getText() {
        return getElement().getText();
    }
}

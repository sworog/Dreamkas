package ru.dreamkas.elements.items;

import org.openqa.selenium.By;
import ru.dreamkas.common.pageObjects.CommonPageObject;

public class Textarea extends Input {

    public Textarea(CommonPageObject pageObject, By findBy) {
        super(pageObject, findBy);
    }

    public Textarea(CommonPageObject pageObject, String name) {
        super(pageObject, name);
    }

    @Override
    public int length() {
        return $().getValue().length();
    }
}

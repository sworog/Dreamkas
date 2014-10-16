package ru.dreamkas.elements.items;

import org.openqa.selenium.By;
import ru.dreamkas.common.item.CommonItem;
import ru.dreamkas.common.pageObjects.CommonPageObject;

public class SelectByValue extends CommonItem {

    public SelectByValue(CommonPageObject pageObject, String name) {
        super(pageObject, name);
    }

    public SelectByValue(CommonPageObject pageObject, By findBy) {
        super(pageObject, findBy);
    }

    @Override
    public void setValue(String value) {
        selectByValue(value);
    }
}

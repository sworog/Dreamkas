package ru.dreamkas.elements.items;


import ru.dreamkas.common.item.CommonItem;
import ru.dreamkas.common.pageObjects.CommonPageObject;

public class InputOnlyVisible extends CommonItem {

    public InputOnlyVisible(CommonPageObject pageObject, String name) {
        super(pageObject, name);
    }

    @Override
    public void setValue(String value) {
        getOnlyVisibleWebElementFacade().type(value);
    }
}

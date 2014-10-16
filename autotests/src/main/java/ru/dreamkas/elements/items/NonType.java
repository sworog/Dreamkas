package ru.dreamkas.elements.items;


import org.openqa.selenium.By;
import ru.dreamkas.common.item.CommonItem;
import ru.dreamkas.common.pageObjects.CommonPageObject;
import ru.dreamkas.pages.modal.ModalWindowPage;

import static junit.framework.Assert.fail;

public class NonType extends CommonItem {

    public NonType(CommonPageObject pageObject, By findBy) {
        super(pageObject, findBy);
    }

    public NonType(CommonPageObject pageObject, String name) {
        super(pageObject, name);
    }

    public NonType(ModalWindowPage modalWindowPage, String xpath) {
        super(modalWindowPage, xpath);
    }

    @Override
    public void setValue(String value) {
        fail(
                String.format("Can't setValue() with '%s' common item type", "NonType")
        );
    }
}

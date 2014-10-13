package ru.dreamkas.elements.items;

import org.openqa.selenium.By;
import ru.dreamkas.common.item.CommonItem;
import ru.dreamkas.pages.modal.ModalWindowPage;

public class SelectByLabel extends CommonItem {

    public SelectByLabel(ModalWindowPage modalWindowPage, String name) {
        super(modalWindowPage, name);
    }

    @Override
    public void setValue(String value) {
        //FIX ME -> harcoded name in xpath pattern
        String xpathPattern = String.format("//label[input[@name='payment.type'] and *[normalize-space(text())='%s']]", value);
        By label = By.xpath(xpathPattern);
        getPageObject().click(label);
    }
}

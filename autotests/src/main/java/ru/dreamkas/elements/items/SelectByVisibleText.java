package ru.dreamkas.elements.items;

import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import ru.dreamkas.common.item.CommonItem;
import ru.dreamkas.common.pageObjects.CommonPageObject;
import ru.dreamkas.pages.modal.ModalWindowPage;

public class SelectByVisibleText extends CommonItem {

    public SelectByVisibleText(CommonPageObject pageObject, By findBy) {
        super(pageObject, findBy);
    }

    public SelectByVisibleText(CommonPageObject pageObject, String name) {
        super(pageObject, name);
    }

    public SelectByVisibleText(ModalWindowPage modalWindowPage, String xpath) {
        super(modalWindowPage, xpath);
    }

    public SelectByVisibleText(CommonPageObject pageObject, String name, String label) {
        super(pageObject, name, label);
    }

    @Override
    public void setValue(String label) {
        selectByVisibleText(label);
    }

    @Override
    public String getText() {
        return getVisibleWebElementFacade().getSelectedVisibleTextValue().trim();
    }

    public Boolean containsOption(String value) {
        WebElement element = getPageObject().getWaiter().getVisibleWebElement(getFindBy());
        return getPageObject().$(element).containsSelectOption(value);
    }
}

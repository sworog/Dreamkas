package ru.dreamkas.elements.items.autocomplete;

import org.openqa.selenium.By;
import ru.dreamkas.collection.posAutoComplete.PosAutoCompleteCollection;
import ru.dreamkas.common.item.CommonItem;
import ru.dreamkas.common.pageObjects.CommonPageObject;
import ru.dreamkas.elements.bootstrap.SimplePreloader;

public class PosAutoComplete extends CommonItem {

    public PosAutoComplete(CommonPageObject pageObject, By findBy) {
        super(pageObject, findBy);
    }

    @Override
    public void setValue(String value) {
        if (value.startsWith("!")) {
            value = value.substring(1);
            typeAndWaitForPreloader(value);
        } else if (value.equals("#clear")) {
            getPageObject().findVisibleElement(By.xpath("//*[contains(@class, 'fa fa-times')]")).click();
        } else {
            typeAndWaitForPreloader(value);
            new PosAutoCompleteCollection(getPageObject().getDriver()).clickByLocator(value);
        }
    }

    private void typeAndWaitForPreloader(String value) {
        getVisibleWebElementFacade().type(value);
        new SimplePreloader(getPageObject().getDriver()).await();
    }
}

package ru.dreamkas.elements.items.autocomplete;

import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import ru.dreamkas.collection.product.autocomplete.GroupAutoCompleteResultCollection;
import ru.dreamkas.common.item.CommonItem;
import ru.dreamkas.pages.modal.ModalWindowPage;

public class AutoComplete extends CommonItem {

    public AutoComplete(ModalWindowPage modalWindowPage, String xpath) {
        super(modalWindowPage, xpath);
    }

    @Override
    public void setValue(String value) {
        if (value.startsWith("!")) {
            value = value.substring(1);
            searchInputType(value);
        } else if (value.startsWith("#")) {
            value = value.substring(1);
            searchInputType(value);
            new GroupAutoCompleteResultCollection(getPageObject().getDriver())
                    .clickByLocator("Создать группу " + value);
        } else {
            searchInputType(value);
            new GroupAutoCompleteResultCollection(getPageObject().getDriver())
                    .clickByLocator(value);
        }
    }

    private void searchInputType(String value) {
        getVisibleWebElementFacade().click();
        WebElement searchInput = getPageObject().findOnlyVisibleWebElementFromTheWebElementsList(By.xpath("//*[contains(@id, '_search')]"));
        getPageObject().$(searchInput).type(value);
    }
}

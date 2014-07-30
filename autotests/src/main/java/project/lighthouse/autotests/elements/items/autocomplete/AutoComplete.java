package project.lighthouse.autotests.elements.items.autocomplete;

import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.common.CommonItem;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.objects.web.product.autocomplete.GroupAutoCompleteResultCollection;

public class AutoComplete extends CommonItem {

    public AutoComplete(CommonPageObject pageObject, By findBy) {
        super(pageObject, findBy);
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

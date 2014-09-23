package project.lighthouse.autotests.elements.items.autocomplete;

import org.openqa.selenium.By;
import project.lighthouse.autotests.collection.posAutoComplete.PosAutoCompleteCollection;
import project.lighthouse.autotests.common.item.CommonItem;
import project.lighthouse.autotests.common.objects.CommonPageObject;
import project.lighthouse.autotests.elements.bootstrap.SimplePreloader;

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

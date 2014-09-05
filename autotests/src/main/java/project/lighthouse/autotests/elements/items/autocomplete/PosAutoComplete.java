package project.lighthouse.autotests.elements.items.autocomplete;

import org.openqa.selenium.By;
import project.lighthouse.autotests.common.CommonItem;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.bootstrap.SimplePreloader;
import project.lighthouse.autotests.objects.web.posAutoComplete.PosAutoCompleteCollection;

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

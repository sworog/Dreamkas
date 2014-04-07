package project.lighthouse.autotests.elements.items;

import org.openqa.selenium.By;
import project.lighthouse.autotests.common.CommonItem;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.objects.web.autocomplete.AutoCompleteResult;
import project.lighthouse.autotests.objects.web.autocomplete.AutoCompleteResultCollection;

/**
 * Common item for interactions with new autocomplete control
 */
public class NewAutoComplete extends CommonItem {

    public NewAutoComplete(CommonPageObject pageObject, By findBy) {
        super(pageObject, findBy);
    }

    @Override
    public void setValue(String value) {
        if (value.startsWith("!")) {
            value = value.substring(1);
            getVisibleWebElementFacade().type(value);
        } else {
            getOnlyVisibleWebElementFacade().type(value);
            AutoCompleteResult autoCompleteResult =
                    (AutoCompleteResult) new AutoCompleteResultCollection(getPageObject().getDriver(), By.xpath("//*[@class='autocomplete__item autocomplete__item_focused']")).get(0);
            autoCompleteResult.click();
        }
    }
}

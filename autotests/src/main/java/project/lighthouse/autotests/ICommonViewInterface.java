package project.lighthouse.autotests;

import net.thucydides.core.pages.WebElementFacade;
import org.jbehave.core.model.ExamplesTable;
import org.openqa.selenium.WebElement;

public interface ICommonViewInterface {

    void ItemCheck(String value);
    void ItemClick(String value);
    void CheckInvoiceListItemWithSkuHasExpectedValue(String value, String elementName, String expectedValue);
}

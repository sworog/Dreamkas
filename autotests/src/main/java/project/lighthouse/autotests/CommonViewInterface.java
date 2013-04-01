package project.lighthouse.autotests;

import net.thucydides.core.pages.WebElementFacade;
import org.jbehave.core.model.ExamplesTable;
import org.openqa.selenium.WebElement;

public interface CommonViewInterface {

    void itemCheck(String value);
    void itemClick(String value);
    void checkInvoiceListItemWithSkuHasExpectedValue(String value, String elementName, String expectedValue);
}

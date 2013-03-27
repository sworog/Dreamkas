package project.lighthouse.autotests.pages.common;


import net.thucydides.core.pages.PageObject;
import net.thucydides.core.pages.WebElementFacade;
import org.jbehave.core.model.ExamplesTable;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.ICommonViewInterface;

import java.util.Map;

public class ICommonView extends PageObject implements ICommonViewInterface{

    String xpath;
    WebElement element;

    public ICommonView(WebDriver driver, String xpath, WebElement element) {
        super(driver);
        this.xpath = xpath;
        this.element = element;
    }

    public WebElementFacade GetItem(String value){
        String getXpath = String.format(xpath, value);
        return $(element).findBy(getXpath);
    }

    public void ItemCheck(String value){
        WebElementFacade listItem = GetItem(value);
        listItem.shouldBePresent();
    }

    public void ItemClick(String value){
        WebElementFacade listItem = GetItem(value);
        listItem.click();
    }

    public void CheckInvoiceListItemWithSkuHasExpectedValue(String value, String elementName, String expectedValue){
        ItemCheck(value);
        WebElementFacade listItem = GetItem(value);
        listItem.findBy(By.name(elementName)).shouldContainText(expectedValue);
    }
}

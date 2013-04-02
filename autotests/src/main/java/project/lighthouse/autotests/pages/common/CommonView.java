package project.lighthouse.autotests.pages.common;


import net.thucydides.core.pages.PageObject;
import net.thucydides.core.pages.WebElementFacade;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.CommonViewInterface;

public class CommonView extends PageObject implements CommonViewInterface {

    String xpath;
    WebElement element;

    public CommonView(WebDriver driver, String xpath, WebElement element) {
        super(driver);
        this.xpath = xpath;
        this.element = element;
    }

    public WebElementFacade getItem(String value){
        String getXpath = String.format(xpath, value);
        return $(element).findBy(getXpath);
    }

    public void itemCheck(String value){
        WebElementFacade listItem = getItem(value);
        listItem.shouldBePresent();
    }

    public void itemClick(String value){
        WebElementFacade listItem = getItem(value);
        listItem.click();
    }

    public void checkInvoiceListItemWithSkuHasExpectedValue(String value, String elementName, String expectedValue){
        itemCheck(value);
        WebElementFacade listItem = getItem(value);
        listItem.findBy(By.name(elementName)).shouldContainText(expectedValue);
    }
}

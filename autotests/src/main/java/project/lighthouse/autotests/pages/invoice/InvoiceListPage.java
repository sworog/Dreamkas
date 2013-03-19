package project.lighthouse.autotests.pages.invoice;

import com.opera.core.systems.OperaWebElement;
import com.opera.core.systems.testing.drivers.MockOperaDriver;
import net.thucydides.core.annotations.DefaultUrl;
import net.thucydides.core.pages.PageObject;
import net.thucydides.core.pages.WebElementFacade;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

@DefaultUrl("/?invoice/list")
public class InvoiceListPage extends InvoiceCreatePage{

    @FindBy(name = "invoice")
    private WebElement invoiceListItem;

    @FindBy(xpath = "//*[@lh_button='create']")
    private WebElement invoiceListItemCreate;

    public InvoiceListPage(WebDriver driver) {
        super(driver);
    }

    public void  InvoiceListItemCreate(){
        $(invoiceListItemCreate).click();
    }

    public WebElementFacade GetInvoiceListItem(String skuValue){
        String xpath = String.format("//*[@name='invoice']/*[@name='sku' and text()='%s']/..", skuValue);
        return $(invoiceListItem).findBy(xpath);
    }

    public void ListItemClick(String skuValue){
        ListItemCheck(skuValue);
        WebElementFacade listItem = GetInvoiceListItem(skuValue);
        listItem.click();
    }

    public void ListItemCheck(String skuValue){
        WebElementFacade listItem = GetInvoiceListItem(skuValue);
        listItem.shouldBePresent();
    }

    public void CheckInvoiceListItemWithSkuHasExpectedValue(String skuValue, String elementName, String expectedValue){
        ListItemCheck(skuValue);
        WebElementFacade listItem = GetInvoiceListItem(skuValue);
        listItem.findBy(By.name(elementName)).shouldContainText(expectedValue);
    }


}

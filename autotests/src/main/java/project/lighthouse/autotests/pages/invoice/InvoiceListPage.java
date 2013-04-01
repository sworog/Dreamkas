package project.lighthouse.autotests.pages.invoice;

import net.thucydides.core.annotations.DefaultUrl;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import project.lighthouse.autotests.CommonViewInterface;
import project.lighthouse.autotests.pages.common.CommonView;

@DefaultUrl("/?invoice/list")
public class InvoiceListPage extends InvoiceCreatePage{

    @FindBy(name = "invoice")
    private WebElement invoiceListItem;

    @FindBy(xpath = "//*[@lh_button='create']")
    private WebElement invoiceListItemCreate;

    private static final String XPATH = "//*[@name='invoice']/*[@name='sku' and text()='%s']/..";
    CommonViewInterface commonViewInterface = new CommonView(getDriver(), XPATH, invoiceListItem);

    public InvoiceListPage(WebDriver driver) {
        super(driver);
    }

    public void invoiceListItemCreate(){
        $(invoiceListItemCreate).click();
    }

    public void listItemClick(String skuValue){
        commonViewInterface.itemClick(skuValue);
    }

    public void listItemCheck(String skuValue){
        commonViewInterface.itemCheck(skuValue);
    }

    public void checkInvoiceListItemWithSkuHasExpectedValue(String skuValue, String elementName, String expectedValue){
        commonViewInterface.checkInvoiceListItemWithSkuHasExpectedValue(skuValue, elementName, expectedValue);
    }
}
